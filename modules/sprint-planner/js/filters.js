/**
 * Plan item filters — structural filters always AND.
 *
 * Ownership model:
 * - Plan → team(s)
 * - Task/deliverable → person or unassigned (not a team)
 *
 * Assignee scope:
 * - Person dropdown → that person only
 * - Team dropdown → tasks assigned to members of that team (plus legacy team assignees)
 * - Otherwise "My tasks" + "Unassigned" are one focus facet (always OR'd)
 */

/**
 * @param {Record<string, unknown>} raw
 * @returns {{
 *   currentWeek: boolean,
 *   hideDone: boolean,
 *   openOnly: boolean,
 *   blocked: boolean,
 *   myTasks: boolean,
 *   unassigned: boolean,
 *   personId: string,
 *   teamId: string,
 *   status: string,
 *   priority: string,
 *   filterLogic: 'and'|'or',
 *   search: string,
 * }}
 */
export function normalizePlanFilters(raw = {}) {
    const source = raw && typeof raw === 'object' ? raw : {};

    // Migrate away from XOR assigneeFilter radios back to independent flags.
    let myTasks = Boolean(source.myTasks);
    let unassigned = Boolean(source.unassigned);
    let personId = String(source.personId || '');
    let teamId = String(source.teamId || '');

    if (typeof source.assigneeFilter === 'string' && source.assigneeFilter) {
        const mode = source.assigneeFilter;
        myTasks = mode === 'myTasks';
        unassigned = mode === 'unassigned';
        if (mode === 'person') {
            personId = String(source.personId || '');
        } else if (mode !== 'person') {
            personId = mode === 'all' ? '' : personId;
        }
        if (mode === 'team') {
            teamId = String(source.teamId || '');
        } else if (mode !== 'team') {
            teamId = mode === 'all' ? '' : teamId;
        }
        if (mode === 'all') {
            myTasks = false;
            unassigned = false;
            personId = '';
            teamId = '';
        }
    }

    const filterLogic = source.filterLogic === 'or' ? 'or' : 'and';

    return {
        currentWeek: Boolean(source.currentWeek),
        hideDone: Boolean(source.hideDone),
        openOnly: Boolean(source.openOnly),
        blocked: Boolean(source.blocked),
        myTasks,
        unassigned,
        personId,
        teamId,
        status: String(source.status || ''),
        priority: String(source.priority || ''),
        filterLogic,
        search: String(source.search || '').trim(),
    };
}

/**
 * @param {object} item
 * @param {string|null} activePersonId
 */
function matchesMyTasks(item, activePersonId) {
    return Boolean(
        activePersonId
        && (!item.assigneeType || item.assigneeType === 'person')
        && String(item.assigneeId) === String(activePersonId),
    );
}

/**
 * @param {object} item
 * @param {string} teamId
 * @param {Record<string, {memberIds?: string[]}>|undefined} teams
 */
function matchesTeamMembers(item, teamId, teams) {
    // Legacy: item was assigned to the team itself.
    if (item.assigneeType === 'team' && String(item.assigneeId) === String(teamId)) {
        return true;
    }
    if (!item.assigneeId || (item.assigneeType && item.assigneeType !== 'person')) {
        return false;
    }
    const members = teams?.[teamId]?.memberIds || [];
    return members.map(String).includes(String(item.assigneeId));
}

/**
 * @param {object} item
 * @param {ReturnType<typeof normalizePlanFilters>} filters
 * @param {{activePersonId: string|null, teams?: Record<string, {memberIds?: string[]}>}} ctx
 */
export function itemMatchesFilters(item, filters, ctx) {
    const normalized = normalizePlanFilters(filters);
    const activePersonId = ctx.activePersonId || null;
    const teams = ctx.teams || {};

    // Structural filters — always AND (including "blocked only").
    if (normalized.hideDone && item.completed) {
        return false;
    }
    if (normalized.openOnly && (item.completed || item.status === 'completed')) {
        return false;
    }
    if (normalized.blocked && item.status !== 'blocked') {
        return false;
    }
    if (normalized.status && item.status !== normalized.status) {
        return false;
    }
    if (normalized.priority && item.priority !== normalized.priority) {
        return false;
    }
    if (normalized.search) {
        const q = normalized.search.toLowerCase();
        const label = String(item.label || '').toLowerCase();
        const note = String(item.note || '').toLowerCase();
        if (!label.includes(q) && !note.includes(q)) {
            return false;
        }
    }

    // Person / team-member dropdowns override the my/unassigned focus facet.
    /** @type {Array<() => boolean>} */
    const groupChecks = [];
    if (normalized.personId || normalized.teamId) {
        if (normalized.personId) {
            groupChecks.push(() => (
                String(item.assigneeId) === normalized.personId
                && (!item.assigneeType || item.assigneeType === 'person')
            ));
        }
        if (normalized.teamId) {
            groupChecks.push(() => matchesTeamMembers(item, normalized.teamId, teams));
        }
    } else if (normalized.myTasks || normalized.unassigned) {
        groupChecks.push(() => {
            const mine = normalized.myTasks && matchesMyTasks(item, activePersonId);
            const open = normalized.unassigned && !item.assigneeId;
            if (normalized.myTasks && normalized.unassigned) {
                return Boolean(mine || open);
            }
            return normalized.myTasks ? Boolean(mine) : Boolean(open);
        });
    }

    if (!groupChecks.length) {
        return true;
    }

    if (normalized.filterLogic === 'or') {
        return groupChecks.some((check) => check());
    }
    return groupChecks.every((check) => check());
}

