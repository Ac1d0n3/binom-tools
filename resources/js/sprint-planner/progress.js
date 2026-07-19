import { statusKey } from './ids.js';
import { normalizeAttachments } from './attachments.js';
import {
    applySprintDependencyBlocks,
    collectSprintItemKeys,
    pickRawDependsOn,
    resolveDependsOnKeys,
} from './dependencies.js';
import { isAllowedHelpHref } from './external-links.js';
import { mergeItemTable, normalizeItemTable } from './item-table.js';
import { t } from './labels.js';
import { resolveActualMinutes, resolvePlannedMinutes } from './time.js';

/**
 * @typedef {Object} SpHelpLink
 * @property {string} label
 * @property {string} href
 */

/**
 * @typedef {Object} SpStoryRef
 * @property {string} slug
 * @property {boolean} required
 */

/**
 * @typedef {Object} SpFlow
 * @property {'linear'|'chevron'} variant
 * @property {'horizontal'|'vertical'} layout
 * @property {string[]} steps
 */

/**
 * @typedef {Object} ResolvedItem
 * @property {string} id
 * @property {string} statusKey
 * @property {'task'|'deliverable'} kind
 * @property {string} label
 * @property {boolean} completed
 * @property {string} status
 * @property {string} priority
 * @property {string|null} assigneeType
 * @property {string|null} assigneeId
 * @property {string|null} dueDate
 * @property {string} note
 * @property {boolean} custom
 * @property {string} sprintId
 * @property {string[]} linkedStorySlugs
 * @property {SpStoryRef[]} stories
 * @property {string|null} helpText
 * @property {SpHelpLink[]} helpLinks
 * @property {string|null} demoCode
 * @property {string} blockerReason
 * @property {string|null} blockerSince
 * @property {string[]} dependsOn
 * @property {boolean} dependencyBlocked
 * @property {string} dependencyReason
 * @property {string} storedStatus
 * @property {import('./attachments.js').SpAttachment[]} attachments
 */

/**
 * @param {Record<string, unknown>} template
 * @param {import('./storage.js').SpPlanInstance} instance
 * @param {'de'|'en'} locale
 */
export function resolveSprints(template, instance, locale) {
    const localePack = template?.locales?.[locale] || template?.locales?.en || template?.locales?.de || {};
    const localeSprints = Array.isArray(localePack.sprints) ? localePack.sprints : [];
    const localeById = Object.fromEntries(localeSprints.map((s) => [s.id, s]));
    const structural = Array.isArray(template?.sprints) ? template.sprints : [];

    const templateSprints = structural.map((sprint) => {
        const texts = localeById[sprint.id] || {};
        const override = instance.sprintOverrides?.[sprint.id] || {};
        return mergeSprint(sprint, texts, override, instance, template.slug, locale, false);
    });

    const customSprints = (instance.customSprints || []).map((sprint) => {
        const texts = pickLocalized(sprint, locale);
        return mergeSprint(
            {
                id: sprint.id,
                number: sprint.number,
                notes: sprint.notes !== false,
                stories: Array.isArray(sprint.stories) ? sprint.stories : [],
                linkedStorySlugs: Array.isArray(sprint.linkedStorySlugs) ? sprint.linkedStorySlugs : [],
                links: Array.isArray(sprint.links) ? sprint.links : [],
                flowVariant: sprint.flowVariant || 'linear',
                flowLayout: sprint.flowLayout || 'vertical',
                flowSteps: Array.isArray(sprint.flowSteps) ? sprint.flowSteps : [],
                tasks: [],
                deliverables: [],
                fields: [],
            },
            {
                ...texts,
                links: Array.isArray(sprint.links) ? sprint.links : [],
            },
            {},
            instance,
            template.slug,
            locale,
            true,
        );
    });

    return [...templateSprints, ...customSprints].sort((a, b) => a.number - b.number);
}

function pickLocalized(sprint, locale) {
    const title = localizeField(sprint.title, locale) || '';
    const goal = localizeField(sprint.goal, locale) || '';
    const description = localizeField(sprint.description, locale) || null;
    return { title, goal, description, tasks: [], deliverables: [], fields: [] };
}

/**
 * @param {unknown} value
 * @param {string} locale
 * @returns {string}
 */
function localizeField(value, locale) {
    if (typeof value === 'string') {
        return value;
    }
    if (value && typeof value === 'object') {
        const pack = /** @type {Record<string, string>} */ (value);
        return String(pack[locale] || pack.en || pack.de || '');
    }
    return '';
}

