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
 * @param {Array<{ tasks?: object[], deliverables?: object[] }>} sprints
 * @returns {number}
 */
export function sumPlannedMinutes(sprints) {
    let planned = 0;
    for (const sprint of sprints || []) {
        for (const kind of /** @type {const} */ (['tasks', 'deliverables'])) {
            for (const item of sprint[kind] || []) {
                const minutes = normalizeMinutes(item.plannedMinutes);
                if (minutes !== null) {
                    planned += minutes;
                }
            }
        }
    }

    return planned;
}

/**
 * @param {number} hours
 * @returns {string}
 */
export function formatHoursShort(hours) {
    if (!Number.isFinite(hours)) {
        return '—';
    }
    if (Number.isInteger(hours)) {
        return String(hours);
    }
    return String(Math.round(hours * 10) / 10);
}

/**
 * @param {{ recommendedPeopleMin?: number|null, recommendedPeopleMax?: number|null, capacityHoursPerPersonWeek?: number|null }} source
 * @returns {{ min: number|null, max: number|null, capacity: number }}
 */
export function normalizeTeamCapacity(source) {
    const min = normalizeMinutes(source?.recommendedPeopleMin);
    const max = normalizeMinutes(source?.recommendedPeopleMax);
    const capacity = normalizeMinutes(source?.capacityHoursPerPersonWeek) || 40;
    if (min === null && max === null) {
        return { min: null, max: null, capacity };
    }
    const safeMin = Math.max(1, min ?? max ?? 1);
    const safeMax = Math.max(safeMin, max ?? safeMin);

    return { min: safeMin, max: safeMax, capacity };
}

/**
 * @param {Array<{ tasks?: object[], deliverables?: object[] }>} sprints
 * @param {{ recommendedPeopleMin?: number|null, recommendedPeopleMax?: number|null, capacityHoursPerPersonWeek?: number|null }} source
 * @param {(key: string, vars?: Record<string, string|number>) => string} spT
 * @returns {string[]}
 */
export function effortFacts(sprints, source, spT) {
    const plannedMinutes = sumPlannedMinutes(sprints);
    if (plannedMinutes <= 0) {
        return [];
    }
    const hours = plannedMinutes / 60;
    const facts = [
        spT('sp.card.personHours', { count: formatHoursShort(hours) }),
    ];
    const team = normalizeTeamCapacity(source);
    if (team.min !== null && team.max !== null) {
        const duration = normalizeMinutes(source?.duration);
        const basePeople = team.min;
        const baseCapacity = duration && duration > 0
            ? duration * basePeople * team.capacity
            : hours;
        const utilization = baseCapacity > 0 ? Math.min(1, hours / baseCapacity) : 1;
        const bufferPct = Math.max(0, Math.round((1 - utilization) * 1000) / 10);
        const weeksForPeople = (people) => {
            if (duration && duration > 0 && basePeople > 0) {
                return (duration * basePeople) / people;
            }
            return utilization > 0 ? hours / (people * team.capacity * utilization) : hours / (people * team.capacity);
        };

        facts.push(spT('sp.card.planBasis', {
            weeks: duration || formatHoursShort(weeksForPeople(basePeople)),
            people: basePeople,
            hours: team.capacity,
        }));
        if (bufferPct > 0) {
            facts.push(spT('sp.card.buffer', { percent: formatHoursShort(bufferPct) }));
        }
        if (team.max > basePeople) {
            facts.push(spT('sp.card.partTimeSplit', {
                people: team.max,
                percent: Math.round((basePeople / team.max) * 100),
            }));
            facts.push(spT('sp.card.fullTimeWeeks', {
                people: team.max,
                weeks: formatHoursShort(weeksForPeople(team.max)),
            }));
        } else {
            facts.push(spT('sp.card.recommendedPeopleOne', { count: basePeople }));
        }
        facts.push(spT('sp.card.fullTimeWeeks', {
            people: 3,
            weeks: formatHoursShort(weeksForPeople(3)),
        }));
    }

    return facts;
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
