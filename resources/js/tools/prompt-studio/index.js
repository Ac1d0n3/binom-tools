import '../../../css/tools/prompt-studio.css';
import { getLocale, applyShellLabels } from '../../locale.js';
import { applyPromptStudioLabels, t } from './labels.js';
import { resolveLocalizedLabel } from './localized-label.js';
import { applyHowtoLabels } from './howto-labels.js';
import { loadConfig, resolveConfigBase, getTasksForRole, getParametersForTask } from './config-loader.js';
import { createStateManager } from './state-manager.js';
import { WorkspaceManager } from './workspace-manager.js';
import { TemplateStore } from './template-store.js';
import {
    renderSplitParameterFields,
    bindParameterFields,
    validateRequiredParameters,
} from './field-renderer.js';
import { renderSectionsHtml, bindSectionEditors } from './prompt-sections.js';
import { PiiPreview } from './pii-preview.js';
import { postProcessCompiledPrompt } from './artist-name-stripper.js';
import { ChainManager } from './chain-manager.js';
import { ContinueManager } from './continue-manager.js';
import { VariantsManager } from './variants-manager.js';
import { generateOptimizerMetaPrompt, generateSplitMetaPrompt } from './meta-prompt-generator.js';
import { buildExportBundle, importBundle, downloadExportBundle } from './export-import.js';
import { pushRecentEntry, loadChains, saveChains, saveWorkspace } from './storage.js';
import {
    isLibraryDrawerOpen,
    setLibraryDrawerOpen,
    isPreviewDrawerOpen,
    setPreviewDrawerOpen,
    loadForbiddenSongWords,
    loadForbiddenArtistNames,
    debouncedSaveSession,
    createDefaultVariants,
} from './session-store.js';
import { getUiMode, setUiMode, applyUiModeClasses, splitParametersForMode, isTechMode } from './ui-mode.js';
import { saveToSanitizer, loadPromptBridge } from '../prompt-shared/prompt-bridge-storage.js';
import { copyFromButton } from '../pii-shared/tool-utils.js';
import { buildMarkdownDocument, downloadTextFile, markdownFilename, normalizeOutputKind, getTaskOutputKind } from './md-export.js';
import {
    getStudioArea,
    setStudioArea,
    normalizeStudioArea,
    outputKindForArea,
    areaForOutputKind,
} from './studio-area.js';
import { getPromptLocale, setPromptLocale, normalizePromptLocale } from './prompt-locale.js';
import {
    getLimitKindForTask,
    modelHasPlans,
    normalizeModelPlan,
    resolveCharLimit,
} from './model-limits.js';
import { ensureStarterTemplates } from './seed-library.js';
import {
    fetchLibraryBundle,
    mergeById,
    readLibraryApiConfig,
    upsertLibraryBundle,
} from './library-api.js';

const app = document.getElementById('prompt-studio-app');
if (!app) {
    throw new Error('Prompt Studio root element not found');
}

const configBase = resolveConfigBase(
    app.dataset.configBase,
    document.documentElement.dataset.appBase ?? '',
);

const roleSelect = /** @type {HTMLSelectElement} */ (document.getElementById('ps-role-select'));
const taskSelect = /** @type {HTMLSelectElement} */ (document.getElementById('ps-task-select'));
const modelSelect = /** @type {HTMLSelectElement} */ (document.getElementById('ps-model-select'));
const dynamicFields = document.getElementById('ps-dynamic-fields');
const sectionsRoot = document.getElementById('ps-sections');
const previewSingle = document.getElementById('ps-preview-single');
const previewText = /** @type {HTMLTextAreaElement} */ (document.getElementById('ps-preview-text'));
const workspaceList = document.getElementById('ps-workspace-list');
const piiPreview = document.getElementById('ps-pii-preview');
const artistStripNotice = document.getElementById('ps-artist-strip-notice');
const charCount = document.getElementById('ps-char-count');
const bridgeBanner = document.getElementById('ps-bridge-banner');
const chainPanel = document.getElementById('ps-chain-panel');
const chainSteps = document.getElementById('ps-chain-steps');
const chainLearning = document.getElementById('ps-chain-learning');
const continuePanel = document.getElementById('ps-continue-panel');
const continueTimeline = document.getElementById('ps-continue-timeline');
const continueResponse = /** @type {HTMLTextAreaElement} */ (document.getElementById('ps-continue-response'));
const metaPanel = document.getElementById('ps-meta-panel');
const metaTitle = document.getElementById('ps-meta-title');
const metaPre = document.getElementById('ps-meta-pre');
const taskChangeHint = document.getElementById('ps-task-change-hint');
const roleHelp = document.getElementById('ps-role-help');
const taskHelp = document.getElementById('ps-task-help');
const workflowSuggestion = document.getElementById('ps-workflow-suggestion');
const libraryDrawer = /** @type {HTMLDetailsElement | null} */ (document.getElementById('ps-library-drawer'));
const helpDrawer = /** @type {HTMLDetailsElement | null} */ (document.getElementById('ps-help-drawer'));
const helpAccordion = document.getElementById('ps-help-accordion');
const previewToggleBtn = /** @type {HTMLButtonElement | null} */ (document.getElementById('ps-preview-toggle'));
const previewCollapseBtn = /** @type {HTMLButtonElement | null} */ (document.getElementById('ps-preview-collapse-btn'));
const previewReopenBtn = /** @type {HTMLButtonElement | null} */ (document.getElementById('ps-preview-reopen-btn'));
const downloadMdBtn = /** @type {HTMLButtonElement | null} */ (document.getElementById('ps-download-md-btn'));
const outputKindBadge = document.getElementById('ps-output-kind-badge');
const selectionSummary = document.getElementById('ps-selection-summary');
const selectionTitle = document.getElementById('ps-selection-title');
const selectionMeta = document.getElementById('ps-selection-meta');
const emptyPick = document.getElementById('ps-empty-pick');
const targetAi = document.getElementById('ps-target-ai');
const modelPlanBox = document.getElementById('ps-model-plan');
const limitHint = document.getElementById('ps-limit-hint');
const templateKindFilter = /** @type {HTMLSelectElement | null} */ (document.getElementById('ps-template-kind-filter'));
const categoryFilter = /** @type {HTMLSelectElement | null} */ (document.getElementById('ps-category-filter'));
const addRoleBtn = /** @type {HTMLButtonElement | null} */ (document.getElementById('ps-add-role-btn'));
const saveWorkflowLibraryBtn = /** @type {HTMLButtonElement | null} */ (document.getElementById('ps-save-workflow-btn'));
const variantsEnabled = /** @type {HTMLInputElement} */ (document.getElementById('ps-variants-enabled'));
const variantsField = /** @type {HTMLSelectElement} */ (document.getElementById('ps-variants-field'));
const variantsBody = document.getElementById('ps-variants-body');
const variantsBulk = /** @type {HTMLTextAreaElement} */ (document.getElementById('ps-variants-bulk'));
const variantsList = document.getElementById('ps-variants-list');
const variantsCounter = document.getElementById('ps-variants-counter');

