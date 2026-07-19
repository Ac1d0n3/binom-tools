/**
 * Plan item filters — all active constraints are AND-combined.
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
 * }}
 */
export function normalizePlanFilters(raw = {}) {
    const source = raw && typeof raw === 'object' ? raw : {};

    // Migrate away from XOR assigneeFilter radios back to independent AND flags.
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
    };
}

/**
 * @param {object} item
 * @param {ReturnType<typeof normalizePlanFilters>} filters
 * @param {{activePersonId: string|null}} ctx
 */
export function itemMatchesFilters(item, filters, ctx) {
    const normalized = normalizePlanFilters(filters);

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

    // AND: each checked assignee constraint must hold.
    if (normalized.myTasks) {
        if (!ctx.activePersonId || item.assigneeType !== 'person' || item.assigneeId !== ctx.activePersonId) {
            return false;
        }
    }
    if (normalized.unassigned) {
        if (item.assigneeId) {
            return false;
        }
    }
    if (normalized.personId) {
        if (!(item.assigneeType === 'person' && item.assigneeId === normalized.personId)) {
            return false;
        }
    }
    if (normalized.teamId) {
        if (!(item.assigneeType === 'team' && item.assigneeId === normalized.teamId)) {
            return false;
        }
    }

    return true;
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
            if (normalized.currentWeek && sprint.number !== currentSprintNumber) {
                return false;
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
        || normalized.priority
        || normalized.currentWeek,
    );
}
