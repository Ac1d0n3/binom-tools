import { RULE_TYPE_DEFS } from './rule-types.js';
import { getWarehouseTemplate } from '../pii-shared/warehouse-templates.js';

/**
 * @param {import('./rule-types.js').DqRule} rule
 * @param {number} [indent=8]
 * @returns {string[]}
 */
export function ruleToYamlLines(rule, indent = 8) {
    const pad = ' '.repeat(indent);
    const lines = [`${pad}- id: ${rule.id}`, `${pad}  type: ${rule.type}`, `${pad}  severity: ${rule.severity}`];

    const def = RULE_TYPE_DEFS[rule.type];
    for (const param of def?.params ?? []) {
        if (param === 'values' && rule.values?.length) {
            lines.push(`${pad}  values: [${rule.values.map((v) => JSON.stringify(v)).join(', ')}]`);
        } else if (param === 'pattern' && rule.pattern) {
            lines.push(`${pad}  pattern: ${JSON.stringify(rule.pattern)}`);
        } else if (param === 'min' && rule.min !== undefined) {
            lines.push(`${pad}  min: ${rule.min}`);
        } else if (param === 'max' && rule.max !== undefined) {
            lines.push(`${pad}  max: ${rule.max}`);
        } else if (param === 'sql' && rule.sql) {
            lines.push(`${pad}  sql: ${JSON.stringify(rule.sql)}`);
        } else if (param === 'max_hours' && rule.max_hours !== undefined) {
            lines.push(`${pad}  max_hours: ${rule.max_hours}`);
        }
    }

    return lines;
}

/**
 * @param {import('./rule-types.js').DqRule} rule
 * @param {number} [indent=12]
 * @returns {string[]}
 */
export function ruleToTestYamlLines(rule, indent = 12) {
    const pad = ' '.repeat(indent);
    const lines = [`${pad}id: ${rule.id}`, `${pad}type: ${rule.type}`, `${pad}severity: ${rule.severity}`];

    const def = RULE_TYPE_DEFS[rule.type];
    for (const param of def?.params ?? []) {
        if (param === 'values' && rule.values?.length) {
            lines.push(`${pad}values: [${rule.values.map((v) => JSON.stringify(v)).join(', ')}]`);
        } else if (param === 'pattern' && rule.pattern) {
            lines.push(`${pad}pattern: ${JSON.stringify(rule.pattern)}`);
        } else if (param === 'min' && rule.min !== undefined) {
            lines.push(`${pad}min: ${rule.min}`);
        } else if (param === 'max' && rule.max !== undefined) {
            lines.push(`${pad}max: ${rule.max}`);
        } else if (param === 'sql' && rule.sql) {
            lines.push(`${pad}sql: ${JSON.stringify(rule.sql)}`);
        } else if (param === 'max_hours' && rule.max_hours !== undefined) {
            lines.push(`${pad}max_hours: ${rule.max_hours}`);
        }
    }

    return lines;
}

/**
 * @param {import('./dq-demo-model.js').DqModelState} state
 * @returns {string}
 */
export function buildDqSchemaYaml(state) {
    const wh = getWarehouseTemplate(state.selectedWarehouse);
    const descriptionLines = state.modelDescription.split('\n').filter((line, i, arr) => {
        if (line.trim()) return true;
        return arr.slice(i + 1).some((l) => l.trim());
    });

    const lines = [
        `# Step 2 — DQ Rules Generator`,
        `# Model: ${state.modelName} | Source: ${state.sourceTable} | Warehouse: ${wh.label}`,
        `# Columns below mirror the source table — edit names/rules to match your project.`,
        `# Requires: macros/dq_governance.sql + tests/generic/dq_rule.sql (Step 1 — DQ Macro Generator)`,
        `# Target: models/schema/${state.modelName}.yml`,
        '',
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
            lines.push(...ruleToYamlLines(rule, 6));
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
