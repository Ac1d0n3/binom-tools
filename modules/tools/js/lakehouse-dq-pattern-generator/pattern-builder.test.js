import { describe, expect, it } from 'vitest';
import {
    buildDatabricksNotebook,
    buildDatabricksSql,
    buildFabricNotebook,
    buildFabricSql,
    buildRunbook,
    splitCsv,
} from './pattern-builder.js';

const state = {
    table: 'main.sales.orders',
    keys: ['order_id'],
    required: ['order_id', 'amount'],
    freshness: 'updated_at',
    pii: ['customer_email'],
    owner: 'data-owner-sales',
    pattern: 'dq',
};

describe('lakehouse dq pattern builder', () => {
    it('splits csv inputs safely', () => {
        expect(splitCsv('a, b,, c ')).toEqual(['a', 'b', 'c']);
    });

    it('builds Fabric checks and delta patterns', () => {
        expect(buildFabricSql(state)).toContain('Fabric DQ checks');
        expect(buildFabricSql({ ...state, pattern: 'delta' })).toContain('MERGE INTO');
    });

    it('builds Databricks expectations and governance patterns', () => {
        expect(buildDatabricksSql(state)).toContain('Delta Live Tables expectations');
        expect(buildDatabricksSql({ ...state, pattern: 'governance' })).toContain('Unity Catalog governance');
    });

    it('builds a release runbook', () => {
        expect(buildRunbook('databricks', state)).toContain('Release gate');
    });

    it('builds specialized Fabric generator outputs', () => {
        expect(buildFabricSql({ ...state, toolId: 'fabric-dq-rule-generator' })).toContain('Fabric DQ Rule Generator');
        expect(buildFabricNotebook({ ...state, toolId: 'fabric-notebook-snippet-generator' })).toContain('Fabric Notebook Snippet Generator');
        expect(buildRunbook('fabric', { ...state, pattern: 'pipeline', toolId: 'fabric-pipeline-checklist-generator' })).toContain('Fabric Pipeline Checklist');
        expect(buildRunbook('fabric', { ...state, pattern: 'semantic', toolId: 'fabric-semantic-model-guardrails' })).toContain('Fabric Semantic Model Guardrails');
    });

    it('builds specialized Databricks generator outputs', () => {
        expect(buildDatabricksSql({ ...state, toolId: 'databricks-dq-expectation-generator' })).toContain('Databricks DQ Expectation Generator');
        expect(buildDatabricksSql({ ...state, pattern: 'governance', toolId: 'unity-catalog-governance-generator' })).toContain('Unity Catalog Governance Generator');
        expect(buildDatabricksNotebook({ ...state, pattern: 'dbt', toolId: 'databricks-dbt-on-databricks-generator' })).toContain('Databricks job handoff for dbt');
        expect(buildRunbook('databricks', { ...state, pattern: 'delta', toolId: 'delta-load-scd-pattern-generator' })).toContain('Delta Load / SCD Pattern Runbook');
    });
});
