/** @typedef {import('./config-validator.js').ToolsLocale} ToolsLocale */

/**
 * @typedef {{ id: string, label: Record<ToolsLocale, string>, bars: number }} MusicStructureSection
 * @typedef {{
 *   label: Record<ToolsLocale, string>,
 *   syllablesPerBar?: number,
 *   buildUpTip?: Record<ToolsLocale, string>,
 *   preset: MusicStructureSection[],
 * }} MusicStructureFamily
 * @typedef {{
 *   defaultFamily?: string,
 *   genreToFamily?: Record<string, string>,
 *   families?: Record<string, MusicStructureFamily>,
 *   sectionCatalog?: MusicStructureSection[],
 * }} MusicStructuresConfig
 */

import { resolveLocalizedLabel } from './localized-label.js';

/**
 * @param {MusicStructureSection[]} sections
 * @param {ToolsLocale} [locale]
 * @returns {string}
 */
export function formatStructureLines(sections, locale = 'en') {
    return (sections ?? [])
        .map((section) => {
            const label = resolveLocalizedLabel(section.label, locale, section.id);
            const bars = Number(section.bars) > 0 ? Number(section.bars) : 8;
            return `${label} (${bars} bars)`;
        })
        .join('\n');
}

/**
 * @param {string} genre
 * @param {MusicStructuresConfig | null | undefined} config
 * @returns {string}
 */
export function resolveFamilyIdForGenre(genre, config) {
    const fallback = config?.defaultFamily || 'pop';
    if (!genre || !config?.genreToFamily) return fallback;
    return config.genreToFamily[String(genre)] || fallback;
}

/**
 * @param {string} genre
 * @param {MusicStructuresConfig | null | undefined} config
 * @returns {MusicStructureFamily | null}
 */
export function getFamilyForGenre(genre, config) {
    if (!config?.families) return null;
    const familyId = resolveFamilyIdForGenre(genre, config);
    return config.families[familyId] ?? config.families[config.defaultFamily || 'pop'] ?? null;
}

/**
 * @param {string} genre
 * @param {MusicStructuresConfig | null | undefined} config
 * @param {ToolsLocale} [locale]
 * @returns {string}
 */
export function getPresetTextForGenre(genre, config, locale = 'en') {
    const family = getFamilyForGenre(genre, config);
    if (!family?.preset?.length) return '';
    return formatStructureLines(family.preset, locale);
}

/**
 * @param {string} genre
 * @param {MusicStructuresConfig | null | undefined} config
 * @returns {number}
 */
export function getSyllablesPerBarForGenre(genre, config) {
    const family = getFamilyForGenre(genre, config);
    const value = Number(family?.syllablesPerBar);
    return Number.isFinite(value) && value > 0 ? value : 4;
}

/**
 * @param {MusicStructuresConfig | null | undefined} raw
 * @returns {MusicStructuresConfig}
 */
export function normalizeMusicStructures(raw) {
    if (!raw || typeof raw !== 'object') {
        return { defaultFamily: 'pop', genreToFamily: {}, families: {}, sectionCatalog: [] };
    }
    return /** @type {MusicStructuresConfig} */ (raw);
}
