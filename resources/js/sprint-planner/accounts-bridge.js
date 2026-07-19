/**
 * Accounts-mode bridge for sprint planner (server JSON plans + shared catalog).
 */

import { normalizeTrigram, teamTrigram } from './trigram.js';

/** @returns {boolean} */
export function isAccountsMode() {
    const root = document.getElementById('sp-app');
    const flag = root?.dataset?.accountsEnabled;

    return flag === '1' || flag === 'true';
}

/** @returns {boolean} */
export function isLoggedIn() {
    const user = readAccountsBootstrap().accountUser;

    return Boolean(user && typeof user === 'object' && user.id);
}

/**
 * Server plan sync only when accounts are on and the user is signed in.
 * @returns {boolean}
 */
export function usesServerPlans() {
    return isAccountsMode() && isLoggedIn();
}

/**
 * Guest demo workspace (accounts on, not signed in) lives in sessionStorage only.
 * @returns {boolean}
 */
export function usesSessionDemoStore() {
    return isAccountsMode() && ! isLoggedIn();
}

/**
 * @returns {{
 *   plansApiUrl: string|null,
 *   storiesApiUrl: string|null,
 *   loginUrl: string|null,
 *   accountUser: Record<string, unknown>|null,
 *   people: Array<Record<string, unknown>>,
 *   teams: Array<Record<string, unknown>>,
 *   serverPlans: Array<Record<string, unknown>>,
 *   readSlugs: string[],
 * }}
 */
export function readAccountsBootstrap() {
    const root = document.getElementById('sp-app');
    if (!root) {
        return {
            plansApiUrl: null,
            storiesApiUrl: null,
            loginUrl: null,
            accountUser: null,
            people: [],
            teams: [],
            serverPlans: [],
            readSlugs: [],
        };
    }

    const accountUser = pickBootstrap(
        readScriptJson('sp-bootstrap-account-user', null),
        parseJson(root.dataset.accountUser, null),
    );
    const people = pickBootstrapList(
        readScriptJson('sp-bootstrap-account-people', null),
        parseJson(root.dataset.accountPeople, []),
    );
    const teams = pickBootstrapList(
        readScriptJson('sp-bootstrap-account-teams', null),
        parseJson(root.dataset.accountTeams, []),
    );
    const serverPlans = pickBootstrapList(
        readScriptJson('sp-bootstrap-server-plans', null),
        parseJson(root.dataset.serverPlans, []),
    );
    const readSlugs = pickBootstrapList(
        readScriptJson('sp-bootstrap-read-slugs', null),
        parseJson(root.dataset.readSlugs, []),
    );

    return {
        plansApiUrl: root.dataset.plansApiUrl || null,
        storiesApiUrl: root.dataset.storiesApiUrl || null,
        loginUrl: root.dataset.loginUrl || null,
        accountUser: accountUser && typeof accountUser === 'object' ? accountUser : null,
        people,
        teams,
        serverPlans,
        readSlugs,
    };
}

/**
 * @param {unknown} primary
 * @param {unknown} fallback
 */
function pickBootstrap(primary, fallback) {
    if (primary !== null && primary !== undefined) {
        return primary;
    }
    return fallback;
}

/**
 * @param {unknown} primary
 * @param {unknown} fallback
 * @returns {Array}
 */
function pickBootstrapList(primary, fallback) {
    if (Array.isArray(primary) && primary.length > 0) {
        return primary;
    }
    if (Array.isArray(fallback) && fallback.length > 0) {
        return fallback;
    }
    return Array.isArray(primary) ? primary : (Array.isArray(fallback) ? fallback : []);
}

/**
 * @param {string} elementId
 * @param {unknown} fallback
 */
function readScriptJson(elementId, fallback) {
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

/** @returns {string} */
export function csrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
}

/**
 * @param {Record<string, unknown>} plan
 * @param {string} plansApiUrl
 */
export async function upsertPlanOnServer(plan, plansApiUrl, historyMeta = {}) {
    if (!plansApiUrl) {
        throw new Error('plans-api-missing');
    }
    if (plan.ephemeral) {
        return { plan };
    }

    /** @type {Record<string, unknown>} */
    const body = { plan };
    if (historyMeta && (historyMeta.action || historyMeta.summary)) {
        body.history = {
            action: String(historyMeta.action || 'update'),
            summary: String(historyMeta.summary || 'Plan updated'),
        };
    }

    const response = await fetch(plansApiUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            Accept: 'application/json',
            'X-CSRF-TOKEN': csrfToken(),
            'X-Requested-With': 'XMLHttpRequest',
        },
        credentials: 'same-origin',
        body: JSON.stringify(body),
    });

    if (!response.ok) {
        throw new Error(`plans-save-${response.status}`);
    }

    return response.json();
}

