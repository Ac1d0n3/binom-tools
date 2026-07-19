import {
    catalogFromAccounts,
    readAccountsBootstrap,
    upsertPlanOnServer,
    usesServerPlans,
    usesSessionDemoStore,
} from './accounts-bridge.js';
import { ensureDefaultCatalog } from './defaults.js';
import {
    normalizeAvatarIcon,
    normalizeColorToken,
    normalizeTeamIds,
    normalizeTrigram,
    teamTrigram,
} from './trigram.js';

/** @typedef {'de'|'en'} SpLocale */

export const WORKSPACE_KEY = 'bn-tools:sprint-planner:workspace:v1';
/** Guest demo plans when accounts are enabled (session only, not persistent). */
export const DEMO_WORKSPACE_KEY = 'bn-tools:sprint-planner:demo:v1';
export const PREFERENCES_KEY = 'bn-tools:sprint-planner:preferences:v1';
export const SCHEMA_VERSION = 1;
export const WORKSPACE_EVENT = 'bn-tools:sprint-planner:workspace';

/**
 * @typedef {Object} SpPerson
 * @property {string} id
 * @property {string} displayName
 * @property {string} shortName
 * @property {string} email
 * @property {string} role
 * @property {string} colorToken
 * @property {string} [avatarIcon]
 * @property {boolean} archived
 */

/**
 * @typedef {Object} SpTeam
 * @property {string} id
 * @property {{de: string, en: string}} name
 * @property {{de: string, en: string}} description
 * @property {string} shortName
 * @property {string} colorToken
 * @property {string} [avatarIcon]
 * @property {string[]} memberIds
 * @property {Record<string, string>} [memberRoles]
 * @property {boolean} archived
 */

/**
 * @typedef {Object} SpPlanInstance
 * @property {string} id
 * @property {string} templateSlug
 * @property {number} templateVersion
 * @property {{de?: {title?: string, description?: string}, en?: {title?: string, description?: string}}} translations
 * @property {string} startedAt
 * @property {'active'|'completed'|'archived'} status
 * @property {string[]} teamIds
 * @property {string|null} [teamId] legacy single team (migrated into teamIds)
 * @property {string[]} participantIds
 * @property {string[]} completedTasks
 * @property {string[]} completedDeliverables
 * @property {Record<string, unknown>} fieldValues
 * @property {Record<string, string>} sprintNotes
 * @property {Record<string, Array<Record<string, unknown>>>} customTasks
 * @property {Record<string, Array<Record<string, unknown>>>} customDeliverables
 * @property {Array<Record<string, unknown>>} customSprints
 * @property {Record<string, Record<string, unknown>>} sprintOverrides
 * @property {Record<string, Record<string, unknown>>} itemOverrides
 * @property {string[]} [removedItemKeys] status keys of template items removed from this plan
 * @property {string|null} [passwordHash]
 * @property {string|null} [passwordSalt]
 * @property {string|null} [ownerUserId]
 * @property {string[]} [viewerUserIds]
 * @property {string[]} [viewerTeamIds]
 * @property {string[]} [linkedStorySlugs]
 * @property {boolean} [ephemeral]
 * @property {boolean} archived
 * @property {string} createdAt
 * @property {string} updatedAt
 */

/**
 * @typedef {Object} SpWorkspaceRoot
 * @property {number} schemaVersion
 * @property {{id: string, name: string, locale: SpLocale, activePersonId: string|null, defaultTeamId: string|null}} workspace
 * @property {Record<string, SpPerson>} people
 * @property {Record<string, SpTeam>} teams
 * @property {Record<string, SpPlanInstance>} instances
 */

/**
 * @returns {SpWorkspaceRoot}
 */
export function createDefaultWorkspace() {
    return {
        schemaVersion: SCHEMA_VERSION,
        workspace: {
            id: 'workspace_default',
            name: 'Local Workspace',
            locale: 'en',
            activePersonId: null,
            defaultTeamId: null,
        },
        people: {},
        teams: {},
        instances: {},
    };
}

/**
 * @returns {Record<string, unknown>}
 */
