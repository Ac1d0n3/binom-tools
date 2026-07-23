import {
    validateConfig,
    validateManifest,
    validateRoles,
    validateTasks,
    validateParameters,
    validateModels,
    validateTemplates,
    validateChains,
    validateMetaPrompts,
} from './config-validator.js';
import { getTaskOutputKind } from './md-export.js';

/** @typedef {import('./config-validator.js').PromptStudioConfig} PromptStudioConfig */
/** @typedef {import('./config-validator.js').PromptRoleDef} PromptRoleDef */
/** @typedef {import('./config-validator.js').PromptTaskDef} PromptTaskDef */
/** @typedef {import('./config-validator.js').PromptParameterDef} PromptParameterDef */
/** @typedef {import('./config-validator.js').PromptModelDef} PromptModelDef */
/** @typedef {import('./config-validator.js').PromptTemplateDef} PromptTemplateDef */

/**
 * @param {PromptTaskDef[] | undefined} tasks
 * @returns {PromptTaskDef[]}
 */
function withNormalizedOutputKinds(tasks) {
    return (tasks ?? []).map((task) => ({
        ...task,
        outputKind: getTaskOutputKind(task),
    }));
}

/**
 * @param {string} path
 * @param {string} appRoot
 * @returns {string}
 */
function withAppRoot(path, appRoot) {
    if (!appRoot || path === appRoot || path.startsWith(`${appRoot}/`)) {
        return path;
    }

    return `${appRoot}${path.startsWith('/') ? path : `/${path}`}`;
}

/**
 * Resolve config base to a same-origin path (robust for subfolder deploys and legacy asset() URLs).
 * @param {string | undefined} rawBase
 * @param {string} [appBase]
 * @returns {string}
 */
export function resolveConfigBase(rawBase, appBase = '') {
    const appRoot = appBase.replace(/\/$/, '');
    const fallback = withAppRoot('/tools/prompt-studio/config', appRoot);

    const raw = rawBase?.trim();
    if (!raw) {
        return fallback;
    }

    let path = fallback;

    if (/^https?:\/\//i.test(raw)) {
        try {
            const pathname = new URL(raw).pathname.replace(/\/$/, '');
            path = pathname || fallback;
        } catch {
            path = fallback;
        }
    } else if (raw.startsWith('/')) {
        path = raw.replace(/\/$/, '');
    } else {
        path = `${appRoot}/${raw}`.replace(/\/$/, '');
    }

    return withAppRoot(path, appRoot);
}

/**
 * @param {string} base
 * @param {string} path
 * @returns {string}
 */
function resolveConfigUrl(base, path) {
    const normalizedBase = base.replace(/\/$/, '');
    const normalizedPath = path.startsWith('/') ? path.slice(1) : path;
    return `${normalizedBase}/${normalizedPath}`;
}

/**
 * @param {string} url
 * @returns {Promise<unknown>}
 */
async function fetchJson(url) {
    const response = await fetch(url);
    if (!response.ok) {
        throw new Error(`Failed to load config from ${url}: ${response.status}`);
    }
    return response.json();
}

/**
 * @param {unknown} raw
 * @returns {string[]}
 */
function normalizeArtistBlocklist(raw) {
    if (!raw || typeof raw !== 'object') return [];
    const artists = /** @type {Record<string, unknown>} */ (raw).artists;
    if (!Array.isArray(artists)) return [];
    return artists.map(String).map((name) => name.trim()).filter(Boolean);
}

/**
 * @param {string} baseUrl
 * @param {Record<string, unknown>} [manifestFiles]
 * @returns {Promise<string[]>}
 */
async function loadArtistBlocklist(baseUrl, manifestFiles) {
    const path = typeof manifestFiles?.artistBlocklist === 'string'
        ? manifestFiles.artistBlocklist
        : 'artist-blocklist.json';
    try {
        const raw = await fetchJson(resolveConfigUrl(baseUrl, path));
        return normalizeArtistBlocklist(raw);
    } catch {
        return [];
    }
}

/**
 * @param {unknown} raw
 * @param {string[]} wrapperKeys
 * @returns {unknown[]}
 */
function unwrapConfigArray(raw, wrapperKeys) {
    if (Array.isArray(raw)) return raw;
    if (raw && typeof raw === 'object') {
        const obj = /** @type {Record<string, unknown>} */ (raw);
        for (const key of wrapperKeys) {
            if (Array.isArray(obj[key])) return obj[key];
        }
        if (obj.id) return [raw];
    }
    return [];
}

/**
 * @param {unknown} item
 * @returns {import('./config-validator.js').PromptTemplateDef | null}
 */
