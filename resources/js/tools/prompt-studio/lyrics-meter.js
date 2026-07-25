/** @typedef {import('./config-validator.js').ToolsLocale} ToolsLocale */

/**
 * @typedef {{ line: string, syllables: number, bars: number }} LyricsMeterLine
 * @typedef {{ lines: LyricsMeterLine[], totalSyllables: number, totalBars: number }} LyricsMeterSummary
 */

const VOWEL_GROUP_RE = /[aeiouyäöüáéíóúàèìòùâêîôû]+/gi;
const GERMAN_DIPHTHONG_ADJUST = /(?:ei|ie|eu|äu|au|ai)/gi;

/**
 * Rough syllable estimate for DE/EN lyric lines (UI helper only — never copied into prompts).
 * @param {string} word
 * @param {ToolsLocale} [locale]
 * @returns {number}
 */
export function estimateSyllables(word, locale = 'en') {
    const cleaned = String(word || '')
        .toLowerCase()
        .replace(/[^a-zäöüßáéíóúàèìòùâêîôûy'-]/gi, '');
    if (!cleaned) return 0;

    // German: treat "e" endings and common patterns lightly
    if (locale === 'de') {
        const matches = cleaned.match(VOWEL_GROUP_RE) ?? [];
        let count = matches.length;
        if (count === 0) return 1;
        // "ie" etc. already one group; silent trailing e in some loanwords
        if (cleaned.endsWith('e') && count > 1 && !cleaned.endsWith('ee') && !cleaned.endsWith('le')) {
            count -= 1;
        }
        // Adjust double-counted diphthongs already in vowel groups — keep count as groups
        void GERMAN_DIPHTHONG_ADJUST;
        return Math.max(1, count);
    }

    // English heuristic (inspired by classic syllable counters)
    let w = cleaned.replace(/'/g, '');
    if (w.length <= 3) return 1;
    w = w.replace(/(?:[^laeiouy]es|ed|[^laeiouy]e)$/, '');
    w = w.replace(/^y/, '');
    const matches = w.match(/[aeiouy]{1,2}/g);
    return Math.max(1, matches ? matches.length : 1);
}

/**
 * @param {string} line
 * @param {ToolsLocale} [locale]
 * @returns {number}
 */
export function estimateLineSyllables(line, locale = 'en') {
    const words = String(line || '')
        .trim()
        .split(/\s+/)
        .filter(Boolean);
    if (words.length === 0) return 0;
    return words.reduce((sum, word) => sum + estimateSyllables(word, locale), 0);
}

/**
 * @param {string} text
 * @param {{ syllablesPerBar?: number, locale?: ToolsLocale }} [options]
 * @returns {LyricsMeterSummary}
 */
export function analyzeLyrics(text, options = {}) {
    const syllablesPerBar = Number(options.syllablesPerBar) > 0 ? Number(options.syllablesPerBar) : 4;
    const locale = options.locale === 'de' ? 'de' : 'en';
    const rawLines = String(text ?? '').split(/\r?\n/);

    /** @type {LyricsMeterLine[]} */
    const lines = rawLines.map((line) => {
        const syllables = estimateLineSyllables(line, locale);
        const bars = syllables === 0 ? 0 : Math.max(0.25, Math.round((syllables / syllablesPerBar) * 4) / 4);
        return { line, syllables, bars };
    });

    const totalSyllables = lines.reduce((sum, row) => sum + row.syllables, 0);
    const totalBars = lines.reduce((sum, row) => sum + row.bars, 0);

    return { lines, totalSyllables, totalBars };
}

/**
 * @param {number} value
 * @returns {string}
 */
export function formatBars(value) {
    if (!Number.isFinite(value) || value === 0) return '0';
    return Number.isInteger(value) ? String(value) : value.toFixed(2).replace(/\.?0+$/, '');
}
