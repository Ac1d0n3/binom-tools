/** @typedef {Object} ContentHeuristicRule
 * @property {string} regex
 * @property {string} piiType
 * @property {number} [minMatchRate]
 */

/** Default content-heuristic rules (cell value regex → PII type). */
export const DEFAULT_CONTENT_HEURISTIC_RULES = [
    {
        regex: '^[^@\\s]+@[^@\\s]+\\.[^@\\s]+$',
        piiType: 'email',
        minMatchRate: 5,
    },
    {
        regex: '^\\+?[0-9]{10,15}$',
        piiType: 'phone',
        minMatchRate: 5,
    },
    {
        regex: '^https?://[^\\s]+$',
        piiType: 'url',
        minMatchRate: 5,
    },
    {
        regex: '^[A-Z]{2}[0-9]{2}[A-Z0-9]{11,30}$',
        piiType: 'iban',
        minMatchRate: 5,
    },
];

export const DEFAULT_CONTENT_SCAN_MIN_MATCH_RATE = 5;

/** @param {number | null | undefined} value @returns {number} */
export function normalizeMinMatchRate(value) {
    const num = Number(value);
    if (!Number.isFinite(num) || num <= 0 || num > 100) {
        return DEFAULT_CONTENT_SCAN_MIN_MATCH_RATE;
    }
    return Math.round(num * 100) / 100;
}

/** @param {ContentHeuristicRule[] | null | undefined} raw @returns {ContentHeuristicRule[]} */
export function normalizeContentHeuristicRules(raw) {
    if (!Array.isArray(raw) || raw.length === 0) {
        return DEFAULT_CONTENT_HEURISTIC_RULES.map((rule) => ({ ...rule }));
    }

    return raw
        .filter((rule) => rule && typeof rule.regex === 'string' && typeof rule.piiType === 'string')
        .map((rule) => ({
            regex: rule.regex.trim(),
            piiType: rule.piiType.trim(),
            minMatchRate: normalizeMinMatchRate(rule.minMatchRate),
        }));
}
