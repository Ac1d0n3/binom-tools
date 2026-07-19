import { createPlanId } from './ids.js';
import {
    SCHEMA_VERSION,
    createDefaultWorkspace,
    loadWorkspace,
    normalizeWorkspace,
    saveWorkspace,
} from './storage.js';

export const EXPORT_TYPE_WORKSPACE = 'bn-tools-sprint-workspace';
export const EXPORT_TYPE_INSTANCE = 'bn-tools-sprint-instance';

/**
 * @param {import('./storage.js').SpWorkspaceRoot} [workspace]
 */
export function buildWorkspaceExport(workspace) {
    const data = workspace || loadWorkspace().data;
    return {
        exportType: EXPORT_TYPE_WORKSPACE,
        schemaVersion: SCHEMA_VERSION,
        exportedAt: new Date().toISOString(),
        workspace: data.workspace,
        people: data.people,
        teams: data.teams,
        instances: data.instances,
    };
}

/**
 * @param {import('./storage.js').SpPlanInstance} instance
 * @param {import('./storage.js').SpWorkspaceRoot} [workspace]
 */
export function buildInstanceExport(instance, workspace) {
    const data = workspace || loadWorkspace().data;
    return {
        exportType: EXPORT_TYPE_INSTANCE,
        schemaVersion: SCHEMA_VERSION,
        exportedAt: new Date().toISOString(),
        instance,
        people: pickRelatedPeople(data, instance),
        teams: pickRelatedTeams(data, instance),
    };
}

function pickRelatedPeople(data, instance) {
    /** @type {Record<string, unknown>} */
    const out = {};
    for (const id of instance.participantIds || []) {
        if (data.people[id]) {
            out[id] = data.people[id];
        }
    }
    return out;
}

function pickRelatedTeams(data, instance) {
    /** @type {Record<string, unknown>} */
    const out = {};
    if (instance.teamId && data.teams[instance.teamId]) {
        out[instance.teamId] = data.teams[instance.teamId];
    }
    return out;
}

/**
 * @param {unknown} raw
 * @returns {{ok: true, data: object} | {ok: false, error: string}}
 */
export function validateImportPayload(raw) {
    if (!raw || typeof raw !== 'object') {
        return { ok: false, error: 'invalid-json' };
    }
    const data = /** @type {Record<string, unknown>} */ (raw);
    const exportType = String(data.exportType || '');
    if (exportType !== EXPORT_TYPE_WORKSPACE && exportType !== EXPORT_TYPE_INSTANCE) {
        return { ok: false, error: 'invalid-export-type' };
    }
    const schemaVersion = Number(data.schemaVersion);
    if (!Number.isFinite(schemaVersion) || schemaVersion < 1 || schemaVersion > SCHEMA_VERSION) {
        return { ok: false, error: 'unsupported-schema' };
    }
    if (exportType === EXPORT_TYPE_WORKSPACE) {
        if (!data.workspace || typeof data.workspace !== 'object') {
            return { ok: false, error: 'missing-fields' };
        }
    }
    if (exportType === EXPORT_TYPE_INSTANCE) {
        if (!data.instance || typeof data.instance !== 'object') {
            return { ok: false, error: 'missing-fields' };
        }
    }
    return { ok: true, data };
}

/**
 * @param {'replace'|'merge'|'new-ids'} mode
 * @param {object} payload
 * @param {import('./storage.js').SpWorkspaceRoot} [current]
 */
