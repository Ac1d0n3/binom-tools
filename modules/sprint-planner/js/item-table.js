import { createLocalId } from './ids.js';
import {
    csvToItemTableRows,
    downloadCsvFile,
    itemTableToCsv,
} from './item-table-csv.js';

/**
 * @typedef {{ id: string, label: string }} SpTableColumn
 * @typedef {{ id: string, cells: Record<string, string> }} SpTableRow
 * @typedef {{ columns: SpTableColumn[], rows: SpTableRow[] }} SpItemTable
 */

/**
 * @param {unknown} raw
 * @returns {SpItemTable|null}
 */
export function normalizeItemTable(raw) {
    if (!raw || typeof raw !== 'object') {
        return null;
    }
    const source = /** @type {Record<string, unknown>} */ (raw);
    const columns = Array.isArray(source.columns)
        ? source.columns
            .map((col, index) => {
                if (!col || typeof col !== 'object') {
                    return null;
                }
                const entry = /** @type {Record<string, unknown>} */ (col);
                const id = String(entry.id || `col_${index + 1}`).trim();
                const label = String(entry.label || id).trim();
                if (!id) {
                    return null;
                }
                return { id, label };
            })
            .filter(Boolean)
        : [];
    if (!columns.length) {
        return null;
    }
    const colIds = new Set(columns.map((c) => c.id));
    const rows = Array.isArray(source.rows)
        ? source.rows
            .map((row, index) => {
                if (!row || typeof row !== 'object') {
                    return null;
                }
                const entry = /** @type {Record<string, unknown>} */ (row);
                const id = String(entry.id || `row_${index + 1}`).trim() || createLocalId('row_');
                /** @type {Record<string, string>} */
                const cells = {};
                const rawCells = entry.cells && typeof entry.cells === 'object'
                    ? /** @type {Record<string, unknown>} */ (entry.cells)
                    : {};
                for (const colId of colIds) {
                    cells[colId] = String(rawCells[colId] ?? '');
                }
                return { id, cells };
            })
            .filter(Boolean)
        : [];
    return { columns: /** @type {SpTableColumn[]} */ (columns), rows: /** @type {SpTableRow[]} */ (rows) };
}

/**
 * Merge template table with override.
 * Template columns win unless override explicitly defines columns; rows come from override when present.
 * @param {unknown} base
 * @param {unknown} override
 * @returns {SpItemTable|null}
 */
export function mergeItemTable(base, override) {
    const baseNorm = normalizeItemTable(base);
    if (override === undefined || override === null) {
        return baseNorm;
    }
    if (!override || typeof override !== 'object') {
        return baseNorm;
    }
    const source = /** @type {Record<string, unknown>} */ (override);
    const overrideHasColumns = Array.isArray(source.columns) && source.columns.length > 0;
    /** @type {SpTableColumn[]} */
    let columns = [];
    if (overrideHasColumns) {
        const overNorm = normalizeItemTable({ columns: source.columns, rows: [] });
        columns = overNorm?.columns || baseNorm?.columns || [];
    } else {
        columns = baseNorm?.columns || [];
    }
    if (!columns.length) {
        return normalizeItemTable(override);
    }
    const colIds = new Set(columns.map((c) => c.id));
    const rawRows = Array.isArray(source.rows)
        ? source.rows
        : (baseNorm?.rows || []);
    const rows = rawRows
        .map((row, index) => {
            if (!row || typeof row !== 'object') {
                return null;
            }
            const entry = /** @type {Record<string, unknown>} */ (row);
            const id = String(entry.id || `row_${index + 1}`).trim() || createLocalId('row_');
            /** @type {Record<string, string>} */
            const cells = {};
            const rawCells = entry.cells && typeof entry.cells === 'object'
                ? /** @type {Record<string, unknown>} */ (entry.cells)
                : {};
            for (const colId of colIds) {
                cells[colId] = String(rawCells[colId] ?? '');
            }
            return { id, cells };
        })
        .filter(Boolean);
    return {
        columns,
        rows: /** @type {SpTableRow[]} */ (rows),
    };
}

/**
 * Parse column labels from a comma/newline separated string (for dialog / creator).
 * @param {string} raw
 * @returns {SpTableColumn[]}
 */