function mergeSprint(sprint, texts, override, instance, templateSlug, locale, custom) {
    const title = override.title?.[locale]
        || override.title?.en
        || texts.title
        || localizeField(sprint.title, locale)
        || '';
    const goal = override.goal?.[locale]
        || override.goal?.en
        || texts.goal
        || localizeField(sprint.goal, locale)
        || '';
    const description = override.description?.[locale]
        || texts.description
        || localizeField(sprint.description, locale)
        || null;
    const notesEnabled = override.notes ?? sprint.notes ?? true;

    const allowedKeys = collectSprintItemKeys({
        templateSlug,
        sprintId: sprint.id,
        templateTasks: sprint.tasks || [],
        templateDeliverables: sprint.deliverables || [],
        customTasks: instance.customTasks?.[sprint.id] || [],
        customDeliverables: instance.customDeliverables?.[sprint.id] || [],
        removedItemKeys: instance.removedItemKeys || [],
    });

    /** @type {ResolvedItem[]} */
    const tasks = [];
    const removedKeys = new Set(instance.removedItemKeys || []);
    const textTasks = Object.fromEntries((texts.tasks || []).map((entry) => [entry.id, entry]));
    for (const task of sprint.tasks || []) {
        const key = statusKey(templateSlug, sprint.id, 'task', task.id);
        if (removedKeys.has(key)) {
            continue;
        }
        const itemOverride = instance.itemOverrides?.[key] || {};
        const textTask = textTasks[task.id] || {};
        tasks.push(resolveTemplateItem({
            item: task,
            textItem: textTask,
            itemOverride,
            kind: 'task',
            key,
            sprintId: sprint.id,
            completedList: instance.completedTasks,
            locale,
            templateSlug,
            allowedKeys,
        }));
    }

    for (const task of instance.customTasks?.[sprint.id] || []) {
        const key = task.statusKey || statusKey(templateSlug, sprint.id, 'task', task.id);
        tasks.push(resolveCustomItem(
            task,
            key,
            'task',
            sprint.id,
            instance.completedTasks,
            locale,
            templateSlug,
            allowedKeys,
        ));
    }

    /** @type {ResolvedItem[]} */
    const deliverables = [];
    const textDels = Object.fromEntries((texts.deliverables || []).map((entry) => [entry.id, entry]));
    for (const del of sprint.deliverables || []) {
        const key = statusKey(templateSlug, sprint.id, 'deliverable', del.id);
        if (removedKeys.has(key)) {
            continue;
        }
        const itemOverride = instance.itemOverrides?.[key] || {};
        const textDel = textDels[del.id] || {};
        deliverables.push(resolveTemplateItem({
            item: del,
            textItem: textDel,
            itemOverride,
            kind: 'deliverable',
            key,
            sprintId: sprint.id,
            completedList: instance.completedDeliverables,
            locale,
            templateSlug,
            allowedKeys,
            defaultAssigneeType: null,
        }));
    }

    for (const del of instance.customDeliverables?.[sprint.id] || []) {
        const key = del.statusKey || statusKey(templateSlug, sprint.id, 'deliverable', del.id);
        deliverables.push(resolveCustomItem(
            del,
            key,
            'deliverable',
            sprint.id,
            instance.completedDeliverables,
            locale,
            templateSlug,
            allowedKeys,
        ));
    }

    applySprintDependencyBlocks([...tasks, ...deliverables], (labels) => (
        t('sp.deps.waitingOn', locale, { labels: labels.join(', ') })
    ));

    const textFields = Object.fromEntries((texts.fields || []).map((f) => [f.id, f]));
    const fields = (sprint.fields || []).map((field) => {
        const key = statusKey(templateSlug, sprint.id, 'field', field.id);
        return {
            id: field.id,
            statusKey: key,
            type: field.type || 'text',
            label: textFields[field.id]?.label || field.id,
            placeholder: textFields[field.id]?.placeholder || null,
            value: instance.fieldValues?.[key] ?? '',
        };
    });

    const done = tasks.filter((t) => t.completed).length + deliverables.filter((d) => d.completed).length;
    const total = tasks.length + deliverables.length;
    const percent = total === 0 ? 0 : Math.round((done / total) * 100);

    const stories = normalizeStories(
        Array.isArray(override.stories) ? override.stories : (sprint.stories || sprint.linkedStorySlugs || sprint.linkedStories),
    );
    const linkedStorySlugs = stories.map((s) => s.slug);

    const templateLinks = mergeSprintLinks(texts.links || sprint.links, locale);
    const overrideLinks = Array.isArray(override.links)
        ? normalizeHelpLinks(override.links, locale)
        : null;

    /** @type {SpFlow|null} */
    let flow = null;
    const flowSteps = Array.isArray(override.flowSteps)
        ? override.flowSteps.map(String).filter(Boolean)
        : (Array.isArray(sprint.flowSteps) ? sprint.flowSteps.map(String).filter(Boolean) : []);
    if (flowSteps.length >= 2) {
        flow = {
            variant: (override.flowVariant || sprint.flowVariant || 'linear') === 'chevron' ? 'chevron' : 'linear',
            layout: (override.flowLayout || sprint.flowLayout || 'vertical') === 'horizontal' ? 'horizontal' : 'vertical',
            steps: flowSteps,
        };
    }

    return {
        id: sprint.id,
        number: Number(override.number ?? sprint.number ?? 0),
        title,
        goal,
        description,
        notesEnabled,
        note: instance.sprintNotes?.[sprint.id] || '',
        custom,
        stories,
        linkedStorySlugs,
        links: overrideLinks !== null ? overrideLinks : templateLinks,
        flow,
        tasks,
        deliverables,
        fields,
        progress: { done, total, percent },
        completed: total > 0 && done === total,
    };
}

