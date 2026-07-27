/** @typedef {import('./config-validator.js').PromptParameterDef} PromptParameterDef */
/** @typedef {import('./config-validator.js').ToolsLocale} ToolsLocale */

import { t } from './labels.js';
import { analyzeLyrics, formatBars } from './lyrics-meter.js';

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
 * @returns {string}
 */
export function renderLyricsMeterField(def, value, locale) {
    const fieldId = `ps-param-${def.id}`;
    const text = String(value ?? '');
    const summary = analyzeLyrics(text, { locale, syllablesPerBar: 4 });

    return `<div class="prompt-studio__lyrics-meter" data-param-id="${escapeAttr(def.id)}" data-lyrics-meter-root>
        <div class="prompt-studio__lyrics-meter-summary" data-lyrics-summary>
            <span>${escapeHtml(t(locale, 'promptStudio.lyricsMeter.syllables'))}: <strong data-lyrics-syllables>${summary.totalSyllables}</strong></span>
            <span>${escapeHtml(t(locale, 'promptStudio.lyricsMeter.bars'))}: <strong data-lyrics-bars>${escapeHtml(formatBars(summary.totalBars))}</strong></span>
            <span class="prompt-studio__lyrics-meter-note">${escapeHtml(t(locale, 'promptStudio.lyricsMeter.uiOnly'))}</span>
        </div>
        <div class="prompt-studio__lyrics-meter-body">
            <textarea id="${fieldId}" class="tools-textarea ps-param-input" data-param-id="${escapeAttr(def.id)}" data-param-type="lyrics-meter" rows="8" placeholder="${escapeAttr(t(locale, 'promptStudio.lyricsMeter.placeholder'))}">${escapeHtml(text)}</textarea>
            <div class="prompt-studio__lyrics-meter-side" data-lyrics-side aria-live="polite"></div>
        </div>
        <div class="prompt-studio__lyrics-meter-actions">
            <button type="button" class="tools-btn tools-btn--sm" data-lyrics-apply-story>${escapeHtml(t(locale, 'promptStudio.lyricsMeter.applyToStory'))}</button>
        </div>
    </div>`;
}

/**
 * @param {HTMLElement} root
 * @param {{ locale: ToolsLocale, syllablesPerBar?: number }} options
 */
export function updateLyricsMeterUi(root, options) {
    const textarea = /** @type {HTMLTextAreaElement | null} */ (root.querySelector('textarea.ps-param-input'));
    const side = root.querySelector('[data-lyrics-side]');
    const sylEl = root.querySelector('[data-lyrics-syllables]');
    const barsEl = root.querySelector('[data-lyrics-bars]');
    if (!textarea) return;

    const summary = analyzeLyrics(textarea.value, {
        locale: options.locale,
        syllablesPerBar: options.syllablesPerBar ?? 4,
    });

    if (sylEl) sylEl.textContent = String(summary.totalSyllables);
    if (barsEl) barsEl.textContent = formatBars(summary.totalBars);

    if (side) {
        side.innerHTML = summary.lines
            .map((row) => {
                if (!row.line.trim()) {
                    return `<div class="prompt-studio__lyrics-meter-row prompt-studio__lyrics-meter-row--empty">&nbsp;</div>`;
                }
                return `<div class="prompt-studio__lyrics-meter-row"><span>${row.syllables}</span><span>${escapeHtml(formatBars(row.bars))}</span></div>`;
            })
            .join('');
    }
}

/**
 * @param {HTMLElement} container
 * @param {{
 *   locale: ToolsLocale,
 *   getSyllablesPerBar?: () => number,
 *   onChange: () => void,
 *   onApplyToStory?: (draftText: string) => void,
 * }} options
 */
export function bindLyricsMeters(container, options) {
    container.querySelectorAll('[data-lyrics-meter-root]').forEach((rootEl) => {
        const root = /** @type {HTMLElement} */ (rootEl);
        const textarea = /** @type {HTMLTextAreaElement | null} */ (root.querySelector('textarea.ps-param-input'));
        if (!textarea) return;

        const refresh = () => {
            updateLyricsMeterUi(root, {
                locale: options.locale,
                syllablesPerBar: options.getSyllablesPerBar?.() ?? 4,
            });
        };

        textarea.addEventListener('input', () => {
            refresh();
            options.onChange();
        });
        textarea.addEventListener('scroll', () => {
            const side = /** @type {HTMLElement | null} */ (root.querySelector('[data-lyrics-side]'));
            if (side) side.scrollTop = textarea.scrollTop;
        });

        root.querySelector('[data-lyrics-apply-story]')?.addEventListener('click', () => {
            options.onApplyToStory?.(textarea.value);
        });

        refresh();
    });
}
