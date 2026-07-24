import '../../../css/tools/pii-policy-generator.css';
import '../../../css/tools/qlik-set-analysis-generator.css';
import { getLocale } from '../../locale';
import { copyFromButton } from '../pii-shared/tool-utils.js';
import { applyQlikSetLabels, t } from './labels.js';
import { buildCurrentFormula, buildModifier, buildSetAnalysisOutputs, injectSetAnalysis, parseBaseMeasures, parseCsv, parseDefinitions, parseFields, parseVariables } from './set-analysis-builder.js';

const app = document.getElementById('qlik-set-analysis-generator-app');
if (!app) throw new Error('Qlik set analysis generator root element not found');

const els = {
    helpSection: document.querySelector('.qlik-set-help'),
    helpToggle: document.getElementById('qlik-set-help-toggle'),
    helpBody: document.getElementById('qlik-set-help-body'),
    paletteToggle: document.getElementById('qlik-set-palette-toggle'),
    paletteBody: document.getElementById('qlik-set-palette-body'),
    descriptionToggle: document.getElementById('qlik-set-description-toggle'),
    descriptionBody: document.getElementById('qlik-set-description-body'),
    baseListToggle: document.getElementById('qlik-set-base-list-toggle'),
    baseListBody: document.getElementById('qlik-set-base-list-body'),
    importModal: document.getElementById('qlik-set-import-modal'),
    workbench: document.querySelector('.qlik-set-workbench'),
    workbenchToggle: document.getElementById('qlik-set-workbench-toggle'),
    workbenchBody: document.getElementById('qlik-set-workbench-body'),
    catalogRail: document.querySelector('[data-qlik-column="catalog"]'),
    appName: document.getElementById('qlik-set-app-name'),
    savedApps: document.getElementById('qlik-set-saved-apps'),
    saveApp: document.getElementById('qlik-set-save-app'),
    loadApp: document.getElementById('qlik-set-load-app'),
    deleteApp: document.getElementById('qlik-set-delete-app'),
    appMessage: document.getElementById('qlik-set-app-message'),
    importModalOpen: document.getElementById('qlik-set-import-modal-open'),
    importModalClose: document.getElementById('qlik-set-import-modal-close'),
    measureName: document.getElementById('qlik-set-measure-name'),
    baseDescription: document.getElementById('qlik-set-base-description'),
    baseDescriptionEn: document.getElementById('qlik-set-base-description-en'),
    descriptionLanguage: document.getElementById('qlik-set-description-language'),
    childDescriptionTemplate: document.getElementById('qlik-set-child-description-template'),
    childDescriptionTemplateEn: document.getElementById('qlik-set-child-description-template-en'),
    baseMeasure: document.getElementById('qlik-set-base-measure'),
    baseMeasures: document.getElementById('qlik-set-base-measures'),
    baseMeasureSelect: document.getElementById('qlik-set-base-measure-select'),
    addCurrentBase: document.getElementById('qlik-set-add-current-base'),
    loadBase: document.getElementById('qlik-set-load-base'),
    newBase: document.getElementById('qlik-set-new-base'),
    undo: document.getElementById('qlik-set-undo'),
    redo: document.getElementById('qlik-set-redo'),
    kpiState: document.getElementById('qlik-set-kpi-state'),
    currentFormulaPre: document.getElementById('qlik-set-current-formula-pre'),
    variablePrefix: document.getElementById('qlik-set-variable-prefix'),
    mode: document.getElementById('qlik-set-mode'),
    identifier: document.getElementById('qlik-set-identifier'),
    assignment: document.getElementById('qlik-set-assignment'),
    expressionStyle: document.getElementById('qlik-set-expression-style'),
    valueMode: document.getElementById('qlik-set-value-mode'),
    useVariables: document.getElementById('qlik-set-use-variables'),
    csvFile: document.getElementById('qlik-set-csv-file'),
    fieldsFile: document.getElementById('qlik-set-fields-file'),
    varsFile: document.getElementById('qlik-set-vars-file'),
    valueDimension: document.getElementById('qlik-set-value-dimension'),
    valueValue: document.getElementById('qlik-set-value-value'),
    valueLabel: document.getElementById('qlik-set-value-label'),
    valueOperator: document.getElementById('qlik-set-value-operator'),
    addValueRow: document.getElementById('qlik-set-add-value-row'),
    valueList: document.getElementById('qlik-set-value-list'),
    valueMessage: document.getElementById('qlik-set-value-message'),
    fields: document.getElementById('qlik-set-fields'),
    vars: document.getElementById('qlik-set-vars'),
    fieldOptions: document.getElementById('qlik-set-field-options'),
    variableOptions: document.getElementById('qlik-set-variable-options'),
    dimensionChips: document.getElementById('qlik-set-dimension-chips'),
    measureChips: document.getElementById('qlik-set-measure-chips'),
    variableChips: document.getElementById('qlik-set-variable-chips'),
    generatedMeasures: document.getElementById('qlik-set-generated-measures'),
    aggregation: document.getElementById('qlik-set-aggregation'),
    measureField: document.getElementById('qlik-set-measure-field'),
    applyBase: document.getElementById('qlik-set-apply-base'),
    applySetFilter: document.getElementById('qlik-set-apply-set-filter'),
    addVariable: document.getElementById('qlik-set-add-variable'),
    addSetVariable: document.getElementById('qlik-set-add-set-variable'),
    addSearchFilter: document.getElementById('qlik-set-add-search-filter'),
    builderDimension: document.getElementById('qlik-set-builder-dimension'),
    builderValue: document.getElementById('qlik-set-builder-value'),
    builderLabel: document.getElementById('qlik-set-builder-label'),
    builderMessage: document.getElementById('qlik-set-builder-message'),
    definitions: document.getElementById('qlik-set-definitions'),
    definitionName: document.getElementById('qlik-set-definition-name'),
    definitionVariable: document.getElementById('qlik-set-definition-variable'),
    definitionPreviewPre: document.getElementById('qlik-set-definition-preview-pre'),
    definitionList: document.getElementById('qlik-set-definition-list'),
    searchExpression: document.getElementById('qlik-set-search-expression'),
    setVarName: document.getElementById('qlik-set-set-var-name'),
    setVarValues: document.getElementById('qlik-set-set-var-values'),
    filterDropzone: document.getElementById('qlik-set-filter-dropzone'),
    treePreview: document.getElementById('qlik-set-tree-preview'),
    hierarchyLevels: document.getElementById('qlik-set-hierarchy-levels'),
    hierarchyPreview: document.getElementById('qlik-set-hierarchy-preview'),
    dateField: document.getElementById('qlik-set-date-field'),
    yearField: document.getElementById('qlik-set-year-field'),
    rows: document.getElementById('qlik-set-rows'),
    measuresPre: document.getElementById('qlik-set-measures-pre'),
    variablesPre: document.getElementById('qlik-set-variables-pre'),
    timeVarsPre: document.getElementById('qlik-set-time-vars-pre'),
    modifiersPre: document.getElementById('qlik-set-modifiers-pre'),
    nestedIfPre: document.getElementById('qlik-set-nested-if-pre'),
    pickMatchPre: document.getElementById('qlik-set-pick-match-pre'),
    csvPre: document.getElementById('qlik-set-csv-pre'),
    copyMeasures: document.getElementById('qlik-set-copy-measures'),
    copyVariables: document.getElementById('qlik-set-copy-variables'),
    copyTimeVars: document.getElementById('qlik-set-copy-time-vars'),
    copyModifiers: document.getElementById('qlik-set-copy-modifiers'),
    copyNestedIf: document.getElementById('qlik-set-copy-nested-if'),
    copyPickMatch: document.getElementById('qlik-set-copy-pick-match'),
    copyCsv: document.getElementById('qlik-set-copy-csv'),
    downloadMeasuresXlsx: document.getElementById('qlik-set-download-measures-xlsx'),
    downloadMeasuresCsv: document.getElementById('qlik-set-download-measures-csv'),
    downloadVariablesXlsx: document.getElementById('qlik-set-download-variables-xlsx'),
    downloadVariablesCsv: document.getElementById('qlik-set-download-variables-csv'),
    outputTabbar: document.querySelector('.qlik-set-output-tabbar'),
    outputTabsViewport: document.querySelector('.qlik-set-output-tabs'),
    outputScrollPrev: document.querySelector('[data-qlik-output-scroll="prev"]'),
    outputScrollNext: document.querySelector('[data-qlik-output-scroll="next"]'),
    outputTabs: Array.from(document.querySelectorAll('[data-qlik-output-tab]')),
    outputPanels: Array.from(document.querySelectorAll('[data-qlik-output-panel]')),
};

const formControls = Array.from(app.querySelectorAll('input, select, textarea'));
const savedAppStorageKey = 'qlik-set-analysis-generator.apps.v1';
const state = {
    activeKpi: null,
    kpiBaseMeasure: null,
    definitionDraftTouched: false,
    focusSnapshot: null,
    undoStack: [],
    redoStack: [],
};
const formulaHistoryControlIds = new Set([
    'qlik-set-measure-name',
    'qlik-set-base-description',
    'qlik-set-base-description-en',
    'qlik-set-description-language',
    'qlik-set-child-description-template',
    'qlik-set-child-description-template-en',
    'qlik-set-base-measure',
    'qlik-set-variable-prefix',
    'qlik-set-aggregation',
    'qlik-set-measure-field',
]);

function controlKey(control) {
    return control.id || control.name;
}

