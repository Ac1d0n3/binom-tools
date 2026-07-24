import '../../../css/tools/pii-policy-generator.css';
import '../../../css/tools/qlik-set-analysis-generator.css';
import { getLocale } from '../../locale';
import { copyFromButton } from '../pii-shared/tool-utils.js';
import { applyQlikSetLabels, t } from './labels.js';
import { buildCurrentFormula, buildSetAnalysisOutputs, injectSetAnalysis, parseCsv, parseFields, parseVariables } from './set-analysis-builder.js';

const app = document.getElementById('qlik-set-analysis-generator-app');
if (!app) throw new Error('Qlik set analysis generator root element not found');

const els = {
    helpSection: document.querySelector('.qlik-set-help'),
    helpToggle: document.getElementById('qlik-set-help-toggle'),
    helpBody: document.getElementById('qlik-set-help-body'),
    paletteToggle: document.getElementById('qlik-set-palette-toggle'),
    paletteBody: document.getElementById('qlik-set-palette-body'),
    importModal: document.getElementById('qlik-set-import-modal'),
    workbench: document.querySelector('.qlik-set-workbench'),
    importModalOpen: document.getElementById('qlik-set-import-modal-open'),
    importModalClose: document.getElementById('qlik-set-import-modal-close'),
    measureName: document.getElementById('qlik-set-measure-name'),
    baseDescription: document.getElementById('qlik-set-base-description'),
    baseMeasure: document.getElementById('qlik-set-base-measure'),
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
    fields: document.getElementById('qlik-set-fields'),
    vars: document.getElementById('qlik-set-vars'),
    fieldOptions: document.getElementById('qlik-set-field-options'),
    variableOptions: document.getElementById('qlik-set-variable-options'),
    fieldChips: document.getElementById('qlik-set-field-chips'),
    variableChips: document.getElementById('qlik-set-variable-chips'),
    aggregation: document.getElementById('qlik-set-aggregation'),
    measureField: document.getElementById('qlik-set-measure-field'),
    variableUse: document.getElementById('qlik-set-variable-use'),
    applyBase: document.getElementById('qlik-set-apply-base'),
    useVariableBase: document.getElementById('qlik-set-use-variable-base'),
    addDimensionRow: document.getElementById('qlik-set-add-dimension-row'),
    addVariable: document.getElementById('qlik-set-add-variable'),
    addSetVariable: document.getElementById('qlik-set-add-set-variable'),
    addSearchFilter: document.getElementById('qlik-set-add-search-filter'),
    builderDimension: document.getElementById('qlik-set-builder-dimension'),
    builderValue: document.getElementById('qlik-set-builder-value'),
    builderLabel: document.getElementById('qlik-set-builder-label'),
    searchExpression: document.getElementById('qlik-set-search-expression'),
    setVarName: document.getElementById('qlik-set-set-var-name'),
    setVarValues: document.getElementById('qlik-set-set-var-values'),
    filterDropzone: document.getElementById('qlik-set-filter-dropzone'),
    filterPreview: document.getElementById('qlik-set-filter-preview'),
    treePreview: document.getElementById('qlik-set-tree-preview'),
    hierarchyLevels: document.getElementById('qlik-set-hierarchy-levels'),
    hierarchyPreview: document.getElementById('qlik-set-hierarchy-preview'),
    dateField: document.getElementById('qlik-set-date-field'),
    yearField: document.getElementById('qlik-set-year-field'),
    rows: document.getElementById('qlik-set-rows'),
    measuresPre: document.getElementById('qlik-set-measures-pre'),
    variablesPre: document.getElementById('qlik-set-variables-pre'),
    hierarchyPre: document.getElementById('qlik-set-hierarchy-pre'),
    timeVarsPre: document.getElementById('qlik-set-time-vars-pre'),
    modifiersPre: document.getElementById('qlik-set-modifiers-pre'),
    nestedIfPre: document.getElementById('qlik-set-nested-if-pre'),
    pickMatchPre: document.getElementById('qlik-set-pick-match-pre'),
    csvPre: document.getElementById('qlik-set-csv-pre'),
    copyMeasures: document.getElementById('qlik-set-copy-measures'),
    copyVariables: document.getElementById('qlik-set-copy-variables'),
    copyHierarchy: document.getElementById('qlik-set-copy-hierarchy'),
    copyTimeVars: document.getElementById('qlik-set-copy-time-vars'),
    copyModifiers: document.getElementById('qlik-set-copy-modifiers'),
    copyNestedIf: document.getElementById('qlik-set-copy-nested-if'),
    copyPickMatch: document.getElementById('qlik-set-copy-pick-match'),
    copyCsv: document.getElementById('qlik-set-copy-csv'),
};

