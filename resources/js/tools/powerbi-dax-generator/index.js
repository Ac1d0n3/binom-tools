import '../../../css/tools/bi-formula-workbench.css';
import '../tableau-calculation-generator/style.css';
import {
    buildDaxCondition,
    buildPowerBiOutputs,
    definitionFromDimensionValues,
    parseBaseMeasures,
    parseCsv,
    parseDefinitions,
    parseFields,
} from './dax-builder.js';
import { t } from './labels.js';

const STORAGE_KEY = 'powerbi-dax-generator.apps.v1';
const FORMULA_KEYS = new Set(['baseExpression', 'measureName', 'measureField', 'aggregation']);

const els = {};
const state = {
    undoStack: [],
    redoStack: [],
    focusSnapshot: null,
    applyingHistory: false,
};

function boot() {
    const root = document.querySelector('[data-powerbi-dax-root]');

    if (!root) {
        return;
    }

    collectElements();
    applyLabels();
    bindEvents();
    refreshSavedAppsSelect();
    pushHistory(true);
    render();

    window.addEventListener('binom-tools:locale', () => {
        applyLabels();
        render();
    });
}

// Vite modules are deferred; DOMContentLoaded may already have fired.
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
} else {
    boot();
}

function collectElements() {
    [
        'fields',
        'values',
        'definitionColumn',
        'definitionValues',
        'definitionName',
        'definitionList',
        'baseExpression',
        'measureName',
        'measureField',
        'aggregation',
        'currentFormula',
        'dimensionChips',
        'measureChips',
        'baseList',
        'baseListBody',
        'baseListToggle',
        'baseMeasureSelect',
        'definitions',
        'definitionPreview',
        'baseMeasures',
        'hierarchy',
        'hierarchyList',
        'hierarchyPreview',
        'descriptionDe',
        'descriptionEn',
        'mode',
        'dateColumn',
        'message',
        'appName',
        'savedApps',
        'appMessage',
        'saveApp',
        'loadApp',
        'deleteApp',
        'undo',
        'redo',
        'importModal',
        'importModalOpen',
        'importModalClose',
        'fieldsFile',
        'valuesFile',
    ].forEach((id) => {
        els[id] = document.querySelector(`[data-powerbi-${camelToKebab(id)}]`);
    });
}

function camelToKebab(value) {
    return value.replace(/[A-Z]/g, (char) => `-${char.toLowerCase()}`);
}

function applyLabels() {
    document.querySelectorAll('[data-powerbi-i18n]').forEach((node) => {
        node.textContent = t(node.dataset.powerbiI18n);
    });
    document.querySelectorAll('[data-i18n]').forEach((node) => {
        const key = node.getAttribute('data-i18n');
        if (!key?.startsWith('powerbiDax.')) {
            return;
        }
        node.textContent = t(key);
    });
}

