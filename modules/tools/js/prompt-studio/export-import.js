import {
    createDefaultDraft,
    createDefaultWorkspace,
    loadChains,
    loadDraft,
    loadSeries,
    loadTemplates,
    loadWorkspace,
    saveChains,
    saveDraft,
    saveSeries,
    saveTemplates,
    saveWorkspace,
} from './storage.js';

/** @typedef {import('./template-store.js').UserTemplate} UserTemplate */
/** @typedef {import('./storage.js').PromptDraftState} PromptDraftState */
/** @typedef {import('./storage.js').PromptWorkspaceState} PromptWorkspaceState */
/** @typedef {import('./storage.js').PromptChainEntry} PromptChainEntry */
/** @typedef {import('./storage.js').PromptSeriesEntry} PromptSeriesEntry */

const EXPORT_VERSION = 1;

/**
 * @typedef {Object} PromptStudioExportBundle
 * @property {number} version
 * @property {string} exportedAt
 * @property {PromptDraftState} [draft]
 * @property {PromptWorkspaceState} [workspace]
 * @property {UserTemplate[]} [templates]
 * @property {PromptChainEntry[]} [chains]
 * @property {PromptSeriesEntry[]} [series]
 */

/**
 * @param {{ includeDraft?: boolean, includeWorkspace?: boolean, includeTemplates?: boolean, includeChains?: boolean, includeSeries?: boolean }} [options]
 * @returns {PromptStudioExportBundle}
 */
export function buildExportBundle(options = {}) {
    const includeDraft = options.includeDraft !== false;
    const includeWorkspace = options.includeWorkspace !== false;
    const includeTemplates = options.includeTemplates !== false;
    const includeChains = options.includeChains !== false;
    const includeSeries = options.includeSeries !== false;

    /** @type {PromptStudioExportBundle} */
    const bundle = {
        version: EXPORT_VERSION,
        exportedAt: new Date().toISOString(),
    };

    if (includeDraft) {
        const draft = loadDraft();
        bundle.draft = draft && 'data' in draft ? draft.data : createDefaultDraft();
    }

    if (includeWorkspace) {
        const workspace = loadWorkspace();
        bundle.workspace = workspace && 'data' in workspace ? workspace.data : createDefaultWorkspace();
    }

    if (includeTemplates) {
        const templates = loadTemplates();
        bundle.templates = templates && 'data' in templates ? /** @type {UserTemplate[]} */ (templates.data) : [];
    }

    if (includeChains) {
        const chains = loadChains();
        bundle.chains = chains && 'data' in chains ? chains.data : [];
    }

    if (includeSeries) {
        const series = loadSeries();
        bundle.series = series && 'data' in series ? series.data : [];
    }

    return bundle;
}

/**
 * @param {PromptStudioExportBundle} bundle
 * @returns {Blob}
 */
export function exportToBlob(bundle) {
    return new Blob([JSON.stringify(bundle, null, 2)], { type: 'application/json' });
}

/**
 * @param {PromptStudioExportBundle} bundle
 * @param {string} [filename]
 */
export function downloadExport(bundle, filename = 'prompt-studio-export.json') {
    const blob = exportToBlob(bundle);
    const url = URL.createObjectURL(blob);
    const anchor = document.createElement('a');
    anchor.href = url;
    anchor.download = filename;
    anchor.click();
    URL.revokeObjectURL(url);
}

/**
 * @param {File} file
 * @returns {Promise<PromptStudioExportBundle>}
 */
export function readImportFile(file) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onload = () => {
            try {
                const parsed = JSON.parse(String(reader.result));
                resolve(/** @type {PromptStudioExportBundle} */ (parsed));
            } catch (error) {
                reject(error);
            }
        };
        reader.onerror = () => reject(reader.error ?? new Error('Failed to read file'));
        reader.readAsText(file);
    });
}

/**
 * @param {unknown} raw
 * @returns {{ valid: boolean, bundle?: PromptStudioExportBundle, error?: string }}
 */
export function validateImportBundle(raw) {
    if (!raw || typeof raw !== 'object') {
        return { valid: false, error: 'Import file must be a JSON object' };
    }

    const bundle = /** @type {PromptStudioExportBundle} */ (raw);
    if (typeof bundle.version !== 'number') {
        return { valid: false, error: 'Missing export version' };
    }

    return { valid: true, bundle };
}

/**
 * @param {PromptStudioExportBundle} bundle
 * @param {{ merge?: boolean }} [options]
 */
/**
 * @param {PromptStudioExportBundle} bundle
 * @param {{ merge?: boolean }} [options]
 */
export function applyImportBundle(bundle, options = {}) {
    const merge = options.merge === true;

    if (bundle.draft) {
        if (merge) {
            const existing = loadDraft();
            const current = existing && 'data' in existing ? existing.data : createDefaultDraft();
            saveDraft({ ...current, ...bundle.draft }, 'import');
        } else {
            saveDraft(bundle.draft, 'import');
        }
    }

    if (bundle.workspace) {
        if (merge) {
            const existing = loadWorkspace();
            const current = existing && 'data' in existing ? existing.data : createDefaultWorkspace();
            saveWorkspace(
                {
                    ...current,
                    favorites: [...new Set([...current.favorites, ...(bundle.workspace.favorites ?? [])])],
                    folders: [...current.folders, ...(bundle.workspace.folders ?? [])],
                    customRoles: [...current.customRoles, ...(bundle.workspace.customRoles ?? [])],
                    itemFolders: { ...current.itemFolders, ...(bundle.workspace.itemFolders ?? {}) },
                },
                'import',
            );
        } else {
            saveWorkspace(bundle.workspace, 'import');
        }
    }

    if (bundle.templates) {
        if (merge) {
            const existing = loadTemplates();
            const current = existing && 'data' in existing ? /** @type {UserTemplate[]} */ (existing.data) : [];
            const map = new Map(current.map((tpl) => [tpl.id, tpl]));
            for (const tpl of bundle.templates) map.set(tpl.id, tpl);
            saveTemplates([...map.values()], 'import');
        } else {
            saveTemplates(bundle.templates, 'import');
        }
    }

    if (bundle.chains) {
        saveChains(bundle.chains, 'import');
    }

    if (bundle.series) {
        saveSeries(bundle.series, 'import');
    }
}

export function downloadExportBundle(bundle, filename = 'prompt-studio-export.json') {
    downloadExport(bundle, filename);
}

/** @param {PromptStudioExportBundle} bundle @param {{ merge?: boolean }} [options] */
export function importBundle(bundle, options = {}) {
    applyImportBundle(bundle, options);
}
