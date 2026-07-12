import { buildPrompt, formatForModel } from './prompt-builder.js';
import { getTemplate } from './config-loader.js';
import { t } from './labels.js';
import { resolveLocalizedLabel } from './localized-label.js';
import { debouncedSaveSession } from './session-store.js';

/** @typedef {import('./config-validator.js').PromptStudioConfig} PromptStudioConfig */
/** @typedef {import('./config-validator.js').ToolsLocale} ToolsLocale */
/** @typedef {import('./session-store.js').PromptSessionChain} PromptSessionChain */
/** @typedef {import('./session-store.js').PromptSessionChainStep} PromptSessionChainStep */

/**
 * Prompt chain workflow assistant and editor.
 */
export class ChainManager {
    /**
     * @param {PromptStudioConfig} config
     * @param {PromptSessionChain} [initialChain]
     */
    constructor(config, initialChain) {
        this.config = config;
        /** @type {string | null} */
        this.presetId = initialChain?.presetId ?? null;
        /** @type {number} */
        this.currentStepIndex = initialChain?.currentStepIndex ?? 0;
        /** @type {boolean} */
        this.isOpen = initialChain?.isOpen ?? false;
        /** @type {PromptSessionChainStep[]} */
        this.activeSteps = initialChain?.steps?.length ? structuredClone(initialChain.steps) : [];
    }

    /** @returns {PromptSessionChain} */
    toSessionState() {
        return {
            presetId: this.presetId,
            currentStepIndex: this.currentStepIndex,
            isOpen: this.isOpen,
            steps: structuredClone(this.activeSteps),
        };
    }

    persist() {
        debouncedSaveSession({ activeChain: this.toSessionState() });
    }

    toggleOpen() {
        this.isOpen = !this.isOpen;
        this.persist();
    }

    open() {
        this.isOpen = true;
        this.persist();
    }

    /** @param {string} chainId */
    loadChain(chainId, locale = 'en') {
        const def = this.config.chains.find((c) => c.id === chainId);
        if (!def) return;
        this.loadFromConfig(def, locale);
        this.isOpen = true;
        this.currentStepIndex = 0;
        this.persist();
    }

    /**
     * @param {import('./config-validator.js').PromptChainDef} chainDef
     */
    loadFromConfig(chainDef, locale = 'en') {
        this.presetId = chainDef.id;
        this.activeSteps = chainDef.steps.map((step, index) => ({
            id: step.id ?? `step_${index + 1}`,
            roleId: step.roleId,
            taskId: step.taskId,
            label: resolveLocalizedLabel(step.label, locale, step.taskId),
            parameterValues: {},
            previousOutput: '',
        }));
        return this.activeSteps;
    }

    /** @returns {PromptSessionChainStep | null} */
    getCurrentStep() {
        return this.activeSteps[this.currentStepIndex] ?? null;
    }

    /** @param {number} index */
    setCurrentStepIndex(index) {
        if (index < 0 || index >= this.activeSteps.length) return;
        this.currentStepIndex = index;
        this.persist();
    }

    nextStep() {
        if (this.currentStepIndex < this.activeSteps.length - 1) {
            this.currentStepIndex += 1;
            this.persist();
        }
    }

    prevStep() {
        if (this.currentStepIndex > 0) {
            this.currentStepIndex -= 1;
            this.persist();
        }
    }

    /**
     * @param {number} index
     * @param {Partial<PromptSessionChainStep>} patch
     */
    patchStep(index, patch) {
        if (!this.activeSteps[index]) return;
        this.activeSteps[index] = { ...this.activeSteps[index], ...patch };
        this.persist();
    }

    /** @param {PromptSessionChainStep} step */
    addStep(step) {
        this.activeSteps = [
            ...this.activeSteps,
            {
                id: step.id ?? `step_${this.activeSteps.length + 1}`,
                roleId: step.roleId,
                taskId: step.taskId,
                label: step.label,
                parameterValues: step.parameterValues ?? {},
                previousOutput: step.previousOutput ?? '',
            },
        ];
        this.persist();
        return this.activeSteps;
    }

    /**
     * @param {string} goal
     * @param {ToolsLocale} [locale]
     */
    addCustomBlock(goal = '', locale = 'en') {
        return this.addStep({
            id: `custom_${Date.now().toString(36)}`,
            roleId: '',
            taskId: 'custom-block',
            label: t(locale, 'promptStudio.chain.customBlock'),
            parameterValues: { goal },
        });
    }

    /** @param {string} stepId */
    removeStep(stepId) {
        this.activeSteps = this.activeSteps.filter((step) => step.id !== stepId);
        if (this.currentStepIndex >= this.activeSteps.length) {
            this.currentStepIndex = Math.max(0, this.activeSteps.length - 1);
        }
        this.persist();
    }

    /**
     * @param {number} fromIndex
     * @param {number} toIndex
     */
    moveStep(fromIndex, toIndex) {
        if (fromIndex < 0 || toIndex < 0 || fromIndex >= this.activeSteps.length || toIndex >= this.activeSteps.length) {
            return;
        }
        const next = [...this.activeSteps];
        const [item] = next.splice(fromIndex, 1);
        next.splice(toIndex, 0, item);
        this.activeSteps = next;
        this.persist();
    }

