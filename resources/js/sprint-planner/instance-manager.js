import {
    deletePlanOnServer,
    isAccountsMode,
    readAccountsBootstrap,
    usesServerPlans,
} from './accounts-bridge.js';
import { normalizeAttachments } from './attachments.js';
import { createDeliverableId, createPlanId, createSprintId, createTaskId, statusKey } from './ids.js';
import { loadWorkspace, saveWorkspace } from './storage.js';

/**
 * @param {object} template
 * @param {{startedAt: string, teamId?: string|null, participantIds?: string[], ephemeral?: boolean}} options
 */
export function startInstanceFromTemplate(template, options) {
    const loaded = loadWorkspace();
    const workspace = loaded.data;
    const id = createPlanId();
    const now = new Date().toISOString();
    const ephemeral = Boolean(options.ephemeral)
        || (isAccountsMode() && ! usesServerPlans());
    const activePersonId = workspace.workspace.activePersonId || null;

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
        teamId: options.teamId || workspace.workspace.defaultTeamId || null,
        participantIds: options.participantIds || [],
        completedTasks: [],
        completedDeliverables: [],
        fieldValues: {},
        sprintNotes: {},
        customTasks: {},
        customDeliverables: {},
        customSprints: [],
        sprintOverrides: {},
        itemOverrides: activePersonId
            ? buildDefaultAssigneeOverrides(template, activePersonId)
            : {},
        // Always keep a snapshot so the plan stays renderable if the slug is lost or the template catalog fails to load.
        templateSnapshot: structuredCloneSafe(template),
        ownerUserId: usesServerPlans() && ! ephemeral
            ? (readAccountsBootstrap().accountUser?.id || null)
            : null,
        viewerUserIds: [],
        viewerTeamIds: [],
        linkedStorySlugs: [],
        ephemeral,
        archived: false,
        createdAt: now,
        updatedAt: now,
    };

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
 * Claim a single item for a person.
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
    return updateInstance(instanceId, (instance) => {
        assignPersonOnItem(instance, kind, key, isCustom, sprintId, personId);
    });
}

/**
 * Claim every currently unassigned resolved item.
 * @param {string} instanceId
 * @param {Array<{kind: 'task'|'deliverable', statusKey: string, custom: boolean, sprintId: string, assigneeId: string|null}>} items
 * @param {string} personId
 */
export function claimAllUnassigned(instanceId, items, personId) {
    if (!personId) {
        return { ok: false, error: 'active-person-missing' };
    }
    return updateInstance(instanceId, (instance) => {
        for (const item of items) {
            if (item.assigneeId) {
                continue;
            }
            assignPersonOnItem(
                instance,
                item.kind,
                item.statusKey,
                item.custom,
                item.sprintId,
                personId,
            );
        }
    });
}

/**
 * @param {import('./storage.js').SpPlanInstance} instance
 * @param {'task'|'deliverable'} kind
 * @param {string} key
 * @param {boolean} isCustom
 * @param {string} sprintId
 * @param {string} personId
 */
function assignPersonOnItem(instance, kind, key, isCustom, sprintId, personId) {
    if (isCustom) {
        const bag = kind === 'task' ? 'customTasks' : 'customDeliverables';
        const list = instance[bag][sprintId] || [];
        const item = list.find((entry) => entry.statusKey === key || entry.id === key.split(':').pop());
        if (!item) {
            return;
        }
        item.assigneeType = 'person';
        item.assigneeId = personId;
        return;
    }
    if (!instance.itemOverrides[key]) {
        instance.itemOverrides[key] = {};
    }
    instance.itemOverrides[key].assigneeType = 'person';
    instance.itemOverrides[key].assigneeId = personId;
}

/**
 * @param {string} instanceId
 * @param {(instance: import('./storage.js').SpPlanInstance) => void} mutator
 */
