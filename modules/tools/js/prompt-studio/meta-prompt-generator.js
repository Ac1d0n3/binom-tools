import { renderTemplate } from './prompt-builder.js';

/** @typedef {import('./config-validator.js').PromptMetaPromptDef} PromptMetaPromptDef */
/** @typedef {import('./config-validator.js').PromptStudioConfig} PromptStudioConfig */
/** @typedef {import('./config-validator.js').ToolsLocale} ToolsLocale */

/**
 * @typedef {Object} MetaPromptContext
 * @property {string} compiledPrompt
 * @property {string} roleId
 * @property {string} taskId
 * @property {string} modelId
 * @property {Record<string, unknown>} parameterValues
 * @property {string} [goal]
 * @property {string} [locale]
 */

/**
 * @param {PromptStudioConfig} config
 * @param {string} id
 * @returns {PromptMetaPromptDef | undefined}
 */
function getMetaPrompt(config, id) {
    return config.metaPrompts.find((entry) => entry.id === id);
}

/**
 * @param {PromptMetaPromptDef | undefined} def
 * @param {MetaPromptContext} context
 * @returns {string}
 */
function renderMetaPrompt(def, context) {
    if (!def) return '';
    return renderTemplate(def.template, {
        ...context.parameterValues,
        compiledPrompt: context.compiledPrompt,
        roleId: context.roleId,
        taskId: context.taskId,
        modelId: context.modelId,
        goal: context.goal ?? '',
        locale: context.locale ?? 'en',
    });
}

/**
 * Generate optimizer meta-prompt for copy-paste into external LLM.
 * @param {PromptStudioConfig} config
 * @param {MetaPromptContext} context
 * @returns {{ title: string, body: string }}
 */
export function generateOptimizerMetaPrompt(config, context) {
    const def = getMetaPrompt(config, 'optimizer') ?? getMetaPrompt(config, 'prompt-optimizer');
    const locale = /** @type {ToolsLocale} */ (context.locale === 'de' ? 'de' : 'en');

    const body =
        renderMetaPrompt(def, context) ||
        renderTemplate(
            `You are a senior prompt engineer. Improve the following prompt for model "{{modelId}}".

Role: {{roleId}}
Task: {{taskId}}

Checklist:
- Clarity and specificity
- Structured sections
- Constraints and output format
- Remove ambiguity

Original prompt:
"""
{{compiledPrompt}}
"""

Return only the improved prompt.`,
            context,
        );

    return {
        title: def?.label?.[locale] ?? (locale === 'de' ? 'Prompt verbessern' : 'Improve prompt'),
        body,
    };
}

/**
 * Generate split-task meta-prompt for decomposing large tasks.
 * @param {PromptStudioConfig} config
 * @param {MetaPromptContext & { goal: string }} context
 * @returns {{ title: string, body: string }}
 */
export function generateSplitMetaPrompt(config, context) {
    const def = getMetaPrompt(config, 'split-task') ?? getMetaPrompt(config, 'split');
    const locale = /** @type {ToolsLocale} */ (context.locale === 'de' ? 'de' : 'en');

    const body =
        renderMetaPrompt(def, context) ||
        renderTemplate(
            `You are a solution architect. Split the following goal into actionable sprint-sized prompts.

Role: {{roleId}}
Task: {{taskId}}
Goal: {{goal}}

Return JSON:
{
  "sprints": [
    { "title": "...", "prompt": "..." }
  ]
}

Context:
{{compiledPrompt}}`,
            context,
        );

    return {
        title: def?.label?.[locale] ?? (locale === 'de' ? 'Aufgabe zerlegen' : 'Split task'),
        body,
    };
}

/**
 * @param {string} llmJsonResponse
 * @returns {{ title: string, prompt: string }[]}
 */
export function parseSplitResponse(llmJsonResponse) {
    try {
        const parsed = JSON.parse(llmJsonResponse);
        if (!parsed || !Array.isArray(parsed.sprints)) return [];
        return parsed.sprints
            .filter((item) => item && typeof item === 'object')
            .map((item) => ({
                title: typeof item.title === 'string' ? item.title : 'Sprint',
                prompt: typeof item.prompt === 'string' ? item.prompt : '',
            }))
            .filter((item) => item.prompt.trim());
    } catch {
        return [];
    }
}

/**
 * @param {PromptStudioConfig} config
 * @param {'optimizer' | 'split'} kind
 * @param {MetaPromptContext} context
 * @returns {{ title: string, body: string }}
 */
export function generateMetaPrompt(config, kind, context) {
    if (kind === 'split') {
        return generateSplitMetaPrompt(config, { ...context, goal: context.goal ?? String(context.parameterValues.goal ?? '') });
    }
    return generateOptimizerMetaPrompt(config, context);
}
