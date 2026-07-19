/**
 * Trigram helpers for people and teams (3-letter short names).
 */

const ACCENT_TOKENS = ['accent-1', 'accent-2', 'accent-3', 'accent-4', 'accent-5', 'accent-6'];

/**
 * @param {string} token
 * @returns {string}
 */
export function normalizeColorToken(token) {
    const value = String(token || '').trim();
    return ACCENT_TOKENS.includes(value) ? value : 'accent-1';
}

/**
 * Build a 3-letter trigram from a display name.
 * @param {string} displayName
 * @returns {string}
 */
export function trigramFromName(displayName) {
    const raw = String(displayName || '').trim();
    if (!raw) {
        return '???';
    }
    const parts = raw.split(/[\s\-_.]+/).filter(Boolean);
    let letters = '';
    if (parts.length >= 3) {
        letters = parts.slice(0, 3).map((p) => p[0] || '').join('');
    } else if (parts.length === 2) {
        const a = parts[0];
        const b = parts[1];
        letters = (a[0] || '') + (a[1] || b[0] || '') + (b[0] || a[2] || '');
    } else {
        letters = parts[0].replace(/[^A-Za-zÀ-ÿ0-9]/g, '').slice(0, 3);
    }
    const cleaned = letters.replace(/[^A-Za-zÀ-ÿ0-9]/g, '').toUpperCase();
    if (cleaned.length >= 3) {
        return cleaned.slice(0, 3);
    }
    return (cleaned + '???').slice(0, 3);
}

/**
 * Normalize an existing shortName to exactly 3 uppercase characters.
 * @param {string} shortName
 * @param {string} [displayName]
 * @returns {string}
 */
export function normalizeTrigram(shortName, displayName = '') {
    const raw = String(shortName || '').trim().toUpperCase().replace(/[^A-ZÀ-ÿ0-9]/g, '');
    if (raw.length >= 3) {
        return raw.slice(0, 3);
    }
    if (raw.length > 0) {
        const fromName = trigramFromName(displayName);
        return (raw + fromName).slice(0, 3);
    }
    return trigramFromName(displayName);
}

/**
 * Trigram for a team from shortName or localized name.
 * @param {{shortName?: string, name?: {de?: string, en?: string}|string}} team
 * @param {'de'|'en'} [locale]
 * @returns {string}
 */
export function teamTrigram(team, locale = 'en') {
    if (!team) {
        return '???';
    }
    if (team.shortName) {
        return normalizeTrigram(team.shortName, '');
    }
    const name = typeof team.name === 'string'
        ? team.name
        : (team.name?.[locale] || team.name?.en || team.name?.de || '');
    return trigramFromName(name);
}

/**
 * Resolve canonical teamIds from legacy teamId and/or teamIds.
 * @param {{teamId?: string|null, teamIds?: unknown}} item
 * @returns {string[]}
 */
export function normalizeTeamIds(item) {
    if (Array.isArray(item?.teamIds) && item.teamIds.length) {
        return [...new Set(item.teamIds.map(String).filter(Boolean))];
    }
    if (item?.teamId) {
        return [String(item.teamId)];
    }
    return [];
}

/**
 * Primary team id for display (first of teamIds).
 * @param {{teamId?: string|null, teamIds?: string[]}} instance
 * @returns {string|null}
 */
export function primaryTeamId(instance) {
    const ids = normalizeTeamIds(instance);
    return ids[0] || null;
}
