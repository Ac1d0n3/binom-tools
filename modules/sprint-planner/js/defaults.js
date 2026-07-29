/**
 * Stable starter people and teams for the local sprint planner workspace.
 * Demo personas only — never real colleague identities.
 * Existing IDs are never overwritten (except legacy rename / archive below).
 */

export const DEFAULT_PEOPLE = [
    {
        id: 'person_thomas_a',
        displayName: 'Thomas L',
        shortName: 'THL',
        email: '',
        role: '',
        colorToken: 'accent-1',
        archived: false,
    },
    {
        id: 'person_lena',
        displayName: 'Lena S.',
        shortName: 'LEN',
        email: '',
        role: '',
        colorToken: 'accent-2',
        archived: false,
    },
    {
        id: 'person_jonas',
        displayName: 'Jonas K.',
        shortName: 'JON',
        email: '',
        role: '',
        colorToken: 'accent-3',
        archived: false,
    },
];

export const DEFAULT_TEAMS = [
    {
        id: 'team_q',
        name: { de: 'Team Q', en: 'Team Q' },
        description: { de: '', en: '' },
        shortName: 'TQ',
        colorToken: 'accent-1',
        memberIds: ['person_thomas_a', 'person_lena', 'person_jonas'],
        archived: false,
    },
    {
        id: 'team_fabrics',
        name: { de: 'Team Fabrics', en: 'Team Fabrics' },
        description: { de: '', en: '' },
        shortName: 'FAB',
        colorToken: 'accent-2',
        memberIds: [],
        archived: false,
    },
    {
        id: 'team_data',
        name: { de: 'Team Data', en: 'Team Data' },
        description: { de: '', en: '' },
        shortName: 'DAT',
        colorToken: 'accent-3',
        memberIds: [],
        archived: false,
    },
    {
        id: 'team_analytics',
        name: { de: 'Team Analytics', en: 'Team Analytics' },
        description: { de: '', en: '' },
        shortName: 'ANA',
        colorToken: 'accent-4',
        memberIds: [],
        archived: false,
    },
];

/** Legacy starter person ids that must not appear as active demo cast. */
const LEGACY_DEMO_PERSON_IDS = ['person_thomas_b', 'person_matthias'];

/**
 * @param {import('./storage.js').SpWorkspaceRoot} workspace
 * @returns {{workspace: import('./storage.js').SpWorkspaceRoot, changed: boolean}}
 */
export function ensureDefaultCatalog(workspace) {
    let changed = false;
    const next = {
        ...workspace,
        people: { ...workspace.people },
        teams: { ...workspace.teams },
        workspace: { ...workspace.workspace },
    };

    // Thomas A → Thomas L (display only; keep stable id).
    const thomas = next.people.person_thomas_a;
    if (thomas && (thomas.displayName === 'Thomas A' || thomas.shortName === 'THA')) {
        next.people.person_thomas_a = {
            ...thomas,
            displayName: thomas.displayName === 'Thomas A' ? 'Thomas L' : thomas.displayName,
            shortName: thomas.shortName === 'THA' ? 'THL' : thomas.shortName,
        };
        changed = true;
    }

    for (const person of DEFAULT_PEOPLE) {
        if (!next.people[person.id]) {
            next.people[person.id] = { ...person };
            changed = true;
        }
    }

    for (const legacyId of LEGACY_DEMO_PERSON_IDS) {
        const legacy = next.people[legacyId];
        if (legacy && !legacy.archived) {
            next.people[legacyId] = { ...legacy, archived: true };
            changed = true;
        }
    }

    for (const team of DEFAULT_TEAMS) {
        if (!next.teams[team.id]) {
            next.teams[team.id] = {
                ...team,
                name: { ...team.name },
                description: { ...team.description },
                memberIds: [...team.memberIds],
            };
            changed = true;
        } else {
            // Backfill color/shortName on existing default teams without overwriting custom values.
            const existing = next.teams[team.id];
            let teamNext = existing;
            if (!existing.colorToken) {
                teamNext = { ...teamNext, colorToken: team.colorToken };
                changed = true;
            }
            if (!existing.shortName) {
                teamNext = { ...teamNext, shortName: team.shortName };
                changed = true;
            }
            const members = Array.isArray(teamNext.memberIds) ? teamNext.memberIds : [];
            const cleaned = members.filter((id) => !LEGACY_DEMO_PERSON_IDS.includes(String(id)));
            if (cleaned.length !== members.length) {
                teamNext = { ...teamNext, memberIds: cleaned };
                changed = true;
            }
            next.teams[team.id] = teamNext;
        }
    }

    if (!next.workspace.activePersonId && next.people.person_thomas_a) {
        next.workspace.activePersonId = 'person_thomas_a';
        changed = true;
    }

    if (!next.workspace.defaultTeamId && next.teams.team_q) {
        next.workspace.defaultTeamId = 'team_q';
        changed = true;
    }

    // Plans without teams inherit the workspace default (Team Q).
    const fallbackTeamId = next.workspace.defaultTeamId
        && next.teams[next.workspace.defaultTeamId]
        ? next.workspace.defaultTeamId
        : (next.teams.team_q ? 'team_q' : null);
    if (fallbackTeamId) {
        next.instances = { ...next.instances };
        for (const [id, instance] of Object.entries(next.instances)) {
            const teamIds = Array.isArray(instance.teamIds) ? instance.teamIds.filter(Boolean) : [];
            if (teamIds.length) {
                continue;
            }
            next.instances[id] = {
                ...instance,
                teamIds: [fallbackTeamId],
                teamId: null,
            };
            changed = true;
        }
    }

    return { workspace: next, changed };
}