export function createDefaultPreferences() {
    return {
        schemaVersion: SCHEMA_VERSION,
        overviewFilter: 'all',
        planFilters: {
            currentWeek: false,
            hideDone: false,
            openOnly: false,
            blocked: false,
            myTasks: true,
            unassigned: true,
            personId: '',
            teamId: '',
            status: '',
            priority: '',
            filterLogic: 'or',
            search: '',
        },
        planFiltersVersion: 7,
        expandedSprints: {},
        expandedItemTables: {},
        expandedSprintFlows: {},
        planHeaderExpanded: {},
        planFilterSidebarCollapsed: {},
        manualCurrentSprint: {},
        lastOpenedPlanId: null,
        blockersExpanded: {},
        statusBanner: {},
    };
}

/**
 * @param {unknown} raw
 * @returns {SpWorkspaceRoot}
 */
export function normalizeWorkspace(raw) {
    const base = createDefaultWorkspace();
    if (!raw || typeof raw !== 'object') {
        return base;
    }

    const data = /** @type {Record<string, unknown>} */ (raw);
    const schemaVersion = Number(data.schemaVersion) || 1;
    const migrated = migrateWorkspace(data, schemaVersion);

    const workspace = migrated.workspace && typeof migrated.workspace === 'object'
        ? { ...base.workspace, .../** @type {object} */ (migrated.workspace) }
        : base.workspace;

    return {
        schemaVersion: SCHEMA_VERSION,
        workspace: {
            id: String(workspace.id || 'workspace_default'),
            name: String(workspace.name || 'Local Workspace'),
            locale: workspace.locale === 'de' ? 'de' : 'en',
            activePersonId: workspace.activePersonId ? String(workspace.activePersonId) : null,
            defaultTeamId: workspace.defaultTeamId ? String(workspace.defaultTeamId) : null,
        },
        people: isPlainObject(migrated.people)
            ? normalizePeople(/** @type {Record<string, unknown>} */ (migrated.people))
            : {},
        teams: isPlainObject(migrated.teams)
            ? normalizeTeams(/** @type {Record<string, unknown>} */ (migrated.teams))
            : {},
        instances: isPlainObject(migrated.instances)
            ? normalizeInstances(/** @type {Record<string, unknown>} */ (migrated.instances))
            : {},
    };
}

/**
 * @param {Record<string, unknown>} people
 * @returns {Record<string, SpPerson>}
 */
function normalizePeople(people) {
    /** @type {Record<string, SpPerson>} */
    const out = {};
    for (const [id, value] of Object.entries(people)) {
        if (!value || typeof value !== 'object') {
            continue;
        }
        const item = /** @type {Record<string, unknown>} */ (value);
        const displayName = String(item.displayName || '').trim();
        out[id] = {
            id: String(item.id || id),
            displayName,
            shortName: normalizeTrigram(String(item.shortName || ''), displayName),
            email: String(item.email || ''),
            role: String(item.role || ''),
            colorToken: normalizeColorToken(String(item.colorToken || '')),
            avatarIcon: normalizeAvatarIcon(item.avatarIcon),
            archived: Boolean(item.archived),
        };
    }
    return out;
}

/**
 * @param {Record<string, unknown>} teams
 * @returns {Record<string, SpTeam>}
 */
function normalizeTeams(teams) {
    /** @type {Record<string, SpTeam>} */
    const out = {};
    for (const [id, value] of Object.entries(teams)) {
        if (!value || typeof value !== 'object') {
            continue;
        }
        const item = /** @type {Record<string, unknown>} */ (value);
        const name = isPlainObject(item.name)
            ? {
                de: String(/** @type {any} */ (item.name).de || ''),
                en: String(/** @type {any} */ (item.name).en || ''),
            }
            : { de: String(item.name || ''), en: String(item.name || '') };
        const icon = normalizeAvatarIcon(item.avatarIcon);
        const shortName = item.shortName
            ? normalizeTrigram(String(item.shortName), '')
            : (icon ? '' : teamTrigram({ name }, 'en'));
        out[id] = {
            id: String(item.id || id),
            name,
            description: isPlainObject(item.description)
                ? {
                    de: String(/** @type {any} */ (item.description).de || ''),
                    en: String(/** @type {any} */ (item.description).en || ''),
                }
                : { de: '', en: '' },
            shortName,
            colorToken: normalizeColorToken(String(item.colorToken || '')),
            avatarIcon: icon,
            memberIds: Array.isArray(item.memberIds) ? item.memberIds.map(String) : [],
            memberRoles: isPlainObject(item.memberRoles)
                ? Object.fromEntries(
                    Object.entries(/** @type {Record<string, unknown>} */ (item.memberRoles))
                        .map(([uid, role]) => [String(uid), String(role || 'member')]),
                )
                : {},
            archived: Boolean(item.archived),
        };
    }
    return out;
}

