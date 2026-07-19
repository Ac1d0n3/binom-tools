import { createPersonId, createTeamId } from './ids.js';
import { loadWorkspace, saveWorkspace } from './storage.js';

export function listPeople(includeArchived = false) {
    const { data } = loadWorkspace();
    return Object.values(data.people).filter((p) => includeArchived || !p.archived);
}

export function listTeams(includeArchived = false) {
    const { data } = loadWorkspace();
    return Object.values(data.teams).filter((t) => includeArchived || !t.archived);
}

export function upsertPerson(input) {
    const loaded = loadWorkspace();
    const workspace = loaded.data;
    const id = input.id || createPersonId();
    workspace.people[id] = {
        id,
        displayName: String(input.displayName || '').trim(),
        shortName: String(input.shortName || '').trim(),
        email: String(input.email || '').trim(),
        role: String(input.role || '').trim(),
        colorToken: input.colorToken || 'accent-1',
        archived: Boolean(input.archived),
    };
    if (!workspace.people[id].displayName) {
        return { ok: false, error: 'validation' };
    }
    const saved = saveWorkspace(workspace);
    return saved.ok ? { ok: true, person: workspace.people[id] } : { ok: false, error: saved.error };
}

export function archivePerson(personId, archived = true) {
    const loaded = loadWorkspace();
    const workspace = loaded.data;
    if (!workspace.people[personId]) {
        return { ok: false, error: 'missing' };
    }
    workspace.people[personId].archived = archived;
    if (archived && workspace.workspace.activePersonId === personId) {
        workspace.workspace.activePersonId = null;
    }
    const saved = saveWorkspace(workspace);
    return saved.ok ? { ok: true } : { ok: false, error: saved.error };
}

export function upsertTeam(input) {
    const loaded = loadWorkspace();
    const workspace = loaded.data;
    const id = input.id || createTeamId();
    const nameDe = String(input.nameDe || '').trim();
    const nameEn = String(input.nameEn || nameDe).trim();
    if (!nameDe && !nameEn) {
        return { ok: false, error: 'validation' };
    }
    workspace.teams[id] = {
        id,
        name: { de: nameDe || nameEn, en: nameEn || nameDe },
        description: {
            de: String(input.descriptionDe || ''),
            en: String(input.descriptionEn || input.descriptionDe || ''),
        },
        memberIds: Array.isArray(input.memberIds) ? input.memberIds.map(String) : [],
        archived: Boolean(input.archived),
    };
    const saved = saveWorkspace(workspace);
    return saved.ok ? { ok: true, team: workspace.teams[id] } : { ok: false, error: saved.error };
}

export function archiveTeam(teamId, archived = true) {
    const loaded = loadWorkspace();
    const workspace = loaded.data;
    if (!workspace.teams[teamId]) {
        return { ok: false, error: 'missing' };
    }
    workspace.teams[teamId].archived = archived;
    if (archived && workspace.workspace.defaultTeamId === teamId) {
        workspace.workspace.defaultTeamId = null;
    }
    const saved = saveWorkspace(workspace);
    return saved.ok ? { ok: true } : { ok: false, error: saved.error };
}

export function setActivePerson(personId) {
    const loaded = loadWorkspace();
    const workspace = loaded.data;
    workspace.workspace.activePersonId = personId || null;
    const saved = saveWorkspace(workspace);
    return saved.ok ? { ok: true } : { ok: false, error: saved.error };
}

export function setDefaultTeam(teamId) {
    const loaded = loadWorkspace();
    const workspace = loaded.data;
    workspace.workspace.defaultTeamId = teamId || null;
    const saved = saveWorkspace(workspace);
    return saved.ok ? { ok: true } : { ok: false, error: saved.error };
}

/**
 * @param {{de?: string, en?: string}|string} value
 * @param {'de'|'en'} locale
 */
export function localizedText(value, locale) {
    if (typeof value === 'string') {
        return value;
    }
    if (!value || typeof value !== 'object') {
        return '';
    }
    return value[locale] || value.en || value.de || '';
}
