import '../../../css/tools/pii-policy-generator.css';
import { getLocale } from '../../locale';
import { applyDqRulesLabels, t } from './labels';
import { createDefaultDqModelState } from '../dq-shared/dq-demo-model.js';
import { mergeDqMeta } from '../dq-shared/dq-meta.js';
import {
    debouncedSaveDqState,
    isDqStorageLoadCorrupt,
    loadDqMetaState,
    subscribeDqState,
} from '../dq-shared/dq-storage.js';
import {
    buildDqRulesYaml,
    buildDqGenericTestsSnippet,
    buildDqSourcesYaml,
    buildDqModelSql,
} from './dq-rules-builder.js';
import { buildDqGovernanceMacro, buildDqRuleTest } from '../dbt-dq-macro-generator/dq-macro-builder.js';
import { getWarehouseTemplate } from '../pii-shared/warehouse-templates.js';
import {
    createDefaultRule,
    readRuleFromRow,
    renderColumnPanel,
    renderModelRulesTable,
} from './dq-rules-ui.js';
import { collectDqIssues } from '../dq-shared/dq-validation.js';
import { renderValidatedOutputs } from '../pii-shared/validation-ui.js';
import { copyFromButton, updateSyncStatusEl } from '../pii-shared/tool-utils.js';

const app = document.getElementById('dbt-dq-rules-generator-app');
if (!app) throw new Error('DQ rules generator root element not found');

/** @type {import('../dq-shared/dq-demo-model.js').DqModelState} */
let state = createDefaultDqModelState();

/** @type {import('../pii-shared/validation.js').ValidationIssue | null} */
let storageWarning = null;

const els = {
    modelName: /** @type {HTMLInputElement} */ (document.getElementById('dq-rules-model-name')),
    sourceTable: /** @type {HTMLInputElement} */ (document.getElementById('dq-rules-source-table')),
    warehouseHint: document.getElementById('dq-rules-warehouse-hint'),
    modelDescription: /** @type {HTMLTextAreaElement} */ (document.getElementById('dq-rules-model-description')),
    columnsRoot: document.getElementById('dq-rules-columns-root'),
    addColumnBtn: document.getElementById('dq-rules-add-column-btn'),
    modelRulesBody: document.getElementById('dq-rules-model-rules-body'),
    addModelRuleBtn: document.getElementById('dq-rules-add-model-rule-btn'),
    validationBanner: document.getElementById('dq-rules-validation-banner'),
    yamlPre: document.getElementById('dq-rules-yaml-pre'),
    sourcesPre: document.getElementById('dq-rules-sources-pre'),
    modelSqlPre: document.getElementById('dq-rules-model-sql-pre'),
    governancePre: document.getElementById('dq-rules-governance-pre'),
    dqRulePre: document.getElementById('dq-rules-dq-rule-pre'),
    testsPre: document.getElementById('dq-rules-tests-pre'),
    copyYamlBtn: /** @type {HTMLButtonElement | null} */ (document.getElementById('dq-rules-copy-yaml-btn')),
    copySourcesBtn: /** @type {HTMLButtonElement | null} */ (document.getElementById('dq-rules-copy-sources-btn')),
    copyModelSqlBtn: /** @type {HTMLButtonElement | null} */ (document.getElementById('dq-rules-copy-model-sql-btn')),
    copyGovernanceBtn: /** @type {HTMLButtonElement | null} */ (document.getElementById('dq-rules-copy-governance-btn')),
    copyDqRuleBtn: /** @type {HTMLButtonElement | null} */ (document.getElementById('dq-rules-copy-dq-rule-btn')),
    copyTestsBtn: /** @type {HTMLButtonElement | null} */ (document.getElementById('dq-rules-copy-tests-btn')),
    syncStatus: document.getElementById('dq-rules-sync-status'),
};

function locale() {
    return getLocale();
}

function tr(key, params = {}) {
    return t(locale(), key, params);
}