function normalizeTemplateItem(item) {
    if (!item || typeof item !== 'object') return null;
    const tpl = /** @type {Record<string, unknown>} */ (item);
    if (typeof tpl.id !== 'string') return null;

    /** @type {Array<{ id: string, label?: Record<string, string>, template: string }>} */
    let sections = [];

    if (Array.isArray(tpl.sections)) {
        sections = /** @type {Array<{ id: string, label?: Record<string, string>, template: string }>} */ (
            tpl.sections
        );
    } else if (tpl.sections && typeof tpl.sections === 'object') {
        sections = Object.entries(/** @type {Record<string, string>} */ (tpl.sections)).map(([id, template]) => ({
            id,
            template: String(template),
        }));
    }

    return {
        id: tpl.id,
        sections,
        label: tpl.label && typeof tpl.label === 'object' ? /** @type {Record<string, string>} */ (tpl.label) : undefined,
    };
}

/**
 * @param {unknown} item
 * @returns {import('./config-validator.js').PromptMetaPromptDef | null}
 */
function normalizeMetaPromptItem(item) {
    if (!item || typeof item !== 'object') return null;
    const meta = /** @type {Record<string, unknown>} */ (item);
    if (typeof meta.id !== 'string') return null;

    if (typeof meta.template === 'string') {
        return {
            id: meta.id,
            label: /** @type {Record<string, string>} */ (meta.label ?? { en: meta.id, de: meta.id }),
            template: meta.template,
        };
    }

    if (meta.sections && typeof meta.sections === 'object') {
        const parts = Object.values(/** @type {Record<string, string>} */ (meta.sections))
            .map(String)
            .filter(Boolean);
        return {
            id: meta.id,
            label: /** @type {Record<string, string>} */ (meta.label ?? { en: meta.id, de: meta.id }),
            template: parts.join('\n\n'),
        };
    }

    return null;
}

/**
 * @param {unknown} raw
 * @param {string[]} wrapperKeys
 * @returns {Promise<unknown[]>}
 */
async function fetchFileList(fileRef, base, wrapperKeys = []) {
    if (!fileRef) return [];
    const paths = Array.isArray(fileRef) ? fileRef : [fileRef];
    const results = await Promise.all(paths.map((path) => fetchJson(resolveConfigUrl(base, path))));
    return results.flatMap((result) => unwrapConfigArray(result, wrapperKeys));
}

/**
 * Merge arrays by id; plugin entries do not override core ids.
 * @template {{ id: string }} T
 * @param {T[]} core
 * @param {T[]} pluginItems
 * @returns {T[]}
 */
function mergeById(core, pluginItems) {
    const map = new Map(core.map((item) => [item.id, item]));
    for (const item of pluginItems) {
        if (!map.has(item.id)) {
            map.set(item.id, item);
        }
    }
    return [...map.values()];
}

/**
 * @param {string} baseUrl
 * @param {PromptStudioConfig} coreConfig
 * @returns {Promise<PromptStudioConfig>}
 */
async function loadAndMergePlugins(baseUrl, coreConfig) {
    const pluginIds = coreConfig.manifest.plugins ?? [];
    if (pluginIds.length === 0) return coreConfig;

    /** @type {PromptStudioConfig} */
    let merged = { ...coreConfig };

    for (const pluginId of pluginIds) {
        const pluginUrl = resolveConfigUrl(baseUrl, `plugins/${pluginId}.json`);
        let pluginManifest;
        try {
            pluginManifest = await fetchJson(pluginUrl);
        } catch {
            continue;
        }

        const pluginManifestResult = validateManifest(pluginManifest);
        if (!pluginManifestResult.valid || !pluginManifestResult.manifest) continue;

        const files = pluginManifestResult.manifest.files ?? {};
        const [roles, tasks, parameters, models, templates, chains, metaPrompts] = await Promise.all([
            fetchFileList(files.roles, baseUrl, ['roles']),
            fetchFileList(files.tasks, baseUrl, ['tasks']),
            fetchFileList(files.parameters, baseUrl, ['parameters']),
            fetchFileList(files.models, baseUrl, ['models']),
            fetchFileList(files.templates, baseUrl, ['templates']),
            fetchFileList(files.chains, baseUrl, ['chains']),
            fetchFileList(files.metaPrompts ?? files['meta-prompts'], baseUrl, ['metaPrompts', 'meta-prompts']),
        ]);

        const normalizedTemplates = templates.map(normalizeTemplateItem).filter(Boolean);
        const normalizedMeta = metaPrompts.map(normalizeMetaPromptItem).filter(Boolean);

        merged = {
            ...merged,
            roles: mergeById(merged.roles, validateRoles(roles).roles ?? []),
            tasks: withNormalizedOutputKinds(mergeById(merged.tasks, validateTasks(tasks).tasks ?? [])),
            parameters: mergeById(merged.parameters, validateParameters(parameters).parameters ?? []),
            models: mergeById(merged.models, validateModels(models).models ?? []),
            templates: mergeById(merged.templates, validateTemplates(normalizedTemplates).templates ?? []),
            chains: mergeById(merged.chains, validateChains(chains).chains ?? []),
            metaPrompts: mergeById(merged.metaPrompts, validateMetaPrompts(normalizedMeta).metaPrompts ?? []),
        };
    }

    return merged;
}

