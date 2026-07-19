/**
 * Plan item filters — structural filters always AND; assignee/blocked group uses AND or OR.
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
        && item.assigneeType === 'person'
        && String(item.assigneeId) === String(activePersonId),
    );
}

/**
 * @param {object} item
 * @param {ReturnType<typeof normalizePlanFilters>} filters
 * @param {{activePersonId: string|null}} ctx
 */
export function itemMatchesFilters(item, filters, ctx) {
    const normalized = normalizePlanFilters(filters);
    const activePersonId = ctx.activePersonId || null;

    // Structural filters — always AND.
    if (normalized.hideDone && item.completed) {
        return false;
    }
    if (normalized.openOnly && (item.completed || item.status === 'completed')) {
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

    /** @type {Array<() => boolean>} */
    const groupChecks = [];
    if (normalized.blocked) {
        groupChecks.push(() => item.status === 'blocked');
    }
    if (normalized.myTasks) {
        groupChecks.push(() => matchesMyTasks(item, activePersonId));
    }
    if (normalized.unassigned) {
        groupChecks.push(() => !item.assigneeId);
    }
    if (normalized.personId) {
        groupChecks.push(() => item.assigneeType === 'person' && String(item.assigneeId) === normalized.personId);
    }
    if (normalized.teamId) {
        groupChecks.push(() => item.assigneeType === 'team' && String(item.assigneeId) === normalized.teamId);
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
 * @param {{activePersonId: string|null}} ctx
 */
export function filterSprints(sprints, filters, currentSprintNumber, ctx) {
    const normalized = normalizePlanFilters(filters);
    const context = {
        activePersonId: ctx.activePersonId || null,
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
    const normalized = normalizePlanFilters(filters);
    return Boolean(
        normalized.hideDone
        || normalized.openOnly
        || normalized.blocked
        || normalized.myTasks
        || normalized.unassigned
        || normalized.personId
        || normalized.teamId
        || normalized.status
        || normalized.priority
        || normalized.search,
    );
}

/**
 * If current filters would hide a freshly assigned item, widen assignee filters
 * so the assignment stays visible (common with default myTasks+unassigned OR).
 *
 * @param {Record<string, unknown>} filters
 * @param {{assigneeType?: string|null, assigneeId?: string|null}} assignment
 * @param {{activePersonId: string|null}} ctx
 * @returns {{ filters: ReturnType<typeof normalizePlanFilters>, changed: boolean }}
 */
export function filtersToRevealAssignee(filters, assignment, ctx) {
    const current = normalizePlanFilters(filters);
    const assigneeType = assignment.assigneeType || 'person';
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

    // Drop assignee-scope filters that hide other people's / team work.
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

    // Still hidden (e.g. status/priority) — focus the person/team filter on the assignee.
    if (assigneeId && assigneeType === 'person') {
        return {
            filters: normalizePlanFilters({ ...next, personId: assigneeId }),
            changed: true,
        };
    }
    if (assigneeId && assigneeType === 'team') {
        return {
            filters: normalizePlanFilters({ ...next, teamId: assigneeId }),
            changed: true,
        };
    }

    return { filters: next, changed: true };
}
