/**
 * External help/community links only — no playbook/story paths or bare slugs.
 */

/**
 * @param {string} href
 * @returns {boolean}
 */
export function isExternalHelpHref(href) {
    const value = String(href || '').trim();
    if (!value) {
        return false;
    }
    if (/^https?:\/\//i.test(value) || /^mailto:/i.test(value)) {
        return true;
    }
    // Reject app-internal paths (playbooks, planner, etc.) and bare slugs.
    if (value.startsWith('/')) {
        return false;
    }
    if (/^[a-z0-9-]+$/i.test(value)) {
        return false;
    }
    return false;
}

/**
 * Resolve href for help/community links. Only external URLs are returned.
 * @param {string} href
 * @returns {string}
 */
export function resolveExternalHelpHref(href) {
    const value = String(href || '').trim();
    if (!isExternalHelpHref(value)) {
        return '#';
    }
    return value;
}

/**
 * Parse "Label | https://…" lines. Rejects story slugs and /playbooks paths.
 * @param {string} raw
 * @returns {{links: Array<{label: string, href: string}>, rejected: string[]}}
 */
export function parseExternalLinksTextarea(raw) {
    /** @type {Array<{label: string, href: string}>} */
    const links = [];
    /** @type {string[]} */
    const rejected = [];
    for (const line of String(raw || '').split('\n')) {
        const trimmed = line.trim();
        if (!trimmed) {
            continue;
        }
        const pipe = trimmed.indexOf('|');
        let label = '';
        let href = '';
        if (pipe >= 0) {
            label = trimmed.slice(0, pipe).trim();
            href = trimmed.slice(pipe + 1).trim();
        } else {
            href = trimmed;
            label = trimmed;
        }
        if (!href) {
            continue;
        }
        if (!isExternalHelpHref(href)) {
            rejected.push(trimmed);
            continue;
        }
        links.push({ label: label || href, href });
    }
    return { links, rejected };
}

/**
 * @param {Array<{label: string, href: string}>} links
 */
export function formatExternalLinksTextarea(links = []) {
    return (links || [])
        .filter((link) => link?.href && isExternalHelpHref(link.href))
        .map((link) => (link.label && link.label !== link.href
            ? `${link.label} | ${link.href}`
            : link.href))
        .join('\n');
}

/**
 * Drop non-external links when normalizing stored help links.
 * @param {unknown} links
 * @param {string} [locale]
 * @returns {Array<{label: string, href: string}>}
 */
export function filterExternalHelpLinks(links, locale = 'en') {
    if (!Array.isArray(links)) {
        return [];
    }
    /** @type {Array<{label: string, href: string}>} */
    const out = [];
    for (const link of links) {
        if (!link || typeof link !== 'object') {
            continue;
        }
        const href = String(/** @type {any} */ (link).href || '').trim();
        if (!isExternalHelpHref(href)) {
            continue;
        }
        let label = /** @type {any} */ (link).label;
        if (label && typeof label === 'object') {
            label = label[locale] || label.en || label.de || '';
        }
        out.push({
            label: String(label || href),
            href,
        });
    }
    return out;
}
