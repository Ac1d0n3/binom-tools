import { describe, expect, it } from 'vitest';
import { buildPiiGovernanceMacro } from '../dbt-governance-macro-generator/governance-macro-builder.js';
import { buildPiiContentScanMacro } from '../pii-recommend-generator/pii-audit-builder.js';
import { createDefaultModelState } from './demo-model.js';
import {
    getWarehouseTemplate,
    warehouseDialectPreview,
    warehouseIds,
} from './warehouse-templates.js';

describe('warehouse-templates', () => {
    it('includes postgres with dialect helpers', () => {
        expect(warehouseIds).toContain('postgres');
        const wh = getWarehouseTemplate('postgres');
        expect(wh.label).toBe('Postgres');
        expect(wh.maskExpr('column_name')).toContain('substring(column_name from');
        expect(wh.regexMatch('val', 'a@b.c')).toBe("val ~ 'a@b.c'");
        expect(wh.nullCast()).toBe('cast(null as text)');
        expect(wh.sampleClause(100)).toBe('LIMIT 100');
    });

    it('preview helpers differ between snowflake and postgres', () => {
        const snowMask = warehouseDialectPreview('snowflake', 'mask');
        const pgMask = warehouseDialectPreview('postgres', 'mask');
        expect(snowMask).toContain('concat(');
        expect(pgMask).toContain("||");
        expect(pgMask).not.toBe(snowMask);

        const snowRegex = warehouseDialectPreview('snowflake', 'regex');
        const pgRegex = warehouseDialectPreview('postgres', 'regex');
        expect(snowRegex).toContain('REGEXP_LIKE');
        expect(pgRegex).toContain(' ~ ');
        expect(pgRegex).not.toBe(snowRegex);
    });

    it('governance macro embeds postgres mask and warehouse comment', () => {
        const state = createDefaultModelState();
        state.selectedWarehouse = 'postgres';
        const sql = buildPiiGovernanceMacro(state);
        expect(sql).toContain('{# Warehouse: Postgres');
        expect(sql).toContain("substring(column_name from 1 for 2)");
        expect(sql).toContain('cast(null as text)');
    });

    it('content scan macro embeds postgres regex and sample clause', () => {
        const state = createDefaultModelState();
        state.selectedWarehouse = 'postgres';
        const sql = buildPiiContentScanMacro(state);
        expect(sql).toContain('{# Warehouse: Postgres');
        expect(sql).toContain("val ~ '");
        expect(sql).toContain('LIMIT __LIMIT__');
    });
});
