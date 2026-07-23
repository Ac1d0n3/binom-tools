import { loadSeries, saveSeries } from './storage.js';
import { buildPrompt, formatForModel } from './prompt-builder.js';
import { getTemplate } from './config-loader.js';

/** @typedef {import('./config-validator.js').PromptStudioConfig} PromptStudioConfig */
/** @typedef {import('./storage.js').PromptDraftState} PromptDraftState */
/** @typedef {import('./storage.js').PromptSeriesEntry} PromptSeriesEntry */

/**
 * @typedef {Object} SeriesVariant
 * @property {number} index
 * @property {string} varyingValue
 * @property {Record<string, unknown>} parameterValues
 * @property {string} compiled
 */

/**
 * Batch prompt generator with one varying parameter field.
 */
export class SeriesManager {
    /**
     * @param {PromptStudioConfig} config
     */
    constructor(config) {
        this.config = config;
        const loaded = loadSeries();
        /** @type {PromptSeriesEntry[]} */
        this.series = loaded && 'data' in loaded ? loaded.data : [];
    }

    /**
     * @param {PromptDraftState} baseDraft
     * @param {string} varyingFieldId
     * @param {string[]} values
     * @param {string} [name]
     * @returns {PromptSeriesEntry}
     */
    createSeries(baseDraft, varyingFieldId, values, name = 'Series') {
        const entry = {
            id: `series_${Date.now().toString(36)}`,
            name,
            varyingFieldId,
            values: values.filter(Boolean),
            baseDraft: structuredClone(baseDraft),
            savedAt: new Date().toISOString(),
        };
        this.series = [entry, ...this.series];
        this.persist();
        return entry;
    }

    /**
     * @param {PromptSeriesEntry} series
     * @returns {SeriesVariant[]}
     */
    generateVariants(series) {
        const task = this.config.tasks.find((t) => t.id === series.baseDraft.taskId);
        const template = task ? getTemplate(task.templateId, this.config) : undefined;
        const model = this.config.models.find((m) => m.id === series.baseDraft.modelId);

        return series.values.map((value, index) => {
            const parameterValues = {
                ...series.baseDraft.parameterValues,
                [series.varyingFieldId]: value,
            };

            let compiled = '';
            if (template) {
                const built = buildPrompt({
                    template,
                    parameterValues,
                    model,
                    extraContext: {
                        roleId: series.baseDraft.roleId,
                        taskId: series.baseDraft.taskId,
                        modelId: series.baseDraft.modelId,
                    },
                });
                const sections = { ...built.sections, ...series.baseDraft.sections };
                compiled = formatForModel(sections, model, { parameterValues });
            }

            return {
                index: index + 1,
                varyingValue: value,
                parameterValues,
                compiled,
            };
        });
    }

    /**
     * @param {SeriesVariant[]} variants
     * @returns {string}
     */
    formatBatchCopy(variants) {
        return variants.map((variant) => `#${variant.index}\n${variant.compiled}`).join('\n\n---\n\n');
    }

    /** @returns {PromptSeriesEntry[]} */
    list() {
        return [...this.series];
    }

    /** @param {string} id @returns {boolean} */
    remove(id) {
        const before = this.series.length;
        this.series = this.series.filter((entry) => entry.id !== id);
        if (before !== this.series.length) {
            this.persist();
            return true;
        }
        return false;
    }

    persist() {
        saveSeries(this.series, 'manual');
    }
}

export function createSeriesManager(config) {
    return new SeriesManager(config);
}