function bindEvents() {
    document.addEventListener('input', (event) => {
        if (!event.target.closest('[data-powerbi-dax-root]')) {
            return;
        }
        maybePushHistory(event.target);
        render();
    });

    document.addEventListener('change', (event) => {
        if (!event.target.closest('[data-powerbi-dax-root]')) {
            return;
        }
        maybePushHistory(event.target);
        render();
    });

    document.querySelector('[data-powerbi-add-definition]')?.addEventListener('click', addDefinition);
    document.querySelector('[data-powerbi-apply-base]')?.addEventListener('click', applyBaseMeasure);
    document.querySelector('[data-powerbi-load-base]')?.addEventListener('click', loadSelectedBase);
    document.querySelector('[data-powerbi-save-current-base]')?.addEventListener('click', () => {
        pushHistory();
        upsertBaseMeasure();
        render();
    });
    document.querySelector('[data-powerbi-new-base]')?.addEventListener('click', newBase);
    els.baseListToggle?.addEventListener('click', toggleBaseList);
    els.undo?.addEventListener('click', undo);
    els.redo?.addEventListener('click', redo);

    document.querySelectorAll('[data-powerbi-function]').forEach((button) => {
        button.addEventListener('click', () => {
            pushHistory();
            insertIntoFormula(button.dataset.powerbiFunction || '');
        });
    });

    document.querySelectorAll('[data-powerbi-tab]').forEach((button) => {
        button.addEventListener('click', () => activateTab(button.dataset.powerbiTab));
    });
    document.querySelectorAll('[data-powerbi-catalog-tab]').forEach((button) => {
        button.addEventListener('click', () => activateCatalogTab(button.dataset.powerbiCatalogTab));
    });
    document.querySelectorAll('[data-powerbi-builder-tab]').forEach((button) => {
        button.addEventListener('click', () => activateBuilderTab(button.dataset.powerbiBuilderTab));
    });
    document.querySelectorAll('[data-powerbi-import-tab]').forEach((button) => {
        button.addEventListener('click', () => activateImportTab(button.dataset.powerbiImportTab));
    });

    document.querySelector('[data-powerbi-workbench-toggle]')?.addEventListener('click', toggleWorkbench);
    document.querySelector('[data-powerbi-help-toggle]')?.addEventListener('click', toggleHelp);
    document.querySelectorAll('[data-powerbi-layout-toggle]').forEach((button) => {
        button.addEventListener('click', () => toggleLayoutColumn(button.dataset.powerbiLayoutToggle));
    });
    document.querySelectorAll('[data-powerbi-layout-preset]').forEach((button) => {
        button.addEventListener('click', () => applyLayoutPreset(button.dataset.powerbiLayoutPreset || 'all'));
    });

    document.querySelectorAll('[data-powerbi-copy]').forEach((button) => {
        button.addEventListener('click', () => copyOutput(button.dataset.powerbiCopy || 'measures'));
    });
    document.querySelectorAll('[data-powerbi-download-csv]').forEach((button) => {
        button.addEventListener('click', downloadCsv);
    });
    document.querySelectorAll('[data-powerbi-download-xlsx]').forEach((button) => {
        button.addEventListener('click', downloadXlsx);
    });

    els.importModalOpen?.addEventListener('click', () => {
        if (typeof els.importModal?.showModal === 'function') {
            els.importModal.showModal();
        }
    });
    els.importModalClose?.addEventListener('click', () => els.importModal?.close());
    els.importModal?.addEventListener('click', (event) => {
        if (event.target === els.importModal) {
            els.importModal.close();
        }
    });
    els.fieldsFile?.addEventListener('change', (event) => readFileInto(event.target, els.fields));
    els.valuesFile?.addEventListener('change', (event) => readFileInto(event.target, els.values));

    bindDropzone(document.querySelector('[data-powerbi-definition-dropzone]'), handleDefinitionDrop);
    bindDropzone(document.querySelector('[data-powerbi-hierarchy-dropzone]'), handleHierarchyDrop);

    els.hierarchyList?.addEventListener('click', (event) => {
        const removeButton = event.target.closest('[data-powerbi-tree-remove]');
        if (removeButton) {
            pushHistory();
            removeHierarchyLevel(removeButton.dataset.powerbiTreeRemove || '');
        }
    });
    els.hierarchyList?.addEventListener('dragover', (event) => {
        if (event.target.closest('[data-powerbi-tree-parent]')) {
            event.preventDefault();
            event.target.closest('[data-powerbi-tree-parent]').classList.add('is-drag-over');
        }
    });
    els.hierarchyList?.addEventListener('dragleave', (event) => {
        event.target.closest?.('[data-powerbi-tree-parent]')?.classList.remove('is-drag-over');
    });
    els.hierarchyList?.addEventListener('drop', (event) => {
        const slot = event.target.closest('[data-powerbi-tree-parent]');
        if (!slot) {
            return;
        }
        event.preventDefault();
        slot.classList.remove('is-drag-over');
        const raw = event.dataTransfer?.getData('application/json') || '{}';
        try {
            const data = JSON.parse(raw);
            if (data.kind === 'dimension') {
                pushHistory();
                addHierarchyLevel(data.name || '', slot.dataset.powerbiTreeParent || '');
            }
        } catch {
            // Ignore drops that do not come from the app catalog.
        }
    });

    els.definitionList?.addEventListener('click', (event) => {
        const removeButton = event.target.closest('[data-powerbi-definition-remove]');
        if (!removeButton) {
            return;
        }
        pushHistory();
        removeDefinition(removeButton.dataset.powerbiDefinitionRemove || '');
    });

    els.saveApp?.addEventListener('click', saveApp);
    els.loadApp?.addEventListener('click', loadApp);
    els.deleteApp?.addEventListener('click', deleteApp);
}

function render() {
    renderCatalogChips();
    renderDefinitionControls();
    renderDefinitionList();
    renderBaseMeasures();
    renderHierarchyPreview();
    renderHierarchy();
    renderCurrentFormula();
    updateHistoryButtons();

    const outputs = buildPowerBiOutputs(readState());
    document.querySelectorAll('[data-powerbi-output]').forEach((node) => {
        const key = node.dataset.powerbiOutput;
        node.textContent = outputs[key] || '';
    });
}

function readState() {
    return {
        fieldsText: els.fields?.value || '',
        valuesText: els.values?.value || '',
        definitionsText: els.definitions?.value || '',
        baseMeasuresText: els.baseMeasures?.value || '',
        baseExpression: els.baseExpression?.value || '',
        measureName: els.measureName?.value || '',
        baseDescriptionDe: els.measureName?.value || '',
        baseDescriptionEn: els.measureName?.value || '',
        hierarchyText: els.hierarchy?.value || '',
        descriptionTemplateDe: els.descriptionDe?.value || '',
        descriptionTemplateEn: els.descriptionEn?.value || '',
        mode: els.mode?.value || 'single',
        dateColumn: els.dateColumn?.value || "'Date'[Date]",
    };
}

