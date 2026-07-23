/** @typedef {'de' | 'en'} ToolsLocale */

import { normalizeCategory } from './categories.js';

/**
 * @typedef {Object} PromptManifest
 * @property {number} version
 * @property {string[]} plugins
 * @property {string} localeFallback
 * @property {Record<string, string | string[]>} files
 */

/**
 * @typedef {Object} PromptRoleDef
 * @property {string} id
 * @property {Record<ToolsLocale, string>} label
 * @property {string} [icon]
 * @property {string[]} taskIds
 */

/**
 * @typedef {Object} PromptTaskDef
 * @property {string} id
 * @property {string[]} roleIds
 * @property {string[]} parameterIds
 * @property {string} templateId
 * @property {string[]} [modelHints]
 * @property {import('./md-export.js').OutputKind} [outputKind]
 * @property {import('./categories.js').PromptCategory} [category]
 * @property {Record<string, unknown>} [parameterDefaults]
 */

/**
 * @typedef {Object} PromptParameterDef
 * @property {string} id
 * @property {'text' | 'textarea' | 'select' | 'multiselect' | 'number' | 'chips' | 'color' | 'aspect-ratio'} type
 * @property {string} [group]
 * @property {Record<ToolsLocale, string>} label
 * @property {unknown[]} [options]
 * @property {unknown} [default]
 * @property {boolean} [required]
 * @property {Record<ToolsLocale, string>} [placeholder]
 */

/**
 * @typedef {Object} PromptModelDef
 * @property {string} id
 * @property {Record<ToolsLocale, string>} label
 * @property {string[]} sectionOrder
 * @property {Record<string, unknown>} [formatRules]
 * @property {'free' | 'paid'} [defaultPlan]
 * @property {Array<{ id: string, label?: Record<ToolsLocale, string> }>} [plans]
 * @property {{ default?: number, style?: number, lyrics?: number | Record<string, number> }} [limits]
 */

/**
 * @typedef {Object} PromptTemplateDef
 * @property {string} id
 * @property {Array<{ id: string, label?: Record<ToolsLocale, string>, template: string }>} sections
 */

/**
 * @typedef {Object} PromptChainDef
 * @property {string} id
 * @property {Record<ToolsLocale, string>} label
 * @property {Array<{ roleId: string, taskId: string, label?: Record<ToolsLocale, string> }>} steps
 * @property {import('./categories.js').PromptCategory} [category]
 */

/**
 * @typedef {Object} PromptMetaPromptDef
 * @property {string} id
 * @property {Record<ToolsLocale, string>} label
 * @property {string} template
 */

/**
 * @typedef {Object} PromptStudioConfig
 * @property {PromptManifest} manifest
 * @property {PromptRoleDef[]} roles
 * @property {PromptTaskDef[]} tasks
 * @property {PromptParameterDef[]} parameters
 * @property {PromptModelDef[]} models
 * @property {PromptTemplateDef[]} templates
 * @property {PromptChainDef[]} chains
 * @property {PromptMetaPromptDef[]} metaPrompts
 * @property {string[]} [artistBlocklist]
 */

/**
 * @typedef {{ path: string, message: string }} ValidationIssue
 */

/**
 * @param {unknown} value
 * @returns {value is Record<ToolsLocale, string>}
 */
function isLocalizedLabel(value) {
    return (
        value !== null &&
        typeof value === 'object' &&
        typeof /** @type {Record<string, unknown>} */ (value).de === 'string' &&
        typeof /** @type {Record<string, unknown>} */ (value).en === 'string'
    );
}

/**
 * @param {unknown} raw
 * @returns {{ valid: boolean, issues: ValidationIssue[], manifest?: PromptManifest }}
 */
