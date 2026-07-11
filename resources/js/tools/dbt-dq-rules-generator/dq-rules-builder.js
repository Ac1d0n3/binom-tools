import { buildDqSchemaYaml, ruleToTestYamlLines } from '../dq-shared/dq-yaml-builder.js';
import { buildDqSourcesYaml, buildDqModelSql } from '../dq-shared/dq-sources-builder.js';

/**
 * @param {import('../dq-shared/dq-demo-model.js').DqModelState} state
 * @returns {string}
 */
export function buildDqRulesYaml(state) {
    return buildDqSchemaYaml(state);
}

export { buildDqSourcesYaml, buildDqModelSql };

/**
 * @param {import('../dq-shared/dq-demo-model.js').DqModelState} state
 * @returns {string}
 */
export function buildDqGenericTestsSnippet(state) {
    const lines = [
        '# Step 2 — attach under models: in schema.yml',
        '# Requires: tests/generic/dq_rule.sql from Step 1 (DQ Macro Generator)',
        '',
    ];

    let hasRules = false;

    for (const column of state.columns) {
        if (!column.dqRules.length) continue;
        hasRules = true;
        lines.push(`  - name: ${column.name}`);
        lines.push('    tests:');
        for (const rule of column.dqRules) {
            lines.push('      - dq_rule:');
            lines.push(`          column_name: ${column.name}`);
            lines.push('          rule:');
            lines.push(...ruleToTestYamlLines(rule));
        }
        lines.push('');
    }

    if (state.modelRules.length > 0) {
        hasRules = true;
        lines.push(`  - name: ${state.modelName}`);
        lines.push('    tests:');
        for (const rule of state.modelRules) {
            lines.push('      - dq_rule:');
            lines.push('          rule:');
            lines.push(...ruleToTestYamlLines(rule));
        }
        lines.push('');
    }

    if (!hasRules) {
        lines.push('# Add meta.dq_rules first, then attach dq_rule tests here.');
    }

    return `${lines.join('\n')}\n`;
}
