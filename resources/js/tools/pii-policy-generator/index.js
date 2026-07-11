import '../../../css/tools/pii-policy-generator.css';
import { getLocale } from '../../locale';
import { applyPiiPolicyLabels, t } from './labels';
import {
    buildCategoryValue,
    createDefaultModelState,
    piiTypeOptions,
    prepareColumnsForAccessMode,
    scopeOptions,
    splitCategoryValue,
    suggestCategoryFromColumnName,
    syncAccessModeLineInDescription,
} from './demo-model';
import { buildDbtSchemaYaml } from './yaml-builder';
import { parseDbtSchemaYaml } from './yaml-parser';
import { mergePiiMeta } from '../pii-shared/pii-meta';
import {
    debouncedSaveSchemaState,
    loadPiiMetaState,
    subscribeSchemaState,
} from '../pii-shared/schema-storage';
import { buildColumnsAccordionHtml, syncColumnFromPanel } from '../pii-shared/column-accordion';
import { buildDbtMacro } from './dbt-macro-builder';
import { buildDbtPolicy } from './dbt-policy-builder';
import { buildDbtModelExample } from './dbt-model-builder';
import { updateSyncStatusEl } from '../pii-shared/tool-utils.js';

const app = document.getElementById('pii-policy-generator-app');
if (!app) throw new Error('PII policy generator root element not found');

/** @type {import('./demo-model.js').DbtModelState} */
let state = createDefaultModelState();

const els = {
    scenarioText: document.getElementById('pii-scenario-text'),
    howtoScenarioRoles: document.getElementById('pii-howto-scenario-roles'),
    howtoScenarioRules: document.getElementById('pii-howto-scenario-rules'),
    useAccessRoles: /** @type {HTMLInputElement} */ (document.getElementById('pii-use-access-roles')),
    accessRolesPanel: document.getElementById('pii-access-roles-panel'),
    accessRulesPanel: document.getElementById('pii-access-rules-panel'),
    defaultAccessRoles: /** @type {HTMLInputElement} */ (document.getElementById('pii-default-access-roles')),
    maskedRoles: /** @type {HTMLInputElement} */ (document.getElementById('pii-masked-roles')),
    unmaskedRoles: /** @type {HTMLInputElement} */ (document.getElementById('pii-unmasked-roles')),
    updateModelBtn: document.getElementById('pii-update-model-btn'),
    modelName: /** @type {HTMLInputElement} */ (document.getElementById('pii-model-name')),
    sourceTable: /** @type {HTMLInputElement} */ (document.getElementById('pii-source-table')),
    piiVersion: /** @type {HTMLInputElement} */ (document.getElementById('pii-version')),
    modelDescription: /** @type {HTMLTextAreaElement} */ (document.getElementById('pii-model-description')),
    descriptionExtra: /** @type {HTMLTextAreaElement} */ (document.getElementById('pii-description-extra')),
    defaultScope: /** @type {HTMLSelectElement} */ (document.getElementById('pii-default-scope')),
    columnsBody: document.getElementById('pii-columns-body'),
    addRowBtn: document.getElementById('pii-add-row-btn'),
    bulkColumns: /** @type {HTMLInputElement} */ (document.getElementById('pii-bulk-columns')),
    bulkAddBtn: document.getElementById('pii-bulk-add-btn'),
    applyScopeBtn: document.getElementById('pii-apply-scope-btn'),
    suggestAllBtn: document.getElementById('pii-suggest-all-btn'),
    yamlTextarea: /** @type {HTMLTextAreaElement} */ (document.getElementById('pii-yaml-textarea')),
    loadYamlBtn: document.getElementById('pii-load-yaml-btn'),
    executeBtn: document.getElementById('pii-execute-btn'),
    parseError: document.getElementById('pii-parse-error'),
    macroPre: document.getElementById('pii-macro-pre'),
    policyPre: document.getElementById('pii-policy-pre'),
    modelExamplePre: document.getElementById('pii-model-example-pre'),
    copyMacroBtn: document.getElementById('pii-copy-macro-btn'),
    copyPolicyBtn: document.getElementById('pii-copy-policy-btn'),
    copyModelBtn: document.getElementById('pii-copy-model-btn'),
    syncStatus: document.getElementById('pii-sync-status'),
};

/** @type {ReturnType<typeof setTimeout> | 0} */
let yamlUpdateTimer = 0;
/** @type {ReturnType<typeof setTimeout> | 0} */
let yamlToFormTimer = 0;
let syncingFromYaml = false;
let yamlEditorActive = false;

function locale() {
    return getLocale();
}

function splitCsv(value) {
    return value
        .split(',')
        .map((item) => item.trim())
        .filter(Boolean);
}