/** @type {import('./config-validator.js').PromptStudioConfig | null} */
let config = null;
/** @type {import('./state-manager.js').StateManager | null} */
let stateManager = null;
/** @type {WorkspaceManager | null} */
let workspaceManager = null;
/** @type {TemplateStore | null} */
let templateStore = null;
/** @type {VariantsManager | null} */
let variantsManager = null;
/** @type {ChainManager | null} */
let chainManager = null;
/** @type {ContinueManager | null} */
let continueManager = null;
const piiScanner = new PiiPreview();
/** @type {string} */
let activeMetaPrompt = '';
/** @type {string} */
let lastTaskId = '';
/** @type {string[]} */
let lastStrippedArtists = [];

const TASK_WORKFLOW_MAP = {
    'song-develop': 'song-production',
    lyrics: 'song-production',
    hook: 'song-production',
    album: 'song-production',
    analysis: 'business-visual',
    'document-summarize': 'document-analysis',
    'root-cause-analysis': 'document-analysis',
    hero: 'business-visual',
    cover: 'song-production',
};

function currentLocale() {
    return /** @type {import('./config-validator.js').ToolsLocale} */ (getLocale());
}

function syncPromptLocaleUi() {
    const locale = stateManager?.getPromptLocale() ?? getPromptLocale(currentLocale());
    document.querySelectorAll('[data-ps-prompt-locale]').forEach((btn) => {
        const id = btn.getAttribute('data-ps-prompt-locale');
        const active = id === locale;
        btn.classList.toggle('prompt-studio__prompt-locale-btn--active', active);
        btn.setAttribute('aria-pressed', active ? 'true' : 'false');
    });
}

/**
 * @param {import('./config-validator.js').ToolsLocale} locale
 */
function applyPromptLocale(locale) {
    const next = setPromptLocale(normalizePromptLocale(locale));
    stateManager?.setPromptLocale(next, { recompile: true });
    syncPromptLocaleUi();
    renderPreview();
}

function tr(key, params = {}) {
    return t(currentLocale(), key, params);
}

function localeToolPath(toolSlug) {
    const base = document.documentElement.dataset.appBase || '';
    const path = window.location.pathname;
    if (path.includes('/de/') || path === '/de' || path.endsWith('/de')) {
        return `${base}/de/tools/${toolSlug}`;
    }
    if (path.includes('/en/') || path === '/en' || path.endsWith('/en')) {
        return `${base}/en/tools/${toolSlug}`;
    }
    return `${base}/tools/${toolSlug}`;
}

function getCompiledForDisplay() {
    if (!stateManager || !variantsManager) return '';
    const raw = variantsManager.getState().enabled && variantsManager.getState().values.length > 0
        ? variantsManager.compileCurrent(stateManager.getDraft())
        : stateManager.getCompiledPrompt();
    const draft = stateManager.getDraft();
    const processed = postProcessCompiledPrompt(raw, {
        blocklist: config?.artistBlocklist ?? [],
        parameterValues: draft.parameterValues,
    });
    lastStrippedArtists = processed.removed;
    const body = processed.text;
    const kind = normalizeOutputKind(draft.kind);
    if (kind === 'prompt') return body;
    const title = draft.title || draft.taskId || draft.roleId || 'document';
    return buildMarkdownDocument({
        kind,
        title,
        body,
        globs: kind === 'rule' ? '**/*.{ts,html,scss}' : undefined,
    });
}

function applyPreviewVisibility() {
    const open = isPreviewDrawerOpen();
    app.classList.toggle('prompt-studio--preview-collapsed', !open);
    const hideLabel = tr('promptStudio.previewToggle.hide');
    const showLabel = tr('promptStudio.previewToggle.show');
    if (previewToggleBtn) {
        previewToggleBtn.setAttribute('aria-pressed', open ? 'true' : 'false');
        previewToggleBtn.textContent = open ? hideLabel : showLabel;
    }
    if (previewCollapseBtn) {
        previewCollapseBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
        previewCollapseBtn.textContent = hideLabel;
    }
    if (previewReopenBtn) {
        previewReopenBtn.hidden = open;
        previewReopenBtn.textContent = showLabel;
    }
}

function togglePreviewOpen() {
    setPreviewDrawerOpen(!isPreviewDrawerOpen());
    applyPreviewVisibility();
}

function kindLabelKey(kind) {
    if (kind === 'rule') return 'promptStudio.kind.rule';
    if (kind === 'agent-task') return 'promptStudio.kind.agentTask';
    return 'promptStudio.kind.prompt';
}

function syncKindUi() {
    const draft = stateManager?.getDraft();
    const area = getStudioArea();
    const areaKind = outputKindForArea(area);
    const task = draft?.taskId ? config?.tasks.find((t) => t.id === draft.taskId) : null;
    const taskKind = task ? getTaskOutputKind(task) : null;
    // Prefer task kind when selected; otherwise follow the active studio area.
    const kind = taskKind ?? areaKind;
    if (draft && draft.kind !== kind) {
        stateManager?.patchDraft({ kind }, { recordHistory: false, persist: true, notify: false });
    }
    if (downloadMdBtn) downloadMdBtn.hidden = kind === 'prompt';
    if (outputKindBadge) {
        if (!draft?.taskId && area === 'prompt') {
            outputKindBadge.hidden = true;
            outputKindBadge.textContent = '';
            return;
        }
        outputKindBadge.hidden = false;
        const kindName = tr(kindLabelKey(kind));
        outputKindBadge.textContent =
            kind === 'prompt'
                ? tr('promptStudio.outputKind.prompt', { kind: kindName })
                : tr('promptStudio.outputKind.md', { kind: kindName });
    }
}

function syncAreaUi() {
    const area = getStudioArea();
    document.getElementById('prompt-studio-app')?.setAttribute('data-ps-studio-area', area);
    document.querySelectorAll('.prompt-studio__area-btn[data-ps-area]').forEach((btn) => {
        const id = btn.getAttribute('data-ps-area');
        const active = id === area;
        btn.classList.toggle('prompt-studio__area-btn--active', active);
        btn.setAttribute('aria-selected', active ? 'true' : 'false');
    });
    // Show only the active area help panel; Builder/Workflows stay available for every area.
    document.querySelectorAll('#ps-help-accordion [data-ps-help-item]').forEach((panel) => {
        const id = panel.getAttribute('data-ps-help-item');
        const isAreaPanel = id === 'prompt' || id === 'rule' || id === 'agent';
        const el = /** @type {HTMLDetailsElement} */ (panel);
        if (isAreaPanel) {
            el.hidden = id !== area;
            el.open = id === area;
            return;
        }
        el.hidden = false;
        el.open = false;
    });

    const builderLead = document.querySelector('.prompt-studio__builder-heading [data-i18n="promptStudio.builder.lead"]');
    if (builderLead) {
        builderLead.textContent = tr(`promptStudio.builder.lead.${area}`);
    }
    if (emptyPick) {
        emptyPick.textContent = tr(`promptStudio.empty.pick.${area}`);
    }

    // Hide category filter in rule/agent areas (short curated lists).
    const tab = workspaceManager?.activeTab;
    if (categoryFilter) {
        const showCategory = area === 'prompt' && (tab === 'library' || tab === 'workflows');
        categoryFilter.hidden = !showCategory;
        if (!showCategory && categoryFilter.value !== 'all') {
            categoryFilter.value = 'all';
            workspaceManager?.setCategoryFilter('all');
        }
    }
}

