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
                || normalized.priority;
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
        || normalized.priority,
    );
}
