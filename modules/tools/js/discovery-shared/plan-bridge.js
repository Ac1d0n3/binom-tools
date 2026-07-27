/**
 * Plan ↔ discovery tool round-trip via URL context + sessionStorage.
 * Tools stay ephemeral; the Sprint Planner owns persistence.
 */

export const PLAN_TRANSFER_STORAGE_KEY = 'bn-tools:plan-transfer:v1';

/**
 * @typedef {{
 *   instanceId: string,
 *   itemKey: string,
 *   kind: 'task'|'deliverable',
 *   sprintId: string,
 *   custom: boolean,
 *   returnUrl: string,
 * }} PlanLaunchContext
 */

/**
 * @typedef {{
 *   v: 1,
 *   instanceId: string,
 *   itemKey: string,
 *   kind: 'task'|'deliverable',
 *   sprintId: string,
 *   custom: boolean,
 *   markdown?: string,
 *   columns?: Array<{ id: string, label: string }>,
 *   rows?: Array<{ id: string, cells: Record<string, string> }>,
 *   at?: number,
 * }} PlanTransferPayload
 */

/**
 * @param {string} [search]
 * @returns {PlanLaunchContext|null}
 */
export function readPlanLaunchContext(search = typeof window !== 'undefined' ? window.location.search : '') {
    const params = new URLSearchParams(search.startsWith('?') ? search : `?${search}`);
    if (params.get('fromPlan') !== '1') {
        return null;
    }
    const instanceId = String(params.get('instanceId') || '').trim();
    const itemKey = String(params.get('itemKey') || '').trim();
    const kind = String(params.get('kind') || '').trim();
    const sprintId = String(params.get('sprintId') || '').trim();
    const returnUrl = String(params.get('return') || '').trim();
    if (!instanceId || !itemKey || !sprintId || !returnUrl) {
        return null;
    }
    if (kind !== 'task' && kind !== 'deliverable') {
        return null;
    }
    // Only allow same-origin relative return paths.
    if (!returnUrl.startsWith('/') || returnUrl.startsWith('//')) {
        return null;
    }
    return {
        instanceId,
        itemKey,
        kind,
        sprintId,
        custom: params.get('custom') === '1',
        returnUrl,
    };
}

/**
 * @param {PlanTransferPayload} payload
 */
export function writeTransferPayload(payload) {
    if (typeof sessionStorage === 'undefined') {
        return;
    }
    sessionStorage.setItem(PLAN_TRANSFER_STORAGE_KEY, JSON.stringify(payload));
}

/**
 * @returns {PlanTransferPayload|null}
 */
export function peekTransferPayload() {
    if (typeof sessionStorage === 'undefined') {
        return null;
    }
    const raw = sessionStorage.getItem(PLAN_TRANSFER_STORAGE_KEY);
    if (!raw) {
        return null;
    }
    try {
        const data = JSON.parse(raw);
        if (!data || data.v !== 1 || !data.instanceId || !data.itemKey) {
            return null;
        }
        return /** @type {PlanTransferPayload} */ (data);
    } catch {
        return null;
    }
}

/**
 * @returns {PlanTransferPayload|null}
 */
export function consumeTransferPayload() {
    const payload = peekTransferPayload();
    if (typeof sessionStorage !== 'undefined') {
        sessionStorage.removeItem(PLAN_TRANSFER_STORAGE_KEY);
    }
    return payload;
}

/**
 * @param {string} label
 * @returns {string}
 */
export function normalizeColumnKey(label) {
    return String(label || '')
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .replace(/[^a-z0-9]+/g, '')
        .trim();
}

/**
 * Map discovery column ids onto plan table column ids (by id / label).
 * @param {Array<{ id: string, label: string }>} planColumns
 * @param {Array<{ id: string, label: string }>} discoveryColumns
 * @returns {Record<string, string>} planColId → discoveryColId
 */
export function mapDiscoveryColumnsToPlan(planColumns, discoveryColumns) {
    /** @type {Record<string, string>} */
    const map = {};
    const used = new Set();
    const disc = Array.isArray(discoveryColumns) ? discoveryColumns : [];

    for (const planCol of planColumns || []) {
        if (!planCol?.id) {
            continue;
        }
        const planNorm = normalizeColumnKey(planCol.label || planCol.id);
        let match = disc.find((d) => !used.has(d.id) && d.id === planCol.id);
        if (!match) {
            match = disc.find((d) => !used.has(d.id) && normalizeColumnKey(d.label) === planNorm);
        }
        if (!match) {
            match = disc.find((d) => !used.has(d.id) && normalizeColumnKey(d.id) === planNorm);
        }
        if (match) {
            map[planCol.id] = match.id;
            used.add(match.id);
        }
    }
    return map;
}

/**
 * @param {{ columns: Array<{ id: string, label: string }>, rows?: Array<{ id: string, cells: Record<string, string> }> }} itemTable
 * @param {Array<{ id: string, label: string }>} discoveryColumns
 * @param {Array<{ id: string, cells: Record<string, string> }>} discoveryRows
 * @param {(prefix?: string) => string} createRowId
 * @returns {{ columns: Array<{ id: string, label: string }>, rows: Array<{ id: string, cells: Record<string, string> }> }}
 */
export function appendDiscoveryRowsToItemTable(itemTable, discoveryColumns, discoveryRows, createRowId) {
    const columns = itemTable?.columns || [];
    const mapping = mapDiscoveryColumnsToPlan(columns, discoveryColumns);
    const existing = Array.isArray(itemTable?.rows) ? itemTable.rows : [];
    const mapped = (discoveryRows || []).map((row, index) => {
        /** @type {Record<string, string>} */
        const cells = {};
        for (const col of columns) {
            const srcId = mapping[col.id];
            cells[col.id] = srcId ? String(row.cells?.[srcId] ?? '') : '';
        }
        return {
            id: createRowId ? createRowId('row_') : `row_${Date.now()}_${index}`,
            cells,
        };
    });
    return {
        columns,
        rows: [...existing, ...mapped],
    };
}

/**
 * @param {string} existing
 * @param {string} markdown
 * @returns {string}
 */
export function appendMarkdownToNote(existing, markdown) {
    const block = String(markdown || '').trim();
    if (!block) {
        return String(existing || '');
    }
    const base = String(existing || '').trim();
    if (!base) {
        return block;
    }
    return `${base}\n\n---\n\n${block}`;
}