function resolveTemplateItem({
    item,
    textItem,
    itemOverride,
    kind,
    key,
    sprintId,
    completedList,
    locale,
    templateSlug,
    allowedKeys,
    defaultAssigneeType = 'person',
}) {
    const completed = completedList.includes(key) || itemOverride.status === 'completed';
    const status = itemOverride.status || (completed ? 'completed' : 'open');
    const stories = normalizeStories(
        item.stories || item.linkedStorySlugs || item.linkedStories,
    );
    const assigneeType = Object.prototype.hasOwnProperty.call(itemOverride, 'assigneeType')
        ? itemOverride.assigneeType
        : (item.assigneeType ?? defaultAssigneeType);
    const rawAssigneeId = Object.prototype.hasOwnProperty.call(itemOverride, 'assigneeId')
        ? itemOverride.assigneeId
        : (item.assigneeId ?? null);
    const assigneeId = rawAssigneeId === null || rawAssigneeId === undefined
        || String(rawAssigneeId).trim() === ''
        || String(rawAssigneeId) === 'null'
        ? null
        : String(rawAssigneeId);
    const dependsOn = resolveDependsOnKeys(pickRawDependsOn(itemOverride, item), {
        templateSlug,
        sprintId,
        allowedKeys,
        selfKey: key,
    });
    return {
        id: item.id,
        statusKey: key,
        kind,
        label: textItem.label
            || localizeField(item.label, locale)
            || item.id,
        completed,
        status,
        storedStatus: status,
        priority: itemOverride.priority || 'normal',
        assigneeType: assigneeId ? (assigneeType || 'person') : (assigneeType || null),
        assigneeId,
        dueDate: itemOverride.dueDate || null,
        note: itemOverride.note || '',
        plannedMinutes: resolvePlannedMinutes(itemOverride, item),
        actualMinutes: resolveActualMinutes(itemOverride),
        custom: false,
        sprintId,
        stories,
        linkedStorySlugs: stories.map((s) => s.slug),
        // Help content always comes from the template locale pack — never from plan overrides.
        helpText: pickHelpText(textItem, locale)
            || pickLocalizedHelpText(item.helpText, locale),
        helpLinks: mergeHelpLinks(textItem.helpLinks || item.helpLinks, locale),
        demoCode: pickDemoCode(textItem, locale),
        blockerReason: status === 'blocked' ? String(itemOverride.blockerReason || '') : '',
        blockerSince: status === 'blocked' ? (itemOverride.blockerSince || null) : null,
        dependsOn,
        dependencyBlocked: false,
        dependencyReason: '',
        attachments: normalizeAttachments(itemOverride.attachments),
        table: mergeItemTable(item.table, itemOverride.table),
    };
}

