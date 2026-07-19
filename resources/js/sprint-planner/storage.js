import {
    catalogFromAccounts,
    readAccountsBootstrap,
    upsertPlanOnServer,
    usesServerPlans,
    usesSessionDemoStore,
} from './accounts-bridge.js';
import { ensureDefaultCatalog } from './defaults.js';

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
 * @property {boolean} archived
 */

/**
 * @typedef {Object} SpTeam
 * @property {string} id
 * @property {{de: string, en: string}} name
 * @property {{de: string, en: string}} description
 * @property {string[]} memberIds
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
 * @property {string|null} teamId
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
            myTasks: false,
            unassigned: false,
            personId: '',
            teamId: '',
            status: '',
            priority: '',
        },
        planFiltersVersion: 3,
        expandedSprints: {},
        manualCurrentSprint: {},
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
        people: isPlainObject(migrated.people) ? /** @type {Record<string, SpPerson>} */ (migrated.people) : {},
        teams: isPlainObject(migrated.teams) ? /** @type {Record<string, SpTeam>} */ (migrated.teams) : {},
        instances: isPlainObject(migrated.instances)
            ? normalizeInstances(/** @type {Record<string, unknown>} */ (migrated.instances))
            : {},
    };
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
            teamId: item.teamId ? String(item.teamId) : null,
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

    // Recover identity fields wiped on the server from the local cache (same plan id).
    for (const [id, local] of Object.entries(localWorkspace.instances || {})) {
        if (local.ephemeral) {
            continue;
        }
        const server = serverInstances[id];
        if (!server) {
            continue;
        }
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
            shortName: displayName.slice(0, 2).toUpperCase(),
            email: String(accountUser.email || ''),
            role: '',
            colorToken: 'accent-1',
            archived: false,
        };
    }

    return {
        schemaVersion: SCHEMA_VERSION,
        workspace: {
            ...base.workspace,
            ...localWorkspace.workspace,
            name: 'Shared Workspace',
            activePersonId,
            defaultTeamId: localWorkspace.workspace.defaultTeamId
                && catalog.teams[localWorkspace.workspace.defaultTeamId]
                ? localWorkspace.workspace.defaultTeamId
                : null,
        },
        people: catalog.people,
        teams: catalog.teams,
        instances: { ...serverInstances, ...ephemeralLocal },
    };
}

/**
 * @param {SpWorkspaceRoot} workspace
 * @returns {{ok: true} | {ok: false, error: string}}
 */
/** @type {Promise<void>} */
let accountsSyncQueue = Promise.resolve();

/**
 * @param {SpWorkspaceRoot} workspace
 * @param {{ dirtyPlanIds?: string[] }} [options]
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
            accountsSyncQueue = accountsSyncQueue
                .then(async () => {
                    for (const plan of Object.values(payload.instances)) {
                        if (plan.ephemeral) {
                            continue;
                        }
                        if (dirtyIds && !dirtyIds.has(String(plan.id))) {
                            continue;
                        }
                        await upsertPlanOnServer(plan, bootstrap.plansApiUrl);
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
