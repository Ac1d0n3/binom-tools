import { copyTextToClipboard } from '../pii-shared/tool-utils.js';
import { downloadTextFile } from './download.js';
import { rowsToCsv, rowsToMarkdownTable } from './export.js';
import { bindLeaveGuard } from './leave-guard.js';
import { bindPlanTransferUi } from './plan-transfer-ui.js';
import { newRowId, purgeLegacyDraftKeys } from './storage.js';

/**
 * @typedef {{
 *   id: string,
 *   labelKey: string,
 *   type?: 'text' | 'select' | 'checkbox' | 'number' | 'textarea',
 *   options?: Array<{ value: string, labelKey: string }>,
 *   min?: number,
 *   max?: number,
 * }} ColumnDef
 */

/**
 * Ephemeral canvas — in-memory only. Copy Markdown/CSV into the Sprint Plan.
 * Plan data lives in the planner (local or accounts), never in these tools.
 *
 * @param {object} options
 * @param {HTMLElement} options.root
 * @param {ColumnDef[]} options.columns
 * @param {(key: string) => string} options.t
 * @param {() => void} options.applyLabels
 * @param {string} [options.exportTitle]
 * @param {string[]} [options.legacyStorageKeys] Keys to remove if an older build stored drafts
 * @param {(rows: Record<string, unknown>[]) => void} [options.onChange]
 * @param {(host: HTMLElement, rows: Record<string, unknown>[], api: { syncUi: () => void, render: () => void }) => void} [options.renderExtra]
 */