export function validateManifest(raw) {
    /** @type {ValidationIssue[]} */
    const issues = [];
    if (!raw || typeof raw !== 'object') {
        return { valid: false, issues: [{ path: 'manifest', message: 'Manifest must be an object' }] };
    }

    const obj = /** @type {Record<string, unknown>} */ (raw);
    if (typeof obj.version !== 'number') {
        issues.push({ path: 'manifest.version', message: 'version must be a number' });
    }
    if (!Array.isArray(obj.plugins)) {
        issues.push({ path: 'manifest.plugins', message: 'plugins must be an array' });
    }
    if (typeof obj.localeFallback !== 'string') {
        issues.push({ path: 'manifest.localeFallback', message: 'localeFallback must be a string' });
    }
    if (!obj.files || typeof obj.files !== 'object') {
        issues.push({ path: 'manifest.files', message: 'files must be an object' });
    }

    if (issues.length > 0) return { valid: false, issues };

    return {
        valid: true,
        issues,
        manifest: /** @type {PromptManifest} */ (obj),
    };
}

/**
 * @param {unknown} raw
 * @returns {{ valid: boolean, issues: ValidationIssue[], roles?: PromptRoleDef[] }}
 */
export function validateRoles(raw) {
    /** @type {ValidationIssue[]} */
    const issues = [];
    if (!Array.isArray(raw)) {
        return { valid: false, issues: [{ path: 'roles', message: 'roles must be an array' }] };
    }

    /** @type {PromptRoleDef[]} */
    const roles = [];
    const ids = new Set();

    raw.forEach((item, index) => {
        const path = `roles[${index}]`;
        if (!item || typeof item !== 'object') {
            issues.push({ path, message: 'role must be an object' });
            return;
        }
        const role = /** @type {Record<string, unknown>} */ (item);
        if (typeof role.id !== 'string' || !role.id.trim()) {
            issues.push({ path: `${path}.id`, message: 'id is required' });
            return;
        }
        if (ids.has(role.id)) {
            issues.push({ path: `${path}.id`, message: `duplicate id "${role.id}"` });
        }
        ids.add(role.id);
        if (!isLocalizedLabel(role.label)) {
            issues.push({ path: `${path}.label`, message: 'label must have de/en strings' });
        }
        if (!Array.isArray(role.taskIds)) {
            issues.push({ path: `${path}.taskIds`, message: 'taskIds must be an array' });
        }

        roles.push(/** @type {PromptRoleDef} */ (item));
    });

    return { valid: issues.length === 0, issues, roles };
}

/**
 * @param {unknown} raw
 * @returns {{ valid: boolean, issues: ValidationIssue[], tasks?: PromptTaskDef[] }}
 */
export function validateTasks(raw) {
    /** @type {ValidationIssue[]} */
    const issues = [];
    if (!Array.isArray(raw)) {
        return { valid: false, issues: [{ path: 'tasks', message: 'tasks must be an array' }] };
    }

    /** @type {PromptTaskDef[]} */
    const tasks = [];
    const ids = new Set();

    raw.forEach((item, index) => {
        const path = `tasks[${index}]`;
        if (!item || typeof item !== 'object') {
            issues.push({ path, message: 'task must be an object' });
            return;
        }
        const task = /** @type {Record<string, unknown>} */ (item);
        if (typeof task.id !== 'string' || !task.id.trim()) {
            issues.push({ path: `${path}.id`, message: 'id is required' });
            return;
        }
        if (ids.has(task.id)) {
            issues.push({ path: `${path}.id`, message: `duplicate id "${task.id}"` });
        }
        ids.add(task.id);
        if (!Array.isArray(task.roleIds)) {
            issues.push({ path: `${path}.roleIds`, message: 'roleIds must be an array' });
        }
        if (!Array.isArray(task.parameterIds)) {
            issues.push({ path: `${path}.parameterIds`, message: 'parameterIds must be an array' });
        }
        if (typeof task.templateId !== 'string') {
            issues.push({ path: `${path}.templateId`, message: 'templateId is required' });
        }

        const category = normalizeCategory(task.category);
        tasks.push(/** @type {PromptTaskDef} */ ({ ...task, ...(category ? { category } : {}) }));
    });

    return { valid: issues.length === 0, issues, tasks };
}

const PARAM_TYPES = new Set(['text', 'textarea', 'select', 'multiselect', 'number', 'chips', 'color', 'aspect-ratio']);

/**
 * @param {unknown} raw
 * @returns {{ valid: boolean, issues: ValidationIssue[], parameters?: PromptParameterDef[] }}
 */