function snapshotForm() {
    return {
        fieldsText: els.fields?.value || '',
        valuesText: els.values?.value || '',
        definitionsText: els.definitions?.value || '',
        baseMeasuresText: els.baseMeasures?.value || '',
        baseExpression: els.baseExpression?.value || '',
        measureName: els.measureName?.value || '',
        measureField: els.measureField?.value || '',
        aggregation: els.aggregation?.value || '',
        hierarchyText: els.hierarchy?.value || '',
        descriptionTemplateDe: els.descriptionDe?.value || '',
        descriptionTemplateEn: els.descriptionEn?.value || '',
        mode: els.mode?.value || 'single',
        dateColumn: els.dateColumn?.value || "'Date'[Date]",
        appName: els.appName?.value || '',
    };
}

function restoreSnapshot(snapshot) {
    if (!snapshot) {
        return;
    }

    state.applyingHistory = true;
    if (els.fields) els.fields.value = snapshot.fieldsText ?? els.fields.value;
    if (els.values) els.values.value = snapshot.valuesText ?? els.values.value;
    if (els.definitions) els.definitions.value = snapshot.definitionsText ?? els.definitions.value;
    if (els.baseMeasures) els.baseMeasures.value = snapshot.baseMeasuresText ?? els.baseMeasures.value;
    if (els.baseExpression) els.baseExpression.value = snapshot.baseExpression ?? els.baseExpression.value;
    if (els.measureName) els.measureName.value = snapshot.measureName ?? els.measureName.value;
    if (els.measureField) els.measureField.value = snapshot.measureField ?? els.measureField.value;
    if (els.aggregation) els.aggregation.value = snapshot.aggregation ?? els.aggregation.value;
    if (els.hierarchy) els.hierarchy.value = snapshot.hierarchyText ?? els.hierarchy.value;
    if (els.descriptionDe) els.descriptionDe.value = snapshot.descriptionTemplateDe ?? els.descriptionDe.value;
    if (els.descriptionEn) els.descriptionEn.value = snapshot.descriptionTemplateEn ?? els.descriptionEn.value;
    if (els.mode) els.mode.value = snapshot.mode ?? els.mode.value;
    if (els.dateColumn) els.dateColumn.value = snapshot.dateColumn ?? els.dateColumn.value;
    if (els.appName && snapshot.appName) els.appName.value = snapshot.appName;
    state.applyingHistory = false;
}

function maybePushHistory(target) {
    if (state.applyingHistory || !target) {
        return;
    }

    const key = Object.keys(els).find((name) => els[name] === target);
    if (key && FORMULA_KEYS.has(key)) {
        pushHistory();
    }
}

function pushHistory(force = false) {
    if (state.applyingHistory && !force) {
        return;
    }

    const snapshot = snapshotForm();
    const last = state.undoStack[state.undoStack.length - 1];
    if (!force && last && JSON.stringify(last) === JSON.stringify(snapshot)) {
        return;
    }

    state.undoStack.push(snapshot);
    if (state.undoStack.length > 80) {
        state.undoStack.shift();
    }
    state.redoStack = [];
    updateHistoryButtons();
}

function undo() {
    if (state.undoStack.length === 0) {
        return;
    }

    const previous = state.undoStack.pop();
    state.redoStack.push(snapshotForm());
    restoreSnapshot(previous);
    updateHistoryButtons();
    render();
}

function redo() {
    if (state.redoStack.length === 0) {
        return;
    }

    state.undoStack.push(snapshotForm());
    restoreSnapshot(state.redoStack.pop());
    updateHistoryButtons();
    render();
}

function updateHistoryButtons() {
    if (els.undo) els.undo.disabled = state.undoStack.length === 0;
    if (els.redo) els.redo.disabled = state.redoStack.length === 0;
}

function renderBaseMeasures() {
    const measures = parseBaseMeasures(els.baseMeasures?.value || '');

    if (els.baseMeasureSelect) {
        const current = els.measureName?.value || '';
        els.baseMeasureSelect.innerHTML = measures.map((measure) => `
            <option value="${escapeAttr(measure.name)}"${measure.name === current ? ' selected' : ''}>${escapeHtml(measure.name)}</option>
        `).join('');
    }

    if (els.baseList) {
        els.baseList.innerHTML = measures.map((measure) => `
            <article class="tableau-calc__item qlik-set-generated-item">
                <strong>${escapeHtml(measure.name)}</strong>
                <code>${escapeHtml(measure.expression)}</code>
                <span>${escapeHtml(measure.descriptionDe || measure.descriptionEn || '')}</span>
            </article>
        `).join('');
    }
}

