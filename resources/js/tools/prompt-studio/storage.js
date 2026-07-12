/** @typedef {'de' | 'en'} ToolsLocale */

/**
 * @typedef {Object} PromptDraftState
 * @property {string} roleId
 * @property {string} taskId
 * @property {string} modelId
 * @property {Record<string, unknown>} parameterValues
 * @property {Record<string, string>} sections
 * @property {Record<string, boolean>} sectionOverrides
 * @property {string} [title]
 * @property {string[]} [tags]
 */

/**
 * @typedef {Object} PromptWorkspaceState
 * @property {string[]} favorites
 * @property {PromptFolder[]} folders
 * @property {PromptCustomRole[]} customRoles
 * @property {Record<string, string[]>} itemFolders
 */

/**
 * @typedef {{ id: string, name: string, parentId?: string | null }} PromptFolder
 */

/**
 * @typedef {{ id: string, label: Record<ToolsLocale, string>, icon?: string, taskIds: string[] }} PromptCustomRole
 */

/**
 * @typedef {Object} PromptRecentEntry
 * @property {string} id
 * @property {string} title
 * @property {string} roleId
 * @property {string} taskId
 * @property {string} modelId
 * @property {string} compiled
 * @property {string} savedAt
 */

/**
 * @typedef {Object} PromptChainEntry
 * @property {string} id
 * @property {string} name
 * @property {PromptChainStep[]} steps
 * @property {string} savedAt
 */

/**
 * @typedef {{ id: string, roleId: string, taskId: string, label?: string, parameterValues?: Record<string, unknown> }} PromptChainStep
 */

/**
 * @typedef {Object} PromptSeriesEntry
 * @property {string} id
 * @property {string} name
 * @property {string} varyingFieldId
 * @property {string[]} values
 * @property {PromptDraftState} baseDraft
 * @property {string} savedAt
 */

/** @typedef {{ savedAt: string, source: 'auto' | 'manual' | 'import' }} PromptStorageMeta */

export const STORAGE_KEYS = {
    draft: 'binom-tools-prompt-studio-state',
    workspace: 'binom-tools-prompt-studio-workspace',
    templates: 'binom-tools-prompt-studio-templates',
    recent: 'binom-tools-prompt-studio-recent',
    chains: 'binom-tools-prompt-studio-chains',
    series: 'binom-tools-prompt-studio-series',
};

export const EVENT_NAMES = {
    updated: 'binom-tools:prompt-studio-updated',
};

const META_SUFFIX = '-meta';
const RECENT_MAX = 20;

/** @returns {PromptDraftState} */
export function createDefaultDraft() {
    return {
        roleId: '',
        taskId: '',
        modelId: '',
        parameterValues: {},
        sections: {},
        sectionOverrides: {},
        title: '',
        tags: [],
    };
}

/** @returns {PromptWorkspaceState} */
export function createDefaultWorkspace() {
    return {
        favorites: [],
        folders: [],
        customRoles: [],
        itemFolders: {},
    };
}

/**
 * @param {unknown} raw
 * @returns {PromptDraftState}
 */
export function normalizeDraft(raw) {
    const base = createDefaultDraft();
    if (!raw || typeof raw !== 'object') return base;

    const obj = /** @type {Record<string, unknown>} */ (raw);
    return {
        roleId: typeof obj.roleId === 'string' ? obj.roleId : base.roleId,
        taskId: typeof obj.taskId === 'string' ? obj.taskId : base.taskId,
        modelId: typeof obj.modelId === 'string' ? obj.modelId : base.modelId,
        parameterValues:
            obj.parameterValues && typeof obj.parameterValues === 'object'
                ? /** @type {Record<string, unknown>} */ (obj.parameterValues)
                : base.parameterValues,
        sections:
            obj.sections && typeof obj.sections === 'object'
                ? /** @type {Record<string, string>} */ (obj.sections)
                : base.sections,
        sectionOverrides:
            obj.sectionOverrides && typeof obj.sectionOverrides === 'object'
                ? /** @type {Record<string, boolean>} */ (obj.sectionOverrides)
                : base.sectionOverrides,
        title: typeof obj.title === 'string' ? obj.title : base.title,
        tags: Array.isArray(obj.tags) ? obj.tags.map(String) : base.tags,
    };
}

/**
 * @param {unknown} raw
 * @returns {PromptWorkspaceState}
 */
