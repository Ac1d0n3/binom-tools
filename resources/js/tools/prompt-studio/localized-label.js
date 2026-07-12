/** @typedef {'de' | 'en'} ToolsLocale */

/**
 * Resolve localized UI/config labels to plain strings.
 * Prevents "[object Object]" when { de, en } records are rendered directly.
 *
 * @param {unknown} value
 * @param {ToolsLocale} [locale]
 * @param {string} [fallback]
 * @returns {string}
 */
export function resolveLocalizedLabel(value, locale = 'en', fallback = '') {
    if (value === null || value === undefined) return fallback;
    if (typeof value === 'string') return value;
    if (typeof value === 'number' || typeof value === 'boolean') return String(value);

    if (Array.isArray(value)) {
        return value.map((entry) => resolveLocalizedLabel(entry, locale, '')).filter(Boolean).join(', ') || fallback;
    }

    if (typeof value === 'object') {
        const record = /** @type {Record<string, unknown>} */ (value);
        const localized = record[locale] ?? record.en ?? record.de;
        if (localized !== undefined && localized !== null && localized !== value) {
            return resolveLocalizedLabel(localized, locale, fallback);
        }
        return fallback;
    }

    return fallback;
}

/**
 * @param {unknown} value
 * @param {ToolsLocale} [locale]
 * @returns {string}
 */
export function formatDisplayValue(value, locale = 'en') {
    if (value === null || value === undefined) return '';
    if (typeof value === 'string') return value;
    if (typeof value === 'number' || typeof value === 'boolean') return String(value);
    if (Array.isArray(value)) {
        return value.map((entry) => formatDisplayValue(entry, locale)).filter(Boolean).join(', ');
    }
    if (typeof value === 'object') {
        const record = /** @type {Record<string, unknown>} */ (value);
        if ('value' in record) return formatDisplayValue(record.value, locale);
        if ('label' in record) return resolveLocalizedLabel(record.label, locale, '');
        return resolveLocalizedLabel(value, locale, '');
    }
    return String(value);
}