/**
 * @param {Array<object>} sprints
 * @param {object} filters
 * @param {number} currentSprintNumber
 * @param {{activePersonId: string|null, teams?: Record<string, {memberIds?: string[]}>}} ctx
 */
export function filterSprints(sprints, filters, currentSprintNumber, ctx) {
    const normalized = normalizePlanFilters(filters);
    const context = {
        activePersonId: ctx.activePersonId || null,
        teams: ctx.teams || {},
    };

    return sprints
        .filter((sprint) => {
            if (normalized.currentWeek) {
                // Before plan start there is no current week — show the first upcoming plan week.
                const targetWeek = currentSprintNumber > 0 ? currentSprintNumber : 1;
                if (sprint.number !== targetWeek) {
                    return false;
                }
            }
            return true;
        })
        .map((sprint) => {
            const tasks = sprint.tasks.filter((item) => itemMatchesFilters(item, normalized, context));
            const deliverables = sprint.deliverables.filter((item) => itemMatchesFilters(item, normalized, context));
            const hasItemFilters = normalized.hideDone
                || normalized.openOnly
                || normalized.blocked
                || normalized.myTasks
                || normalized.unassigned
                || normalized.personId
                || normalized.teamId
                || normalized.status
                || normalized.priority
                || normalized.search;
            if (hasItemFilters && tasks.length === 0 && deliverables.length === 0) {
                return null;
            }
            return { ...sprint, tasks, deliverables };
        })
        .filter(Boolean);
}

/**
 * @param {ReturnType<typeof normalizePlanFilters>} filters
 */
export function hasActiveItemFilters(filters) {
    return countActiveItemFilters(filters) > 0;
}

/**
 * Count independent filter facets that currently constrain the plan view.
 * @param {ReturnType<typeof normalizePlanFilters>} filters
 * @returns {number}
 */
export function countActiveItemFilters(filters) {
    const normalized = normalizePlanFilters(filters);
    let count = 0;
    if (normalized.currentWeek) count += 1;
    if (normalized.hideDone) count += 1;
    if (normalized.openOnly) count += 1;
    if (normalized.blocked) count += 1;
    if (normalized.myTasks) count += 1;
    if (normalized.unassigned) count += 1;
    if (normalized.personId) count += 1;
    if (normalized.teamId) count += 1;
    if (normalized.status) count += 1;
    if (normalized.priority) count += 1;
    if (normalized.search) count += 1;
    return count;
}

/**
 * Empty plan filters (show all items). Keeps OR as the default combine mode.
 * @returns {ReturnType<typeof normalizePlanFilters>}
 */
export function emptyPlanFilters() {
    return normalizePlanFilters({
        currentWeek: false,
        hideDone: false,
        openOnly: false,
        blocked: false,
        myTasks: false,
        unassigned: false,
        personId: '',
        teamId: '',
        status: '',
        priority: '',
        filterLogic: 'or',
        search: '',
    });
}

/**
 * If current filters would hide a freshly assigned item, widen assignee filters
 * so the assignment stays visible (common with default myTasks+unassigned OR).
 *
 * @param {Record<string, unknown>} filters
 * @param {{assigneeType?: string|null, assigneeId?: string|null}} assignment
 * @param {{activePersonId: string|null, teams?: Record<string, {memberIds?: string[]}>}} ctx
 * @returns {{ filters: ReturnType<typeof normalizePlanFilters>, changed: boolean }}
 */
export function filtersToRevealAssignee(filters, assignment, ctx) {
    const current = normalizePlanFilters(filters);
    // Tasks are person-scoped; ignore legacy team assignment type.
    const assigneeType = 'person';
    const rawId = assignment.assigneeId;
    const assigneeId = rawId === null || rawId === undefined || String(rawId).trim() === ''
        ? null
        : String(rawId);

    const probe = {
        completed: false,
        status: 'open',
        priority: 'normal',
        label: '',
        assigneeType: assigneeId ? assigneeType : null,
        assigneeId,
    };

    if (itemMatchesFilters(probe, current, ctx)) {
        return { filters: current, changed: false };
    }

    // Drop assignee-scope filters that hide other people's work.
    const next = normalizePlanFilters({
        ...current,
        myTasks: false,
        unassigned: false,
        personId: '',
        teamId: '',
    });

    if (itemMatchesFilters(probe, next, ctx)) {
        return { filters: next, changed: true };
    }

    if (assigneeId) {
        return {
            filters: normalizePlanFilters({ ...next, personId: assigneeId }),
            changed: true,
        };
    }

    return { filters: next, changed: true };
}