function showBuilderMessage(message, type = 'info') {
    if (!els.builderMessage) return;
    els.builderMessage.textContent = message;
    els.builderMessage.dataset.type = type;
    els.builderMessage.hidden = false;
}

function clearBuilderMessage() {
    if (!els.builderMessage) return;
    els.builderMessage.textContent = '';
    els.builderMessage.hidden = true;
}

function showAppMessage(message, type = 'info') {
    if (!els.appMessage) return;
    els.appMessage.textContent = message;
    els.appMessage.dataset.type = type;
    els.appMessage.hidden = false;
}

function clearAppMessage() {
    if (!els.appMessage) return;
    els.appMessage.textContent = '';
    els.appMessage.hidden = true;
}

function snapshotForm(scope = 'all') {
    const values = {};
    formControls.forEach((control) => {
        const key = controlKey(control);
        if (!key || control.type === 'file') return;
        if (scope === 'formula' && !formulaHistoryControlIds.has(key)) return;
        if (control.type === 'checkbox' || control.type === 'radio') {
            values[key] = control.checked;
        } else {
            values[key] = control.value;
        }
    });

    return {
        values,
        activeKpi: state.activeKpi,
        kpiBaseMeasure: state.kpiBaseMeasure,
    };
}

function readSavedApps() {
    try {
        const savedApps = JSON.parse(localStorage.getItem(savedAppStorageKey) || '{}');
        return savedApps && typeof savedApps === 'object' ? savedApps : {};
    } catch {
        return {};
    }
}

function writeSavedApps(savedApps) {
    localStorage.setItem(savedAppStorageKey, JSON.stringify(savedApps));
}

function refreshSavedAppsSelect(selected = '') {
    if (!els.savedApps) return;
    const savedApps = readSavedApps();
    const names = Object.keys(savedApps).sort((a, b) => a.localeCompare(b));
    els.savedApps.innerHTML = [
        `<option value="">${escapeHtml(t(getLocale(), 'qlikSet.apps.none'))}</option>`,
        ...names.map((name) => `<option value="${escapeHtml(name)}">${escapeHtml(name)}</option>`),
    ].join('');
    if (selected && names.includes(selected)) els.savedApps.value = selected;
}

function snapshotEquals(a, b) {
    return JSON.stringify(a) === JSON.stringify(b);
}

function restoreSnapshot(snapshot) {
    if (!snapshot) return;
    formControls.forEach((control) => {
        const key = controlKey(control);
        if (!key || control.type === 'file' || !(key in snapshot.values)) return;
        if (control.type === 'checkbox' || control.type === 'radio') {
            control.checked = Boolean(snapshot.values[key]);
        } else {
            control.value = snapshot.values[key];
        }
    });
    state.activeKpi = snapshot.activeKpi;
    state.kpiBaseMeasure = snapshot.kpiBaseMeasure;
}

function activateOutputTab(name) {
    let activeTab = null;
    els.outputTabs.forEach((tab) => {
        const active = tab.dataset.qlikOutputTab === name;
        tab.classList.toggle('is-active', active);
        tab.setAttribute('aria-selected', active ? 'true' : 'false');
        if (active) activeTab = tab;
    });

    els.outputPanels.forEach((panel) => {
        const active = panel.dataset.qlikOutputPanel === name;
        panel.classList.toggle('is-active', active);
        panel.hidden = !active;
    });

    activeTab?.scrollIntoView({ block: 'nearest', inline: 'nearest', behavior: 'smooth' });
    window.requestAnimationFrame(updateOutputTabOverflow);
}

function updateOutputTabOverflow() {
    const viewport = els.outputTabsViewport;
    if (!viewport || !els.outputTabbar) return;
    const maxScroll = Math.max(0, viewport.scrollWidth - viewport.clientWidth);
    const hasOverflow = maxScroll > 1;
    const atStart = viewport.scrollLeft <= 1;
    const atEnd = viewport.scrollLeft >= maxScroll - 1;

    els.outputTabbar.classList.toggle('has-overflow', hasOverflow);
    if (els.outputScrollPrev) els.outputScrollPrev.disabled = !hasOverflow || atStart;
    if (els.outputScrollNext) els.outputScrollNext.disabled = !hasOverflow || atEnd;
}

function scrollOutputTabs(direction) {
    const viewport = els.outputTabsViewport;
    if (!viewport) return;
    const amount = Math.max(180, Math.floor(viewport.clientWidth * 0.65));
    viewport.scrollBy({ left: direction * amount, behavior: 'smooth' });
    window.setTimeout(updateOutputTabOverflow, 220);
}

function setBuilderDimension(value) {
    if (!els.builderDimension || !value) return;
    if (els.builderDimension.tagName === 'SELECT') {
        const exists = Array.from(els.builderDimension.options).some((option) => option.value === value);
        if (!exists) els.builderDimension.add(new Option(value, value));
    }
    els.builderDimension.value = value;
}

function selectedBuilderValues() {
    if (!els.builderValue) return [];
    if (els.builderValue.tagName === 'SELECT' && els.builderValue.multiple) {
        return Array.from(els.builderValue.selectedOptions)
            .map((option) => option.value.trim())
            .filter(Boolean);
    }
    return [els.builderValue.value?.trim() || ''].filter(Boolean);
}

function rememberBeforeChange() {
    const snapshot = snapshotForm('formula');
    pushFormulaSnapshot(snapshot);
}

function pushFormulaSnapshot(snapshot) {
    const last = state.undoStack[state.undoStack.length - 1];
    if (!last || !snapshotEquals(last, snapshot)) {
        state.undoStack.push(snapshot);
        if (state.undoStack.length > 80) state.undoStack.shift();
    }
    state.redoStack = [];
    updateHistoryButtons();
}

function resetKpiLock() {
    state.activeKpi = null;
    state.kpiBaseMeasure = null;
}

function updateHistoryButtons() {
    if (els.undo) els.undo.disabled = state.undoStack.length === 0;
    if (els.redo) els.redo.disabled = state.redoStack.length === 0;
}

function setWorkbenchColumn(column, open) {
    if (!els.workbench) return;
    els.workbench.dataset[`layout${column.charAt(0).toUpperCase()}${column.slice(1)}`] = open ? 'open' : 'closed';
    app.querySelectorAll(`[data-qlik-layout-toggle="${column}"]`).forEach((button) => {
        button.classList.toggle('is-active', open);
        button.setAttribute('aria-pressed', open ? 'true' : 'false');
    });
}

function workbenchColumnOpen(column) {
    if (!els.workbench) return true;
    return els.workbench.dataset[`layout${column.charAt(0).toUpperCase()}${column.slice(1)}`] !== 'closed';
}

function applyWorkbenchPreset(preset) {
    if (preset === 'focus-formula') {
        setWorkbenchColumn('catalog', false);
        setWorkbenchColumn('composer', true);
        setWorkbenchColumn('builder', false);
        return;
    }

    setWorkbenchColumn('catalog', true);
    setWorkbenchColumn('composer', true);
    setWorkbenchColumn('builder', true);
}

function readState() {
    return {
        measureName: els.measureName?.value?.trim() || 'Sales',
        baseDescription: els.baseDescription?.value?.trim() || '',
        baseDescriptionEn: els.baseDescriptionEn?.value?.trim() || '',
        descriptionLanguage: els.descriptionLanguage?.value || 'de',
        childDescriptionTemplate: els.childDescriptionTemplate?.value || '',
        childDescriptionTemplateEn: els.childDescriptionTemplateEn?.value || '',
        baseMeasure: els.baseMeasure?.value?.trim() || 'Sum(Sales)',
        baseMeasuresText: els.baseMeasures?.value || '',
        definitionsText: els.definitions?.value || '',
        variablePrefix: els.variablePrefix?.value?.trim() || 'vSales',
        rowsText: els.rows?.value || '',
        mode: els.mode?.value || 'single',
        setIdentifier: els.identifier?.value ?? '$',
        assignment: els.assignment?.value || '=',
        expressionStyle: els.expressionStyle?.value || 'inner',
        valueMode: els.valueMode?.value || 'literal',
        dateField: els.dateField?.value?.trim() || 'Date',
        yearField: els.yearField?.value?.trim() || 'Year',
        fieldsText: els.fields?.value || '',
        variablesText: els.vars?.value || '',
        hierarchyText: els.hierarchyLevels?.value || '',
        useVariables: els.useVariables?.value !== 'no',
    };
}

function buildOutputs() {
    return buildSetAnalysisOutputs(readState());
}

function currentFormula() {
    return buildCurrentFormula(readState());
}

function updateHelpToggleLabel() {
    if (!els.helpToggle) return;
    const expanded = els.helpToggle.getAttribute('aria-expanded') === 'true';
    els.helpToggle.textContent = t(getLocale(), expanded ? 'qlikSet.help.hide' : 'qlikSet.help.show');
}

function updatePaletteToggleLabel() {
    if (!els.paletteToggle) return;
    const expanded = els.paletteToggle.getAttribute('aria-expanded') !== 'false';
    els.paletteToggle.textContent = t(getLocale(), expanded ? 'qlikSet.palette.hide' : 'qlikSet.palette.show');
}

function updateDescriptionToggleLabel() {
    if (!els.descriptionToggle) return;
    const expanded = els.descriptionToggle.getAttribute('aria-expanded') === 'true';
    els.descriptionToggle.textContent = t(getLocale(), expanded ? 'qlikSet.descriptions.hide' : 'qlikSet.descriptions.show');
}