/**
 * @param {string} baseUrl
 * @returns {Promise<PromptStudioConfig>}
 */
export async function loadConfig(baseUrl) {
    const manifestUrl = resolveConfigUrl(baseUrl, 'manifest.json');
    const manifestRaw = await fetchJson(manifestUrl);
    const manifestResult = validateManifest(manifestRaw);
    if (!manifestResult.valid || !manifestResult.manifest) {
        throw new Error(
            `Invalid manifest: ${manifestResult.issues.map((i) => i.message).join('; ')}`,
        );
    }

    const files = manifestResult.manifest.files ?? {};
    const [rolesRaw, tasksRaw, parametersRaw, modelsRaw, templatesRaw, chainsRaw, metaPromptsRaw] =
        await Promise.all([
            fetchFileList(files.roles, baseUrl, ['roles']),
            fetchFileList(files.tasks, baseUrl, ['tasks']),
            fetchFileList(files.parameters, baseUrl, ['parameters']),
            fetchFileList(files.models, baseUrl, ['models']),
            fetchFileList(files.templates, baseUrl, ['templates']),
            fetchFileList(files.chains, baseUrl, ['chains']),
            fetchFileList(files.metaPrompts ?? files['meta-prompts'], baseUrl, ['metaPrompts', 'meta-prompts']),
        ]);

    const templatesNormalized = templatesRaw.map(normalizeTemplateItem).filter(Boolean);
    const metaNormalized = metaPromptsRaw.map(normalizeMetaPromptItem).filter(Boolean);

    /** @type {Partial<PromptStudioConfig>} */
    const partial = {
        manifest: manifestResult.manifest,
        roles: validateRoles(rolesRaw).roles,
        tasks: withNormalizedOutputKinds(validateTasks(tasksRaw).tasks),
        parameters: validateParameters(parametersRaw).parameters,
        models: validateModels(modelsRaw).models,
        templates: validateTemplates(templatesNormalized).templates,
        chains: validateChains(chainsRaw).chains,
        metaPrompts: validateMetaPrompts(metaNormalized).metaPrompts,
    };

    const validated = validateConfig(partial);
    if (!validated.valid || !validated.config) {
        throw new Error(`Invalid config: ${validated.issues.map((i) => i.message).join('; ')}`);
    }

    const artistBlocklist = await loadArtistBlocklist(baseUrl, files);

    return loadAndMergePlugins(baseUrl, { ...validated.config, artistBlocklist });
}

/**
 * @param {PromptStudioConfig} config
 * @returns {Map<string, PromptRoleDef>}
 */
export function indexRoles(config) {
    return new Map(config.roles.map((role) => [role.id, role]));
}

/**
 * @param {PromptStudioConfig} config
 * @returns {Map<string, PromptTaskDef>}
 */
export function indexTasks(config) {
    return new Map(config.tasks.map((task) => [task.id, task]));
}

/**
 * @param {PromptStudioConfig} config
 * @returns {Map<string, PromptParameterDef>}
 */
export function indexParameters(config) {
    return new Map(config.parameters.map((param) => [param.id, param]));
}

/**
 * @param {PromptStudioConfig} config
 * @returns {Map<string, PromptModelDef>}
 */
export function indexModels(config) {
    return new Map(config.models.map((model) => [model.id, model]));
}

/**
 * @param {PromptStudioConfig} config
 * @returns {Map<string, PromptTemplateDef>}
 */
export function indexTemplates(config) {
    return new Map(config.templates.map((template) => [template.id, template]));
}

/**
 * @param {string} roleId
 * @param {PromptStudioConfig} config
 * @returns {PromptTaskDef[]}
 */
export function getTasksForRole(roleId, config) {
    const role = config.roles.find((r) => r.id === roleId);
    const primaryIds = new Set(role?.taskIds ?? []);
    const crossIds = role?.crossRoleTaskIds ?? [];

    const tasks = config.tasks.filter(
        (task) => task.roleIds.includes(roleId) || crossIds.includes(task.id),
    );

    return tasks.sort((a, b) => {
        const aPrimary = primaryIds.has(a.id) ? 0 : 1;
        const bPrimary = primaryIds.has(b.id) ? 0 : 1;
        if (aPrimary !== bPrimary) return aPrimary - bPrimary;
        return (a.label?.en ?? a.id).localeCompare(b.label?.en ?? b.id);
    });
}

/**
 * @param {string} taskId
 * @param {PromptStudioConfig} config
 * @returns {PromptParameterDef[]}
 */
export function getParametersForTask(taskId, config) {
    const task = config.tasks.find((t) => t.id === taskId);
    if (!task) return [];
    const paramMap = indexParameters(config);
    return task.parameterIds.map((id) => paramMap.get(id)).filter(Boolean);
}

/**
 * @param {string} templateId
 * @param {PromptStudioConfig} config
 * @returns {PromptTemplateDef | undefined}
 */
export function getTemplate(templateId, config) {
    return indexTemplates(config).get(templateId);
}