function resolveCustomItem(item, key, kind, sprintId, completedList, locale, templateSlug, allowedKeys) {
    const completed = completedList.includes(key) || item.status === 'completed';
    const status = item.status || (completed ? 'completed' : 'open');
    const stories = normalizeStories(item.stories || item.linkedStorySlugs);
    const dependsOn = resolveDependsOnKeys(item.dependsOn, {
        templateSlug,
        sprintId,
        allowedKeys,
        selfKey: key,
    });
    return {
        id: item.id,
        statusKey: key,
        kind,
        label: item.label?.[locale] || item.label?.en || item.label?.de || item.label || item.id,
        completed,
        status,
        storedStatus: status,
        priority: item.priority || 'normal',
        assigneeType: item.assigneeType || (kind === 'task' ? 'person' : null),
        assigneeId: item.assigneeId || null,
        dueDate: item.dueDate || null,
        note: item.note || '',
        plannedMinutes: resolvePlannedMinutes(item, null),
        actualMinutes: resolveActualMinutes(item),
        custom: true,
        sprintId,
        stories,
        linkedStorySlugs: stories.map((s) => s.slug),
        helpText: pickLocalizedHelpText(item.helpText, locale),
        helpLinks: normalizeHelpLinks(item.helpLinks, locale),
        demoCode: pickLocalizedHelpText(item.demoCode, locale),
        blockerReason: status === 'blocked' ? String(item.blockerReason || '') : '',
        blockerSince: status === 'blocked' ? (item.blockerSince || null) : null,
        dependsOn,
        dependencyBlocked: false,
        dependencyReason: '',
        attachments: normalizeAttachments(item.attachments),
        table: normalizeItemTable(item.table),
    };
}

/**
 * @param {unknown} value
 * @returns {SpStoryRef[]}
 */
export function normalizeStories(value) {
    if (!Array.isArray(value)) {
        return [];
    }
    /** @type {SpStoryRef[]} */
    const out = [];
    const seen = new Set();
    for (const entry of value) {
        if (typeof entry === 'string') {
            const slug = entry.trim();
            if (!slug || seen.has(slug)) {
                continue;
            }
            seen.add(slug);
            out.push({ slug, required: true });
            continue;
        }
        if (!entry || typeof entry !== 'object') {
            continue;
        }
        const slug = String(/** @type {any} */ (entry).slug || '').trim();
        if (!slug || seen.has(slug)) {
            continue;
        }
        seen.add(slug);
        out.push({
            slug,
            required: /** @type {any} */ (entry).required !== false,
        });
    }
    return out;
}

/**
 * @param {ResolvedItem} item
 */
export function itemHasHelp(item) {
    return Boolean(
        (item.helpText && String(item.helpText).trim())
        || (Array.isArray(item.helpLinks) && item.helpLinks.length > 0)
        || (Array.isArray(item.stories) && item.stories.length > 0)
        || (Array.isArray(item.linkedStorySlugs) && item.linkedStorySlugs.length > 0)
        || (item.demoCode && String(item.demoCode).trim()),
    );
}

/**
 * @param {SpStoryRef[]} stories
 * @param {(slug: string) => boolean} isRead
 */
export function requiredStoryProgress(stories, isRead) {
    const required = (stories || []).filter((s) => s.required);
    const done = required.filter((s) => isRead(s.slug)).length;
    return { done, total: required.length };
}

/**
 * Collect open blockers across resolved sprints.
 * @param {ReturnType<typeof resolveSprints>} sprints
 */
export function collectBlockers(sprints) {
    return collectItemsByStatus(sprints, 'blocked');
}

/**
 * Collect items with a given status across resolved sprints.
 * @param {ReturnType<typeof resolveSprints>} sprints
 * @param {string} status
 * @returns {Array<{sprintId: string, sprintNumber: number, sprintTitle: string, item: ResolvedItem}>}
 */
export function collectItemsByStatus(sprints, status) {
    /** @type {Array<{sprintId: string, sprintNumber: number, sprintTitle: string, item: ResolvedItem}>} */
    const out = [];
    const want = String(status || '');
    for (const sprint of sprints) {
        for (const item of [...sprint.tasks, ...sprint.deliverables]) {
            if (String(item.status || 'open') === want) {
                out.push({
                    sprintId: sprint.id,
                    sprintNumber: sprint.number,
                    sprintTitle: sprint.title,
                    item,
                });
            }
        }
    }
    return out;
}

function mergeStorySlugs(base, override) {
    if (Array.isArray(override)) {
        return override.map(String).filter(Boolean);
    }
    if (Array.isArray(base)) {
        return base.map(String).filter(Boolean);
    }
    return [];
}

