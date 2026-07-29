import '../../css/discovery-canvas.css';
import './gspd.css';
import { createLabelApi, mergeDiscoveryLabels } from '../discovery-shared/labels.js';
import { bindLeaveGuard } from '../discovery-shared/leave-guard.js';
import { bindPlanTransferUi } from '../discovery-shared/plan-transfer-ui.js';
import { copyTextToClipboard } from '../pii-shared/tool-utils.js';
import {
    readCustomStack,
    summarizeSelection,
    derivePlatformTags,
    writeStartingPointProduct,
} from '../../../governance/js/stack-builder.js';
import { buildToolLabels } from './labels.js';
import { buildMarkdown } from './markdown.js';
import {
    AREA_STATUS,
    DECISION_STATUS,
    DRAFT_STORAGE_KEY,
    GOVERNANCE_AREAS,
    LIST_KEYS,
    MULTIPLE_ROW_FIELDS,
    PRODUCT_FIELDS,
    PRODUCT_IDS,
    createEmptyState,
    emptyMultipleRow,
    normalizeState,
    productFromTags,
} from './model.js';

const labels = mergeDiscoveryLabels(buildToolLabels());
const { t, applyLabels } = createLabelApi(labels);

const app = document.getElementById('gspd-app');
if (!app) {
    throw new Error('Governance starting-point decision root not found');
}

/** @type {ReturnType<typeof createEmptyState>} */
let state = createEmptyState();
let baselineJson = '';
let transferred = false;

/** @type {Set<string>} */
const openFolds = new Set(['context']);

const formHost = /** @type {HTMLElement} */ (app.querySelector('[data-gspd-form]'));
if (formHost instanceof HTMLFormElement) {
    formHost.addEventListener('submit', (event) => {
        event.preventDefault();
    });
}
const preview = /** @type {HTMLElement|null} */ (app.querySelector('[data-export-preview]'));
const statusEl = /** @type {HTMLElement|null} */ (app.querySelector('[data-gspd-status]'));
const stackHint = /** @type {HTMLElement|null} */ (app.querySelector('[data-stack-hint]'));

/**
 * @param {string} message
 */
function setStatus(message) {
    if (statusEl) {
        statusEl.textContent = message;
    }
}

/**
 * @param {string} id
 */
function productLabel(id) {
    return t(`gspd.product.${id}`);
}

function snapshotState() {
    return JSON.stringify(state);
}

function markClean() {
    baselineJson = snapshotState();
    transferred = true;
}

function isDirty() {
    return snapshotState() !== baselineJson;
}

function readProductFromUrl() {
    const params = new URLSearchParams(window.location.search);
    const raw = String(params.get('product') || '').trim().toLowerCase();
    if (PRODUCT_IDS.includes(/** @type {any} */ (raw))) {
        return /** @type {typeof PRODUCT_IDS[number]} */ (raw);
    }
    return '';
}

/**
 * @returns {{ used: boolean, summary: string }}
 */
function applyStackPrefill() {
    const selection = readCustomStack();
    const hasStack = Object.values(selection).some((arr) => Array.isArray(arr) && arr.length > 0);
    if (!hasStack) {
        return { used: false, summary: '' };
    }
    const tags = derivePlatformTags(selection).filter((tag) => tag !== 'custom');
    const product = productFromTags(tags);
    const summary = summarizeSelection(selection);
    if (product && !state.product) {
        state.product = product;
    }
    if (!String(state.context.existingContext || '').trim()) {
        state.context.existingContext = summary;
    }
    return { used: true, summary };
}

/**
 * @param {string} tag
 * @param {Record<string, string>} attrs
 * @param {string} [text]
 */
function el(tag, attrs = {}, text = '') {
    const node = document.createElement(tag);
    for (const [key, value] of Object.entries(attrs)) {
        if (key === 'className') {
            node.className = value;
        } else if (value !== undefined && value !== null) {
            node.setAttribute(key, value);
        }
    }
    if (text) {
        node.textContent = text;
    }
    return node;
}

/**
 * @param {string} foldId
 * @param {string} title
 * @param {string} [extraClass]
 * @returns {{ details: HTMLDetailsElement, body: HTMLElement }}
 */
function createFold(foldId, title, extraClass = '') {
    const details = /** @type {HTMLDetailsElement} */ (
        el('details', {
            className: `gspd-fold tools-panel gspd-section${extraClass ? ` ${extraClass}` : ''}`,
        })
    );
    details.open = openFolds.has(foldId);
    const summary = el('summary', { className: 'gspd-fold__summary' }, title);
    details.appendChild(summary);
    const body = el('div', { className: 'gspd-fold__body' });
    details.appendChild(body);
    details.addEventListener('toggle', () => {
        if (details.open) {
            openFolds.add(foldId);
        } else {
            openFolds.delete(foldId);
        }
    });
    return { details, body };
}

