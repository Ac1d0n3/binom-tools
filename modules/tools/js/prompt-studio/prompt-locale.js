/** @typedef {import('./config-validator.js').ToolsLocale} ToolsLocale */

export const PROMPT_LOCALE_KEY = 'binom-tools-prompt-studio-prompt-locale';

/**
 * @param {unknown} value
 * @returns {ToolsLocale}
 */
export function normalizePromptLocale(value) {
    return value === 'de' || value === 'en' ? value : 'en';
}

/**
 * @param {ToolsLocale} [fallback]
 * @returns {ToolsLocale}
 */
export function getPromptLocale(fallback = 'en') {
    try {
        const stored = localStorage.getItem(PROMPT_LOCALE_KEY);
        if (stored === 'de' || stored === 'en') return stored;
    } catch {
        // ignore
    }
    return normalizePromptLocale(fallback);
}

/**
 * @param {ToolsLocale} locale
 * @returns {ToolsLocale}
 */
export function setPromptLocale(locale) {
    const normalized = normalizePromptLocale(locale);
    try {
        localStorage.setItem(PROMPT_LOCALE_KEY, normalized);
    } catch {
        // ignore
    }
    return normalized;
}