/**
 * @param {Record<string, unknown>} instances
 * @returns {Record<string, SpPlanInstance>}
 */
function normalizeInstances(instances) {
    /** @type {Record<string, SpPlanInstance>} */
    const out = {};
    for (const [id, value] of Object.entries(instances)) {
        if (!value || typeof value !== 'object') {
            continue;
        }
        const item = /** @type {Record<string, unknown>} */ (value);
        out[id] = {
            id: String(item.id || id),
            templateSlug: String(item.templateSlug || ''),
            templateVersion: Number(item.templateVersion) || 1,
            translations: isPlainObject(item.translations) ? /** @type {any} */ (item.translations) : {},
            startedAt: String(item.startedAt || ''),
            status: /** @type {any} */ (['active', 'completed', 'archived'].includes(String(item.status))
                ? item.status
                : 'active'),
            teamIds: normalizeTeamIds(item),
            teamId: null,
            participantIds: Array.isArray(item.participantIds) ? item.participantIds.map(String) : [],
            completedTasks: Array.isArray(item.completedTasks) ? item.completedTasks.map(String) : [],
            completedDeliverables: Array.isArray(item.completedDeliverables)
                ? item.completedDeliverables.map(String)
                : [],
            fieldValues: isPlainObject(item.fieldValues) ? /** @type {any} */ (item.fieldValues) : {},
            sprintNotes: isPlainObject(item.sprintNotes) ? /** @type {any} */ (item.sprintNotes) : {},
            customTasks: isPlainObject(item.customTasks) ? /** @type {any} */ (item.customTasks) : {},
            customDeliverables: isPlainObject(item.customDeliverables)
                ? /** @type {any} */ (item.customDeliverables)
                : {},
            customSprints: Array.isArray(item.customSprints) ? /** @type {any} */ (item.customSprints) : [],
            sprintOverrides: isPlainObject(item.sprintOverrides) ? /** @type {any} */ (item.sprintOverrides) : {},
            itemOverrides: isPlainObject(item.itemOverrides) ? /** @type {any} */ (item.itemOverrides) : {},
            removedItemKeys: Array.isArray(item.removedItemKeys) ? item.removedItemKeys.map(String) : [],
            templateSnapshot: isPlainObject(item.templateSnapshot) ? /** @type {any} */ (item.templateSnapshot) : null,
            passwordHash: item.passwordHash ? String(item.passwordHash) : null,
            passwordSalt: item.passwordSalt ? String(item.passwordSalt) : null,
            ownerUserId: item.ownerUserId ? String(item.ownerUserId) : null,
            viewerUserIds: Array.isArray(item.viewerUserIds) ? item.viewerUserIds.map(String) : [],
            viewerTeamIds: Array.isArray(item.viewerTeamIds) ? item.viewerTeamIds.map(String) : [],
            linkedStorySlugs: Array.isArray(item.linkedStorySlugs) ? item.linkedStorySlugs.map(String) : [],
            ephemeral: Boolean(item.ephemeral),
            archived: Boolean(item.archived),
            createdAt: String(item.createdAt || new Date().toISOString()),
            updatedAt: String(item.updatedAt || new Date().toISOString()),
        };
    }
    return out;
}

/**
 * @param {Record<string, unknown>} data
 * @param {number} fromVersion
 */
function migrateWorkspace(data, fromVersion) {
    if (fromVersion > SCHEMA_VERSION) {
        throw new Error(`Unsupported workspace schema version: ${fromVersion}`);
    }
    return data;
}

/**
 * @returns {{ok: true, data: SpWorkspaceRoot} | {ok: false, error: string, data: SpWorkspaceRoot}}
 */