export function validateParameters(raw) {
    /** @type {ValidationIssue[]} */
    const issues = [];
    if (!Array.isArray(raw)) {
        return { valid: false, issues: [{ path: 'parameters', message: 'parameters must be an array' }] };
    }

    /** @type {PromptParameterDef[]} */
    const parameters = [];
    const ids = new Set();

    raw.forEach((item, index) => {
        const path = `parameters[${index}]`;
        if (!item || typeof item !== 'object') {
            issues.push({ path, message: 'parameter must be an object' });
            return;
        }
        const param = /** @type {Record<string, unknown>} */ (item);
        if (typeof param.id !== 'string' || !param.id.trim()) {
            issues.push({ path: `${path}.id`, message: 'id is required' });
            return;
        }
        if (ids.has(param.id)) {
            issues.push({ path: `${path}.id`, message: `duplicate id "${param.id}"` });
        }
        ids.add(param.id);
        if (typeof param.type !== 'string' || !PARAM_TYPES.has(param.type)) {
            issues.push({ path: `${path}.type`, message: `type must be one of ${[...PARAM_TYPES].join(', ')}` });
        }
        if (!isLocalizedLabel(param.label)) {
            issues.push({ path: `${path}.label`, message: 'label must have de/en strings' });
        }

        parameters.push(/** @type {PromptParameterDef} */ (item));
    });

    return { valid: issues.length === 0, issues, parameters };
}

/**
 * @param {unknown} raw
 * @returns {{ valid: boolean, issues: ValidationIssue[], models?: PromptModelDef[] }}
 */
export function validateModels(raw) {
    /** @type {ValidationIssue[]} */
    const issues = [];
    if (!Array.isArray(raw)) {
        return { valid: false, issues: [{ path: 'models', message: 'models must be an array' }] };
    }

    /** @type {PromptModelDef[]} */
    const models = [];
    const ids = new Set();

    raw.forEach((item, index) => {
        const path = `models[${index}]`;
        if (!item || typeof item !== 'object') {
            issues.push({ path, message: 'model must be an object' });
            return;
        }
        const model = /** @type {Record<string, unknown>} */ (item);
        if (typeof model.id !== 'string' || !model.id.trim()) {
            issues.push({ path: `${path}.id`, message: 'id is required' });
            return;
        }
        if (ids.has(model.id)) {
            issues.push({ path: `${path}.id`, message: `duplicate id "${model.id}"` });
        }
        ids.add(model.id);
        if (!isLocalizedLabel(model.label)) {
            issues.push({ path: `${path}.label`, message: 'label must have de/en strings' });
        }
        if (!Array.isArray(model.sectionOrder)) {
            issues.push({ path: `${path}.sectionOrder`, message: 'sectionOrder must be an array' });
        }

        models.push(/** @type {PromptModelDef} */ (item));
    });

    return { valid: issues.length === 0, issues, models };
}

/**
 * @param {unknown} raw
 * @returns {{ valid: boolean, issues: ValidationIssue[], templates?: PromptTemplateDef[] }}
 */
export function validateTemplates(raw) {
    /** @type {ValidationIssue[]} */
    const issues = [];
    if (!Array.isArray(raw)) {
        return { valid: false, issues: [{ path: 'templates', message: 'templates must be an array' }] };
    }

    /** @type {PromptTemplateDef[]} */
    const templates = [];
    const ids = new Set();

    raw.forEach((item, index) => {
        const path = `templates[${index}]`;
        if (!item || typeof item !== 'object') {
            issues.push({ path, message: 'template must be an object' });
            return;
        }
        const tpl = /** @type {Record<string, unknown>} */ (item);
        if (typeof tpl.id !== 'string' || !tpl.id.trim()) {
            issues.push({ path: `${path}.id`, message: 'id is required' });
            return;
        }
        if (ids.has(tpl.id)) {
            issues.push({ path: `${path}.id`, message: `duplicate id "${tpl.id}"` });
        }
        ids.add(tpl.id);
        if (!Array.isArray(tpl.sections)) {
            issues.push({ path: `${path}.sections`, message: 'sections must be an array' });
        }

        templates.push(/** @type {PromptTemplateDef} */ (item));
    });

    return { valid: issues.length === 0, issues, templates };
}

