import { getLocale } from '../../locale.js';
import { isAccountsMode } from '../accounts-bridge.js';
import { t } from '../labels.js';

/**
 * Apply data-i18n attributes inside a root for sprint-planner keys.
 * Shell locale.js already handles nav.*; this covers sp.* keys on the page.
 * @param {ParentNode} [root]
 */
export function applySpI18n(root = document) {
    const locale = getLocale();
    root.querySelectorAll('[data-i18n]').forEach((el) => {
        const key = el.getAttribute('data-i18n');
        if (!key || !key.startsWith('sp.')) {
            return;
        }
        // Prefer explicit bilingual attributes (works even before Vite rebuild).
        const localized = el.getAttribute(locale === 'de' ? 'data-text-de' : 'data-text-en')
            || el.getAttribute(locale === 'de' ? 'data-i18n-de' : 'data-i18n-en');
        if (typeof localized === 'string' && localized !== '') {
            el.textContent = localized;
            return;
        }
        // Never overwrite server-rendered copy with a raw missing key.
        if (el.hasAttribute('data-i18n-server')) {
            return;
        }
        const count = el.getAttribute('data-i18n-count');
        const text = t(key, locale, count ? { count } : {});
        if (text === key) {
            return;
        }
        el.textContent = text;
    });
    root.querySelectorAll('[data-i18n-placeholder]').forEach((el) => {
        const key = el.getAttribute('data-i18n-placeholder');
        if (!key || !key.startsWith('sp.')) {
            return;
        }
        const text = t(key, locale);
        if (text === key || !('placeholder' in el)) {
            return;
        }
        el.placeholder = text;
    });
}

/**
 * @param {string} key
 * @param {Record<string, string|number>} [vars]
 */
export function spT(key, vars = {}) {
    return t(key, getLocale(), vars);
}

/**
 * @param {string} message
 */
export function showToast(message) {
    const el = document.getElementById('sp-toast');
    if (!el) {
        return;
    }
    el.textContent = message;
    el.hidden = false;
    window.clearTimeout(showToast._timer);
    showToast._timer = window.setTimeout(() => {
        el.hidden = true;
    }, 3200);
}

/**
 * @param {string} key
 */
export function storageErrorMessage(key) {
    if (key === 'storage-unavailable') {
        return spT('sp.error.storageUnavailable');
    }
    if (key === 'storage-full') {
        return spT('sp.error.storageFull');
    }
    if (key === 'storage-corrupt') {
        return spT('sp.error.storageCorrupt');
    }
    if (key === 'unsupported-schema' || key === 'import-schema') {
        return spT('sp.error.importSchema');
    }
    return spT('sp.error.importInvalid');
}

/**
 * @param {HTMLSelectElement} select
 * @param {Array<{id: string, label: string}>} options
 * @param {string} [selected]
 * @param {boolean} [includeEmpty]
 */
export function fillSelect(select, options, selected = '', includeEmpty = true) {
    select.innerHTML = '';
    if (includeEmpty) {
        const opt = document.createElement('option');
        opt.value = '';
        opt.textContent = spT('sp.field.none');
        select.appendChild(opt);
    }
    for (const item of options) {
        const opt = document.createElement('option');
        opt.value = item.id;
        opt.textContent = item.label;
        if (item.id === selected) {
            opt.selected = true;
        }
        select.appendChild(opt);
    }
}

/**
 * @param {import('../storage.js').SpPlanInstance} instance
 * @param {import('../storage.js').SpWorkspaceRoot} workspace
 */
export function planOwnershipLabel(instance, workspace) {
    if (instance.ephemeral) {
        return spT('sp.card.ownerDemo');
    }
    if (!isAccountsMode()) {
        return spT('sp.card.ownerLocal');
    }
    const ownerId = instance.ownerUserId ? String(instance.ownerUserId) : '';
    if (!ownerId) {
        return spT('sp.card.ownerUnknown');
    }
    const person = workspace.people?.[ownerId];
    const name = person?.displayName || ownerId;
    return spT('sp.card.owner', { name });
}

/**
 * @returns {object[]}
 */
export function readTemplatesFromDom() {
    const root = document.getElementById('sp-app');
    if (!root) {
        return [];
    }
    try {
        return JSON.parse(root.dataset.spTemplates || '[]');
    } catch {
        return [];
    }
}

/**
 * Build show URL for an instance (always path-absolute, never cross-origin).
 * @param {string} instanceId
 */
export function planUrl(instanceId) {
    const root = document.getElementById('sp-app');
    const raw = root?.dataset.spShowBase || root?.dataset.spIndexUrl || '/sprint-planner';
    let path = raw;
    try {
        if (/^https?:\/\//i.test(raw)) {
            path = new URL(raw).pathname;
        }
    } catch {
        path = raw;
    }
    const normalized = path.replace(/\/$/, '') || '/sprint-planner';
    return `${normalized}/${instanceId}`;
}
