import { loadTemplates, saveTemplates } from './storage.js';
import { normalizeOutputKind } from './md-export.js';

/** @typedef {import('./storage.js').PromptDraftState} PromptDraftState */

/**
 * @typedef {Object} UserTemplate
 * @property {string} id
 * @property {string} name
 * @property {string} [description]
 * @property {import('./md-export.js').OutputKind} [kind]
 * @property {string} roleId
 * @property {string} taskId
 * @property {string} modelId
 * @property {Record<string, unknown>} parameterValues
 * @property {Record<string, string>} sections
 * @property {Record<string, boolean>} sectionOverrides
 * @property {string[]} [tags]
 * @property {string} savedAt
 */

/**
 * User template CRUD backed by localStorage.
 */
export class TemplateStore {
    constructor() {
        const loaded = loadTemplates();
        /** @type {UserTemplate[]} */
        this.templates = loaded && 'data' in loaded ? /** @type {UserTemplate[]} */ (loaded.data) : [];
    }

    /** @returns {UserTemplate[]} */
    list() {
        return [...this.templates];
    }

    /**
     * @param {string} id
     * @returns {UserTemplate | undefined}
     */
    get(id) {
        return this.templates.find((tpl) => tpl.id === id);
    }

    /** @param {string} id */
    getById(id) {
        const template = this.get(id);
        if (!template) return undefined;
        return { ...template, draft: this.toDraft(template) };
    }

    /**
     * @param {string} name
     * @param {PromptDraftState} draft
     */
    save(name, draft) {
        return this.saveFromDraft(draft, name);
    }

    reload() {
        const loaded = loadTemplates();
        this.templates = loaded && 'data' in loaded ? /** @type {UserTemplate[]} */ (loaded.data) : [];
    }

    /**
     * @param {Omit<UserTemplate, 'id' | 'savedAt'> & { id?: string }} input
     * @returns {UserTemplate}
     */
    create(input) {
        const template = normalizeTemplate({
            ...input,
            id: input.id ?? `tpl_${Date.now().toString(36)}`,
            savedAt: new Date().toISOString(),
        });
        this.templates = [template, ...this.templates.filter((t) => t.id !== template.id)];
        this.persist();
        return template;
    }

    /**
     * @param {string} id
     * @param {Partial<UserTemplate>} patch
     * @returns {UserTemplate | null}
     */
    update(id, patch) {
        const index = this.templates.findIndex((t) => t.id === id);
        if (index === -1) return null;
        const updated = normalizeTemplate({ ...this.templates[index], ...patch, id, savedAt: new Date().toISOString() });
        this.templates[index] = updated;
        this.persist();
        return updated;
    }

    /** @param {string} id @returns {boolean} */
    remove(id) {
        const before = this.templates.length;
        this.templates = this.templates.filter((t) => t.id !== id);
        if (this.templates.length !== before) {
            this.persist();
            return true;
        }
        return false;
    }

    /**
     * @param {PromptDraftState} draft
     * @param {string} name
     * @param {{ description?: string, tags?: string[] }} [meta]
     */
    saveFromDraft(draft, name, meta = {}) {
        return this.create({
            name,
            description: meta.description,
            tags: meta.tags,
            kind: normalizeOutputKind(draft.kind ?? meta.kind),
            roleId: draft.roleId,
            taskId: draft.taskId,
            modelId: draft.modelId,
            parameterValues: draft.parameterValues,
            sections: draft.sections,
            sectionOverrides: draft.sectionOverrides,
        });
    }

    /** @param {UserTemplate} template @returns {PromptDraftState} */
    toDraft(template) {
        return {
            roleId: template.roleId,
            taskId: template.taskId,
            modelId: template.modelId,
            parameterValues: template.parameterValues,
            sections: template.sections,
            sectionOverrides: template.sectionOverrides,
            title: template.name,
            tags: template.tags ?? [],
            kind: normalizeOutputKind(template.kind),
        };
    }

    /** @param {UserTemplate[]} templates */
    replaceAll(templates) {
        this.templates = templates.map((tpl) => normalizeTemplate(tpl));
        this.persist();
    }

    persist() {
        saveTemplates(this.templates, 'manual');
    }
}

/**
 * @param {unknown} raw
 * @returns {UserTemplate}
 */
function normalizeTemplate(raw) {
    const obj = /** @type {Record<string, unknown>} */ (raw ?? {});
    return {
        id: typeof obj.id === 'string' ? obj.id : `tpl_${Date.now().toString(36)}`,
        name: typeof obj.name === 'string' ? obj.name : 'Untitled',
        description: typeof obj.description === 'string' ? obj.description : undefined,
        kind: normalizeOutputKind(obj.kind),
        roleId: typeof obj.roleId === 'string' ? obj.roleId : '',
        taskId: typeof obj.taskId === 'string' ? obj.taskId : '',
        modelId: typeof obj.modelId === 'string' ? obj.modelId : '',
        parameterValues:
            obj.parameterValues && typeof obj.parameterValues === 'object'
                ? /** @type {Record<string, unknown>} */ (obj.parameterValues)
                : {},
        sections:
            obj.sections && typeof obj.sections === 'object'
                ? /** @type {Record<string, string>} */ (obj.sections)
                : {},
        sectionOverrides:
            obj.sectionOverrides && typeof obj.sectionOverrides === 'object'
                ? /** @type {Record<string, boolean>} */ (obj.sectionOverrides)
                : {},
        tags: Array.isArray(obj.tags) ? obj.tags.map(String) : [],
        savedAt: typeof obj.savedAt === 'string' ? obj.savedAt : new Date().toISOString(),
    };
}

export function createTemplateStore() {
    return new TemplateStore();
}