function syncLibraryTabChrome() {
    const tab = workspaceManager?.activeTab;
    const area = getStudioArea();
    if (categoryFilter) {
        categoryFilter.hidden = area !== 'prompt' || (tab !== 'library' && tab !== 'workflows');
    }
    if (templateKindFilter) templateKindFilter.hidden = tab !== 'templates';
    if (addRoleBtn) addRoleBtn.hidden = tab !== 'roles' || !isTechMode();
    if (saveWorkflowLibraryBtn) saveWorkflowLibraryBtn.hidden = tab !== 'workflows';
}

/**
 * Switch studio area; clear selection if the current task does not belong here.
 * @param {import('./studio-area.js').StudioArea} area
 */
function applyStudioArea(area) {
    const next = setStudioArea(area);
    const kind = outputKindForArea(next);
    const draft = stateManager?.getDraft();
    const task = draft?.taskId ? config?.tasks.find((t) => t.id === draft.taskId) : null;
    if (task && getTaskOutputKind(task) !== kind) {
        chainManager?.clear();
        stateManager?.patchDraft(
            { roleId: '', taskId: '', kind, parameterValues: {}, sections: {}, sectionOverrides: {} },
            { recordHistory: false, persist: true, notify: false },
        );
    } else if (stateManager) {
        stateManager.patchDraft({ kind }, { recordHistory: false, persist: true, notify: false });
    }
    workspaceManager?.setTab('library');
    document.querySelectorAll('[data-ps-tab]').forEach((b) => b.classList.remove('prompt-studio__tab--active'));
    document.querySelector('[data-ps-tab="library"]')?.classList.add('prompt-studio__tab--active');
    syncAreaUi();
    renderAll();
}

function syncSelectionSummary() {
    if (!stateManager || !config || !app) return;
    const draft = stateManager.getDraft();
    const hasSelection = Boolean(draft.taskId);
    const tech = isTechMode();

    app.classList.toggle('prompt-studio--has-selection', hasSelection);

    if (emptyPick) emptyPick.hidden = tech || hasSelection;
    if (selectionSummary) selectionSummary.hidden = tech || !hasSelection;
    if (targetAi) targetAi.hidden = !hasSelection && !tech;

    if (!hasSelection || tech) {
        if (selectionTitle) selectionTitle.textContent = '';
        if (selectionMeta) selectionMeta.textContent = '';
        if (!hasSelection) {
            syncTargetAiUi();
            return;
        }
    }

    if (hasSelection && !tech) {
        const task = config.tasks.find((t) => t.id === draft.taskId);
        const role = workspaceManager?.getAllRoles().find((r) => r.id === draft.roleId)
            ?? config.roles.find((r) => r.id === draft.roleId);
        const locale = currentLocale();
        const taskLabel = resolveLocalizedLabel(task?.label, locale, draft.taskId);
        const roleLabel = resolveLocalizedLabel(role?.label, locale, draft.roleId);
        const kind = getTaskOutputKind(task);
        const kindLabel = tr(kindLabelKey(kind));

        if (selectionTitle) selectionTitle.textContent = taskLabel;
        if (selectionMeta) {
            const parts = [kindLabel];
            if (roleLabel) parts.push(roleLabel);
            if (chainManager?.isOpen) parts.push(tr('promptStudio.badge.workflow'));
            selectionMeta.textContent = parts.join(' · ');
        }
    }

    syncTargetAiUi();
}

function syncTargetAiUi() {
    if (!stateManager || !config) return;
    const draft = stateManager.getDraft();
    const model = config.models.find((m) => m.id === draft.modelId);
    const hasPlans = modelHasPlans(model);
    const plan = normalizeModelPlan(draft.modelPlan ?? model?.defaultPlan);
    const limit = resolveCharLimit({ model, plan, taskId: draft.taskId });
    const limitKind = getLimitKindForTask(draft.taskId);

    if (modelPlanBox) {
        modelPlanBox.hidden = !hasPlans;
        modelPlanBox.querySelectorAll('[data-ps-plan]').forEach((btn) => {
            const id = btn.getAttribute('data-ps-plan');
            btn.classList.toggle('prompt-studio__plan-btn--active', id === plan);
        });
    }

    if (limitHint) {
        if (!draft.taskId || limit == null) {
            limitHint.hidden = true;
            limitHint.textContent = '';
        } else {
            limitHint.hidden = false;
            const kindKey =
                limitKind === 'lyrics'
                    ? 'promptStudio.limit.lyrics'
                    : limitKind === 'style'
                      ? 'promptStudio.limit.style'
                      : 'promptStudio.limit.default';
            limitHint.textContent = tr(kindKey, {
                limit,
                plan: hasPlans ? tr(plan === 'paid' ? 'promptStudio.modelPlan.paid' : 'promptStudio.modelPlan.free') : '',
            });
        }
    }
}

async function syncLibraryToAccount() {
    const api = readLibraryApiConfig(app);
    if (!api.enabled || !templateStore || !workspaceManager) return;
    const chainsLoaded = loadChains();
    await upsertLibraryBundle(api.url, {
        templates: templateStore.list(),
        chains: chainsLoaded && 'data' in chainsLoaded ? chainsLoaded.data : [],
        customRoles: workspaceManager.workspace.customRoles,
    });
}

async function hydrateLibraryFromAccount() {
    const api = readLibraryApiConfig(app);
    if (!api.enabled || !templateStore || !workspaceManager) return;
    const remote = await fetchLibraryBundle(api.url);
    if (!remote) return;
    templateStore.replaceAll(mergeById(templateStore.list(), /** @type {any[]} */ (remote.templates)));
    workspaceManager.refreshUserTemplates(templateStore.list());
    const localChains = loadChains();
    const localChainList = localChains && 'data' in localChains ? localChains.data : [];
    saveChains(mergeById(localChainList, /** @type {any[]} */ (remote.chains)), 'import');
    workspaceManager.workspace.customRoles = mergeById(
        workspaceManager.workspace.customRoles,
        /** @type {any[]} */ (remote.customRoles),
    );
    saveWorkspace(workspaceManager.workspace, 'import');
}

function saveCurrentWorkflow() {
    if (!chainManager || chainManager.activeSteps.length === 0) {
        window.alert(tr('promptStudio.chain.empty'));
        return;
    }
    const name = window.prompt(tr('promptStudio.workflow.savePrompt'), chainManager.presetId || 'My workflow');
    if (!name) return;
    const entry = chainManager.toUserChainEntry(name);
    chainManager.presetId = entry.id;
    const loaded = loadChains();
    const list = loaded && 'data' in loaded ? loaded.data.filter((c) => c.id !== entry.id) : [];
    list.unshift(entry);
    saveChains(list, 'manual');
    void syncLibraryToAccount();
    renderWorkspace();
}

/**
 * @param {string} text
 * @param {Record<string, unknown>} [parameterValues]
 * @returns {string}
 */
