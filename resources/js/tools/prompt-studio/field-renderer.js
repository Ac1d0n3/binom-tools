/** @typedef {import('./config-validator.js').PromptParameterDef} PromptParameterDef */
/** @typedef {import('./config-validator.js').ToolsLocale} ToolsLocale */

import { resolveLocalizedLabel } from './localized-label.js';
import { t } from './labels.js';

/**
 * @param {string} value
 * @returns {string}
 */
function escapeAttr(value) {
    return value.replaceAll('&', '&amp;').replaceAll('"', '&quot;').replaceAll('<', '&lt;');
}

/**
 * @param {string} value
 * @returns {string}
 */
function escapeHtml(value) {
    return escapeAttr(value).replaceAll('>', '&gt;');
}

/**
 * @param {PromptParameterDef} def
 * @param {ToolsLocale} locale
 * @returns {string}
 */
function labelFor(def, locale) {
    return resolveLocalizedLabel(def.label, locale, def.id);
}

/**
 * @param {PromptParameterDef} def
 * @param {ToolsLocale} locale
 * @returns {string}
 */
function placeholderFor(def, locale) {
    return resolveLocalizedLabel(def.placeholder, locale, '');
}

/**
 * @param {PromptParameterDef} def
 * @param {ToolsLocale} locale
 * @returns {string}
 */
function helpFor(def, locale) {
    return resolveLocalizedLabel(def.help, locale, '');
}

function optionLabel(opt, locale) {
    if (typeof opt === 'object' && opt !== null && 'label' in opt) {
        const value = /** @type {{ value?: string }} */ (opt).value;
        return resolveLocalizedLabel(/** @type {{ label?: unknown }} */ (opt).label, locale, String(value ?? ''));
    }
    return String(opt);
}

/**
 * @param {unknown} value
 * @param {import('./config-validator.js').PromptParameterDef} def
 * @returns {unknown}
 */
export function normalizeParameterValue(value, def) {
    if (def.type === 'multiselect' || def.type === 'chips') {
        if (Array.isArray(value)) return value.map(String);
        if (typeof value === 'string') {
            return value
                .split(',')
                .map((v) => v.trim())
                .filter(Boolean);
        }
        return def.default ?? [];
    }

    if (def.type === 'number') {
        if (typeof value === 'number') return value;
        const parsed = Number(value);
        return Number.isFinite(parsed) ? parsed : def.default ?? '';
    }

    if (value === null || value === undefined) {
        return def.default ?? '';
    }

    return value;
}

/**
 * @param {import('./config-validator.js').PromptParameterDef} def
 * @param {unknown} value
 * @param {ToolsLocale} [locale]
 * @returns {string}
 */
export function renderParameterField(def, value, locale = 'en') {
    const normalized = normalizeParameterValue(value, def);
    const label = labelFor(def, locale);
    const placeholder = placeholderFor(def, locale);
    const help = helpFor(def, locale);
    const required = def.required ? ' required' : '';
    const fieldId = `ps-param-${def.id}`;

    let control = '';

    switch (def.type) {
        case 'textarea':
            control = `<textarea id="${fieldId}" class="tools-textarea ps-param-input" data-param-id="${escapeAttr(def.id)}" rows="3"${required} placeholder="${escapeAttr(placeholder)}">${escapeHtml(String(normalized ?? ''))}</textarea>`;
            break;

        case 'select': {
            const options = (def.options ?? []).map((opt) => {
                const optValue = typeof opt === 'object' && opt !== null && 'value' in opt ? String(opt.value) : String(opt);
                const optLabel = optionLabel(opt, locale);
                const selected = String(normalized ?? def.default ?? '') === optValue ? ' selected' : '';
                return `<option value="${escapeAttr(optValue)}"${selected}>${escapeHtml(optLabel)}</option>`;
            });
            control = `<select id="${fieldId}" class="tools-select ps-param-input" data-param-id="${escapeAttr(def.id)}"${required}>${options.join('')}</select>`;
            break;
        }

        case 'multiselect': {
            const selected = new Set((Array.isArray(normalized) ? normalized : []).map(String));
            const options = (def.options ?? []).map((opt) => {
                const optValue = typeof opt === 'object' && opt !== null && 'value' in opt ? String(opt.value) : String(opt);
                const optLabel = optionLabel(opt, locale);
                const checked = selected.has(optValue) ? ' checked' : '';
                return `<label class="prompt-studio__multiselect-option"><input type="checkbox" class="ps-param-input ps-param-multiselect" data-param-id="${escapeAttr(def.id)}" value="${escapeAttr(optValue)}"${checked} /> ${escapeHtml(optLabel)}</label>`;
            });
            control = `<div class="prompt-studio__multiselect" data-param-id="${escapeAttr(def.id)}">${options.join('')}</div>`;
            break;
        }

        case 'chips':
            control = `<input id="${fieldId}" class="tools-input ps-param-input" type="text" data-param-id="${escapeAttr(def.id)}" data-param-type="chips" value="${escapeAttr((Array.isArray(normalized) ? normalized : []).join(', '))}" placeholder="${escapeAttr(placeholder || t(locale, 'promptStudio.field.chipsPlaceholder'))}"${required} />`;
            break;

        case 'number':
            control = `<input id="${fieldId}" class="tools-input ps-param-input" type="number" data-param-id="${escapeAttr(def.id)}" value="${escapeAttr(String(normalized ?? ''))}" placeholder="${escapeAttr(placeholder)}"${required} />`;
            break;

        case 'color':
            control = `<input id="${fieldId}" class="tools-input ps-param-input ps-param-color" type="color" data-param-id="${escapeAttr(def.id)}" value="${escapeAttr(String(normalized || '#000000'))}"${required} />`;
            break;

        case 'aspect-ratio':
            control = `<select id="${fieldId}" class="tools-select ps-param-input" data-param-id="${escapeAttr(def.id)}"${required}>${(def.options ?? ['1:1', '16:9', '9:16', '4:3'])
                .map((opt) => {
                    const optValue = String(opt);
                    const selected = String(normalized ?? def.default ?? '') === optValue ? ' selected' : '';
                    return `<option value="${escapeAttr(optValue)}"${selected}>${escapeHtml(optValue)}</option>`;
                })
                .join('')}</select>`;
            break;

        default:
            control = `<input id="${fieldId}" class="tools-input ps-param-input" type="text" data-param-id="${escapeAttr(def.id)}" value="${escapeAttr(String(normalized ?? ''))}" placeholder="${escapeAttr(placeholder)}"${required} />`;
    }

    const helpHtml = help ? `<p class="tools-field__help prompt-studio__param-help">${escapeHtml(help)}</p>` : '';

    return `<label class="tools-field prompt-studio__param-field prompt-studio__param-field--${escapeAttr(def.type)}" data-param-id="${escapeAttr(def.id)}">
        <span class="tools-field__label">${escapeHtml(label)}${def.required ? ' *' : ''}</span>
        ${control}
        ${helpHtml}
    </label>`;
}

