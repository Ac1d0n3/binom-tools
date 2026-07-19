/** @typedef {'under' | 'on_track' | 'over' | null} TimeVariance */

/** Preset durations in minutes (15m … 8h). */
export const TIME_PRESETS = Object.freeze([15, 30, 60, 120, 240, 480]);

/**
 * @param {unknown} value
 * @returns {number|null}
 */
export function normalizeMinutes(value) {
    if (value === null || value === undefined || value === '') {
        return null;
    }
    const n = typeof value === 'number' ? value : Number(String(value).trim());
    if (!Number.isFinite(n) || n < 0) {
        return null;
    }
    return Math.round(n);
}

/**
 * Override wins when the key is present (including explicit null/0).
 *
 * @param {Record<string, unknown>|null|undefined} override
 * @param {Record<string, unknown>|null|undefined} templateItem
 * @returns {number|null}
 */
export function resolvePlannedMinutes(override, templateItem) {
    if (override && Object.prototype.hasOwnProperty.call(override, 'plannedMinutes')) {
        return normalizeMinutes(override.plannedMinutes);
    }
    return normalizeMinutes(
        templateItem?.plannedMinutes ?? templateItem?.planned_minutes ?? null,
    );
}

/**
 * Actual time is plan-only (never from template).
 *
 * @param {Record<string, unknown>|null|undefined} source
 * @returns {number|null}
 */
export function resolveActualMinutes(source) {
    return normalizeMinutes(source?.actualMinutes ?? null);
}

/**
 * @param {number|null|undefined} planned
 * @param {number|null|undefined} actual
 * @returns {TimeVariance}
 */
export function timeVariance(planned, actual) {
    const p = normalizeMinutes(planned);
    const a = normalizeMinutes(actual);
    if (p === null || p <= 0 || a === null || a <= 0) {
        return null;
    }
    if (a > p) {
        return 'over';
    }
    if (a < p) {
        return 'under';
    }
    return 'on_track';
}

/**
 * Compact display: 15 → "15m", 60 → "1h", 90 → "1.5h".
 *
 * @param {number|null|undefined} minutes
 * @returns {string}
 */
export function formatMinutesShort(minutes) {
    const m = normalizeMinutes(minutes);
    if (m === null) {
        return '—';
    }
    if (m < 60) {
        return `${m}m`;
    }
    const hours = m / 60;
    if (Number.isInteger(hours)) {
        return `${hours}h`;
    }
    const rounded = Math.round(hours * 10) / 10;
    return `${rounded}h`;
}

/**
 * @param {number|null|undefined} minutes
 * @param {(key: string, vars?: Record<string, string|number>) => string} spT
 * @returns {string}
 */
export function formatMinutesLabel(minutes, spT) {
    const m = normalizeMinutes(minutes);
    if (m === null) {
        return spT('sp.time.none');
    }
    if (m < 60) {
        return spT('sp.time.minutes', { count: m });
    }
    const hours = m / 60;
    if (Number.isInteger(hours)) {
        return spT('sp.time.hours', { count: hours });
    }
    const rounded = Math.round(hours * 10) / 10;
    return spT('sp.time.hoursDecimal', { count: rounded });
}

/**
 * Build `<option>` values for preset + custom select.
 *
 * @param {number|null|undefined} current
 * @returns {{ value: string, minutes: number|null, custom: boolean }[]}
 */
export function timeSelectOptions(current) {
    const cur = normalizeMinutes(current);
    const options = TIME_PRESETS.map((minutes) => ({
        value: String(minutes),
        minutes,
        custom: false,
    }));
    options.unshift({ value: '', minutes: null, custom: false });
    if (cur !== null && !TIME_PRESETS.includes(cur)) {
        options.push({ value: String(cur), minutes: cur, custom: true });
    }
    options.push({ value: '__custom__', minutes: null, custom: true });
    return options;
}

/**
 * Sum planned/actual and count over/under across resolved sprints.
 *
 * @param {Array<{ tasks?: object[], deliverables?: object[] }>} sprints
 * @returns {{ planned: number, actual: number, over: number, under: number, onTrack: number, rows: object[] }}
 */
export function collectTimeRows(sprints) {
    /** @type {object[]} */
    const rows = [];
    let planned = 0;
    let actual = 0;
    let over = 0;
    let under = 0;
    let onTrack = 0;

    for (const sprint of sprints || []) {
        for (const kind of /** @type {const} */ (['tasks', 'deliverables'])) {
            for (const item of sprint[kind] || []) {
                const p = normalizeMinutes(item.plannedMinutes);
                const a = normalizeMinutes(item.actualMinutes);
                const variance = timeVariance(p, a);
                if (p !== null) {
                    planned += p;
                }
                if (a !== null) {
                    actual += a;
                }
                if (variance === 'over') {
                    over += 1;
                } else if (variance === 'under') {
                    under += 1;
                } else if (variance === 'on_track') {
                    onTrack += 1;
                }
                if (p !== null || a !== null) {
                    rows.push({
                        sprintNumber: sprint.number,
                        sprintTitle: sprint.title || '',
                        item,
                        planned: p,
                        actual: a,
                        variance,
                        delta: p !== null && a !== null ? a - p : null,
                    });
                }
            }
        }
    }

    return {
        planned, actual, over, under, onTrack, rows,
    };
}

/**
 * Find the most recent actualMinutes for the same template task across other plans.
 *
 * @param {object} workspace
 * @param {{ templateSlug: string, itemId: string, excludeInstanceId: string }} opts
 * @returns {number|null}
 */
export function findLastActualMinutes(workspace, opts) {
    const { templateSlug, itemId, excludeInstanceId } = opts;
    if (!templateSlug || !itemId || !workspace?.instances) {
        return null;
    }
    /** @type {{ at: string, minutes: number }[]} */
    const hits = [];
    for (const [id, plan] of Object.entries(workspace.instances)) {
        if (id === excludeInstanceId) {
            continue;
        }
        if (String(plan?.templateSlug || '') !== templateSlug) {
            continue;
        }
        const overrides = plan.itemOverrides || {};
        for (const [key, ov] of Object.entries(overrides)) {
            const minutes = resolveActualMinutes(/** @type {any} */ (ov));
            if (minutes === null || minutes <= 0) {
                continue;
            }
            const keyId = key.includes(':') ? key.slice(key.lastIndexOf(':') + 1) : key;
            if (keyId !== itemId && /** @type {any} */ (ov).id !== itemId) {
                continue;
            }
            hits.push({
                at: String(plan.updatedAt || plan.createdAt || ''),
                minutes,
            });
        }
        for (const bag of ['customTasks', 'customDeliverables']) {
            const bySprint = plan[bag] || {};
            for (const list of Object.values(bySprint)) {
                if (!Array.isArray(list)) {
                    continue;
                }
                for (const entry of list) {
                    if (String(entry?.id || '') !== itemId) {
                        continue;
                    }
                    const minutes = resolveActualMinutes(entry);
                    if (minutes === null || minutes <= 0) {
                        continue;
                    }
                    hits.push({
                        at: String(plan.updatedAt || plan.createdAt || ''),
                        minutes,
                    });
                }
            }
        }
    }
    if (!hits.length) {
        return null;
    }
    hits.sort((a, b) => String(b.at).localeCompare(String(a.at)));
    return hits[0].minutes;
}