function updateBaseListToggleLabel() {
    if (!els.baseListToggle) return;
    const expanded = els.baseListToggle.getAttribute('aria-expanded') === 'true';
    els.baseListToggle.textContent = t(getLocale(), expanded ? 'qlikSet.baseList.hide' : 'qlikSet.baseList.show');
}

function updateWorkbenchToggleLabel() {
    if (!els.workbenchToggle) return;
    const expanded = els.workbenchToggle.getAttribute('aria-expanded') !== 'false';
    els.workbenchToggle.textContent = t(getLocale(), expanded ? 'qlikSet.workbench.hide' : 'qlikSet.workbench.show');
}

function escapeHtml(value) {
    return value.replace(/[&<>"']/g, (char) => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;',
    })[char] || char);
}

function qlikFieldName(value) {
    const trimmed = value.trim();
    if (trimmed.startsWith('[') && trimmed.endsWith(']')) return trimmed;
    return `[${trimmed.replaceAll(']', ']]')}]`;
}

function qlikSingleQuoted(value) {
    return `'${value.replaceAll("'", "''")}'`;
}

function csvCell(value) {
    return `"${value.replaceAll('"', '""')}"`;
}

function dimensionValueCsvLine(row) {
    return [
        row.dimension || '',
        row.value || '',
        row.label || row.value || '',
        row.operator || '',
        row.assignment || '',
    ].map(csvCell).join(',');
}

function writeDimensionValues(rows) {
    if (!els.rows) return;
    els.rows.value = [
        'dimension,value,label,operator,assignment',
        ...rows.map(dimensionValueCsvLine),
    ].join('\n');
}

function definitionCsvLine(definition) {
    return [
        definition.name || '',
        definition.modifier || '',
        definition.variableName || '',
        definition.description || '',
        definition.dimensions || '',
        definition.values || '',
    ].map(csvCell).join(',');
}

function writeDefinitions(definitions) {
    if (!els.definitions) return;
    els.definitions.value = [
        'name,modifier,variable,description,dimensions,values',
        ...definitions.map(definitionCsvLine),
    ].join('\n');
}

function selectedDefinitionDraft() {
    const dimension = els.builderDimension?.value?.trim() || '';
    const values = selectedBuilderValues();
    const label = values.join(' + ');
    const name = els.definitionName?.value?.trim() || [dimension, label].filter(Boolean).join(' ');
    const variableName = els.definitionVariable?.value?.trim() || `vDef${[dimension, label].join('_').replace(/[^A-Za-z0-9]+/g, '_').replace(/^_+|_+$/g, '')}`;
    const modifier = dimension && values.length
        ? buildModifier([{
            dimension,
            value: values.join('\u0000'),
            label,
        }], {
            assignment: els.assignment?.value || '=',
            valueMode: els.valueMode?.value || 'literal',
            setIdentifier: els.identifier?.value ?? '$',
        })
        : '';
    return {
        name,
        modifier,
        variableName,
        description: `Definition ${name}`,
        dimensions: dimension,
        values: values.join(' | '),
    };
}

function suggestedDefinitionMeta() {
    const dimension = els.builderDimension?.value?.trim() || '';
    const values = selectedBuilderValues();
    const valueLabel = values.join(' + ');
    const name = [dimension, valueLabel].filter(Boolean).join(' ') || 'Definition';
    const variableName = `vDef${name.replace(/[^A-Za-z0-9]+/g, '_').replace(/^_+|_+$/g, '').replace(/^./, (char) => char.toUpperCase()) || 'Definition'}`;
    return { name, variableName };
}

function refreshDefinitionDraftFromSelection() {
    if (state.definitionDraftTouched) return;
    const suggestion = suggestedDefinitionMeta();
    if (els.definitionName) els.definitionName.value = suggestion.name;
    if (els.definitionVariable) els.definitionVariable.value = suggestion.variableName;
}

function showValueMessage(message, type = 'info') {
    if (!els.valueMessage) return;
    els.valueMessage.textContent = message;
    els.valueMessage.dataset.type = type;
    els.valueMessage.hidden = false;
}

function clearValueMessage() {
    if (!els.valueMessage) return;
    els.valueMessage.textContent = '';
    els.valueMessage.hidden = true;
}

function baseMeasuresCsvLine(base) {
    return [
        base.measureName || 'Measure',
        base.baseMeasure || 'Sum(Sales)',
        base.variablePrefix || 'vMeasure',
        base.baseDescription || '',
        base.baseDescriptionEn || '',
    ].map(csvCell).join(',');
}

function baseMeasureList() {
    return parseBaseMeasures(els.baseMeasures?.value || '');
}

function writeBaseMeasureList(measures) {
    if (!els.baseMeasures) return;
    els.baseMeasures.value = [
        'name,expression,prefix,description_de,description_en',
        ...measures.map(baseMeasuresCsvLine),
    ].join('\n');
}

function activeBaseFromForm() {
    return {
        measureName: els.measureName?.value?.trim() || els.measureField?.value?.trim() || 'Measure',
        baseMeasure: els.baseMeasure?.value?.trim() || 'Sum(Sales)',
        variablePrefix: els.variablePrefix?.value?.trim() || 'vMeasure',
        baseDescription: els.baseDescription?.value?.trim() || '',
        baseDescriptionEn: els.baseDescriptionEn?.value?.trim() || '',
    };
}

function downloadBlob(filename, blob) {
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = filename;
    document.body.appendChild(link);
    link.click();
    link.remove();
    URL.revokeObjectURL(url);
}

function downloadText(filename, content, mime = 'text/csv;charset=utf-8') {
    downloadBlob(filename, new Blob([`\uFEFF${content}`], { type: mime }));
}

function xmlEscape(value) {
    return String(value ?? '').replace(/[<>&'"]/g, (char) => ({
        '<': '&lt;',
        '>': '&gt;',
        '&': '&amp;',
        "'": '&apos;',
        '"': '&quot;',
    })[char]);
}

function columnName(index) {
    let name = '';
    let value = index + 1;
    while (value > 0) {
        const remainder = (value - 1) % 26;
        name = String.fromCharCode(65 + remainder) + name;
        value = Math.floor((value - 1) / 26);
    }
    return name;
}

function sheetXml(rows) {
    const body = rows.map((row, rowIndex) => {
        const cells = row.map((cell, cellIndex) => {
            const ref = `${columnName(cellIndex)}${rowIndex + 1}`;
            return `<c r="${ref}" t="inlineStr"><is><t>${xmlEscape(cell)}</t></is></c>`;
        }).join('');
        return `<row r="${rowIndex + 1}">${cells}</row>`;
    }).join('');
    return `<?xml version="1.0" encoding="UTF-8" standalone="yes"?><worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><sheetData>${body}</sheetData></worksheet>`;
}

function crc32(bytes) {
    let crc = -1;
    for (const byte of bytes) {
        crc ^= byte;
        for (let bit = 0; bit < 8; bit += 1) crc = (crc >>> 1) ^ (0xEDB88320 & -(crc & 1));
    }
    return (crc ^ -1) >>> 0;
}

function uint16(value) {
    return [value & 255, (value >>> 8) & 255];
}

function uint32(value) {
    return [value & 255, (value >>> 8) & 255, (value >>> 16) & 255, (value >>> 24) & 255];
}

