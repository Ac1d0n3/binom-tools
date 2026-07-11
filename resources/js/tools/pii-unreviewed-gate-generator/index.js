import '../../../css/tools/pii-policy-generator.css';
import { getLocale } from '../../locale';
import { applyGateLabels, t } from './labels';
import { createDefaultModelState } from '../pii-shared/demo-model.js';
import { mergePiiMeta } from '../pii-shared/pii-meta.js';
import {
    debouncedSaveSchemaState,
    loadPiiMetaState,
    subscribeSchemaState,
} from '../pii-shared/schema-storage';
import {
    buildGatedViewExample,
    buildPiiTableGateMacro,
    buildTableGateYamlExample,
} from './table-gate-builder.js';
import { copyFromButton, splitCsv, updateSyncStatusEl } from '../pii-shared/tool-utils.js';

const app = document.getElementById('pii-unreviewed-gate-generator-app');
if (!app) throw new Error('Unreviewed table gate generator root element not found');

/** @type {import('../pii-shared/demo-model.js').DbtModelState} */
let state = createDefaultModelState();

const els = {
    reviewRoles: /** @type {HTMLInputElement} */ (document.getElementById('gate-review-roles')),
    accessGroups: /** @type {HTMLInputElement} */ (document.getElementById('gate-access-groups')),
    macroPre: document.getElementById('gate-macro-pre'),
    yamlPre: document.getElementById('gate-yaml-pre'),
    viewPre: document.getElementById('gate-view-pre'),
    copyMacroBtn: /** @type {HTMLButtonElement | null} */ (document.getElementById('gate-copy-macro-btn')),
    copyYamlBtn: /** @type {HTMLButtonElement | null} */ (document.getElementById('gate-copy-yaml-btn')),
    copyViewBtn: /** @type {HTMLButtonElement | null} */ (document.getElementById('gate-copy-view-btn')),
    syncStatus: document.getElementById('gate-sync-status'),
};

function locale() {
    return getLocale();
}

function readForm() {
    state.defaultReviewRoles = splitCsv(els.reviewRoles.value);
    state.defaultModelAccessGroups = splitCsv(els.accessGroups.value);
}

function writeForm() {
    els.reviewRoles.value = (state.defaultReviewRoles ?? []).join(', ');
    els.accessGroups.value = (state.defaultModelAccessGroups ?? []).join(', ');
}

function renderOutputs() {
    if (els.macroPre) els.macroPre.textContent = buildPiiTableGateMacro(state);
    if (els.yamlPre) els.yamlPre.textContent = buildTableGateYamlExample(state);
    if (els.viewPre) els.viewPre.textContent = buildGatedViewExample(state);
}

function persistState() {
    debouncedSaveSchemaState(state, 'pii-unreviewed-gate-generator');
}

function onFormChange() {
    readForm();
    renderOutputs();
    persistState();
}

function bindEvents() {
    [els.reviewRoles, els.accessGroups].forEach((el) => {
        el?.addEventListener('input', onFormChange);
    });

    els.copyMacroBtn?.addEventListener('click', () =>
        copyFromButton(els.copyMacroBtn, buildPiiTableGateMacro(state), (key) => t(locale(), key)),
    );
    els.copyYamlBtn?.addEventListener('click', () =>
        copyFromButton(els.copyYamlBtn, buildTableGateYamlExample(state), (key) => t(locale(), key)),
    );
    els.copyViewBtn?.addEventListener('click', () =>
        copyFromButton(els.copyViewBtn, buildGatedViewExample(state), (key) => t(locale(), key)),
    );
}

function hydrateFromStorage() {
    const loaded = loadPiiMetaState();
    if (loaded?.state) {
        state = mergePiiMeta(state, loaded.state);
        updateSyncStatusEl(els.syncStatus, loaded.meta, (key) => t(locale(), key));
    }
}

function boot() {
    applyGateLabels();
    hydrateFromStorage();
    writeForm();
    renderOutputs();
    bindEvents();

    subscribeSchemaState(({ state: piiMeta, meta }) => {
        if (!piiMeta || meta?.source === 'pii-unreviewed-gate-generator') return;
        state = mergePiiMeta(state, piiMeta);
        writeForm();
        renderOutputs();
        updateSyncStatusEl(els.syncStatus, meta, (key) => t(locale(), key));
    });

    window.addEventListener('binom-tools:locale', () => {
        applyGateLabels();
        updateSyncStatusEl(els.syncStatus, loadPiiMetaState()?.meta, (key) => t(locale(), key));
    });
}

boot();
