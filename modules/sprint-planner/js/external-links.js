/**
 * Help/community links: external URLs and in-app /tools/ paths.
 * Playbook stories belong in `stories` / `linkedStories`, not helpLinks.
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
    return false;
}

/**
 * In-app discovery/generator tools (locale-optional).
 * @param {string} href
 * @returns {boolean}
 */
export function isAppToolHref(href) {
    const value = String(href || '').trim();
    return /^\/(?:de\/|en\/)?tools\//i.test(value);
}

/**
 * In-app playbook/story paths (locale-optional).
 * @param {string} href
 * @returns {boolean}
 */
export function isAppPlaybookHref(href) {
    const value = String(href || '').trim();
    return /^\/(?:de\/|en\/)?playbooks\//i.test(value);
}

/**
 * @param {string} href
 * @returns {boolean}
 */
export function isAllowedHelpHref(href) {
    return isExternalHelpHref(href) || isAppToolHref(href);
}

/**
 * Prefer locale-prefixed in-app paths when opened from a localized plan page.
 * @param {string} href
 * @param {string} [locale]
 * @returns {string}
 */
export function localizeAppHref(href, locale = 'en') {
    const value = String(href || '').trim();
    if (!isAppToolHref(value) && !isAppPlaybookHref(value)) {
        return value;
    }
    const stripped = value.replace(/^\/(de|en)(?=\/)/i, '');
    const loc = locale === 'de' || locale === 'en' ? locale : 'en';
    return `/${loc}${stripped}`;
}

/**
 * @param {string} href
 * @param {string} [locale]
 * @returns {string}
 */
export function localizeToolHref(href, locale = 'en') {
    return localizeAppHref(href, locale);
}

/**
 * Resolve href for help links: external URLs and /tools/.
 * @param {string} href
 * @param {string} [locale]
 * @returns {string}
 */
export function resolveHelpHref(href, locale = 'en') {
    const value = String(href || '').trim();
    if (isExternalHelpHref(value)) {
        return value;
    }
    if (isAppToolHref(value)) {
        return localizeAppHref(value, locale);
    }
    return '#';
}

/**
 * @deprecated Prefer resolveHelpHref — kept for call sites that only need externals.
 * @param {string} href
 * @returns {string}
 */
export function resolveExternalHelpHref(href) {
    return resolveHelpHref(href);
}

/**
 * @typedef {{
 *   instanceId: string,
 *   itemKey: string,
 *   kind: 'task'|'deliverable',
 *   sprintId: string,
 *   custom: boolean,
 *   returnUrl: string,
 * }} PlanToolContext
 */

/**
 * Append plan return context so discovery tools can write back into the open item.
 * @param {string} href
 * @param {PlanToolContext} context
 * @param {string} [locale]
 * @returns {string}
 */
export function buildPlanToolHref(href, context, locale = 'en') {
    const path = resolveHelpHref(href, locale);
    if (path === '#' || !isAppToolHref(href) || !context?.instanceId || !context?.itemKey) {
        return path;
    }
    const params = new URLSearchParams({
        fromPlan: '1',
        instanceId: String(context.instanceId),
        itemKey: String(context.itemKey),
        kind: String(context.kind || 'task'),
        sprintId: String(context.sprintId || ''),
        custom: context.custom ? '1' : '0',
        return: String(context.returnUrl || ''),
    });
    return `${path}?${params.toString()}`;
}

/**
 * Parse "Label | https://…" lines. Allows external URLs and /tools/ paths (not /playbooks/).
 * @param {string} raw
 * @returns {{links: Array<{label: string, href: string, description: string}>, rejected: string[]}}
 */
export function parseExternalLinksTextarea(raw) {
    /** @type {Array<{label: string, href: string, description: string}>} */
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
        if (!isAllowedHelpHref(href)) {
            rejected.push(trimmed);
            continue;
        }
        links.push({ label: label || href, href, description: '' });
    }
    return { links, rejected };
}

/**
 * @param {Array<{label: string, href: string, description?: string}>} links
 */
export function formatExternalLinksTextarea(links = []) {
    return (links || [])
        .filter((link) => link?.href && isAllowedHelpHref(link.href))
        .map((link) => (link.label && link.label !== link.href
            ? `${link.label} | ${link.href}`
            : link.href))
        .join('\n');
}

/**
 * Drop non-allowed links when normalizing stored help links.
 * @param {unknown} links
 * @param {string} [locale]
 * @returns {Array<{label: string, href: string, description: string}>}
 */
export function filterExternalHelpLinks(links, locale = 'en') {
    if (!Array.isArray(links)) {
        return [];
    }
    /** @type {Array<{label: string, href: string, description: string}>} */
    const out = [];
    for (const link of links) {
        if (!link || typeof link !== 'object') {
            continue;
        }
        const href = String(/** @type {any} */ (link).href || '').trim();
        if (!isAllowedHelpHref(href)) {
            continue;
        }
        let label = /** @type {any} */ (link).label;
        if (label && typeof label === 'object') {
            label = label[locale] || label.en || label.de || '';
        }
        let description = /** @type {any} */ (link).description;
        if (description && typeof description === 'object') {
            description = description[locale] || description.en || description.de || '';
        }
        out.push({
            label: String(label || href),
            href,
            description: String(description || ''),
        });
    }
    return out;
}