function renderHierarchyPreview() {
    if (!els.hierarchyPreview) {
        return;
    }

    const lines = String(els.hierarchy?.value || '')
        .split('\n')
        .map((line) => line.trimEnd())
        .filter((line) => line.trim() !== '');

    els.hierarchyPreview.innerHTML = lines.length
        ? `<ol class="qlik-set-hierarchy-preview__list">${lines.map((line) => {
            const depth = Math.floor((line.match(/^\s*/)?.[0].length || 0) / 2);
            return `<li style="--depth:${depth}">${escapeHtml(line.trim())}</li>`;
        }).join('')}</ol>`
        : '';
}

function renderHierarchy() {
    if (!els.hierarchyList) {
        return;
    }

    const tree = hierarchyTree();
    const baseLabel = t('powerbiDax.tree.base');
    const removeLabel = t('powerbiDax.tree.remove');
    const rootLabel = t('powerbiDax.tree.dropOnBase');

    els.hierarchyList.innerHTML = `
        <ul class="qlik-set-tree-list">
            <li>
                <div class="qlik-set-tree-node qlik-set-tree-node--root"><span>${escapeHtml(baseLabel)}</span></div>
                <div class="qlik-set-tree-slot qlik-set-tree-slot--root" data-powerbi-tree-parent="">${escapeHtml(rootLabel)}</div>
                ${renderHierarchyBranches(tree, removeLabel)}
            </li>
        </ul>
    `;
}

function hierarchyTree() {
    const stack = [];
    const roots = [];

    String(els.hierarchy?.value || '')
        .split('\n')
        .map((line) => {
            const name = line.trim();
            return name ? { name, depth: Math.floor((line.match(/^\s*/)?.[0].length || 0) / 2), children: [] } : null;
        })
        .filter(Boolean)
        .forEach((node) => {
            stack[node.depth] = node;
            stack.length = node.depth + 1;
            const parent = stack[node.depth - 1];
            (parent?.children || roots).push(node);
        });

    return roots;
}

function renderHierarchyBranches(nodes, removeLabel) {
    if (!nodes.length) {
        return '';
    }

    return `<ul>${nodes.map((node) => `
        <li>
            <div class="qlik-set-tree-node" draggable="true" data-powerbi-tree-level="${escapeAttr(node.name)}">
                <span>${escapeHtml(node.name)}</span>
                <span class="qlik-set-tree-actions">
                    <button type="button" class="qlik-set-tree-action" data-powerbi-tree-remove="${escapeAttr(node.name)}">${escapeHtml(removeLabel)}</button>
                </span>
            </div>
            <div class="qlik-set-tree-slot" data-powerbi-tree-parent="${escapeAttr(node.name)}">${escapeHtml(t('powerbiDax.tree.dropOnNode').replace('{level}', node.name))}</div>
            ${renderHierarchyBranches(node.children, removeLabel)}
        </li>
    `).join('')}</ul>`;
}

function fieldRef(field) {
    if (!field?.name) {
        return '';
    }

    if (!field.table) {
        return field.name.includes('[') ? field.name : `[${field.name}]`;
    }

    return `${field.table}[${field.name}]`;
}

function renderCatalogChips() {
    const fields = parseFields(els.fields?.value || '');
    const dimensions = fields.filter((field) => field.type === 'dimension' || field.type === 'date');
    const measures = fields.filter((field) => field.type === 'measure');
    const baseMeasures = parseBaseMeasures(els.baseMeasures?.value || '');

    if (els.dimensionChips) {
        els.dimensionChips.innerHTML = dimensions.map((field) => chipHtml('dimension', fieldRef(field), field.tags, field.table, field.name)).join('');
    }

    if (els.measureChips) {
        els.measureChips.innerHTML = [
            ...measures.map((field) => chipHtml('measure', fieldRef(field), field.tags, field.table, field.name)),
            ...baseMeasures.map((measure) => chipHtml('base', measure.name, measure.expression)),
        ].join('');
    }

    document.querySelectorAll('[data-powerbi-chip]').forEach((chip) => {
        chip.addEventListener('dragstart', (event) => {
            event.dataTransfer?.setData('application/json', JSON.stringify(chip.dataset));
            event.dataTransfer?.setData('text/plain', chip.dataset.name || '');
        });
        chip.addEventListener('click', () => {
            if (chip.dataset.kind === 'dimension') {
                setDefinitionDimension(chip.dataset.column || chip.dataset.name || '', chip.dataset.table || '');
            } else if (chip.dataset.kind === 'measure') {
                setMeasureField(chip.dataset.name || '');
            } else if (chip.dataset.kind === 'base') {
                setBaseFromCatalog(chip.dataset.name || '', chip.dataset.meta || '');
            }
        });
    });
}

