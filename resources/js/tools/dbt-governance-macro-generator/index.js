import '../../../css/tools/pii-policy-generator.css';
import { getLocale } from '../../locale';
import { applyGovernanceMacroLabels, t } from './labels';
import { createDefaultModelState, prepareColumnsForAccessMode } from '../pii-shared/demo-model.js';
import { mergePiiMeta } from '../pii-shared/pii-meta.js';
import {
    debouncedSaveSchemaState,
    isStorageLoadCorrupt,
    loadPiiMetaState,
    subscribeSchemaState,
} from '../pii-shared/schema-storage';
import {
    buildPiiGovernanceMacro,
    buildPiiReviewedTest,
    buildSetupMarkdown,
} from './governance-macro-builder.js';
import {
    copyFromButton,
    readWarehouseFromSelect,
    splitCsv,
    updateSyncStatusEl,
    writeWarehouseToForm,
} from '../pii-shared/tool-utils.js';
import { warehouseIds } from '../pii-shared/warehouse-templates.js';
import { collectGeneratorIssues } from '../pii-shared/validation.js';
import { mergeValidationTranslator } from '../pii-shared/validation-labels.js';
import { renderValidatedOutputs } from '../pii-shared/validation-ui.js';

const app = document.getElementById('dbt-governance-macro-generator-app');
if (!app) throw new Error('Governance macro generator root element not found');

/** @type {import('../pii-shared/demo-model.js').DbtModelState} */
let state = createDefaultModelState();

/** @type {import('../pii-shared/validation.js').ValidationIssue | null} */
let storageWarning = null;

const els = {
    warehouse: /** @type {HTMLSelectElement | null} */ (document.getElementById('gov-warehouse')),
    useAccessRoles: /** @type {HTMLInputElement} */ (document.getElementById('gov-use-access-roles')),
    accessRolesPanel: document.getElementById('gov-access-roles-panel'),
    accessRulesPanel: document.getElementById('gov-access-rules-panel'),
    defaultAccessRoles: /** @type {HTMLInputElement} */ (document.getElementById('gov-default-access-roles')),
    maskedRoles: /** @type {HTMLInputElement} */ (document.getElementById('gov-masked-roles')),
    unmaskedRoles: /** @type {HTMLInputElement} */ (document.getElementById('gov-unmasked-roles')),
    accessGroups: /** @type {HTMLInputElement} */ (document.getElementById('gov-access-groups')),
    validationBanner: document.getElementById('gov-validation-banner'),
    governancePre: document.getElementById('gov-governance-pre'),
    testPre: document.getElementById('gov-test-pre'),
    setupPre: document.getElementById('gov-setup-pre'),
    copyGovernanceBtn: /** @type {HTMLButtonElement | null} */ (document.getElementById('gov-copy-governance-btn')),
    copyTestBtn: /** @type {HTMLButtonElement | null} */ (document.getElementById('gov-copy-test-btn')),
    copySetupBtn: /** @type {HTMLButtonElement | null} */ (document.getElementById('gov-copy-setup-btn')),
    syncStatus: document.getElementById('gov-sync-status'),
};

function locale() {
    return getLocale();
}

function validationT(key, params = {}) {
    return mergeValidationTranslator(locale(), t)(key, params);
}

function readForm() {
    const warehouse = readWarehouseFromSelect(els.warehouse);
    if (warehouse) state.selectedWarehouse = warehouse;
    state.useAccessRoles = els.useAccessRoles.checked;
    state.defaultAccessRoles = splitCsv(els.defaultAccessRoles.value);
    state.accessRules = {
        masked: splitCsv(els.maskedRoles.value),
        unmasked: splitCsv(els.unmaskedRoles.value),
    };
    state.defaultModelAccessGroups = splitCsv(els.accessGroups.value);
    prepareColumnsForAccessMode(state);
}

