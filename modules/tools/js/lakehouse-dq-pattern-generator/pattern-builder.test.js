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

    it('includes regional pack checks in Fabric and Databricks DQ output', () => {
        const withPacks = {
            ...state,
            region: 'DE',
            appliedPackIds: ['address-format'],
            packNotes: ['Address column order (DE): street → house_number → postal_code → city'],
            extraChecks: [
                { column: 'postal_code', type: 'regex', pattern: '^[0-9]{5}$', severity: 'error' },
                { column: 'email', type: 'regex', pattern: '^[^@\\s]+@[^@\\s]+\\.[^@\\s]+$', severity: 'warn' },
            ],
        };
        expect(buildFabricSql(withPacks)).toContain('postal_code');
        expect(buildFabricSql(withPacks)).toContain('Region: DE');
        expect(buildFabricSql(withPacks)).toContain('regex gate');
        expect(buildDatabricksSql(withPacks)).toContain('RLIKE');
        expect(buildDatabricksSql(withPacks)).toContain('postal_code_pack_regex');
        expect(buildFabricNotebook(withPacks)).toContain('pack_regex_checks');
        expect(buildDatabricksNotebook(withPacks)).toContain('pack_regex_checks');
    });
});