/**
 * @param {string} planId
 * @param {string} plansApiUrl
 */
export async function deletePlanOnServer(planId, plansApiUrl) {
    if (!plansApiUrl) {
        throw new Error('plans-api-missing');
    }

    const url = `${plansApiUrl.replace(/\/$/, '')}/${encodeURIComponent(planId)}`;
    const response = await fetch(url, {
        method: 'DELETE',
        headers: {
            Accept: 'application/json',
            'X-CSRF-TOKEN': csrfToken(),
            'X-Requested-With': 'XMLHttpRequest',
        },
        credentials: 'same-origin',
    });

    if (!response.ok && response.status !== 404) {
        throw new Error(`plans-delete-${response.status}`);
    }
}

/**
 * Upload a plan attachment file (accounts mode).
 * @param {string} planId
 * @param {File} file
 * @param {string} [attachmentId]
 * @returns {Promise<{attachment: import('./attachments.js').SpAttachment}>}
 */
export async function uploadAttachmentToServer(planId, file, attachmentId) {
    const plansApiUrl = readAccountsBootstrap().plansApiUrl;
    if (!plansApiUrl) {
        throw new Error('plans-api-missing');
    }
    const base = plansApiUrl.replace(/\/$/, '');
    const url = `${base}/${encodeURIComponent(planId)}/attachments`;
    const body = new FormData();
    body.append('file', file);
    if (attachmentId) {
        body.append('attachmentId', attachmentId);
    }
    const response = await fetch(url, {
        method: 'POST',
        headers: {
            Accept: 'application/json',
            'X-CSRF-TOKEN': csrfToken(),
            'X-Requested-With': 'XMLHttpRequest',
        },
        credentials: 'same-origin',
        body,
    });
    if (!response.ok) {
        throw new Error(`attachment-upload-${response.status}`);
    }
    return response.json();
}

/**
 * Delete a server-side attachment.
 * @param {string} planId
 * @param {string} attachmentId
 */
export async function deleteAttachmentOnServer(planId, attachmentId) {
    const plansApiUrl = readAccountsBootstrap().plansApiUrl;
    if (!plansApiUrl) {
        return;
    }
    const base = plansApiUrl.replace(/\/$/, '');
    const url = `${base}/${encodeURIComponent(planId)}/attachments/${encodeURIComponent(attachmentId)}`;
    await fetch(url, {
        method: 'DELETE',
        headers: {
            Accept: 'application/json',
            'X-CSRF-TOKEN': csrfToken(),
            'X-Requested-With': 'XMLHttpRequest',
        },
        credentials: 'same-origin',
    });
}

/**
 * @param {string} planId
 * @returns {Promise<{revisions: Array<Record<string, unknown>>}>}
 */
