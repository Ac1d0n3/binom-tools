import { buildDqSchemaYaml } from '../dq-shared/dq-yaml-builder.js';

/**
 * @param {import('../dq-shared/dq-demo-model.js').DqModelState} state
 * @returns {string}
 */
export function buildDqRulesYaml(state) {
    return buildDqSchemaYaml(state);
}

/**
 * @param {import('../dq-shared/dq-demo-model.js').DqModelState} state
 * @returns {string}
 */
export function buildDqGenericTestsSnippet(state) {
    const lines = ['# Add to schema.yml under each column with dq_rules:', ''];

    for (const column of state.columns) {
        if (!column.dqRules.length) continue;
        lines.push(`  - name: ${column.name}`);
        lines.push('    tests:');
        for (const rule of column.dqRules) {
            lines.push(`      - dq_rule:`);
            lines.push(`          column_name: ${column.name}`);
            lines.push(`          rule:`);
            lines.push(`            id: ${rule.id}`);
            lines.push(`            type: ${rule.type}`);
            lines.push(`            severity: ${rule.severity}`);
        }
        lines.push('');
    }

    for (const rule of state.modelRules) {
        lines.push(`# Model-level rule: ${rule.id}`);
        lines.push('    tests:');
        lines.push(`      - dq_rule:`);
        lines.push(`          rule:`);
        lines.push(`            id: ${rule.id}`);
        lines.push(`            type: ${rule.type}`);
        lines.push(`            severity: ${rule.severity}`);
        lines.push('');
    }

    return `${lines.join('\n')}\n`;
}
