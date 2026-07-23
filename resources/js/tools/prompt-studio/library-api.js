/**
 * Account-backed Prompt Studio library sync (templates + chains + customRoles).
 */

/**
 * @returns {{ enabled: boolean, url: string, userId: string }}
 */
export function readLibraryApiConfig(root = document.getElementById('prompt-studio-app')) {
    if (!root) {
        return { enabled: false, url: '', userId: '' };
    }
    const url = String(root.getAttribute('data-library-api-url') || '').trim();
    const userId = String(root.getAttribute('data-account-user-id') || '').trim();
    const enabled = root.getAttribute('data-accounts-enabled') === '1' && Boolean(url) && Boolean(userId);
    return { enabled, url, userId };
}

/**
 * @param {string} url
 * @returns {Promise<{ templates: unknown[], chains: unknown[], customRoles: unknown[] } | null>}
 */
export async function fetchLibraryBundle(url) {
    try {
        const res = await fetch(url, {
            headers: { Accept: 'application/json' },
            credentials: 'same-origin',
        });
        if (!res.ok) return null;
        const data = await res.json();
        return {
            templates: Array.isArray(data.templates) ? data.templates : [],
            chains: Array.isArray(data.chains) ? data.chains : [],
            customRoles: Array.isArray(data.customRoles) ? data.customRoles : [],
        };
    } catch {
        return null;
    }
}

/**
 * @param {string} url
 * @param {{ templates?: unknown[], chains?: unknown[], customRoles?: unknown[] }} bundle
 */
export async function upsertLibraryBundle(url, bundle) {
    try {
        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const res = await fetch(url, {
            method: 'POST',
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
            },
            credentials: 'same-origin',
            body: JSON.stringify({ library: bundle }),
        });
        return res.ok;
    } catch {
        return false;
    }
}

/**
 * Merge remote items over local by id (remote wins).
 * @template {{ id: string }} T
 * @param {T[]} local
 * @param {T[]} remote
 * @returns {T[]}
 */
export function mergeById(local, remote) {
    const map = new Map();
    for (const item of local) {
        if (item?.id) map.set(item.id, item);
    }
    for (const item of remote) {
        if (item?.id) map.set(item.id, item);
    }
    return [...map.values()];
}
