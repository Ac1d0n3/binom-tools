import { RULE_TYPE_DEFS, RULE_TYPE_IDS } from './rule-types.js';

/** @typedef {'error' | 'warning' | 'info'} ValidationLevel */

/**
 * @typedef {Object} DqValidationIssue
 * @property {ValidationLevel} severity
 * @property {string} code
 * @property {string} messageKey
 * @property {Record<string, string | number>} [params]
 */

/**
 * @param {import('./dq-demo-model.js').DqModelState} state
 * @returns {DqValidationIssue[]}
 */
export function collectDqIssues(state) {
    /** @type {DqValidationIssue[]} */
    const issues = [];

    if (!state.modelName?.trim()) {
        issues.push({ severity: 'error', code: 'model_name', messageKey: 'dq.validation.modelName' });
    }

    const columnNames = new Set();
    for (const column of state.columns) {
        const name = column.name?.trim();
        if (!name) {
            issues.push({ severity: 'error', code: 'column_name', messageKey: 'dq.validation.columnName' });
            continue;
        }
        if (columnNames.has(name)) {
            issues.push({
                severity: 'error',
                code: 'duplicate_column',
                messageKey: 'dq.validation.duplicateColumn',
                params: { name },
            });
        }
        columnNames.add(name);
        issues.push(...validateRules(column.dqRules, `column:${name}`));
    }

    issues.push(...validateRules(state.modelRules, 'model'));

    return issues;
}

/**
 * @param {import('./rule-types.js').DqRule[]} rules
 * @param {string} scope
 * @returns {DqValidationIssue[]}
 */
function validateRules(rules, scope) {
    /** @type {DqValidationIssue[]} */
    const issues = [];
    const ids = new Set();

    for (const rule of rules) {
        if (!rule.id?.trim()) {
            issues.push({ severity: 'error', code: 'rule_id', messageKey: 'dq.validation.ruleId', params: { scope } });
        } else if (ids.has(rule.id)) {
            issues.push({
                severity: 'error',
                code: 'duplicate_rule_id',
                messageKey: 'dq.validation.duplicateRuleId',
                params: { id: rule.id, scope },
            });
        } else {
            ids.add(rule.id);
        }

        if (!RULE_TYPE_IDS.includes(rule.type)) {
            issues.push({
                severity: 'error',
                code: 'rule_type',
                messageKey: 'dq.validation.ruleType',
                params: { id: rule.id },
            });
        }

        if (rule.type === 'regex' && rule.pattern) {
            try {
                // eslint-disable-next-line no-new
                new RegExp(rule.pattern);
            } catch {
                issues.push({
                    severity: 'error',
                    code: 'regex',
                    messageKey: 'dq.validation.regex',
                    params: { id: rule.id },
                });
            }
        }

        const def = RULE_TYPE_DEFS[rule.type];
        if (def?.params.includes('values') && (!rule.values || rule.values.length === 0)) {
            issues.push({
                severity: 'error',
                code: 'accepted_values',
                messageKey: 'dq.validation.acceptedValues',
                params: { id: rule.id },
            });
        }
        if (def?.params.includes('pattern') && !rule.pattern?.trim()) {
            issues.push({
                severity: 'error',
                code: 'pattern',
                messageKey: 'dq.validation.pattern',
                params: { id: rule.id },
            });
        }
        if (def?.params.includes('sql') && !rule.sql?.trim()) {
            issues.push({
                severity: 'error',
                code: 'expression',
                messageKey: 'dq.validation.expression',
                params: { id: rule.id },
            });
        }
    }

    return issues;
}

/** @param {DqValidationIssue[]} issues @returns {boolean} */
export function hasDqValidationErrors(issues) {
    return issues.some((i) => i.severity === 'error');
}
