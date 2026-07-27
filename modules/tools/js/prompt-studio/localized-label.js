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

/**
 * Map select/chips/multiselect raw values to localized option labels for prompt output.
 * Free-text values and unknown options stay unchanged. Does not mutate the input.
 *
 * @param {Record<string, unknown>} values
 * @param {Array<{ id: string, type?: string, options?: unknown[], suggestions?: unknown[] }>} parameterDefs
 * @param {ToolsLocale} [locale]
 * @returns {Record<string, unknown>}
 */
export function localizeParameterValues(values, parameterDefs, locale = 'en') {
    if (!values || !parameterDefs?.length) return { ...values };

    /** @type {Record<string, unknown>} */
    const out = { ...values };

    for (const def of parameterDefs) {
        const raw = values[def.id];
        if (raw === null || raw === undefined || raw === '') continue;

        const choices = [...(def.options ?? []), ...(def.suggestions ?? [])];
        if (!choices.length) continue;

        /**
         * @param {unknown} entry
         * @returns {string}
         */
        const resolveChoice = (entry) => {
            const key = String(entry);
            const match = choices.find((opt) => {
                if (opt && typeof opt === 'object' && 'value' in /** @type {object} */ (opt)) {
                    return String(/** @type {{ value?: unknown }} */ (opt).value) === key;
                }
                return String(opt) === key;
            });
            if (!match || typeof match !== 'object' || match === null) return key;
            const labeled = /** @type {{ label?: unknown, value?: unknown }} */ (match);
            if ('label' in labeled) {
                return resolveLocalizedLabel(labeled.label, locale, key);
            }
            return key;
        };

        if (Array.isArray(raw)) {
            out[def.id] = raw.map(resolveChoice);
        } else {
            out[def.id] = resolveChoice(raw);
        }
    }

    return out;
}