function processCompiledOutput(text, parameterValues = stateManager?.getDraft().parameterValues ?? {}) {
    return postProcessCompiledPrompt(text, {
        blocklist: config?.artistBlocklist ?? [],
        parameterValues,
    }).text;
}

function populateSelect(select, items, selectedId, labelKey = 'label', allowEmpty = false) {
    select.innerHTML = '';
    if (allowEmpty && !selectedId) {
        const empty = document.createElement('option');
        empty.value = '';
        empty.textContent = '—';
        empty.selected = true;
        select.appendChild(empty);
    }
    for (const item of items) {
        const option = document.createElement('option');
        option.value = item.id;
        const label = item[labelKey];
        option.textContent = resolveLocalizedLabel(label, currentLocale(), item.id);
        if (item.id === selectedId) option.selected = true;
        select.appendChild(option);
    }
}

function applySongForbiddenDefaults() {
    if (!stateManager) return;
    const draft = stateManager.getDraft();
    const songTasks = new Set(['song-develop', 'lyrics', 'hook', 'album', 'suno-style']);
    if (!songTasks.has(draft.taskId)) return;

    const savedWords = loadForbiddenSongWords();
    if (Array.isArray(savedWords) && savedWords.length > 0) {
        const current = draft.parameterValues.forbiddenLyricsWords;
        if (!current || (Array.isArray(current) && current.length === 0)) {
            stateManager.setParameterValues({ forbiddenLyricsWords: savedWords });
        }
    }

    const savedArtists = loadForbiddenArtistNames();
    if (Array.isArray(savedArtists) && savedArtists.length > 0) {
        const current = draft.parameterValues.forbiddenArtistNames;
        if (!current || (Array.isArray(current) && current.length === 0)) {
            stateManager.setParameterValues({ forbiddenArtistNames: savedArtists });
        }
    }

    if (!draft.parameterValues.stripArtistNames) {
        stateManager.setParameterValues({ stripArtistNames: 'yes' });
    }
}

function escapeHtml(value) {
    return value
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;');
}

function focusChainPanel() {
    if (!chainPanel || chainPanel.hidden) return;
    chainPanel.classList.add('prompt-studio__chain-panel--focus');
    chainPanel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    window.setTimeout(() => chainPanel.classList.remove('prompt-studio__chain-panel--focus'), 1600);
}

function renderBuilderHints() {
    if (!config || !stateManager) return;
    const locale = currentLocale();
    const draft = stateManager.getDraft();
    const task = config.tasks.find((t) => t.id === draft.taskId);

    if (roleHelp) roleHelp.textContent = tr('promptStudio.roleHelp');
    if (taskHelp) {
        taskHelp.textContent = resolveLocalizedLabel(task?.description, locale, '');
    }

    if (taskChangeHint && lastTaskId && lastTaskId !== draft.taskId) {
        const taskLabel = resolveLocalizedLabel(task?.label, locale, draft.taskId);
        taskChangeHint.hidden = false;
        taskChangeHint.textContent = tr('promptStudio.taskChanged', { task: taskLabel });
    }

    if (workflowSuggestion && config) {
        const workflowId = TASK_WORKFLOW_MAP[/** @type {keyof typeof TASK_WORKFLOW_MAP} */ (draft.taskId)];
        const chain = workflowId ? config.chains.find((c) => c.id === workflowId) : null;
        // Only hint at an optional multi-step workflow — single tasks stay the default path.
        if (chain && !(chainManager?.isOpen)) {
            const name = resolveLocalizedLabel(chain.label, locale, chain.id);
            workflowSuggestion.hidden = false;
            workflowSuggestion.innerHTML = `
                <p class="prompt-studio__workflow-suggestion-title">${escapeHtml(tr('promptStudio.workflowSuggestOptional', { name }))}</p>
                <button type="button" class="tools-btn tools-btn--sm" id="ps-workflow-start-btn">${escapeHtml(tr('promptStudio.workflowStart'))}</button>
            `;
            document.getElementById('ps-workflow-start-btn')?.addEventListener('click', () => {
                chainManager?.loadChain(chain.id, locale);
                chainManager?.open();
                syncChainStepToBuilder();
                renderAll();
                focusChainPanel();
            });
        } else {
            workflowSuggestion.hidden = true;
        }
    }
}

function renderBuilder() {
    if (!config || !stateManager || !workspaceManager) return;
    const draft = stateManager.getDraft();
    const roles = workspaceManager.getAllRoles().filter((r) => r.taskIds?.length || r.id);
    populateSelect(roleSelect, roles, draft.roleId, 'label', !draft.roleId);

    const tasks = getTasksForRole(draft.roleId, config);
    populateSelect(taskSelect, tasks, draft.taskId, 'label', !draft.taskId);

    populateSelect(modelSelect, config.models, draft.modelId);

    const allParameters = getParametersForTask(draft.taskId, config);
    const split = splitParametersForMode(allParameters, {
        chainOpen: chainManager?.isOpen ?? false,
        taskId: draft.taskId,
    });

    if (dynamicFields) {
        dynamicFields.innerHTML = renderSplitParameterFields(
            split.primary,
            split.advanced,
            draft.parameterValues,
            currentLocale(),
            tr('promptStudio.advancedFields'),
        );
        bindParameterFields(dynamicFields, allParameters, (values) => {
            if (!stateManager) return;
            stateManager.setParameterValues(values);
            if (values.forbiddenLyricsWords && Array.isArray(values.forbiddenLyricsWords)) {
                debouncedSaveSession({ forbiddenSongWords: values.forbiddenLyricsWords.map(String) });
            }
            if (values.forbiddenArtistNames && Array.isArray(values.forbiddenArtistNames)) {
                debouncedSaveSession({ forbiddenArtistNames: values.forbiddenArtistNames.map(String) });
            }
            if (chainManager?.isOpen) {
                const step = chainManager.getCurrentStep();
                if (step) {
                    chainManager.patchStep(chainManager.currentStepIndex, { parameterValues: stateManager.getDraft().parameterValues });
                }
            }
            renderPreview();
            renderVariantsPanel();
        });
    }

    if (variantsField) {
        const previous = variantsField.value;
        variantsField.innerHTML = '';
        for (const def of allParameters) {
            const option = document.createElement('option');
            option.value = def.id;
            option.textContent = resolveLocalizedLabel(def.label, currentLocale(), def.id);
            variantsField.appendChild(option);
        }
        variantsField.value = variantsManager?.getState().varyingFieldId || previous || allParameters[0]?.id || 'goal';
    }

    renderBuilderHints();
    lastTaskId = draft.taskId;
}

