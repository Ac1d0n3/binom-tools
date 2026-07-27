import { describe, expect, it } from 'vitest';
import {
    csvToItemTableRows,
    itemTableToCsv,
    normalizeHeaderKey,
    parseCsv,
} from './item-table-csv.js';

describe('item table CSV', () => {
    it('parses quoted commas and newlines', () => {
        const grid = parseCsv('Name,Note\nAlpha,"hello, world"\nBeta,"line1\nline2"\n');
        expect(grid).toEqual([
            ['Name', 'Note'],
            ['Alpha', 'hello, world'],
            ['Beta', 'line1\nline2'],
        ]);
    });

    it('round-trips an item table', () => {
        const table = {
            columns: [
                { id: 'kpi', label: 'KPI' },
                { id: 'owner', label: 'Owner' },
            ],
            rows: [
                { id: 'r1', cells: { kpi: 'Revenue', owner: 'A, B' } },
            ],
        };
        const csv = itemTableToCsv(table);
        expect(csv.startsWith('\uFEFF')).toBe(true);
        expect(csv).toContain('KPI,Owner');
        expect(csv).toContain('Revenue,"A, B"');

        const parsed = csvToItemTableRows(csv, table.columns);
        expect(parsed.ok).toBe(true);
        if (!parsed.ok) {
            return;
        }
        expect(parsed.rows).toHaveLength(1);
        expect(parsed.rows[0].cells).toEqual({ kpi: 'Revenue', owner: 'A, B' });
    });

    it('maps headers by normalized label', () => {
        expect(normalizeHeaderKey('Geschäftsfrage')).toBe('geschaftsfrage');
        const result = csvToItemTableRows(
            'Report,Rhythmus\nSales,weekly\n',
            [
                { id: 'report', label: 'Report' },
                { id: 'rhythmus', label: 'Rhythmus' },
            ],
        );
        expect(result.ok).toBe(true);
        if (!result.ok) {
            return;
        }
        expect(result.rows[0].cells).toEqual({ report: 'Sales', rhythmus: 'weekly' });
    });

    it('fails when no columns match', () => {
        const result = csvToItemTableRows('Foo,Bar\n1,2\n', [
            { id: 'report', label: 'Report' },
        ]);
        expect(result).toEqual({ ok: false, error: 'no-matching-columns' });
    });
});
