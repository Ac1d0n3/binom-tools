import '../../../css/tools/pii-policy-generator.css';
import { getLocale } from '../../locale';
import { applyPiiRecommendLabels, renderPiiTypeOptions, t } from './labels';
import {
    createDefaultModelState,
    prepareColumnsForAccessMode,
    scopeOptions,
} from '../pii-shared/demo-model.js';
import { mergePiiMeta } from '../pii-shared/pii-meta.js';
import { normalizeMinMatchRate } from '../pii-shared/content-heuristic-rules.js';
import {
    debouncedSaveSchemaState,
    isStorageLoadCorrupt,
    loadPiiMetaState,
    subscribeSchemaState,
} from '../pii-shared/schema-storage';
import { buildDbtSchemaYaml } from '../pii-shared/yaml-builder.js';
import { buildPiiAuditByNameMacro, buildPiiContentScanMacro } from './pii-audit-builder.js';
import {
    copyFromButton,
    readWarehouseFromSelect,
    splitCsv,
    updateSyncStatusEl,
    updateWarehousePreview,
    writeWarehouseToForm,
} from '../pii-shared/tool-utils.js';
import { fillWarehouseSelect } from '../pii-shared/warehouse-templates.js';
import {
    validateAccessConfig,
    validateContentHeuristicRules,
    validateNameHeuristicRules,
} from '../pii-shared/validation.js';
import { mergeValidationTranslator } from '../pii-shared/validation-labels.js';
import { markInvalidRuleRows, renderValidatedOutputs } from '../pii-shared/validation-ui.js';

const app = document.getElementById('pii-recommend-generator-app');
if (!app) throw new Error('PII recommend generator root element not found');

/** @type {import('../pii-shared/demo-model.js').DbtModelState} */
let state = createDefaultModelState();

/** @type {import('../pii-shared/validation.js').ValidationIssue | null} */
let storageWarning = null;

const els = {
    warehouse: /** @type {HTMLSelectElement | null} */ (document.getElementById('rec-warehouse')),
    warehousePreview: document.getElementById('rec-warehouse-preview'),
    defaultScope: /** @type {HTMLSelectElement} */ (document.getElementById('rec-default-scope')),
    useAccessRoles: /** @type {HTMLInputElement} */ (document.getElementById('rec-use-access-roles')),
    accessRolesPanel: document.getElementById('rec-access-roles-panel'),
    accessRulesPanel: document.getElementById('rec-access-rules-panel'),
    howtoAccessRoles: document.getElementById('rec-howto-access-roles'),
    howtoAccessRules: document.getElementById('rec-howto-access-rules'),
    defaultAccessRoles: /** @type {HTMLInputElement} */ (document.getElementById('rec-default-access-roles')),
    maskedRoles: /** @type {HTMLInputElement} */ (document.getElementById('rec-masked-roles')),
    unmaskedRoles: /** @type {HTMLInputElement} */ (document.getElementById('rec-unmasked-roles')),
    accessGroups: /** @type {HTMLInputElement} */ (document.getElementById('rec-access-groups')),
    nameRulesBody: document.getElementById('rec-name-rules-body'),
    contentRulesBody: document.getElementById('rec-content-rules-body'),
    addNameRuleBtn: document.getElementById('rec-add-name-rule-btn'),
    addContentRuleBtn: document.getElementById('rec-add-content-rule-btn'),
    auditNamePre: document.getElementById('rec-audit-name-pre'),
    auditContentPre: document.getElementById('rec-audit-content-pre'),
    yamlPre: document.getElementById('rec-yaml-pre'),
    copyAuditNameBtn: /** @type {HTMLButtonElement | null} */ (document.getElementById('rec-copy-audit-name-btn')),
    copyAuditContentBtn: /** @type {HTMLButtonElement | null} */ (document.getElementById('rec-copy-audit-content-btn')),
    copyYamlBtn: /** @type {HTMLButtonElement | null} */ (document.getElementById('rec-copy-yaml-btn')),
    syncStatus: document.getElementById('rec-sync-status'),
    validationBanner: document.getElementById('rec-validation-banner'),
};

function locale() {
    return getLocale();
}

