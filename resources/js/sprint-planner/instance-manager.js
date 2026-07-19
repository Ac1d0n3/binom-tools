import {
    deletePlanOnServer,
    isAccountsMode,
    readAccountsBootstrap,
    usesServerPlans,
} from './accounts-bridge.js';
import { normalizeAttachments } from './attachments.js';
import {
    buildDependsOnGraph,
    collectSprintItemKeys,
    pickRawDependsOn,
    resolveDependsOnKeys,
    validateDependsOn,
} from './dependencies.js';
import { createDeliverableId, createPlanId, createSprintId, createTaskId, statusKey } from './ids.js';
import { resolveSprints } from './progress.js';
import { loadWorkspace, saveWorkspace, clearLastOpenedPlanIdIf } from './storage.js';

/** @type {Map<string, Array<Record<string, unknown>>>} */
const undoStacks = new Map();
const MAX_UNDO = 20;

/**
 * @param {string} instanceId
 * @param {Record<string, unknown>} snapshot
 */
function pushUndoSnapshot(instanceId, snapshot) {
    const stack = undoStacks.get(instanceId) || [];
    stack.push(snapshot);
    while (stack.length > MAX_UNDO) {
        stack.shift();
    }
    undoStacks.set(instanceId, stack);
}

/**
 * @param {string} instanceId
 */
export function canUndoInstance(instanceId) {
    return (undoStacks.get(instanceId) || []).length > 0;
}

/**
 * Restore the previous in-tab plan snapshot (session undo).
 * @param {string} instanceId
 */
export function undoLastInstanceChange(instanceId) {
    const stack = undoStacks.get(instanceId) || [];
    const previous = stack.pop();
    undoStacks.set(instanceId, stack);
    if (!previous) {
        return { ok: false, error: 'nothing-to-undo' };
    }
    const loaded = loadWorkspace();
    const workspace = loaded.data;
    const instance = workspace.instances[instanceId];
    if (!instance) {
        return { ok: false, error: 'plan-missing' };
    }
    const keys = new Set([...Object.keys(instance), ...Object.keys(previous)]);
    for (const key of keys) {
        if (!(key in previous)) {
            delete instance[key];
        } else {
            instance[key] = previous[key];
        }
    }
    instance.updatedAt = new Date().toISOString();
    const saved = saveWorkspace(workspace, {
        dirtyPlanIds: [instanceId],
        historyByPlanId: {
            [instanceId]: { action: 'undo', summary: 'Session undo' },
        },
    });
    return saved.ok ? { ok: true, instance, workspace } : { ok: false, error: saved.error };
}

/**
 * @param {object} template
 * @param {{startedAt: string, teamId?: string|null, teamIds?: string[], participantIds?: string[], ephemeral?: boolean}} options
 */
export function startInstanceFromTemplate(template, options) {
    const loaded = loadWorkspace();
    const workspace = loaded.data;
    const id = createPlanId();
    const now = new Date().toISOString();
    const ephemeral = Boolean(options.ephemeral)
        || (isAccountsMode() && ! usesServerPlans());
    const activePersonId = workspace.workspace.activePersonId || null;
    const teamIds = Array.isArray(options.teamIds) && options.teamIds.length
        ? [...new Set(options.teamIds.map(String).filter(Boolean))]
        : (options.teamId || workspace.workspace.defaultTeamId
            ? [String(options.teamId || workspace.workspace.defaultTeamId)]
            : []);

    let participantIds = Array.isArray(options.participantIds) ? [...options.participantIds] : [];
    if (!participantIds.length) {
        const memberSet = new Set();
        for (const tid of teamIds) {
            for (const mid of workspace.teams[tid]?.memberIds || []) {
                memberSet.add(String(mid));
            }
        }
        if (activePersonId) {
            memberSet.add(activePersonId);
        }
        participantIds = [...memberSet];
    }

    /** @type {import('./storage.js').SpPlanInstance} */
    const instance = {
        id,
        templateSlug: template.slug,
        templateVersion: Number(template.version) || 1,
        translations: {
            de: {
                title: template.locales?.de?.title || template.slug,
                description: template.locales?.de?.description || '',
            },
            en: {
                title: template.locales?.en?.title || template.slug,
                description: template.locales?.en?.description || '',
            },
        },
        startedAt: options.startedAt,
        status: 'active',
        teamIds,
        teamId: null,
        participantIds,
        completedTasks: [],
        completedDeliverables: [],
        fieldValues: {},
        sprintNotes: {},
        customTasks: {},
        customDeliverables: {},
        customSprints: [],
        sprintOverrides: {},
        // Leave template items unassigned so "Claim all" / assign can own them explicitly.
        itemOverrides: {},
        removedItemKeys: [],
        // Always keep a snapshot so the plan stays renderable if the slug is lost or the template catalog fails to load.
        templateSnapshot: structuredCloneSafe(template),
        ownerUserId: usesServerPlans() && ! ephemeral
            ? (readAccountsBootstrap().accountUser?.id || null)
            : null,
        viewerUserIds: [],
        // Plan team members can access by default (server also checks plan.teamIds).
        viewerTeamIds: [...teamIds],
        linkedStorySlugs: [],
        ephemeral,
        archived: false,
        createdAt: now,
        updatedAt: now,
    };

    if (activePersonId && !instance.participantIds.includes(activePersonId)) {
        instance.participantIds.push(activePersonId);
    }

    workspace.instances[id] = instance;
    const saved = saveWorkspace(workspace, { dirtyPlanIds: [id] });
    return saved.ok ? { ok: true, instance } : { ok: false, error: saved.error };
}

/**
 * Assign all template tasks/deliverables to a person via itemOverrides.
 * @param {object} template
 * @param {string} personId
 * @returns {Record<string, Record<string, unknown>>}
 */
