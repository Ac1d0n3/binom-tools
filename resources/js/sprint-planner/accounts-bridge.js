/**
 * Accounts-mode bridge for sprint planner (server JSON plans + shared catalog).
 */

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

    return {
        plansApiUrl: root.dataset.plansApiUrl || null,
        storiesApiUrl: root.dataset.storiesApiUrl || null,
        loginUrl: root.dataset.loginUrl || null,
        accountUser: parseJson(root.dataset.accountUser, null),
        people: parseJson(root.dataset.accountPeople, []),
        teams: parseJson(root.dataset.accountTeams, []),
        serverPlans: parseJson(root.dataset.serverPlans, []),
        readSlugs: parseJson(root.dataset.readSlugs, []),
    };
}

/** @returns {string} */
export function csrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
}

/**
 * @param {Record<string, unknown>} plan
 * @param {string} plansApiUrl
 */
export async function upsertPlanOnServer(plan, plansApiUrl) {
    if (!plansApiUrl) {
        throw new Error('plans-api-missing');
    }
    if (plan.ephemeral) {
        return { plan };
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
        body: JSON.stringify({ plan }),
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
        people[id] = {
            id,
            displayName,
            shortName: displayName.slice(0, 2).toUpperCase(),
            email: String(user.email || ''),
            role: '',
            colorToken: `accent-${((accent - 1) % 6) + 1}`,
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
        teamMap[id] = {
            id,
            name: { de: name.de || name.en || id, en: name.en || name.de || id },
            description: { de: description.de || '', en: description.en || '' },
            memberIds: Array.isArray(team.memberIds) ? team.memberIds.map(String) : [],
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
