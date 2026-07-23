/** @typedef {import('./config-validator.js').PromptModelDef} PromptModelDef */

import { formatDisplayValue, resolveLocalizedLabel } from './localized-label.js';

/**
 * @typedef {Record<string, unknown>} TemplateContext
 */

/**
 * @param {unknown} value
 * @returns {boolean}
 */
function isTruthy(value) {
    if (value === null || value === undefined) return false;
    if (typeof value === 'boolean') return value;
    if (typeof value === 'number') return value !== 0;
    if (Array.isArray(value)) return value.length > 0;
    if (typeof value === 'string') return value.trim().length > 0;
    return true;
}

/**
 * Simple mustache-like template engine supporting {{var}}, {{#if var}}...{{/if}},
 * and {{#if var==value}}...{{/if}} (string equality).
 * @param {string} template
 * @param {TemplateContext} context
 * @param {import('./config-validator.js').ToolsLocale} [locale]
 * @param {{ trim?: boolean }} [options]
 * @returns {string}
 */
export function renderTemplate(template, context, locale = 'en', options = {}) {
    const shouldTrim = options.trim !== false;
    let result = template;

    result = result.replace(
        /\{\{#if\s+([\w.-]+)(?:\s*==\s*([^\s}]+))?\s*\}\}([\s\S]*?)\{\{\/if(?:\s+\1)?\s*\}\}/g,
        (_, key, equals, block) => {
            const raw = context[key];
            const ok =
                equals === undefined || equals === null || equals === ''
                    ? isTruthy(raw)
                    : String(raw ?? '').trim() === String(equals).trim();
            return ok ? renderTemplate(block, context, locale, { trim: false }) : '';
        },
    );

    result = result.replace(/\{\{([\w.-]+)\}\}/g, (_, key) => formatDisplayValue(context[key], locale));

    return shouldTrim ? result.trim() : result;
}

/**
 * @typedef {{ id: string, label?: string, content: string }} CompiledSection
 */

/**
 * Section bodies may be a plain string (legacy EN) or { de, en }.
 * @param {unknown} template
 * @param {import('./config-validator.js').ToolsLocale} locale
 * @returns {string}
 */
export function resolveSectionTemplate(template, locale = 'en') {
    if (typeof template === 'string') return template;
    return resolveLocalizedLabel(template, locale, '');
}

/**
 * @param {Array<{ id: string, label?: Record<string, string>, template: string | Record<string, string> }>} sectionDefs
 * @param {TemplateContext} context
 * @param {import('./config-validator.js').ToolsLocale} [locale]
 * @returns {CompiledSection[]}
 */
export function compileSections(sectionDefs, context, locale = 'en') {
    return sectionDefs
        .map((section) => {
            const template = resolveSectionTemplate(section.template, locale);
            const content = renderTemplate(template, context, locale);
            if (!content) return null;
            return {
                id: section.id,
                label: resolveLocalizedLabel(section.label, locale, section.id),
                content,
            };
        })
        .filter(Boolean);
}

/**
 * @param {CompiledSection[]} sections
 * @param {PromptModelDef | undefined} model
 * @returns {Record<string, string>}
 */
export function sectionsToMap(sections) {
    /** @type {Record<string, string>} */
    const map = {};
    for (const section of sections) {
        map[section.id] = section.content;
    }
    return map;
}

/**
 * @param {Record<string, string>} sections
 * @param {PromptModelDef | undefined} model
 * @returns {string}
 */
export function formatForModel(sections, model) {
    const order = model?.sectionOrder ?? Object.keys(sections);
    const rules = model?.formatRules ?? {};
    const separator = typeof rules.separator === 'string' ? rules.separator : '\n\n';
    const includeLabels = rules.includeLabels === true;
    const omitEmpty = rules.omitEmpty !== false;

    const parts = order
        .map((sectionId) => {
            const content = sections[sectionId]?.trim() ?? '';
            if (omitEmpty && !content) return '';
            if (includeLabels) {
                return `## ${sectionId}\n${content}`;
            }
            return content;
        })
        .filter(Boolean);

    if (rules.style === 'comma-separated') {
        return parts.join(', ');
    }

    if (rules.style === 'single-block') {
        return parts.join(' ');
    }

    return parts.join(separator);
}

/**
 * @param {Record<string, unknown>} parameterValues
 * @param {Record<string, string>} [extra]
 * @returns {TemplateContext}
 */
export function buildTemplateContext(parameterValues, extra = {}) {
    return {
        ...parameterValues,
        ...extra,
    };
}

/**
 * Compile template sections and produce final compiled prompt text.
 * @param {{
 *   template: import('./config-validator.js').PromptTemplateDef,
 *   parameterValues: Record<string, unknown>,
 *   model?: PromptModelDef,
 *   extraContext?: Record<string, unknown>,
 *   locale?: import('./config-validator.js').ToolsLocale,
 * }} options
 * @returns {{ sections: Record<string, string>, compiled: string, compiledList: CompiledSection[] }}
 */
export function buildPrompt(options) {
    const { template, parameterValues, model, extraContext = {}, locale = 'en' } = options;
    const context = buildTemplateContext(parameterValues, extraContext);
    const compiledList = compileSections(template.sections, context, locale);
    const sections = sectionsToMap(compiledList);
    const compiled = formatForModel(sections, model);

    return { sections, compiled, compiledList };
}