/**
 * @param {HTMLElement} parent
 * @param {string} fieldId
 * @param {string} labelText
 * @param {HTMLElement} control
 */
function appendField(parent, fieldId, labelText, control) {
    const wrap = el('div', { className: 'tools-field gspd-field' });
    const label = el('label', {
        className: 'tools-field__label',
        for: fieldId,
    }, labelText);
    control.id = fieldId;
    control.setAttribute('name', fieldId);
    if (!control.getAttribute('autocomplete')) {
        control.setAttribute('autocomplete', 'off');
    }
    wrap.appendChild(label);
    wrap.appendChild(control);
    parent.appendChild(wrap);
    return wrap;
}

/**
 * @param {string} value
 * @param {(next: string) => void} onInput
 * @param {{ multiline?: boolean, type?: string }} [opts]
 */
function textControl(value, onInput, opts = {}) {
    if (opts.multiline) {
        const area = /** @type {HTMLTextAreaElement} */ (
            el('textarea', { className: 'tools-input', rows: '3' })
        );
        area.value = value;
        area.addEventListener('input', () => {
            onInput(area.value);
            scheduleRenderPreview();
        });
        return area;
    }
    const input = /** @type {HTMLInputElement} */ (
        el('input', { className: 'tools-input', type: opts.type || 'text' })
    );
    input.value = value;
    input.addEventListener('input', () => {
        onInput(input.value);
        scheduleRenderPreview();
    });
    return input;
}

/**
 * @param {string} value
 * @param {string[]} options
 * @param {(id: string) => string} labelFn
 * @param {(next: string) => void} onChange
 * @param {{ placeholder?: string }} [opts]
 */
function selectControl(value, options, labelFn, onChange, opts = {}) {
    const select = /** @type {HTMLSelectElement} */ (el('select', { className: 'tools-input' }));
    if (opts.placeholder) {
        const empty = el('option', { value: '' }, opts.placeholder);
        select.appendChild(empty);
    }
    for (const id of options) {
        const opt = el('option', { value: id }, labelFn(id));
        if (id === value) {
            opt.setAttribute('selected', 'selected');
        }
        select.appendChild(opt);
    }
    select.value = value;
    select.addEventListener('change', () => {
        onChange(select.value);
        if (select.dataset.gspdProduct === '1') {
            renderForm();
        } else {
            scheduleRenderPreview();
        }
    });
    return select;
}

