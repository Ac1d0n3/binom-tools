import './style.css';
import { buildTableauCondition, buildTableauOutputs, definitionFromDimensionValues, parseCsv, parseDefinitions, parseFields } from './tableau-builder.js';
import { t } from './labels.js';

const STORAGE_KEY = 'binom-tools-tableau-calculation-generator-app';

const els = {};

document.addEventListener('DOMContentLoaded', () => {
    const root = document.querySelector('[data-tableau-calc-root]');

    if (!root) {
        return;
    }

    collectElements();
    applyLabels();
    bindEvents();
    render();
});

function collectElements() {
    [
        'fields',
        'values',
        'definitionDimension',
        'definitionValues',
        'definitionName',
        'definitions',
        'definitionPreview',
        'baseMeasures',
        'descriptionDe',
        'descriptionEn',
        'output',
        'message',
        'saveApp',
        'loadApp',
    ].forEach((id) => {
        els[id] = document.querySelector(`[data-tableau-${id.replace(/[A-Z]/g, (char) => `-${char.toLowerCase()}`)}]`);
    });
}

function applyLabels() {
    document.querySelectorAll('[data-tableau-i18n]').forEach((node) => {
        node.textContent = t(node.dataset.tableauI18n);
    });
}

function bindEvents() {
    document.addEventListener('input', (event) => {
        if (event.target.closest('[data-tableau-calc-root]')) {
            render();
        }
    });

    document.addEventListener('change', (event) => {
        if (event.target.closest('[data-tableau-calc-root]')) {
            render();
        }
    });

    document.querySelector('[data-tableau-add-definition]')?.addEventListener('click', addDefinition);

    document.querySelectorAll('[data-tableau-tab]').forEach((button) => {
        button.addEventListener('click', () => activateTab(button.dataset.tableauTab));
    });

    document.querySelector('[data-tableau-copy]')?.addEventListener('click', copyActiveOutput);
    document.querySelector('[data-tableau-download]')?.addEventListener('click', downloadCsv);
    els.saveApp?.addEventListener('click', saveApp);
    els.loadApp?.addEventListener('click', loadApp);
}

function render() {
    renderDefinitionControls();
    const state = readState();
    const outputs = buildTableauOutputs(state);
    const activeTab = document.querySelector('[data-tableau-tab].is-active')?.dataset.tableauTab || 'calculations';
    els.output.textContent = outputs[activeTab] || outputs.calculations;
}

function readState() {
    return {
        fieldsText: els.fields?.value || '',
        valuesText: els.values?.value || '',
        definitionsText: els.definitions?.value || '',
        baseMeasuresText: els.baseMeasures?.value || '',
        descriptionTemplateDe: els.descriptionDe?.value || '',
        descriptionTemplateEn: els.descriptionEn?.value || '',
    };
}

function renderDefinitionControls() {
    const dimensions = parseFields(els.fields?.value || '')
        .filter((field) => field.type === 'dimension' || field.type === 'date')
        .map((field) => field.name);
    const currentDimension = els.definitionDimension?.value || dimensions[0] || '';

    if (els.definitionDimension) {
        els.definitionDimension.innerHTML = dimensions.map((dimension) => `<option value="${escapeAttr(dimension)}"${dimension === currentDimension ? ' selected' : ''}>${escapeHtml(dimension)}</option>`).join('');
    }

    const values = parseCsv(els.values?.value || '')
        .filter((row) => (row.dimension || '') === currentDimension)
        .map((row) => row.label || row.value)
        .filter(Boolean);

    if (els.definitionValues) {
        const selected = Array.from(els.definitionValues.selectedOptions).map((option) => option.value);
        els.definitionValues.innerHTML = values.map((value) => `<option value="${escapeAttr(value)}"${selected.includes(value) ? ' selected' : ''}>${escapeHtml(value)}</option>`).join('');
    }

    const selectedValues = Array.from(els.definitionValues?.selectedOptions || []).map((option) => option.value);
    const draft = definitionFromDimensionValues(currentDimension, selectedValues, els.definitionName?.value || '');
    els.definitionPreview.textContent = draft.expression || buildTableauCondition(currentDimension, values.slice(0, 1));
}

function addDefinition() {
    const dimension = els.definitionDimension?.value || '';
    const values = Array.from(els.definitionValues?.selectedOptions || []).map((option) => option.value);
    const definition = definitionFromDimensionValues(dimension, values, els.definitionName?.value || '');

    if (!definition.name || !definition.expression) {
        return;
    }

    const existing = parseDefinitions(els.definitions?.value || '').filter((item) => item.name !== definition.name);
    const next = [...existing, definition];
    els.definitions.value = [
        'name,dimensions,values,expression,description',
        ...next.map((item) => csvLine([
            item.name,
            item.dimensions.join('|'),
            item.values.join('|'),
            item.expression,
            item.description,
        ])),
    ].join('\n');
    els.message.textContent = t('tableauCalc.saved');
    els.message.hidden = false;
    render();
}

function activateTab(tab) {
    document.querySelectorAll('[data-tableau-tab]').forEach((button) => {
        const active = button.dataset.tableauTab === tab;
        button.classList.toggle('is-active', active);
        button.setAttribute('aria-selected', active ? 'true' : 'false');
    });
    render();
}

async function copyActiveOutput() {
    await navigator.clipboard?.writeText(els.output?.textContent || '');
}

function downloadCsv() {
    const csv = buildTableauOutputs(readState()).csv;
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = 'tableau-calculated-fields.csv';
    link.click();
    URL.revokeObjectURL(url);
}

function saveApp() {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(readState()));
}

function loadApp() {
    const raw = localStorage.getItem(STORAGE_KEY);

    if (!raw) {
        return;
    }

    const state = JSON.parse(raw);
    els.fields.value = state.fieldsText || els.fields.value;
    els.values.value = state.valuesText || els.values.value;
    els.definitions.value = state.definitionsText || els.definitions.value;
    els.baseMeasures.value = state.baseMeasuresText || els.baseMeasures.value;
    els.descriptionDe.value = state.descriptionTemplateDe || els.descriptionDe.value;
    els.descriptionEn.value = state.descriptionTemplateEn || els.descriptionEn.value;
    render();
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