function writeForm() {
    writeWarehouseToForm(state, els.warehouse);
    els.useAccessRoles.checked = state.useAccessRoles;
    els.defaultAccessRoles.value = state.defaultAccessRoles.join(', ');
    els.maskedRoles.value = state.accessRules.masked.join(', ');
    els.unmaskedRoles.value = state.accessRules.unmasked.join(', ');
    els.accessGroups.value = (state.defaultModelAccessGroups ?? []).join(', ');
    updateAccessPanels();
}

function updateAccessPanels() {
    const useRoles = els.useAccessRoles.checked;
    if (els.accessRolesPanel) els.accessRolesPanel.hidden = !useRoles;
    if (els.accessRulesPanel) els.accessRulesPanel.hidden = useRoles;
}

function renderOutputs() {
    const issues = [
        ...(storageWarning ? [storageWarning] : []),
        ...collectGeneratorIssues(state, 'govMacro'),
    ];
    renderValidatedOutputs({
        bannerEl: els.validationBanner,
        outputPres: [els.governancePre, els.testPre, els.setupPre],
        issues,
        builds: [
            { el: els.governancePre, fn: () => buildPiiGovernanceMacro(state) },
            { el: els.testPre, fn: () => buildPiiReviewedTest() },
            { el: els.setupPre, fn: () => buildSetupMarkdown(state) },
        ],
        t: validationT,
    });
}

function persistState() {
    debouncedSaveSchemaState(state, 'dbt-governance-macro-generator');
}

function bindEvents() {
    const inputs = [
        els.warehouse,
        els.useAccessRoles,
        els.defaultAccessRoles,
        els.maskedRoles,
        els.unmaskedRoles,
        els.accessGroups,
    ];
    inputs.forEach((el) => {
        el?.addEventListener('input', () => {
            readForm();
            renderOutputs();
            persistState();
        });
        el?.addEventListener('change', () => {
            readForm();
            updateAccessPanels();
            renderOutputs();
            persistState();
        });
    });

    els.copyGovernanceBtn?.addEventListener('click', () =>
        copyFromButton(els.copyGovernanceBtn, buildPiiGovernanceMacro(state), (key) => t(locale(), key)),
    );
    els.copyTestBtn?.addEventListener('click', () =>
        copyFromButton(els.copyTestBtn, buildPiiReviewedTest(), (key) => t(locale(), key)),
    );
    els.copySetupBtn?.addEventListener('click', () =>
        copyFromButton(els.copySetupBtn, buildSetupMarkdown(state), (key) => t(locale(), key)),
    );
}

function hydrateFromStorage() {
    const loaded = loadPiiMetaState();
    if (isStorageLoadCorrupt(loaded)) {
        storageWarning = {
            code: 'storage_corrupt',
            messageKey: 'validation.storageCorrupt',
            severity: 'warning',
        };
        return;
    }
    if (loaded && 'state' in loaded) {
        state = mergePiiMeta(state, loaded.state);
        updateSyncStatusEl(els.syncStatus, loaded.meta, (key) => t(locale(), key));
    }
}

function initWarehouseSelect() {
    if (!els.warehouse) return;
    els.warehouse.innerHTML = warehouseIds
        .map((id) => `<option value="${id}">${id}</option>`)
        .join('');
}

function boot() {
    applyGovernanceMacroLabels();
    initWarehouseSelect();
    hydrateFromStorage();
    writeForm();
    renderOutputs();
    bindEvents();

    subscribeSchemaState(({ state: piiMeta, meta }) => {
        if (!piiMeta || meta?.source === 'dbt-governance-macro-generator') return;
        state = mergePiiMeta(state, piiMeta);
        writeForm();
        renderOutputs();
        updateSyncStatusEl(els.syncStatus, meta, (key) => t(locale(), key));
    });

    window.addEventListener('binom-tools:locale', () => {
        applyGovernanceMacroLabels();
        const loaded = loadPiiMetaState();
        updateSyncStatusEl(
            els.syncStatus,
            loaded && 'meta' in loaded ? loaded.meta : null,
            (key) => t(locale(), key),
        );
        renderOutputs();
    });
}

boot();