function updateWarehouseHint() {
    if (!els.warehouseHint) return;
    const wh = getWarehouseTemplate(state.selectedWarehouse);
    els.warehouseHint.textContent = tr('dqRules.model.warehouseHint', { warehouse: wh.label });
}

function readForm() {
    state.modelName = els.modelName.value.trim() || 'orders';
    state.sourceTable = els.sourceTable.value.trim() || 'raw.orders';
    state.modelDescription = els.modelDescription.value;

    els.columnsRoot?.querySelectorAll('.dq-column-panel').forEach((panel) => {
        const index = Number(panel.getAttribute('data-column-index'));
        const column = state.columns[index];
        if (!column) return;
        const nameInput = /** @type {HTMLInputElement | null} */ (panel.querySelector('[data-field="name"]'));
        const descInput = /** @type {HTMLInputElement | null} */ (panel.querySelector('[data-field="description"]'));
        if (nameInput) column.name = nameInput.value.trim();
        if (descInput) column.description = descInput.value;

        column.dqRules = [];
        panel.querySelectorAll('.dq-rule-row').forEach((row, ruleIndex) => {
            const base = state.columns[index]?.dqRules[ruleIndex] ?? createDefaultRule();
            column.dqRules.push(readRuleFromRow(base, /** @type {HTMLTableRowElement} */ (row)));
        });
    });

    state.modelRules = [];
    els.modelRulesBody?.querySelectorAll('.dq-rule-row').forEach((row) => {
        state.modelRules.push(readRuleFromRow(createDefaultRule('row_count_between'), /** @type {HTMLTableRowElement} */ (row)));
    });
}

function renderColumns() {
    if (!els.columnsRoot) return;
    els.columnsRoot.innerHTML = state.columns
        .map((column, index) => renderColumnPanel(column, index, tr))
        .join('');
    bindColumnEvents();
}

function renderModelRules() {
    if (!els.modelRulesBody) return;
    els.modelRulesBody.innerHTML = renderModelRulesTable(state, tr);
    bindModelRuleEvents();
}

function bindRuleRowEvents(row, onChange) {
    row.querySelectorAll('input, select').forEach((el) => {
        el.addEventListener('change', onChange);
        el.addEventListener('input', onChange);
    });
    row.querySelector('.dq-rule-remove')?.addEventListener('click', onChange);
}

function bindColumnEvents() {
    els.columnsRoot?.querySelectorAll('.dq-column-panel').forEach((panel) => {
        const index = Number(panel.getAttribute('data-column-index'));

        panel.querySelectorAll('.dq-rule-row').forEach((row) => {
            bindRuleRowEvents(row, () => {
                readForm();
                renderColumns();
                renderOutputs();
                persistState();
            });
        });

        panel.querySelector('.dq-add-rule-btn')?.addEventListener('click', () => {
            readForm();
            state.columns[index]?.dqRules.push(createDefaultRule());
            renderColumns();
            renderOutputs();
            persistState();
        });

        panel.querySelectorAll('[data-field="name"], [data-field="description"]').forEach((el) => {
            el.addEventListener('input', () => {
                readForm();
                renderOutputs();
                persistState();
            });
        });
    });
}

function bindModelRuleEvents() {
    els.modelRulesBody?.querySelectorAll('.dq-rule-row').forEach((row, ruleIndex) => {
        bindRuleRowEvents(row, () => {
            readForm();
            if (row.querySelector('.dq-rule-remove') && !row.isConnected) return;
            renderModelRules();
            renderOutputs();
            persistState();
        });
        row.querySelector('.dq-rule-remove')?.addEventListener('click', () => {
            readForm();
            state.modelRules.splice(ruleIndex, 1);
            renderModelRules();
            renderOutputs();
            persistState();
        });
    });
}

