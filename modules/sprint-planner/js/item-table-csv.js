/**
 * CSV export/import for plan item tables (Excel-friendly round-trip).
 */

import { createLocalId } from './ids.js';

/**
 * @param {unknown} value
 */
function csvCell(value) {
    const text = value == null ? '' : String(value);
    if (/[",\r\n]/.test(text)) {
        return `"${text.replace(/"/g, '""')}"`;
    }
    return text;
}

/**
 * Minimal RFC4180-ish CSV parser (quoted fields, commas, newlines).
 * @param {string} text
 * @returns {string[][]}
 */
export function parseCsv(text) {
    const source = String(text || '').replace(/^\uFEFF/, '');
    /** @type {string[][]} */
    const rows = [];
    /** @type {string[]} */
    let row = [];
    let field = '';
    let inQuotes = false;
    for (let i = 0; i < source.length; i += 1) {
        const ch = source[i];
        const next = source[i + 1];
        if (inQuotes) {
            if (ch === '"' && next === '"') {
                field += '"';
                i += 1;
            } else if (ch === '"') {
                inQuotes = false;
            } else {
                field += ch;
            }
            continue;
        }
        if (ch === '"') {
            inQuotes = true;
            continue;
        }
        if (ch === ',') {
            row.push(field);
            field = '';
            continue;
        }
        if (ch === '\n') {
            row.push(field);
            rows.push(row);
            row = [];
            field = '';
            continue;
        }
        if (ch === '\r') {
            continue;
        }
        field += ch;
    }
    if (field.length || row.length) {
        row.push(field);
        rows.push(row);
    }
    return rows.filter((r) => r.some((cell) => String(cell || '').trim() !== ''));
}

/**
 * @param {string} label
 */
export function normalizeHeaderKey(label) {
    return String(label || '')
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .replace(/[^a-z0-9]+/g, '')
        .trim();
}

/**
 * @param {import('./item-table.js').SpItemTable} table
 * @returns {string} CSV with UTF-8 BOM for Excel
 */
export function itemTableToCsv(table) {
    const columns = table?.columns || [];
    const headers = columns.map((c) => c.label || c.id);
    const lines = [headers.map(csvCell).join(',')];
    for (const row of table?.rows || []) {
        lines.push(columns.map((col) => csvCell(row.cells?.[col.id] ?? '')).join(','));
    }
    return `\uFEFF${lines.join('\n')}\n`;
}

/**
 * Map CSV onto existing plan columns by header label (and id fallback).
 * Unknown CSV columns are ignored; missing plan columns become empty.
 *
 * @param {string} csvText
 * @param {import('./item-table.js').SpTableColumn[]} columns
 * @returns {{ ok: true, rows: import('./item-table.js').SpTableRow[] } | { ok: false, error: string }}
 */
export function csvToItemTableRows(csvText, columns) {
    const grid = parseCsv(csvText);
    if (!grid.length) {
        return { ok: false, error: 'empty' };
    }
    if (!columns?.length) {
        return { ok: false, error: 'no-columns' };
    }

    const header = grid[0].map((h) => String(h || '').trim());
    /** @type {Record<string, number>} */
    const headerIndex = {};
    header.forEach((label, index) => {
        const key = normalizeHeaderKey(label);
        if (key && headerIndex[key] === undefined) {
            headerIndex[key] = index;
        }
    });

    /** @type {Array<{ col: import('./item-table.js').SpTableColumn, index: number }>} */
    const mapped = [];
    for (const col of columns) {
        const byLabel = headerIndex[normalizeHeaderKey(col.label || col.id)];
        const byId = headerIndex[normalizeHeaderKey(col.id)];
        const index = byLabel !== undefined ? byLabel : byId;
        if (index !== undefined) {
            mapped.push({ col, index });
        }
    }
    if (!mapped.length) {
        return { ok: false, error: 'no-matching-columns' };
    }

    const dataRows = grid.slice(1);
    const rows = dataRows.map((cells) => {
        /** @type {Record<string, string>} */
        const nextCells = {};
        for (const col of columns) {
            nextCells[col.id] = '';
        }
        for (const entry of mapped) {
            nextCells[entry.col.id] = String(cells[entry.index] ?? '');
        }
        return {
            id: createLocalId('row_'),
            cells: nextCells,
        };
    });

    return { ok: true, rows };
}

/**
 * @param {string} filename
 * @param {string} content
 */
export function downloadCsvFile(filename, content) {
    const safe = String(filename || 'table.csv').replace(/[^\w.\-]+/g, '-');
    const blob = new Blob([content], { type: 'text/csv;charset=utf-8' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = safe.endsWith('.csv') ? safe : `${safe}.csv`;
    link.rel = 'noopener';
    document.body.appendChild(link);
    link.click();
    link.remove();
    window.setTimeout(() => URL.revokeObjectURL(url), 1000);
}