export function parseTableColumnsText(raw) {
    const labels = String(raw || '')
        .split(/[\n,]+/)
        .map((s) => s.trim())
        .filter(Boolean);
    return labels.map((label, index) => ({
        id: slugifyColumn(label, index),
        label,
    }));
}

/**
 * @param {SpTableColumn[]} columns
 */
export function formatTableColumnsText(columns = []) {
    return (columns || []).map((c) => c.label || c.id).join(', ');
}

/**
 * Read editable table DOM into SpItemTable.
 * @param {HTMLElement|null} host
 * @returns {SpItemTable|null}
 */
export function readTableEditor(host) {
    if (!host) {
        return null;
    }
    const columnsRaw = host.querySelector('[data-sp-table-columns]')?.value || '';
    const columns = parseTableColumnsText(columnsRaw);
    if (!columns.length) {
        return null;
    }
    /** @type {SpTableRow[]} */
    const rows = [];
    for (const tr of host.querySelectorAll('[data-sp-table-row]')) {
        const id = tr.getAttribute('data-row-id') || createLocalId('row_');
        /** @type {Record<string, string>} */
        const cells = {};
        for (const col of columns) {
            const input = tr.querySelector(`[data-col-id="${CSS.escape(col.id)}"]`);
            cells[col.id] = input instanceof HTMLInputElement ? input.value : '';
        }
        rows.push({ id, cells });
    }
    return { columns, rows };
}

/**
 * Render editable table into host.
 * @param {HTMLElement} host
 * @param {SpItemTable|null} table
 * @param {{spT: (key: string, vars?: object) => string, columnsOnly?: boolean, columnsLocked?: boolean}} i18n
 */
export function renderTableEditor(host, table, i18n) {
    const { spT } = i18n;
    const columnsOnly = Boolean(i18n.columnsOnly);
    const columnsLocked = Boolean(i18n.columnsLocked);
    host.innerHTML = '';
    host.hidden = false;

    if (columnsLocked && !columnsOnly) {
        const note = document.createElement('p');
        note.className = 'sp-password-note';
        note.textContent = spT('sp.field.tableInlineHint');
        host.appendChild(note);
        return;
    }

    const columnsLabel = document.createElement('label');
    columnsLabel.className = 'sp-field';
    const columnsSpan = document.createElement('span');
    columnsSpan.textContent = spT('sp.field.tableColumns');
    const columnsInput = document.createElement('input');
    columnsInput.type = 'text';
    columnsInput.className = 'tools-input';
    columnsInput.id = `sp-table-columns-${Math.random().toString(36).slice(2, 9)}`;
    columnsInput.name = columnsInput.id;
    columnsInput.setAttribute('data-sp-table-columns', '1');
    columnsInput.value = formatTableColumnsText(table?.columns || []);
    columnsInput.placeholder = spT('sp.field.tableColumnsHint');
    columnsInput.readOnly = columnsLocked;
    columnsInput.disabled = columnsLocked;
    columnsLabel.htmlFor = columnsInput.id;
    columnsLabel.append(columnsSpan, columnsInput);
    host.appendChild(columnsLabel);

    if (columnsOnly) {
        const hint = document.createElement('p');
        hint.className = 'sp-password-note';
        hint.textContent = spT('sp.field.tableColumnsOnlyHint');
        host.appendChild(hint);
        return;
    }

    const tableWrap = document.createElement('div');
    tableWrap.className = 'sp-item-table-wrap';
    tableWrap.setAttribute('data-sp-table-rows', '1');
    host.appendChild(tableWrap);

    const rebuildRows = () => {
        const cols = parseTableColumnsText(columnsInput.value);
        const previous = readRowsFromDom(tableWrap, cols);
        renderRows(tableWrap, cols, previous.length ? previous : (table?.rows || []), spT);
    };

    columnsInput.addEventListener('input', rebuildRows);
    columnsInput.addEventListener('change', rebuildRows);
    columnsInput.addEventListener('blur', rebuildRows);

    const actions = document.createElement('div');
    actions.className = 'sp-action-row';
    const addRow = document.createElement('button');
    addRow.type = 'button';
    addRow.className = 'tools-btn tools-btn--secondary tools-btn--small';
    addRow.textContent = spT('sp.action.addTableRow');
    addRow.addEventListener('click', () => {
        const cols = parseTableColumnsText(columnsInput.value);
        if (!cols.length) {
            return;
        }
        const rows = readRowsFromDom(tableWrap, cols);
        rows.push({
            id: createLocalId('row_'),
            cells: Object.fromEntries(cols.map((c) => [c.id, ''])),
        });
        renderRows(tableWrap, cols, rows, spT);
    });
    actions.appendChild(addRow);
    host.appendChild(actions);

    rebuildRows();
}

