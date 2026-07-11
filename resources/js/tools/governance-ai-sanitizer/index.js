import '../../../css/tools/governance-ai-sanitizer.css';
import { getLocale } from '../../locale';
import { applyGovernanceLabels, t } from './labels';
import { getDemoDefaults } from './demo-defaults';
import { GovernanceState } from './governance-state';

import { mergeValidationTranslator } from '../pii-shared/validation-labels.js';
import { renderValidationMessage } from '../pii-shared/validation-ui.js';

const app = document.getElementById('governance-ai-sanitizer-app');
if (!app) {
    throw new Error('Governance AI sanitizer root element not found');
}

const promptInput = /** @type {HTMLTextAreaElement} */ (document.getElementById('gov-prompt-input'));
const sanitizeBtn = document.getElementById('gov-sanitize-btn');
const findingsSummary = document.getElementById('gov-findings-summary');
const outboundBlock = document.getElementById('gov-outbound-block');
const outboundPre = document.getElementById('gov-outbound-pre');
const copyOutboundBtn = document.getElementById('gov-copy-outbound-btn');
const aiInput = /** @type {HTMLTextAreaElement} */ (document.getElementById('gov-ai-input'));
const simulateBtn = document.getElementById('gov-simulate-btn');
const restoreBtn = /** @type {HTMLButtonElement} */ (document.getElementById('gov-restore-btn'));
const restoredBlock = document.getElementById('gov-restored-block');
const restoredPre = document.getElementById('gov-restored-pre');
const findingsEmpty = document.getElementById('gov-findings-empty');
const findingsTable = /** @type {HTMLTableElement} */ (document.getElementById('gov-findings-table'));
const findingsBody = document.getElementById('gov-findings-body');
const validationBanner = document.getElementById('gov-validation-banner');

const state = new GovernanceState({ reusePlaceholders: true });

function validationT(key, params = {}) {
    return mergeValidationTranslator(currentLocale(), t)(key, params);
}

function clearValidationBanner() {
    if (!validationBanner) return;
    validationBanner.hidden = true;
    validationBanner.innerHTML = '';
    validationBanner.classList.remove('tools-validation-banner--has-errors', 'tools-validation-banner--has-warnings');
}

function currentLocale() {
    return getLocale();
}

function applyDemoDefaults() {
    const defaults = getDemoDefaults(currentLocale());
    state.setInput(defaults.userMessage);
    state.setLlmRules(defaults.llmRules);
    state.setAiResponseTemplate(defaults.aiResponseTemplate);
    promptInput.value = defaults.userMessage;
    aiInput.value = '';
    render();
}

function renderFindings() {
    const loc = currentLocale();
    if (!state.hasFindings()) {
        findingsEmpty.hidden = false;
        findingsTable.hidden = true;
        findingsBody.innerHTML = '';
        findingsSummary.hidden = true;
        return;
    }

    findingsEmpty.hidden = true;
    findingsTable.hidden = false;
    findingsSummary.hidden = false;
    findingsSummary.textContent = `${state.findingCount()} ${t(loc, 'panels.promptSanitizer.findingsLabel')}`;

    findingsBody.innerHTML = state.findings
        .map((finding) => {
            const typeLabel = t(loc, `detectors.${finding.type}`) || finding.type;
            const confidence = typeof finding.confidence === 'number'
                ? `${Math.round(finding.confidence * 100)}%`
                : '—';
            return `<tr>
                <td>${escapeHtml(typeLabel)}</td>
                <td><code>${escapeHtml(finding.value)}</code></td>
                <td>${finding.start}–${finding.end}</td>
                <td>${confidence}</td>
            </tr>`;
        })
        .join('');
}

function renderOutbound() {
    if (!state.outbound) {
        outboundBlock.hidden = true;
        return;
    }
    outboundBlock.hidden = false;
    outboundPre.textContent = state.outbound;
}

function renderAiPanel() {
    aiInput.value = state.aiResponse;
    restoreBtn.disabled = !state.canRestore();

    if (state.restored) {
        restoredBlock.hidden = false;
        restoredPre.textContent = state.restored;
    } else {
        restoredBlock.hidden = true;
        restoredPre.textContent = '';
    }
}

function render() {
    renderOutbound();
    renderFindings();
    renderAiPanel();
}

/** @param {string} value */
function escapeHtml(value) {
    return value
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;');
}

function bindEvents() {
    promptInput.addEventListener('input', () => {
        state.setInput(promptInput.value);
        clearValidationBanner();
        if (!promptInput.value.trim()) {
            state.findings = [];
            state.outbound = '';
            state.mapping = null;
            render();
        }
    });

    sanitizeBtn?.addEventListener('click', () => {
        state.setInput(promptInput.value);
        if (!promptInput.value.trim()) {
            renderValidationMessage(validationBanner, validationT('validation.promptEmpty'));
            return;
        }
        try {
            state.runSanitize();
            clearValidationBanner();
            render();
        } catch (error) {
            const detail = error instanceof Error ? error.message : String(error);
            renderValidationMessage(validationBanner, validationT('validation.sanitizeFailed', { detail }));
        }
    });

    copyOutboundBtn?.addEventListener('click', async () => {
        if (!state.outbound) return;
        try {
            await navigator.clipboard.writeText(state.outbound);
            const original = copyOutboundBtn.textContent;
            copyOutboundBtn.textContent = t(currentLocale(), 'outbound.copied');
            window.setTimeout(() => {
                copyOutboundBtn.textContent = original;
            }, 1500);
        } catch {
            // Fallback for older browsers
            window.prompt(t(currentLocale(), 'outbound.copy'), state.outbound);
        }
    });

    aiInput.addEventListener('input', () => {
        state.setAiResponse(aiInput.value);
        renderAiPanel();
    });

    simulateBtn?.addEventListener('click', () => {
        state.buildSimulatedAiResponse();
        render();
    });

    restoreBtn.addEventListener('click', () => {
        state.runRestore();
        render();
    });

    window.addEventListener('binom-tools:locale', () => {
        applyGovernanceLabels(currentLocale());
        state.setLlmRules(getDemoDefaults(currentLocale()).llmRules);
        renderFindings();
        if (copyOutboundBtn) {
            copyOutboundBtn.textContent = t(currentLocale(), 'outbound.copy');
        }
    });
}

function init() {
    applyGovernanceLabels(currentLocale());
    applyDemoDefaults();
    bindEvents();
}

init();
