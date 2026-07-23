import { statusKey } from './ids.js';

/**
 * Sprint-only item dependencies (parallel = empty dependsOn, chain = predecessors).
 */

/**
 * @param {string} key
 * @returns {string|null}
 */
export function sprintIdFromStatusKey(key) {
    const parts = String(key || '').split(':');
    return parts.length >= 4 ? parts[1] : null;
}

/**
 * @param {unknown} raw
 * @returns {string[]}
 */
export function normalizeDependsOnList(raw) {
    if (!Array.isArray(raw)) {
        return [];
    }
    const out = [];
    const seen = new Set();
    for (const entry of raw) {
        const value = String(entry || '').trim();
        if (!value || seen.has(value)) {
            continue;
        }
        seen.add(value);
        out.push(value);
    }
    return out;
}

/**
 * Resolve template local IDs or status keys to sprint-local status keys.
 * @param {unknown} raw
 * @param {{
 *   templateSlug: string,
 *   sprintId: string,
 *   allowedKeys: Set<string>|string[],
 *   selfKey?: string|null,
 * }} opts
 * @returns {string[]}
 */
export function resolveDependsOnKeys(raw, opts) {
    const allowed = opts.allowedKeys instanceof Set
        ? opts.allowedKeys
        : new Set(opts.allowedKeys || []);
    const selfKey = opts.selfKey || null;
    const resolved = [];
    const seen = new Set();

    for (const entry of normalizeDependsOnList(raw)) {
        let key = entry;
        if (!entry.includes(':')) {
            const asTask = statusKey(opts.templateSlug, opts.sprintId, 'task', entry);
            const asDel = statusKey(opts.templateSlug, opts.sprintId, 'deliverable', entry);
            if (allowed.has(asTask)) {
                key = asTask;
            } else if (allowed.has(asDel)) {
                key = asDel;
            } else {
                continue;
            }
        }
        if (!allowed.has(key)) {
            continue;
        }
        if (selfKey && key === selfKey) {
            continue;
        }
        if (sprintIdFromStatusKey(key) !== opts.sprintId) {
            continue;
        }
        if (seen.has(key)) {
            continue;
        }
        seen.add(key);
        resolved.push(key);
    }
    return resolved;
}

/**
 * Pick raw dependsOn from override (wins, including empty) or template/custom item.
 * @param {Record<string, unknown>|null|undefined} stored
 * @param {Record<string, unknown>|null|undefined} templateItem
 * @returns {unknown}
 */
export function pickRawDependsOn(stored, templateItem) {
    if (stored && Object.prototype.hasOwnProperty.call(stored, 'dependsOn')) {
        const raw = stored.dependsOn;
        // Prefer template chains when an override only stored an empty list.
        if (Array.isArray(raw) && raw.length === 0 && templateItem?.dependsOn) {
            return templateItem.dependsOn;
        }
        return raw;
    }
    return templateItem?.dependsOn;
}

/**
 * @param {Map<string, string[]>|Record<string, string[]>} graph
 * @param {string} fromKey
 * @param {string[]} toKeys
 * @returns {boolean}
 */
export function wouldCreateCycle(graph, fromKey, toKeys) {
    const adj = graph instanceof Map
        ? graph
        : new Map(Object.entries(graph || {}));
    const targets = normalizeDependsOnList(toKeys);
    for (const target of targets) {
        if (target === fromKey) {
            return true;
        }
        if (canReach(adj, target, fromKey)) {
            return true;
        }
    }
    return false;
}

/**
 * @param {Map<string, string[]>} adj
 * @param {string} start
 * @param {string} goal
 * @returns {boolean}
 */
function canReach(adj, start, goal) {
    const stack = [start];
    const seen = new Set();
    while (stack.length) {
        const node = stack.pop();
        if (!node || seen.has(node)) {
            continue;
        }
        if (node === goal) {
            return true;
        }
        seen.add(node);
        for (const next of adj.get(node) || []) {
            stack.push(next);
        }
    }
    return false;
}

/**
 * Validate dependsOn for save. Returns sanitized keys or an error code.
 * @param {string} itemKey
 * @param {unknown} rawDependsOn
 * @param {{
 *   sprintId: string,
 *   allowedKeys: Set<string>|string[],
 *   graph: Map<string, string[]>|Record<string, string[]>,
 * }} opts
 * @returns {{ ok: true, dependsOn: string[] }|{ ok: false, error: string }}
 */