function pickHelpText(textTask, locale) {
    const fromText = textTask?.helpText;
    if (typeof fromText === 'string' && fromText.trim()) {
        return fromText.trim();
    }
    return pickLocalizedHelpText(fromText, locale);
}

function pickDemoCode(textTask, locale) {
    const fromText = textTask?.demoCode;
    if (typeof fromText === 'string' && fromText.trim()) {
        return fromText.trim();
    }
    return pickLocalizedHelpText(fromText, locale);
}

function pickLocalizedHelpText(value, locale) {
    if (typeof value === 'string' && value.trim()) {
        return value.trim();
    }
    if (value && typeof value === 'object') {
        const pack = /** @type {Record<string, string>} */ (value);
        const picked = pack[locale] || pack.en || pack.de || '';
        return typeof picked === 'string' && picked.trim() ? picked.trim() : null;
    }
    return null;
}

function mergeHelpLinks(baseLinks, locale) {
    return normalizeHelpLinks(baseLinks, locale);
}

function normalizeHelpLinks(links, locale) {
    if (!Array.isArray(links)) {
        return [];
    }
    /** @type {SpHelpLink[]} */
    const out = [];
    for (const link of links) {
        if (!link || typeof link !== 'object') {
            continue;
        }
        const href = String(/** @type {any} */ (link).href || '').trim();
        // External help URLs or in-app /tools/ — not playbook paths or bare slugs.
        if (!isAllowedHelpHref(href)) {
            continue;
        }
        let label = /** @type {any} */ (link).label;
        if (label && typeof label === 'object') {
            label = label[locale] || label.en || label.de || '';
        }
        out.push({
            label: String(label || href),
            href,
        });
    }
    return out;
}

function mergeSprintLinks(links, locale) {
    return normalizeHelpLinks(links, locale);
}

/**
 * @param {ReturnType<typeof resolveSprints>} sprints
 */
export function calculateOverallProgress(sprints) {
    let done = 0;
    let total = 0;
    for (const sprint of sprints) {
        done += sprint.progress.done;
        total += sprint.progress.total;
    }
    return {
        done,
        total,
        percent: total === 0 ? 0 : Math.round((done / total) * 100),
    };
}

/**
 * @param {string} startedAt ISO date YYYY-MM-DD
 * @param {number} sprintCount
 * @param {string} [unit]
 * @param {Date} [today]
 * @returns {number} 1-based sprint number, or 0 if the plan has not started yet
 */
export function calculateCurrentSprintNumber(startedAt, sprintCount, unit = 'week', today = new Date()) {
    if (!startedAt || sprintCount < 1) {
        return 1;
    }
    const start = parseDateOnly(startedAt);
    if (!start) {
        return 1;
    }
    const now = new Date(today.getFullYear(), today.getMonth(), today.getDate());
    if (now < start) {
        // Plan has not started — no "current" plan week yet.
        return 0;
    }
    const msPerDay = 24 * 60 * 60 * 1000;
    const days = Math.floor((now.getTime() - start.getTime()) / msPerDay);
    const length = unit === 'week' ? 7 : 7;
    const number = Math.floor(days / length) + 1;
    return Math.min(Math.max(number, 1), sprintCount);
}

/**
 * @param {string} startedAt
 * @param {Date} [today]
 */
export function isPlanStarted(startedAt, today = new Date()) {
    const start = parseDateOnly(startedAt);
    if (!start) {
        return true;
    }
    const now = new Date(today.getFullYear(), today.getMonth(), today.getDate());
    return now >= start;
}

/**
 * Format a Date as locale short day (e.g. 23.08.2026 / 23 Aug 2026).
 * @param {Date} date
 * @param {'de'|'en'} locale
 */
export function formatDateShort(date, locale = 'en') {
    if (!date) {
        return '';
    }
    const loc = locale === 'de' ? 'de-DE' : 'en-GB';
    return date.toLocaleDateString(loc, { day: '2-digit', month: '2-digit', year: 'numeric' });
}

/**
 * Planned calendar range for sprint number N (1-based) from plan start.
 * @param {string} startedAt
 * @param {number} sprintNumber
 * @param {string} [unit]
 * @returns {{start: Date, end: Date, isoWeek: number, isoYear: number}|null}
 */
