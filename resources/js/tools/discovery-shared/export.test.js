import { describe, expect, it } from 'vitest';
import { checklistToMarkdown, rowsToCsv, rowsToMarkdownTable } from './export.js';

describe('discovery export helpers', () => {
    it('builds a markdown table', () => {
        const md = rowsToMarkdownTable(['A', 'B'], [
            ['1', 'two|three'],
            ['x', 'y'],
        ]);
        expect(md).toContain('| A | B |');
        expect(md).toContain('| --- | --- |');
        expect(md).toContain('| 1 | two\\|three |');
    });

    it('builds csv with quoting', () => {
        const csv = rowsToCsv(['Name', 'Note'], [['Alpha', 'hello, world']]);
        expect(csv).toBe('Name,Note\nAlpha,"hello, world"');
    });

    it('builds checklist markdown', () => {
        const md = checklistToMarkdown([
            { checked: true, label: 'Done', note: 'ok' },
            { checked: false, label: 'Open' },
        ]);
        expect(md).toBe('- [x] Done — ok\n- [ ] Open');
    });
});
