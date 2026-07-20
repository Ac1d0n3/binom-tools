import '../../../css/tools/pii-policy-generator.css';
import { getLocale } from '../../locale';
import { copyFromButton } from '../pii-shared/tool-utils.js';
import { applyLakehouseDqLabels, t } from './labels.js';
import {
    buildDatabricksNotebook,
    buildDatabricksSql,
    buildFabricNotebook,
    buildFabricSql,
    buildRunbook,
    splitCsv,
} from './pattern-builder.js';

/** @param {'fabric' | 'databricks'} platform */
export function bootLakehouseDqPatternGenerator(platform) {
    const app = document.getElementById('lakehouse-dq-pattern-generator-app');
    if (!app) throw new Error('Lakehouse DQ pattern generator root element not found');

    const els = {
        table: document.getElementById('lakehouse-dq-table'),
        keys: document.getElementById('lakehouse-dq-keys'),
        required: document.getElementById('lakehouse-dq-required'),
        freshness: document.getElementById('lakehouse-dq-freshness'),
        pii: document.getElementById('lakehouse-dq-pii'),
        owner: document.getElementById('lakehouse-dq-owner'),
        pattern: document.getElementById('lakehouse-dq-pattern'),
        sqlPre: document.getElementById('lakehouse-dq-sql-pre'),
        notebookPre: document.getElementById('lakehouse-dq-notebook-pre'),
        runbookPre: document.getElementById('lakehouse-dq-runbook-pre'),
        copySql: document.getElementById('lakehouse-dq-copy-sql'),
        copyNotebook: document.getElementById('lakehouse-dq-copy-notebook'),
        copyRunbook: document.getElementById('lakehouse-dq-copy-runbook'),
    };

    function locale() {
        return getLocale();
    }

    function readState() {
        return {
            table: els.table?.value?.trim() || (platform === 'fabric' ? 'sales.orders_curated' : 'main.sales.orders_curated'),
            keys: splitCsv(els.keys?.value || ''),
            required: splitCsv(els.required?.value || ''),
            freshness: els.freshness?.value?.trim() || 'updated_at',
            pii: splitCsv(els.pii?.value || ''),
            owner: els.owner?.value?.trim() || 'data-owner',
            pattern: els.pattern?.value || 'dq',
        };
    }

    function buildOutputs() {
        const state = readState();
        return {
            sql: platform === 'fabric' ? buildFabricSql(state) : buildDatabricksSql(state),
            notebook: platform === 'fabric' ? buildFabricNotebook(state) : buildDatabricksNotebook(state),
            runbook: buildRunbook(platform, state),
        };
    }

    function render() {
        const outputs = buildOutputs();
        if (els.sqlPre) els.sqlPre.textContent = outputs.sql;
        if (els.notebookPre) els.notebookPre.textContent = outputs.notebook;
        if (els.runbookPre) els.runbookPre.textContent = outputs.runbook;
    }

    function bind() {
        app.querySelectorAll('input, select').forEach((el) => {
            el.addEventListener('input', render);
            el.addEventListener('change', render);
        });
        els.copySql?.addEventListener('click', () => copyFromButton(els.copySql, buildOutputs().sql, (key) => t(locale(), key)));
        els.copyNotebook?.addEventListener('click', () => copyFromButton(els.copyNotebook, buildOutputs().notebook, (key) => t(locale(), key)));
        els.copyRunbook?.addEventListener('click', () => copyFromButton(els.copyRunbook, buildOutputs().runbook, (key) => t(locale(), key)));
    }

    applyLakehouseDqLabels();
    render();
    bind();

    window.addEventListener('binom-tools:locale', () => {
        applyLakehouseDqLabels();
        render();
    });
}