/**
 * @param {PromptParameterDef[]} parameters
 * @param {Record<string, unknown>} values
 * @param {ToolsLocale} [locale]
 * @returns {string}
 */
export function renderParameterFields(parameters, values, locale = 'en') {
    return parameters.map((def) => renderParameterField(def, values[def.id], locale)).join('');
}

/**
 * @param {PromptParameterDef[]} primary
 * @param {PromptParameterDef[]} advanced
 * @param {Record<string, unknown>} values
 * @param {ToolsLocale} [locale]
 * @param {string} [advancedLabel]
 * @returns {string}
 */
export function renderSplitParameterFields(primary, advanced, values, locale = 'en', advancedLabel = 'More options') {
    const primaryHtml = primary.map((def) => renderParameterField(def, values[def.id], locale)).join('');
    if (advanced.length === 0) return primaryHtml;

    const advancedHtml = advanced.map((def) => renderParameterField(def, values[def.id], locale)).join('');
    return `${primaryHtml}
        <details class="prompt-studio__advanced-fields">
            <summary class="prompt-studio__advanced-summary">${escapeHtml(advancedLabel)}</summary>
            <div class="prompt-studio__advanced-fields-inner">${advancedHtml}</div>
        </details>`;
}

/**
 * @param {HTMLElement} container
 * @param {Map<string, PromptParameterDef>} parameterMap
 * @returns {Record<string, unknown>}
 */
export function readParameterValues(container, parameterMap) {
    /** @type {Record<string, unknown>} */
    const values = {};

    container.querySelectorAll('.ps-param-input[data-param-id]').forEach((el) => {
        const input = /** @type {HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement} */ (el);
        const paramId = input.getAttribute('data-param-id');
        if (!paramId) return;

        const def = parameterMap.get(paramId);
        if (!def) return;

        if (def.type === 'multiselect') {
            const group = container.querySelector(`.prompt-studio__multiselect[data-param-id="${paramId}"]`);
            const checked = group
                ? [...group.querySelectorAll('input[type="checkbox"]:checked')].map(
                      (cb) => /** @type {HTMLInputElement} */ (cb).value,
                  )
                : [];
            values[paramId] = checked;
            return;
        }

        if (input.classList.contains('ps-param-multiselect')) return;

        values[paramId] = normalizeParameterValue(
            input instanceof HTMLInputElement && input.type === 'checkbox' ? input.checked : input.value,
            def,
        );
    });

    return values;
}

/**
 * @param {Record<string, unknown>} values
 * @param {PromptParameterDef[]} parameters
 * @returns {string[]}
 */
export function getMissingRequiredFields(values, parameters) {
    return parameters
        .filter((def) => def.required && !isTruthy(normalizeParameterValue(values[def.id], def)))
        .map((def) => def.id);
}

/**
 * @param {unknown} value
 * @returns {boolean}
 */
function isTruthy(value) {
    if (value === null || value === undefined) return false;
    if (typeof value === 'string') return value.trim().length > 0;
    if (Array.isArray(value)) return value.length > 0;
    return true;
}

/**
 * @param {PromptParameterDef[]} parameters
 * @param {Record<string, unknown>} [existing]
 * @returns {Record<string, unknown>}
 */
export function createDefaultParameterValues(parameters, existing = {}) {
    /** @type {Record<string, unknown>} */
    const values = { ...existing };
    for (const def of parameters) {
        if (values[def.id] === undefined) {
            values[def.id] = normalizeParameterValue(def.default, def);
        }
    }
    return values;
}

/**
 * @param {HTMLElement} container
 * @param {PromptParameterDef[]} parameters
 * @param {(values: Record<string, unknown>) => void} onChange
 */
export function bindParameterFields(container, parameters, onChange) {
    const parameterMap = new Map(parameters.map((def) => [def.id, def]));

    const handler = () => {
        onChange(readParameterValues(container, parameterMap));
    };

    container.querySelectorAll('.ps-param-input').forEach((el) => {
        el.addEventListener('input', handler);
        el.addEventListener('change', handler);
    });
    container.querySelectorAll('.prompt-studio__multiselect input').forEach((el) => {
        el.addEventListener('change', handler);
    });
}

/**
 * @param {PromptParameterDef[]} parameters
 * @param {Record<string, unknown>} values
 * @returns {string[]}
 */
export function validateRequiredParameters(parameters, values) {
    return getMissingRequiredFields(values, parameters);
}