    /**
     * @param {PromptSessionChainStep} step
     * @param {ToolsLocale} [locale]
     * @returns {string}
     */
    compileStep(step, locale = 'en') {
        const task = this.config.tasks.find((t) => t.id === step.taskId);
        const templateId = task?.templateId ?? (step.taskId === 'custom-block' ? 'custom-block' : step.taskId);
        const template = getTemplate(templateId, this.config);
        const model = this.config.models[0];
        if (!template) return '';

        const role = this.config.roles.find((r) => r.id === step.roleId);
        const built = buildPrompt({
            template,
            parameterValues: {
                ...(step.parameterValues ?? {}),
                previousOutput: step.previousOutput ?? '',
                goal: step.parameterValues?.goal ?? '',
            },
            model,
            extraContext: {
                roleId: step.roleId,
                taskId: step.taskId,
                roleLabel: resolveLocalizedLabel(role?.label, locale, step.roleId),
                taskLabel: resolveLocalizedLabel(task?.label, locale, step.taskId),
            },
            locale,
        });

        return formatForModel(built.sections, model);
    }

    /**
     * @param {ToolsLocale} locale
     * @returns {string}
     */
    getLearningTip(locale = 'en') {
        if (!this.presetId) return '';
        const def = this.config.chains.find((c) => c.id === this.presetId);
        const stepDef = def?.steps?.[this.currentStepIndex];
        if (!stepDef || typeof stepDef !== 'object') return '';
        const tip = stepDef.learningTip;
        if (tip && typeof tip === 'object') return tip[locale] ?? tip.en ?? '';
        const notes = stepDef.notes;
        if (notes && typeof notes === 'object') return notes[locale] ?? notes.en ?? '';
        return '';
    }

    /**
     * @param {ToolsLocale} locale
     * @returns {string}
     */
    renderStepsHtml(locale = 'en') {
        if (this.activeSteps.length === 0) {
            return `<p class="prompt-studio__workspace-empty">${t(locale, 'promptStudio.chain.empty')}</p>`;
        }

        const chainDef = this.presetId ? this.config.chains.find((c) => c.id === this.presetId) : null;

        return this.activeSteps
            .map((step, index) => {
                const task = this.config.tasks.find((t) => t.id === step.taskId);
                const taskLabel = resolveLocalizedLabel(task?.label, locale, step.taskId);
                const role = this.config.roles.find((r) => r.id === step.roleId);
                const roleLabel = resolveLocalizedLabel(role?.label, locale, step.roleId);
                const stepDef = chainDef?.steps?.[index];
                let displayLabel = resolveLocalizedLabel(step.label, locale, taskLabel);
                if (step.taskId === 'custom-block') {
                    displayLabel = t(locale, 'promptStudio.chain.customBlock');
                } else if (stepDef?.label) {
                    displayLabel = resolveLocalizedLabel(stepDef.label, locale, displayLabel);
                }
                const isActive = index === this.currentStepIndex;
                return `<article class="prompt-studio__chain-step${isActive ? ' prompt-studio__chain-step--active' : ''}" data-step-index="${index}" data-step-id="${escapeAttr(step.id)}">
                <div class="prompt-studio__chain-step-header">
                    <strong>${index + 1}. ${escapeHtml(displayLabel)}</strong>
                    <button type="button" class="tools-btn tools-btn--sm ps-chain-load" data-step-index="${index}">${t(locale, 'promptStudio.chain.load')}</button>
                </div>
                <div class="prompt-studio__chain-step-meta">${escapeHtml(roleLabel)} · ${escapeHtml(taskLabel)}</div>
                <div class="prompt-studio__chain-step-actions">
                    <button type="button" class="tools-btn tools-btn--sm ps-chain-copy" data-step-index="${index}">${t(locale, 'promptStudio.chain.copy')}</button>
                    <button type="button" class="tools-btn tools-btn--sm ps-chain-sanitize" data-step-index="${index}">${t(locale, 'promptStudio.sanitize')}</button>
                    <button type="button" class="tools-btn tools-btn--sm ps-chain-up prompt-studio__toolbar-group--tech" data-index="${index}" ${index === 0 ? 'disabled' : ''}>↑</button>
                    <button type="button" class="tools-btn tools-btn--sm ps-chain-down prompt-studio__toolbar-group--tech" data-index="${index}" ${index === this.activeSteps.length - 1 ? 'disabled' : ''}>↓</button>
                    <button type="button" class="tools-btn tools-btn--sm ps-chain-remove prompt-studio__toolbar-group--tech" data-step-id="${escapeAttr(step.id)}" aria-label="${escapeAttr(t(locale, 'promptStudio.chain.remove'))}">×</button>
                </div>
            </article>`;
            })
            .join('');
    }
}

/**
 * @param {string} value
 * @returns {string}
 */
function escapeAttr(value) {
    return value.replaceAll('&', '&amp;').replaceAll('"', '&quot;').replaceAll('<', '&lt;');
}

/**
 * @param {string} value
 * @returns {string}
 */
function escapeHtml(value) {
    return escapeAttr(value).replaceAll('>', '&gt;');
}

export function createChainManager(config, initialChain) {
    return new ChainManager(config, initialChain);
}