export function buildDefaultAssigneeOverrides(template, personId) {
    /** @type {Record<string, Record<string, unknown>>} */
    const overrides = {};
    const slug = template?.slug || 'custom';
    for (const sprint of Array.isArray(template?.sprints) ? template.sprints : []) {
        for (const task of sprint.tasks || []) {
            const key = statusKey(slug, sprint.id, 'task', task.id);
            overrides[key] = {
                assigneeType: 'person',
                assigneeId: personId,
            };
        }
        for (const del of sprint.deliverables || []) {
            const key = statusKey(slug, sprint.id, 'deliverable', del.id);
            overrides[key] = {
                assigneeType: 'person',
                assigneeId: personId,
            };
        }
    }
    return overrides;
}

/**
 * Claim a single item for a person (only if currently unassigned).
 * @param {string} instanceId
 * @param {'task'|'deliverable'} kind
 * @param {string} key
 * @param {boolean} isCustom
 * @param {string} sprintId
 * @param {string} personId
 */
export function claimItem(instanceId, kind, key, isCustom, sprintId, personId) {
    if (!personId) {
        return { ok: false, error: 'active-person-missing' };
    }
    let claimed = false;
    const result = updateInstance(instanceId, (instance) => {
        claimed = assignPersonOnItem(instance, kind, key, isCustom, sprintId, personId, {
            onlyUnassigned: true,
        });
    });
    return result.ok ? { ...result, claimed } : result;
}

/**
 * Claim every currently unassigned item on the plan.
 * Re-resolves assignees from live plan data so already-assigned tasks are never stolen.
 *
 * @param {string} instanceId
 * @param {Array<{kind: 'task'|'deliverable', statusKey: string, custom: boolean, sprintId: string, assigneeId?: string|null}>|null|undefined} _itemsIgnored
 * @param {string} personId
 * @param {object|null|undefined} [template]
 */
export function claimAllUnassigned(instanceId, _itemsIgnored, personId, template = null) {
    if (!personId) {
        return { ok: false, error: 'active-person-missing' };
    }
    let claimed = 0;
    const result = updateInstance(instanceId, (instance) => {
        const tpl = template || instance.templateSnapshot;
        if (!tpl) {
            return;
        }
        const sprints = resolveSprints(tpl, instance, 'en');
        for (const sprint of sprints) {
            for (const item of [...(sprint.tasks || []), ...(sprint.deliverables || [])]) {
                if (hasAssigneeId(item.assigneeId)) {
                    continue;
                }
                if (itemAlreadyAssignedOnInstance(instance, item)) {
                    continue;
                }
                const did = assignPersonOnItem(
                    instance,
                    /** @type {'task'|'deliverable'} */ (item.kind),
                    item.statusKey,
                    Boolean(item.custom),
                    item.sprintId,
                    personId,
                    { onlyUnassigned: true },
                );
                if (did) {
                    claimed += 1;
                }
            }
        }
    });
    return result.ok ? { ...result, claimed } : result;
}

/**
 * @param {string|null|undefined} value
 */
function hasAssigneeId(value) {
    if (value === null || value === undefined) {
        return false;
    }
    const id = String(value).trim();
    return id !== '' && id !== 'null' && id !== 'undefined';
}

/**
 * Live check against persisted plan data (not a possibly stale UI snapshot).
 * @param {import('./storage.js').SpPlanInstance} instance
 * @param {{kind: string, statusKey: string, custom?: boolean, sprintId: string}} item
 */
function itemAlreadyAssignedOnInstance(instance, item) {
    if (item.custom) {
        const bag = item.kind === 'task' ? 'customTasks' : 'customDeliverables';
        const list = instance[bag]?.[item.sprintId] || [];
        const found = list.find(
            (entry) => entry.statusKey === item.statusKey || entry.id === item.statusKey.split(':').pop(),
        );
        return hasAssigneeId(found?.assigneeId);
    }
    const override = instance.itemOverrides?.[item.statusKey];
    if (override && Object.prototype.hasOwnProperty.call(override, 'assigneeId')) {
        return hasAssigneeId(override.assigneeId);
    }
    return false;
}

/**
 * @param {import('./storage.js').SpPlanInstance} instance
 * @param {'task'|'deliverable'} kind
 * @param {string} key
 * @param {boolean} isCustom
 * @param {string} sprintId
 * @param {string} personId
 * @param {{onlyUnassigned?: boolean}} [options]
 * @returns {boolean} true when assignment was written
 */
function assignPersonOnItem(instance, kind, key, isCustom, sprintId, personId, options = {}) {
    const onlyUnassigned = Boolean(options.onlyUnassigned);
    if (isCustom) {
        const bag = kind === 'task' ? 'customTasks' : 'customDeliverables';
        const list = instance[bag][sprintId] || [];
        const item = list.find((entry) => entry.statusKey === key || entry.id === key.split(':').pop());
        if (!item) {
            return false;
        }
        if (onlyUnassigned && hasAssigneeId(item.assigneeId)) {
            return false;
        }
        item.assigneeType = 'person';
        item.assigneeId = personId;
        ensureParticipant(instance, personId);
        return true;
    }
    if (!instance.itemOverrides[key]) {
        instance.itemOverrides[key] = {};
    }
    if (
        onlyUnassigned
        && Object.prototype.hasOwnProperty.call(instance.itemOverrides[key], 'assigneeId')
        && hasAssigneeId(instance.itemOverrides[key].assigneeId)
    ) {
        return false;
    }
    instance.itemOverrides[key].assigneeType = 'person';
    instance.itemOverrides[key].assigneeId = personId;
    ensureParticipant(instance, personId);
    return true;
}

/**
 * @param {import('./storage.js').SpPlanInstance} instance
 * @param {string|null|undefined} personId
 */
export function ensureParticipant(instance, personId) {
    if (!personId) {
        return;
    }
    const id = String(personId);
    if (!Array.isArray(instance.participantIds)) {
        instance.participantIds = [];
    }
    if (!instance.participantIds.includes(id)) {
        instance.participantIds.push(id);
    }
}