export function normalizeWorkspace(raw) {
    const base = createDefaultWorkspace();
    if (!raw || typeof raw !== 'object') return base;

    const obj = /** @type {Record<string, unknown>} */ (raw);
    return {
        favorites: Array.isArray(obj.favorites) ? obj.favorites.map(String) : base.favorites,
        folders: Array.isArray(obj.folders)
            ? obj.folders
                  .filter((f) => f && typeof f === 'object' && typeof f.id === 'string')
                  .map((f) => ({
                      id: String(f.id),
                      name: typeof f.name === 'string' ? f.name : 'Folder',
                      parentId: typeof f.parentId === 'string' ? f.parentId : null,
                  }))
            : base.folders,
        customRoles: Array.isArray(obj.customRoles)
            ? obj.customRoles
                  .filter((r) => r && typeof r === 'object' && typeof r.id === 'string')
                  .map((r) => ({
                      id: String(r.id),
                      label:
                          r.label && typeof r.label === 'object'
                              ? /** @type {Record<ToolsLocale, string>} */ (r.label)
                              : { de: String(r.id), en: String(r.id) },
                      icon: typeof r.icon === 'string' ? r.icon : undefined,
                      taskIds: Array.isArray(r.taskIds) ? r.taskIds.map(String) : [],
                  }))
            : base.customRoles,
        itemFolders:
            obj.itemFolders && typeof obj.itemFolders === 'object'
                ? /** @type {Record<string, string[]>} */ (obj.itemFolders)
                : base.itemFolders,
    };
}

/**
 * @template T
 * @param {string} key
 * @param {(raw: unknown) => T} normalize
 * @returns {{ data: T, meta: PromptStorageMeta } | { corrupt: true } | null}
 */
function loadJson(key, normalize) {
    try {
        const raw = localStorage.getItem(key);
        if (!raw) return null;
        const parsed = JSON.parse(raw);
        const metaRaw = localStorage.getItem(`${key}${META_SUFFIX}`);
        const meta = metaRaw
            ? /** @type {PromptStorageMeta} */ (JSON.parse(metaRaw))
            : { savedAt: '', source: 'manual' };
        return { data: normalize(parsed), meta };
    } catch {
        return { corrupt: true };
    }
}

/**
 * @param {unknown} loaded
 * @returns {boolean}
 */
export function isStorageLoadCorrupt(loaded) {
    return loaded !== null && typeof loaded === 'object' && 'corrupt' in loaded && loaded.corrupt === true;
}

/** @returns {{ data: PromptDraftState, meta: PromptStorageMeta } | { corrupt: true } | null} */
export function loadDraft() {
    return loadJson(STORAGE_KEYS.draft, normalizeDraft);
}

/** @returns {{ data: PromptWorkspaceState, meta: PromptStorageMeta } | { corrupt: true } | null} */
export function loadWorkspace() {
    return loadJson(STORAGE_KEYS.workspace, normalizeWorkspace);
}

/** @returns {{ data: unknown[], meta: PromptStorageMeta } | { corrupt: true } | null} */
export function loadTemplates() {
    return loadJson(STORAGE_KEYS.templates, (raw) => (Array.isArray(raw) ? raw : []));
}

/** @returns {{ data: PromptRecentEntry[], meta: PromptStorageMeta } | { corrupt: true } | null} */
export function loadRecent() {
    return loadJson(STORAGE_KEYS.recent, (raw) => (Array.isArray(raw) ? /** @type {PromptRecentEntry[]} */ (raw) : []));
}

/** @returns {{ data: PromptChainEntry[], meta: PromptStorageMeta } | { corrupt: true } | null} */
export function loadChains() {
    return loadJson(STORAGE_KEYS.chains, (raw) => (Array.isArray(raw) ? /** @type {PromptChainEntry[]} */ (raw) : []));
}

/** @returns {{ data: PromptSeriesEntry[], meta: PromptStorageMeta } | { corrupt: true } | null} */
export function loadSeries() {
    return loadJson(STORAGE_KEYS.series, (raw) => (Array.isArray(raw) ? /** @type {PromptSeriesEntry[]} */ (raw) : []));
}

/**
 * @param {string} key
 * @param {unknown} data
 * @param {PromptStorageMeta['source']} [source]
 */
function saveJson(key, data, source = 'manual') {
    const meta = /** @type {PromptStorageMeta} */ ({
        savedAt: new Date().toISOString(),
        source,
    });
    localStorage.setItem(key, JSON.stringify(data));
    localStorage.setItem(`${key}${META_SUFFIX}`, JSON.stringify(meta));
    window.dispatchEvent(
        new CustomEvent(EVENT_NAMES.updated, {
            detail: { key, data, meta },
        }),
    );
}

