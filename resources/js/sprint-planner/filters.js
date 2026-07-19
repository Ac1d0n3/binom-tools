/**
 * @param {object} item
 * @param {object} filters
 * @param {{activePersonId: string|null}} ctx
 */
export function itemMatchesFilters(item, filters, ctx) {
    if (filters.hideDone && item.completed) {
        return false;
    }
    if (filters.openOnly && (item.completed || item.status === 'completed')) {
        return false;
    }
    if (filters.blocked && item.status !== 'blocked') {
        return false;
    }
    if (filters.status && item.status !== filters.status) {
        return false;
    }
    if (filters.priority && item.priority !== filters.priority) {
        return false;
    }
    if (filters.myTasks) {
        if (!ctx.activePersonId || item.assigneeType !== 'person' || item.assigneeId !== ctx.activePersonId) {
            return false;
        }
    }
    if (filters.personId) {
        if (!(item.assigneeType === 'person' && item.assigneeId === filters.personId)) {
            return false;
        }
    }
    if (filters.teamId) {
        if (!(item.assigneeType === 'team' && item.assigneeId === filters.teamId)) {
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
    const context = {
        activePersonId: ctx.activePersonId || null,
    };

    return sprints
        .filter((sprint) => {
            if (filters.currentWeek && sprint.number !== currentSprintNumber) {
                return false;
            }
            return true;
        })
        .map((sprint) => {
            const tasks = sprint.tasks.filter((item) => itemMatchesFilters(item, filters, context));
            const deliverables = sprint.deliverables.filter((item) => itemMatchesFilters(item, filters, context));
            const hasItemFilters = filters.hideDone
                || filters.openOnly
                || filters.blocked
                || filters.myTasks
                || filters.personId
                || filters.teamId
                || filters.status
                || filters.priority;
            if (hasItemFilters && tasks.length === 0 && deliverables.length === 0) {
                return null;
            }
            return { ...sprint, tasks, deliverables };
        })
        .filter(Boolean);
}