/**
 * Merge person assignees from resolved sprints into participantIds.
 * @param {import('./storage.js').SpPlanInstance} instance
 * @param {Array<{tasks?: Array<{assigneeType?: string|null, assigneeId?: string|null}>, deliverables?: Array<{assigneeType?: string|null, assigneeId?: string|null}>}>} sprints
 * @returns {boolean} whether participantIds changed
 */
export function backfillParticipantsFromAssignees(instance, sprints) {
    const before = (instance.participantIds || []).join(',');
    for (const sprint of sprints || []) {
        for (const item of [...(sprint.tasks || []), ...(sprint.deliverables || [])]) {
            if (item.assigneeType === 'person' && item.assigneeId) {
                ensureParticipant(instance, item.assigneeId);
            }
        }
    }
    return (instance.participantIds || []).join(',') !== before;
}

/**
 * @param {string} instanceId
 * @param {(instance: import('./storage.js').SpPlanInstance) => void} mutator
 * @param {{ action?: string, summary?: string }} [meta]
 */
export function updateInstance(instanceId, mutator, meta = {}) {
    const loaded = loadWorkspace();
    const workspace = loaded.data;
    const instance = workspace.instances[instanceId];
    if (!instance) {
        return { ok: false, error: 'plan-missing' };
    }
    const previousSlug = String(instance.templateSlug || '');
    const previousStartedAt = String(instance.startedAt || '');
    const previousTranslations = instance.translations;
    let before = null;
    try {
        before = JSON.parse(JSON.stringify(instance));
    } catch {
        before = null;
    }
    mutator(instance);
    // Never allow assign/edit paths to wipe structural identity fields.
    if (!instance.templateSlug && previousSlug) {
        instance.templateSlug = previousSlug;
    }
    if (!instance.startedAt && previousStartedAt) {
        instance.startedAt = previousStartedAt;
    }
    if ((!instance.translations || Object.keys(instance.translations).length === 0) && previousTranslations) {
        instance.translations = previousTranslations;
    }
    instance.updatedAt = new Date().toISOString();
    if (before) {
        pushUndoSnapshot(instanceId, before);
    }
    const historyMeta = {
        action: String(meta.action || 'update'),
        summary: String(meta.summary || 'Plan updated'),
    };
    const saved = saveWorkspace(workspace, {
        dirtyPlanIds: [instanceId],
        historyByPlanId: { [instanceId]: historyMeta },
    });
    return saved.ok ? { ok: true, instance, workspace } : { ok: false, error: saved.error };
}

export function toggleCompleted(instanceId, kind, key, completed) {
    return updateInstance(instanceId, (instance) => {
        const listKey = kind === 'task' ? 'completedTasks' : 'completedDeliverables';
        const set = new Set(instance[listKey]);
        if (completed) {
            set.add(key);
        } else {
            set.delete(key);
        }
        instance[listKey] = [...set];
        if (!instance.itemOverrides[key]) {
            instance.itemOverrides[key] = {};
        }
        instance.itemOverrides[key].status = completed ? 'completed' : 'open';
    }, {
        action: completed ? 'completeItem' : 'reopenItem',
        summary: completed ? 'Marked item completed' : 'Reopened item',
    });
}

export function setFieldValue(instanceId, key, value) {
    return updateInstance(instanceId, (instance) => {
        instance.fieldValues[key] = value;
    });
}

export function setSprintNote(instanceId, sprintId, note) {
    return updateInstance(instanceId, (instance) => {
        instance.sprintNotes[sprintId] = note.slice(0, 8000);
    });
}

export function addCustomSprint(instanceId, data) {
    return updateInstance(instanceId, (instance) => {
        const id = createSprintId();
        const maxNumber = Math.max(
            0,
            ...Object.values(instance.sprintOverrides || {}).map((s) => Number(s.number) || 0),
            ...(instance.customSprints || []).map((s) => Number(s.number) || 0),
            Number(data.number) || 0,
        );
        instance.customSprints.push({
            id,
            number: data.number || maxNumber + 1,
            title: { de: data.titleDe || '', en: data.titleEn || data.titleDe || '' },
            goal: { de: data.goalDe || '', en: data.goalEn || data.goalDe || '' },
            description: {
                de: data.descriptionDe || '',
                en: data.descriptionEn || data.descriptionDe || '',
            },
            notes: data.notes !== false,
            teamId: data.teamId || null,
            ownerPersonId: data.ownerPersonId || null,
            stories: Array.isArray(data.stories) ? data.stories : [],
            linkedStorySlugs: Array.isArray(data.linkedStorySlugs) ? data.linkedStorySlugs : [],
            links: Array.isArray(data.links) ? data.links : [],
        });
        resequenceCustomOnly(instance);
        shiftNumbers(instance, id, data.number);
    });
}

/**
 * Copy one sprint (with tasks/deliverables) from a plan template into the instance as a custom sprint.
 *
 * @param {string} instanceId
 * @param {object} template
 * @param {string} sprintId
 */