/** @param {PromptDraftState} draft @param {PromptStorageMeta['source']} [source] */
export function saveDraft(draft, source = 'manual') {
    saveJson(STORAGE_KEYS.draft, normalizeDraft(draft), source);
}

/** @param {PromptWorkspaceState} workspace @param {PromptStorageMeta['source']} [source] */
export function saveWorkspace(workspace, source = 'manual') {
    saveJson(STORAGE_KEYS.workspace, normalizeWorkspace(workspace), source);
}

/** @param {unknown[]} templates @param {PromptStorageMeta['source']} [source] */
export function saveTemplates(templates, source = 'manual') {
    saveJson(STORAGE_KEYS.templates, templates, source);
}

/** @param {PromptRecentEntry[]} recent @param {PromptStorageMeta['source']} [source] */
export function saveRecent(recent, source = 'manual') {
    saveJson(STORAGE_KEYS.recent, recent.slice(0, RECENT_MAX), source);
}

/** @param {PromptChainEntry[]} chains @param {PromptStorageMeta['source']} [source] */
export function saveChains(chains, source = 'manual') {
    saveJson(STORAGE_KEYS.chains, chains, source);
}

/** @param {PromptSeriesEntry[]} series @param {PromptStorageMeta['source']} [source] */
export function saveSeries(series, source = 'manual') {
    saveJson(STORAGE_KEYS.series, series, source);
}

/** @param {PromptRecentEntry} entry */
export function pushRecentEntry(entry) {
    const loaded = loadRecent();
    const list = loaded && 'data' in loaded ? loaded.data.filter((r) => r.id !== entry.id) : [];
    list.unshift(entry);
    saveRecent(list.slice(0, RECENT_MAX), 'auto');
}

export function clearDraft() {
    localStorage.removeItem(STORAGE_KEYS.draft);
    localStorage.removeItem(`${STORAGE_KEYS.draft}${META_SUFFIX}`);
    window.dispatchEvent(
        new CustomEvent(EVENT_NAMES.updated, {
            detail: { key: STORAGE_KEYS.draft, data: createDefaultDraft(), meta: null },
        }),
    );
}

/** @param {(payload: { key: string, data: unknown, meta: PromptStorageMeta | null }) => void} callback */
export function subscribePromptStudio(callback) {
    const onCustom = (/** @type {CustomEvent} */ event) => {
        const detail = event.detail ?? {};
        callback({
            key: detail.key ?? '',
            data: detail.data,
            meta: detail.meta ?? null,
        });
    };

    const onStorage = (/** @type {StorageEvent} */ event) => {
        if (!event.key || !Object.values(STORAGE_KEYS).includes(event.key)) return;
        const loaders = {
            [STORAGE_KEYS.draft]: loadDraft,
            [STORAGE_KEYS.workspace]: loadWorkspace,
            [STORAGE_KEYS.templates]: loadTemplates,
            [STORAGE_KEYS.recent]: loadRecent,
            [STORAGE_KEYS.chains]: loadChains,
            [STORAGE_KEYS.series]: loadSeries,
        };
        const loader = loaders[/** @type {keyof typeof loaders} */ (event.key)];
        if (!loader) return;
        const loaded = loader();
        if (loaded && 'data' in loaded) {
            callback({ key: event.key, data: loaded.data, meta: loaded.meta });
        }
    };

    window.addEventListener(EVENT_NAMES.updated, onCustom);
    window.addEventListener('storage', onStorage);

    return () => {
        window.removeEventListener(EVENT_NAMES.updated, onCustom);
        window.removeEventListener('storage', onStorage);
    };
}

let draftSaveTimer = 0;
let workspaceSaveTimer = 0;

/** @param {PromptDraftState} draft @param {PromptStorageMeta['source']} source */
export function debouncedSaveDraft(draft, source = 'auto') {
    window.clearTimeout(draftSaveTimer);
    draftSaveTimer = window.setTimeout(() => saveDraft(draft, source), 300);
}

/** @param {PromptWorkspaceState} workspace @param {PromptStorageMeta['source']} source */
export function debouncedSaveWorkspace(workspace, source = 'auto') {
    window.clearTimeout(workspaceSaveTimer);
    workspaceSaveTimer = window.setTimeout(() => saveWorkspace(workspace, source), 300);
}
