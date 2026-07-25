/** @typedef {import('./config-validator.js').PromptParameterDef} PromptParameterDef */
/** @typedef {import('./config-validator.js').ToolsLocale} ToolsLocale */
/** @typedef {import('./music-structures.js').MusicStructuresConfig} MusicStructuresConfig */

import { resolveLocalizedLabel } from './localized-label.js';
import { t } from './labels.js';
import {
    formatStructureLines,
    getFamilyForGenre,
    getPresetTextForGenre,
} from './music-structures.js';

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
 * @param {PromptParameterDef} def
 * @param {unknown} value
 * @param {ToolsLocale} locale
 * @param {MusicStructuresConfig | null | undefined} musicStructures
 * @param {string} [genre]
 * @returns {string}
 */
export function renderStructureEditor(def, value, locale, musicStructures, genre = 'pop') {
    const fieldId = `ps-param-${def.id}`;
    const text = String(value ?? '');
    const family = getFamilyForGenre(genre, musicStructures);
    const tip = family?.buildUpTip
        ? resolveLocalizedLabel(family.buildUpTip, locale, '')
        : t(locale, 'promptStudio.structure.buildUpTipFallback');
    const catalog = musicStructures?.sectionCatalog ?? [];

    const sectionButtons = catalog
        .map((section) => {
            const label = resolveLocalizedLabel(section.label, locale, section.id);
            const line = `${label} (${section.bars} bars)`;
            return `<button type="button" class="tools-btn tools-btn--sm prompt-studio__structure-add" data-structure-line="${escapeAttr(line)}">${escapeHtml(label)}</button>`;
        })
        .join('');

    return `<div class="prompt-studio__structure-editor" data-param-id="${escapeAttr(def.id)}" data-structure-root>
        <div class="prompt-studio__structure-toolbar">
            <button type="button" class="tools-btn tools-btn--sm" data-structure-preset>${escapeHtml(t(locale, 'promptStudio.structure.loadPreset'))}</button>
            <button type="button" class="tools-btn tools-btn--sm" data-structure-buildup>${escapeHtml(t(locale, 'promptStudio.structure.insertBuildUp'))}</button>
        </div>
        <p class="tools-field__help prompt-studio__structure-tip" data-structure-tip>${escapeHtml(tip)}</p>
        <textarea id="${fieldId}" class="tools-textarea ps-param-input" data-param-id="${escapeAttr(def.id)}" data-param-type="structure-editor" rows="8" placeholder="${escapeAttr(t(locale, 'promptStudio.structure.placeholder'))}">${escapeHtml(text)}</textarea>
        <div class="prompt-studio__structure-catalog" aria-label="${escapeAttr(t(locale, 'promptStudio.structure.addSection'))}">
            ${sectionButtons}
        </div>
    </div>`;
}

/**
 * @param {HTMLElement} container
 * @param {{
 *   musicStructures?: MusicStructuresConfig | null,
 *   getGenre: () => string,
 *   locale: ToolsLocale,
 *   onChange: () => void,
 * }} options
 */
export function bindStructureEditors(container, options) {
    container.querySelectorAll('[data-structure-root]').forEach((root) => {
        const el = /** @type {HTMLElement} */ (root);
        const textarea = /** @type {HTMLTextAreaElement | null} */ (el.querySelector('textarea.ps-param-input'));
        if (!textarea) return;

        const tipEl = el.querySelector('[data-structure-tip]');

        const refreshTip = () => {
            const family = getFamilyForGenre(options.getGenre(), options.musicStructures);
            if (tipEl && family?.buildUpTip) {
                tipEl.textContent = resolveLocalizedLabel(family.buildUpTip, options.locale, '');
            }
        };

        el.querySelector('[data-structure-preset]')?.addEventListener('click', () => {
            textarea.value = getPresetTextForGenre(options.getGenre(), options.musicStructures, options.locale);
            refreshTip();
            options.onChange();
        });

        el.querySelector('[data-structure-buildup]')?.addEventListener('click', () => {
            const buildUp =
                (options.musicStructures?.sectionCatalog ?? []).find((s) => s.id === 'build-up') ??
                { id: 'build-up', label: { de: 'Build-up', en: 'Build-up' }, bars: 8 };
            const line = formatStructureLines([buildUp], options.locale);
            textarea.value = textarea.value.trim() ? `${textarea.value.trim()}\n${line}` : line;
            refreshTip();
            options.onChange();
        });

        el.querySelectorAll('[data-structure-line]').forEach((btn) => {
            btn.addEventListener('click', () => {
                const line = btn.getAttribute('data-structure-line') || '';
                if (!line) return;
                textarea.value = textarea.value.trim() ? `${textarea.value.trim()}\n${line}` : line;
                options.onChange();
            });
        });

        refreshTip();
    });
}