export function addSprintFromTemplate(instanceId, template, sprintId) {
    const structural = (template?.sprints || []).find((s) => String(s.id) === String(sprintId));
    if (!structural) {
        return { ok: false, error: 'sprint-missing' };
    }
    const dePack = (template.locales?.de?.sprints || []).find((s) => String(s.id) === String(sprintId)) || {};
    const enPack = (template.locales?.en?.sprints || []).find((s) => String(s.id) === String(sprintId)) || {};
    const deTasks = Object.fromEntries((dePack.tasks || []).map((t) => [String(t.id), t]));
    const enTasks = Object.fromEntries((enPack.tasks || []).map((t) => [String(t.id), t]));
    const deDels = Object.fromEntries((dePack.deliverables || []).map((d) => [String(d.id), d]));
    const enDels = Object.fromEntries((enPack.deliverables || []).map((d) => [String(d.id), d]));

    return updateInstance(instanceId, (instance) => {
        const id = createSprintId();
        const maxNumber = Math.max(
            0,
            ...Object.values(instance.sprintOverrides || {}).map((s) => Number(s.number) || 0),
            ...(instance.customSprints || []).map((s) => Number(s.number) || 0),
        );
        // Prefer resolved sprint count from template structure already on the plan.
        const templateSprintCount = Array.isArray(template?.sprints) ? template.sprints.length : 0;
        const nextNumber = Math.max(maxNumber, templateSprintCount) + 1;
        const stories = Array.isArray(structural.stories) ? structuredClone(structural.stories) : [];
        const links = Array.isArray(structural.links)
            ? structuredClone(structural.links)
            : (Array.isArray(enPack.links) ? structuredClone(enPack.links) : []);

        instance.customSprints.push({
            id,
            number: nextNumber,
            title: {
                de: String(dePack.title || ''),
                en: String(enPack.title || dePack.title || ''),
            },
            goal: {
                de: String(dePack.goal || ''),
                en: String(enPack.goal || dePack.goal || ''),
            },
            description: {
                de: String(dePack.description || ''),
                en: String(enPack.description || dePack.description || ''),
            },
            notes: structural.notes !== false,
            teamId: null,
            ownerPersonId: null,
            stories,
            linkedStorySlugs: Array.isArray(structural.linkedStorySlugs)
                ? [...structural.linkedStorySlugs]
                : stories.map((s) => String(s.slug || '')).filter(Boolean),
            links,
        });

        instance.customTasks[id] = (structural.tasks || []).map((task) => {
            const tid = String(task.id);
            const de = deTasks[tid] || {};
            const en = enTasks[tid] || {};
            return {
                id: createTaskId(),
                label: {
                    de: String(de.label || ''),
                    en: String(en.label || de.label || ''),
                },
                status: 'open',
                priority: 'normal',
                assigneeType: task.assigneeType || 'person',
                assigneeId: task.assigneeId || null,
                dueDate: null,
                note: '',
                helpText: {
                    de: String(de.helpText || ''),
                    en: String(en.helpText || de.helpText || ''),
                },
                helpLinks: Array.isArray(task.helpLinks)
                    ? structuredClone(task.helpLinks)
                    : (Array.isArray(en.helpLinks) ? structuredClone(en.helpLinks) : []),
                stories: Array.isArray(task.stories) ? structuredClone(task.stories) : [],
                linkedStorySlugs: Array.isArray(task.linkedStorySlugs) ? [...task.linkedStorySlugs] : [],
                demoCode: String(en.demoCode || de.demoCode || ''),
                blockerReason: '',
                blockerSince: null,
                attachments: [],
                table: task.table ? structuredClone(task.table) : null,
            };
        });

        instance.customDeliverables[id] = (structural.deliverables || []).map((del) => {
            const did = String(del.id);
            const de = deDels[did] || {};
            const en = enDels[did] || {};
            return {
                id: createDeliverableId(),
                label: {
                    de: String(de.label || ''),
                    en: String(en.label || de.label || ''),
                },
                status: 'open',
                priority: 'normal',
                assigneeType: 'person',
                assigneeId: null,
                dueDate: null,
                note: '',
                helpText: {
                    de: String(de.helpText || ''),
                    en: String(en.helpText || de.helpText || ''),
                },
                helpLinks: Array.isArray(del.helpLinks)
                    ? structuredClone(del.helpLinks)
                    : (Array.isArray(en.helpLinks) ? structuredClone(en.helpLinks) : []),
                stories: Array.isArray(del.stories) ? structuredClone(del.stories) : [],
                linkedStorySlugs: Array.isArray(del.linkedStorySlugs) ? [...del.linkedStorySlugs] : [],
                demoCode: String(en.demoCode || de.demoCode || ''),
                blockerReason: '',
                blockerSince: null,
                attachments: [],
                table: del.table ? structuredClone(del.table) : null,
            };
        });

        resequenceCustomOnly(instance);
        shiftNumbers(instance, id, nextNumber);
    });
}

export function updateCustomOrOverrideSprint(instanceId, sprintId, data, isCustom) {
    return updateInstance(instanceId, (instance) => {
        if (isCustom) {
            const sprint = instance.customSprints.find((s) => s.id === sprintId);
            if (!sprint) {
                return;
            }
            sprint.title = { de: data.titleDe || '', en: data.titleEn || data.titleDe || '' };
            sprint.goal = { de: data.goalDe || '', en: data.goalEn || data.goalDe || '' };
            sprint.description = {
                de: data.descriptionDe || '',
                en: data.descriptionEn || data.descriptionDe || '',
            };
            sprint.notes = data.notes !== false;
            if (data.number) {
                sprint.number = Number(data.number);
            }
            if (Array.isArray(data.stories)) {
                sprint.stories = data.stories;
            }
            if (Array.isArray(data.linkedStorySlugs)) {
                sprint.linkedStorySlugs = data.linkedStorySlugs;
            }
            if (Array.isArray(data.links)) {
                sprint.links = data.links;
            }
            return;
        }
        instance.sprintOverrides[sprintId] = {
            ...(instance.sprintOverrides[sprintId] || {}),
            title: { de: data.titleDe || '', en: data.titleEn || data.titleDe || '' },
            goal: { de: data.goalDe || '', en: data.goalEn || data.goalDe || '' },
            description: {
                de: data.descriptionDe || '',
                en: data.descriptionEn || data.descriptionDe || '',
            },
            notes: data.notes !== false,
            number: data.number ? Number(data.number) : undefined,
            stories: Array.isArray(data.stories) ? data.stories : undefined,
            linkedStorySlugs: Array.isArray(data.linkedStorySlugs) ? data.linkedStorySlugs : undefined,
            links: Array.isArray(data.links) ? data.links : undefined,
        };
    });
}

