/** @typedef {import('./config-validator.js').PromptModelDef} PromptModelDef */
/** @typedef {import('./config-validator.js').PromptTaskDef} PromptTaskDef */

/** @typedef {'free' | 'paid'} ModelPlan */
/** @typedef {'lyrics' | 'style' | 'default'} LimitKind */

const LYRICS_TASK_IDS = new Set(['lyrics', 'song-develop', 'hook', 'album']);
const STYLE_TASK_IDS = new Set(['suno-style']);

/**
 * @param {unknown} value
 * @returns {ModelPlan}
 */
export function normalizeModelPlan(value) {
    return value === 'paid' ? 'paid' : 'free';
}

/**
 * @param {string | undefined} taskId
 * @returns {LimitKind}
 */
export function getLimitKindForTask(taskId) {
    if (!taskId) return 'default';
    if (STYLE_TASK_IDS.has(taskId)) return 'style';
    if (LYRICS_TASK_IDS.has(taskId)) return 'lyrics';
    return 'default';
}

/**
 * @param {PromptModelDef | undefined | null} model
 * @returns {boolean}
 */
export function modelHasPlans(model) {
    const plans = model?.plans;
    return Array.isArray(plans) && plans.length > 0;
}

/**
 * Resolve the character limit for the current model / plan / task.
 * @param {{
 *   model?: PromptModelDef | null,
 *   plan?: unknown,
 *   taskId?: string,
 * }} options
 * @returns {number | null}
 */
export function resolveCharLimit(options) {
    const model = options.model;
    if (!model) return null;

    const kind = getLimitKindForTask(options.taskId);
    const planId = normalizeModelPlan(options.plan);
    const limits = model.limits && typeof model.limits === 'object' ? model.limits : null;

    if (limits) {
        const byKind = /** @type {Record<string, unknown>} */ (limits)[kind];
        if (typeof byKind === 'number' && Number.isFinite(byKind)) {
            return Math.max(0, Math.floor(byKind));
        }
        if (byKind && typeof byKind === 'object') {
            const byPlan = /** @type {Record<string, unknown>} */ (byKind)[planId];
            if (typeof byPlan === 'number' && Number.isFinite(byPlan)) {
                return Math.max(0, Math.floor(byPlan));
            }
            const free = /** @type {Record<string, unknown>} */ (byKind).free;
            if (typeof free === 'number' && Number.isFinite(free)) {
                return Math.max(0, Math.floor(free));
            }
        }
        const fallback = /** @type {Record<string, unknown>} */ (limits).default;
        if (typeof fallback === 'number' && Number.isFinite(fallback)) {
            return Math.max(0, Math.floor(fallback));
        }
    }

    const rules = model.formatRules ?? {};
    if (typeof rules.maxLength === 'number' && Number.isFinite(rules.maxLength)) {
        return Math.max(0, Math.floor(rules.maxLength));
    }

    return null;
}

/**
 * Models allowed for a task (modelHints order only — no fallback to all models).
 * @param {PromptTaskDef | undefined | null} task
 * @param {PromptModelDef[]} models
 * @returns {PromptModelDef[]}
 */
export function modelsForTask(task, models) {
    const list = Array.isArray(models) ? models : [];
    if (!task) return [];

    const hints = Array.isArray(task.modelHints) ? task.modelHints.filter(Boolean) : [];
    if (hints.length === 0) {
        // Tasks without hints: only generic/LLM-safe defaults (never media tools).
        return list.filter((m) => !m.family || m.family === 'llm');
    }

    /** @type {PromptModelDef[]} */
    const matched = [];
    for (const id of hints) {
        const model = list.find((m) => m.id === id);
        if (model) matched.push(model);
    }
    return matched;
}

/**
 * Prefer the first model hint for a task when the current model is empty or unsupported.
 * @param {PromptTaskDef | undefined | null} task
 * @param {string} currentModelId
 * @param {PromptModelDef[]} models
 * @returns {string}
 */
export function preferModelForTask(task, currentModelId, models) {
    const available = modelsForTask(task, models);
    if (available.length === 0) return currentModelId ?? '';
    if (currentModelId && available.some((m) => m.id === currentModelId)) return currentModelId;
    return available[0]?.id ?? currentModelId ?? '';
}

/**
 * Whether the Target AI controls should be visible for the current draft.
 * Hidden when unused (no task / single model with no plan or limit).
 * @param {{
 *   task?: PromptTaskDef | null,
 *   models: PromptModelDef[],
 *   modelId?: string,
 *   modelPlan?: unknown,
 *   tech?: boolean,
 * }} options
 * @returns {boolean}
 */
export function shouldShowTargetAi(options) {
    const { task, models, modelId, modelPlan, tech = false } = options;
    if (!task) return Boolean(tech);

    const available = modelsForTask(task, models);
    if (available.length > 1) return true;

    const model = available.find((m) => m.id === modelId) ?? available[0] ?? models.find((m) => m.id === modelId);
    if (modelHasPlans(model)) return true;
    if (resolveCharLimit({ model, plan: modelPlan, taskId: task.id }) != null) return true;
    return Boolean(tech);
}
