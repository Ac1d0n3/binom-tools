import { createDefaultDraft, debouncedSaveDraft, normalizeDraft } from './storage.js';
import { resolveLocalizedLabel } from './localized-label.js';
import { debouncedSaveSession } from './session-store.js';
import { HistoryStack } from './history-stack.js';
import { PromptSections } from './prompt-sections.js';
import { buildPrompt, formatForModel } from './prompt-builder.js';
import { createDefaultParameterValues } from './field-renderer.js';
import { getParametersForTask, getTemplate, getTasksForRole } from './config-loader.js';
import { getTaskOutputKind } from './md-export.js';
import { preferModelForTask, normalizeModelPlan } from './model-limits.js';

/** @typedef {import('./config-validator.js').PromptStudioConfig} PromptStudioConfig */
/** @typedef {import('./storage.js').PromptDraftState} PromptDraftState */

/**
 * Central draft state for role, task, model, params, and preview sections.
 */
export class StateManager {
    /**
     * @param {PromptStudioConfig} config
     * @param {PromptDraftState} [initialDraft]
     */
    constructor(config, initialDraft) {
        this.config = config;
        /** @type {PromptDraftState} */
        this.draft = normalizeDraft(initialDraft ?? createDefaultDraft());
        /** @type {import('./config-validator.js').ToolsLocale} */
        this.locale = 'en';
        this.history = new HistoryStack();
        this.history.reset(this.draft);
        this.sections = new PromptSections(this.draft.sections, this.draft.sectionOverrides);
        /** @type {Set<(draft: PromptDraftState) => void>} */
        this.listeners = new Set();
    }

    /** @returns {PromptDraftState} */
    getDraft() {
        return {
            ...this.draft,
            parameterValues: { ...this.draft.parameterValues },
            sections: { ...this.draft.sections },
            sectionOverrides: { ...this.draft.sectionOverrides },
            tags: [...(this.draft.tags ?? [])],
        };
    }

    /** @param {(draft: PromptDraftState) => void} listener */
    subscribe(listener) {
        this.listeners.add(listener);
        return () => this.listeners.delete(listener);
    }

    notify() {
        for (const listener of this.listeners) {
            listener(this.getDraft());
        }
    }

    /**
     * @param {Partial<PromptDraftState>} patch
     * @param {{ recordHistory?: boolean, persist?: boolean, notify?: boolean }} [options]
     */
    patchDraft(patch, options = {}) {
        const recordHistory = options.recordHistory !== false;
        const persist = options.persist !== false;
        const notify = options.notify !== false;

        this.draft = normalizeDraft({ ...this.draft, ...patch });

        if (patch.sections) {
            this.sections.sections = { ...this.draft.sections };
        }
        if (patch.sectionOverrides) {
            this.sections.overrides = { ...this.draft.sectionOverrides };
        }

        if (recordHistory) {
            this.history.push(this.draft);
        }
        if (persist) {
            debouncedSaveDraft(this.draft, 'auto');
            debouncedSaveSession({ draft: this.draft });
        }

        if (notify) {
            this.notify();
        }
    }

    /** @param {string} roleId */
    setRole(roleId) {
        const tasks = getTasksForRole(roleId, this.config);
        const nextTaskId = tasks.some((t) => t.id === this.draft.taskId) ? this.draft.taskId : (tasks[0]?.id ?? '');
        this.setRoleAndTask(roleId, nextTaskId);
    }

    /**
     * @param {string} roleId
     * @param {string} taskId
     */
    setRoleAndTask(roleId, taskId) {
        const parameters = getParametersForTask(taskId, this.config);
        const parameterValues = createDefaultParameterValues(parameters, {});
        const task = this.config.tasks.find((t) => t.id === taskId);
        const modelId = preferModelForTask(task, this.draft.modelId, this.config.models);

        this.patchDraft({
            roleId,
            taskId,
            modelId,
            kind: getTaskOutputKind(task),
            parameterValues: { ...parameterValues, ...(task?.parameterDefaults ?? {}) },
            sections: {},
            sectionOverrides: {},
        }, { notify: false });

        this.sections = new PromptSections();
        this.recompile();
    }

    /** @param {string} taskId */
    setTask(taskId) {
        const parameters = getParametersForTask(taskId, this.config);
        const parameterValues = createDefaultParameterValues(parameters, this.draft.parameterValues);
        const task = this.config.tasks.find((t) => t.id === taskId);
        const modelId = preferModelForTask(task, this.draft.modelId, this.config.models);

        this.patchDraft({
            taskId,
            modelId,
            kind: getTaskOutputKind(task),
            parameterValues: {
                ...parameterValues,
                ...(task?.parameterDefaults ?? {}),
            },
            sections: {},
            sectionOverrides: {},
        }, { notify: false });

        this.sections = new PromptSections();
        this.recompile();
    }