function validationT(key, params = {}) {
    return mergeValidationTranslator(locale(), t)(key, params);
}

function readAllNameRulesFromDom() {
    if (!els.nameRulesBody) return [];
    /** @type {import('../pii-shared/heuristic-rules.js').HeuristicRule[]} */
    const rules = [];
    els.nameRulesBody.querySelectorAll('[data-name-rule-index]').forEach((row) => {
        rules.push({
            pattern: /** @type {HTMLInputElement} */ (row.querySelector('[data-field="pattern"]')).value,
            piiType: /** @type {HTMLSelectElement} */ (row.querySelector('[data-field="piiType"]')).value,
        });
    });
    return rules;
}

function readAllContentRulesFromDom() {
    if (!els.contentRulesBody) return { rules: [], rawMinRates: [] };
    /** @type {import('../pii-shared/content-heuristic-rules.js').ContentHeuristicRule[]} */
    const rules = [];
    /** @type {(number | string)[]} */
    const rawMinRates = [];
    els.contentRulesBody.querySelectorAll('[data-content-rule-index]').forEach((row) => {
        const regex = /** @type {HTMLInputElement} */ (row.querySelector('[data-field="regex"]')).value;
        const piiType = /** @type {HTMLSelectElement} */ (row.querySelector('[data-field="piiType"]')).value;
        const minInput = /** @type {HTMLInputElement} */ (row.querySelector('[data-field="minMatchRate"]'));
        rawMinRates.push(minInput.value);
        rules.push({
            regex,
            piiType,
            minMatchRate: normalizeMinMatchRate(Number(minInput.value)),
        });
    });
    return { rules, rawMinRates };
}

function collectRecommendIssues() {
    const nameRules = readAllNameRulesFromDom();
    const { rules: contentRules, rawMinRates } = readAllContentRulesFromDom();
    return [
        ...(storageWarning ? [storageWarning] : []),
        ...validateAccessConfig(state),
        ...validateNameHeuristicRules(nameRules),
        ...validateContentHeuristicRules(contentRules, { rawMinRates }),
    ];
}

function applyRuleFieldMarkers(issues) {
    markInvalidRuleRows(els.nameRulesBody, '[data-name-rule-index]', '[data-field="pattern"]', issues, 'pattern');
    markInvalidRuleRows(els.contentRulesBody, '[data-content-rule-index]', '[data-field="regex"]', issues, 'regex');
    markInvalidRuleRows(
        els.contentRulesBody,
        '[data-content-rule-index]',
        '[data-field="minMatchRate"]',
        issues,
        'minMatchRate',
    );
}

function escapeAttr(value) {
    return value.replaceAll('&', '&amp;').replaceAll('"', '&quot;').replaceAll('<', '&lt;');
}

function readForm() {
    const warehouse = readWarehouseFromSelect(els.warehouse);
    if (warehouse) state.selectedWarehouse = warehouse;
    updateWarehousePreview(els.warehousePreview, state.selectedWarehouse, 'regex');
    state.defaultScope = /** @type {import('../pii-shared/demo-model.js').PiiScope} */ (els.defaultScope.value);
    state.useAccessRoles = els.useAccessRoles.checked;
    state.defaultAccessRoles = splitCsv(els.defaultAccessRoles.value);
    state.accessRules = {
        masked: splitCsv(els.maskedRoles.value),
        unmasked: splitCsv(els.unmaskedRoles.value),
    };
    state.defaultModelAccessGroups = splitCsv(els.accessGroups.value);
    readNameRulesFromDom();
    readContentRulesFromDom();
    prepareColumnsForAccessMode(state);
}

function writeForm() {
    writeWarehouseToForm(state, els.warehouse);
    updateWarehousePreview(els.warehousePreview, state.selectedWarehouse, 'regex');
    els.defaultScope.value = state.defaultScope;
    els.useAccessRoles.checked = state.useAccessRoles;
    els.defaultAccessRoles.value = state.defaultAccessRoles.join(', ');
    els.maskedRoles.value = state.accessRules.masked.join(', ');
    els.unmaskedRoles.value = state.accessRules.unmasked.join(', ');
    els.accessGroups.value = (state.defaultModelAccessGroups ?? []).join(', ');
    updateAccessPanels();
    renderNameRules();
    renderContentRules();
}