export function loadWorkspace() {
    try {
        if (usesServerPlans()) {
            return { ok: true, data: loadAccountsWorkspace() };
        }

        const sessionDemo = usesSessionDemoStore();
        const storage = sessionDemo ? sessionStorageBucket() : localStorageBucket();
        const key = sessionDemo ? DEMO_WORKSPACE_KEY : WORKSPACE_KEY;

        if (!storage) {
            const seeded = ensureDefaultCatalog(createDefaultWorkspace());
            return { ok: false, error: 'storage-unavailable', data: seeded.workspace };
        }
        const raw = storage.getItem(key);
        let data;
        let error = null;
        if (!raw) {
            data = createDefaultWorkspace();
        } else {
            try {
                data = normalizeWorkspace(JSON.parse(raw));
            } catch {
                error = 'storage-corrupt';
                data = createDefaultWorkspace();
            }
        }

        const seeded = ensureDefaultCatalog(data);
        if (seeded.changed) {
            try {
                storage.setItem(key, JSON.stringify({
                    ...seeded.workspace,
                    schemaVersion: SCHEMA_VERSION,
                }));
            } catch {
                // Keep in-memory defaults even if persist fails.
            }
        }

        if (error) {
            return { ok: false, error, data: seeded.workspace };
        }
        return { ok: true, data: seeded.workspace };
    } catch (error) {
        const message = error instanceof Error && error.message.includes('Unsupported')
            ? 'unsupported-schema'
            : 'storage-error';
        const seeded = ensureDefaultCatalog(createDefaultWorkspace());
        return { ok: false, error: message, data: seeded.workspace };
    }
}

/**
 * @returns {SpWorkspaceRoot}
 */
function loadAccountsWorkspace() {
    const bootstrap = readAccountsBootstrap();
    const catalog = catalogFromAccounts(bootstrap.people, bootstrap.teams);
    const base = createDefaultWorkspace();

    /** @type {SpWorkspaceRoot} */
    let localWorkspace = base;
    try {
        if (storageAvailable()) {
            const raw = localStorage.getItem(WORKSPACE_KEY);
            if (raw) {
                localWorkspace = normalizeWorkspace(JSON.parse(raw));
            }
        }
    } catch {
        // ignore corrupt local cache
    }

    const serverInstances = normalizeInstances(
        Object.fromEntries(
            (bootstrap.serverPlans || [])
                .filter((plan) => plan && typeof plan === 'object' && plan.id)
                .map((plan) => [String(plan.id), plan]),
        ),
    );

    // Prefer the local cache when it is as new or newer than the page bootstrap.
    // Otherwise claim/assign updates vanish on the next render (bootstrap stays stale until reload).
    for (const [id, local] of Object.entries(localWorkspace.instances || {})) {
        if (local.ephemeral) {
            continue;
        }
        const server = serverInstances[id];
        if (!server) {
            // Plan created this session and not yet in the HTML bootstrap.
            serverInstances[id] = local;
            continue;
        }
        const localUpdated = String(local.updatedAt || '');
        const serverUpdated = String(server.updatedAt || '');
        if (localUpdated && localUpdated >= serverUpdated) {
            serverInstances[id] = local;
            continue;
        }
        // Server newer: still heal hollow identity from local.
        if (!server.templateSlug && local.templateSlug) {
            server.templateSlug = local.templateSlug;
        }
        if (!server.templateSnapshot && local.templateSnapshot) {
            server.templateSnapshot = local.templateSnapshot;
        }
        if (!server.startedAt && local.startedAt) {
            server.startedAt = local.startedAt;
        }
        if (
            (!server.translations || Object.keys(server.translations).length === 0)
            && local.translations
            && Object.keys(local.translations).length > 0
        ) {
            server.translations = local.translations;
        }
        const serverTeams = normalizeTeamIds(server);
        const localTeams = normalizeTeamIds(local);
        if (!serverTeams.length && localTeams.length) {
            server.teamIds = localTeams;
            server.teamId = null;
        }
    }

    // Keep local-only demo (ephemeral) plans alongside server plans.
    const ephemeralLocal = Object.fromEntries(
        Object.entries(localWorkspace.instances || {}).filter(([, plan]) => plan.ephemeral),
    );

    const accountUser = bootstrap.accountUser;
    const activePersonId = accountUser?.id
        ? String(accountUser.id)
        : (localWorkspace.workspace.activePersonId && catalog.people[localWorkspace.workspace.activePersonId]
            ? localWorkspace.workspace.activePersonId
            : null);

    // Ensure the signed-in user is always claimable / filterable as a person.
    if (accountUser?.id && !catalog.people[String(accountUser.id)]) {
        const id = String(accountUser.id);
        const displayName = String(accountUser.displayName || accountUser.email || id);
        catalog.people[id] = {
            id,
            displayName,
            shortName: normalizeTrigram(String(accountUser.shortName || ''), displayName),
            email: String(accountUser.email || ''),
            role: '',
            colorToken: normalizeColorToken(String(accountUser.colorToken || 'accent-1')),
            avatarIcon: String(accountUser.avatarIcon || ''),
            archived: false,
        };
    }

    const defaultTeamId = resolveAccountsDefaultTeamId(
        localWorkspace.workspace.defaultTeamId,
        catalog.teams,
    );

    // Empty plan teams fall back to the workspace default (usually Team Q).
    if (defaultTeamId) {
        for (const instance of Object.values(serverInstances)) {
            if (!normalizeTeamIds(instance).length) {
                instance.teamIds = [defaultTeamId];
                instance.teamId = null;
            }
        }
        for (const instance of Object.values(ephemeralLocal)) {
            if (!normalizeTeamIds(instance).length) {
                instance.teamIds = [defaultTeamId];
                instance.teamId = null;
            }
        }
    }

    return {
        schemaVersion: SCHEMA_VERSION,
        workspace: {
            ...base.workspace,
            ...localWorkspace.workspace,
            name: 'Shared Workspace',
            activePersonId,
            defaultTeamId,
        },
        people: catalog.people,
        teams: catalog.teams,
        instances: { ...serverInstances, ...ephemeralLocal },
    };
}