function renderPreview() {
    if (!stateManager) return;
    const draft = stateManager.getDraft();
    const compiled = getCompiledForDisplay();
    const sections = stateManager.getCompiledSections();

    if (previewText) previewText.value = compiled;
    if (previewSingle) previewSingle.hidden = isTechMode();

    if (sectionsRoot) {
        sectionsRoot.innerHTML = renderSectionsHtml(sections, draft.sectionOverrides, currentLocale());
        bindSectionEditors(sectionsRoot, (sectionId, content) => {
            stateManager?.setSection(sectionId, content);
        });
        sectionsRoot.hidden = !isTechMode();
    }

    if (charCount) {
        const model = config?.models.find((m) => m.id === draft.modelId);
        const plan = normalizeModelPlan(draft.modelPlan ?? model?.defaultPlan);
        const limit = resolveCharLimit({ model, plan, taskId: draft.taskId });
        const count = compiled.length;
        if (limit != null) {
            charCount.textContent = tr('promptStudio.charCount.limit', { count, limit });
            charCount.classList.toggle('is-over', count > limit);
            charCount.classList.toggle('is-warn', count > limit * 0.9 && count <= limit);
        } else {
            charCount.textContent = tr('promptStudio.charCount', { count });
            charCount.classList.remove('is-over', 'is-warn');
        }
    }

    if (piiPreview) {
        piiScanner.scan(compiled);
        piiPreview.hidden = false;
        piiPreview.innerHTML = piiScanner.renderHtml(tr);
    }

    if (artistStripNotice) {
        if (lastStrippedArtists.length > 0) {
            artistStripNotice.hidden = false;
            artistStripNotice.textContent = tr('promptStudio.artists.removed', {
                names: lastStrippedArtists.join(', '),
            });
        } else {
            artistStripNotice.hidden = true;
            artistStripNotice.textContent = '';
        }
    }
}

function renderVariantsPanel() {
    if (!variantsManager) return;
    const state = variantsManager.getState();
    if (variantsEnabled) variantsEnabled.checked = state.enabled;
    if (variantsBody) variantsBody.hidden = !state.enabled;
    if (variantsField && state.varyingFieldId) variantsField.value = state.varyingFieldId;

    if (variantsList) {
        variantsList.innerHTML = state.values
            .map((value, index) => `<li class="${index === state.currentIndex ? 'is-active' : ''}">${escapeHtml(value)}</li>`)
            .join('');
    }

    if (variantsCounter) {
        if (state.values.length === 0) {
            variantsCounter.textContent = '';
        } else {
            variantsCounter.textContent = tr('promptStudio.variants.counter', {
                current: state.currentIndex + 1,
                total: state.values.length,
            });
        }
    }
}

function renderWorkspace() {
    if (!workspaceManager || !workspaceList) return;
    syncLibraryTabChrome();
    workspaceList.innerHTML = workspaceManager.renderListHtml(currentLocale());
}

function renderChainPanel() {
    if (!chainManager || !chainSteps || !chainPanel) return;
    chainPanel.hidden = !chainManager.isOpen;
    chainPanel.classList.toggle('prompt-studio__chain-panel--open', chainManager.isOpen);
    if (!chainManager.isOpen) return;
    chainSteps.innerHTML = chainManager.renderStepsHtml(currentLocale());
    if (chainLearning) {
        const tip = chainManager.getLearningTip(currentLocale());
        chainLearning.hidden = !tip;
        chainLearning.textContent = tip;
    }
}

function renderContinuePanel() {
    if (!continueManager || !continueTimeline || !continuePanel) return;
    continuePanel.hidden = !continueManager.isOpen;
    if (!continueManager.isOpen) return;
    continueTimeline.innerHTML = continueManager.renderTimelineHtml(tr);
}

function syncChainStepToBuilder() {
    if (!chainManager || !stateManager || !config) return;
    const step = chainManager.getCurrentStep();
    if (!step) return;

    if (step.roleId && step.taskId) {
        stateManager.setRoleAndTask(step.roleId, step.taskId);
    } else if (step.taskId === 'custom-block') {
        stateManager.setTask('custom-block');
    }

    const values = { ...(step.parameterValues ?? {}) };
    if (step.previousOutput) values.previousOutput = step.previousOutput;
    stateManager.setParameterValues(values);
}

function saveCurrentStepFromBuilder() {
    if (!chainManager?.isOpen || !stateManager) return;
    const step = chainManager.getCurrentStep();
    if (!step) return;
    const draft = stateManager.getDraft();
    chainManager.patchStep(chainManager.currentStepIndex, {
        roleId: draft.roleId,
        taskId: draft.taskId,
        parameterValues: draft.parameterValues,
        previousOutput: String(draft.parameterValues.previousOutput ?? step.previousOutput ?? ''),
    });
}

function renderModeToggle() {
    document.querySelectorAll('[data-ps-mode]').forEach((btn) => {
        const mode = btn.getAttribute('data-ps-mode');
        btn.classList.toggle('prompt-studio__mode-btn--active', mode === getUiMode());
    });
}

function renderAll() {
    renderModeToggle();
    syncAreaUi();
    syncPromptLocaleUi();
    applyPreviewVisibility();
    syncKindUi();
    syncSelectionSummary();
    renderBuilder();
    renderVariantsPanel();
    renderPreview();
    renderWorkspace();
    renderChainPanel();
    renderContinuePanel();
}

function handleBridgeReturn() {
    const loaded = loadPromptBridge();
    if (!loaded || !('payload' in loaded) || loaded.payload.direction !== 'to-studio') return;
    if (!bridgeBanner) return;

    bridgeBanner.hidden = false;
    bridgeBanner.innerHTML = `
        <p>${tr('promptStudio.bridge.banner')}</p>
        <button type="button" class="tools-btn tools-btn--sm" id="ps-bridge-apply">${tr('promptStudio.bridge.apply')}</button>
        <button type="button" class="tools-btn tools-btn--sm" id="ps-bridge-dismiss">${tr('promptStudio.bridge.dismiss')}</button>
    `;

    document.getElementById('ps-bridge-apply')?.addEventListener('click', () => {
        if (!stateManager) return;
        const text = loaded.payload.prompt.compiled;
        const ctx = loaded.payload.studioContext;
        if (ctx?.roleId && ctx?.taskId) stateManager.setRoleAndTask(ctx.roleId, ctx.taskId);
        if (ctx?.modelId) stateManager.setModel(ctx.modelId);
        if (ctx?.parameterValues) stateManager.setParameterValues(ctx.parameterValues);
        stateManager.setSection('user', text);
        bridgeBanner.hidden = true;
        renderAll();
    });

    document.getElementById('ps-bridge-dismiss')?.addEventListener('click', () => {
        bridgeBanner.hidden = true;
    });
}

