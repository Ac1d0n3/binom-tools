import { describe, expect, it } from 'vitest';
import { createDefaultDqModelState } from './dq-demo-model.js';
import { buildDqSchemaYaml } from './dq-yaml-builder.js';
import { collectDqIssues } from './dq-validation.js';

describe('buildDqSchemaYaml', () => {
    it('writes meta.dq_rules on columns and model', () => {
        const state = createDefaultDqModelState();
        const yaml = buildDqSchemaYaml(state);

        expect(yaml).toContain('models:');
        expect(yaml).toContain(`name: ${state.modelName}`);
        expect(yaml).toContain('tags: [dq]');
        expect(yaml).toContain('dq_rules:');
        expect(yaml).toContain('id: email_not_null');
        expect(yaml).toContain('type: regex');
        expect(yaml).toContain('id: orders_row_count');
        expect(yaml).toContain('type: row_count_between');
    });

    it('serializes accepted_values and severity', () => {
        const state = createDefaultDqModelState();
        const yaml = buildDqSchemaYaml(state);

        expect(yaml).toContain('severity: error');
        expect(yaml).toContain('values:');
        expect(yaml).toContain('"pending"');
    });
});

describe('collectDqIssues', () => {
    it('flags duplicate rule ids', () => {
        const state = createDefaultDqModelState();
        state.columns[0].dqRules.push({ ...state.columns[0].dqRules[0] });
        const issues = collectDqIssues(state);

        expect(issues.some((issue) => issue.code === 'duplicate_rule_id')).toBe(true);
    });

    it('flags invalid regex patterns', () => {
        const state = createDefaultDqModelState();
        state.columns[1].dqRules[1].pattern = '([unclosed';
        const issues = collectDqIssues(state);

        expect(issues.some((issue) => issue.code === 'regex')).toBe(true);
    });
});