/**
 * Prefer local default when present in the accounts catalog, else Team Q, else first team.
 * @param {string|null|undefined} localDefault
 * @param {Record<string, unknown>} teams
 * @returns {string|null}
 */
function resolveAccountsDefaultTeamId(localDefault, teams) {
    const local = localDefault ? String(localDefault) : '';
    if (local && teams[local]) {
        return local;
    }
    if (teams.team_q) {
        return 'team_q';
    }
    for (const [id, team] of Object.entries(teams)) {
        const name = team && typeof team === 'object' && team.name && typeof team.name === 'object'
            ? team.name
            : null;
        const label = String(name?.en || name?.de || '').trim().toLowerCase();
        if (label === 'team q') {
            return id;
        }
    }
    const first = Object.keys(teams)[0];
    return first || null;
}

/**
 * @param {SpWorkspaceRoot} workspace
 * @returns {{ok: true} | {ok: false, error: string}}
 */
/** @type {Promise<void>} */
let accountsSyncQueue = Promise.resolve();

/**
 * @param {SpWorkspaceRoot} workspace
 * @param {{ dirtyPlanIds?: string[], historyByPlanId?: Record<string, {action?: string, summary?: string}> }} [options]
 *   When set, only those non-ephemeral plans are POSTed (avoids overwriting siblings from a stale page bootstrap).
 */
