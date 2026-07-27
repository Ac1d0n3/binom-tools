/**
 * Client helpers for user template CRUD API.
 */
import { csrfToken, readAccountsBootstrap } from './accounts-bridge.js';

export function userTemplatesApiUrl() {
    const root = document.getElementById('sp-app');
    return root?.dataset?.userTemplatesApiUrl || null;
}

export function readUserTemplatesFromDom() {
    const el = document.getElementById('sp-bootstrap-user-templates');
    if (el?.textContent?.trim()) {
        try {
            const parsed = JSON.parse(el.textContent);
            return Array.isArray(parsed) ? parsed : [];
        } catch {
            // fall through
        }
    }
    const root = document.getElementById('sp-app');
    if (!root?.dataset?.userTemplates) {
        return [];
    }
    try {
        const parsed = JSON.parse(root.dataset.userTemplates);
        return Array.isArray(parsed) ? parsed : [];
    } catch {
        return [];
    }
}

/**
 * @param {Record<string, unknown>} template
 */
export async function upsertUserTemplate(template) {
    const url = userTemplatesApiUrl();
    if (!url) {
        throw new Error('user-templates-api-missing');
    }
    const response = await fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            Accept: 'application/json',
            'X-CSRF-TOKEN': csrfToken(),
            'X-Requested-With': 'XMLHttpRequest',
        },
        credentials: 'same-origin',
        body: JSON.stringify({ template }),
    });
    if (!response.ok) {
        throw new Error(`user-template-save-${response.status}`);
    }
    return response.json();
}

/**
 * @param {string} templateId
 */
export async function deleteUserTemplate(templateId) {
    const base = userTemplatesApiUrl();
    if (!base) {
        throw new Error('user-templates-api-missing');
    }
    const url = `${base.replace(/\/$/, '')}/${encodeURIComponent(templateId)}`;
    const response = await fetch(url, {
        method: 'DELETE',
        headers: {
            Accept: 'application/json',
            'X-CSRF-TOKEN': csrfToken(),
            'X-Requested-With': 'XMLHttpRequest',
        },
        credentials: 'same-origin',
    });
    if (!response.ok) {
        throw new Error(`user-template-delete-${response.status}`);
    }
    return response.json();
}

export function isLoggedInForTemplates() {
    return Boolean(readAccountsBootstrap().accountUser?.id);
}