export function duplicateSprint(instanceId, sprint, locale) {
    return updateInstance(instanceId, (instance) => {
        const id = createSprintId();
        instance.customSprints.push({
            id,
            number: Number(sprint.number) + 1,
            title: {
                de: `${sprint.title} (Kopie)`,
                en: `${sprint.title} (Copy)`,
            },
            goal: { de: sprint.goal, en: sprint.goal },
            description: { de: sprint.description || '', en: sprint.description || '' },
            notes: sprint.notesEnabled,
        });
        instance.customTasks[id] = (sprint.tasks || []).map((task) => ({
            id: createTaskId(),
            label: { de: task.label, en: task.label },
            status: 'open',
            priority: task.priority || 'normal',
            assigneeType: task.assigneeType || 'person',
            assigneeId: task.assigneeId || null,
        }));
        instance.customDeliverables[id] = (sprint.deliverables || []).map((del) => ({
            id: createDeliverableId(),
            label: { de: del.label, en: del.label },
            status: 'open',
            priority: del.priority || 'normal',
        }));
    });
}

export function moveSprint(instanceId, sprintId, direction, isCustom, currentNumber = 1) {
    return updateInstance(instanceId, (instance) => {
        const delta = direction === 'up' ? -1 : 1;
        const nextNumber = Math.max(1, Number(currentNumber || 1) + delta);
        if (isCustom) {
            const sprint = instance.customSprints.find((s) => s.id === sprintId);
            if (!sprint) {
                return;
            }
            sprint.number = nextNumber;
            return;
        }
        instance.sprintOverrides[sprintId] = {
            ...(instance.sprintOverrides[sprintId] || {}),
            number: nextNumber,
        };
    });
}

export function deleteCustomSprint(instanceId, sprintId) {
    return updateInstance(instanceId, (instance) => {
        instance.customSprints = instance.customSprints.filter((s) => s.id !== sprintId);
        delete instance.customTasks[sprintId];
        delete instance.customDeliverables[sprintId];
        delete instance.sprintNotes[sprintId];
    });
}

export function resetSprint(instanceId, sprintId, templateSlug) {
    return updateInstance(instanceId, (instance) => {
        delete instance.sprintOverrides[sprintId];
        delete instance.sprintNotes[sprintId];
        instance.customTasks[sprintId] = [];
        instance.customDeliverables[sprintId] = [];
        const prefix = `${templateSlug}:${sprintId}:`;
        instance.completedTasks = instance.completedTasks.filter((key) => !key.startsWith(prefix));
        instance.completedDeliverables = instance.completedDeliverables.filter((key) => !key.startsWith(prefix));
        for (const key of Object.keys(instance.fieldValues)) {
            if (key.startsWith(prefix)) {
                delete instance.fieldValues[key];
            }
        }
        for (const key of Object.keys(instance.itemOverrides)) {
            if (key.startsWith(prefix)) {
                delete instance.itemOverrides[key];
            }
        }
        if (Array.isArray(instance.removedItemKeys)) {
            instance.removedItemKeys = instance.removedItemKeys.filter((key) => !key.startsWith(prefix));
        }
    });
}

export function addCustomItem(instanceId, sprintId, kind, data, templateSlug) {
    const loaded = loadWorkspace();
    const instance = loaded.data.instances[instanceId];
    if (!instance) {
        return { ok: false, error: 'plan-missing' };
    }
    const id = kind === 'task' ? createTaskId() : createDeliverableId();
    const key = statusKey(templateSlug, sprintId, kind, id);
    const depsCheck = sanitizeDependsOnForItem(instance, sprintId, key, data.dependsOn ?? []);
    if (!depsCheck.ok) {
        return depsCheck;
    }

    return updateInstance(instanceId, (plan) => {
        const item = {
            id,
            statusKey: key,
            label: { de: data.labelDe || '', en: data.labelEn || data.labelDe || '' },
            status: data.status || 'open',
            priority: data.priority || 'normal',
            assigneeType: data.assigneeType || 'person',
            assigneeId: data.assigneeId || null,
            dueDate: data.dueDate || null,
            note: data.note || '',
            plannedMinutes: Object.prototype.hasOwnProperty.call(data, 'plannedMinutes')
                ? data.plannedMinutes
                : null,
            actualMinutes: Object.prototype.hasOwnProperty.call(data, 'actualMinutes')
                ? data.actualMinutes
                : null,
            helpText: { de: '', en: '' },
            helpLinks: [],
            stories: [],
            linkedStorySlugs: [],
            demoCode: '',
            blockerReason: data.status === 'blocked' ? (data.blockerReason || '') : '',
            blockerSince: data.status === 'blocked' ? (data.blockerSince || null) : null,
            dependsOn: depsCheck.dependsOn,
            attachments: normalizeAttachments(data.attachments),
            table: data.table || null,
        };
        const bag = kind === 'task' ? 'customTasks' : 'customDeliverables';
        if (!plan[bag][sprintId]) {
            plan[bag][sprintId] = [];
        }
        plan[bag][sprintId].push(item);
        if (item.status === 'completed') {
            const list = kind === 'task' ? 'completedTasks' : 'completedDeliverables';
            plan[list] = [...new Set([...plan[list], key])];
        }
    });
}

