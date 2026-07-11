/** @typedef {'not_null' | 'unique' | 'accepted_values' | 'regex' | 'range' | 'expression' | 'freshness' | 'row_count_between'} DqRuleType */

/** @typedef {'error' | 'warn'} DqSeverity */

/**
 * @typedef {Object} DqRule
 * @property {string} id
 * @property {DqRuleType} type
 * @property {DqSeverity} severity
 * @property {string} [pattern]
 * @property {number} [min]
 * @property {number} [max]
 * @property {string[]} [values]
 * @property {string} [sql]
 * @property {number} [max_hours]
 */

/** @type {Record<DqRuleType, { label: string, modelLevel?: boolean, params: string[] }>} */
export const RULE_TYPE_DEFS = {
    not_null: { label: 'Not null', params: [] },
    unique: { label: 'Unique', params: [] },
    accepted_values: { label: 'Accepted values', params: ['values'] },
    regex: { label: 'Regex', params: ['pattern'] },
    range: { label: 'Range', params: ['min', 'max'] },
    expression: { label: 'Expression', params: ['sql'] },
    freshness: { label: 'Freshness (hours)', params: ['max_hours'] },
    row_count_between: { label: 'Row count between', modelLevel: true, params: ['min', 'max'] },
};

/** @type {DqRuleType[]} */
export const RULE_TYPE_IDS = /** @type {DqRuleType[]} */ (Object.keys(RULE_TYPE_DEFS));

/** @type {DqSeverity[]} */
export const SEVERITY_OPTIONS = ['error', 'warn'];

/**
 * @param {DqRuleType} type
 * @returns {DqRule}
 */
export function createDefaultRule(type = 'not_null') {
    /** @type {DqRule} */
    const rule = {
        id: `${type}_${Date.now().toString(36).slice(-4)}`,
        type,
        severity: 'error',
    };

    if (type === 'regex') {
        rule.pattern = '^[^@\\s]+@[^@\\s]+\\.[^@\\s]+$';
    }
    if (type === 'accepted_values') {
        rule.values = ['active', 'inactive'];
    }
    if (type === 'range') {
        rule.min = 0;
        rule.max = 100;
    }
    if (type === 'expression') {
        rule.sql = 'amount >= 0';
    }
    if (type === 'freshness') {
        rule.max_hours = 24;
    }
    if (type === 'row_count_between') {
        rule.min = 1;
        rule.max = 1000000;
    }

    return rule;
}

/** @param {unknown} raw @returns {DqRule} */
export function normalizeRule(raw) {
    const type = RULE_TYPE_IDS.includes(/** @type {DqRuleType} */ (raw?.type))
        ? /** @type {DqRuleType} */ (raw.type)
        : 'not_null';
    const defaults = createDefaultRule(type);

    return {
        id: typeof raw?.id === 'string' && raw.id.trim() ? raw.id.trim() : defaults.id,
        type,
        severity: SEVERITY_OPTIONS.includes(raw?.severity) ? raw.severity : defaults.severity,
        pattern: typeof raw?.pattern === 'string' ? raw.pattern : defaults.pattern,
        min: raw?.min ?? defaults.min,
        max: raw?.max ?? defaults.max,
        values: Array.isArray(raw?.values) ? raw.values.map(String) : defaults.values,
        sql: typeof raw?.sql === 'string' ? raw.sql : defaults.sql,
        max_hours: raw?.max_hours ?? defaults.max_hours,
    };
}
