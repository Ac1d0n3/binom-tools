/**
 * Trigram helpers for people and teams (3-letter short names).
 */

export const ACCENT_TOKENS = [
    'accent-1', 'accent-2', 'accent-3', 'accent-4', 'accent-5', 'accent-6',
    'accent-7', 'accent-8', 'accent-9', 'accent-10', 'accent-11', 'accent-12',
    'outline-1', 'outline-2', 'outline-3', 'outline-4', 'outline-5', 'outline-6',
    'dotted-1', 'dotted-2', 'dotted-3', 'dotted-4', 'dotted-5', 'dotted-6',
    'dashed-1', 'dashed-2', 'dashed-3', 'dashed-4', 'dashed-5', 'dashed-6',
];

export const TEAM_ACCENT_TOKENS = [
    'accent-1', 'accent-2', 'accent-3', 'accent-4', 'accent-5', 'accent-6',
];

/** @type {Record<string, string>} */
export const ACCENT_HEX = {
    'accent-1': '#2563eb',
    'accent-2': '#0d9488',
    'accent-3': '#c2410c',
    'accent-4': '#7c3aed',
    'accent-5': '#be185d',
    'accent-6': '#475569',
    'accent-7': '#15803d',
    'accent-8': '#d97706',
    'accent-9': '#0891b2',
    'accent-10': '#4338ca',
    'accent-11': '#e11d48',
    'accent-12': '#65a30d',
    'outline-1': '#2563eb',
    'outline-2': '#0d9488',
    'outline-3': '#c2410c',
    'outline-4': '#7c3aed',
    'outline-5': '#be185d',
    'outline-6': '#475569',
    'dotted-1': '#2563eb',
    'dotted-2': '#0d9488',
    'dotted-3': '#c2410c',
    'dotted-4': '#7c3aed',
    'dotted-5': '#be185d',
    'dotted-6': '#475569',
    'dashed-1': '#2563eb',
    'dashed-2': '#0d9488',
    'dashed-3': '#c2410c',
    'dashed-4': '#7c3aed',
    'dashed-5': '#be185d',
    'dashed-6': '#475569',
};

export const AVATAR_ICON_OPTIONS = [
    'user', 'user-tie', 'user-gear', 'user-graduate', 'user-astronaut', 'user-ninja',
    'user-secret', 'user-doctor', 'users', 'people-group', 'person', 'face-smile',
    'hat-wizard', 'crown', 'rocket', 'flask', 'code', 'laptop-code', 'database',
    'chart-line', 'shield-halved', 'lightbulb', 'star', 'heart', 'paw', 'cat', 'dog',
    'mug-hot', 'headphones', 'camera', 'gamepad', 'music', 'brush', 'compass', 'sitemap', 'brain',
    'briefcase', 'gear', 'globe', 'trophy', 'puzzle-piece', 'microscope', 'leaf', 'fire',
    'moon', 'sun', 'dragon', 'fish', 'handshake', 'key', 'wrench', 'gift', 'glasses',
    'feather', 'cube', 'cloud', 'bug', 'book', 'bicycle', 'seedling',
    'yin-yang', 'atom', 'bolt', 'ghost', 'gem', 'ankh', 'om', 'peace', 'spa',
    'wand-magic-sparkles', 'infinity', 'fingerprint', 'cookie', 'bomb',
    'layer-group', 'diagram-project', 'circle-nodes',
];

/**
 * @param {string} token
 * @returns {string}
 */
export function normalizeColorToken(token) {
    const value = String(token || '').trim();
    return ACCENT_TOKENS.includes(value) ? value : 'accent-1';
}

/**
 * @param {string} token
 * @returns {boolean}
 */
export function isOutlineColorToken(token) {
    return normalizeColorToken(token).startsWith('outline-');
}

/**
 * @param {string} token
 * @returns {boolean}
 */
export function isBorderedColorToken(token) {
    const value = normalizeColorToken(token);
    return value.startsWith('outline-') || value.startsWith('dotted-') || value.startsWith('dashed-');
}

/**
 * @param {string} token
 * @returns {'solid'|'dotted'|'dashed'}
 */
export function colorTokenBorderStyle(token) {
    const value = normalizeColorToken(token);
    if (value.startsWith('dotted-')) {
        return 'dotted';
    }
    if (value.startsWith('dashed-')) {
        return 'dashed';
    }
    return 'solid';
}

/**
 * @param {string} token
 * @returns {string}
 */
export function colorTokenHex(token) {
    return ACCENT_HEX[normalizeColorToken(token)];
}

/**
 * @param {HTMLElement} el
 * @param {string} token
 * @param {'person'|'team'|'empty'} [kind]
 */
export function applyAvatarColor(el, token, kind = 'person') {
    if (kind === 'empty') {
        el.style.backgroundColor = '';
        el.style.color = '';
        el.style.borderColor = '';
        el.style.borderStyle = '';
        el.style.borderWidth = '';
        return;
    }
    const normalized = normalizeColorToken(token);
    const hex = colorTokenHex(normalized);
    if (isBorderedColorToken(normalized)) {
        el.style.backgroundColor = '#fff';
        el.style.color = hex;
        el.style.border = `2px ${colorTokenBorderStyle(normalized)} ${hex}`;
        return;
    }
    el.style.backgroundColor = hex;
    el.style.color = '#fff';
    el.style.border = '2px solid transparent';
}

/**
 * @param {string|null|undefined} icon
 * @returns {string}
 */
export function normalizeAvatarIcon(icon) {
    let value = String(icon || '').trim().toLowerCase();
    value = value.replace(/^fa-(solid|regular|brands)\s+/, '').replace(/^fa-/, '').trim();
    if (!value || value === 'none' || value === 'trigram') {
        return '';
    }
    return AVATAR_ICON_OPTIONS.includes(value) ? value : '';
}

/**
 * First unused accent token, or cycle by count when all are taken.
 * @param {Iterable<string>} usedTokens
 * @param {number} [existingCount]
 * @param {string[]} [pool]
 * @returns {string}
 */
export function nextUnusedColorToken(usedTokens, existingCount = 0, pool = TEAM_ACCENT_TOKENS) {
    const used = new Set([...usedTokens].map((token) => normalizeColorToken(token)));
    for (const token of pool) {
        if (!used.has(token)) {
            return token;
        }
    }
    return pool[existingCount % pool.length];
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
 * Normalize a persisted shortName: keep 2–3 A–Z as-is; otherwise derive from displayName.
 * @param {string} shortName
 * @param {string} [displayName]
 * @returns {string}
 */
export function normalizeTrigram(shortName, displayName = '') {
    const raw = String(shortName || '').trim().toUpperCase().replace(/[^A-Z]/g, '');
    if (raw.length >= 2 && raw.length <= 3) {
        return raw;
    }
    if (raw.length > 3) {
        return raw.slice(0, 3);
    }
    if (raw.length === 1) {
        const fromName = trigramFromName(displayName).replace(/[^A-Z]/g, '');
        const merged = (raw + fromName).replace(/[^A-Z]/g, '');
        return merged.length >= 2 ? merged.slice(0, 3) : trigramFromName(displayName).slice(0, 3);
    }
    return trigramFromName(displayName).slice(0, 3);
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