export function updateItemMeta(instanceId, kind, key, data, isCustom, sprintId) {
    const loaded = loadWorkspace();
    const instance = loaded.data.instances[instanceId];
    if (!instance) {
        return { ok: false, error: 'plan-missing' };
    }
    let dependsOn = undefined;
    if (data.dependsOn !== undefined) {
        const depsCheck = sanitizeDependsOnForItem(instance, sprintId, key, data.dependsOn);
        if (!depsCheck.ok) {
            return depsCheck;
        }
        dependsOn = depsCheck.dependsOn;
    }

    return updateInstance(instanceId, (plan) => {
        if (isCustom) {
            const bag = kind === 'task' ? 'customTasks' : 'customDeliverables';
            const list = plan[bag][sprintId] || [];
            const item = list.find((entry) => entry.statusKey === key || entry.id === data.id);
            if (!item) {
                return;
            }
            item.label = { de: data.labelDe || '', en: data.labelEn || data.labelDe || '' };
            item.status = data.status || item.status;
            item.priority = data.priority || item.priority;
            item.assigneeType = 'person';
            item.assigneeId = data.assigneeId || null;
            ensureParticipant(plan, item.assigneeId);
            item.dueDate = data.dueDate || null;
            item.note = data.note || '';
            if (Object.prototype.hasOwnProperty.call(data, 'plannedMinutes')) {
                item.plannedMinutes = data.plannedMinutes;
            }
            if (Object.prototype.hasOwnProperty.call(data, 'actualMinutes')) {
                item.actualMinutes = data.actualMinutes;
            }
            item.blockerReason = item.status === 'blocked' ? (data.blockerReason || '') : '';
            item.blockerSince = item.status === 'blocked' ? (data.blockerSince || null) : null;
            if (dependsOn !== undefined) {
                item.dependsOn = dependsOn;
            }
            if (Array.isArray(data.attachments) || data.attachments === null) {
                item.attachments = normalizeAttachments(data.attachments || []);
            }
            if (data.table !== undefined) {
                item.table = data.table;
            }
            syncCompletion(plan, kind, key, item.status === 'completed');
            return;
        }
        const previous = plan.itemOverrides[key] || {};
        /** @type {Record<string, unknown>} */
        const next = {
            ...previous,
            status: data.status,
            priority: data.priority,
            assigneeType: 'person',
            assigneeId: data.assigneeId || null,
            dueDate: data.dueDate || null,
            note: data.note || '',
            blockerReason: data.status === 'blocked' ? (data.blockerReason || '') : '',
            blockerSince: data.status === 'blocked' ? (data.blockerSince || null) : null,
        };
        if (Object.prototype.hasOwnProperty.call(data, 'plannedMinutes')) {
            next.plannedMinutes = data.plannedMinutes;
        }
        if (Object.prototype.hasOwnProperty.call(data, 'actualMinutes')) {
            next.actualMinutes = data.actualMinutes;
        }
        if (dependsOn !== undefined) {
            next.dependsOn = dependsOn;
        }
        // Plan overrides must not store template content (help/stories/labels) — that broke DE/EN help.
        delete next.label;
        delete next.helpText;
        delete next.helpLinks;
        delete next.demoCode;
        delete next.stories;
        delete next.linkedStorySlugs;
        if (Array.isArray(data.attachments) || data.attachments === null) {
            next.attachments = normalizeAttachments(data.attachments || []);
        }
        if (data.table !== undefined) {
            next.table = data.table;
        }
        plan.itemOverrides[key] = next;
        if (next.assigneeType === 'person') {
            ensureParticipant(plan, /** @type {string|null} */ (next.assigneeId));
        }
        syncCompletion(plan, kind, key, data.status === 'completed');
    });
}

/**
 * @param {import('./storage.js').SpPlanInstance} instance
 * @param {string} sprintId
 * @returns {Set<string>}
 */
function allowedKeysForSprint(instance, sprintId) {
    const snap = instance.templateSnapshot;
    const templateSprint = (snap?.sprints || []).find((entry) => entry.id === sprintId);
    return collectSprintItemKeys({
        templateSlug: String(instance.templateSlug || snap?.slug || ''),
        sprintId,
        templateTasks: templateSprint?.tasks || [],
        templateDeliverables: templateSprint?.deliverables || [],
        customTasks: instance.customTasks?.[sprintId] || [],
        customDeliverables: instance.customDeliverables?.[sprintId] || [],
        removedItemKeys: instance.removedItemKeys || [],
    });
}

/**
 * @param {import('./storage.js').SpPlanInstance} instance
 * @param {string} sprintId
 * @param {Set<string>} allowedKeys
 * @returns {Map<string, string[]>}
 */
function storedDependsGraph(instance, sprintId, allowedKeys) {
    const slug = String(instance.templateSlug || instance.templateSnapshot?.slug || '');
    const snap = instance.templateSnapshot;
    const templateSprint = (snap?.sprints || []).find((entry) => entry.id === sprintId);
    /** @type {Array<{statusKey: string, dependsOn?: string[]}>} */
    const items = [];

    for (const task of templateSprint?.tasks || []) {
        const key = statusKey(slug, sprintId, 'task', task.id);
        if (!allowedKeys.has(key)) {
            continue;
        }
        const override = instance.itemOverrides?.[key] || {};
        items.push({
            statusKey: key,
            dependsOn: resolveDependsOnKeys(pickRawDependsOn(override, task), {
                templateSlug: slug,
                sprintId,
                allowedKeys,
                selfKey: key,
            }),
        });
    }
    for (const del of templateSprint?.deliverables || []) {
        const key = statusKey(slug, sprintId, 'deliverable', del.id);
        if (!allowedKeys.has(key)) {
            continue;
        }
        const override = instance.itemOverrides?.[key] || {};
        items.push({
            statusKey: key,
            dependsOn: resolveDependsOnKeys(pickRawDependsOn(override, del), {
                templateSlug: slug,
                sprintId,
                allowedKeys,
                selfKey: key,
            }),
        });
    }
    for (const task of instance.customTasks?.[sprintId] || []) {
        const key = task.statusKey || statusKey(slug, sprintId, 'task', task.id);
        items.push({
            statusKey: key,
            dependsOn: resolveDependsOnKeys(task.dependsOn, {
                templateSlug: slug,
                sprintId,
                allowedKeys,
                selfKey: key,
            }),
        });
    }
    for (const del of instance.customDeliverables?.[sprintId] || []) {
        const key = del.statusKey || statusKey(slug, sprintId, 'deliverable', del.id);
        items.push({
            statusKey: key,
            dependsOn: resolveDependsOnKeys(del.dependsOn, {
                templateSlug: slug,
                sprintId,
                allowedKeys,
                selfKey: key,
            }),
        });
    }
    return buildDependsOnGraph(items);
}

/**
 * @param {import('./storage.js').SpPlanInstance} instance
 * @param {string} sprintId
 * @param {string} itemKey
 * @param {unknown} rawDependsOn
 * @returns {{ ok: true, dependsOn: string[] }|{ ok: false, error: string }}
 */