function renderForm() {
    formHost.replaceChildren();

    const productSection = el('section', { className: 'tools-panel gspd-section', 'aria-labelledby': 'gspd-product-title' });
    productSection.appendChild(el('h2', { id: 'gspd-product-title', className: 'discovery-check-section__title' }, t('gspd.product')));
    const productSelect = selectControl(
        state.product,
        [...PRODUCT_IDS],
        (id) => t(`gspd.product.${id}`),
        (next) => {
            state.product = /** @type {any} */ (next);
            writeStartingPointProduct(state.product);
        },
        { placeholder: t('gspd.product.placeholder') },
    );
    productSelect.dataset.gspdProduct = '1';
    appendField(productSection, 'gspd-product', t('gspd.product'), productSelect);
    formHost.appendChild(productSection);

    const contextFold = createFold('context', t('gspd.section.context'));
    const contextFields = [
        ['title', false],
        ['firstUseCase', true],
        ['existingContext', true],
        ['decisionGoal', true],
        ['decisionOwner', false],
        ['dataOwner', false],
        ['dataSteward', false],
        ['technicalOwner', false],
        ['reviewDate', false],
    ];
    for (const [key, multiline] of contextFields) {
        appendField(
            contextFold.body,
            `gspd-context-${key}`,
            t(`gspd.context.${key}`),
            textControl(state.context[key], (v) => {
                state.context[key] = v;
            }, { multiline, type: key === 'reviewDate' ? 'date' : 'text' }),
        );
    }
    formHost.appendChild(contextFold.details);

    const designFold = createFold('design', t('gspd.section.design'));
    for (const areaId of GOVERNANCE_AREAS) {
        const area = state.areas[areaId];
        const areaFold = createFold(`area:${areaId}`, t(`gspd.area.${areaId}`), 'gspd-fold--nested');
        appendField(
            areaFold.body,
            `gspd-area-${areaId}-status`,
            t('gspd.area.status'),
            selectControl(area.status, AREA_STATUS, (id) => t(`gspd.areaStatus.${id}`), (v) => {
                area.status = v;
            }),
        );
        for (const fieldKey of ['description', 'owner', 'evidence', 'gap']) {
            appendField(
                areaFold.body,
                `gspd-area-${areaId}-${fieldKey}`,
                t(`gspd.area.${fieldKey}`),
                textControl(area[fieldKey], (v) => {
                    area[fieldKey] = v;
                }, { multiline: fieldKey !== 'owner' }),
            );
        }
        designFold.body.appendChild(areaFold.details);
    }
    formHost.appendChild(designFold.details);

    if (state.product && state.product !== 'multiple') {
        const productFold = createFold('productFields', t('gspd.section.productFields'));
        const fields = PRODUCT_FIELDS[state.product] || [];
        for (const fieldId of fields) {
            appendField(
                productFold.body,
                `gspd-product-${state.product}-${fieldId}`,
                t(`gspd.productField.${state.product}.${fieldId}`),
                textControl(state.productFields[state.product][fieldId], (v) => {
                    state.productFields[state.product][fieldId] = v;
                }, { multiline: true }),
            );
        }
        formHost.appendChild(productFold.details);
    }

    if (state.product === 'multiple') {
        const multiFold = createFold('multiple', t('gspd.section.multiple'));
        if (state.multipleRows.length === 0) {
            multiFold.body.appendChild(el('p', { className: 'discovery-export__hint' }, t('gspd.multiple.empty')));
        }
        state.multipleRows.forEach((row, index) => {
            const rowFold = createFold(`multiple:${index}`, `${t('gspd.multiple.row')} ${index + 1}`, 'gspd-fold--nested');
            const removeBtn = el('button', { type: 'button', className: 'tools-btn tools-btn--ghost' }, t('discovery.remove'));
            removeBtn.addEventListener('click', () => {
                state.multipleRows.splice(index, 1);
                openFolds.delete(`multiple:${index}`);
                renderForm();
            });
            rowFold.body.appendChild(removeBtn);
            for (const fieldId of MULTIPLE_ROW_FIELDS) {
                appendField(
                    rowFold.body,
                    `gspd-multiple-${index}-${fieldId}`,
                    t(`gspd.multiple.${fieldId}`),
                    textControl(row[fieldId], (v) => {
                        row[fieldId] = v;
                    }, { multiline: fieldId !== 'platform' && fieldId !== 'workload' }),
                );
            }
            multiFold.body.appendChild(rowFold.details);
        });
        const addRow = el('button', { type: 'button', className: 'tools-btn' }, t('gspd.multiple.add'));
        addRow.addEventListener('click', () => {
            state.multipleRows.push(emptyMultipleRow());
            openFolds.add(`multiple:${state.multipleRows.length - 1}`);
            renderForm();
        });
        multiFold.body.appendChild(addRow);
        formHost.appendChild(multiFold.details);
    }

    const gapsFold = createFold('gaps', t('gspd.section.gaps'));
    for (const listKey of LIST_KEYS) {
        const block = el('div', { className: 'gspd-list-block' });
        block.appendChild(el('h3', { className: 'gspd-area-card__title' }, t(`gspd.list.${listKey}`)));
        state.lists[listKey].forEach((item, index) => {
            const fieldId = `gspd-list-${listKey}-${index}`;
            const row = el('div', { className: 'gspd-list-row' });
            const input = textControl(item, (v) => {
                state.lists[listKey][index] = v;
            });
            input.id = fieldId;
            input.setAttribute('name', fieldId);
            input.setAttribute('autocomplete', 'off');
            input.setAttribute('aria-label', `${t(`gspd.list.${listKey}`)} ${index + 1}`);
            input.setAttribute('placeholder', t('gspd.list.itemPlaceholder'));
            row.appendChild(input);
            const removeBtn = el('button', { type: 'button', className: 'tools-btn tools-btn--ghost' }, t('discovery.remove'));
            removeBtn.addEventListener('click', () => {
                state.lists[listKey].splice(index, 1);
                renderForm();
            });
            row.appendChild(removeBtn);
            block.appendChild(row);
        });
        const addBtn = el('button', { type: 'button', className: 'tools-btn' }, t('gspd.list.add'));
        addBtn.addEventListener('click', () => {
            state.lists[listKey].push('');
            renderForm();
        });
        block.appendChild(addBtn);
        gapsFold.body.appendChild(block);
    }
    appendField(
        gapsFold.body,
        'gspd-exception-owner',
        t('gspd.exception.owner'),
        textControl(state.exceptionMeta.exceptionOwner, (v) => {
            state.exceptionMeta.exceptionOwner = v;
        }),
    );
    appendField(
        gapsFold.body,
        'gspd-exception-expiry',
        t('gspd.exception.expiry'),
        textControl(state.exceptionMeta.expiryDate, (v) => {
            state.exceptionMeta.expiryDate = v;
        }, { type: 'date' }),
    );
    formHost.appendChild(gapsFold.details);

    const decisionFold = createFold('decision', t('gspd.section.decision'));
    appendField(
        decisionFold.body,
        'gspd-decision-status',
        t('gspd.decision.status'),
        selectControl(state.decision.status, DECISION_STATUS, (id) => t(`gspd.decisionStatus.${id}`), (v) => {
            state.decision.status = v;
        }),
    );
    const decisionFields = [
        'preferredStartingPattern',
        'conditionalAlternative',
        'noNewPlatformAlternative',
        'blockers',
        'validationPlan',
        'noRegretNextStep',
        'decisionRationale',
        'implementationOwner',
        'approvalOwner',
        'reviewDate',
    ];
    for (const key of decisionFields) {
        appendField(
            decisionFold.body,
            `gspd-decision-${key}`,
            t(`gspd.decision.${key}`),
            textControl(state.decision[key], (v) => {
                state.decision[key] = v;
            }, {
                multiline: !['implementationOwner', 'approvalOwner', 'reviewDate'].includes(key),
                type: key === 'reviewDate' ? 'date' : 'text',
            }),
        );
    }
    formHost.appendChild(decisionFold.details);

    applyLabels();
    scheduleRenderPreview();
}