export async function fetchPlanHistory(planId) {
    const plansApiUrl = readAccountsBootstrap().plansApiUrl;
    if (!plansApiUrl) {
        throw new Error('plans-api-missing');
    }
    const base = plansApiUrl.replace(/\/$/, '');
    const url = `${base}/${encodeURIComponent(planId)}/history`;
    const response = await fetch(url, {
        method: 'GET',
        headers: {
            Accept: 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
        credentials: 'same-origin',
    });
    if (response.status === 401) {
        throw new Error('plans-history-unauthorized');
    }
    if (response.status === 403) {
        throw new Error('plans-history-forbidden');
    }
    if (response.status === 404) {
        throw new Error('plans-history-not-found');
    }
    if (!response.ok) {
        throw new Error(`plans-history-${response.status}`);
    }
    return response.json();
}

/**
 * @param {string} planId
 * @param {string} revisionId
 * @returns {Promise<{revision: Record<string, unknown>}>}
 */
export async function fetchPlanRevision(planId, revisionId) {
    const plansApiUrl = readAccountsBootstrap().plansApiUrl;
    if (!plansApiUrl) {
        throw new Error('plans-api-missing');
    }
    const base = plansApiUrl.replace(/\/$/, '');
    const url = `${base}/${encodeURIComponent(planId)}/history/${encodeURIComponent(revisionId)}`;
    const response = await fetch(url, {
        method: 'GET',
        headers: {
            Accept: 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
        credentials: 'same-origin',
    });
    if (!response.ok) {
        throw new Error(`plans-revision-${response.status}`);
    }
    return response.json();
}

/**
 * @param {string} planId
 * @param {string} revisionId
 * @returns {Promise<{plan: Record<string, unknown>}>}
 */
export async function restorePlanRevision(planId, revisionId) {
    const plansApiUrl = readAccountsBootstrap().plansApiUrl;
    if (!plansApiUrl) {
        throw new Error('plans-api-missing');
    }
    const base = plansApiUrl.replace(/\/$/, '');
    const url = `${base}/${encodeURIComponent(planId)}/history/${encodeURIComponent(revisionId)}/restore`;
    const response = await fetch(url, {
        method: 'POST',
        headers: {
            Accept: 'application/json',
            'X-CSRF-TOKEN': csrfToken(),
            'X-Requested-With': 'XMLHttpRequest',
        },
        credentials: 'same-origin',
    });
    if (!response.ok) {
        throw new Error(`plans-restore-${response.status}`);
    }
    return response.json();
}

/**
 * Map account users/teams into sprint-planner catalog shape.
 * @param {Array<Record<string, unknown>>} users
 * @param {Array<Record<string, unknown>>} teams
 */
export function catalogFromAccounts(users, teams) {
    /** @type {Record<string, import('./storage.js').SpPerson>} */
    const people = {};
    let accent = 1;
    for (const user of users) {
        const id = String(user.id || '');
        if (!id) {
            continue;
        }
        const displayName = String(user.displayName || user.email || id);
        const persistedShort = String(user.shortName || '').trim();
        const persistedColor = String(user.colorToken || '').trim();
        const persistedIcon = String(user.avatarIcon || '').trim();
        people[id] = {
            id,
            displayName,
            shortName: persistedShort || normalizeTrigram('', displayName),
            email: String(user.email || ''),
            role: '',
            colorToken: /^accent-(?:[1-9]|1[0-2])$|^(?:outline|dotted|dashed)-[1-6]$/.test(persistedColor)
                ? persistedColor
                : `accent-${((accent - 1) % 12) + 1}`,
            avatarIcon: persistedIcon,
            archived: user.active === false,
        };
        accent += 1;
    }

    /** @type {Record<string, import('./storage.js').SpTeam>} */
    const teamMap = {};
    for (const team of teams) {
        const id = String(team.id || '');
        if (!id) {
            continue;
        }
        const name = team.name && typeof team.name === 'object'
            ? /** @type {{de?: string, en?: string}} */ (team.name)
            : { de: String(team.name || id), en: String(team.name || id) };
        const description = team.description && typeof team.description === 'object'
            ? /** @type {{de?: string, en?: string}} */ (team.description)
            : { de: '', en: '' };
        const nameObj = { de: name.de || name.en || id, en: name.en || name.de || id };
        const persistedShort = String(team.shortName || '').trim();
        const persistedColor = String(team.colorToken || '').trim();
        const persistedIcon = String(team.avatarIcon || '').trim();
        teamMap[id] = {
            id,
            name: nameObj,
            description: { de: description.de || '', en: description.en || '' },
            shortName: persistedShort || (persistedIcon ? '' : teamTrigram({ name: nameObj }, 'en')),
            colorToken: /^accent-(?:[1-9]|1[0-2])$|^(?:outline|dotted|dashed)-[1-6]$/.test(persistedColor)
                ? persistedColor
                : `accent-${((Object.keys(teamMap).length % 6) + 1)}`,
            avatarIcon: persistedIcon,
            memberIds: Array.isArray(team.memberIds) ? team.memberIds.map(String) : [],
            memberRoles: team.memberRoles && typeof team.memberRoles === 'object' && !Array.isArray(team.memberRoles)
                ? Object.fromEntries(
                    Object.entries(/** @type {Record<string, unknown>} */ (team.memberRoles))
                        .map(([uid, role]) => [String(uid), String(role || 'member')]),
                )
                : {},
            archived: Boolean(team.archived),
        };
    }

    return { people, teams: teamMap };
}

/**
 * @param {string|undefined} raw
 * @param {unknown} fallback
 */
function parseJson(raw, fallback) {
    if (!raw) {
        return fallback;
    }
    try {
        return JSON.parse(raw);
    } catch {
        return fallback;
    }
}