export function mountTableCanvas(options) {
    const {
        root,
        columns,
        t,
        applyLabels,
        exportTitle = '',
        legacyStorageKeys = [],
        onChange,
        renderExtra,
    } = options;

    purgeLegacyDraftKeys(legacyStorageKeys);

    /** @type {{ rows: Record<string, unknown>[] }} */
    let state = { rows: [] };
    /** After copy/download, skip leave warning until content changes again. */
    let transferred = false;

    const tableHost = root.querySelector('[data-discovery-table]');
    const preview = /** @type {HTMLElement|null} */ (root.querySelector('[data-export-preview]'));
    const extraHost = /** @type {HTMLElement|null} */ (root.querySelector('[data-discovery-extra]'));
    const btnMd = /** @type {HTMLButtonElement|null} */ (root.querySelector('[data-copy-md]'));
    const btnCsv = /** @type {HTMLButtonElement|null} */ (root.querySelector('[data-copy-csv]'));
    const btnDlMd = /** @type {HTMLButtonElement|null} */ (root.querySelector('[data-download-md]'));
    const btnDlCsv = /** @type {HTMLButtonElement|null} */ (root.querySelector('[data-download-csv]'));
    const btnAdd = /** @type {HTMLButtonElement|null} */ (root.querySelector('[data-add-row]'));
    const btnClear = /** @type {HTMLButtonElement|null} */ (root.querySelector('[data-clear]'));

    function emptyRow() {
        /** @type {Record<string, unknown>} */
        const row = { id: newRowId() };
        for (const col of columns) {
            if (col.type === 'checkbox') {
                row[col.id] = false;
            } else if (col.type === 'number') {
                row[col.id] = col.min ?? 1;
            } else if (col.type === 'select' && col.options?.length) {
                row[col.id] = col.options[0].value;
            } else {
                row[col.id] = '';
            }
        }
        return row;
    }

    function syncUi() {
        transferred = false;
        onChange?.(state.rows);
        updatePreview();
        if (extraHost && renderExtra) {
            renderExtra(extraHost, state.rows, { syncUi, render });
        }
    }

    bindLeaveGuard(
        () => state.rows.length > 0 && !transferred,
        () => t('discovery.leaveConfirm'),
    );

    /**
     * @param {string} filename
     */
    function safeFilename(filename) {
        return filename.replace(/[^\w.\-]+/g, '-').replace(/-+/g, '-');
    }

    function markTransferred() {
        transferred = true;
    }

    function headerLabels() {
        return columns.map((col) => t(col.labelKey));
    }

    /**
     * @param {Record<string, unknown>} row
     * @param {ColumnDef} col
     */
    function displayValue(row, col) {
        const raw = row[col.id];
        if (col.type === 'checkbox') {
            return raw ? t('discovery.yes') : t('discovery.no');
        }
        if (col.type === 'select' && col.options) {
            const match = col.options.find((o) => o.value === raw);
            return match ? t(match.labelKey) : String(raw ?? '');
        }
        return String(raw ?? '');
    }

    function buildExportRows() {
        return state.rows.map((row) => columns.map((col) => displayValue(row, col)));
    }

    function markdownExport() {
        const table = rowsToMarkdownTable(headerLabels(), buildExportRows());
        const title = exportTitle || t('discovery.exportTitle');
        return `# ${title}\n\n${table}\n`;
    }

    function csvExport() {
        return rowsToCsv(headerLabels(), buildExportRows());
    }

    function updatePreview() {
        if (preview) {
            preview.textContent = markdownExport();
        }
    }

    /**
     * @param {ColumnDef} col
     * @param {Record<string, unknown>} row
     */
    function createInput(col, row) {
        const type = col.type || 'text';
        if (type === 'checkbox') {
            const input = document.createElement('input');
            input.type = 'checkbox';
            input.className = 'discovery-check';
            input.checked = Boolean(row[col.id]);
            input.addEventListener('change', () => {
                row[col.id] = input.checked;
                syncUi();
            });
            return input;
        }
        if (type === 'select') {
            const select = document.createElement('select');
            select.className = 'tools-input discovery-input';
            for (const opt of col.options || []) {
                const option = document.createElement('option');
                option.value = opt.value;
                option.textContent = t(opt.labelKey);
                option.dataset.i18n = opt.labelKey;
                select.appendChild(option);
            }
            select.value = String(row[col.id] ?? '');
            select.addEventListener('change', () => {
                row[col.id] = select.value;
                syncUi();
            });
            return select;
        }
        if (type === 'textarea') {
            const area = document.createElement('textarea');
            area.className = 'tools-input discovery-input discovery-input--area';
            area.rows = 2;
            area.value = String(row[col.id] ?? '');
            area.addEventListener('input', () => {
                row[col.id] = area.value;
                syncUi();
            });
            return area;
        }
        const input = document.createElement('input');
        input.className = 'tools-input discovery-input';
        input.type = type === 'number' ? 'number' : 'text';
        if (type === 'number') {
            if (col.min != null) input.min = String(col.min);
            if (col.max != null) input.max = String(col.max);
        }
        input.value = String(row[col.id] ?? '');
        input.addEventListener('input', () => {
            row[col.id] = type === 'number' ? Number(input.value) : input.value;
            syncUi();
        });
        return input;
    }

    function render() {
        if (!tableHost) return;
        tableHost.innerHTML = '';

        const wrap = document.createElement('div');
        wrap.className = 'discovery-table-wrap';

        const table = document.createElement('table');
        table.className = 'discovery-table';
        table.setAttribute('role', 'grid');

        const thead = document.createElement('thead');
        const headRow = document.createElement('tr');
        for (const col of columns) {
            const th = document.createElement('th');
            th.dataset.i18n = col.labelKey;
            th.textContent = t(col.labelKey);
            headRow.appendChild(th);
        }
        const thActions = document.createElement('th');
        thActions.className = 'discovery-table__actions';
        thActions.dataset.i18n = 'discovery.actions';
        thActions.textContent = t('discovery.actions');
        headRow.appendChild(thActions);
        thead.appendChild(headRow);
        table.appendChild(thead);

        const tbody = document.createElement('tbody');
        if (state.rows.length === 0) {
            const empty = document.createElement('tr');
            const td = document.createElement('td');
            td.colSpan = columns.length + 1;
            td.className = 'discovery-table__empty';
            td.dataset.i18n = 'discovery.empty';
            td.textContent = t('discovery.empty');
            empty.appendChild(td);
            tbody.appendChild(empty);
        } else {
            for (const row of state.rows) {
                const tr = document.createElement('tr');
                for (const col of columns) {
                    const td = document.createElement('td');
                    td.appendChild(createInput(col, row));
                    tr.appendChild(td);
                }
                const tdAct = document.createElement('td');
                tdAct.className = 'discovery-table__actions';
                const remove = document.createElement('button');
                remove.type = 'button';
                remove.className = 'tools-btn tools-btn--ghost discovery-btn-remove';
                remove.dataset.i18n = 'discovery.remove';
                remove.textContent = t('discovery.remove');
                remove.addEventListener('click', () => {
                    state.rows = state.rows.filter((r) => r.id !== row.id);
                    syncUi();
                    render();
                });
                tdAct.appendChild(remove);
                tr.appendChild(tdAct);
                tbody.appendChild(tr);
            }
        }
        table.appendChild(tbody);
        wrap.appendChild(table);
        tableHost.appendChild(wrap);

        updatePreview();
        if (extraHost && renderExtra) {
            renderExtra(extraHost, state.rows, { syncUi, render });
        }
    }

    /**
     * @param {HTMLButtonElement|null} btn
     * @param {() => string} getText
     * @param {string} [doneKey]
     */
    function wireCopy(btn, getText, doneKey = 'discovery.copied') {
        btn?.addEventListener('click', async () => {
            try {
                await copyTextToClipboard(getText());
                markTransferred();
                const original = btn.textContent;
                btn.textContent = t(doneKey);
                window.setTimeout(() => {
                    btn.textContent = original;
                }, 1800);
            } catch {
                window.prompt('Copy:', getText());
            }
        });
    }

    /**
     * @param {HTMLButtonElement|null} btn
     * @param {() => string} getText
     * @param {string} filename
     * @param {string} [mime]
     */
    function wireDownload(btn, getText, filename, mime) {
        btn?.addEventListener('click', () => {
            downloadTextFile(safeFilename(filename), getText(), mime);
            markTransferred();
            const original = btn.textContent;
            btn.textContent = t('discovery.downloaded');
            window.setTimeout(() => {
                btn.textContent = original;
            }, 1800);
        });
    }

    const baseName = safeFilename(t('discovery.exportTitle') || 'export');
    wireCopy(btnMd, markdownExport);
    wireCopy(btnCsv, csvExport);
    wireDownload(btnDlMd, markdownExport, `${baseName}.md`);
    wireDownload(btnDlCsv, csvExport, `${baseName}.csv`, 'text/csv;charset=utf-8');

    bindPlanTransferUi({
        root,
        t,
        hasContent: () => state.rows.length > 0,
        markTransferred,
        getPayload: () => ({
            markdown: markdownExport(),
            columns: columns.map((col) => ({ id: col.id, label: t(col.labelKey) })),
            rows: state.rows.map((row) => ({
                id: String(row.id),
                cells: Object.fromEntries(
                    columns.map((col) => [col.id, displayValue(row, col)]),
                ),
            })),
        }),
    });

    btnAdd?.addEventListener('click', () => {
        state.rows.push(emptyRow());
        syncUi();
        render();
    });

    btnClear?.addEventListener('click', () => {
        if (!window.confirm(t('discovery.clearConfirm'))) return;
        state.rows = [];
        syncUi();
        render();
    });

    function refresh() {
        applyLabels();
        render();
    }

    refresh();
    window.addEventListener('binom-tools:locale', refresh);

    return {
        getRows: () => state.rows,
        render,
        syncUi,
    };
}