/**
 * Inline plan-view table editor (rows). Persists via onChange.
 * @param {HTMLElement} host
 * @param {SpItemTable} table
 * @param {{spT: (key: string, vars?: object) => string, onChange: (table: SpItemTable) => void}} opts
 */
export function renderInlineTableEditor(host, table, opts) {
    const { spT, onChange } = opts;
    host.innerHTML = '';
    const columns = table.columns || [];
    if (!columns.length) {
        return;
    }

    const wrap = document.createElement('div');
    wrap.className = 'sp-item-table-wrap';
    host.appendChild(wrap);

    const persist = () => {
        const rows = readRowsFromDom(wrap, columns);
        onChange({ columns, rows });
    };

    const draw = (rows) => {
        renderRows(wrap, columns, rows, spT, { onDelete: persist });
        wrap.querySelectorAll('input[data-col-id]').forEach((input) => {
            input.addEventListener('change', persist);
            input.addEventListener('blur', persist);
        });
    };

    const actions = document.createElement('div');
    actions.className = 'sp-action-row sp-item-table__toolbar';
    const addRow = document.createElement('button');
    addRow.type = 'button';
    addRow.className = 'tools-btn tools-btn--secondary tools-btn--small';
    addRow.textContent = spT('sp.action.addTableRow');
    addRow.addEventListener('click', () => {
        const rows = readRowsFromDom(wrap, columns);
        rows.push({
            id: createLocalId('row_'),
            cells: Object.fromEntries(columns.map((c) => [c.id, ''])),
        });
        draw(rows);
        persist();
    });

    const exportBtn = document.createElement('button');
    exportBtn.type = 'button';
    exportBtn.className = 'tools-btn tools-btn--secondary tools-btn--small';
    exportBtn.textContent = spT('sp.action.exportTableCsv');
    exportBtn.addEventListener('click', () => {
        const rows = readRowsFromDom(wrap, columns);
        downloadCsvFile('plan-table.csv', itemTableToCsv({ columns, rows }));
    });

    const importInput = document.createElement('input');
    importInput.type = 'file';
    importInput.accept = '.csv,text/csv';
    importInput.hidden = true;
    importInput.addEventListener('change', async () => {
        const file = importInput.files?.[0];
        importInput.value = '';
        if (!file) {
            return;
        }
        const existing = readRowsFromDom(wrap, columns);
        if (existing.length && !window.confirm(spT('sp.table.csvImportReplaceConfirm'))) {
            return;
        }
        try {
            const text = await file.text();
            const result = csvToItemTableRows(text, columns);
            if (!result.ok) {
                window.alert(spT(`sp.table.csvError.${result.error}`));
                return;
            }
            draw(result.rows);
            persist();
        } catch {
            window.alert(spT('sp.table.csvError.read'));
        }
    });

    const importBtn = document.createElement('button');
    importBtn.type = 'button';
    importBtn.className = 'tools-btn tools-btn--secondary tools-btn--small';
    importBtn.textContent = spT('sp.action.importTableCsv');
    importBtn.addEventListener('click', () => importInput.click());

    actions.append(addRow, exportBtn, importBtn, importInput);
    host.appendChild(actions);

    draw(table.rows || []);
}

/**
 * @param {HTMLElement} wrap
 * @param {SpTableColumn[]} columns
 * @param {SpTableRow[]} rows
 * @param {(key: string) => string} spT
 * @param {{ onDelete?: () => void }} [opts]
 */