function sanitizeDependsOnForItem(instance, sprintId, itemKey, rawDependsOn) {
    const allowedKeys = allowedKeysForSprint(instance, sprintId);
    // Include the new key when creating custom items that are not yet in storage.
    allowedKeys.add(itemKey);
    const graph = storedDependsGraph(instance, sprintId, allowedKeys);
    if (!graph.has(itemKey)) {
        graph.set(itemKey, []);
    }
    return validateDependsOn(itemKey, rawDependsOn, {
        sprintId,
        allowedKeys,
        graph,
    });
}

/**
 * Quick-assign only (person/team) without touching other plan fields.
 * @param {string} instanceId
 * @param {'task'|'deliverable'} kind
 * @param {string} key
 * @param {boolean} isCustom
 * @param {string} sprintId
 * @param {{assigneeType: string, assigneeId: string|null}} assignment
 */
export function assignItem(instanceId, kind, key, isCustom, sprintId, assignment) {
    return updateInstance(instanceId, (instance) => {
        // Tasks belong to people (or nobody). Team ownership lives on the plan.
        const assigneeType = 'person';
        const rawId = assignment?.assigneeId;
        const assigneeId = rawId === null || rawId === undefined || String(rawId).trim() === ''
            ? null
            : String(rawId);
        if (isCustom) {
            const bag = kind === 'task' ? 'customTasks' : 'customDeliverables';
            const list = instance[bag][sprintId] || [];
            const item = list.find((entry) => entry.statusKey === key || entry.id === key.split(':').pop());
            if (!item) {
                return;
            }
            item.assigneeType = assigneeType;
            item.assigneeId = assigneeId;
            ensureParticipant(instance, assigneeId);
            return;
        }
        if (!instance.itemOverrides[key]) {
            instance.itemOverrides[key] = {};
        }
        instance.itemOverrides[key].assigneeType = assigneeType;
        instance.itemOverrides[key].assigneeId = assigneeId;
        ensureParticipant(instance, assigneeId);
        // Clear polluted template fields if present from older editors.
        delete instance.itemOverrides[key].helpText;
        delete instance.itemOverrides[key].helpLinks;
        delete instance.itemOverrides[key].demoCode;
        delete instance.itemOverrides[key].stories;
        delete instance.itemOverrides[key].linkedStorySlugs;
        delete instance.itemOverrides[key].label;
    });
}

export function deleteCustomItem(instanceId, sprintId, kind, itemId, statusKeyValue) {
    return updateInstance(instanceId, (instance) => {
        const bag = kind === 'task' ? 'customTasks' : 'customDeliverables';
        instance[bag][sprintId] = (instance[bag][sprintId] || []).filter((item) => item.id !== itemId);
        const list = kind === 'task' ? 'completedTasks' : 'completedDeliverables';
        instance[list] = instance[list].filter((key) => key !== statusKeyValue);
        delete instance.itemOverrides[statusKeyValue];
    });
}

/**
 * Remove a plan item (custom delete, or soft-remove a template item for this plan).
 * @param {string} instanceId
 * @param {{custom: boolean, sprintId: string, kind: string, itemId: string, statusKey: string}} opts
 */
export function removePlanItem(instanceId, opts) {
    if (opts.custom) {
        return deleteCustomItem(instanceId, opts.sprintId, opts.kind, opts.itemId, opts.statusKey);
    }
    return updateInstance(instanceId, (instance) => {
        if (!Array.isArray(instance.removedItemKeys)) {
            instance.removedItemKeys = [];
        }
        if (!instance.removedItemKeys.includes(opts.statusKey)) {
            instance.removedItemKeys.push(opts.statusKey);
        }
        const list = opts.kind === 'task' ? 'completedTasks' : 'completedDeliverables';
        instance[list] = instance[list].filter((key) => key !== opts.statusKey);
        delete instance.itemOverrides[opts.statusKey];
    });
}

export function duplicateInstance(instanceId) {
    const loaded = loadWorkspace();
    const workspace = loaded.data;
    const source = workspace.instances[instanceId];
    if (!source) {
        return { ok: false, error: 'plan-missing' };
    }
    const id = createPlanId();
    const now = new Date().toISOString();
    workspace.instances[id] = {
        ...structuredClone(source),
        id,
        status: 'active',
        archived: false,
        createdAt: now,
        updatedAt: now,
    };
    const saved = saveWorkspace(workspace, { dirtyPlanIds: [id] });
    return saved.ok ? { ok: true, instance: workspace.instances[id] } : { ok: false, error: saved.error };
}

export function archiveInstance(instanceId, archived = true) {
    const result = updateInstance(instanceId, (instance) => {
        instance.archived = archived;
        instance.status = archived ? 'archived' : 'active';
    });
    if (result.ok && archived) {
        clearLastOpenedPlanIdIf(instanceId);
    }
    return result;
}

export function resetInstanceProgress(instanceId) {
    return updateInstance(instanceId, (instance) => {
        instance.completedTasks = [];
        instance.completedDeliverables = [];
        instance.fieldValues = {};
        instance.sprintNotes = {};
        instance.customTasks = {};
        instance.customDeliverables = {};
        instance.customSprints = [];
        instance.sprintOverrides = {};
        instance.itemOverrides = {};
        instance.removedItemKeys = [];
        instance.status = 'active';
    });
}

export function deleteInstance(instanceId) {
    const loaded = loadWorkspace();
    const workspace = loaded.data;
    const existing = workspace.instances[instanceId];
    if (!existing) {
        return { ok: false, error: 'plan-missing' };
    }
    const wasEphemeral = Boolean(existing.ephemeral);
    delete workspace.instances[instanceId];
    // Cache only — server delete is handled below (do not re-upsert siblings).
    const saved = saveWorkspace(workspace, { dirtyPlanIds: [] });
    if (!saved.ok) {
        return { ok: false, error: saved.error };
    }
    clearLastOpenedPlanIdIf(instanceId);

    if (usesServerPlans() && ! wasEphemeral) {
        const { plansApiUrl } = readAccountsBootstrap();
        deletePlanOnServer(instanceId, plansApiUrl).catch(() => {
            // Local delete already applied; server retry on next sync if needed.
        });
    }

    return { ok: true };
}