function escapeAttr(value) {
    return value.replaceAll('&', '&amp;').replaceAll('"', '&quot;').replaceAll('<', '&lt;');
}

function readModelFromForm() {
    state.modelName = els.modelName.value.trim() || 'example_table';
    state.sourceTable = els.sourceTable.value.trim() || 'source.schema.table';
    state.piiVersion = els.piiVersion.value.trim() || 'cf38c9353be46d305f35c22a8d926c62';
    state.modelDescription = els.modelDescription?.value ?? '';
    state.descriptionExtra = els.descriptionExtra.value.trim();
    state.defaultScope = /** @type {import('./demo-model.js').PiiScope} */ (els.defaultScope.value);
    state.useAccessRoles = els.useAccessRoles.checked;
    state.defaultAccessRoles = splitCsv(els.defaultAccessRoles.value);
    state.accessRules = {
        masked: splitCsv(els.maskedRoles.value),
        unmasked: splitCsv(els.unmaskedRoles.value),
    };
}

function writeModelToForm() {
    els.modelName.value = state.modelName;
    els.sourceTable.value = state.sourceTable;
    els.piiVersion.value = state.piiVersion;
    if (els.modelDescription) els.modelDescription.value = state.modelDescription ?? '';
    els.descriptionExtra.value = state.descriptionExtra;
    els.defaultScope.value = state.defaultScope;
    els.useAccessRoles.checked = state.useAccessRoles;
    els.defaultAccessRoles.value = state.defaultAccessRoles.join(', ');
    els.maskedRoles.value = state.accessRules.masked.join(', ');
    els.unmaskedRoles.value = state.accessRules.unmasked.join(', ');
    updateAccessPanels();
}

function updateAccessPanels() {
    const useRoles = els.useAccessRoles.checked;
    if (els.accessRolesPanel) els.accessRolesPanel.hidden = !useRoles;
    if (els.accessRulesPanel) els.accessRulesPanel.hidden = useRoles;
    if (els.scenarioText) {
        els.scenarioText.textContent = t(locale(), useRoles ? 'pii.scenario.roles' : 'pii.scenario.rules');
    }
    if (els.howtoScenarioRoles) els.howtoScenarioRoles.hidden = !useRoles;
    if (els.howtoScenarioRules) els.howtoScenarioRules.hidden = useRoles;
}

function renderTypeOptions(selected) {
    return piiTypeOptions
        .map((type) => `<option value="${type}" ${type === selected ? 'selected' : ''}>${type === 'none' ? t(locale(), 'pii.type.none') : type}</option>`)
        .join('');
}

function renderScopeOptions(selected) {
    return scopeOptions
        .map((scope) => `<option value="${scope}" ${scope === selected ? 'selected' : ''}>${t(locale(), `pii.scope.${scope}`)}</option>`)
        .join('');
}

function renderColumns() {
    if (!els.columnsBody) return;

    els.columnsBody.innerHTML = buildColumnsAccordionHtml({
        columns: state.columns,
        state,
        showAccessRoles: els.useAccessRoles.checked,
        t: (key) => t(locale(), key),
        labelKeys: {
            name: 'pii.columns.colName',
            description: 'pii.columns.colDescription',
            descriptionHint: 'pii.columns.colDescriptionHint',
            piiType: 'pii.columns.colPiiType',
            scope: 'pii.columns.colScope',
            category: 'pii.columns.colCategory',
            accessRoles: 'pii.columns.colAccessRoles',
            remove: 'pii.columns.remove',
        },
        inputClass: 'pii-policy-input',
        categoryClass: 'pii-policy-category-preview',
        renderTypeOptions,
        renderScopeOptions,
        escapeAttr,
    });

    bindColumnEvents();
}

function syncColumnFromRow(panel, index) {
    syncColumnFromPanel(panel, index, state.columns, state, splitCsv, buildCategoryValue);
}