export function updateInstance(instanceId, mutator) {
    const loaded = loadWorkspace();
    const workspace = loaded.data;
    const instance = workspace.instances[instanceId];
    if (!instance) {
        return { ok: false, error: 'plan-missing' };
    }
    const previousSlug = String(instance.templateSlug || '');
    const previousStartedAt = String(instance.startedAt || '');
    const previousTranslations = instance.translations;
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
    const saved = saveWorkspace(workspace, { dirtyPlanIds: [instanceId] });
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
    });
}

export function addCustomItem(instanceId, sprintId, kind, data, templateSlug) {
    return updateInstance(instanceId, (instance) => {
        const id = kind === 'task' ? createTaskId() : createDeliverableId();
        const key = statusKey(templateSlug, sprintId, kind, id);
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
            helpText: { de: '', en: '' },
            helpLinks: [],
            stories: [],
            linkedStorySlugs: [],
            demoCode: '',
            blockerReason: data.status === 'blocked' ? (data.blockerReason || '') : '',
            blockerSince: data.status === 'blocked' ? (data.blockerSince || null) : null,
            attachments: normalizeAttachments(data.attachments),
            table: data.table || null,
        };
        const bag = kind === 'task' ? 'customTasks' : 'customDeliverables';
        if (!instance[bag][sprintId]) {
            instance[bag][sprintId] = [];
        }
        instance[bag][sprintId].push(item);
        if (item.status === 'completed') {
            const list = kind === 'task' ? 'completedTasks' : 'completedDeliverables';
            instance[list] = [...new Set([...instance[list], key])];
        }
    });
}

export function updateItemMeta(instanceId, kind, key, data, isCustom, sprintId) {
    return updateInstance(instanceId, (instance) => {
        if (isCustom) {
            const bag = kind === 'task' ? 'customTasks' : 'customDeliverables';
            const list = instance[bag][sprintId] || [];
            const item = list.find((entry) => entry.statusKey === key || entry.id === data.id);
            if (!item) {
                return;
            }
            item.label = { de: data.labelDe || '', en: data.labelEn || data.labelDe || '' };
            item.status = data.status || item.status;
            item.priority = data.priority || item.priority;
            item.assigneeType = data.assigneeType;
            item.assigneeId = data.assigneeId || null;
            item.dueDate = data.dueDate || null;
            item.note = data.note || '';
            item.blockerReason = item.status === 'blocked' ? (data.blockerReason || '') : '';
            item.blockerSince = item.status === 'blocked' ? (data.blockerSince || null) : null;
            if (Array.isArray(data.attachments) || data.attachments === null) {
                item.attachments = normalizeAttachments(data.attachments || []);
            }
            if (data.table !== undefined) {
                item.table = data.table;
            }
            syncCompletion(instance, kind, key, item.status === 'completed');
            return;
        }
        const previous = instance.itemOverrides[key] || {};
        /** @type {Record<string, unknown>} */
        const next = {
            ...previous,
            status: data.status,
            priority: data.priority,
            assigneeType: data.assigneeType,
            assigneeId: data.assigneeId || null,
            dueDate: data.dueDate || null,
            note: data.note || '',
            blockerReason: data.status === 'blocked' ? (data.blockerReason || '') : '',
            blockerSince: data.status === 'blocked' ? (data.blockerSince || null) : null,
        };
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
        instance.itemOverrides[key] = next;
        syncCompletion(instance, kind, key, data.status === 'completed');
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
        const assigneeType = assignment.assigneeType || 'person';
        const assigneeId = assignment.assigneeId || null;
        if (isCustom) {
            const bag = kind === 'task' ? 'customTasks' : 'customDeliverables';
            const list = instance[bag][sprintId] || [];
            const item = list.find((entry) => entry.statusKey === key || entry.id === key.split(':').pop());
            if (!item) {
                return;
            }
            item.assigneeType = assigneeType;
            item.assigneeId = assigneeId;
            return;
        }
        if (!instance.itemOverrides[key]) {
            instance.itemOverrides[key] = {};
        }
        instance.itemOverrides[key].assigneeType = assigneeType;
        instance.itemOverrides[key].assigneeId = assigneeId;
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
    return updateInstance(instanceId, (instance) => {
        instance.archived = archived;
        instance.status = archived ? 'archived' : 'active';
    });
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