export function sprintDateRange(startedAt, sprintNumber, unit = 'week') {
    const start = parseDateOnly(startedAt);
    if (!start || !sprintNumber || sprintNumber < 1) {
        return null;
    }
    const length = unit === 'week' ? 7 : 7;
    const rangeStart = new Date(start);
    rangeStart.setDate(rangeStart.getDate() + (sprintNumber - 1) * length);
    const rangeEnd = new Date(rangeStart);
    rangeEnd.setDate(rangeEnd.getDate() + length - 1);
    const iso = isoWeekYear(rangeStart);
    return {
        start: rangeStart,
        end: rangeEnd,
        isoWeek: iso.week,
        isoYear: iso.year,
    };
}

/**
 * ISO week number (1–53) and ISO week-year for a date.
 * @param {Date} date
 * @returns {{week: number, year: number}}
 */
export function isoWeekYear(date) {
    const d = new Date(Date.UTC(date.getFullYear(), date.getMonth(), date.getDate()));
    const dayNum = d.getUTCDay() || 7;
    d.setUTCDate(d.getUTCDate() + 4 - dayNum);
    const yearStart = new Date(Date.UTC(d.getUTCFullYear(), 0, 1));
    const week = Math.ceil((((d.getTime() - yearStart.getTime()) / 86400000) + 1) / 7);
    return { week, year: d.getUTCFullYear() };
}

/**
 * Format ISO week label for locale.
 * @param {number} week
 * @param {'de'|'en'} locale
 */
export function formatIsoWeekLabel(week, locale = 'en') {
    if (!week) {
        return '';
    }
    return locale === 'de' ? `KW ${week}` : `W${week}`;
}

/**
 * Short date range label, e.g. "6.–12. Jul".
 * @param {Date} start
 * @param {Date} end
 * @param {'de'|'en'} locale
 */
export function formatDateRangeShort(start, end, locale = 'en') {
    if (!start || !end) {
        return '';
    }
    const loc = locale === 'de' ? 'de-DE' : 'en-GB';
    const sameMonth = start.getMonth() === end.getMonth() && start.getFullYear() === end.getFullYear();
    if (sameMonth) {
        const dayStart = start.getDate();
        const dayEnd = end.getDate();
        const month = start.toLocaleDateString(loc, { month: 'short' });
        return locale === 'de'
            ? `${dayStart}.–${dayEnd}. ${month}`
            : `${dayStart}–${dayEnd} ${month}`;
    }
    const a = start.toLocaleDateString(loc, { day: 'numeric', month: 'short' });
    const b = end.toLocaleDateString(loc, { day: 'numeric', month: 'short' });
    return `${a} – ${b}`;
}

/**
 * Schedule health: open items in sprints before the current plan week.
 * @param {Array<{number: number, title?: string, tasks?: Array<{completed?: boolean, status?: string}>, deliverables?: Array<{completed?: boolean, status?: string}>}>} sprints
 * @param {number} currentSprintNumber
 * @returns {{weeksBehind: number, openCount: number, onTrack: boolean, sources: Array<{sprintNumber: number, title: string, openCount: number}>}}
 */
export function computeScheduleHealth(sprints, currentSprintNumber) {
    /** @type {Array<{sprintNumber: number, title: string, openCount: number}>} */
    const sources = [];
    let openCount = 0;
    let earliestBehind = null;
    for (const sprint of sprints || []) {
        const num = Number(sprint.number) || 0;
        if (num >= currentSprintNumber || num < 1) {
            continue;
        }
        const items = [...(sprint.tasks || []), ...(sprint.deliverables || [])];
        const open = items.filter((item) => !item.completed && item.status !== 'completed').length;
        if (open > 0) {
            openCount += open;
            sources.push({
                sprintNumber: num,
                title: sprint.title || `#${num}`,
                openCount: open,
            });
            if (earliestBehind === null || num < earliestBehind) {
                earliestBehind = num;
            }
        }
    }
    const weeksBehind = earliestBehind === null
        ? 0
        : Math.max(0, currentSprintNumber - earliestBehind);
    return {
        weeksBehind,
        openCount,
        onTrack: openCount === 0,
        sources,
    };
}

/**
 * @param {string} value
 * @returns {Date|null}
 */
function parseDateOnly(value) {
    const match = /^(\d{4})-(\d{2})-(\d{2})$/.exec(value);
    if (!match) {
        return null;
    }
    return new Date(Number(match[1]), Number(match[2]) - 1, Number(match[3]));
}
