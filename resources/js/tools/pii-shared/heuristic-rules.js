/** @typedef {Object} HeuristicRule
 * @property {string} pattern
 * @property {string} piiType
 */

/** Default name-heuristic rules (column name substrings → PII type). */
export const DEFAULT_HEURISTIC_RULES = [
    { pattern: 'info_mail', piiType: 'none' },
    { pattern: 'info-mail', piiType: 'none' },
    { pattern: 'info_email', piiType: 'none' },
    { pattern: '_id', piiType: 'none' },
    { pattern: 'created_at', piiType: 'none' },
    { pattern: 'updated_at', piiType: 'none' },
    { pattern: 'email', piiType: 'email' },
    { pattern: 'mail', piiType: 'email' },
    { pattern: 'agent_name', piiType: 'name' },
    { pattern: 'full_name', piiType: 'name' },
    { pattern: 'person_name', piiType: 'name' },
    { pattern: 'name', piiType: 'name' },
    { pattern: 'filename', piiType: 'none' },
    { pattern: 'ipv4', piiType: 'ip' },
    { pattern: 'ipv6', piiType: 'ip' },
    { pattern: 'client_ip', piiType: 'ip' },
    { pattern: 'ip', piiType: 'ip' },
    { pattern: 'address', piiType: 'address' },
    { pattern: 'street', piiType: 'address' },
    { pattern: 'postal', piiType: 'address' },
    { pattern: 'zip', piiType: 'address' },
    { pattern: 'phone', piiType: 'phone' },
    { pattern: 'mobile', piiType: 'phone' },
    { pattern: 'tel', piiType: 'phone' },
    { pattern: 'latitude', piiType: 'geo' },
    { pattern: 'longitude', piiType: 'geo' },
    { pattern: 'geo', piiType: 'geo' },
    { pattern: 'card', piiType: 'card' },
    { pattern: 'pan', piiType: 'card' },
    { pattern: 'iban', piiType: 'iban' },
    { pattern: 'passport', piiType: 'passport' },
    { pattern: 'plate', piiType: 'license_plate' },
    { pattern: 'license', piiType: 'license_plate' },
    { pattern: 'dob', piiType: 'date_of_birth' },
    { pattern: 'birth', piiType: 'date_of_birth' },
    { pattern: 'date_of_birth', piiType: 'date_of_birth' },
    { pattern: 'url', piiType: 'url' },
    { pattern: 'link', piiType: 'url' },
    { pattern: 'website', piiType: 'url' },
];

/** @param {HeuristicRule[] | null | undefined} raw @returns {HeuristicRule[]} */
export function normalizeHeuristicRules(raw) {
    if (!Array.isArray(raw) || raw.length === 0) {
        return DEFAULT_HEURISTIC_RULES.map((rule) => ({ ...rule }));
    }
    return raw
        .filter((rule) => rule && typeof rule.pattern === 'string' && typeof rule.piiType === 'string')
        .map((rule) => ({ pattern: rule.pattern.trim(), piiType: rule.piiType.trim() }));
}