function bindEvents() {
    document.querySelectorAll('.prompt-studio__area-btn[data-ps-area]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const area = normalizeStudioArea(btn.getAttribute('data-ps-area'));
            applyStudioArea(area);
        });
    });

    // Exclusive accordion: only one help panel open at a time (keeps drawer height stable).
    helpAccordion?.querySelectorAll(':scope > details[data-ps-help-item]').forEach((item) => {
        item.addEventListener('toggle', () => {
            if (!(/** @type {HTMLDetailsElement} */ (item).open) || !helpAccordion) return;
            helpAccordion.querySelectorAll(':scope > details[data-ps-help-item]').forEach((other) => {
                if (other !== item) /** @type {HTMLDetailsElement} */ (other).open = false;
            });
        });
    });

    document.querySelectorAll('[data-ps-prompt-locale]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const locale = normalizePromptLocale(btn.getAttribute('data-ps-prompt-locale'));
            applyPromptLocale(locale);
        });
    });

    document.querySelectorAll('[data-ps-mode]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const mode = btn.getAttribute('data-ps-mode');
            if (mode === 'regular' || mode === 'tech') {
                setUiMode(mode);
                applyUiModeClasses(app);
                // Roles tab is tech-only — leave it when switching to regular.
                if (mode === 'regular' && workspaceManager?.activeTab === 'roles') {
                    workspaceManager.setTab('library');
                    document.querySelectorAll('[data-ps-tab]').forEach((b) => b.classList.remove('prompt-studio__tab--active'));
                    document.querySelector('[data-ps-tab="library"]')?.classList.add('prompt-studio__tab--active');
                }
                renderAll();
            }
        });
    });

    libraryDrawer?.addEventListener('toggle', () => {
        if (libraryDrawer) setLibraryDrawerOpen(libraryDrawer.open);
    });

    previewToggleBtn?.addEventListener('click', () => togglePreviewOpen());
    previewCollapseBtn?.addEventListener('click', () => togglePreviewOpen());
    previewReopenBtn?.addEventListener('click', () => {
        setPreviewDrawerOpen(true);
        applyPreviewVisibility();
    });

    downloadMdBtn?.addEventListener('click', () => {
        if (!stateManager) return;
        const draft = stateManager.getDraft();
        const kind = normalizeOutputKind(draft.kind);
        const title = draft.title || draft.taskId || 'document';
        downloadTextFile(getCompiledForDisplay(), markdownFilename(title, kind));
    });

    templateKindFilter?.addEventListener('change', () => {
        const value = templateKindFilter.value === 'all' ? 'all' : normalizeOutputKind(templateKindFilter.value);
        workspaceManager?.setTemplateKindFilter(value);
        renderWorkspace();
    });

    categoryFilter?.addEventListener('change', () => {
        const value = categoryFilter.value === 'all' ? 'all' : categoryFilter.value;
        workspaceManager?.setCategoryFilter(/** @type {import('./categories.js').PromptCategory | 'all'} */ (value));
        renderWorkspace();
    });

    addRoleBtn?.addEventListener('click', () => {
        const name = window.prompt(tr('promptStudio.roles.addPrompt'), '');
        if (!name || !workspaceManager) return;
        workspaceManager.addCustomRole(name);
        void syncLibraryToAccount();
        renderBuilder();
        renderWorkspace();
    });

    saveWorkflowLibraryBtn?.addEventListener('click', () => saveCurrentWorkflow());
    document.getElementById('ps-chain-save-btn')?.addEventListener('click', () => saveCurrentWorkflow());

    roleSelect?.addEventListener('change', () => {
        if (!roleSelect.value) return;
        stateManager?.setRole(roleSelect.value);
        applySongForbiddenDefaults();
        renderAll();
    });

    taskSelect?.addEventListener('change', () => {
        if (!taskSelect.value) return;
        stateManager?.setTask(taskSelect.value);
        applySongForbiddenDefaults();
        renderAll();
    });

    modelSelect?.addEventListener('change', () => {
        stateManager?.setModel(modelSelect.value);
        renderAll();
    });

    document.querySelectorAll('[data-ps-plan]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const plan = normalizeModelPlan(btn.getAttribute('data-ps-plan'));
            stateManager?.setModelPlan(plan);
            renderAll();
        });
    });

    document.querySelectorAll('[data-ps-tab]').forEach((btn) => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('[data-ps-tab]').forEach((b) => b.classList.remove('prompt-studio__tab--active'));
            btn.classList.add('prompt-studio__tab--active');
            workspaceManager?.setTab(/** @type {import('./workspace-manager.js').WorkspaceTab} */ (btn.getAttribute('data-ps-tab')));
            renderWorkspace();
        });
    });

    // Ensure default active tab matches DOM (Prompts / library)
    document.querySelector('[data-ps-tab="library"]')?.classList.add('prompt-studio__tab--active');
    document.querySelectorAll('[data-ps-tab]:not([data-ps-tab="library"])').forEach((b) => b.classList.remove('prompt-studio__tab--active'));
    workspaceManager?.setTab('library');

    document.getElementById('ps-search')?.addEventListener('input', (event) => {
        const target = /** @type {HTMLInputElement} */ (event.target);
        workspaceManager?.setSearch(target.value);
        renderWorkspace();
    });

    workspaceList?.addEventListener('click', (event) => {
        const target = /** @type {HTMLElement} */ (event.target);
        const favBtn = target.closest('[data-fav-id]');
        if (favBtn) {
            const id = favBtn.getAttribute('data-fav-id');
            if (id) workspaceManager?.toggleFavorite(id);
            renderWorkspace();
            return;
        }

        const itemBtn = target.closest('[data-item-id]');
        if (!itemBtn || !config || !stateManager) return;
        const itemId = itemBtn.getAttribute('data-item-id');
        const roleId = itemBtn.getAttribute('data-role-id');
        const taskId = itemBtn.getAttribute('data-task-id');
        const workflowId = itemBtn.getAttribute('data-workflow-id');
        if (!itemId) return;

        if (workflowId) {
            if (itemId.startsWith('user-workflow:')) {
                const loaded = loadChains();
                const list = loaded && 'data' in loaded ? loaded.data : [];
                const entry = list.find((c) => c.id === workflowId);
                if (entry) chainManager?.loadUserChain(entry, currentLocale());
            } else {
                chainManager?.loadChain(workflowId, currentLocale());
            }
            syncChainStepToBuilder();
            renderAll();
            focusChainPanel();
            return;
        }

        if (itemId.startsWith('task:') && taskId && roleId) {
            // Single task from library — leave any open workflow so the prompt stays standalone.
            chainManager?.clear();
            const task = config.tasks.find((t) => t.id === taskId);
            if (task) setStudioArea(areaForOutputKind(getTaskOutputKind(task)));
            stateManager.setRoleAndTask(roleId, taskId);
            applySongForbiddenDefaults();
            renderAll();
        } else if (itemId.startsWith('user:')) {
            const tplId = itemId.replace('user:', '');
            const tpl = templateStore?.getById(tplId);
            if (tpl?.draft) {
                if (tpl.draft.kind) setStudioArea(areaForOutputKind(normalizeOutputKind(tpl.draft.kind)));
                stateManager.loadDraft(tpl.draft);
            }
            renderAll();
        } else if (itemId.startsWith('recent:')) {
            if (roleId && taskId) {
                const task = config.tasks.find((t) => t.id === taskId);
                if (task) setStudioArea(areaForOutputKind(getTaskOutputKind(task)));
                stateManager.setRoleAndTask(roleId, taskId);
                applySongForbiddenDefaults();
                renderAll();
            }
        } else if (itemId.startsWith('role:') || itemId.startsWith('custom-role:')) {
            if (roleId) {
                stateManager.setRole(roleId);
                applySongForbiddenDefaults();
                renderAll();
            }
        }
    });

    document.getElementById('ps-undo-btn')?.addEventListener('click', () => {
        stateManager?.undo();
        renderAll();
    });
    document.getElementById('ps-redo-btn')?.addEventListener('click', () => {
        stateManager?.redo();
        renderAll();
    });

    document.getElementById('ps-copy-btn')?.addEventListener('click', async () => {
        if (!stateManager || !config) return;
        const parameters = getParametersForTask(stateManager.getDraft().taskId, config);
        const missing = validateRequiredParameters(parameters, stateManager.getDraft().parameterValues);
        if (missing.length > 0) {
            window.alert(tr('promptStudio.validation.missingRequired'));
            return;
        }
        const btn = /** @type {HTMLButtonElement} */ (document.getElementById('ps-copy-btn'));
        await copyFromButton(btn, getCompiledForDisplay(), (k) => tr(k));
        const draft = stateManager.getDraft();
        const task = config.tasks.find((t) => t.id === draft.taskId);
        pushRecentEntry({
            id: `recent_${Date.now()}`,
            title: task?.label?.[currentLocale()] ?? `${draft.roleId} / ${draft.taskId}`,
            roleId: draft.roleId,
            taskId: draft.taskId,
            modelId: draft.modelId,
            compiled: getCompiledForDisplay(),
            savedAt: new Date().toISOString(),
        });
    });

    document.getElementById('ps-sanitize-btn')?.addEventListener('click', () => {
        if (!stateManager) return;
        const draft = stateManager.getDraft();
        saveToSanitizer({
            compiled: getCompiledForDisplay(),
            sections: draft.sections,
            studioContext: {
                roleId: draft.roleId,
                taskId: draft.taskId,
                modelId: draft.modelId,
                parameterValues: draft.parameterValues,
            },
        });
        window.location.href = localeToolPath('governance-ai-sanitizer');
    });

    document.getElementById('ps-save-template-btn')?.addEventListener('click', () => {
        if (!stateManager || !templateStore) return;
        const draft = stateManager.getDraft();
        const name = window.prompt(tr('promptStudio.saveTemplate.prompt'), `${draft.roleId}-${draft.taskId}`);
        if (!name) return;
        templateStore.save(name, draft);
        workspaceManager?.refreshUserTemplates(templateStore.list());
        void syncLibraryToAccount();
        renderWorkspace();
    });

    document.getElementById('ps-export-btn')?.addEventListener('click', () => {
        downloadExportBundle(buildExportBundle());
    });

    document.getElementById('ps-import-btn')?.addEventListener('click', () => {
        document.getElementById('ps-import-file')?.click();
    });

    document.getElementById('ps-import-file')?.addEventListener('change', async (event) => {
        const input = /** @type {HTMLInputElement} */ (event.target);
        const file = input.files?.[0];
        if (!file) return;
        try {
            const text = await file.text();
            const bundle = JSON.parse(text);
            importBundle(bundle);
            if (stateManager) {
                const draft = buildExportBundle({ includeWorkspace: false, includeTemplates: false }).draft;
                if (draft) stateManager.loadDraft(draft);
            }
            workspaceManager?.reload();
            templateStore?.reload();
            renderAll();
        } catch {
            window.alert(tr('promptStudio.validation.importFailed'));
        }
        input.value = '';
    });

    document.getElementById('ps-chain-btn')?.addEventListener('click', () => {
        chainManager?.toggleOpen();
        renderChainPanel();
        if (chainManager?.isOpen) focusChainPanel();
    });

    chainSteps?.addEventListener('click', async (event) => {
        const target = /** @type {HTMLElement} */ (event.target);
        if (!chainManager || !stateManager || !config) return;

        const loadBtn = target.closest('.ps-chain-load');
        if (loadBtn) {
            const index = Number(loadBtn.getAttribute('data-step-index'));
            saveCurrentStepFromBuilder();
            chainManager.setCurrentStepIndex(index);
            syncChainStepToBuilder();
            renderAll();
            return;
        }

        const copyBtn = target.closest('.ps-chain-copy');
        if (copyBtn) {
            const index = Number(copyBtn.getAttribute('data-step-index'));
            const step = chainManager.activeSteps[index];
            if (!step) return;
            const compiled = processCompiledOutput(
                chainManager.compileStep(step, stateManager?.getPromptLocale() ?? currentLocale()),
                step.parameterValues ?? {},
            );
            await copyFromButton(/** @type {HTMLButtonElement} */ (copyBtn), compiled, (k) => tr(k));
            return;
        }

        const sanitizeBtn = target.closest('.ps-chain-sanitize');
        if (sanitizeBtn) {
            const index = Number(sanitizeBtn.getAttribute('data-step-index'));
            const step = chainManager.activeSteps[index];
            if (!step) return;
            const compiled = processCompiledOutput(
                chainManager.compileStep(step, stateManager?.getPromptLocale() ?? currentLocale()),
                step.parameterValues ?? {},
            );
            saveToSanitizer({
                compiled,
                sections: { user: compiled },
                studioContext: {
                    roleId: step.roleId,
                    taskId: step.taskId,
                    chainStepId: step.id,
                    chainId: chainManager.presetId,
                },
            });
            window.location.href = localeToolPath('governance-ai-sanitizer');
            return;
        }

        const upBtn = target.closest('.ps-chain-up');
        if (upBtn) {
            chainManager.moveStep(Number(upBtn.getAttribute('data-index')), Number(upBtn.getAttribute('data-index')) - 1);
            renderChainPanel();
            return;
        }

        const downBtn = target.closest('.ps-chain-down');
        if (downBtn) {
            chainManager.moveStep(Number(downBtn.getAttribute('data-index')), Number(downBtn.getAttribute('data-index')) + 1);
            renderChainPanel();
            return;
        }

        const removeBtn = target.closest('.ps-chain-remove');
        if (removeBtn) {
            const stepId = removeBtn.getAttribute('data-step-id');
            if (stepId) chainManager.removeStep(stepId);
            renderChainPanel();
        }
    });

    document.getElementById('ps-chain-prev-btn')?.addEventListener('click', () => {
        saveCurrentStepFromBuilder();
        chainManager?.prevStep();
        syncChainStepToBuilder();
        renderAll();
    });

    document.getElementById('ps-chain-next-btn')?.addEventListener('click', () => {
        saveCurrentStepFromBuilder();
        chainManager?.nextStep();
        syncChainStepToBuilder();
        renderAll();
    });

    document.getElementById('ps-chain-add-btn')?.addEventListener('click', () => {
        if (!stateManager || !chainManager) return;
        const draft = stateManager.getDraft();
        chainManager.addStep({ roleId: draft.roleId, taskId: draft.taskId, parameterValues: draft.parameterValues });
        renderChainPanel();
    });

    document.getElementById('ps-chain-custom-btn')?.addEventListener('click', () => {
        const goal = window.prompt(tr('promptStudio.chain.customPrompt'), '');
        chainManager?.addCustomBlock(goal ?? '', currentLocale());
        renderChainPanel();
    });

    variantsEnabled?.addEventListener('change', () => {
        variantsManager?.setEnabled(variantsEnabled.checked);
        renderVariantsPanel();
        renderPreview();
    });

    variantsField?.addEventListener('change', () => {
        variantsManager?.setVaryingField(variantsField.value);
        renderVariantsPanel();
        renderPreview();
    });

    document.getElementById('ps-variants-add-btn')?.addEventListener('click', () => {
        variantsManager?.addValuesFromText(variantsBulk?.value ?? '');
        if (variantsBulk) variantsBulk.value = '';
        renderVariantsPanel();
        renderPreview();
    });

    document.getElementById('ps-variants-prev')?.addEventListener('click', () => {
        variantsManager?.prev();
        if (stateManager && variantsManager) {
            const draft = variantsManager.applyCurrentToDraft(stateManager.getDraft());
            stateManager.setParameterValues(draft.parameterValues);
        }
        renderVariantsPanel();
        renderBuilder();
        renderPreview();
    });

    document.getElementById('ps-variants-next')?.addEventListener('click', () => {
        variantsManager?.next();
        if (stateManager && variantsManager) {
            const draft = variantsManager.applyCurrentToDraft(stateManager.getDraft());
            stateManager.setParameterValues(draft.parameterValues);
        }
        renderVariantsPanel();
        renderBuilder();
        renderPreview();
    });

    document.getElementById('ps-variants-copy-all')?.addEventListener('click', async () => {
        if (!stateManager || !variantsManager) return;
        const all = variantsManager.compileAll(stateManager.getDraft());
        const text = all.map((item) => `#${item.index} ${item.value}\n${item.compiled}`).join('\n\n---\n\n');
        const btn = /** @type {HTMLButtonElement} */ (document.getElementById('ps-variants-copy-all'));
        await copyFromButton(btn, text, (k) => tr(k));
    });

    document.getElementById('ps-continue-btn')?.addEventListener('click', () => {
        if (!stateManager || !continueManager) return;
        continueManager.toggleOpen();
        const compiled = stateManager.getCompiledPrompt();
        const response = continueResponse?.value ?? '';
        continueManager.addTurn(compiled, response);
        stateManager.setParameterValues({ conversationHistory: continueManager.getHistoryText() });
        renderContinuePanel();
        renderPreview();
    });

    document.getElementById('ps-optimize-btn')?.addEventListener('click', () => {
        if (!stateManager || !config || !metaPanel || !metaPre || !metaTitle) return;
        const draft = stateManager.getDraft();
        const meta = generateOptimizerMetaPrompt(config, {
            compiledPrompt: stateManager.getCompiledPrompt(),
            roleId: draft.roleId,
            taskId: draft.taskId,
            modelId: draft.modelId,
            parameterValues: draft.parameterValues,
            locale: stateManager.getPromptLocale(),
        });
        activeMetaPrompt = meta.body;
        metaTitle.textContent = meta.title;
        metaPre.textContent = meta.body;
        metaPanel.hidden = false;
    });

    document.getElementById('ps-split-btn')?.addEventListener('click', () => {
        if (!stateManager || !config || !metaPanel || !metaPre || !metaTitle) return;
        const draft = stateManager.getDraft();
        const goal = window.prompt(tr('promptStudio.split.prompt'), String(draft.parameterValues.goal ?? ''));
        const meta = generateSplitMetaPrompt(config, {
            compiledPrompt: stateManager.getCompiledPrompt(),
            roleId: draft.roleId,
            taskId: draft.taskId,
            modelId: draft.modelId,
            parameterValues: { ...draft.parameterValues, goal: goal ?? '' },
            goal: goal ?? '',
            locale: stateManager.getPromptLocale(),
        });
        activeMetaPrompt = meta.body;
        metaTitle.textContent = meta.title;
        metaPre.textContent = meta.body;
        metaPanel.hidden = false;
    });

    document.getElementById('ps-meta-copy-btn')?.addEventListener('click', async () => {
        const btn = /** @type {HTMLButtonElement} */ (document.getElementById('ps-meta-copy-btn'));
        await copyFromButton(btn, activeMetaPrompt, (k) => tr(k));
    });

    document.getElementById('ps-meta-apply-btn')?.addEventListener('click', () => {
        if (!stateManager || !activeMetaPrompt) return;
        stateManager.setSection('user', activeMetaPrompt);
        if (metaPanel) metaPanel.hidden = true;
        renderPreview();
    });

    document.getElementById('ps-meta-sanitize-btn')?.addEventListener('click', () => {
        if (!stateManager) return;
        const draft = stateManager.getDraft();
        saveToSanitizer({
            compiled: activeMetaPrompt,
            sections: { user: activeMetaPrompt },
            studioContext: {
                roleId: draft.roleId,
                taskId: draft.taskId,
                modelId: draft.modelId,
                parameterValues: draft.parameterValues,
            },
        });
        window.location.href = localeToolPath('governance-ai-sanitizer');
    });

    stateManager?.subscribe(() => {
        renderPreview();
        document.getElementById('ps-undo-btn')?.toggleAttribute('disabled', !stateManager?.canUndo());
        document.getElementById('ps-redo-btn')?.toggleAttribute('disabled', !stateManager?.canRedo());
    });

    window.addEventListener('binom-tools:locale', () => {
        const locale = currentLocale();
        applyShellLabels(locale);
        applyPromptStudioLabels(locale);
        applyHowtoLabels(locale);
        // Keep prompt preview language independent of the page chrome language.
        stateManager?.setLocale(locale);
        renderAll();
        syncPromptLocaleUi();
    });
}

