import '../../../css/tools/schema-yml-editor.css';
import { getLocale } from '../../locale';
import { applySchemaEditorLabels, t } from './labels';
import {
    buildCategoryValue,
    createDefaultModelState,
    piiTypeOptions,
    prepareColumnsForAccessMode,
    scopeOptions,
    splitCategoryValue,
    syncAccessModeLineInDescription,
} from '../pii-shared/demo-model';
import { buildDbtSchemaYaml } from '../pii-shared/yaml-builder';
import { parseDbtSchemaYaml } from '../pii-shared/yaml-parser';
import { extractPiiMeta, mergePiiMeta } from '../pii-shared/pii-meta';
import {
    clearSchemaState,
    clearYamlDraft,
    debouncedSaveSchemaState,
    loadPiiMetaState,
    loadYamlDraft,
    saveYamlDraft,
    subscribeSchemaState,
} from '../pii-shared/schema-storage';
import { buildColumnsAccordionHtml, syncColumnFromPanel } from '../pii-shared/column-accordion';

const app = document.getElementById('schema-yml-editor-app');
if (!app) throw new Error('Schema YML editor root element not found');

/** @type {import('../pii-shared/demo-model.js').DbtModelState} */
let state = createDefaultModelState();

let syncingFromYaml = false;
let yamlEditorActive = false;
/** @type {ReturnType<typeof window.setTimeout> | null} */
let formToYamlTimer = null;
/** @type {ReturnType<typeof window.setTimeout> | null} */
let yamlToFormTimer = null;

const els = {
    syncBadge: document.getElementById('schema-sync-badge'),
    loadStorageBtn: document.getElementById('schema-load-storage-btn'),
    clearStorageBtn: document.getElementById('schema-clear-storage-btn'),
    scenarioText: document.getElementById('schema-scenario-text'),
    howtoScenarioRoles: document.getElementById('schema-howto-scenario-roles'),
    howtoScenarioRules: document.getElementById('schema-howto-scenario-rules'),
    useAccessRoles: /** @type {HTMLInputElement} */ (document.getElementById('schema-use-access-roles')),
    accessRolesPanel: document.getElementById('schema-access-roles-panel'),
    accessRulesPanel: document.getElementById('schema-access-rules-panel'),
    defaultAccessRoles: /** @type {HTMLInputElement} */ (document.getElementById('schema-default-access-roles')),
    maskedRoles: /** @type {HTMLInputElement} */ (document.getElementById('schema-masked-roles')),
    unmaskedRoles: /** @type {HTMLInputElement} */ (document.getElementById('schema-unmasked-roles')),
    modelName: /** @type {HTMLInputElement} */ (document.getElementById('schema-model-name')),
    sourceTable: /** @type {HTMLInputElement} */ (document.getElementById('schema-source-table')),
    piiVersion: /** @type {HTMLInputElement} */ (document.getElementById('schema-pii-version')),
    modelDescription: /** @type {HTMLTextAreaElement} */ (document.getElementById('schema-model-description')),
    columnsBody: document.getElementById('schema-columns-body'),
    addRowBtn: document.getElementById('schema-add-row-btn'),
    yamlTextarea: /** @type {HTMLTextAreaElement} */ (document.getElementById('schema-yaml-textarea')),
    yamlParseError: document.getElementById('schema-yaml-parse-error'),
    copyYamlBtn: document.getElementById('schema-copy-yaml-btn'),
};

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
    state.sourceTable = els.sourceTable.value.trim() || 'raw.example_table';
    state.piiVersion = els.piiVersion.value.trim() || 'cf38c9353be46d305f35c22a8d926c62';
    state.modelDescription = els.modelDescription.value;
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
    els.modelDescription.value = state.modelDescription ?? '';
    els.useAccessRoles.checked = state.useAccessRoles;
    els.defaultAccessRoles.value = state.defaultAccessRoles.join(', ');
    els.maskedRoles.value = state.accessRules.masked.join(', ');
    els.unmaskedRoles.value = state.accessRules.unmasked.join(', ');
    updateAccessPanels();
}

function syncStateFromForm() {
    readModelFromForm();
    readColumnsFromDom();
}

function yamlPreviewState() {
    return {
        ...state,
        columns: state.columns.filter((col) => col.name.trim()),
    };
}

function cancelYamlToForm() {
    window.clearTimeout(yamlToFormTimer);
    yamlToFormTimer = null;
}