/**
 * @param {unknown} raw
 * @returns {{ valid: boolean, issues: ValidationIssue[], chains?: PromptChainDef[] }}
 */
export function validateChains(raw) {
    if (raw === undefined || raw === null) {
        return { valid: true, issues: [], chains: [] };
    }
    if (!Array.isArray(raw)) {
        return { valid: false, issues: [{ path: 'chains', message: 'chains must be an array' }] };
    }

    /** @type {ValidationIssue[]} */
    const issues = [];
    /** @type {PromptChainDef[]} */
    const chains = [];

    raw.forEach((item, index) => {
        const path = `chains[${index}]`;
        if (!item || typeof item !== 'object') {
            issues.push({ path, message: 'chain must be an object' });
            return;
        }
        const chain = /** @type {Record<string, unknown>} */ (item);
        if (typeof chain.id !== 'string') {
            issues.push({ path: `${path}.id`, message: 'id is required' });
        }
        if (!Array.isArray(chain.steps)) {
            issues.push({ path: `${path}.steps`, message: 'steps must be an array' });
        }
        chains.push(
            /** @type {PromptChainDef} */ ({
                ...chain,
                ...(normalizeCategory(chain.category) ? { category: normalizeCategory(chain.category) } : {}),
            }),
        );
    });

    return { valid: issues.length === 0, issues, chains };
}

/**
 * @param {unknown} raw
 * @returns {{ valid: boolean, issues: ValidationIssue[], metaPrompts?: PromptMetaPromptDef[] }}
 */
export function validateMetaPrompts(raw) {
    if (raw === undefined || raw === null) {
        return { valid: true, issues: [], metaPrompts: [] };
    }
    if (!Array.isArray(raw)) {
        return { valid: false, issues: [{ path: 'metaPrompts', message: 'metaPrompts must be an array' }] };
    }

    /** @type {ValidationIssue[]} */
    const issues = [];
    /** @type {PromptMetaPromptDef[]} */
    const metaPrompts = [];

    raw.forEach((item, index) => {
        const path = `metaPrompts[${index}]`;
        if (!item || typeof item !== 'object') {
            issues.push({ path, message: 'meta-prompt must be an object' });
            return;
        }
        const meta = /** @type {Record<string, unknown>} */ (item);
        if (typeof meta.id !== 'string') {
            issues.push({ path: `${path}.id`, message: 'id is required' });
        }
        if (typeof meta.template !== 'string') {
            issues.push({ path: `${path}.template`, message: 'template is required' });
        }
        metaPrompts.push(/** @type {PromptMetaPromptDef} */ (item));
    });

    return { valid: issues.length === 0, issues, metaPrompts };
}

/**
 * @param {Partial<PromptStudioConfig>} config
 * @returns {{ valid: boolean, issues: ValidationIssue[], config?: PromptStudioConfig }}
 */
export function validateConfig(config) {
    /** @type {ValidationIssue[]} */
    const issues = [];

    const manifestResult = validateManifest(config.manifest);
    issues.push(...manifestResult.issues);

    const rolesResult = validateRoles(config.roles);
    issues.push(...rolesResult.issues);

    const tasksResult = validateTasks(config.tasks);
    issues.push(...tasksResult.issues);

    const paramsResult = validateParameters(config.parameters);
    issues.push(...paramsResult.issues);

    const modelsResult = validateModels(config.models);
    issues.push(...modelsResult.issues);

    const templatesResult = validateTemplates(config.templates);
    issues.push(...templatesResult.issues);

    const chainsResult = validateChains(config.chains);
    issues.push(...chainsResult.issues);

    const metaResult = validateMetaPrompts(config.metaPrompts);
    issues.push(...metaResult.issues);

    if (issues.length > 0) {
        return { valid: false, issues };
    }

    return {
        valid: true,
        issues: [],
        config: {
            manifest: manifestResult.manifest,
            roles: rolesResult.roles ?? [],
            tasks: tasksResult.tasks ?? [],
            parameters: paramsResult.parameters ?? [],
            models: modelsResult.models ?? [],
            templates: templatesResult.templates ?? [],
            chains: chainsResult.chains ?? [],
            metaPrompts: metaResult.metaPrompts ?? [],
        },
    };
}