function renderCurrentFormula() {
    if (els.currentFormula) {
        els.currentFormula.textContent = els.baseExpression?.value || '';
    }
}

function selectedDefinitionField() {
    const fields = parseFields(els.fields?.value || '').filter((field) => field.type === 'dimension' || field.type === 'date');
    const current = els.definitionColumn?.value || '';
    return fields.find((field) => fieldRef(field) === current || field.name === current) || fields[0] || null;
}

function renderDefinitionControls() {
    const fields = parseFields(els.fields?.value || '').filter((field) => field.type === 'dimension' || field.type === 'date');
    const currentField = selectedDefinitionField();
    const currentKey = currentField ? fieldRef(currentField) : '';

    if (els.definitionColumn) {
        els.definitionColumn.innerHTML = fields.map((field) => {
            const key = fieldRef(field);
            return `<option value="${escapeAttr(key)}"${key === currentKey ? ' selected' : ''}>${escapeHtml(key)}</option>`;
        }).join('');
    }

    const values = parseCsv(els.values?.value || '')
        .filter((row) => (row.dimension || row.column || '') === (currentField?.name || ''))
        .map((row) => row.label || row.value)
        .filter(Boolean);

    if (els.definitionValues) {
        const selected = Array.from(els.definitionValues.selectedOptions).map((option) => option.value);
        els.definitionValues.innerHTML = values.map((value) => `<option value="${escapeAttr(value)}"${selected.includes(value) ? ' selected' : ''}>${escapeHtml(value)}</option>`).join('');
    }

    const selectedValues = Array.from(els.definitionValues?.selectedOptions || []).map((option) => option.value);
    const draft = definitionFromDimensionValues(
        currentField?.table || 'Sales',
        currentField?.name || '',
        selectedValues,
        els.definitionName?.value || '',
    );
    if (els.definitionPreview) {
        els.definitionPreview.textContent = draft.expression || buildDaxCondition({
            table: currentField?.table || 'Sales',
            column: currentField?.name || '',
            values: values.slice(0, 1),
            expression: '',
        });
    }
}

function renderDefinitionList() {
    if (!els.definitionList) {
        return;
    }

    const definitions = parseDefinitions(els.definitions?.value || '');
    const removeLabel = t('powerbiDax.definitions.remove');
    els.definitionList.innerHTML = definitions.map((definition) => `
        <article class="qlik-set-generated-item">
            <div>
                <strong>${escapeHtml(definition.name)}</strong>
                <code>${escapeHtml(definition.expression || buildDaxCondition(definition))}</code>
            </div>
            <button type="button" class="tools-btn tools-btn--secondary" data-powerbi-definition-remove="${escapeAttr(definition.name)}">${escapeHtml(removeLabel)}</button>
        </article>
    `).join('');
}

function setDefinitionDimension(column, table = '') {
    if (!column || !els.definitionColumn) {
        return;
    }

    const fields = parseFields(els.fields?.value || '');
    const match = fields.find((field) => field.name === column || fieldRef(field) === column)
        || { table, name: column.replace(/^.*\[|\]$/g, '') };
    const key = fieldRef({ table: match.table || table, name: match.name || column });
    els.definitionColumn.value = key;
    if (els.definitionName && !els.definitionName.value.trim()) {
        els.definitionName.value = match.name || column;
    }
    render();
}

function setMeasureField(field) {
    if (!field) {
        return;
    }

    pushHistory();
    if (els.measureField) {
        els.measureField.value = field;
    }
    insertIntoFormula(field.includes('[') ? field : `[${field}]`);
}

function setBaseFromCatalog(name, expression) {
    pushHistory();
    if (els.measureName && name) {
        els.measureName.value = name;
    }
    if (els.baseExpression && expression) {
        els.baseExpression.value = expression;
    }
    render();
}

function applyBaseMeasure() {
    pushHistory();
    const field = String(els.measureField?.value || 'Sales[Sales]').trim();
    const aggregation = String(els.aggregation?.value || 'SUM').trim() || 'SUM';
    const expression = `${aggregation}(${field.includes('[') ? field : `[${field}]`})`;

    if (els.baseExpression) {
        els.baseExpression.value = expression;
    }
    if (els.measureName && !els.measureName.value.trim()) {
        els.measureName.value = field.replace(/^.*\[|\]$/g, '') || 'Sales';
    }
    upsertBaseMeasure();
    render();
}

function loadSelectedBase() {
    const name = els.baseMeasureSelect?.value || '';
    const measure = parseBaseMeasures(els.baseMeasures?.value || '').find((item) => item.name === name);
    if (!measure) {
        return;
    }

    pushHistory();
    if (els.measureName) els.measureName.value = measure.name;
    if (els.baseExpression) els.baseExpression.value = measure.expression;
    render();
}