function pushFormToYaml() {
    if (syncingFromYaml || !els.yamlTextarea) return;
    syncStateFromForm();
    const yaml = buildDbtSchemaYaml(yamlPreviewState());
    els.yamlTextarea.value = yaml;
    saveYamlDraft(yaml);
    if (els.yamlParseError) els.yamlParseError.hidden = true;
}

function debouncedPushFormToYaml() {
    cancelYamlToForm();
    window.clearTimeout(formToYamlTimer);
    formToYamlTimer = window.setTimeout(() => pushFormToYaml(), 150);
}

function pushYamlToForm() {
    if (!els.yamlTextarea) return;

    const parsed = parseDbtSchemaYaml(els.yamlTextarea.value);
    if (!parsed) {
        if (els.yamlParseError) {
            els.yamlParseError.hidden = false;
            els.yamlParseError.textContent = t(locale(), 'schema.yaml.parseError');
        }
        return;
    }

    syncingFromYaml = true;
    const storedPii = loadPiiMetaState();
    state = mergePiiMeta(parsed, storedPii?.state ?? extractPiiMeta(state));
    prepareColumnsForAccessMode(state);
    writeModelToForm();
    renderColumns();
    saveYamlDraft(els.yamlTextarea.value);
    if (els.yamlParseError) els.yamlParseError.hidden = true;
    persistPiiMeta();
    syncingFromYaml = false;
}

function debouncedPushYamlToForm() {
    window.clearTimeout(yamlToFormTimer);
    yamlToFormTimer = window.setTimeout(() => pushYamlToForm(), 400);
}

function updateAccessPanels() {
    const useRoles = els.useAccessRoles.checked;
    if (els.accessRolesPanel) els.accessRolesPanel.hidden = !useRoles;
    if (els.accessRulesPanel) els.accessRulesPanel.hidden = useRoles;
    if (els.scenarioText) {
        els.scenarioText.textContent = t(locale(), useRoles ? 'schema.scenario.roles' : 'schema.scenario.rules');
    }
    if (els.howtoScenarioRoles) els.howtoScenarioRoles.hidden = !useRoles;
    if (els.howtoScenarioRules) els.howtoScenarioRules.hidden = useRoles;
}

function onAccessModeChange() {
    readModelFromForm();
    state.modelDescription = syncAccessModeLineInDescription(state.modelDescription, state.useAccessRoles);
    prepareColumnsForAccessMode(state);
    els.modelDescription.value = state.modelDescription;
    updateAccessPanels();
    renderColumns();
    onFormFieldChange();
}

function updateSyncBadge(source) {
    if (!els.syncBadge) return;
    const key =
        source === 'pii-policy-generator' ? 'schema.sync.loadedFromGenerator' : 'schema.sync.saved';
    els.syncBadge.textContent = t(locale(), key);
}

function persistPiiMeta() {
    syncStateFromForm();
    debouncedSaveSchemaState(state, 'schema-yml-editor');
    updateSyncBadge('schema-yml-editor');
}

function onFormFieldChange() {
    cancelYamlToForm();
    debouncedPushFormToYaml();
    persistPiiMeta();
}

function renderTypeOptions(selected) {
    return piiTypeOptions
        .map(
            (type) =>
                `<option value="${type}" ${type === selected ? 'selected' : ''}>${type === 'none' ? t(locale(), 'schema.type.none') : type}</option>`,
        )
        .join('');
}

function renderScopeOptions(selected) {
    return scopeOptions
        .map(
            (scope) =>
                `<option value="${scope}" ${scope === selected ? 'selected' : ''}>${t(locale(), `schema.scope.${scope}`)}</option>`,
        )
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
            name: 'schema.columns.colName',
            description: 'schema.columns.colDescription',
            descriptionHint: 'schema.columns.colDescriptionHint',
            piiType: 'schema.columns.colPiiType',
            scope: 'schema.columns.colScope',
            category: 'schema.columns.colCategory',
            accessRoles: 'schema.columns.colAccessRoles',
            remove: 'schema.columns.remove',
        },
        inputClass: 'schema-editor-input',
        categoryClass: 'schema-editor-category-preview',
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

            const onChange = () => {
                syncStateFromForm();
                syncColumnFromRow(panel, index);
                onFormFieldChange();
            };

            field.addEventListener('input', onChange);
            field.addEventListener('change', onChange);
        });

        panel.querySelector('[data-action="remove-row"]')?.addEventListener('click', () => {
            state.columns.splice(index, 1);
            if (state.columns.length === 0) {
                state.columns.push({ name: '', description: '', category: 'none' });
            }
            renderColumns();
            pushFormToYaml();
            persistPiiMeta();
        });
    });
}