function renderOutputs() {
    const dqIssues = collectDqIssues(state).map((issue) => ({
        severity: /** @type {'error' | 'warning'} */ (issue.severity === 'info' ? 'warning' : issue.severity),
        code: issue.code,
        messageKey: issue.messageKey,
        params: issue.params,
    }));

    renderValidatedOutputs({
        bannerEl: els.validationBanner,
        outputPres: [
            els.yamlPre,
            els.sourcesPre,
            els.modelSqlPre,
            els.governancePre,
            els.dqRulePre,
            els.testsPre,
        ],
        issues: [...(storageWarning ? [storageWarning] : []), ...dqIssues],
        builds: [
            { el: els.yamlPre, fn: () => buildDqRulesYaml(state) },
            { el: els.sourcesPre, fn: () => buildDqSourcesYaml(state) },
            { el: els.modelSqlPre, fn: () => buildDqModelSql(state) },
            { el: els.governancePre, fn: () => buildDqGovernanceMacro(state) },
            { el: els.dqRulePre, fn: () => buildDqRuleTest() },
            { el: els.testsPre, fn: () => buildDqGenericTestsSnippet(state) },
        ],
        t: tr,
    });
}

function persistState() {
    debouncedSaveDqState(state, 'dbt-dq-rules-generator');
}

function writeForm() {
    els.modelName.value = state.modelName;
    els.sourceTable.value = state.sourceTable;
    els.modelDescription.value = state.modelDescription;
    updateWarehouseHint();
    renderColumns();
    renderModelRules();
}

function hydrateFromStorage() {
    const loaded = loadDqMetaState();
    if (isDqStorageLoadCorrupt(loaded)) {
        storageWarning = {
            severity: 'warning',
            code: 'storage_corrupt',
            messageKey: 'validation.storageCorrupt',
        };
        return;
    }
    if (loaded && 'state' in loaded) {
        state = mergeDqMeta(state, loaded.state);
        updateSyncStatusEl(els.syncStatus, loaded.meta, tr);
    }
}

applyDqRulesLabels();
hydrateFromStorage();
writeForm();
renderOutputs();

for (const input of [els.modelName, els.sourceTable, els.modelDescription]) {
    input?.addEventListener('input', () => {
        readForm();
        updateWarehouseHint();
        renderOutputs();
        persistState();
    });
}

els.addColumnBtn?.addEventListener('click', () => {
    readForm();
    state.columns.push({ name: `column_${state.columns.length + 1}`, description: '', dqRules: [] });
    renderColumns();
    renderOutputs();
    persistState();
});

els.addModelRuleBtn?.addEventListener('click', () => {
    readForm();
    state.modelRules.push(createDefaultRule('row_count_between'));
    renderModelRules();
    renderOutputs();
    persistState();
});

subscribeDqState(({ state: next, meta }) => {
    state = mergeDqMeta(state, next);
    writeForm();
    renderOutputs();
    updateSyncStatusEl(els.syncStatus, meta, tr);
});

els.copyYamlBtn?.addEventListener('click', () => copyFromButton(els.copyYamlBtn, buildDqRulesYaml(state), tr));
els.copySourcesBtn?.addEventListener('click', () => copyFromButton(els.copySourcesBtn, buildDqSourcesYaml(state), tr));
els.copyModelSqlBtn?.addEventListener('click', () => copyFromButton(els.copyModelSqlBtn, buildDqModelSql(state), tr));
els.copyGovernanceBtn?.addEventListener('click', () =>
    copyFromButton(els.copyGovernanceBtn, buildDqGovernanceMacro(state), tr),
);
els.copyDqRuleBtn?.addEventListener('click', () => copyFromButton(els.copyDqRuleBtn, buildDqRuleTest(), tr));
els.copyTestsBtn?.addEventListener('click', () =>
    copyFromButton(els.copyTestsBtn, buildDqGenericTestsSnippet(state), tr),
);

window.addEventListener('binom-tools:locale', () => {
    applyDqRulesLabels();
    updateWarehouseHint();
    renderColumns();
    renderModelRules();
    renderOutputs();
});
