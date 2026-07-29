import '../../css/pii-policy-generator.css';
import { getLocale } from '../../../../resources/js/shell/locale';
import { copyFromButton } from '../pii-shared/tool-utils.js';
import { applyLakehouseDqLabels, t } from './labels.js';
import { DEFAULT_DQ_REGION, normalizeDqRegionId } from '../dq-shared/dq-regions.js';
import { buildPackExtraChecks, getRulePack } from '../dq-shared/dq-rule-packs.js';
import { mountDqPacksPanel } from '../dq-shared/dq-packs-panel.js';
import {
    buildDatabricksNotebook,
    buildDatabricksSql,
    buildFabricNotebook,
    buildFabricSql,
    buildRunbook,
    splitCsv,
} from './pattern-builder.js';

/**
 * @typedef {import('./pattern-builder.js').LakehouseExtraCheck} LakehouseExtraCheck
 */

/** @param {'fabric' | 'databricks'} platform */
export function bootLakehouseDqPatternGenerator(platform) {
    const app = document.getElementById('lakehouse-dq-pattern-generator-app');
    if (!app) throw new Error('Lakehouse DQ pattern generator root element not found');

    const toolId =
        app.dataset.toolId ||
        (platform === 'fabric' ? 'fabric-dq-pattern-generator' : 'databricks-dq-pattern-generator');

    const els = {
        table: /** @type {HTMLInputElement | null} */ (document.getElementById('lakehouse-dq-table')),
        keys: /** @type {HTMLInputElement | null} */ (document.getElementById('lakehouse-dq-keys')),
        required: /** @type {HTMLInputElement | null} */ (document.getElementById('lakehouse-dq-required')),
        freshness: /** @type {HTMLInputElement | null} */ (document.getElementById('lakehouse-dq-freshness')),
        pii: /** @type {HTMLInputElement | null} */ (document.getElementById('lakehouse-dq-pii')),
        owner: /** @type {HTMLInputElement | null} */ (document.getElementById('lakehouse-dq-owner')),
        pattern: /** @type {HTMLSelectElement | null} */ (document.getElementById('lakehouse-dq-pattern')),
        sqlPre: document.getElementById('lakehouse-dq-sql-pre'),
        notebookPre: document.getElementById('lakehouse-dq-notebook-pre'),
        runbookPre: document.getElementById('lakehouse-dq-runbook-pre'),
        copySql: document.getElementById('lakehouse-dq-copy-sql'),
        copyNotebook: document.getElementById('lakehouse-dq-copy-notebook'),
        copyRunbook: document.getElementById('lakehouse-dq-copy-runbook'),
    };

    /** @type {string} */
    let region = DEFAULT_DQ_REGION;
    /** @type {string[]} */
    let appliedPackIds = [];
    /** @type {LakehouseExtraCheck[]} */
    let extraChecks = [];
    /** @type {string[]} */
    let packNotes = [];

    function locale() {
        return getLocale();
    }

    function tr(key, params = {}) {
        return t(locale(), key, params);
    }

    function readState() {
        return {
            table:
                els.table?.value?.trim() ||
                (platform === 'fabric' ? 'sales.orders_curated' : 'main.sales.orders_curated'),
            keys: splitCsv(els.keys?.value || ''),
            required: splitCsv(els.required?.value || ''),
            freshness: els.freshness?.value?.trim() || 'updated_at',
            pii: splitCsv(els.pii?.value || ''),
            owner: els.owner?.value?.trim() || 'data-owner',
            pattern: els.pattern?.value || 'dq',
            toolId,
            region,
            appliedPackIds: [...appliedPackIds],
            extraChecks: [...extraChecks],
            packNotes: [...packNotes],
        };
    }

    /** @param {ReturnType<typeof readState>} next */
    function writeState(next) {
        if (els.table) els.table.value = next.table || '';
        if (els.keys) els.keys.value = (next.keys || []).join(', ');
        if (els.required) els.required.value = (next.required || []).join(', ');
        if (els.freshness) els.freshness.value = next.freshness || 'updated_at';
        if (els.pii) els.pii.value = (next.pii || []).join(', ');
        if (els.owner) els.owner.value = next.owner || '';
        if (els.pattern && next.pattern) els.pattern.value = next.pattern;
        region = normalizeDqRegionId(next.region);
        appliedPackIds = Array.isArray(next.appliedPackIds) ? [...next.appliedPackIds] : [];
        extraChecks = Array.isArray(next.extraChecks) ? [...next.extraChecks] : [];
        packNotes = Array.isArray(next.packNotes) ? [...next.packNotes] : [];
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

    /**
     * @param {string} packId
     * @param {string} regionId
     */
    function applyPack(packId, regionId) {
        region = normalizeDqRegionId(regionId);
        const built = buildPackExtraChecks(packId, region);
        const pack = getRulePack(packId);
        if (!pack) return;

        const columnNames = pack.build(region).columns.map((col) => col.name);
        const state = readState();
        const required = new Set(state.required);
        const pii = new Set(state.pii);
        const keys = new Set(state.keys);

        for (const name of columnNames) {
            if (packId === 'pii-detection') pii.add(name);
            else if (packId === 'unique-business-key') keys.add(name);
            else required.add(name);
        }

        if (els.required) els.required.value = [...required].join(', ');
        if (els.pii) els.pii.value = [...pii].join(', ');
        if (els.keys) els.keys.value = [...keys].join(', ');

        const byKey = new Map(
            extraChecks.map((check) => [`${check.column}:${check.type}:${check.pattern || ''}`, check]),
        );
        for (const check of built.extraChecks) {
            byKey.set(`${check.column}:${check.type}:${check.pattern || ''}`, check);
        }
        extraChecks = [...byKey.values()];
        packNotes = [...new Set([...packNotes, ...built.notes])];
        if (!appliedPackIds.includes(packId)) appliedPackIds.push(packId);
        render();
    }

    function bind() {
        app.querySelectorAll('input, select').forEach((el) => {
            if (el.closest('[data-dq-packs-panel]')) return;
            el.addEventListener('input', render);
            el.addEventListener('change', render);
        });
        els.copySql?.addEventListener('click', () =>
            copyFromButton(els.copySql, buildOutputs().sql, (key) => t(locale(), key)),
        );
        els.copyNotebook?.addEventListener('click', () =>
            copyFromButton(els.copyNotebook, buildOutputs().notebook, (key) => t(locale(), key)),
        );
        els.copyRunbook?.addEventListener('click', () =>
            copyFromButton(els.copyRunbook, buildOutputs().runbook, (key) => t(locale(), key)),
        );
    }

    applyLakehouseDqLabels();
    const packsPanel = mountDqPacksPanel({
        root: app,
        toolId,
        locale,
        t: tr,
        getPayload: () => readState(),
        applyPayload: (payload) => {
            if (!payload || typeof payload !== 'object') return;
            writeState(/** @type {ReturnType<typeof readState>} */ (payload));
            packsPanel?.setRegion(region);
            render();
        },
        applyPack,
        onRegionChange: (next) => {
            region = normalizeDqRegionId(next);
            render();
        },
    });
    render();
    bind();

    window.addEventListener('binom-tools:locale', () => {
        applyLakehouseDqLabels();
        packsPanel?.rerenderLabels();
        render();
    });
}
