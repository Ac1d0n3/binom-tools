import '../../../css/tools/pii-policy-generator.css';
import { getLocale } from '../../locale';
import { applyDqHistoryLabels, t } from './labels';
import { createDefaultDqModelState } from '../dq-shared/dq-demo-model.js';
import { mergeDqMeta } from '../dq-shared/dq-meta.js';
import {
    debouncedSaveDqState,
    isDqStorageLoadCorrupt,
    loadDqMetaState,
    subscribeDqState,
} from '../dq-shared/dq-storage.js';
import {
    buildDqCollectResultsMacro,
    buildDqHistorySetupMarkdown,
    buildDqOpenFailuresView,
    buildDqRunHistoryModel,
    buildDqScoreDailyView,
    buildDqTrendWeeklyView,
} from './dq-history-builder.js';
import { collectDqIssues } from '../dq-shared/dq-validation.js';
import { renderValidatedOutputs } from '../pii-shared/validation-ui.js';
import { copyFromButton, updateSyncStatusEl } from '../pii-shared/tool-utils.js';

const app = document.getElementById('dbt-dq-history-generator-app');
if (!app) throw new Error('DQ history generator root element not found');

/** @type {import('../dq-shared/dq-demo-model.js').DqModelState} */
let state = createDefaultDqModelState();

/** @type {import('../pii-shared/validation.js').ValidationIssue | null} */
let storageWarning = null;

const els = {
    validationBanner: document.getElementById('dq-history-validation-banner'),
    historyPre: document.getElementById('dq-history-pre'),
    scorePre: document.getElementById('dq-score-pre'),
    trendPre: document.getElementById('dq-trend-pre'),
    failuresPre: document.getElementById('dq-failures-pre'),
    collectPre: document.getElementById('dq-collect-pre'),
    setupPre: document.getElementById('dq-history-setup-pre'),
    syncStatus: document.getElementById('dq-history-sync-status'),
};

function locale() {
    return getLocale();
}

function tr(key, params = {}) {
    return t(locale(), key, params);
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
        outputPres: [els.historyPre, els.scorePre, els.trendPre, els.failuresPre, els.collectPre, els.setupPre],
        issues: [...(storageWarning ? [storageWarning] : []), ...dqIssues],
        builds: [
            { el: els.historyPre, fn: () => buildDqRunHistoryModel(state) },
            { el: els.scorePre, fn: () => buildDqScoreDailyView() },
            { el: els.trendPre, fn: () => buildDqTrendWeeklyView() },
            { el: els.failuresPre, fn: () => buildDqOpenFailuresView() },
            { el: els.collectPre, fn: () => buildDqCollectResultsMacro() },
            { el: els.setupPre, fn: () => buildDqHistorySetupMarkdown(state) },
        ],
        t: tr,
    });
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

applyDqHistoryLabels();
hydrateFromStorage();
renderOutputs();
debouncedSaveDqState(state, 'dbt-dq-history-generator');

subscribeDqState(({ state: next, meta }) => {
    state = mergeDqMeta(state, next);
    renderOutputs();
    updateSyncStatusEl(els.syncStatus, meta, tr);
});

document.querySelectorAll('[data-dq-copy]').forEach((btn) => {
    const targetId = btn.getAttribute('data-dq-copy');
    const pre = targetId ? document.getElementById(targetId) : null;
    btn.addEventListener('click', () => copyFromButton(/** @type {HTMLButtonElement} */ (btn), pre?.textContent ?? '', tr));
});

window.addEventListener('binom-tools:locale', () => {
    applyDqHistoryLabels();
    renderOutputs();
});