function zipStore(files) {
    const encoder = new TextEncoder();
    const chunks = [];
    const central = [];
    let offset = 0;

    files.forEach(({ name, content }) => {
        const nameBytes = encoder.encode(name);
        const data = encoder.encode(content);
        const crc = crc32(data);
        const local = new Uint8Array([
            ...uint32(0x04034b50), ...uint16(20), ...uint16(0), ...uint16(0), ...uint16(0), ...uint16(0),
            ...uint32(crc), ...uint32(data.length), ...uint32(data.length), ...uint16(nameBytes.length), ...uint16(0),
            ...nameBytes,
        ]);
        chunks.push(local, data);
        central.push({ nameBytes, dataLength: data.length, crc, offset });
        offset += local.length + data.length;
    });

    const centralStart = offset;
    central.forEach((entry) => {
        const directory = new Uint8Array([
            ...uint32(0x02014b50), ...uint16(20), ...uint16(20), ...uint16(0), ...uint16(0), ...uint16(0), ...uint16(0),
            ...uint32(entry.crc), ...uint32(entry.dataLength), ...uint32(entry.dataLength), ...uint16(entry.nameBytes.length),
            ...uint16(0), ...uint16(0), ...uint16(0), ...uint16(0), ...uint32(0), ...uint32(entry.offset), ...entry.nameBytes,
        ]);
        chunks.push(directory);
        offset += directory.length;
    });

    const centralSize = offset - centralStart;
    chunks.push(new Uint8Array([
        ...uint32(0x06054b50), ...uint16(0), ...uint16(0), ...uint16(central.length), ...uint16(central.length),
        ...uint32(centralSize), ...uint32(centralStart), ...uint16(0),
    ]));
    return new Blob(chunks, { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
}

function workbookBlob(sheetName, rows) {
    const safeSheetName = xmlEscape(sheetName.slice(0, 31));
    return zipStore([
        {
            name: '[Content_Types].xml',
            content: '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/><Default Extension="xml" ContentType="application/xml"/><Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/><Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/></Types>',
        },
        {
            name: '_rels/.rels',
            content: '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/></Relationships>',
        },
        {
            name: 'xl/workbook.xml',
            content: `<?xml version="1.0" encoding="UTF-8" standalone="yes"?><workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"><sheets><sheet name="${safeSheetName}" sheetId="1" r:id="rId1"/></sheets></workbook>`,
        },
        {
            name: 'xl/_rels/workbook.xml.rels',
            content: '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/></Relationships>',
        },
        { name: 'xl/worksheets/sheet1.xml', content: sheetXml(rows) },
    ]);
}

function setVariableName(dimension, values) {
    const suffix = `${dimension}_${values[0] || 'Values'}`.replace(/[^A-Za-z0-9]+/g, '_').replace(/^_+|_+$/g, '');
    return `vSet${suffix.charAt(0).toUpperCase()}${suffix.slice(1)}`;
}

function insertAtCursor(target, text) {
    const start = target.selectionStart ?? target.value.length;
    const end = target.selectionEnd ?? target.value.length;
    target.value = `${target.value.slice(0, start)}${text}${target.value.slice(end)}`;
    target.selectionStart = start + text.length;
    target.selectionEnd = start + text.length;
    target.focus();
}

function firstMeasureField() {
    return parseFields(els.fields?.value || '').find((field) => /measure|currency|amount|zahl|betrag/i.test(`${field.type} ${field.tags}`))?.name
        || els.measureField?.value?.trim()
        || 'Sales';
}

function firstDateField() {
    return parseFields(els.fields?.value || '').find((field) => /date|datum|calendar/i.test(`${field.name} ${field.type} ${field.tags}`))?.name
        || els.dateField?.value?.trim()
        || 'Date';
}

function composedBaseMeasure() {
    return `${els.aggregation?.value || 'Sum'}(${qlikFieldName(firstMeasureField())})`;
}

function looksLikeGeneratedTimeKpi(expression) {
    return /\$\(vCurrentYear\)|\$\(vPreviousYear\)|YearStart\(|MonthStart\(|AddMonths\(|\)\s*-\s*Sum\(|\)\s*\/\s*Sum\(/i.test(expression);
}

function kpiExpression(type, baseOverride = null) {
    const base = baseOverride?.trim() || els.baseMeasure?.value?.trim() || composedBaseMeasure();
    const year = qlikFieldName(els.yearField?.value?.trim() || 'Year');
    const date = qlikFieldName(firstDateField());
    const cy = `${year}={'$(vCurrentYear)'}`;
    const py = `${year}={'$(vPreviousYear)'}`;
    const ytd = `${date}={">=$(=YearStart(Today()))<=$(=Today())"}`;
    const mtd = `${date}={">=$(=MonthStart(Today()))<=$(=Today())"}`;
    const last12 = `${date}={">=$(=AddMonths(MonthStart(Today()),-11))<=$(=MonthEnd(Today()))"}`;
    const options = { setIdentifier: els.identifier?.value ?? '$', expressionStyle: els.expressionStyle?.value || 'inner' };

    if (type === 'cy') return injectSetAnalysis(base, cy, options);
    if (type === 'py') return injectSetAnalysis(base, py, options);
    if (type === 'ytd') return injectSetAnalysis(base, ytd, options);
    if (type === 'mtd') return injectSetAnalysis(base, mtd, options);
    if (type === 'last12m') return injectSetAnalysis(base, last12, options);
    if (type === 'yoy') return `${injectSetAnalysis(base, cy, options)} - ${injectSetAnalysis(base, py, options)}`;
    if (type === 'yoyPct') return `(${injectSetAnalysis(base, cy, options)} - ${injectSetAnalysis(base, py, options)}) / ${injectSetAnalysis(base, py, options)}`;
    return base;
}

function applyFormulaToken(token) {
    if (!els.baseMeasure) return;
    if (token.kind === 'field') {
        rememberBeforeChange();
        resetKpiLock();
        insertAtCursor(els.baseMeasure, qlikFieldName(token.value));
    } else if (token.kind === 'variable') {
        rememberBeforeChange();
        resetKpiLock();
        insertAtCursor(els.baseMeasure, `$(${token.value})`);
    } else if (token.kind === 'kpi') {
        applyKpi(token.value);
    }
    render();
}

function applyKpi(type) {
    if (!els.baseMeasure || !type || type === state.activeKpi) return;
    rememberBeforeChange();
    const current = els.baseMeasure.value || '';
    const base = state.kpiBaseMeasure || (looksLikeGeneratedTimeKpi(current) ? composedBaseMeasure() : current || 'Sum(Sales)');
    state.kpiBaseMeasure = base;
    state.activeKpi = type;
    els.baseMeasure.value = kpiExpression(type, base);
}

function activeFormulaTarget() {
    return document.activeElement === els.searchExpression ? els.searchExpression : els.baseMeasure;
}

function appendFunctionSnippet(snippet) {
    const target = activeFormulaTarget();
    if (!target) return;
    if (target === els.baseMeasure) {
        rememberBeforeChange();
        resetKpiLock();
    }
    insertAtCursor(target, snippet);
}

function appendSearchFilter() {
    if (!els.rows) return;
    const dimension = els.builderDimension?.value?.trim() || 'Customer';
    const expression = els.searchExpression?.value?.trim() || '=Sum(Sales)>1000';
    const normalized = expression.startsWith('=') ? expression : `=${expression}`;
    const label = els.builderLabel?.value?.trim() || 'Formula search';
    els.rows.value = `${els.rows.value.trim()}\n${dimension},${normalized},${label},search`;
}

function appendHierarchyLevel(field) {
    if (!els.hierarchyLevels || !field) return;
    const tree = hierarchyTree();
    if (findHierarchyNode(tree, field)) return;
    tree.push({ name: field, children: [] });
    writeHierarchyTree(tree);
}

function hierarchyTree() {
    const root = [];
    const stack = [{ depth: -1, children: root }];
    return (els.hierarchyLevels?.value || '')
        .split(/\r?\n/)
        .map((line) => {
            const name = line.trim();
            if (!name) return null;
            return { depth: Math.max(0, Math.floor((line.match(/^\s*/)?.[0].length || 0) / 2)), name };
        })
        .filter(Boolean)
        .reduce((tree, item) => {
            while (stack.length > 1 && stack[stack.length - 1].depth >= item.depth) stack.pop();
            const node = { name: item.name, children: [] };
            stack[stack.length - 1].children.push(node);
            stack.push({ depth: item.depth, children: node.children });
            return tree;
        }, root);
}

function flattenHierarchyTree(nodes) {
    return nodes.flatMap((node) => [node.name, ...flattenHierarchyTree(node.children || [])]);
}

function hierarchyLevels() {
    return flattenHierarchyTree(hierarchyTree());
}

function serializeHierarchyTree(nodes, depth = 0) {
    return nodes.flatMap((node) => [
        `${'  '.repeat(depth)}${node.name}`,
        ...serializeHierarchyTree(node.children || [], depth + 1),
    ]);
}

function writeHierarchyTree(nodes) {
    if (!els.hierarchyLevels) return;
    els.hierarchyLevels.value = serializeHierarchyTree(nodes).join('\n');
}

function findHierarchyNode(nodes, name) {
    for (const node of nodes) {
        if (node.name === name) return node;
        const child = findHierarchyNode(node.children || [], name);
        if (child) return child;
    }
    return null;
}

function removeHierarchyNode(nodes, name) {
    const index = nodes.findIndex((node) => node.name === name);
    if (index >= 0) return nodes.splice(index, 1)[0];
    for (const node of nodes) {
        const removed = removeHierarchyNode(node.children || [], name);
        if (removed) return removed;
    }
    return null;
}

function addHierarchyNode(targetParent = '', nextLevelOverride = '') {
    const nextLevel = nextLevelOverride || els.builderDimension?.value?.trim() || '';
    if (!nextLevel || !els.hierarchyLevels) return;

    const tree = hierarchyTree();
    const movingNode = removeHierarchyNode(tree, nextLevel) || { name: nextLevel, children: [] };
    if (targetParent && targetParent === movingNode.name) {
        tree.push(movingNode);
        writeHierarchyTree(tree);
        return;
    }
    const parent = targetParent ? findHierarchyNode(tree, targetParent) : null;
    (parent?.children || tree).push(movingNode);
    writeHierarchyTree(tree);
}

function removeHierarchyLevel(name) {
    const tree = hierarchyTree();
    removeHierarchyNode(tree, name);
    writeHierarchyTree(tree);
}

function valueChipsForDimension(rows, dimension) {
    const values = rows
        .filter((row) => row.dimension === dimension)
        .map((row) => row.label || row.value)
        .filter(Boolean);
    if (values.length === 0) {
        return `<div class="qlik-set-tree-values qlik-set-tree-values--empty"><span>${escapeHtml(t(getLocale(), 'qlikSet.tree.noValues'))}</span></div>`;
    }
    const previewLimit = 4;
    const previewValues = values.slice(0, previewLimit);
    const hiddenCount = values.length - previewValues.length;
    const moreLabel = hiddenCount > 0
        ? `<span class="qlik-set-tree-values__more">+${hiddenCount} ${escapeHtml(t(getLocale(), 'qlikSet.tree.moreValues'))}</span>`
        : '';
    return `<div class="qlik-set-tree-values">${previewValues.map((value) => `<span>${escapeHtml(value)}</span>`).join('')}${moreLabel}</div>`;
}

function hierarchySlotLabel(parentLevel) {
    const locale = getLocale();
    return parentLevel
        ? t(locale, 'qlikSet.tree.dropOnNode').replace('{level}', parentLevel)
        : t(locale, 'qlikSet.tree.dropOnBase');
}

function renderHierarchySlot(parentLevel = '', extraClass = '') {
    const label = hierarchySlotLabel(parentLevel);
    return `<div class="qlik-set-tree-slot ${extraClass}" data-qlik-tree-parent="${escapeHtml(parentLevel)}"><span aria-hidden="true">+</span>${escapeHtml(label)}</div>`;
}

function renderHierarchyBranches(nodes, rows) {
    if (nodes.length === 0) return '';
    const removeLabel = t(getLocale(), 'qlikSet.tree.remove');
    return `
        <ul>
            ${nodes.map((node) => `
                <li>
                    <div class="qlik-set-tree-node" draggable="true" data-qlik-tree-level="${escapeHtml(node.name)}" data-qlik-tree-parent="${escapeHtml(node.name)}" title="${escapeHtml(hierarchySlotLabel(node.name))}">
                        <span>${escapeHtml(node.name)}</span>
                        <span class="qlik-set-tree-actions">
                            <button type="button" class="qlik-set-tree-action" data-qlik-tree-remove="${escapeHtml(node.name)}">${escapeHtml(removeLabel)}</button>
                        </span>
                    </div>
                    ${valueChipsForDimension(rows, node.name)}
                    ${renderHierarchyBranches(node.children || [], rows)}
                </li>
            `).join('')}
        </ul>
    `;
}

function appendSetVariable() {
    if (!els.vars) return;
    const dimension = els.builderDimension?.value?.trim() || 'Region';
    const explicitValues = (els.setVarValues?.value || '')
        .split(/[,\n;]/)
        .map((value) => value.trim())
        .filter(Boolean);
    const values = explicitValues.length ? explicitValues : selectedBuilderValues();
    if (values.length === 0) return;

    const name = els.setVarName?.value?.trim() || setVariableName(dimension, values);
    const definition = `${qlikFieldName(dimension)}={${values.map(qlikSingleQuoted).join(',')}}`;
    const description = `Set modifier for ${dimension}: ${values.join(', ')}`;
    const line = [name, definition, description].map(csvCell).join(',');
    els.vars.value = `${els.vars.value.trim()}\n${line}`;
}

function parseDragToken(event) {
    try {
        const json = event.dataTransfer?.getData('application/json') || '';
        if (json) return JSON.parse(json);
    } catch {
        // Fall through to text/plain for browsers that strip custom drag MIME types.
    }
    const textValue = event.dataTransfer?.getData('text/plain') || '';
    return textValue ? { kind: 'field', value: textValue } : {};
}

function fieldCatalogType(field) {
    const explicitType = `${field.type || ''}`.toLowerCase();
    if (/measure/.test(explicitType)) return 'measure';
    if (/date|datum|calendar|time/.test(explicitType)) return 'date';
    if (/dimension|dim/.test(explicitType)) return 'dimension';

    const haystack = `${field.name} ${field.tags}`.toLowerCase();
    if (/date|datum|calendar|year|month|quarter|week|time/.test(haystack)) return 'date';
    if (/amount|zahl|betrag|sales|revenue|umsatz|margin|cost|count/.test(haystack)) return 'measure';
    if (/geo|customer|region|channel|product|salesperson|category|segment|country|city/.test(haystack)) return 'dimension';
    return 'other';
}

function fieldChip(field, type) {
    const typeLabel = t(getLocale(), `qlikSet.catalog.${type}`);
    return `
        <button type="button" class="qlik-set-chip qlik-set-chip--${escapeHtml(type)}" draggable="true" data-qlik-field="${escapeHtml(field.name)}" data-qlik-field-type="${escapeHtml(field.type || type)}" title="${escapeHtml(typeLabel)}">
            <span class="qlik-set-chip__icon qlik-set-chip__icon--${escapeHtml(type)}" aria-hidden="true"></span>
            <span>${escapeHtml(field.name)}</span>
        </button>
    `;
}

function renderFieldGroup(type, fields) {
    if (fields.length === 0) return '';
    const label = t(getLocale(), `qlikSet.catalog.${type}`);
    return `
        <section class="qlik-set-catalog-group">
            <h4>${escapeHtml(label)}</h4>
            <div class="qlik-set-catalog-group__chips">
                ${fields.map((field) => fieldChip(field, type)).join('')}
            </div>
        </section>
    `;
}

function renderGeneratedMeasureList(outputs) {
    const rows = (outputs.measureRows || []).slice(1, 51);
    if (rows.length === 0) {
        return `<p class="qlik-set-small">${escapeHtml(t(getLocale(), 'qlikSet.catalog.noGenerated'))}</p>`;
    }
    return rows.map(([name, expression, description]) => `
        <div class="qlik-set-generated-item">
            <strong>${escapeHtml(name || 'Measure')}</strong>
            <code>${escapeHtml(expression || '')}</code>
            ${description ? `<span>${escapeHtml(description.split('\n')[0])}</span>` : ''}
        </div>
    `).join('');
}

function closestTreeDropSlot(event) {
    const directSlot = event.target.closest?.('[data-qlik-tree-parent]');
    if (directSlot) return directSlot;

    const slots = Array.from(els.treePreview?.querySelectorAll('[data-qlik-tree-parent]') || []);
    if (slots.length === 0) return null;

    const y = event.clientY;
    return slots.reduce((best, slot) => {
        const rect = slot.getBoundingClientRect();
        const distance = Math.abs(y - (rect.top + rect.height / 2));
        return !best || distance < best.distance ? { slot, distance } : best;
    }, null)?.slot || null;
}

function clearTreeDropHighlights() {
    els.treePreview?.querySelectorAll('.is-drag-over').forEach((slot) => {
        slot.classList.remove('is-drag-over');
    });
}

function renderCatalog() {
    const fields = parseFields(els.fields?.value || '');
    const variables = parseVariables(els.vars?.value || '');
    const rows = parseCsv(els.rows?.value || '');

    if (els.fieldOptions) {
        els.fieldOptions.innerHTML = fields.map((field) => `<option value="${escapeHtml(field.name)}"></option>`).join('');
    }
    if (els.builderDimension && els.builderDimension.tagName === 'SELECT') {
        const currentValue = els.builderDimension.value || '';
        const dimensions = fields
            .filter((field) => fieldCatalogType(field) === 'dimension')
            .map((field) => field.name)
            .filter((name, index, names) => names.indexOf(name) === index);
        const options = currentValue && !dimensions.includes(currentValue) ? [currentValue, ...dimensions] : dimensions;
        els.builderDimension.innerHTML = options
            .map((name) => `<option value="${escapeHtml(name)}">${escapeHtml(name)}</option>`)
            .join('');
        els.builderDimension.value = options.includes(currentValue) ? currentValue : (options[0] || '');
    }
    if (els.builderValue && els.builderValue.tagName === 'SELECT') {
        const selectedDimension = els.builderDimension?.value || 'Region';
        const currentValues = selectedBuilderValues();
        const values = rows
            .filter((row) => row.dimension === selectedDimension)
            .map((row) => row.value)
            .filter((value, index, list) => list.indexOf(value) === index);
        els.builderValue.innerHTML = (values.length ? values : [''])
            .map((value) => `<option value="${escapeHtml(value)}">${escapeHtml(value || t(getLocale(), 'qlikSet.builder.noValues'))}</option>`)
            .join('');
        const validValues = currentValues.filter((value) => values.includes(value));
        const nextValues = validValues.length ? validValues : values.slice(0, 1);
        Array.from(els.builderValue.options).forEach((option) => {
            option.selected = nextValues.includes(option.value);
        });
    }
    refreshDefinitionDraftFromSelection();
    if (els.valueDimension) {
        const currentValue = els.valueDimension.value || els.builderDimension?.value || '';
        const dimensions = fields
            .filter((field) => fieldCatalogType(field) === 'dimension')
            .map((field) => field.name)
            .filter((name, index, names) => names.indexOf(name) === index);
        const rowDimensions = rows
            .map((row) => row.dimension)
            .filter((name, index, names) => name && names.indexOf(name) === index);
        const options = [...new Set([...dimensions, ...rowDimensions])];
        els.valueDimension.innerHTML = options
            .map((name) => `<option value="${escapeHtml(name)}">${escapeHtml(name)}</option>`)
            .join('');
        els.valueDimension.value = options.includes(currentValue) ? currentValue : (options[0] || '');
    }
    if (els.valueList) {
        const groupedRows = rows.reduce((groups, row) => {
            if (!groups[row.dimension]) groups[row.dimension] = [];
            groups[row.dimension].push(row);
            return groups;
        }, {});
        const dimensionNames = Object.keys(groupedRows).sort((a, b) => a.localeCompare(b));
        els.valueList.innerHTML = dimensionNames.length
            ? dimensionNames.map((dimension) => `
                <section class="qlik-set-value-group">
                    <h5>${escapeHtml(dimension)}</h5>
                    <div class="qlik-set-value-group__chips">
                        ${groupedRows[dimension].map((row) => `
                            <span class="qlik-set-chip qlik-set-chip--dimension" title="${escapeHtml(row.label || row.value)}">
                                <span class="qlik-set-chip__icon qlik-set-chip__icon--dimension" aria-hidden="true"></span>
                                <span>${escapeHtml(row.value)}${row.label && row.label !== row.value ? ` · ${escapeHtml(row.label)}` : ''}</span>
                            </span>
                        `).join('')}
                    </div>
                </section>
            `).join('')
            : `<p class="qlik-set-small">${escapeHtml(t(getLocale(), 'qlikSet.values.empty'))}</p>`;
    }
    const definitionDraft = selectedDefinitionDraft();
    if (els.definitionPreviewPre) {
        els.definitionPreviewPre.textContent = definitionDraft.modifier || t(getLocale(), 'qlikSet.definitions.noPreview');
    }
    if (els.definitionList) {
        const definitions = parseDefinitions(els.definitions?.value || '');
        els.definitionList.innerHTML = definitions.length
            ? definitions.map((definition) => `
                <div class="qlik-set-generated-item">
                    <strong>${escapeHtml(definition.name)}</strong>
                    <code>${escapeHtml(definition.variableName ? `$(${definition.variableName})` : definition.modifier)}</code>
                    <span>${escapeHtml(definition.modifier)}</span>
                </div>
            `).join('')
            : `<p class="qlik-set-small">${escapeHtml(t(getLocale(), 'qlikSet.definitions.empty'))}</p>`;
    }
    if (els.variableOptions) {
        els.variableOptions.innerHTML = variables.map((variable) => `<option value="${escapeHtml(variable.name)}"></option>`).join('');
    }
    if (els.baseMeasureSelect) {
        const current = els.baseMeasureSelect.value || els.measureName?.value?.trim() || '';
        const bases = baseMeasureList();
        els.baseMeasureSelect.innerHTML = bases.length
            ? bases.map((base) => `<option value="${escapeHtml(base.measureName)}">${escapeHtml(base.measureName)} - ${escapeHtml(base.baseMeasure)}</option>`).join('')
            : `<option value="">${escapeHtml(t(getLocale(), 'qlikSet.baseList.empty'))}</option>`;
        if (bases.some((base) => base.measureName === current)) els.baseMeasureSelect.value = current;
    }
    const groupedFields = fields.slice(0, 120).reduce((groups, field) => {
        groups[fieldCatalogType(field)].push(field);
        return groups;
    }, { dimension: [], measure: [], date: [], other: [] });
    if (els.dimensionChips) {
        els.dimensionChips.innerHTML = [
            renderFieldGroup('dimension', groupedFields.dimension),
            renderFieldGroup('date', groupedFields.date),
            renderFieldGroup('other', groupedFields.other),
        ].join('');
    }
    if (els.measureChips) {
        els.measureChips.innerHTML = renderFieldGroup('measure', groupedFields.measure);
    }
    if (els.variableChips) {
        els.variableChips.innerHTML = variables.slice(0, 40).map((variable) => `<button type="button" class="qlik-set-chip qlik-set-chip--variable" draggable="true" data-qlik-variable="${escapeHtml(variable.name)}" data-qlik-variable-definition="${escapeHtml(variable.definition)}"><span class="qlik-set-chip__icon qlik-set-chip__icon--variable" aria-hidden="true"></span><span>${escapeHtml(variable.name)}</span></button>`).join('');
    }
    if (els.treePreview) {
        const rows = parseCsv(els.rows?.value || '');
        const tree = hierarchyTree();
        const selectedDimension = els.builderDimension?.value?.trim() || 'Dimension';
        const currentDimensionLabel = t(getLocale(), 'qlikSet.tree.currentDimension');
        const baseLabel = t(getLocale(), 'qlikSet.tree.base');
        els.treePreview.innerHTML = `
            <div class="qlik-set-tree-context">
                <span>${escapeHtml(currentDimensionLabel)}</span>
                <strong>${escapeHtml(selectedDimension)}</strong>
            </div>
            <ul class="qlik-set-tree-list">
                <li>
                    <div class="qlik-set-tree-node qlik-set-tree-node--root"><span>${escapeHtml(baseLabel)}</span></div>
                    ${renderHierarchySlot('', 'qlik-set-tree-slot--root')}
                    ${renderHierarchyBranches(tree, rows)}
                </li>
            </ul>
        `;
    }
    if (els.hierarchyPreview) {
        const levels = (els.hierarchyLevels?.value || '').split(/\r?\n|>|,/).map((value) => value.trim()).filter(Boolean);
        els.hierarchyPreview.innerHTML = ['Base', ...levels].map((level, index) => {
            const arrow = index < levels.length ? '<span class="qlik-set-hierarchy-arrow">-&gt;</span>' : '';
            return `<span class="qlik-set-hierarchy-node">${escapeHtml(level)}</span>${arrow}`;
        }).join('');
    }
}

function render() {
    renderCatalog();
    const outputs = buildOutputs();
    if (els.generatedMeasures) els.generatedMeasures.innerHTML = renderGeneratedMeasureList(outputs);
    app.querySelectorAll('[data-qlik-kpi]').forEach((button) => {
        const active = button.getAttribute('data-qlik-kpi') === state.activeKpi;
        button.classList.toggle('is-active', active);
        button.disabled = active;
        button.setAttribute('aria-pressed', active ? 'true' : 'false');
    });
    if (els.kpiState) {
        const key = state.activeKpi ? `qlikSet.kpis.${state.activeKpi}` : 'qlikSet.kpis.none';
        const label = t(getLocale(), key);
        els.kpiState.textContent = state.activeKpi
            ? `${t(getLocale(), 'qlikSet.kpis.active')}: ${label}`
            : label;
    }
    updateHistoryButtons();
    if (els.currentFormulaPre) els.currentFormulaPre.textContent = currentFormula();
    if (els.measuresPre) els.measuresPre.textContent = outputs.measures;
    if (els.variablesPre) els.variablesPre.textContent = outputs.variables;
    if (els.timeVarsPre) els.timeVarsPre.textContent = outputs.timeVariables;
    if (els.modifiersPre) els.modifiersPre.textContent = outputs.modifiers;
    if (els.nestedIfPre) els.nestedIfPre.textContent = outputs.nestedIf;
    if (els.pickMatchPre) els.pickMatchPre.textContent = outputs.pickMatch;
    if (els.csvPre) els.csvPre.textContent = outputs.csv;
}

app.querySelectorAll('input, select, textarea').forEach((el) => {
    el.addEventListener('focus', () => {
        const key = controlKey(el);
        if (formulaHistoryControlIds.has(key)) state.focusSnapshot = snapshotForm('formula');
    });
    el.addEventListener('input', () => {
        if (el === els.baseMeasure) resetKpiLock();
        if ([els.definitionName, els.definitionVariable].includes(el)) state.definitionDraftTouched = true;
        if ([els.builderDimension, els.builderValue, els.builderLabel].includes(el)) clearBuilderMessage();
        if ([els.valueDimension, els.valueValue, els.valueLabel, els.valueOperator].includes(el)) clearValueMessage();
        render();
    });
    el.addEventListener('change', () => {
        if (el === els.baseMeasure) resetKpiLock();
        if ([els.builderDimension, els.builderValue].includes(el)) state.definitionDraftTouched = false;
        if ([els.definitionName, els.definitionVariable].includes(el)) state.definitionDraftTouched = true;
        if ([els.builderDimension, els.builderValue, els.builderLabel].includes(el)) clearBuilderMessage();
        if ([els.valueDimension, els.valueValue, els.valueLabel, els.valueOperator].includes(el)) clearValueMessage();
        render();
    });
    el.addEventListener('blur', () => {
        const key = controlKey(el);
        if (!formulaHistoryControlIds.has(key) || !state.focusSnapshot) return;
        const current = snapshotForm('formula');
        if (!snapshotEquals(state.focusSnapshot, current)) pushFormulaSnapshot(state.focusSnapshot);
        state.focusSnapshot = null;
    });
});

app.querySelectorAll('[data-qlik-help-tab]').forEach((tab) => {
    tab.addEventListener('click', () => {
        const target = tab.getAttribute('data-qlik-help-tab');
        app.querySelectorAll('[data-qlik-help-tab]').forEach((el) => el.classList.toggle('is-active', el === tab));
        app.querySelectorAll('[data-qlik-help-panel]').forEach((panel) => {
            const active = panel.getAttribute('data-qlik-help-panel') === target;
            panel.classList.toggle('is-active', active);
            panel.toggleAttribute('hidden', !active);
        });
    });
});

app.querySelectorAll('[data-qlik-builder-tab]').forEach((tab) => {
    tab.addEventListener('click', () => {
        const target = tab.getAttribute('data-qlik-builder-tab');
        app.querySelectorAll('[data-qlik-builder-tab]').forEach((el) => {
            const active = el === tab;
            el.classList.toggle('is-active', active);
            el.setAttribute('aria-selected', active ? 'true' : 'false');
        });
        app.querySelectorAll('[data-qlik-builder-panel]').forEach((panel) => {
            const active = panel.getAttribute('data-qlik-builder-panel') === target;
            panel.classList.toggle('is-active', active);
            panel.toggleAttribute('hidden', !active);
        });
    });
});

app.querySelectorAll('[data-qlik-catalog-tab]').forEach((tab) => {
    tab.addEventListener('click', () => {
        const target = tab.getAttribute('data-qlik-catalog-tab');
        app.querySelectorAll('[data-qlik-catalog-tab]').forEach((el) => {
            const active = el === tab;
            el.classList.toggle('is-active', active);
            el.setAttribute('aria-selected', active ? 'true' : 'false');
        });
        app.querySelectorAll('[data-qlik-catalog-panel]').forEach((panel) => {
            const active = panel.getAttribute('data-qlik-catalog-panel') === target;
            panel.classList.toggle('is-active', active);
            panel.toggleAttribute('hidden', !active);
        });
    });
});

app.querySelectorAll('[data-qlik-import-tab]').forEach((tab) => {
    tab.addEventListener('click', () => {
        const target = tab.getAttribute('data-qlik-import-tab');
        app.querySelectorAll('[data-qlik-import-tab]').forEach((el) => {
            const active = el === tab;
            el.classList.toggle('is-active', active);
            el.setAttribute('aria-selected', active ? 'true' : 'false');
        });
        app.querySelectorAll('[data-qlik-import-panel]').forEach((panel) => {
            const active = panel.getAttribute('data-qlik-import-panel') === target;
            panel.classList.toggle('is-active', active);
            panel.toggleAttribute('hidden', !active);
        });
    });
});

els.helpToggle?.addEventListener('click', () => {
    const expanded = els.helpToggle.getAttribute('aria-expanded') === 'true';
    els.helpToggle.setAttribute('aria-expanded', expanded ? 'false' : 'true');
    els.helpBody?.toggleAttribute('hidden', expanded);
    els.helpSection?.classList.toggle('is-collapsed', expanded);
    updateHelpToggleLabel();
});

els.paletteToggle?.addEventListener('click', () => {
    const expanded = els.paletteToggle.getAttribute('aria-expanded') !== 'false';
    els.paletteToggle.setAttribute('aria-expanded', expanded ? 'false' : 'true');
    els.paletteBody?.toggleAttribute('hidden', expanded);
    updatePaletteToggleLabel();
});

els.descriptionToggle?.addEventListener('click', () => {
    const expanded = els.descriptionToggle.getAttribute('aria-expanded') === 'true';
    els.descriptionToggle.setAttribute('aria-expanded', expanded ? 'false' : 'true');
    els.descriptionBody?.toggleAttribute('hidden', expanded);
    updateDescriptionToggleLabel();
});

els.baseListToggle?.addEventListener('click', () => {
    const expanded = els.baseListToggle.getAttribute('aria-expanded') === 'true';
    els.baseListToggle.setAttribute('aria-expanded', expanded ? 'false' : 'true');
    els.baseListBody?.toggleAttribute('hidden', expanded);
    updateBaseListToggleLabel();
});

els.workbenchToggle?.addEventListener('click', () => {
    const expanded = els.workbenchToggle.getAttribute('aria-expanded') !== 'false';
    els.workbenchToggle.setAttribute('aria-expanded', expanded ? 'false' : 'true');
    els.workbenchBody?.toggleAttribute('hidden', expanded);
    updateWorkbenchToggleLabel();
});

els.saveApp?.addEventListener('click', () => {
    const name = els.appName?.value?.trim() || '';
    if (!name) {
        showAppMessage(t(getLocale(), 'qlikSet.apps.missingName'), 'error');
        return;
    }
    const savedApps = readSavedApps();
    savedApps[name] = {
        savedAt: new Date().toISOString(),
        snapshot: snapshotForm(),
    };
    writeSavedApps(savedApps);
    refreshSavedAppsSelect(name);
    showAppMessage(t(getLocale(), 'qlikSet.apps.savedMessage').replace('{name}', name));
});

els.loadApp?.addEventListener('click', () => {
    const name = els.savedApps?.value || '';
    const savedApp = readSavedApps()[name];
    if (!savedApp?.snapshot) {
        showAppMessage(t(getLocale(), 'qlikSet.apps.missingSelection'), 'error');
        return;
    }
    restoreSnapshot(savedApp.snapshot);
    if (els.appName) els.appName.value = name;
    refreshSavedAppsSelect(name);
    clearAppMessage();
    render();
});

els.deleteApp?.addEventListener('click', () => {
    const name = els.savedApps?.value || '';
    if (!name) {
        showAppMessage(t(getLocale(), 'qlikSet.apps.missingSelection'), 'error');
        return;
    }
    const savedApps = readSavedApps();
    delete savedApps[name];
    writeSavedApps(savedApps);
    refreshSavedAppsSelect();
    showAppMessage(t(getLocale(), 'qlikSet.apps.deletedMessage').replace('{name}', name));
});

app.querySelectorAll('[data-qlik-layout-toggle]').forEach((button) => {
    const column = button.getAttribute('data-qlik-layout-toggle');
    if (!column) return;
    button.setAttribute('aria-pressed', 'true');
    button.addEventListener('click', () => {
        setWorkbenchColumn(column, !workbenchColumnOpen(column));
    });
});

app.querySelectorAll('[data-qlik-layout-preset]').forEach((button) => {
    button.addEventListener('click', () => {
        applyWorkbenchPreset(button.getAttribute('data-qlik-layout-preset') || 'all');
    });
});

app.addEventListener('dragstart', (event) => {
    const field = event.target.closest?.('[data-qlik-field]');
    const variable = event.target.closest?.('[data-qlik-variable]');
    const treeLevel = event.target.closest?.('[data-qlik-tree-level]');
    const payload = field
        ? { kind: 'field', value: field.getAttribute('data-qlik-field'), type: field.getAttribute('data-qlik-field-type') }
        : variable
            ? { kind: 'variable', value: variable.getAttribute('data-qlik-variable') }
            : treeLevel
                ? { kind: 'hierarchy-level', value: treeLevel.getAttribute('data-qlik-tree-level') }
            : null;
    if (!payload) return;
    treeLevel?.classList.add('is-dragging');
    event.dataTransfer.setData('application/json', JSON.stringify(payload));
    event.dataTransfer.setData('text/plain', payload.value || '');
    event.dataTransfer.effectAllowed = payload.kind === 'hierarchy-level' ? 'move' : 'copy';
});

app.addEventListener('dragend', () => {
    app.querySelectorAll('.is-dragging').forEach((element) => {
        element.classList.remove('is-dragging');
    });
    clearTreeDropHighlights();
});

app.querySelectorAll('[data-qlik-dropzone]').forEach((dropzone) => {
    dropzone.addEventListener('dragover', (event) => {
        event.preventDefault();
        dropzone.classList.add('is-drag-over');
    });
    dropzone.addEventListener('dragleave', () => {
        dropzone.classList.remove('is-drag-over');
    });
    dropzone.addEventListener('drop', (event) => {
        event.preventDefault();
        dropzone.classList.remove('is-drag-over');
        const token = parseDragToken(event);
        if (dropzone.getAttribute('data-qlik-dropzone') === 'filter') {
            if (token.kind === 'field') {
                setBuilderDimension(token.value);
                clearBuilderMessage();
            }
        } else if (dropzone.getAttribute('data-qlik-dropzone') === 'hierarchy') {
            if (token.kind === 'field') {
                setBuilderDimension(token.value);
                appendHierarchyLevel(token.value);
            }
        } else {
            applyFormulaToken(token);
        }
        render();
    });
});

app.querySelectorAll('[data-qlik-kpi]').forEach((button) => {
    button.addEventListener('click', () => applyFormulaToken({ kind: 'kpi', value: button.getAttribute('data-qlik-kpi') }));
});

app.querySelectorAll('[data-qlik-function]').forEach((button) => {
    button.addEventListener('click', () => {
        appendFunctionSnippet(button.getAttribute('data-qlik-function') || '');
        render();
    });
});

app.querySelectorAll('[data-qlik-set-search]').forEach((button) => {
    button.addEventListener('click', () => {
        if (els.searchExpression) els.searchExpression.value = button.getAttribute('data-qlik-set-search') || '';
        render();
    });
});

els.importModalOpen?.addEventListener('click', () => {
    if (typeof els.importModal?.showModal === 'function') {
        els.importModal.showModal();
    }
});

els.importModalClose?.addEventListener('click', () => {
    els.importModal?.close();
});

els.importModal?.addEventListener('click', (event) => {
    if (event.target === els.importModal) els.importModal.close();
});

els.csvFile?.addEventListener('change', async () => {
    const file = els.csvFile.files?.[0];
    if (!file || !els.rows) return;
    els.rows.value = await file.text();
    render();
});

els.fieldsFile?.addEventListener('change', async () => {
    const file = els.fieldsFile.files?.[0];
    if (!file || !els.fields) return;
    els.fields.value = await file.text();
    render();
});

els.varsFile?.addEventListener('change', async () => {
    const file = els.varsFile.files?.[0];
    if (!file || !els.vars) return;
    els.vars.value = await file.text();
    render();
});

els.addValueRow?.addEventListener('click', () => {
    if (!els.rows) return;
    const dimension = els.valueDimension?.value?.trim() || '';
    const value = els.valueValue?.value?.trim() || '';
    const label = els.valueLabel?.value?.trim() || value;
    const operator = els.valueOperator?.value?.trim() || '';
    if (!dimension || !value) {
        showValueMessage(t(getLocale(), 'qlikSet.values.missing'), 'error');
        return;
    }
    const rows = parseCsv(els.rows.value || '');
    const duplicate = rows.some((row) => row.dimension.toLowerCase() === dimension.toLowerCase() && row.value.toLowerCase() === value.toLowerCase());
    if (duplicate) {
        showValueMessage(t(getLocale(), 'qlikSet.builder.duplicate'), 'error');
        return;
    }
    writeDimensionValues([...rows, { dimension, value, label, operator, assignment: '' }]);
    if (els.builderDimension) setBuilderDimension(dimension);
    if (els.valueValue) els.valueValue.value = '';
    if (els.valueLabel) els.valueLabel.value = '';
    showValueMessage(t(getLocale(), 'qlikSet.values.added'));
    render();
});

els.applyBase?.addEventListener('click', () => {
    const aggregation = els.aggregation?.value || 'Sum';
    const field = els.measureField?.value?.trim() || 'Sales';
    if (els.baseMeasure) {
        rememberBeforeChange();
        resetKpiLock();
        els.baseMeasure.value = `${aggregation}(${qlikFieldName(field)})`;
    }
    render();
});

els.addCurrentBase?.addEventListener('click', () => {
    if (!els.baseMeasures) return;
    const current = activeBaseFromForm();
    const measures = baseMeasureList();
    const nextMeasures = measures.filter((measure) => measure.measureName !== current.measureName);
    nextMeasures.push(current);
    writeBaseMeasureList(nextMeasures);
    if (els.baseMeasureSelect) els.baseMeasureSelect.value = current.measureName;
    render();
});

els.loadBase?.addEventListener('click', () => {
    const selected = els.baseMeasureSelect?.value || '';
    const base = baseMeasureList().find((measure) => measure.measureName === selected);
    if (!base) return;
    rememberBeforeChange();
    resetKpiLock();
    if (els.measureName) els.measureName.value = base.measureName;
    if (els.baseMeasure) els.baseMeasure.value = base.baseMeasure;
    if (els.variablePrefix) els.variablePrefix.value = base.variablePrefix || `v${base.measureName}`;
    if (els.baseDescription) els.baseDescription.value = base.baseDescription || '';
    if (els.baseDescriptionEn) els.baseDescriptionEn.value = base.baseDescriptionEn || '';
    render();
});

els.newBase?.addEventListener('click', () => {
    rememberBeforeChange();
    resetKpiLock();
    if (els.measureName) els.measureName.value = 'Margin';
    if (els.baseMeasure) els.baseMeasure.value = 'Sum(Margin)';
    if (els.variablePrefix) els.variablePrefix.value = 'vMargin';
    if (els.baseDescription) els.baseDescription.value = 'Marge basierend auf Sum(Margin).';
    if (els.baseDescriptionEn) els.baseDescriptionEn.value = 'Margin based on Sum(Margin).';
    render();
});

els.applySetFilter?.addEventListener('click', () => {
    if (!els.definitions) return;
    const draft = selectedDefinitionDraft();
    if (!draft.name || !draft.modifier) {
        showBuilderMessage(t(getLocale(), 'qlikSet.builder.missingSetFilter'), 'error');
        return;
    }
    const definitions = parseDefinitions(els.definitions.value || '');
    const nextDefinitions = definitions.filter((definition) => definition.name !== draft.name);
    nextDefinitions.push(draft);
    writeDefinitions(nextDefinitions);
    state.definitionDraftTouched = false;
    clearBuilderMessage();
    showBuilderMessage(t(getLocale(), 'qlikSet.definitions.saved'));
    render();
});

els.treePreview?.addEventListener('click', (event) => {
    const removeButton = event.target.closest('[data-qlik-tree-remove]');
    if (removeButton) {
        removeHierarchyLevel(removeButton.getAttribute('data-qlik-tree-remove') || '');
        render();
    }
});

els.treePreview?.addEventListener('dragover', (event) => {
    const slot = closestTreeDropSlot(event);
    if (!slot) return;
    event.preventDefault();
    if (event.dataTransfer) event.dataTransfer.dropEffect = app.querySelector('.qlik-set-tree-node.is-dragging') ? 'move' : 'copy';
    clearTreeDropHighlights();
    slot.classList.add('is-drag-over');
});

els.treePreview?.addEventListener('dragleave', (event) => {
    if (!els.treePreview.contains(event.relatedTarget)) clearTreeDropHighlights();
});

els.treePreview?.addEventListener('drop', (event) => {
    const slot = closestTreeDropSlot(event);
    if (!slot) return;
    event.preventDefault();
    clearTreeDropHighlights();
    const token = parseDragToken(event);
    if (token.kind !== 'field' && token.kind !== 'hierarchy-level') return;
    const parent = slot.getAttribute('data-qlik-tree-parent') || '';
    if (parent && parent === token.value) return;
    setBuilderDimension(token.value);
    addHierarchyNode(parent, token.value);
    render();
});

els.catalogRail?.addEventListener('click', (event) => {
    const button = event.target.closest('[data-qlik-field]');
    const variableButton = event.target.closest('[data-qlik-variable]');

    if (button) {
        const field = button.getAttribute('data-qlik-field') || '';
        const type = (button.getAttribute('data-qlik-field-type') || '').toLowerCase();

        if (type.includes('measure') && els.measureField) {
            rememberBeforeChange();
            els.measureField.value = field;
        } else if (type.includes('date')) {
            rememberBeforeChange();
            if (els.dateField) els.dateField.value = field;
            if (els.yearField && /year|jahr/i.test(field)) els.yearField.value = field;
            setBuilderDimension(field);
        } else {
            return;
        }
        if (els.baseMeasure && (type.includes('measure') || /sales|amount|margin|umsatz/i.test(field))) {
            resetKpiLock();
            els.baseMeasure.value = `${els.aggregation?.value || 'Sum'}(${qlikFieldName(field)})`;
        }
        if (event.altKey || event.metaKey) appendHierarchyLevel(field);
        render();
        return;
    }

    if (variableButton) {
        const variable = variableButton.getAttribute('data-qlik-variable') || '';
        if (els.setVarName && /^vSet/i.test(variable)) {
            els.setVarName.value = variable;
            render();
        }
        return;
    }

});

els.addVariable?.addEventListener('click', () => {
    if (!els.vars) return;
    const prefix = els.variablePrefix?.value?.trim() || 'vMeasure';
    const definition = els.baseMeasure?.value?.trim() || 'Sum(Sales)';
    const name = `${prefix}_${parseVariables(els.vars.value).length + 1}`;
    els.vars.value = `${els.vars.value.trim()}\n${name},${definition},Generated in Set Analysis Generator`;
    render();
});

els.addSetVariable?.addEventListener('click', () => {
    rememberBeforeChange();
    resetKpiLock();
    appendSetVariable();
    render();
});

els.addSearchFilter?.addEventListener('click', () => {
    appendSearchFilter();
    render();
});

els.undo?.addEventListener('click', () => {
    const previous = state.undoStack.pop();
    if (!previous) return;
    state.redoStack.push(snapshotForm('formula'));
    restoreSnapshot(previous);
    render();
});

els.redo?.addEventListener('click', () => {
    const next = state.redoStack.pop();
    if (!next) return;
    state.undoStack.push(snapshotForm('formula'));
    restoreSnapshot(next);
    render();
});

els.copyMeasures?.addEventListener('click', () => copyFromButton(els.copyMeasures, buildOutputs().measures, (key) => t(getLocale(), key)));
els.copyVariables?.addEventListener('click', () => copyFromButton(els.copyVariables, buildOutputs().variables, (key) => t(getLocale(), key)));
els.copyTimeVars?.addEventListener('click', () => copyFromButton(els.copyTimeVars, buildOutputs().timeVariables, (key) => t(getLocale(), key)));
els.copyModifiers?.addEventListener('click', () => copyFromButton(els.copyModifiers, buildOutputs().modifiers, (key) => t(getLocale(), key)));
els.copyNestedIf?.addEventListener('click', () => copyFromButton(els.copyNestedIf, buildOutputs().nestedIf, (key) => t(getLocale(), key)));
els.copyPickMatch?.addEventListener('click', () => copyFromButton(els.copyPickMatch, buildOutputs().pickMatch, (key) => t(getLocale(), key)));
els.copyCsv?.addEventListener('click', () => copyFromButton(els.copyCsv, buildOutputs().csv, (key) => t(getLocale(), key)));
els.outputTabs.forEach((tab) => {
    tab.addEventListener('click', () => activateOutputTab(tab.dataset.qlikOutputTab));
});
els.outputScrollPrev?.addEventListener('click', () => scrollOutputTabs(-1));
els.outputScrollNext?.addEventListener('click', () => scrollOutputTabs(1));
els.outputTabsViewport?.addEventListener('scroll', updateOutputTabOverflow, { passive: true });
els.downloadMeasuresXlsx?.addEventListener('click', () => {
    const outputs = buildOutputs();
    downloadBlob('qlik-child-measures.xlsx', workbookBlob('Measures', outputs.measureRows || [[]]));
});
els.downloadMeasuresCsv?.addEventListener('click', () => {
    downloadText('qlik-child-measures.csv', buildOutputs().measuresCsv || buildOutputs().csv);
});
els.downloadVariablesXlsx?.addEventListener('click', () => {
    const outputs = buildOutputs();
    downloadBlob('qlik-variables.xlsx', workbookBlob('Variables', outputs.variableRows || [[]]));
});
els.downloadVariablesCsv?.addEventListener('click', () => {
    downloadText('qlik-variables.csv', buildOutputs().variablesCsv || '');
});

applyQlikSetLabels();
refreshSavedAppsSelect();
updateHelpToggleLabel();
updatePaletteToggleLabel();
updateDescriptionToggleLabel();
updateBaseListToggleLabel();
updateWorkbenchToggleLabel();
render();
updateOutputTabOverflow();
window.addEventListener('binom-tools:locale', () => {
    applyQlikSetLabels();
    refreshSavedAppsSelect(els.savedApps?.value || '');
    updateHelpToggleLabel();
    updatePaletteToggleLabel();
    updateDescriptionToggleLabel();
    updateBaseListToggleLabel();
    updateWorkbenchToggleLabel();
    render();
    window.requestAnimationFrame(updateOutputTabOverflow);
});
window.addEventListener('resize', updateOutputTabOverflow);