function newBase() {
    pushHistory();
    if (els.measureName) els.measureName.value = '';
    if (els.baseExpression) els.baseExpression.value = '';
    if (els.measureField) els.measureField.value = '';
    render();
}

function upsertBaseMeasure() {
    const name = String(els.measureName?.value || '').trim();
    const expression = String(els.baseExpression?.value || '').trim();

    if (!name || !expression || !els.baseMeasures) {
        return;
    }

    const existing = parseBaseMeasures(els.baseMeasures.value).filter((measure) => measure.name !== name);
    const next = [{ name, expression, descriptionDe: name, descriptionEn: name }, ...existing];
    els.baseMeasures.value = [
        'name,expression,description_de,description_en',
        ...next.map((measure) => csvLine([measure.name, measure.expression, measure.descriptionDe, measure.descriptionEn])),
    ].join('\n');
}

function insertIntoFormula(snippet) {
    if (!snippet || !els.baseExpression) {
        return;
    }

    const input = els.baseExpression;
    const start = input.selectionStart ?? input.value.length;
    const end = input.selectionEnd ?? input.value.length;
    input.value = `${input.value.slice(0, start)}${snippet}${input.value.slice(end)}`;
    input.focus();
    input.setSelectionRange(start + snippet.length, start + snippet.length);
    render();
}

function handleDefinitionDrop(data) {
    if (data.kind !== 'dimension') {
        return;
    }

    setDefinitionDimension(data.column || data.name || '', data.table || '');
}

function handleHierarchyDrop(data) {
    if (data.kind !== 'dimension') {
        return;
    }

    pushHistory();
    addHierarchyLevel(data.name || '');
}

function addHierarchyLevel(name, parentName = '') {
    if (!name || !els.hierarchy) {
        return;
    }

    const tree = removeHierarchyNode(hierarchyTree(), name);
    const node = { name, children: [] };
    const parent = parentName ? findHierarchyNode(tree, parentName) : null;
    (parent?.children || tree).push(node);
    els.hierarchy.value = serializeHierarchyTree(tree).join('\n');
    render();
}

function removeHierarchyLevel(name) {
    if (!name || !els.hierarchy) {
        return;
    }

    els.hierarchy.value = serializeHierarchyTree(removeHierarchyNode(hierarchyTree(), name)).join('\n');
    render();
}

function findHierarchyNode(nodes, name) {
    for (const node of nodes) {
        if (node.name === name) {
            return node;
        }
        const found = findHierarchyNode(node.children, name);
        if (found) {
            return found;
        }
    }

    return null;
}

function removeHierarchyNode(nodes, name) {
    return nodes
        .filter((node) => node.name !== name)
        .map((node) => ({ ...node, children: removeHierarchyNode(node.children, name) }));
}

function serializeHierarchyTree(nodes, depth = 0) {
    return nodes.flatMap((node) => [
        `${'  '.repeat(depth)}${node.name}`,
        ...serializeHierarchyTree(node.children, depth + 1),
    ]);
}

function addDefinition() {
    const field = selectedDefinitionField();
    const values = Array.from(els.definitionValues?.selectedOptions || []).map((option) => option.value);
    const definition = definitionFromDimensionValues(
        field?.table || 'Sales',
        field?.name || '',
        values,
        els.definitionName?.value || '',
    );

    if (!definition.name || !definition.expression) {
        return;
    }

    pushHistory();
    const existing = parseDefinitions(els.definitions?.value || '').filter((item) => item.name !== definition.name);
    writeDefinitions([...existing, definition]);
    if (els.message) {
        els.message.textContent = t('powerbiDax.saved');
        els.message.hidden = false;
    }
    render();
}

function removeDefinition(name) {
    writeDefinitions(parseDefinitions(els.definitions?.value || '').filter((item) => item.name !== name));
    render();
}

function writeDefinitions(definitions) {
    if (!els.definitions) {
        return;
    }

    els.definitions.value = [
        'name,table,column,values,expression,description',
        ...definitions.map((item) => csvLine([
            item.name,
            item.table,
            item.column,
            item.values.join('|'),
            item.expression,
            item.description,
        ])),
    ].join('\n');
}

function activateTab(tab) {
    document.querySelectorAll('[data-powerbi-tab]').forEach((button) => {
        const active = button.dataset.powerbiTab === tab;
        button.classList.toggle('is-active', active);
        button.setAttribute('aria-selected', active ? 'true' : 'false');
    });
    document.querySelectorAll('[data-powerbi-output-panel]').forEach((panel) => {
        const active = panel.dataset.powerbiOutputPanel === tab;
        panel.classList.toggle('is-active', active);
        panel.hidden = !active;
    });
}