/**
 * @param {string} instanceId
 * @param {string} password
 */
export async function setPlanPassword(instanceId, password) {
    const { createSalt, hashPassword } = await import('./plan-password.js');
    const trimmed = String(password || '');
    if (trimmed.length < 4) {
        return { ok: false, error: 'password-too-short' };
    }
    const salt = createSalt();
    const passwordHash = await hashPassword(trimmed, salt);
    return updateInstance(instanceId, (instance) => {
        instance.passwordHash = passwordHash;
        instance.passwordSalt = salt;
    });
}

/**
 * @param {string} instanceId
 */
export function clearPlanPassword(instanceId) {
    return updateInstance(instanceId, (instance) => {
        instance.passwordHash = null;
        instance.passwordSalt = null;
    });
}

/**
 * Structural compatibility: same sprint/task/deliverable/field IDs.
 * @param {object} fromTemplate
 * @param {object} toTemplate
 */
export function templatesAreCompatible(fromTemplate, toTemplate) {
    if (!fromTemplate?.sprints || !toTemplate?.sprints) {
        return false;
    }
    if (fromTemplate.sprints.length !== toTemplate.sprints.length) {
        return false;
    }
    for (let i = 0; i < fromTemplate.sprints.length; i += 1) {
        const a = fromTemplate.sprints[i];
        const b = toTemplate.sprints[i];
        if (a.id !== b.id) {
            return false;
        }
        const taskA = (a.tasks || []).map((t) => t.id).join(',');
        const taskB = (b.tasks || []).map((t) => t.id).join(',');
        const delA = (a.deliverables || []).map((d) => d.id).join(',');
        const delB = (b.deliverables || []).map((d) => d.id).join(',');
        const fieldA = (a.fields || []).map((f) => f.id).join(',');
        const fieldB = (b.fields || []).map((f) => f.id).join(',');
        if (taskA !== taskB || delA !== delB || fieldA !== fieldB) {
            return false;
        }
    }
    return true;
}

/**
 * Remap status-key prefixes when switching between compatible templates.
 * @param {string} key
 * @param {string} fromSlug
 * @param {string} toSlug
 */
function remapStatusKey(key, fromSlug, toSlug) {
    const prefix = `${fromSlug}:`;
    if (!String(key).startsWith(prefix)) {
        return key;
    }
    return `${toSlug}:${String(key).slice(prefix.length)}`;
}

/**
 * @param {Record<string, unknown>} map
 * @param {string} fromSlug
 * @param {string} toSlug
 */
function remapKeyedMap(map, fromSlug, toSlug) {
    /** @type {Record<string, unknown>} */
    const out = {};
    for (const [key, value] of Object.entries(map || {})) {
        out[remapStatusKey(key, fromSlug, toSlug)] = value;
    }
    return out;
}

/**
 * Switch a plan instance to another structurally compatible template (keeps progress).
 * @param {string} instanceId
 * @param {object} newTemplate
 * @param {object[]} templates
 */
export function switchPlanTemplate(instanceId, newTemplate, templates = []) {
    if (!newTemplate?.slug) {
        return { ok: false, error: 'template-missing' };
    }
    const loaded = loadWorkspace();
    const current = loaded.data.instances[instanceId];
    if (!current) {
        return { ok: false, error: 'plan-missing' };
    }
    const fromSlug = current.templateSlug;
    const toSlug = newTemplate.slug;
    if (fromSlug === toSlug) {
        return { ok: true, instance: current };
    }
    const fromTemplate = (templates || []).find((t) => t.slug === fromSlug);
    if (fromTemplate && !templatesAreCompatible(fromTemplate, newTemplate)) {
        return { ok: false, error: 'template-incompatible' };
    }

    return updateInstance(instanceId, (instance) => {
        instance.completedTasks = (instance.completedTasks || []).map((key) => remapStatusKey(key, fromSlug, toSlug));
        instance.completedDeliverables = (instance.completedDeliverables || []).map((key) => remapStatusKey(key, fromSlug, toSlug));
        instance.fieldValues = remapKeyedMap(instance.fieldValues || {}, fromSlug, toSlug);
        instance.itemOverrides = remapKeyedMap(instance.itemOverrides || {}, fromSlug, toSlug);

        for (const bag of ['customTasks', 'customDeliverables']) {
            const groups = instance[bag] || {};
            for (const sprintId of Object.keys(groups)) {
                groups[sprintId] = (groups[sprintId] || []).map((item) => ({
                    ...item,
                    statusKey: item.statusKey
                        ? remapStatusKey(item.statusKey, fromSlug, toSlug)
                        : item.statusKey,
                }));
            }
        }

        instance.templateSlug = toSlug;
        instance.templateVersion = Number(newTemplate.version) || instance.templateVersion || 1;
        instance.translations = {
            de: {
                title: newTemplate.locales?.de?.title || toSlug,
                description: newTemplate.locales?.de?.description || '',
            },
            en: {
                title: newTemplate.locales?.en?.title || toSlug,
                description: newTemplate.locales?.en?.description || '',
            },
        };
    });
}

function syncCompletion(instance, kind, key, completed) {
    const listKey = kind === 'task' ? 'completedTasks' : 'completedDeliverables';
    const set = new Set(instance[listKey]);
    if (completed) {
        set.add(key);
    } else {
        set.delete(key);
    }
    instance[listKey] = [...set];
}

/**
 * @param {unknown} value
 */
function structuredCloneSafe(value) {
    try {
        return typeof structuredClone === 'function'
            ? structuredClone(value)
            : JSON.parse(JSON.stringify(value));
    } catch {
        return value && typeof value === 'object' ? { .../** @type {object} */ (value) } : value;
    }
}

function resequenceCustomOnly() {
    // placeholder for future dense renumber — numbers are free-form in v1
}

function shiftNumbers() {
    // position handled by explicit number field
}
