import { describe, expect, it } from 'vitest';
import {
    buildDatabricksSql,
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
});
