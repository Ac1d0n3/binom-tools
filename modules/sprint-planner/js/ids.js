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

export function createUserTemplateId() {
    return createLocalId('utpl_');
}

export function createPersonId() {
    return createLocalId('person_');
}

export function createTeamId() {
    return createLocalId('team_');
}

/**
 * @param {string|null|undefined} [dateIso] YYYY-MM-DD — defaults to today (UTC)
 */
export function createPlanId(dateIso = null) {
    const raw = typeof dateIso === 'string' && /^\d{4}-\d{2}-\d{2}$/.test(dateIso.trim())
        ? dateIso.trim()
        : new Date().toISOString().slice(0, 10);
    const day = raw.replace(/-/g, '');
    const suffix = typeof crypto !== 'undefined' && crypto.randomUUID
        ? crypto.randomUUID().replace(/-/g, '').slice(0, 6)
        : Math.random().toString(36).slice(2, 8);
    return `plan_${day}_${suffix}`;
}

/**
 * Extract YYYY-MM-DD from plan ids like plan_20260817_acid1.
 * @param {string|null|undefined} planId
 * @returns {string} empty when not encoded
 */
export function inferStartedAtFromPlanId(planId) {
    const match = String(planId || '').match(/^plan_(\d{4})(\d{2})(\d{2})(?:_|$)/);
    if (!match) {
        return '';
    }
    const iso = `${match[1]}-${match[2]}-${match[3]}`;
    const probe = new Date(`${iso}T00:00:00Z`);
    if (Number.isNaN(probe.getTime()) || probe.toISOString().slice(0, 10) !== iso) {
        return '';
    }
    return iso;
}

/**
 * Prefer stored startedAt; if missing (or recover filled "today" onto a dated id), use the id date.
 * @param {string|null|undefined} planId
 * @param {string|null|undefined} startedAt
 * @param {Date} [today]
 * @returns {string}
 */
export function resolvePlanStartedAt(planId, startedAt, today = new Date()) {
    const stored = String(startedAt || '').trim();
    const fromId = inferStartedAtFromPlanId(planId);
    const todayIso = today.toISOString().slice(0, 10);
    if (!stored) {
        return fromId || todayIso;
    }
    // Recover/heal used to write "today" when startedAt was missing — undo that when the id encodes another day.
    if (fromId && stored === todayIso && fromId !== todayIso) {
        return fromId;
    }
    return stored;
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
