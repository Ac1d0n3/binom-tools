import { getLocale } from '../../locale.js';
import { isAccountsMode } from '../accounts-bridge.js';
import { t } from '../labels.js';
import { setLastOpenedPlanId as persistLastOpenedPlanId, clearLastOpenedPlanIdIf } from '../storage.js';

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
    if (key === 'active-person-missing') {
        return spT('sp.error.activePersonMissing');
    }
    if (key === 'attachment-too-large') {
        return spT('sp.error.attachmentTooLarge');
    }
    if (key === 'attachment-type') {
        return spT('sp.error.attachmentType');
    }
    if (key === 'attachment-upload' || String(key || '').startsWith('attachment-upload')) {
        return spT('sp.error.attachmentUpload');
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
 * @param {string} [emptyLabel]
 */
export function fillSelect(select, options, selected = '', includeEmpty = true, emptyLabel = '') {
    select.innerHTML = '';
    if (includeEmpty) {
        const opt = document.createElement('option');
        opt.value = '';
        opt.textContent = emptyLabel || spT('sp.field.none');
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
 * Read JSON from a bootstrap <script type="application/json" id="…"> tag.
 * @param {string} elementId
 * @param {unknown} [fallback]
 */
export function readBootstrapJson(elementId, fallback = null) {
    const el = document.getElementById(elementId);
    if (!el?.textContent?.trim()) {
        return fallback;
    }
    try {
        return JSON.parse(el.textContent);
    } catch {
        return fallback;
    }
}

/**
 * @returns {object[]}
 */
export function readTemplatesFromDom() {
    const fromScript = readBootstrapJson('sp-bootstrap-templates', null);
    const root = document.getElementById('sp-app');
    let fromAttr = [];
    if (root?.dataset?.spTemplates) {
        try {
            const parsed = JSON.parse(root.dataset.spTemplates);
            fromAttr = Array.isArray(parsed) ? parsed : [];
        } catch {
            fromAttr = [];
        }
    }
    if (Array.isArray(fromScript) && fromScript.length > 0) {
        return fromScript;
    }
    if (fromAttr.length > 0) {
        return fromAttr;
    }
    return Array.isArray(fromScript) ? fromScript : [];
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

/**
 * Index URL that always shows the plan list (skips last-opened restore).
 */
export function plansListUrl() {
    const root = document.getElementById('sp-app');
    const raw = root?.dataset.spIndexUrl || '/sprint-planner';
    let path = raw;
    try {
        if (/^https?:\/\//i.test(raw)) {
            path = new URL(raw).pathname;
        }
    } catch {
        path = raw;
    }
    const normalized = path.replace(/\/$/, '') || '/sprint-planner';
    return `${normalized}?list=1`;
}

export function setLastOpenedPlanId(planId) {
    persistLastOpenedPlanId(planId);
}

export { clearLastOpenedPlanIdIf };