const formControls = Array.from(app.querySelectorAll('input, select, textarea'));
const state = {
    activeKpi: null,
    kpiBaseMeasure: null,
    undoStack: [],
    redoStack: [],
};

function controlKey(control) {
    return control.id || control.name;
}

function snapshotForm() {
    const values = {};
    formControls.forEach((control) => {
        const key = controlKey(control);
        if (!key || control.type === 'file') return;
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

function rememberBeforeChange() {
    const snapshot = snapshotForm();
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
        baseMeasure: els.baseMeasure?.value?.trim() || 'Sum(Sales)',
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
    rememberBeforeChange();
    if (target === els.baseMeasure) resetKpiLock();
    insertAtCursor(target, snippet);
}

function appendFilterRow(dimension, value = '', label = '') {
    if (!els.rows || !dimension) return;
    const line = `${dimension},${value || 'VALUE'},${label || value || dimension}`;
    els.rows.value = `${els.rows.value.trim()}\n${line}`;
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
    const levels = els.hierarchyLevels.value
        .split(/\r?\n|>|,/)
        .map((value) => value.trim())
        .filter(Boolean);
    if (!levels.includes(field)) levels.push(field);
    els.hierarchyLevels.value = levels.join('\n');
}

function hierarchyLevels() {
    return (els.hierarchyLevels?.value || '')
        .split(/\r?\n|>|,/)
        .map((value) => value.trim())
        .filter(Boolean);
}

function writeHierarchyLevels(levels) {
    if (!els.hierarchyLevels) return;
    els.hierarchyLevels.value = levels.filter(Boolean).join('\n');
}

function insertHierarchyLevelAfter(parentLevel = '', nextLevelOverride = '') {
    const nextLevel = nextLevelOverride || els.builderDimension?.value?.trim() || '';
    if (!nextLevel || !els.hierarchyLevels) return;

    const levels = hierarchyLevels().filter((level) => level !== nextLevel);
    const parentIndex = parentLevel ? levels.indexOf(parentLevel) : -1;
    const insertIndex = parentIndex >= 0 ? parentIndex + 1 : 0;
    levels.splice(insertIndex, 0, nextLevel);
    writeHierarchyLevels(levels);
}

function removeHierarchyLevelAt(index) {
    const levels = hierarchyLevels();
    if (index < 0 || index >= levels.length) return;
    levels.splice(index, 1);
    writeHierarchyLevels(levels);
}

function moveHierarchyLevel(index, direction) {
    const levels = hierarchyLevels();
    const nextIndex = index + direction;
    if (index < 0 || nextIndex < 0 || index >= levels.length || nextIndex >= levels.length) return;
    [levels[index], levels[nextIndex]] = [levels[nextIndex], levels[index]];
    writeHierarchyLevels(levels);
}

function valueChipsForDimension(rows, dimension) {
    const values = rows
        .filter((row) => row.dimension === dimension)
        .map((row) => row.label || row.value)
        .filter(Boolean)
        .slice(0, 4);
    if (values.length === 0) return '';
    return `<div class="qlik-set-tree-values">${values.map((value) => `<span>${escapeHtml(value)}</span>`).join('')}</div>`;
}

function hierarchySlotLabel(previousLevel, nextLevel) {
    const locale = getLocale();
    if (nextLevel) {
        return t(locale, 'qlikSet.tree.insertBetween')
            .replace('{before}', previousLevel || t(locale, 'qlikSet.tree.base'))
            .replace('{after}', nextLevel);
    }
    return t(locale, 'qlikSet.tree.insertUnder')
        .replace('{level}', previousLevel || t(locale, 'qlikSet.tree.base'));
}

function renderHierarchySlot(previousLevel, nextLevel = '', extraClass = '') {
    const label = previousLevel ? hierarchySlotLabel(previousLevel, nextLevel) : hierarchySlotLabel(previousLevel, '');
    const rootAttribute = previousLevel ? '' : ' data-qlik-tree-insert-root';
    return `<div class="qlik-set-tree-slot ${extraClass}" role="button" tabindex="0"${rootAttribute} data-qlik-tree-insert-after="${escapeHtml(previousLevel)}" data-qlik-tree-drop-after="${escapeHtml(previousLevel)}">${escapeHtml(label)}</div>`;
}

function renderHierarchyBranch(levels, rows, index = 0) {
    if (index >= levels.length) return '';
    const level = levels[index];
    const nextLevel = levels[index + 1] || '';
    const moveUpLabel = t(getLocale(), 'qlikSet.tree.moveUp');
    const moveDownLabel = t(getLocale(), 'qlikSet.tree.moveDown');
    const removeLabel = t(getLocale(), 'qlikSet.tree.remove');
    return `
        <ul>
            <li>
                <div class="qlik-set-tree-node" draggable="true" data-qlik-tree-level="${escapeHtml(level)}" data-qlik-tree-index="${index}">
                    <span>${escapeHtml(level)}</span>
                    <span class="qlik-set-tree-actions">
                        <button type="button" class="qlik-set-tree-action" data-qlik-tree-move="${index}" data-qlik-tree-direction="-1" ${index === 0 ? 'disabled' : ''}>${escapeHtml(moveUpLabel)}</button>
                        <button type="button" class="qlik-set-tree-action" data-qlik-tree-move="${index}" data-qlik-tree-direction="1" ${index === levels.length - 1 ? 'disabled' : ''}>${escapeHtml(moveDownLabel)}</button>
                        <button type="button" class="qlik-set-tree-action" data-qlik-tree-remove="${index}">${escapeHtml(removeLabel)}</button>
                    </span>
                </div>
                ${valueChipsForDimension(rows, level)}
                ${renderHierarchySlot(level, nextLevel)}
                ${renderHierarchyBranch(levels, rows, index + 1)}
            </li>
        </ul>
    `;
}

function appendSetVariable() {
    if (!els.vars) return;
    const dimension = els.builderDimension?.value?.trim() || 'Region';
    const values = (els.setVarValues?.value || els.builderValue?.value || '')
        .split(/[,\n;]/)
        .map((value) => value.trim())
        .filter(Boolean);
    if (values.length === 0) return;

    const name = els.setVarName?.value?.trim() || setVariableName(dimension, values);
    const definition = `${qlikFieldName(dimension)}={${values.map(qlikSingleQuoted).join(',')}}`;
    const description = `Set modifier for ${dimension}: ${values.join(', ')}`;
    const line = [name, definition, description].map(csvCell).join(',');
    els.vars.value = `${els.vars.value.trim()}\n${line}`;
    if (els.variableUse) els.variableUse.value = name;
    if (els.builderValue) els.builderValue.value = values[0];
    if (els.baseMeasure) {
        els.baseMeasure.value = injectSetAnalysis(els.baseMeasure.value || 'Sum(Sales)', `$(${name})`, {
            setIdentifier: els.identifier?.value ?? '$',
            expressionStyle: els.expressionStyle?.value || 'inner',
        });
    }
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

function closestTreeDropSlot(event) {
    const directSlot = event.target.closest?.('[data-qlik-tree-drop-after]');
    if (directSlot) return directSlot;

    const slots = Array.from(els.treePreview?.querySelectorAll('[data-qlik-tree-drop-after]') || []);
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

    if (els.fieldOptions) {
        els.fieldOptions.innerHTML = fields.map((field) => `<option value="${escapeHtml(field.name)}"></option>`).join('');
    }
    if (els.variableOptions) {
        els.variableOptions.innerHTML = variables.map((variable) => `<option value="${escapeHtml(variable.name)}"></option>`).join('');
    }
    if (els.fieldChips) {
        const groupedFields = fields.slice(0, 80).reduce((groups, field) => {
            groups[fieldCatalogType(field)].push(field);
            return groups;
        }, { dimension: [], measure: [], date: [], other: [] });
        els.fieldChips.innerHTML = ['dimension', 'measure', 'date', 'other']
            .map((type) => renderFieldGroup(type, groupedFields[type]))
            .join('');
    }
    if (els.variableChips) {
        els.variableChips.innerHTML = variables.slice(0, 40).map((variable) => `<button type="button" class="qlik-set-chip qlik-set-chip--variable" draggable="true" data-qlik-variable="${escapeHtml(variable.name)}" data-qlik-variable-definition="${escapeHtml(variable.definition)}"><span class="qlik-set-chip__icon qlik-set-chip__icon--variable" aria-hidden="true"></span><span>${escapeHtml(variable.name)}</span></button>`).join('');
    }
    if (els.filterPreview) {
        const rows = parseCsv(els.rows?.value || '');
        els.filterPreview.innerHTML = rows.slice(0, 20).map((row) => `<span class="qlik-set-filter-pill">${escapeHtml(row.dimension)} = ${escapeHtml(row.value)}</span>`).join('');
    }
    if (els.treePreview) {
        const rows = parseCsv(els.rows?.value || '');
        const levels = hierarchyLevels();
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
                    ${renderHierarchySlot('', levels[0] || '', 'qlik-set-tree-slot--root')}
                    ${renderHierarchyBranch(levels, rows)}
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
    if (els.hierarchyPre) els.hierarchyPre.textContent = outputs.hierarchy;
    if (els.timeVarsPre) els.timeVarsPre.textContent = outputs.timeVariables;
    if (els.modifiersPre) els.modifiersPre.textContent = outputs.modifiers;
    if (els.nestedIfPre) els.nestedIfPre.textContent = outputs.nestedIf;
    if (els.pickMatchPre) els.pickMatchPre.textContent = outputs.pickMatch;
    if (els.csvPre) els.csvPre.textContent = outputs.csv;
}

app.querySelectorAll('input, select, textarea').forEach((el) => {
    el.addEventListener('input', () => {
        if (el === els.baseMeasure) resetKpiLock();
        render();
    });
    el.addEventListener('change', () => {
        if (el === els.baseMeasure) resetKpiLock();
        render();
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
                rememberBeforeChange();
                if (els.builderDimension) els.builderDimension.value = token.value;
                appendFilterRow(token.value);
            }
        } else if (dropzone.getAttribute('data-qlik-dropzone') === 'hierarchy') {
            if (token.kind === 'field') {
                rememberBeforeChange();
                if (els.builderDimension) els.builderDimension.value = token.value;
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
    rememberBeforeChange();
    els.rows.value = await file.text();
    render();
});

els.fieldsFile?.addEventListener('change', async () => {
    const file = els.fieldsFile.files?.[0];
    if (!file || !els.fields) return;
    rememberBeforeChange();
    els.fields.value = await file.text();
    render();
});

els.varsFile?.addEventListener('change', async () => {
    const file = els.varsFile.files?.[0];
    if (!file || !els.vars) return;
    rememberBeforeChange();
    els.vars.value = await file.text();
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

els.useVariableBase?.addEventListener('click', () => {
    const variable = els.variableUse?.value?.trim() || 'vSales';
    if (els.baseMeasure) {
        rememberBeforeChange();
        resetKpiLock();
        els.baseMeasure.value = `$(${variable})`;
    }
    render();
});

els.addDimensionRow?.addEventListener('click', () => {
    if (!els.rows) return;
    rememberBeforeChange();
    const dimension = els.builderDimension?.value?.trim() || 'Region';
    const value = els.builderValue?.value?.trim() || 'DACH';
    const label = els.builderLabel?.value?.trim() || value;
    const line = `${dimension},${value},${label}`;
    els.rows.value = `${els.rows.value.trim()}\n${line}`;
    render();
});

els.treePreview?.addEventListener('click', (event) => {
    const removeButton = event.target.closest('[data-qlik-tree-remove]');
    const moveButton = event.target.closest('[data-qlik-tree-move]');
    const rootButton = event.target.closest('[data-qlik-tree-insert-root]');
    const insertButton = event.target.closest('[data-qlik-tree-insert-after]');
    if (removeButton) {
        rememberBeforeChange();
        removeHierarchyLevelAt(Number(removeButton.getAttribute('data-qlik-tree-remove') || -1));
        render();
        return;
    }
    if (moveButton) {
        rememberBeforeChange();
        moveHierarchyLevel(
            Number(moveButton.getAttribute('data-qlik-tree-move') || -1),
            Number(moveButton.getAttribute('data-qlik-tree-direction') || 0),
        );
        render();
        return;
    }
    if (rootButton || insertButton) {
        rememberBeforeChange();
        insertHierarchyLevelAfter(insertButton?.getAttribute('data-qlik-tree-insert-after') || '');
        render();
    }
});

els.treePreview?.addEventListener('keydown', (event) => {
    if (event.key !== 'Enter' && event.key !== ' ') return;
    const slot = event.target.closest('[data-qlik-tree-insert-after]');
    if (!slot) return;
    event.preventDefault();
    rememberBeforeChange();
    insertHierarchyLevelAfter(slot.getAttribute('data-qlik-tree-insert-after') || '');
    render();
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
    rememberBeforeChange();
    if (els.builderDimension) els.builderDimension.value = token.value;
    insertHierarchyLevelAfter(slot.getAttribute('data-qlik-tree-drop-after') || '', token.value);
    render();
});

els.fieldChips?.addEventListener('click', (event) => {
    const button = event.target.closest('[data-qlik-field]');
    if (!button) return;
    const field = button.getAttribute('data-qlik-field') || '';
    const type = (button.getAttribute('data-qlik-field-type') || '').toLowerCase();

    if (type.includes('measure') && els.measureField) {
        rememberBeforeChange();
        els.measureField.value = field;
    } else if (type.includes('date')) {
        rememberBeforeChange();
        if (els.dateField) els.dateField.value = field;
        if (els.yearField && /year|jahr/i.test(field)) els.yearField.value = field;
        if (els.builderDimension) els.builderDimension.value = field;
    } else if (els.builderDimension) {
        rememberBeforeChange();
        els.builderDimension.value = field;
    }
    if (els.baseMeasure && (type.includes('measure') || /sales|amount|margin|umsatz/i.test(field))) {
        resetKpiLock();
        els.baseMeasure.value = `${els.aggregation?.value || 'Sum'}(${qlikFieldName(field)})`;
    }
    if (event.altKey || event.metaKey) appendHierarchyLevel(field);
    render();
});

els.variableChips?.addEventListener('click', (event) => {
    const button = event.target.closest('[data-qlik-variable]');
    if (!button) return;
    const variable = button.getAttribute('data-qlik-variable') || '';
    const definition = button.getAttribute('data-qlik-variable-definition') || '';
    rememberBeforeChange();
    if (els.variableUse) els.variableUse.value = variable;
    if (els.baseMeasure) {
        resetKpiLock();
        els.baseMeasure.value = definition.includes('={')
            ? injectSetAnalysis(els.baseMeasure.value || 'Sum(Sales)', `$(${variable})`, {
                setIdentifier: els.identifier?.value ?? '$',
                expressionStyle: els.expressionStyle?.value || 'inner',
            })
            : `$(${variable})`;
    }
    render();
});

els.addVariable?.addEventListener('click', () => {
    if (!els.vars) return;
    rememberBeforeChange();
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
    rememberBeforeChange();
    appendSearchFilter();
    render();
});

els.undo?.addEventListener('click', () => {
    const previous = state.undoStack.pop();
    if (!previous) return;
    state.redoStack.push(snapshotForm());
    restoreSnapshot(previous);
    render();
});

els.redo?.addEventListener('click', () => {
    const next = state.redoStack.pop();
    if (!next) return;
    state.undoStack.push(snapshotForm());
    restoreSnapshot(next);
    render();
});

els.copyMeasures?.addEventListener('click', () => copyFromButton(els.copyMeasures, buildOutputs().measures, (key) => t(getLocale(), key)));
els.copyVariables?.addEventListener('click', () => copyFromButton(els.copyVariables, buildOutputs().variables, (key) => t(getLocale(), key)));
els.copyHierarchy?.addEventListener('click', () => copyFromButton(els.copyHierarchy, buildOutputs().hierarchy, (key) => t(getLocale(), key)));
els.copyTimeVars?.addEventListener('click', () => copyFromButton(els.copyTimeVars, buildOutputs().timeVariables, (key) => t(getLocale(), key)));
els.copyModifiers?.addEventListener('click', () => copyFromButton(els.copyModifiers, buildOutputs().modifiers, (key) => t(getLocale(), key)));
els.copyNestedIf?.addEventListener('click', () => copyFromButton(els.copyNestedIf, buildOutputs().nestedIf, (key) => t(getLocale(), key)));
els.copyPickMatch?.addEventListener('click', () => copyFromButton(els.copyPickMatch, buildOutputs().pickMatch, (key) => t(getLocale(), key)));
els.copyCsv?.addEventListener('click', () => copyFromButton(els.copyCsv, buildOutputs().csv, (key) => t(getLocale(), key)));

applyQlikSetLabels();
updateHelpToggleLabel();
updatePaletteToggleLabel();
render();
window.addEventListener('binom-tools:locale', () => {
    applyQlikSetLabels();
    updateHelpToggleLabel();
    updatePaletteToggleLabel();
    render();
});
