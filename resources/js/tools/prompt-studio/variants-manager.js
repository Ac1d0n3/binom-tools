import { buildPrompt, formatForModel } from './prompt-builder.js';
import { getTemplate } from './config-loader.js';
import { createDefaultVariants, debouncedSaveSession, loadSession, normalizeSession } from './session-store.js';

/** @typedef {import('./config-validator.js').PromptStudioConfig} PromptStudioConfig */
/** @typedef {import('./session-store.js').PromptSessionVariants} PromptSessionVariants */
/** @typedef {import('./storage.js').PromptDraftState} PromptDraftState */

/**
 * Manages variant lists (e.g. 6 images with different motifs).
 */
export class VariantsManager {
    /** @param {PromptStudioConfig} config */
    constructor(config) {
        this.config = config;
        const session = loadSession();
        if (session && 'data' in session) {
            const normalized = normalizeSession({ variants: session.data.variants });
            this.variants = normalized.variants;
        } else {
            this.variants = createDefaultVariants();
        }
    }

    /** @returns {PromptSessionVariants} */
    getState() {
        return { ...this.variants, values: [...this.variants.values] };
    }

    /** @param {Partial<PromptSessionVariants>} patch */
    patch(patch) {
        this.variants = {
            ...this.variants,
            ...patch,
            values: patch.values ? [...patch.values] : [...this.variants.values],
        };
        if (this.variants.currentIndex >= this.variants.values.length) {
            this.variants.currentIndex = Math.max(0, this.variants.values.length - 1);
        }
        debouncedSaveSession({ variants: this.variants });
    }

    /** @param {boolean} enabled */
    setEnabled(enabled) {
        this.patch({ enabled });
    }

    /** @param {string} fieldId */
    setVaryingField(fieldId) {
        this.patch({ varyingFieldId: fieldId });
    }

    /** @param {string[]} values */
    setValues(values) {
        this.patch({ values: values.filter(Boolean), currentIndex: 0 });
    }

    /** @param {string} value */
    addValue(value) {
        const trimmed = value.trim();
        if (!trimmed) return;
        this.patch({ values: [...this.variants.values, trimmed] });
    }

    /** @param {string} bulkText */
    addValuesFromText(bulkText) {
        const extra = bulkText
            .split('\n')
            .map((v) => v.trim())
            .filter(Boolean);
        if (extra.length === 0) return;
        this.patch({ values: [...this.variants.values, ...extra] });
    }

    next() {
        if (this.variants.values.length === 0) return;
        const nextIndex = (this.variants.currentIndex + 1) % this.variants.values.length;
        this.patch({ currentIndex: nextIndex });
    }

    prev() {
        if (this.variants.values.length === 0) return;
        const nextIndex =
            (this.variants.currentIndex - 1 + this.variants.values.length) % this.variants.values.length;
        this.patch({ currentIndex: nextIndex });
    }

    /**
     * @param {PromptDraftState} baseDraft
     * @returns {PromptDraftState}
     */
    applyCurrentToDraft(baseDraft) {
        if (!this.variants.enabled || this.variants.values.length === 0) return baseDraft;
        const value = this.variants.values[this.variants.currentIndex];
        return {
            ...baseDraft,
            parameterValues: {
                ...baseDraft.parameterValues,
                [this.variants.varyingFieldId]: value,
            },
        };
    }

    /**
     * @param {PromptDraftState} baseDraft
     * @returns {string}
     */
    compileCurrent(baseDraft) {
        const draft = this.applyCurrentToDraft(baseDraft);
        const task = this.config.tasks.find((t) => t.id === draft.taskId);
        if (!task) return '';
        const template = getTemplate(task.templateId, this.config);
        if (!template) return '';
        const model = this.config.models.find((m) => m.id === draft.modelId) ?? this.config.models[0];
        const role = this.config.roles.find((r) => r.id === draft.roleId);

        const built = buildPrompt({
            template,
            parameterValues: draft.parameterValues,
            model,
            extraContext: {
                roleId: draft.roleId,
                taskId: draft.taskId,
                modelId: draft.modelId,
                roleLabel: role?.label?.en ?? draft.roleId,
            },
        });

        const sections = { ...built.sections, ...draft.sections };
        return formatForModel(sections, model, { parameterValues: draft.parameterValues });
    }

    /**
     * @param {PromptDraftState} baseDraft
     * @returns {Array<{ index: number, value: string, compiled: string }>}
     */
    compileAll(baseDraft) {
        if (!this.variants.enabled || this.variants.values.length === 0) return [];

        return this.variants.values.map((value, index) => {
            const draft = {
                ...baseDraft,
                parameterValues: {
                    ...baseDraft.parameterValues,
                    [this.variants.varyingFieldId]: value,
                },
            };
            return { index: index + 1, value, compiled: this.compileForDraft(draft) };
        });
    }

    /**
     * @param {PromptDraftState} draft
     * @returns {string}
     */
    compileForDraft(draft) {
        const task = this.config.tasks.find((t) => t.id === draft.taskId);
        if (!task) return '';
        const template = getTemplate(task.templateId, this.config);
        if (!template) return '';
        const model = this.config.models.find((m) => m.id === draft.modelId) ?? this.config.models[0];
        const built = buildPrompt({
            template,
            parameterValues: draft.parameterValues,
            model,
            extraContext: { roleId: draft.roleId, taskId: draft.taskId },
        });
        return formatForModel({ ...built.sections, ...draft.sections }, model, {
            parameterValues: draft.parameterValues,
        });
    }
}