function updateAccessPanels() {
    const useRoles = els.useAccessRoles.checked;
    if (els.accessRolesPanel) els.accessRolesPanel.hidden = !useRoles;
    if (els.accessRulesPanel) els.accessRulesPanel.hidden = useRoles;
    if (els.howtoAccessRoles) els.howtoAccessRoles.hidden = !useRoles;
    if (els.howtoAccessRules) els.howtoAccessRules.hidden = useRoles;
}

function renderNameRules() {
    if (!els.nameRulesBody) return;
    els.nameRulesBody.innerHTML = (state.nameHeuristicRules ?? [])
        .map(
            (rule, index) => `
        <tr data-name-rule-index="${index}">
            <td><input class="pii-policy-input" type="text" data-field="pattern" value="${escapeAttr(rule.pattern)}" placeholder="email" /></td>
            <td><select class="pii-policy-input" data-field="piiType">${renderPiiTypeOptions(rule.piiType)}</select></td>
            <td><button type="button" class="tools-btn" data-action="remove">${t(locale(), 'piiRec.nameRules.remove')}</button></td>
        </tr>`,
        )
        .join('');
    bindNameRuleEvents();
}

function renderContentRules() {
    if (!els.contentRulesBody) return;
    els.contentRulesBody.innerHTML = (state.contentHeuristicRules ?? [])
        .map(
            (rule, index) => `
        <tr data-content-rule-index="${index}">
            <td><input class="pii-policy-input" type="text" data-field="regex" value="${escapeAttr(rule.regex)}" placeholder="^[^@\\s]+@[^@\\s]+\\.[^@\\s]+$" /></td>
            <td><select class="pii-policy-input" data-field="piiType">${renderPiiTypeOptions(rule.piiType)}</select></td>
            <td><input class="pii-policy-input" type="number" min="1" max="100" step="1" data-field="minMatchRate" value="${rule.minMatchRate ?? state.contentScanDefaultMinMatchRate ?? 5}" /></td>
            <td><button type="button" class="tools-btn" data-action="remove">${t(locale(), 'piiRec.contentRules.remove')}</button></td>
        </tr>`,
        )
        .join('');
    bindContentRuleEvents();
}

function readNameRulesFromDom() {
    if (!els.nameRulesBody) return;
    /** @type {import('../pii-shared/heuristic-rules.js').HeuristicRule[]} */
    const rules = [];
    els.nameRulesBody.querySelectorAll('[data-name-rule-index]').forEach((row) => {
        const pattern = /** @type {HTMLInputElement} */ (row.querySelector('[data-field="pattern"]')).value.trim();
        const piiType = /** @type {HTMLSelectElement} */ (row.querySelector('[data-field="piiType"]')).value;
        if (pattern) rules.push({ pattern, piiType });
    });
    state.nameHeuristicRules = rules;
}

function readContentRulesFromDom() {
    if (!els.contentRulesBody) return;
    /** @type {import('../pii-shared/content-heuristic-rules.js').ContentHeuristicRule[]} */
    const rules = [];
    els.contentRulesBody.querySelectorAll('[data-content-rule-index]').forEach((row) => {
        const regex = /** @type {HTMLInputElement} */ (row.querySelector('[data-field="regex"]')).value.trim();
        const piiType = /** @type {HTMLSelectElement} */ (row.querySelector('[data-field="piiType"]')).value;
        const minMatchRate = normalizeMinMatchRate(
            Number(/** @type {HTMLInputElement} */ (row.querySelector('[data-field="minMatchRate"]')).value),
        );
        if (regex) rules.push({ regex, piiType, minMatchRate });
    });
    state.contentHeuristicRules = rules;
}

function bindRuleRowEvents(row, readFn, renderFn, indexAttr) {
    row.querySelectorAll('input, select').forEach((field) => {
        field.addEventListener('input', onFormChange);
        field.addEventListener('change', onFormChange);
    });
    row.querySelector('[data-action="remove"]')?.addEventListener('click', () => {
        readFn();
        const index = Number(row.getAttribute(indexAttr));
        if (indexAttr === 'data-name-rule-index') {
            state.nameHeuristicRules = state.nameHeuristicRules.filter((_, i) => i !== index);
        } else {
            state.contentHeuristicRules = state.contentHeuristicRules.filter((_, i) => i !== index);
        }
        renderFn();
        renderOutputs();
        persistState();
    });
}

