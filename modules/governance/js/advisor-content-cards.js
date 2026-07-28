/**
 * Curated Advisor content cards (stories / suppliers / vendors) from catalog JSON.
 */

/**
 * @param {Record<string, unknown>|null|undefined} when
 * @param {{ goal?: string, scenario?: string, domain?: string, platform?: string, role?: string }} state
 * @returns {boolean}
 */
export function matchesContentWhen(when, state) {
    if (!when || typeof when !== 'object') {
        return true;
    }

    /** @type {Array<[string, string]>} */
    const dims = [
        ['goals', state.goal || ''],
        ['scenarios', state.scenario || ''],
        ['domains', state.domain || ''],
        ['platforms', state.platform || ''],
        ['roles', state.role || ''],
    ];

    for (const [key, value] of dims) {
        const list = when[key];
        if (!Array.isArray(list) || list.length === 0) {
            continue;
        }
        if (!list.includes(value)) {
            return false;
        }
    }

    return true;
}

/**
 * @param {Record<string, unknown>} config
 * @returns {Array<Record<string, unknown>>}
 */
export function contentCardCandidates(config) {
    const raw = config?.links?.contentCards;
    return Array.isArray(raw) ? raw.filter((item) => item && typeof item === 'object') : [];
}

/**
 * @param {Record<string, unknown>} item
 * @returns {Record<string, unknown>}
 */
export function normalizeContentCard(item) {
    const asLocale = (value) => {
        if (value && typeof value === 'object') {
            return {
                de: String(value.de || value.en || ''),
                en: String(value.en || value.de || ''),
            };
        }
        const text = String(value || '');
        return { de: text, en: text };
    };

    return {
        id: String(item.id || ''),
        kind: String(item.kind || 'content'),
        group: String(item.group || 'resources'),
        icon: String(item.icon || 'fa-arrow-right'),
        title: asLocale(item.title),
        reason: asLocale(item.reason),
        tags: Array.isArray(item.tags) ? item.tags.map(String) : [],
        url: typeof item.url === 'string' ? item.url : '#',
        baseScore: Number(item.score) || 0,
        when: item.when && typeof item.when === 'object' ? item.when : {},
    };
}
