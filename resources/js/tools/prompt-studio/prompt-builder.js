/** @typedef {import('./config-validator.js').PromptModelDef} PromptModelDef */

import { formatDisplayValue, resolveLocalizedLabel } from './localized-label.js';

/**
 * @typedef {Record<string, unknown>} TemplateContext
 */

/**
 * Map Midjourney-style CLI flags to draft parameter ids.
 * @type {Record<string, string>}
 */
const SUFFIX_PARAM_KEYS = {
    '--ar': 'aspectRatio',
    '--v': 'modelVersion',
    '--stylize': 'stylize',
    '--s': 'stylize',
    '--chaos': 'chaos',
    '--q': 'quality',
};

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
 * @param {Record<string, unknown>} rules
 * @returns {string}
 */
function resolveSectionSeparator(rules) {
    if (typeof rules.joinSections === 'string') return rules.joinSections;
    if (typeof rules.separator === 'string') return rules.separator;
    if (rules.style === 'comma-separated') return ', ';
    if (rules.style === 'single-block') return ' ';
    return '\n\n';
}

/**
 * @param {string} sectionId
 * @param {Record<string, unknown>} rules
 * @returns {string}
 */
function formatSectionHeader(sectionId, rules) {
    const template =
        typeof rules.headerFormat === 'string' && rules.headerFormat.trim()
            ? rules.headerFormat
            : '## {section}';
    return template.replaceAll('{section}', sectionId);
}

/**
 * @param {Record<string, unknown>} parameterValues
 * @param {unknown} suffixParams
 * @returns {string}
 */
function buildSuffixParams(parameterValues, suffixParams) {
    if (!Array.isArray(suffixParams) || suffixParams.length === 0) return '';

    /** @type {string[]} */
    const parts = [];
    for (const raw of suffixParams) {
        if (typeof raw !== 'string' || !raw.trim()) continue;
        const flag = raw.trim();
        const key = SUFFIX_PARAM_KEYS[flag];
        if (!key) continue;
        const value = parameterValues[key];
        if (!isTruthy(value)) continue;
        parts.push(`${flag} ${String(value).trim()}`);
    }
    return parts.length ? ` ${parts.join(' ')}` : '';
}

/**
 * @param {Record<string, unknown>} parameterValues
 * @param {unknown} prefix
 * @returns {string}
 */
function buildNegativePromptSuffix(parameterValues, prefix) {
    if (typeof prefix !== 'string' || !prefix.trim()) return '';
    const raw = parameterValues.negativePrompt;
    if (!isTruthy(raw)) return '';
    const text = String(raw).trim();
    if (!text) return '';
    const spacer = prefix.endsWith(' ') ? '' : ' ';
    return `\n${prefix}${spacer}${text}`;
}

/**
 * Format compiled sections according to the target model's formatRules.
 * @param {Record<string, string>} sections
 * @param {PromptModelDef | undefined} model
 * @param {{ parameterValues?: Record<string, unknown> }} [options]
 * @returns {string}
 */
export function formatForModel(sections, model, options = {}) {
    const parameterValues = options.parameterValues ?? {};
    const order = model?.sectionOrder ?? Object.keys(sections);
    const rules = /** @type {Record<string, unknown>} */ (model?.formatRules ?? {});
    const omitEmpty = rules.omitEmpty !== false;
    const includeHeaders = rules.includeSectionHeaders === true || rules.includeLabels === true;
    const separator = resolveSectionSeparator(rules);

    const parts = order
        .map((sectionId) => {
            if (sectionId === 'system' && rules.omitSystemSection === true) return '';
            if (sectionId === 'output' && rules.omitOutputSection === true) return '';
            if (sectionId === 'context' && rules.omitContextSection === true) return '';

            const content = sections[sectionId]?.trim() ?? '';
            if (omitEmpty && !content) return '';
            if (includeHeaders) {
                return `${formatSectionHeader(sectionId, rules)}\n${content}`;
            }
            return content;
        })
        .filter(Boolean);

    const joinWith = rules.compactTags === true ? ', ' : separator;
    let body = parts.join(joinWith);

    if (rules.compactTags === true) {
        body = body
            .replace(/\n+/g, ', ')
            .replace(/\s*,\s*/g, ', ')
            .replace(/,\s*,+/g, ', ')
            .trim();
    }

    if (typeof rules.stylePrefix === 'string' && rules.stylePrefix.trim() && body) {
        const prefix = rules.stylePrefix.trim();
        const close =
            typeof rules.styleSuffix === 'string'
                ? rules.styleSuffix
                : prefix.includes('[') && !prefix.includes(']')
                  ? ']'
                  : '';
        const needsSpace = /[:\w]$/.test(prefix);
        body = `${prefix}${needsSpace ? ' ' : ''}${body}${close}`;
    }

    body += buildSuffixParams(parameterValues, rules.suffixParams);
    body += buildNegativePromptSuffix(parameterValues, rules.negativePromptPrefix);

    if (typeof rules.maxLength === 'number' && Number.isFinite(rules.maxLength) && rules.maxLength > 0) {
        if (body.length > rules.maxLength) {
            body = body.slice(0, rules.maxLength).trimEnd();
        }
    }

    return body.trim();
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
    const compiled = formatForModel(sections, model, { parameterValues });

    return { sections, compiled, compiledList };
}
