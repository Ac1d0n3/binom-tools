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
 * Flat DQ rule backlog for governance handoff (Phase-C artifact name: dq-backlog.csv).
 * @param {import('../dq-shared/dq-demo-model.js').DqModelState} state
 * @returns {string}
 */
export function buildDqBacklogCsv(state) {
    const escape = (value) => `"${String(value ?? '').replace(/"/g, '""')}"`;
    const lines = ['scope,target,rule_type,severity,params'];

    const pushRule = (scope, target, rule) => {
        const params = JSON.stringify(rule?.params ?? rule ?? {});
        lines.push([
            escape(scope),
            escape(target),
            escape(rule?.type || rule?.ruleType || ''),
            escape(rule?.severity || ''),
            escape(params),
        ].join(','));
    };

    for (const column of state.columns || []) {
        for (const rule of column.dqRules || []) {
            pushRule('column', column.name, rule);
        }
    }
    for (const rule of state.modelRules || []) {
        pushRule('model', state.modelName || '', rule);
    }

    return `${lines.join('\n')}\n`;
}

/**
 * @param {import('../dq-shared/dq-demo-model.js').DqModelState} state
 * @returns {string}
 */
export function buildDqBacklogJson(state) {
    const rules = [];
    for (const column of state.columns || []) {
        for (const rule of column.dqRules || []) {
            rules.push({ scope: 'column', target: column.name, rule });
        }
    }
    for (const rule of state.modelRules || []) {
        rules.push({ scope: 'model', target: state.modelName || '', rule });
    }
    return `${JSON.stringify({
        artifact: 'dq-backlog',
        modelName: state.modelName,
        sourceTable: state.sourceTable,
        rules,
    }, null, 2)}\n`;
}

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
