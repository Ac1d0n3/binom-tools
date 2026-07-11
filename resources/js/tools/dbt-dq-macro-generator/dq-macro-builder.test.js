import { describe, expect, it } from 'vitest';
import { createDefaultDqModelState } from '../dq-shared/dq-demo-model.js';
import {
    buildDqGovernanceMacro,
    buildDqRuleTest,
    buildSetupDqMarkdown,
} from './dq-macro-builder.js';

describe('buildDqGovernanceMacro', () => {
    it('includes warehouse-specific regex and core macros', () => {
        const state = createDefaultDqModelState();
        state.selectedWarehouse = 'snowflake';
        const sql = buildDqGovernanceMacro(state);

        expect(sql).toContain('macros/dq_governance.sql');
        expect(sql).toContain('dq_effective_rules');
        expect(sql).toContain('dq_check_not_null');
        expect(sql).toContain('REGEXP_LIKE');
        expect(sql).toContain('dq_record_result');
    });

    it('switches regex dialect for BigQuery', () => {
        const state = createDefaultDqModelState();
        state.selectedWarehouse = 'bigquery';
        const sql = buildDqGovernanceMacro(state);

        expect(sql).toContain('REGEXP_CONTAINS');
    });
});

describe('buildDqRuleTest', () => {
    it('generates generic dq_rule test with rule types', () => {
        const sql = buildDqRuleTest();

        expect(sql).toContain('{% test dq_rule');
        expect(sql).toContain("rule_type == 'not_null'");
        expect(sql).toContain("rule_type == 'row_count_between'");
    });
});

describe('buildSetupDqMarkdown', () => {
    it('references model name and runbook commands', () => {
        const state = createDefaultDqModelState();
        const md = buildSetupDqMarkdown(state);

        expect(md).toContain(state.modelName);
        expect(md).toContain('dbt test --select tag:dq');
        expect(md).toContain('dq_collect_results');
    });
});