function readColumnsFromDom() {
    els.columnsBody?.querySelectorAll('[data-column-index]').forEach((panel) => {
        syncColumnFromRow(panel, Number(panel.getAttribute('data-column-index')));
    });
}

function applyExternalPiiMeta(piiMeta, source) {
    syncStateFromForm();
    state = mergePiiMeta(state, piiMeta);
    prepareColumnsForAccessMode(state);
    writeModelToForm();
    renderColumns();
    pushFormToYaml();
    if (source) updateSyncBadge(source);
}

function syncFormFromYamlSilently() {
    if (!els.yamlTextarea) return;

    const parsed = parseDbtSchemaYaml(els.yamlTextarea.value);
    if (!parsed) return;

    syncingFromYaml = true;
    const storedPii = loadPiiMetaState();
    state = mergePiiMeta(parsed, storedPii?.state ?? extractPiiMeta(state));
    prepareColumnsForAccessMode(state);
    writeModelToForm();
    renderColumns();
    syncingFromYaml = false;
}

function ensureYamlDraft() {
    if (!els.yamlTextarea) return;

    const existing = loadYamlDraft();
    if (existing) {
        els.yamlTextarea.value = existing;
        return;
    }

    const yaml = buildDbtSchemaYaml(yamlPreviewState());
    els.yamlTextarea.value = yaml;
    saveYamlDraft(yaml);
}

async function copyText(text, button) {
    if (!text || !button) return;
    try {
        await navigator.clipboard.writeText(text);
        const original = button.textContent;
        button.textContent = t(locale(), 'schema.copied');
        window.setTimeout(() => {
            button.textContent = original;
        }, 1500);
    } catch {
        window.prompt(t(locale(), 'schema.copy'), text);
    }
}

function bindEvents() {
    els.useAccessRoles?.addEventListener('change', onAccessModeChange);

    els.addRowBtn?.addEventListener('click', () => {
        syncStateFromForm();
        state.columns.push({ name: '', description: '', category: 'none' });
        renderColumns();
        pushFormToYaml();
        persistPiiMeta();
    });

    els.loadStorageBtn?.addEventListener('click', () => {
        const loaded = loadPiiMetaState();
        if (loaded) applyExternalPiiMeta(loaded.state, loaded.meta.source);
    });

    els.clearStorageBtn?.addEventListener('click', () => {
        clearSchemaState();
        clearYamlDraft();
        state = createDefaultModelState();
        ensureYamlDraft();
        syncFormFromYamlSilently();
        updateSyncBadge('schema-yml-editor');
    });

    els.copyYamlBtn?.addEventListener('click', () => copyText(els.yamlTextarea?.value ?? '', els.copyYamlBtn));

    els.yamlTextarea?.addEventListener('focus', () => {
        yamlEditorActive = true;
    });

    els.yamlTextarea?.addEventListener('blur', () => {
        yamlEditorActive = false;
        pushYamlToForm();
    });

    els.yamlTextarea?.addEventListener('input', () => {
        saveYamlDraft(els.yamlTextarea?.value ?? '');
        if (yamlEditorActive) debouncedPushYamlToForm();
    });

    const persistOnInput = [
        els.modelName,
        els.sourceTable,
        els.piiVersion,
        els.modelDescription,
        els.defaultAccessRoles,
        els.maskedRoles,
        els.unmaskedRoles,
    ];
    persistOnInput.forEach((el) => {
        el?.addEventListener('input', onFormFieldChange);
        el?.addEventListener('change', onFormFieldChange);
    });

    window.addEventListener('binom-tools:locale', () => {
        applySchemaEditorLabels(locale());
        updateAccessPanels();
        renderColumns();
        if (els.copyYamlBtn) els.copyYamlBtn.textContent = t(locale(), 'schema.copy');
    });
}

function init() {
    applySchemaEditorLabels(locale());
    const storedPii = loadPiiMetaState();
    if (storedPii) updateSyncBadge(storedPii.meta.source);

    state = createDefaultModelState();
    if (storedPii) state = mergePiiMeta(state, storedPii.state);
    prepareColumnsForAccessMode(state);

    ensureYamlDraft();
    syncFormFromYamlSilently();
    updateAccessPanels();

    bindEvents();
    subscribeSchemaState(({ state: piiMeta, meta }) => {
        if (!piiMeta || meta?.source === 'schema-yml-editor') return;
        applyExternalPiiMeta(piiMeta, meta?.source ?? 'pii-policy-generator');
    });
}

init();
