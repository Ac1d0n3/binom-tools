import '../../../css/tools/pii-policy-generator.css';
import { getLocale } from '../../locale';
import { applyGateLabels, t } from './labels';
import { createDefaultModelState } from '../pii-shared/demo-model.js';
import { mergePiiMeta } from '../pii-shared/pii-meta.js';
import {
    debouncedSaveSchemaState,
    isStorageLoadCorrupt,
    loadPiiMetaState,
    subscribeSchemaState,
} from '../pii-shared/schema-storage';
import {
    buildGatedViewExample,
    buildPiiTableGateMacro,
    buildTableGateYamlExample,
} from './table-gate-builder.js';
import { copyFromButton, splitCsv, updateSyncStatusEl } from '../pii-shared/tool-utils.js';
import { collectGeneratorIssues } from '../pii-shared/validation.js';
import { mergeValidationTranslator } from '../pii-shared/validation-labels.js';
import { renderValidatedOutputs } from '../pii-shared/validation-ui.js';

const app = document.getElementById('pii-unreviewed-gate-generator-app');
if (!app) throw new Error('Unreviewed table gate generator root element not found');

/** @type {import('../pii-shared/demo-model.js').DbtModelState} */
let state = createDefaultModelState();

/** @type {import('../pii-shared/validation.js').ValidationIssue | null} */
let storageWarning = null;

const els = {
    reviewRoles: /** @type {HTMLInputElement} */ (document.getElementById('gate-review-roles')),
    accessGroups: /** @type {HTMLInputElement} */ (document.getElementById('gate-access-groups')),
    validationBanner: document.getElementById('gate-validation-banner'),
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

function validationT(key, params = {}) {
    return mergeValidationTranslator(locale(), t)(key, params);
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
    const issues = [
        ...(storageWarning ? [storageWarning] : []),
        ...collectGeneratorIssues(state, 'gate'),
    ];
    renderValidatedOutputs({
        bannerEl: els.validationBanner,
        outputPres: [els.macroPre, els.yamlPre, els.viewPre],
        issues,
        builds: [
            { el: els.macroPre, fn: () => buildPiiTableGateMacro(state) },
            { el: els.yamlPre, fn: () => buildTableGateYamlExample(state) },
            { el: els.viewPre, fn: () => buildGatedViewExample(state) },
        ],
        t: validationT,
    });
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