let previewTimer = 0;
function scheduleRenderPreview() {
    window.clearTimeout(previewTimer);
    previewTimer = window.setTimeout(renderPreview, 80);
    transferred = false;
}

function renderPreview() {
    if (preview) {
        preview.textContent = buildMarkdown(state, t, productLabel);
    }
}

function saveDraft() {
    try {
        localStorage.setItem(DRAFT_STORAGE_KEY, JSON.stringify({ v: 1, savedAt: Date.now(), state }));
        markClean();
        setStatus(t('gspd.draftSaved'));
    } catch {
        setStatus(t('gspd.draftMissing'));
    }
}

function loadDraft() {
    try {
        const raw = localStorage.getItem(DRAFT_STORAGE_KEY);
        if (!raw) {
            setStatus(t('gspd.draftMissing'));
            return;
        }
        const parsed = JSON.parse(raw);
        state = normalizeState(parsed?.state ?? parsed);
        writeStartingPointProduct(state.product);
        renderForm();
        markClean();
        setStatus(t('gspd.draftLoaded'));
    } catch {
        setStatus(t('gspd.draftMissing'));
    }
}

function resetForm() {
    if (!window.confirm(t('gspd.clearConfirm'))) {
        return;
    }
    const product = state.product;
    state = createEmptyState();
    state.product = product;
    renderForm();
    markClean();
    setStatus('');
}

function downloadMarkdown() {
    const md = buildMarkdown(state, t, productLabel);
    const blob = new Blob([md], { type: 'text/markdown;charset=utf-8' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    const slug = state.product || 'decision';
    a.href = url;
    a.download = `governance-starting-point-decision-${slug}.md`;
    a.click();
    URL.revokeObjectURL(url);
    markClean();
    setStatus(t('discovery.downloaded'));
}

async function copyMarkdown() {
    const md = buildMarkdown(state, t, productLabel);
    await copyTextToClipboard(md);
    markClean();
    setStatus(t('discovery.copied'));
}

function init() {
    const urlProduct = readProductFromUrl();
    if (urlProduct) {
        state.product = urlProduct;
    } else {
        const stack = applyStackPrefill();
        if (stackHint) {
            stackHint.hidden = !stack.used;
        }
    }
    if (stackHint && urlProduct) {
        const selection = readCustomStack();
        const hasStack = Object.values(selection).some((arr) => Array.isArray(arr) && arr.length > 0);
        stackHint.hidden = !hasStack;
    }

    writeStartingPointProduct(state.product);
    renderForm();
    applyLabels();
    markClean();

    app.querySelector('[data-copy-md]')?.addEventListener('click', () => {
        void copyMarkdown();
    });
    app.querySelector('[data-download-md]')?.addEventListener('click', downloadMarkdown);
    app.querySelector('[data-save-draft]')?.addEventListener('click', saveDraft);
    app.querySelector('[data-load-draft]')?.addEventListener('click', loadDraft);
    app.querySelector('[data-clear]')?.addEventListener('click', resetForm);

    bindPlanTransferUi({
        root: app,
        t,
        getPayload: () => ({ markdown: buildMarkdown(state, t, productLabel) }),
        markTransferred: () => {
            markClean();
        },
        hasContent: isDirty,
    });

    bindLeaveGuard(
        () => isDirty() && !transferred,
        () => t('discovery.leaveConfirm'),
        {
            getTitle: () => t('discovery.leaveTitle'),
            getStayLabel: () => t('discovery.leaveStay'),
            getLeaveLabel: () => t('discovery.leaveGo'),
        },
    );

    window.addEventListener('binom-tools:locale', () => {
        applyLabels();
        renderForm();
    });
}

init();