function renderRows(wrap, columns, rows, spT, opts = {}) {
    wrap.innerHTML = '';
    if (!columns.length) {
        return;
    }
    const table = document.createElement('table');
    table.className = 'sp-item-table sp-item-table--edit';
    const thead = document.createElement('thead');
    const headRow = document.createElement('tr');
    for (const col of columns) {
        const th = document.createElement('th');
        th.textContent = col.label;
        headRow.appendChild(th);
    }
    const thAction = document.createElement('th');
    thAction.className = 'sp-item-table__actions';
    thAction.textContent = spT('sp.field.tableActions');
    headRow.appendChild(thAction);
    thead.appendChild(headRow);
    table.appendChild(thead);

    const tbody = document.createElement('tbody');
    for (const row of rows) {
        const tr = document.createElement('tr');
        tr.setAttribute('data-sp-table-row', '1');
        tr.setAttribute('data-row-id', row.id);
        for (const col of columns) {
            const td = document.createElement('td');
            const input = document.createElement('input');
            input.type = 'text';
            input.className = 'tools-input tools-input--table';
            input.name = `sp-table-${row.id}-${col.id}`;
            input.id = `sp-table-${row.id}-${col.id}`;
            input.setAttribute('aria-label', col.label || col.id);
            input.setAttribute('data-col-id', col.id);
            input.value = row.cells?.[col.id] || '';
            td.appendChild(input);
            tr.appendChild(td);
        }
        const tdAction = document.createElement('td');
        tdAction.className = 'sp-item-table__actions';
        const remove = document.createElement('button');
        remove.type = 'button';
        remove.className = 'tools-btn tools-btn--secondary tools-btn--small';
        remove.textContent = spT('sp.action.delete');
        remove.addEventListener('click', () => {
            tr.remove();
            opts.onDelete?.();
        });
        tdAction.appendChild(remove);
        tr.appendChild(tdAction);
        tbody.appendChild(tr);
    }
    table.appendChild(tbody);
    wrap.appendChild(table);
}

/**
 * @param {HTMLElement} wrap
 * @param {SpTableColumn[]} columns
 * @returns {SpTableRow[]}
 */
function readRowsFromDom(wrap, columns) {
    /** @type {SpTableRow[]} */
    const rows = [];
    for (const tr of wrap.querySelectorAll('[data-sp-table-row]')) {
        const id = tr.getAttribute('data-row-id') || createLocalId('row_');
        /** @type {Record<string, string>} */
        const cells = {};
        for (const col of columns) {
            const input = tr.querySelector(`[data-col-id="${CSS.escape(col.id)}"]`);
            cells[col.id] = input instanceof HTMLInputElement ? input.value : '';
        }
        rows.push({ id, cells });
    }
    return rows;
}

/**
 * Read-only table for plan view / reports.
 * @param {SpItemTable|null|undefined} table
 * @returns {HTMLElement|null}
 */
export function renderItemTableReadonly(table) {
    const normalized = normalizeItemTable(table);
    if (!normalized?.columns?.length) {
        return null;
    }
    const wrap = document.createElement('div');
    wrap.className = 'sp-item-table-wrap';
    const el = document.createElement('table');
    el.className = 'sp-item-table';
    const thead = document.createElement('thead');
    const headRow = document.createElement('tr');
    for (const col of normalized.columns) {
        const th = document.createElement('th');
        th.textContent = col.label;
        headRow.appendChild(th);
    }
    thead.appendChild(headRow);
    el.appendChild(thead);
    const tbody = document.createElement('tbody');
    for (const row of normalized.rows) {
        const tr = document.createElement('tr');
        for (const col of normalized.columns) {
            const td = document.createElement('td');
            td.textContent = row.cells?.[col.id] || '';
            tr.appendChild(td);
        }
        tbody.appendChild(tr);
    }
    if (!normalized.rows.length) {
        const tr = document.createElement('tr');
        const td = document.createElement('td');
        td.colSpan = normalized.columns.length;
        td.className = 'sp-item-table__empty';
        td.textContent = '—';
        tr.appendChild(td);
        tbody.appendChild(tr);
    }
    el.appendChild(tbody);
    wrap.appendChild(el);
    return wrap;
}

/**
 * @param {string} label
 * @param {number} index
 */
function slugifyColumn(label, index) {
    const base = String(label)
        .toLowerCase()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-|-$/g, '')
        .slice(0, 40);
    return base || `col_${index + 1}`;
}
