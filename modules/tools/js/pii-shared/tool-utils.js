import { formatSyncSourceLabel } from './pii-meta.js';
import { warehouseDialectPreview } from './warehouse-templates.js';

/**
 * @param {HTMLElement | null} el
 * @param {import('./schema-storage.js').SchemaMeta | null | undefined} meta
 * @param {(key: string) => string} t
 */
export function updateSyncStatusEl(el, meta, t) {
    if (!el) return;
    if (!meta?.savedAt) {
        el.textContent = '';
        return;
    }
    const when = new Date(meta.savedAt);
    const time = Number.isNaN(when.getTime()) ? meta.savedAt : when.toLocaleString();
    const source = formatSyncSourceLabel(meta.source);
    el.textContent = t('shared.syncStatus').replace('{source}', source).replace('{time}', time);
}

/** @param {string} text */
export async function copyTextToClipboard(text) {
    if (navigator.clipboard?.writeText) {
        await navigator.clipboard.writeText(text);
        return;
    }
    const area = document.createElement('textarea');
    area.value = text;
    area.style.position = 'fixed';
    area.style.left = '-9999px';
    document.body.appendChild(area);
    area.select();
    document.execCommand('copy');
    document.body.removeChild(area);
}

/**
 * @param {HTMLButtonElement | null} btn
 * @param {string} text
 * @param {(key: string) => string} t
 */
export async function copyFromButton(btn, text, t) {
    if (!btn) return;
    try {
        await copyTextToClipboard(text);
        const original = btn.textContent;
        btn.textContent = t('shared.copied');
        window.setTimeout(() => {
            btn.textContent = original;
        }, 1500);
    } catch {
        window.prompt('Copy:', text);
    }
}

/** @param {HTMLSelectElement | null} selectEl @returns {import('./warehouse-templates.js').WarehouseId | undefined} */
export function readWarehouseFromSelect(selectEl) {
    if (!selectEl) return undefined;
    return /** @type {import('./warehouse-templates.js').WarehouseId} */ (selectEl.value);
}

/** @param {import('./demo-model.js').DbtModelState} state @param {HTMLSelectElement | null} selectEl */
export function writeWarehouseToForm(state, selectEl) {
    if (!selectEl) return;
    selectEl.value = state.selectedWarehouse ?? 'snowflake';
}

/**
 * @param {HTMLElement | null} previewEl
 * @param {string | undefined} warehouseId
 * @param {'mask' | 'regex'} [kind='mask']
 */
export function updateWarehousePreview(previewEl, warehouseId, kind = 'mask') {
    if (!previewEl) return;
    previewEl.textContent = warehouseDialectPreview(warehouseId ?? 'snowflake', kind);
}

/** @param {string} value @returns {string[]} */
export function splitCsv(value) {
    return value
        .split(',')
        .map((item) => item.trim())
        .filter(Boolean);
}
