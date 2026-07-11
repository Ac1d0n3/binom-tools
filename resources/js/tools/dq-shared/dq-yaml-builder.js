import { RULE_TYPE_DEFS } from './rule-types.js';

/**
 * @param {import('./rule-types.js').DqRule} rule
 * @returns {string[]}
 */
function ruleToYamlLines(rule) {
    const lines = [`        - id: ${rule.id}`, `          type: ${rule.type}`, `          severity: ${rule.severity}`];

    const def = RULE_TYPE_DEFS[rule.type];
    for (const param of def?.params ?? []) {
        if (param === 'values' && rule.values?.length) {
            lines.push(`          values: [${rule.values.map((v) => JSON.stringify(v)).join(', ')}]`);
        } else if (param === 'pattern' && rule.pattern) {
            lines.push(`          pattern: ${JSON.stringify(rule.pattern)}`);
        } else if (param === 'min' && rule.min !== undefined) {
            lines.push(`          min: ${rule.min}`);
        } else if (param === 'max' && rule.max !== undefined) {
            lines.push(`          max: ${rule.max}`);
        } else if (param === 'sql' && rule.sql) {
            lines.push(`          sql: ${JSON.stringify(rule.sql)}`);
        } else if (param === 'max_hours' && rule.max_hours !== undefined) {
            lines.push(`          max_hours: ${rule.max_hours}`);
        }
    }

    return lines;
}

/**
 * @param {import('./dq-demo-model.js').DqModelState} state
 * @returns {string}
 */
export function buildDqSchemaYaml(state) {
    const descriptionLines = state.modelDescription.split('\n').filter((line, i, arr) => {
        if (line.trim()) return true;
        return arr.slice(i + 1).some((l) => l.trim());
    });

    const lines = [
        'version: 2',
        '',
        'models:',
        `  - name: ${state.modelName}`,
        '    config:',
        '      tags: [dq]',
        '    description: |',
        ...descriptionLines.map((line) => `      ${line}`),
    ];

    if (state.modelRules.length > 0) {
        lines.push('    meta:');
        lines.push('      dq_rules:');
        for (const rule of state.modelRules) {
            lines.push(...ruleToYamlLines(rule).map((l) => l.replace(/^        /, '      ')));
        }
    }

    lines.push('    columns:');

    for (const column of state.columns) {
        lines.push(`      - name: ${column.name}`);
        if (column.description?.trim()) {
            lines.push(`        description: ${column.description.trim()}`);
        }
        if (column.dqRules.length > 0) {
            lines.push('        meta:');
            lines.push('          dq_rules:');
            for (const rule of column.dqRules) {
                lines.push(...ruleToYamlLines(rule));
            }
        }
    }

    return `${lines.join('\n')}\n`;
}
