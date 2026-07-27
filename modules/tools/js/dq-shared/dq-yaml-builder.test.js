import { describe, expect, it } from 'vitest';
import { createDefaultDqModelState } from './dq-demo-model.js';
import { buildDqSchemaYaml } from './dq-yaml-builder.js';
import { buildDqSourcesYaml, buildDqModelSql } from './dq-sources-builder.js';
import { collectDqIssues } from './dq-validation.js';
import { buildDqGenericTestsSnippet } from '../dbt-dq-rules-generator/dq-rules-builder.js';

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

    it('includes provenance header comments', () => {
        const state = createDefaultDqModelState();
        const yaml = buildDqSchemaYaml(state);

        expect(yaml).toContain('# Step 2 — DQ Rules Generator');
        expect(yaml).toContain(`Source: ${state.sourceTable}`);
        expect(yaml).toContain('dq_governance.sql');
        expect(yaml).toContain('dq_rule.sql');
    });

    it('serializes accepted_values and severity', () => {
        const state = createDefaultDqModelState();
        const yaml = buildDqSchemaYaml(state);

        expect(yaml).toContain('severity: error');
        expect(yaml).toContain('values:');
        expect(yaml).toContain('"pending"');
    });
});

describe('buildDqSourcesYaml', () => {
    it('writes sources.yml for raw.orders', () => {
        const state = createDefaultDqModelState();
        const yaml = buildDqSourcesYaml(state);

        expect(yaml).toContain('sources:');
        expect(yaml).toContain('- name: raw');
        expect(yaml).toContain('- name: orders');
        expect(yaml).toContain('Source for model');
    });
});

describe('buildDqModelSql', () => {
    it('references source() from source table', () => {
        const state = createDefaultDqModelState();
        const sql = buildDqModelSql(state);

        expect(sql).toContain("source('raw', 'orders')");
        expect(sql).toContain(state.modelName);
    });
});

describe('buildDqGenericTestsSnippet', () => {
    it('includes rule params in dq_rule tests', () => {
        const state = createDefaultDqModelState();
        const snippet = buildDqGenericTestsSnippet(state);

        expect(snippet).toContain('dq_rule:');
        expect(snippet).toContain('pattern:');
        expect(snippet).toContain('values:');
        expect(snippet).toContain('max_hours:');
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