function bindNameRuleEvents() {
    els.nameRulesBody?.querySelectorAll('[data-name-rule-index]').forEach((row) => {
        bindRuleRowEvents(row, readNameRulesFromDom, renderNameRules, 'data-name-rule-index');
    });
}

function bindContentRuleEvents() {
    els.contentRulesBody?.querySelectorAll('[data-content-rule-index]').forEach((row) => {
        bindRuleRowEvents(row, readContentRulesFromDom, renderContentRules, 'data-content-rule-index');
    });
}

function onFormChange() {
    readForm();
    renderOutputs();
    persistState();
}

function buildRecommendYaml() {
    return buildDbtSchemaYaml(state, { metaMode: 'recommend', piiReviewed: false });
}

function renderOutputs() {
    const issues = collectRecommendIssues();
    applyRuleFieldMarkers(issues);
    renderValidatedOutputs({
        bannerEl: els.validationBanner,
        outputPres: [els.auditNamePre, els.auditContentPre, els.yamlPre],
        issues,
        builds: [
            { el: els.auditNamePre, fn: () => buildPiiAuditByNameMacro(state) },
            { el: els.auditContentPre, fn: () => buildPiiContentScanMacro(state) },
            { el: els.yamlPre, fn: () => buildRecommendYaml() },
        ],
        t: validationT,
    });
}

function persistState() {
    debouncedSaveSchemaState(state, 'pii-recommend-generator');
}

function bindEvents() {
    [
        els.warehouse,
        els.defaultScope,
        els.useAccessRoles,
        els.defaultAccessRoles,
        els.maskedRoles,
        els.unmaskedRoles,
        els.accessGroups,
    ].forEach((el) => {
        el?.addEventListener('input', onFormChange);
        el?.addEventListener('change', () => {
            readForm();
            updateAccessPanels();
            renderOutputs();
            persistState();
        });
    });

    els.addNameRuleBtn?.addEventListener('click', () => {
        readNameRulesFromDom();
        state.nameHeuristicRules.push({ pattern: '', piiType: 'email' });
        renderNameRules();
    });

    els.addContentRuleBtn?.addEventListener('click', () => {
        readContentRulesFromDom();
        state.contentHeuristicRules.push({
            regex: '',
            piiType: 'email',
            minMatchRate: state.contentScanDefaultMinMatchRate ?? 5,
        });
        renderContentRules();
    });

    els.copyAuditNameBtn?.addEventListener('click', () =>
        copyFromButton(els.copyAuditNameBtn, buildPiiAuditByNameMacro(state), (key) => t(locale(), key)),
    );
    els.copyAuditContentBtn?.addEventListener('click', () =>
        copyFromButton(els.copyAuditContentBtn, buildPiiContentScanMacro(state), (key) => t(locale(), key)),
    );
    els.copyYamlBtn?.addEventListener('click', () =>
        copyFromButton(els.copyYamlBtn, buildRecommendYaml(), (key) => t(locale(), key)),
    );
}

function initWarehouseSelect() {
    fillWarehouseSelect(els.warehouse);
}

function initScopeSelect() {
    els.defaultScope.innerHTML = scopeOptions
        .map((scope) => `<option value="${scope}">${scope}</option>`)
        .join('');
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
    applyPiiRecommendLabels();
    initWarehouseSelect();
    initScopeSelect();
    hydrateFromStorage();
    writeForm();
    renderOutputs();
    bindEvents();

    subscribeSchemaState(({ state: piiMeta, meta }) => {
        if (!piiMeta || meta?.source === 'pii-recommend-generator') return;
        state = mergePiiMeta(state, piiMeta);
        writeForm();
        renderOutputs();
        updateSyncStatusEl(els.syncStatus, meta, (key) => t(locale(), key));
    });

    window.addEventListener('binom-tools:locale', () => {
        applyPiiRecommendLabels();
        renderNameRules();
        renderContentRules();
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