function activateCatalogTab(tab) {
    document.querySelectorAll('[data-powerbi-catalog-tab]').forEach((button) => {
        const active = button.dataset.powerbiCatalogTab === tab;
        button.classList.toggle('is-active', active);
        button.setAttribute('aria-selected', active ? 'true' : 'false');
    });
    document.querySelectorAll('[data-powerbi-catalog-panel]').forEach((panel) => {
        const active = panel.dataset.powerbiCatalogPanel === tab;
        panel.classList.toggle('is-active', active);
        panel.hidden = !active;
    });
}

function activateBuilderTab(tab) {
    document.querySelectorAll('[data-powerbi-builder-tab]').forEach((button) => {
        const active = button.dataset.powerbiBuilderTab === tab;
        button.classList.toggle('is-active', active);
        button.setAttribute('aria-selected', active ? 'true' : 'false');
    });
    document.querySelectorAll('[data-powerbi-builder-panel]').forEach((panel) => {
        const active = panel.dataset.powerbiBuilderPanel === tab;
        panel.classList.toggle('is-active', active);
        panel.hidden = !active;
    });
}

function activateImportTab(tab) {
    document.querySelectorAll('[data-powerbi-import-tab]').forEach((button) => {
        const active = button.dataset.powerbiImportTab === tab;
        button.classList.toggle('is-active', active);
        button.setAttribute('aria-selected', active ? 'true' : 'false');
    });
    document.querySelectorAll('[data-powerbi-import-panel]').forEach((panel) => {
        const active = panel.dataset.powerbiImportPanel === tab;
        panel.classList.toggle('is-active', active);
        panel.hidden = !active;
    });
}

function toggleWorkbench() {
    const body = document.querySelector('[data-powerbi-workbench-body]');
    const button = document.querySelector('[data-powerbi-workbench-toggle]');

    if (!body || !button) {
        return;
    }

    const open = !body.hidden;
    body.hidden = open;
    button.setAttribute('aria-expanded', open ? 'false' : 'true');
    button.textContent = t(open ? 'powerbiDax.workbench.show' : 'powerbiDax.workbench.hide');
}

function toggleHelp() {
    const section = document.querySelector('[data-powerbi-help]');
    const body = document.querySelector('[data-powerbi-help-body]');
    const button = document.querySelector('[data-powerbi-help-toggle]');

    if (!section || !body || !button) {
        return;
    }

    const expanded = button.getAttribute('aria-expanded') === 'true';
    button.setAttribute('aria-expanded', expanded ? 'false' : 'true');
    body.hidden = expanded;
    section.classList.toggle('is-collapsed', expanded);
    button.textContent = t(expanded ? 'powerbiDax.help.show' : 'powerbiDax.help.hide');
}

function toggleBaseList() {
    if (!els.baseListToggle || !els.baseListBody) {
        return;
    }

    const expanded = els.baseListToggle.getAttribute('aria-expanded') === 'true';
    els.baseListToggle.setAttribute('aria-expanded', expanded ? 'false' : 'true');
    els.baseListBody.hidden = expanded;
    els.baseListToggle.textContent = t(expanded ? 'powerbiDax.baseList.show' : 'powerbiDax.baseList.hide');
}

function toggleLayoutColumn(column) {
    const workbench = document.querySelector('.qlik-set-workbench');

    if (!workbench || !column) {
        return;
    }

    const key = `layout${column.charAt(0).toUpperCase()}${column.slice(1)}`;
    const open = workbench.dataset[key] !== 'closed';
    setLayoutColumn(column, !open);
}

function setLayoutColumn(column, open) {
    const workbench = document.querySelector('.qlik-set-workbench');

    if (!workbench || !column) {
        return;
    }

    const key = `layout${column.charAt(0).toUpperCase()}${column.slice(1)}`;
    workbench.dataset[key] = open ? 'open' : 'closed';
    document.querySelectorAll(`[data-powerbi-layout-toggle="${column}"]`).forEach((button) => {
        button.classList.toggle('is-active', open);
    });
}

function applyLayoutPreset(preset) {
    if (preset === 'focus-formula') {
        setLayoutColumn('catalog', false);
        setLayoutColumn('composer', true);
        setLayoutColumn('builder', false);
        return;
    }

    setLayoutColumn('catalog', true);
    setLayoutColumn('composer', true);
    setLayoutColumn('builder', true);
}

function bindDropzone(dropzone, handler) {
    if (!dropzone) {
        return;
    }

    dropzone.addEventListener('dragover', (event) => {
        event.preventDefault();
        dropzone.classList.add('is-dragover');
    });
    dropzone.addEventListener('dragleave', () => {
        dropzone.classList.remove('is-dragover');
    });
    dropzone.addEventListener('drop', (event) => {
        event.preventDefault();
        dropzone.classList.remove('is-dragover');
        const raw = event.dataTransfer?.getData('application/json') || '{}';
        try {
            handler(JSON.parse(raw));
        } catch {
            // Ignore drops that do not come from the app catalog.
        }
    });
}