export function saveWorkspace(workspace, options = {}) {
    try {
        const payload = normalizeWorkspace(workspace);
        payload.schemaVersion = SCHEMA_VERSION;

        if (usesServerPlans()) {
            // Cache meta + ephemeral plans in localStorage; non-ephemeral sync to server.
            // Do not mix with guest session-demo storage.
            if (storageAvailable()) {
                try {
                    localStorage.setItem(WORKSPACE_KEY, JSON.stringify(payload));
                } catch {
                    // ignore quota for cache
                }
            }
            const bootstrap = readAccountsBootstrap();
            const dirtyIds = Array.isArray(options.dirtyPlanIds)
                ? new Set(options.dirtyPlanIds.map(String))
                : null;
            const historyByPlanId = options.historyByPlanId && typeof options.historyByPlanId === 'object'
                ? options.historyByPlanId
                : {};
            accountsSyncQueue = accountsSyncQueue
                .then(async () => {
                    for (const plan of Object.values(payload.instances)) {
                        if (plan.ephemeral) {
                            continue;
                        }
                        if (dirtyIds && !dirtyIds.has(String(plan.id))) {
                            continue;
                        }
                        await upsertPlanOnServer(
                            plan,
                            bootstrap.plansApiUrl,
                            historyByPlanId[String(plan.id)] || {},
                        );
                    }
                })
                .catch(() => {
                    // Errors surface via toast on next user action if needed.
                });
            window.dispatchEvent(new CustomEvent(WORKSPACE_EVENT, { detail: { workspace: payload } }));
            return { ok: true };
        }

        const sessionDemo = usesSessionDemoStore();
        const storage = sessionDemo ? sessionStorageBucket() : localStorageBucket();
        const key = sessionDemo ? DEMO_WORKSPACE_KEY : WORKSPACE_KEY;
        if (!storage) {
            return { ok: false, error: 'storage-unavailable' };
        }
        storage.setItem(key, JSON.stringify(payload));
        window.dispatchEvent(new CustomEvent(WORKSPACE_EVENT, { detail: { workspace: payload } }));
        return { ok: true };
    } catch (error) {
        const quota = error instanceof DOMException && (
            error.name === 'QuotaExceededError' || error.code === 22
        );
        return { ok: false, error: quota ? 'storage-full' : 'storage-error' };
    }
}

/**
 * @returns {Record<string, unknown>}
 */
export function loadPreferences() {
    try {
        if (!storageAvailable()) {
            return createDefaultPreferences();
        }
        const raw = localStorage.getItem(PREFERENCES_KEY);
        if (!raw) {
            return createDefaultPreferences();
        }
        const parsed = JSON.parse(raw);
        return { ...createDefaultPreferences(), ...(isPlainObject(parsed) ? parsed : {}) };
    } catch {
        return createDefaultPreferences();
    }
}

/**
 * @param {Record<string, unknown>} prefs
 */
export function savePreferences(prefs) {
    try {
        if (!storageAvailable()) {
            return false;
        }
        localStorage.setItem(PREFERENCES_KEY, JSON.stringify({
            ...createDefaultPreferences(),
            ...prefs,
            schemaVersion: SCHEMA_VERSION,
        }));
        return true;
    } catch {
        return false;
    }
}

/**
 * @param {string|null|undefined} planId
 */
export function setLastOpenedPlanId(planId) {
    const prefs = loadPreferences();
    const next = planId ? String(planId) : null;
    if (prefs.lastOpenedPlanId === next) {
        return;
    }
    savePreferences({ ...prefs, lastOpenedPlanId: next });
}

/**
 * Clear remembered plan when it was deleted/archived.
 * @param {string} planId
 */
export function clearLastOpenedPlanIdIf(planId) {
    const prefs = loadPreferences();
    if (String(prefs.lastOpenedPlanId || '') !== String(planId || '')) {
        return;
    }
    savePreferences({ ...prefs, lastOpenedPlanId: null });
}

export function clearWorkspaceStorage() {
    let cleared = false;
    if (storageAvailable()) {
        localStorage.removeItem(WORKSPACE_KEY);
        localStorage.removeItem(PREFERENCES_KEY);
        cleared = true;
    }
    if (sessionStorageAvailable()) {
        sessionStorage.removeItem(DEMO_WORKSPACE_KEY);
        cleared = true;
    }
    if (!cleared) {
        return false;
    }
    window.dispatchEvent(new CustomEvent(WORKSPACE_EVENT, { detail: { workspace: createDefaultWorkspace() } }));
    return true;
}

function storageAvailable() {
    return probeStorage(localStorage);
}

function sessionStorageAvailable() {
    return probeStorage(sessionStorage);
}

/**
 * @returns {Storage|null}
 */
function localStorageBucket() {
    return storageAvailable() ? localStorage : null;
}

/**
 * @returns {Storage|null}
 */
function sessionStorageBucket() {
    return sessionStorageAvailable() ? sessionStorage : null;
}

/**
 * @param {Storage} store
 */
function probeStorage(store) {
    try {
        const key = '__sp_test__';
        store.setItem(key, '1');
        store.removeItem(key);
        return true;
    } catch {
        return false;
    }
}

/**
 * @param {unknown} value
 */
function isPlainObject(value) {
    return Boolean(value) && typeof value === 'object' && !Array.isArray(value);
}