export function applyImport(mode, payload, current) {
    const existing = current || loadWorkspace().data;
    const validated = validateImportPayload(payload);
    if (!validated.ok) {
        return validated;
    }

    if (payload.exportType === EXPORT_TYPE_WORKSPACE) {
        if (mode === 'replace') {
            const next = normalizeWorkspace({
                schemaVersion: SCHEMA_VERSION,
                workspace: payload.workspace,
                people: payload.people || {},
                teams: payload.teams || {},
                instances: payload.instances || {},
            });
            const saved = saveWorkspace(next);
            return saved.ok ? { ok: true, data: next } : saved;
        }

        if (mode === 'merge') {
            const next = normalizeWorkspace({
                schemaVersion: SCHEMA_VERSION,
                workspace: { ...existing.workspace, ...payload.workspace },
                people: { ...existing.people, ...(payload.people || {}) },
                teams: { ...existing.teams, ...(payload.teams || {}) },
                instances: { ...existing.instances, ...(payload.instances || {}) },
            });
            const saved = saveWorkspace(next);
            return saved.ok ? { ok: true, data: next } : saved;
        }

        // new-ids
        /** @type {Record<string, unknown>} */
        const people = { ...existing.people };
        /** @type {Record<string, unknown>} */
        const teams = { ...existing.teams };
        /** @type {Record<string, unknown>} */
        const instances = { ...existing.instances };
        const personMap = {};
        const teamMap = {};

        for (const [id, person] of Object.entries(payload.people || {})) {
            const newId = id.startsWith('person_') ? `${id}_imp_${Date.now().toString(36)}` : id;
            personMap[id] = newId;
            people[newId] = { ...person, id: newId };
        }
        for (const [id, team] of Object.entries(payload.teams || {})) {
            const newId = `${id}_imp_${Date.now().toString(36)}`;
            teamMap[id] = newId;
            teams[newId] = {
                ...team,
                id: newId,
                memberIds: (team.memberIds || []).map((mid) => personMap[mid] || mid),
            };
        }
        for (const [, instance] of Object.entries(payload.instances || {})) {
            const newId = createPlanId();
            instances[newId] = {
                ...instance,
                id: newId,
                teamId: instance.teamId ? teamMap[instance.teamId] || instance.teamId : null,
                participantIds: (instance.participantIds || []).map((pid) => personMap[pid] || pid),
            };
        }

        const next = normalizeWorkspace({
            schemaVersion: SCHEMA_VERSION,
            workspace: existing.workspace,
            people,
            teams,
            instances,
        });
        const saved = saveWorkspace(next);
        return saved.ok ? { ok: true, data: next } : saved;
    }

    // instance export
    const instance = { ...payload.instance };
    let nextPeople = { ...existing.people, ...(payload.people || {}) };
    let nextTeams = { ...existing.teams, ...(payload.teams || {}) };

    if (mode === 'new-ids') {
        instance.id = createPlanId();
    } else if (mode === 'merge' && existing.instances[instance.id]) {
        instance.id = createPlanId();
    }

    const next = normalizeWorkspace({
        schemaVersion: SCHEMA_VERSION,
        workspace: existing.workspace,
        people: nextPeople,
        teams: nextTeams,
        instances: {
            ...existing.instances,
            ...(mode === 'replace' && payload.instance?.id
                ? { [payload.instance.id]: { ...payload.instance, id: payload.instance.id } }
                : { [instance.id]: instance }),
        },
    });

    if (mode === 'replace' && payload.instance?.id) {
        next.instances = {
            ...existing.instances,
            [payload.instance.id]: normalizeWorkspace({
                instances: { [payload.instance.id]: payload.instance },
            }).instances[payload.instance.id],
        };
        next.people = nextPeople;
        next.teams = nextTeams;
    }

    const saved = saveWorkspace(next);
    return saved.ok ? { ok: true, data: next } : saved;
}

/**
 * @param {object} data
 * @param {string} filename
 */
export function downloadJson(data, filename) {
    const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
    const url = URL.createObjectURL(blob);
    const anchor = document.createElement('a');
    anchor.href = url;
    anchor.download = filename;
    anchor.click();
    URL.revokeObjectURL(url);
}

export function resetToEmptyWorkspace() {
    const empty = createDefaultWorkspace();
    return saveWorkspace(empty);
}