function chipHtml(kind, name, meta = '', table = '', column = '') {
    return `<button type="button" class="tableau-calc__chip tableau-calc__chip--${escapeAttr(kind)} qlik-set-chip qlik-set-chip--${escapeAttr(kind === 'base' ? 'measure' : kind)}" draggable="true" data-powerbi-chip data-kind="${escapeAttr(kind)}" data-name="${escapeAttr(name)}" data-meta="${escapeAttr(meta)}" data-table="${escapeAttr(table)}" data-column="${escapeAttr(column || name)}">
        <span class="tableau-calc__chip-icon qlik-set-chip__icon qlik-set-chip__icon--${escapeAttr(kind === 'base' ? 'measure' : kind)}" aria-hidden="true"></span>
        <span>${escapeHtml(name)}</span>
        ${meta ? `<small>${escapeHtml(meta)}</small>` : ''}
    </button>`;
}

async function copyOutput(tab) {
    const node = document.querySelector(`[data-powerbi-output="${tab}"]`);
    await navigator.clipboard?.writeText(node?.textContent || '');
}

function downloadCsv() {
    const csv = buildPowerBiOutputs(readState()).csv;
    downloadBlob('powerbi-dax-measures.csv', new Blob([`\uFEFF${csv}`], { type: 'text/csv;charset=utf-8' }));
}

function downloadXlsx() {
    const outputs = buildPowerBiOutputs(readState());
    downloadBlob('powerbi-dax-measures.xlsx', workbookBlob('Measures', outputs.rows || [[]]));
}

function readSavedApps() {
    try {
        const savedApps = JSON.parse(localStorage.getItem(STORAGE_KEY) || '{}');
        return savedApps && typeof savedApps === 'object' ? savedApps : {};
    } catch {
        return {};
    }
}

function writeSavedApps(savedApps) {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(savedApps));
}

function refreshSavedAppsSelect(selected = '') {
    if (!els.savedApps) {
        return;
    }

    const names = Object.keys(readSavedApps()).sort((a, b) => a.localeCompare(b));
    els.savedApps.innerHTML = [
        `<option value="">${escapeHtml(t('powerbiDax.apps.none'))}</option>`,
        ...names.map((name) => `<option value="${escapeAttr(name)}">${escapeHtml(name)}</option>`),
    ].join('');
    if (selected && names.includes(selected)) {
        els.savedApps.value = selected;
    }
}

function showAppMessage(message, type = 'info') {
    if (!els.appMessage) {
        return;
    }

    els.appMessage.textContent = message;
    els.appMessage.hidden = false;
    els.appMessage.dataset.type = type;
}

function saveApp() {
    const name = els.appName?.value?.trim() || '';
    if (!name) {
        showAppMessage(t('powerbiDax.apps.missingName'), 'error');
        return;
    }

    const savedApps = readSavedApps();
    savedApps[name] = {
        savedAt: new Date().toISOString(),
        snapshot: snapshotForm(),
    };
    writeSavedApps(savedApps);
    refreshSavedAppsSelect(name);
    showAppMessage(t('powerbiDax.apps.savedMessage').replace('{name}', name));
}

function loadApp() {
    const name = els.savedApps?.value || '';
    const savedApp = readSavedApps()[name];
    if (!savedApp?.snapshot) {
        showAppMessage(t('powerbiDax.apps.missingSelection'), 'error');
        return;
    }

    restoreSnapshot(savedApp.snapshot);
    if (els.appName) {
        els.appName.value = name;
    }
    refreshSavedAppsSelect(name);
    render();
}

function deleteApp() {
    const name = els.savedApps?.value || '';
    if (!name) {
        showAppMessage(t('powerbiDax.apps.missingSelection'), 'error');
        return;
    }

    const savedApps = readSavedApps();
    delete savedApps[name];
    writeSavedApps(savedApps);
    refreshSavedAppsSelect();
    showAppMessage(t('powerbiDax.apps.deletedMessage').replace('{name}', name));
}

function readFileInto(input, target) {
    const file = input?.files?.[0];
    if (!file || !target) {
        return;
    }

    const reader = new FileReader();
    reader.onload = () => {
        target.value = String(reader.result || '');
        render();
    };
    reader.readAsText(file);
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

function escapeHtml(value) {
    return String(value ?? '').replace(/[&<>"']/g, (char) => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;',
    })[char]);
}

function escapeAttr(value) {
    return escapeHtml(value);
}

function csvLine(values) {
    return values.map((value) => {
        const text = String(value ?? '');
        return /[",\n\r]/.test(text) ? `"${text.replace(/"/g, '""')}"` : text;
    }).join(',');
}
