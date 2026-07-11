import '../../../css/tools/pii-policy-generator.css';
import { getLocale } from '../../locale';
import { applyDqMacroLabels, t } from './labels';
import { createDefaultDqModelState } from '../dq-shared/dq-demo-model.js';
import { mergeDqMeta } from '../dq-shared/dq-meta.js';
import {
    debouncedSaveDqState,
    isDqStorageLoadCorrupt,
    loadDqMetaState,
    subscribeDqState,
} from '../dq-shared/dq-storage.js';
import { buildDqGovernanceMacro, buildDqRuleTest, buildSetupDqMarkdown } from './dq-macro-builder.js';
import {
    copyFromButton,
    readWarehouseFromSelect,
    updateSyncStatusEl,
    writeWarehouseToForm,
} from '../pii-shared/tool-utils.js';
import { warehouseIds } from '../pii-shared/warehouse-templates.js';
import { collectDqIssues } from '../dq-shared/dq-validation.js';
import { renderValidatedOutputs } from '../pii-shared/validation-ui.js';

const app = document.getElementById('dbt-dq-macro-generator-app');
if (!app) throw new Error('DQ macro generator root element not found');

/** @type {import('../dq-shared/dq-demo-model.js').DqModelState} */
let state = createDefaultDqModelState();

/** @type {import('../pii-shared/validation.js').ValidationIssue | null} */
let storageWarning = null;

const els = {
    warehouse: /** @type {HTMLSelectElement | null} */ (document.getElementById('dq-warehouse')),
    validationBanner: document.getElementById('dq-macro-validation-banner'),
    governancePre: document.getElementById('dq-governance-pre'),
    testPre: document.getElementById('dq-test-pre'),
    setupPre: document.getElementById('dq-setup-pre'),
    copyGovernanceBtn: /** @type {HTMLButtonElement | null} */ (document.getElementById('dq-copy-governance-btn')),
    copyTestBtn: /** @type {HTMLButtonElement | null} */ (document.getElementById('dq-copy-test-btn')),
    copySetupBtn: /** @type {HTMLButtonElement | null} */ (document.getElementById('dq-copy-setup-btn')),
    syncStatus: document.getElementById('dq-sync-status'),
};

function locale() {
    return getLocale();
}

function validationT(key, params = {}) {
    return t(locale(), key, params);
}

function readForm() {
    state.selectedWarehouse = readWarehouseFromSelect(els.warehouse) ?? state.selectedWarehouse;
}

function renderOutputs() {
    const dqIssues = collectDqIssues(state).map((issue) => ({
        severity: /** @type {'error' | 'warning'} */ (issue.severity === 'info' ? 'warning' : issue.severity),
        code: issue.code,
        messageKey: issue.messageKey,
        params: issue.params,
    }));

    renderValidatedOutputs({
        bannerEl: els.validationBanner,
        outputPres: [els.governancePre, els.testPre, els.setupPre],
        issues: [...(storageWarning ? [storageWarning] : []), ...dqIssues],
        builds: [
            { el: els.governancePre, fn: () => buildDqGovernanceMacro(state) },
            { el: els.testPre, fn: () => buildDqRuleTest() },
            { el: els.setupPre, fn: () => buildSetupDqMarkdown(state) },
        ],
        t: validationT,
    });
}

function persistState() {
    debouncedSaveDqState(state, 'dbt-dq-macro-generator');
}

function bindEvents() {
    els.warehouse?.addEventListener('change', () => {
        readForm();
        renderOutputs();
        persistState();
    });

    els.copyGovernanceBtn?.addEventListener('click', () =>
        copyFromButton(els.copyGovernanceBtn, buildDqGovernanceMacro(state), validationT),
    );
    els.copyTestBtn?.addEventListener('click', () =>
        copyFromButton(els.copyTestBtn, buildDqRuleTest(), validationT),
    );
    els.copySetupBtn?.addEventListener('click', () =>
        copyFromButton(els.copySetupBtn, buildSetupDqMarkdown(state), validationT),
    );
}

function initWarehouseSelect() {
    if (!els.warehouse) return;
    els.warehouse.innerHTML = warehouseIds.map((id) => `<option value="${id}">${id}</option>`).join('');
}

function hydrateFromStorage() {
    const loaded = loadDqMetaState();
    if (isDqStorageLoadCorrupt(loaded)) {
        storageWarning = {
            severity: 'warning',
            code: 'storage_corrupt',
            messageKey: 'validation.storageCorrupt',
        };
        return;
    }
    if (loaded && 'state' in loaded) {
        state = mergeDqMeta(state, loaded.state);
        updateSyncStatusEl(els.syncStatus, loaded.meta, validationT);
    }
}

function boot() {
    applyDqMacroLabels();
    initWarehouseSelect();
    hydrateFromStorage();
    writeWarehouseToForm(state, els.warehouse);
    renderOutputs();
    bindEvents();
}

boot();

subscribeDqState(({ state: next, meta }) => {
    state = mergeDqMeta(state, next);
    writeWarehouseToForm(state, els.warehouse);
    renderOutputs();
    updateSyncStatusEl(els.syncStatus, meta, validationT);
});

window.addEventListener('binom-tools:locale', () => {
    applyDqMacroLabels();
    renderOutputs();
});