function bindColumnEvents() {
    els.columnsBody?.querySelectorAll('[data-column-index]').forEach((panel) => {
        const index = Number(panel.getAttribute('data-column-index'));

        panel.querySelectorAll('[data-field]').forEach((field) => {
            if (field.matches('[data-field="categoryPreview"]')) return;

            field.addEventListener('input', () => {
                cancelYamlToForm();
                readModelFromForm();
                syncColumnFromRow(panel, index);
                debouncedUpdateYaml();
                persistState();
            });
            field.addEventListener('change', () => {
                cancelYamlToForm();
                readModelFromForm();
                const nameInput = /** @type {HTMLInputElement} */ (panel.querySelector('[data-field="name"]'));
                if (field.matches('[data-field="name"]') && nameInput.value.trim()) {
                    const suggested = suggestCategoryFromColumnName(nameInput.value, state.defaultScope, state.nameHeuristicRules);
                    const { piiType, scope } = splitCategoryValue(suggested);
                    /** @type {HTMLSelectElement} */ (panel.querySelector('[data-field="piiType"]')).value = piiType;
                    /** @type {HTMLSelectElement} */ (panel.querySelector('[data-field="scope"]')).value = scope;
                }
                syncColumnFromRow(panel, index);
                updateYamlPreview();
                persistState();
            });
        });

        panel.querySelector('[data-action="remove-row"]')?.addEventListener('click', () => {
            state.columns.splice(index, 1);
            if (state.columns.length === 0) {
                state.columns.push(createColumn());
            }
            renderColumns();
            updateYamlPreview();
            persistState();
        });
    });
}

function createColumn(name = '', category = 'none') {
    return {
        name,
        description: '',
        category: category || suggestCategoryFromColumnName(name, state.defaultScope, state.nameHeuristicRules),
        accessRoles: category !== 'none' ? [...state.defaultAccessRoles] : undefined,
    };
}

function readColumnsFromDom() {
    els.columnsBody?.querySelectorAll('[data-column-index]').forEach((panel) => {
        syncColumnFromRow(panel, Number(panel.getAttribute('data-column-index')));
    });
}

function cancelYamlToForm() {
    window.clearTimeout(yamlToFormTimer);
    yamlToFormTimer = 0;
}

function updateYamlPreview() {
    if (syncingFromYaml) return;
    readModelFromForm();
    readColumnsFromDom();
    const previewState = {
        ...state,
        columns: state.columns.filter((col) => col.name.trim()),
    };
    els.yamlTextarea.value = buildDbtSchemaYaml(previewState, { metaMode: 'details', piiReviewed: true });
}

function debouncedPushYamlToForm() {
    window.clearTimeout(yamlToFormTimer);
    yamlToFormTimer = window.setTimeout(() => loadFromYaml(), 400);
}

function onFormFieldChange() {
    cancelYamlToForm();
    readModelFromForm();
    debouncedUpdateYaml();
    persistState();
}

function debouncedUpdateYaml() {
    window.clearTimeout(yamlUpdateTimer);
    yamlUpdateTimer = window.setTimeout(() => updateYamlPreview(), 250);
}

function renderOutputs() {
    if (els.macroPre) els.macroPre.textContent = buildDbtMacro(state);
    if (els.policyPre) els.policyPre.textContent = buildDbtPolicy(state);
    if (els.modelExamplePre) els.modelExamplePre.textContent = buildDbtModelExample(state);
}

function updateSyncStatus(meta) {
    updateSyncStatusEl(els.syncStatus, meta, (key) => t(locale(), key));
}

function persistState() {
    debouncedSaveSchemaState(state, 'pii-policy-generator');
    updateSyncStatus({ savedAt: new Date().toISOString(), source: 'pii-policy-generator' });
}

function applyExternalPiiMeta(piiMeta) {
    readModelFromForm();
    readColumnsFromDom();
    state = mergePiiMeta(state, piiMeta);
    prepareColumnsForAccessMode(state);
    writeModelToForm();
    renderColumns();
    execute({ skipPersist: true });
}

function execute(options = {}) {
    readModelFromForm();
    readColumnsFromDom();
    const previewState = {
        ...state,
        columns: state.columns.filter((col) => col.name.trim()),
    };

    els.yamlTextarea.value = buildDbtSchemaYaml(previewState, { metaMode: 'details', piiReviewed: true });
    renderOutputs();
    if (els.parseError) els.parseError.hidden = true;
    if (!options.skipPersist) persistState();
}

function refreshFromYaml() {
    updateYamlPreview();
    renderOutputs();
}

function loadFromYaml() {
    const parsed = parseDbtSchemaYaml(els.yamlTextarea.value);
    if (!parsed) {
        if (els.parseError) {
            els.parseError.hidden = false;
            els.parseError.textContent = t(locale(), 'pii.yaml.parseError');
        }
        return;
    }

    syncingFromYaml = true;
    const storedPii = loadPiiMetaState();
    state = mergePiiMeta(parsed, storedPii?.state);
    prepareColumnsForAccessMode(state);
    writeModelToForm();
    renderColumns();
    renderOutputs();
    if (els.parseError) els.parseError.hidden = true;
    persistState();
    syncingFromYaml = false;
}

function bulkAddColumns() {
    readModelFromForm();
    const names = splitCsv(els.bulkColumns.value);
    if (!names.length) return;

    for (const name of names) {
        if (state.columns.some((col) => col.name === name)) continue;
        state.columns.push(createColumn(name));
    }

    els.bulkColumns.value = '';
    renderColumns();
    execute();
}

