import { t } from './labels.js';

/** @typedef {import('./prompt-builder.js').CompiledSection} CompiledSection */

/**
 * Manages editable preview sections with override tracking.
 */
export class PromptSections {
    /**
     * @param {Record<string, string>} [initialSections]
     * @param {Record<string, boolean>} [initialOverrides]
     */
    constructor(initialSections = {}, initialOverrides = {}) {
        /** @type {Record<string, string>} */
        this.sections = { ...initialSections };
        /** @type {Record<string, boolean>} */
        this.overrides = { ...initialOverrides };
    }

    /**
     * @param {CompiledSection[]} compiled
     * @param {{ preserveOverrides?: boolean }} [options]
     */
    applyCompiled(compiled, options = { preserveOverrides: true }) {
        const preserve = options.preserveOverrides !== false;
        const nextSections = { ...this.sections };

        for (const section of compiled) {
            if (preserve && this.overrides[section.id]) continue;
            nextSections[section.id] = section.content;
            if (!preserve || !this.overrides[section.id]) {
                this.overrides[section.id] = false;
            }
        }

        this.sections = nextSections;
    }

    /**
     * @param {string} sectionId
     * @param {string} content
     */
    setSection(sectionId, content) {
        this.sections[sectionId] = content;
        this.overrides[sectionId] = true;
    }

    /**
     * @param {string} sectionId
     */
    clearOverride(sectionId) {
        this.overrides[sectionId] = false;
    }

    /**
     * @param {string} sectionId
     * @returns {boolean}
     */
    isOverridden(sectionId) {
        return this.overrides[sectionId] === true;
    }

    /** @returns {Record<string, string>} */
    toMap() {
        return { ...this.sections };
    }

    /** @returns {{ sections: Record<string, string>, sectionOverrides: Record<string, boolean> }} */
    toState() {
        return {
            sections: this.toMap(),
            sectionOverrides: { ...this.overrides },
        };
    }

    /**
     * @param {string} sectionId
     * @param {string} label
     * @param {string} content
     * @returns {string}
     */
    /**
     * @param {string} sectionId
     * @param {string} label
     * @param {string} content
     * @param {string} [editedLabel]
     * @returns {string}
     */
    renderSectionHtml(sectionId, label, content, editedLabel = 'edited') {
        const overridden = this.isOverridden(sectionId);
        return `<article class="prompt-studio__section${overridden ? ' prompt-studio__section--overridden' : ''}" data-section-id="${escapeAttr(sectionId)}">
            <header class="prompt-studio__section-header">
                <h3>${escapeHtml(label)}</h3>
                ${overridden ? `<span class="prompt-studio__section-badge">${escapeHtml(editedLabel)}</span>` : ''}
            </header>
            <textarea class="tools-textarea prompt-studio__section-editor" data-section-id="${escapeAttr(sectionId)}" rows="4">${escapeHtml(content)}</textarea>
        </article>`;
    }

    /**
     * @param {CompiledSection[]} compiledList
     * @param {string} [editedLabel]
     * @returns {string}
     */
    renderAll(compiledList, editedLabel = 'edited') {
        return compiledList
            .map((section) =>
                this.renderSectionHtml(section.id, section.label ?? section.id, this.sections[section.id] ?? section.content, editedLabel),
            )
            .join('');
    }

    /**
     * @param {HTMLElement} container
     */
    bindEditors(container) {
        container.querySelectorAll('.prompt-studio__section-editor').forEach((el) => {
            const textarea = /** @type {HTMLTextAreaElement} */ (el);
            const sectionId = textarea.getAttribute('data-section-id');
            if (!sectionId) return;

            textarea.addEventListener('input', () => {
                this.setSection(sectionId, textarea.value);
            });
        });
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

/**
 * @param {Record<string, string>} sections
 * @param {Record<string, boolean>} overrides
 * @returns {PromptSections}
 */
export function createPromptSections(sections = {}, overrides = {}) {
    return new PromptSections(sections, overrides);
}

/**
 * @param {CompiledSection[]} sections
 * @param {Record<string, boolean>} overrides
 * @param {import('./config-validator.js').ToolsLocale} locale
 * @returns {string}
 */
export function renderSectionsHtml(sections, overrides = {}, locale = 'en') {
    const manager = new PromptSections(
        Object.fromEntries(sections.map((s) => [s.id, s.content])),
        overrides,
    );
    const editedLabel = t(locale, 'promptStudio.section.edited');
    return manager.renderAll(sections, editedLabel);
}

/**
 * @param {HTMLElement} container
 * @param {(sectionId: string, content: string) => void} onChange
 */
export function bindSectionEditors(container, onChange) {
    container.querySelectorAll('.prompt-studio__section-editor').forEach((el) => {
        const textarea = /** @type {HTMLTextAreaElement} */ (el);
        const sectionId = textarea.getAttribute('data-section-id');
        if (!sectionId) return;
        textarea.addEventListener('input', () => onChange(sectionId, textarea.value));
    });
}