async function init() {
    const locale = currentLocale();
    applyShellLabels(locale);
    applyPromptStudioLabels(locale);
    applyHowtoLabels(locale);
    applyUiModeClasses(app);
    setUiMode(getUiMode());
    setStudioArea(getStudioArea());

    if (libraryDrawer) libraryDrawer.open = isLibraryDrawerOpen();
    if (helpDrawer) helpDrawer.open = false;

    config = await loadConfig(configBase);

    ensureStarterTemplates();
    templateStore = new TemplateStore();
    workspaceManager = new WorkspaceManager(config, { userTemplates: templateStore.list() });
    stateManager = createStateManager(config);
    stateManager.setLocale(locale);
    stateManager.setPromptLocale(getPromptLocale(locale), { recompile: false });

    variantsManager = new VariantsManager(config);
    variantsManager.patch(createDefaultVariants());
    // Boot without restoring an in-progress workflow — empty start.
    chainManager = new ChainManager(config);
    chainManager.clear();
    continueManager = new ContinueManager();

    await hydrateLibraryFromAccount();
    workspaceManager.refreshUserTemplates(templateStore.list());

    if (config.models[0]) {
        stateManager.setModel(config.models[0].id);
    }

    applyPreviewVisibility();

    bindEvents();
    handleBridgeReturn();
    renderAll();
}

init().catch((error) => {
    console.error('Prompt Studio failed to initialize', error);
    if (!app) return;
    const detail = error instanceof Error ? error.message : String(error);
    app.innerHTML = `
        <div class="tools-validation-banner tools-validation-banner--has-errors">
            <p>${escapeHtml(tr('promptStudio.validation.configFailed'))}</p>
            <p class="tools-panel-meta">${escapeHtml(detail)}</p>
            <p class="tools-panel-meta">${escapeHtml(tr('promptStudio.validation.configFailedHint', { base: configBase }))}</p>
        </div>
    `;
});