export function validateDependsOn(itemKey, rawDependsOn, opts) {
    const allowed = opts.allowedKeys instanceof Set
        ? opts.allowedKeys
        : new Set(opts.allowedKeys || []);
    const dependsOn = normalizeDependsOnList(rawDependsOn).filter((key) => {
        if (!allowed.has(key)) {
            return false;
        }
        if (key === itemKey) {
            return false;
        }
        return sprintIdFromStatusKey(key) === opts.sprintId;
    });

    if (dependsOn.some((key) => key === itemKey)) {
        return { ok: false, error: 'deps-self' };
    }
    if (wouldCreateCycle(opts.graph, itemKey, dependsOn)) {
        return { ok: false, error: 'deps-cycle' };
    }
    return { ok: true, dependsOn };
}

/**
 * Apply effective dependency waiting state on resolved sprint items (mutates in place).
 * Dependencies are sequence hints, not manual blockers: status/blockerReason stay unchanged.
 * @param {Array<{
 *   statusKey: string,
 *   completed: boolean,
 *   status: string,
 *   label: string,
 *   dependsOn?: string[],
 *   blockerReason?: string,
 *   blockerSince?: string|null,
 *   dependencyBlocked?: boolean,
 *   dependencyReason?: string,
 *   storedStatus?: string,
 * }>} items
 * @param {(labels: string[]) => string} formatWaitingOn
 */
export function applySprintDependencyBlocks(items, formatWaitingOn) {
    const byKey = new Map(items.map((item) => [item.statusKey, item]));
    const byId = new Map();
    for (const item of items) {
        const id = String(item.id || '').trim();
        if (id && !byId.has(id)) {
            byId.set(id, item);
        }
    }

    for (const item of items) {
        const deps = Array.isArray(item.dependsOn) ? item.dependsOn : [];
        item.dependsOn = deps;
        item.dependencyBlocked = false;
        item.dependencyReason = '';
        item.storedStatus = item.status;

        if (item.completed || item.status === 'completed' || !deps.length) {
            continue;
        }

        /** @type {typeof items} */
        const openPreds = [];
        for (const depKey of deps) {
            const pred = findDependencyPredecessor(depKey, byKey, byId);
            if (!pred || pred.completed || pred.status === 'completed') {
                continue;
            }
            openPreds.push(pred);
        }
        if (!openPreds.length) {
            continue;
        }

        item.dependencyBlocked = true;
        item.dependencyReason = formatWaitingOn(openPreds.map((p) => p.label));
    }
}

/**
 * @param {string} depKey
 * @param {Map<string, any>} byKey
 * @param {Map<string, any>} byId
 */
function findDependencyPredecessor(depKey, byKey, byId) {
    const key = String(depKey || '').trim();
    if (!key) {
        return null;
    }
    if (byKey.has(key)) {
        return byKey.get(key);
    }
    // Template local ids ("identify-stakeholders") or trailing statusKey segment.
    const localId = key.includes(':') ? key.split(':').pop() : key;
    return byId.get(String(localId || '')) || null;
}

/**
 * Build adjacency map statusKey → dependsOn for a sprint's items.
 * @param {Array<{statusKey: string, dependsOn?: string[]}>} items
 * @returns {Map<string, string[]>}
 */
export function buildDependsOnGraph(items) {
    const graph = new Map();
    for (const item of items) {
        graph.set(item.statusKey, Array.isArray(item.dependsOn) ? [...item.dependsOn] : []);
    }
    return graph;
}

/**
 * Collect allowed status keys for a sprint (template + custom, minus removed).
 * @param {{
 *   templateSlug: string,
 *   sprintId: string,
 *   templateTasks?: Array<{id: string}>,
 *   templateDeliverables?: Array<{id: string}>,
 *   customTasks?: Array<{id: string, statusKey?: string}>,
 *   customDeliverables?: Array<{id: string, statusKey?: string}>,
 *   removedItemKeys?: string[],
 * }} opts
 * @returns {Set<string>}
 */
export function collectSprintItemKeys(opts) {
    const removed = new Set(opts.removedItemKeys || []);
    const keys = new Set();
    for (const task of opts.templateTasks || []) {
        const key = statusKey(opts.templateSlug, opts.sprintId, 'task', task.id);
        if (!removed.has(key)) {
            keys.add(key);
        }
    }
    for (const del of opts.templateDeliverables || []) {
        const key = statusKey(opts.templateSlug, opts.sprintId, 'deliverable', del.id);
        if (!removed.has(key)) {
            keys.add(key);
        }
    }
    for (const task of opts.customTasks || []) {
        const key = task.statusKey || statusKey(opts.templateSlug, opts.sprintId, 'task', task.id);
        keys.add(key);
    }
    for (const del of opts.customDeliverables || []) {
        const key = del.statusKey || statusKey(opts.templateSlug, opts.sprintId, 'deliverable', del.id);
        keys.add(key);
    }
    return keys;
}