function applyScopeToAll() {
    readModelFromForm();
    readColumnsFromDom();
    state.columns = state.columns.map((column) => {
        if (column.category === 'none') return column;
        const { piiType } = splitCategoryValue(column.category);
        if (piiType === 'none') return column;
        return { ...column, category: buildCategoryValue(piiType, state.defaultScope) };
    });
    renderColumns();
}

function suggestAllCategories() {
    readModelFromForm();
    readColumnsFromDom();
    state.columns = state.columns.map((column) => ({
        ...column,
        category: suggestCategoryFromColumnName(column.name, state.defaultScope, state.nameHeuristicRules),
    }));
    renderColumns();
}

async function copyText(text, button) {
    if (!text || !button) return;
    try {
        await navigator.clipboard.writeText(text);
        const original = button.textContent;
        button.textContent = t(locale(), 'pii.copied');
        window.setTimeout(() => {
            button.textContent = original;
        }, 1500);
    } catch {
        window.prompt(t(locale(), 'pii.copy'), text);
    }
}

function bindEvents() {
    els.useAccessRoles?.addEventListener('change', () => {
        readModelFromForm();
        state.modelDescription = syncAccessModeLineInDescription(state.modelDescription, state.useAccessRoles);
        prepareColumnsForAccessMode(state);
        if (els.modelDescription) els.modelDescription.value = state.modelDescription;
        updateAccessPanels();
        renderColumns();
        execute();
    });

    const persistOnInput = [
        els.modelName,
        els.sourceTable,
        els.piiVersion,
        els.modelDescription,
        els.descriptionExtra,
        els.defaultScope,
        els.defaultAccessRoles,
        els.maskedRoles,
        els.unmaskedRoles,
    ];
    persistOnInput.forEach((el) => {
        el?.addEventListener('input', onFormFieldChange);
        el?.addEventListener('change', () => {
            cancelYamlToForm();
            readModelFromForm();
            execute();
        });
    });

    els.updateModelBtn?.addEventListener('click', () => {
        readModelFromForm();
        readColumnsFromDom();
        refreshFromYaml();
        persistState();
    });

    els.addRowBtn?.addEventListener('click', () => {
        readModelFromForm();
        readColumnsFromDom();
        state.columns.push(createColumn());
        renderColumns();
    });

    els.bulkAddBtn?.addEventListener('click', bulkAddColumns);
    els.applyScopeBtn?.addEventListener('click', applyScopeToAll);
    els.suggestAllBtn?.addEventListener('click', suggestAllCategories);
    els.loadYamlBtn?.addEventListener('click', loadFromYaml);
    els.executeBtn?.addEventListener('click', execute);
    els.yamlTextarea?.addEventListener('focus', () => {
        yamlEditorActive = true;
    });
    els.yamlTextarea?.addEventListener('blur', () => {
        yamlEditorActive = false;
        loadFromYaml();
    });
    els.yamlTextarea?.addEventListener('input', () => {
        if (yamlEditorActive) debouncedPushYamlToForm();
    });

    els.copyMacroBtn?.addEventListener('click', () => copyText(els.macroPre?.textContent ?? '', els.copyMacroBtn));
    els.copyPolicyBtn?.addEventListener('click', () => copyText(els.policyPre?.textContent ?? '', els.copyPolicyBtn));
    els.copyModelBtn?.addEventListener('click', () => copyText(els.modelExamplePre?.textContent ?? '', els.copyModelBtn));

    window.addEventListener('binom-tools:locale', () => {
        applyPiiPolicyLabels(locale());
        updateAccessPanels();
        renderColumns();
        updateSyncStatus(loadPiiMetaState()?.meta);
        [els.copyMacroBtn, els.copyPolicyBtn, els.copyModelBtn].forEach((btn) => {
            if (btn) btn.textContent = t(locale(), 'pii.copy');
        });
    });
}

function init() {
    applyPiiPolicyLabels(locale());
    const stored = loadPiiMetaState();
    if (stored) {
        state = mergePiiMeta(state, stored.state);
        prepareColumnsForAccessMode(state);
        updateSyncStatus(stored.meta);
    }
    writeModelToForm();
    renderColumns();
    execute({ skipPersist: true });
    bindEvents();
    subscribeSchemaState(({ state: piiMeta, meta }) => {
        if (!piiMeta || meta?.source === 'pii-policy-generator') return;
        applyExternalPiiMeta(piiMeta);
        if (meta) updateSyncStatus(meta);
    });
}

init();