    /** @param {string} modelId */
    setModel(modelId) {
        const model = this.config.models.find((m) => m.id === modelId);
        const modelPlan = model?.defaultPlan
            ? normalizeModelPlan(model.defaultPlan)
            : this.draft.modelPlan;
        this.patchDraft({ modelId, modelPlan }, { notify: false });
        this.recompile();
    }

    /** @param {import('./model-limits.js').ModelPlan} plan */
    setModelPlan(plan) {
        this.patchDraft({ modelPlan: normalizeModelPlan(plan) }, { notify: false });
        this.recompile();
    }

    /** @param {Record<string, unknown>} parameterValues */
    setParameterValues(parameterValues) {
        this.patchDraft({ parameterValues: { ...this.draft.parameterValues, ...parameterValues } }, { notify: false });
        this.recompile();
    }

    /** @param {string} sectionId @param {string} content */
    setSection(sectionId, content) {
        this.sections.setSection(sectionId, content);
        this.patchDraft({
            sections: this.sections.toMap(),
            sectionOverrides: { ...this.sections.overrides },
        }, { recordHistory: true });
    }

    /** @param {import('./config-validator.js').ToolsLocale} locale */
    setLocale(locale) {
        this.locale = locale;
    }

    recompile() {
        const task = this.config.tasks.find((t) => t.id === this.draft.taskId);
        if (!task) return { compiled: '', compiledList: [] };

        const template = getTemplate(task.templateId, this.config);
        if (!template) return { compiled: '', compiledList: [] };

        const model = this.config.models.find((m) => m.id === this.draft.modelId);
        const role = this.config.roles.find((r) => r.id === this.draft.roleId);
        const extraContext = {
            roleId: this.draft.roleId,
            taskId: this.draft.taskId,
            modelId: this.draft.modelId,
            roleLabel: resolveLocalizedLabel(role?.label, this.locale, this.draft.roleId),
            taskLabel: resolveLocalizedLabel(task.label, this.locale, this.draft.taskId),
            modelLabel: resolveLocalizedLabel(model?.label, this.locale, this.draft.modelId),
        };

        const built = buildPrompt({
            template,
            parameterValues: this.draft.parameterValues,
            model,
            extraContext,
            locale: this.locale,
        });

        this.sections.applyCompiled(built.compiledList, { preserveOverrides: true });

        const sections = this.sections.toMap();
        const compiled = formatForModel(sections, model);

        this.patchDraft(
            {
                sections,
                sectionOverrides: { ...this.sections.overrides },
            },
            { recordHistory: false, persist: true, notify: false },
        );

        this.notify();

        return { compiled, compiledList: built.compiledList, sections };
    }

    undo() {
        const previous = this.history.undo();
        if (!previous) return false;
        this.draft = normalizeDraft(previous);
        this.sections = new PromptSections(this.draft.sections, this.draft.sectionOverrides);
        debouncedSaveDraft(this.draft, 'auto');
        debouncedSaveSession({ draft: this.draft });
        this.notify();
        return true;
    }

    redo() {
        const next = this.history.redo();
        if (!next) return false;
        this.draft = normalizeDraft(next);
        this.sections = new PromptSections(this.draft.sections, this.draft.sectionOverrides);
        debouncedSaveDraft(this.draft, 'auto');
        debouncedSaveSession({ draft: this.draft });
        this.notify();
        return true;
    }

    canUndo() {
        return this.history.canUndo();
    }

    canRedo() {
        return this.history.canRedo();
    }

    /** @param {PromptDraftState} draft */
    loadDraft(draft) {
        this.draft = normalizeDraft(draft);
        this.sections = new PromptSections(this.draft.sections, this.draft.sectionOverrides);
        this.history.reset(this.draft);
        debouncedSaveDraft(this.draft, 'manual');
        debouncedSaveSession({ draft: this.draft });
        this.recompile();
    }

    getCompiledPrompt() {
        const model = this.config.models.find((m) => m.id === this.draft.modelId);
        return formatForModel(this.sections.toMap(), model);
    }

    /** @returns {import('./prompt-builder.js').CompiledSection[]} */
    getCompiledSections() {
        const task = this.config.tasks.find((t) => t.id === this.draft.taskId);
        if (!task) return [];
        const template = getTemplate(task.templateId, this.config);
        if (!template) return [];

        const model = this.config.models.find((m) => m.id === this.draft.modelId);
        const built = buildPrompt({
            template,
            parameterValues: this.draft.parameterValues,
            model,
            extraContext: {
                roleId: this.draft.roleId,
                taskId: this.draft.taskId,
                modelId: this.draft.modelId,
            },
        });

        return built.compiledList.map((section) => ({
            ...section,
            content: this.sections.sections[section.id] ?? section.content,
        }));
    }
}

/**
 * @param {PromptStudioConfig} config
 * @returns {StateManager}
 */
export function createStateManager(config) {
    // Always boot with an empty builder — resume via Aufgaben, Vorlagen, or Zuletzt.
    return new StateManager(config, createDefaultDraft());
}
