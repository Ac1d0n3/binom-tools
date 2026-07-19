/**
 * Stable local IDs for sprint planner entities.
 */

/**
 * @param {string} prefix
 * @returns {string}
 */
export function createLocalId(prefix) {
    const rand = typeof crypto !== 'undefined' && crypto.randomUUID
        ? crypto.randomUUID().replace(/-/g, '').slice(0, 12)
        : `${Date.now().toString(36)}${Math.random().toString(36).slice(2, 10)}`;
    return `${prefix}${rand}`;
}

export function createPersonId() {
    return createLocalId('person_');
}

export function createTeamId() {
    return createLocalId('team_');
}

export function createPlanId() {
    const day = new Date().toISOString().slice(0, 10).replace(/-/g, '');
    const suffix = typeof crypto !== 'undefined' && crypto.randomUUID
        ? crypto.randomUUID().replace(/-/g, '').slice(0, 6)
        : Math.random().toString(36).slice(2, 8);
    return `plan_${day}_${suffix}`;
}

export function createSprintId() {
    return createLocalId('sprint_');
}

export function createTaskId() {
    return createLocalId('task_');
}

export function createDeliverableId() {
    return createLocalId('deliverable_');
}

/**
 * Status key for template-backed items (stable across locales).
 * @param {string} templateSlug
 * @param {string} sprintId
 * @param {'task'|'deliverable'|'field'} itemType
 * @param {string} itemId
 */
export function statusKey(templateSlug, sprintId, itemType, itemId) {
    return `${templateSlug}:${sprintId}:${itemType}:${itemId}`;
}
