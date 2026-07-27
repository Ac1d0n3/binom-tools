import { copyTextToClipboard } from '../pii-shared/tool-utils.js';
import { downloadTextFile } from './download.js';
import { checklistToMarkdown } from './export.js';
import { bindLeaveGuard } from './leave-guard.js';
import { bindPlanTransferUi } from './plan-transfer-ui.js';
import { purgeLegacyDraftKeys } from './storage.js';

/**
 * @typedef {{ id: string, labelKey: string, helpKey?: string }} CheckItemDef
 * @typedef {{ id: string, titleKey: string, items: CheckItemDef[] }} CheckSectionDef
 */

/**
 * Ephemeral checklist — in-memory only. Copy Markdown into the Sprint Plan.
 *
 * @param {object} options
 * @param {HTMLElement} options.root
 * @param {CheckSectionDef[]} options.sections
 * @param {(key: string) => string} options.t
 * @param {() => void} options.applyLabels
 * @param {string} [options.exportTitle]
 * @param {string[]} [options.legacyStorageKeys]
 */
export function mountChecklistCanvas(options) {
    const {
        root,
        sections,
        t,
        applyLabels,
        exportTitle = '',
        legacyStorageKeys = [],
    } = options;

    purgeLegacyDraftKeys(legacyStorageKeys);

    /** @type {{ items: Record<string, { checked: boolean, note: string }> }} */
    let state = { items: {} };
    let transferred = false;

    const listHost = root.querySelector('[data-discovery-checklist]');
    const preview = /** @type {HTMLElement|null} */ (root.querySelector('[data-export-preview]'));
    const btnMd = /** @type {HTMLButtonElement|null} */ (root.querySelector('[data-copy-md]'));
    const btnDlMd = /** @type {HTMLButtonElement|null} */ (root.querySelector('[data-download-md]'));
    const btnClear = /** @type {HTMLButtonElement|null} */ (root.querySelector('[data-clear]'));

    function hasContent() {
        return Object.values(state.items).some(
            (item) => item.checked || Boolean(item.note?.trim()),
        );
    }

    function ensureItem(id) {
        if (!state.items[id]) {
            state.items[id] = { checked: false, note: '' };
        }
        return state.items[id];
    }

    function allDefs() {
        return sections.flatMap((section) => section.items);
    }

    function markdownExport() {
        const title = exportTitle || t('discovery.exportTitle');
        const blocks = [`# ${title}`, ''];
        for (const section of sections) {
            blocks.push(`## ${t(section.titleKey)}`, '');
            const items = section.items.map((item) => {
                const data = ensureItem(item.id);
                return {
                    checked: data.checked,
                    label: t(item.labelKey),
                    note: data.note,
                };
            });
            blocks.push(checklistToMarkdown(items), '');
        }
        const open = allDefs().filter((item) => !ensureItem(item.id).checked);
        if (open.length) {
            blocks.push(`## ${t('architectureFit.bottlenecks')}`, '');
            for (const item of open) {
                const note = ensureItem(item.id).note?.trim();
                blocks.push(`- ${t(item.labelKey)}${note ? ` — ${note}` : ''}`);
            }
            blocks.push('');
        }
        return blocks.join('\n');
    }

    function updatePreview() {
        if (preview) {
            preview.textContent = markdownExport();
        }
    }

    function syncUi() {
        transferred = false;
        updatePreview();
    }

    bindLeaveGuard(
        () => hasContent() && !transferred,
        () => t('discovery.leaveConfirm'),
    );

    function render() {
        if (!listHost) return;
        listHost.innerHTML = '';

        for (const section of sections) {
            const panel = document.createElement('section');
            panel.className = 'tools-panel discovery-check-section';

            const heading = document.createElement('h2');
            heading.className = 'discovery-check-section__title';
            heading.dataset.i18n = section.titleKey;
            heading.textContent = t(section.titleKey);
            panel.appendChild(heading);

            const list = document.createElement('ul');
            list.className = 'discovery-check-list';

            for (const item of section.items) {
                const data = ensureItem(item.id);
                const li = document.createElement('li');
                li.className = 'discovery-check-item';

                const row = document.createElement('label');
                row.className = 'discovery-check-item__row';

                const check = document.createElement('input');
                check.type = 'checkbox';
                check.className = 'discovery-check';
                check.checked = data.checked;
                check.addEventListener('change', () => {
                    data.checked = check.checked;
                    syncUi();
                });

                const label = document.createElement('span');
                label.dataset.i18n = item.labelKey;
                label.textContent = t(item.labelKey);

                row.appendChild(check);
                row.appendChild(label);
                li.appendChild(row);

                if (item.helpKey) {
                    const help = document.createElement('p');
                    help.className = 'discovery-check-item__help';
                    help.dataset.i18n = item.helpKey;
                    help.textContent = t(item.helpKey);
                    li.appendChild(help);
                }

                const note = document.createElement('textarea');
                note.className = 'tools-input discovery-input discovery-input--area';
                note.rows = 2;
                note.placeholder = t('architectureFit.notePlaceholder');
                note.dataset.i18nPlaceholder = 'architectureFit.notePlaceholder';
                note.value = data.note || '';
                note.addEventListener('input', () => {
                    data.note = note.value;
                    syncUi();
                });
                li.appendChild(note);

                list.appendChild(li);
            }

            panel.appendChild(list);
            listHost.appendChild(panel);
        }

        updatePreview();
    }

    btnMd?.addEventListener('click', async () => {
        try {
            await copyTextToClipboard(markdownExport());
            transferred = true;
            const original = btnMd.textContent;
            btnMd.textContent = t('discovery.copied');
            window.setTimeout(() => {
                btnMd.textContent = original;
            }, 1800);
        } catch {
            window.prompt('Copy:', markdownExport());
        }
    });

    btnDlMd?.addEventListener('click', () => {
        const name = `${(t('discovery.exportTitle') || 'export').replace(/[^\w.\-]+/g, '-')}.md`;
        downloadTextFile(name, markdownExport());
        transferred = true;
        const original = btnDlMd.textContent;
        btnDlMd.textContent = t('discovery.downloaded');
        window.setTimeout(() => {
            btnDlMd.textContent = original;
        }, 1800);
    });

    btnClear?.addEventListener('click', () => {
        if (!window.confirm(t('discovery.clearConfirm'))) return;
        state.items = {};
        syncUi();
        render();
    });

    bindPlanTransferUi({
        root,
        t,
        hasContent,
        markTransferred: () => {
            transferred = true;
        },
        getPayload: () => ({
            markdown: markdownExport(),
            columns: [],
            rows: [],
        }),
    });

    function refresh() {
        applyLabels();
        root.querySelectorAll('[data-i18n-placeholder]').forEach((el) => {
            const key = el.getAttribute('data-i18n-placeholder');
            if (key && 'placeholder' in el) {
                el.placeholder = t(key);
            }
        });
        render();
    }

    refresh();
    window.addEventListener('binom-tools:locale', refresh);

    return { render, syncUi };
}
