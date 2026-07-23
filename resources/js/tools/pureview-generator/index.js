import '../../../css/tools/pii-policy-generator.css';
import { getLocale } from '../../locale';
import { copyFromButton } from '../pii-shared/tool-utils.js';
import { applyPureViewLabels, t } from './labels.js';
import { buildPureViewOutputs, splitCsv } from './pureview-builder.js';

function bootPureViewGenerator() {
    const app = document.getElementById('pureview-generator-app');
    if (!app) throw new Error('PureView generator root element not found');

    const els = {
        asset: document.getElementById('pureview-asset'),
        domain: document.getElementById('pureview-domain'),
        platform: document.getElementById('pureview-platform'),
        owner: document.getElementById('pureview-owner'),
        steward: document.getElementById('pureview-steward'),
        columns: document.getElementById('pureview-columns'),
        sensitive: document.getElementById('pureview-sensitive'),
        frequency: document.getElementById('pureview-frequency'),
        jsonPre: document.getElementById('pureview-json-pre'),
        mappingPre: document.getElementById('pureview-mapping-pre'),
        runbookPre: document.getElementById('pureview-runbook-pre'),
        copyJson: document.getElementById('pureview-copy-json'),
        copyMapping: document.getElementById('pureview-copy-mapping'),
        copyRunbook: document.getElementById('pureview-copy-runbook'),
    };

    function readState() {
        return {
            asset: els.asset?.value?.trim() || 'sales.orders_curated',
            domain: els.domain?.value?.trim() || 'sales-domain',
            platform: els.platform?.value || 'fabric',
            owner: els.owner?.value?.trim() || 'data-owner',
            steward: els.steward?.value?.trim() || 'data-steward',
            columns: splitCsv(els.columns?.value || 'id, updated_at'),
            sensitive: splitCsv(els.sensitive?.value || ''),
            frequency: els.frequency?.value || 'weekly',
            toolId: app.dataset.toolId || 'pureview-scan-generator',
        };
    }

    function buildOutputs() {
        return buildPureViewOutputs(readState());
    }

    function render() {
        const outputs = buildOutputs();
        if (els.jsonPre) els.jsonPre.textContent = outputs.json;
        if (els.mappingPre) els.mappingPre.textContent = outputs.mapping;
        if (els.runbookPre) els.runbookPre.textContent = outputs.runbook;
    }

    function bind() {
        app.querySelectorAll('input, select').forEach((el) => {
            el.addEventListener('input', render);
            el.addEventListener('change', render);
        });
        els.copyJson?.addEventListener('click', () => copyFromButton(els.copyJson, buildOutputs().json, (key) => t(getLocale(), key)));
        els.copyMapping?.addEventListener('click', () => copyFromButton(els.copyMapping, buildOutputs().mapping, (key) => t(getLocale(), key)));
        els.copyRunbook?.addEventListener('click', () => copyFromButton(els.copyRunbook, buildOutputs().runbook, (key) => t(getLocale(), key)));
    }

    applyPureViewLabels();
    render();
    bind();

    window.addEventListener('binom-tools:locale', () => {
        applyPureViewLabels();
        render();
    });
}

bootPureViewGenerator();
