/**
 * Stable starter people and teams for the local sprint planner workspace.
 * Existing IDs are never overwritten.
 */

export const DEFAULT_PEOPLE = [
    {
        id: 'person_thomas_a',
        displayName: 'Thomas A',
        shortName: 'THA',
        email: '',
        role: '',
        colorToken: 'accent-1',
        archived: false,
    },
    {
        id: 'person_thomas_b',
        displayName: 'Thomas B',
        shortName: 'THB',
        email: '',
        role: '',
        colorToken: 'accent-2',
        archived: false,
    },
    {
        id: 'person_matthias',
        displayName: 'Matthias',
        shortName: 'MAT',
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
        memberIds: ['person_thomas_a', 'person_thomas_b', 'person_matthias'],
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

    for (const person of DEFAULT_PEOPLE) {
        if (!next.people[person.id]) {
            next.people[person.id] = { ...person };
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
            if (!existing.colorToken) {
                next.teams[team.id] = { ...existing, colorToken: team.colorToken };
                changed = true;
            }
            if (!existing.shortName) {
                next.teams[team.id] = { ...next.teams[team.id], shortName: team.shortName };
                changed = true;
            }
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
