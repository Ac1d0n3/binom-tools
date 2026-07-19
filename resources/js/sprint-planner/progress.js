import { statusKey } from './ids.js';

/**
 * @typedef {Object} SpHelpLink
 * @property {string} label
 * @property {string} href
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
 * @property {string|null} helpText
 * @property {SpHelpLink[]} helpLinks
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
                linkedStorySlugs: Array.isArray(sprint.linkedStorySlugs) ? sprint.linkedStorySlugs : [],
                links: Array.isArray(sprint.links) ? sprint.links : [],
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
    const title = sprint.title?.[locale] || sprint.title?.en || sprint.title?.de || sprint.title || '';
    const goal = sprint.goal?.[locale] || sprint.goal?.en || sprint.goal?.de || sprint.goal || '';
    const description = sprint.description?.[locale] || sprint.description?.en || sprint.description?.de || null;
    return { title, goal, description, tasks: [], deliverables: [], fields: [] };
}

function mergeSprint(sprint, texts, override, instance, templateSlug, locale, custom) {
    const title = override.title?.[locale] || override.title?.en || texts.title || sprint.title || '';
    const goal = override.goal?.[locale] || override.goal?.en || texts.goal || sprint.goal || '';
    const description = override.description?.[locale] || texts.description || null;
    const notesEnabled = override.notes ?? sprint.notes ?? true;

    /** @type {ResolvedItem[]} */
    const tasks = [];
    const textTasks = Object.fromEntries((texts.tasks || []).map((t) => [t.id, t]));
    for (const task of sprint.tasks || []) {
        const key = statusKey(templateSlug, sprint.id, 'task', task.id);
        const itemOverride = instance.itemOverrides?.[key] || {};
        const completed = instance.completedTasks.includes(key) || itemOverride.status === 'completed';
        const textTask = textTasks[task.id] || {};
        tasks.push({
            id: task.id,
            statusKey: key,
            kind: 'task',
            label: textTask.label || itemOverride.label?.[locale] || itemOverride.label?.en || task.id,
            completed,
            status: itemOverride.status || (completed ? 'completed' : 'open'),
            priority: itemOverride.priority || 'normal',
            assigneeType: itemOverride.assigneeType ?? task.assigneeType ?? 'person',
            assigneeId: itemOverride.assigneeId ?? task.assigneeId ?? null,
            dueDate: itemOverride.dueDate || null,
            note: itemOverride.note || '',
            custom: false,
            sprintId: sprint.id,
            linkedStorySlugs: mergeStorySlugs(
                task.linkedStorySlugs || task.linkedStories,
                itemOverride.linkedStorySlugs,
            ),
            helpText: pickHelpText(itemOverride, textTask, locale),
            helpLinks: mergeHelpLinks(
                textTask.helpLinks || task.helpLinks,
                itemOverride.helpLinks,
                locale,
            ),
        });
    }

    for (const task of instance.customTasks?.[sprint.id] || []) {
        const key = task.statusKey || statusKey(templateSlug, sprint.id, 'task', task.id);
        const completed = instance.completedTasks.includes(key) || task.status === 'completed';
        tasks.push({
            id: task.id,
            statusKey: key,
            kind: 'task',
            label: task.label?.[locale] || task.label?.en || task.label?.de || task.label || task.id,
            completed,
            status: task.status || (completed ? 'completed' : 'open'),
            priority: task.priority || 'normal',
            assigneeType: task.assigneeType || 'person',
            assigneeId: task.assigneeId || null,
            dueDate: task.dueDate || null,
            note: task.note || '',
            custom: true,
            sprintId: sprint.id,
            linkedStorySlugs: Array.isArray(task.linkedStorySlugs) ? task.linkedStorySlugs.map(String) : [],
            helpText: pickLocalizedHelpText(task.helpText, locale),
            helpLinks: normalizeHelpLinks(task.helpLinks, locale),
        });
    }

    /** @type {ResolvedItem[]} */
    const deliverables = [];
    const textDels = Object.fromEntries((texts.deliverables || []).map((d) => [d.id, d]));
    for (const del of sprint.deliverables || []) {
        const key = statusKey(templateSlug, sprint.id, 'deliverable', del.id);
        const itemOverride = instance.itemOverrides?.[key] || {};
        const completed = instance.completedDeliverables.includes(key) || itemOverride.status === 'completed';
        deliverables.push({
            id: del.id,
            statusKey: key,
            kind: 'deliverable',
            label: textDels[del.id]?.label || itemOverride.label?.[locale] || del.id,
            completed,
            status: itemOverride.status || (completed ? 'completed' : 'open'),
            priority: itemOverride.priority || 'normal',
            assigneeType: itemOverride.assigneeType || null,
            assigneeId: itemOverride.assigneeId || null,
            dueDate: itemOverride.dueDate || null,
            note: itemOverride.note || '',
            custom: false,
            sprintId: sprint.id,
            linkedStorySlugs: [],
            helpText: null,
            helpLinks: [],
        });
    }

    for (const del of instance.customDeliverables?.[sprint.id] || []) {
        const key = del.statusKey || statusKey(templateSlug, sprint.id, 'deliverable', del.id);
        const completed = instance.completedDeliverables.includes(key) || del.status === 'completed';
        deliverables.push({
            id: del.id,
            statusKey: key,
            kind: 'deliverable',
            label: del.label?.[locale] || del.label?.en || del.label?.de || del.label || del.id,
            completed,
            status: del.status || (completed ? 'completed' : 'open'),
            priority: del.priority || 'normal',
            assigneeType: del.assigneeType || null,
            assigneeId: del.assigneeId || null,
            dueDate: del.dueDate || null,
            note: del.note || '',
            custom: true,
            sprintId: sprint.id,
            linkedStorySlugs: Array.isArray(del.linkedStorySlugs) ? del.linkedStorySlugs.map(String) : [],
            helpText: pickLocalizedHelpText(del.helpText, locale),
            helpLinks: normalizeHelpLinks(del.helpLinks, locale),
        });
    }

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

    const templateStorySlugs = Array.isArray(sprint.linkedStories)
        ? sprint.linkedStories.map(String)
        : (Array.isArray(sprint.linkedStorySlugs) ? sprint.linkedStorySlugs.map(String) : []);
    const overrideStorySlugs = Array.isArray(override.linkedStorySlugs)
        ? override.linkedStorySlugs.map(String)
        : null;
    const linkedStorySlugs = overrideStorySlugs !== null
        ? overrideStorySlugs
        : templateStorySlugs;

    const templateLinks = mergeSprintLinks(texts.links || sprint.links, locale);
    const overrideLinks = Array.isArray(override.links)
        ? normalizeHelpLinks(override.links, locale)
        : null;

    return {
        id: sprint.id,
        number: Number(override.number ?? sprint.number ?? 0),
        title,
        goal,
        description,
        notesEnabled,
        note: instance.sprintNotes?.[sprint.id] || '',
        custom,
        linkedStorySlugs,
        links: overrideLinks !== null ? overrideLinks : templateLinks,
        tasks,
        deliverables,
        fields,
        progress: { done, total, percent },
        completed: total > 0 && done === total,
    };
}

/**
 * @param {unknown} base
 * @param {unknown} override
 * @returns {string[]}
 */
function mergeStorySlugs(base, override) {
    if (Array.isArray(override)) {
        return override.map(String).filter(Boolean);
    }
    if (Array.isArray(base)) {
        return base.map(String).filter(Boolean);
    }
    return [];
}

/**
 * @param {unknown} override
 * @param {Record<string, unknown>} textTask
 * @param {string} locale
 * @returns {string|null}
 */
function pickHelpText(override, textTask, locale) {
    const fromOverride = pickLocalizedHelpText(override?.helpText, locale);
    if (fromOverride) {
        return fromOverride;
    }
    const fromText = textTask?.helpText;
    if (typeof fromText === 'string' && fromText.trim()) {
        return fromText.trim();
    }
    return null;
}

/**
 * @param {unknown} value
 * @param {string} locale
 * @returns {string|null}
 */
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

/**
 * @param {unknown} baseLinks
 * @param {unknown} overrideLinks
 * @param {string} locale
 * @returns {SpHelpLink[]}
 */
function mergeHelpLinks(baseLinks, overrideLinks, locale) {
    if (Array.isArray(overrideLinks)) {
        return normalizeHelpLinks(overrideLinks, locale);
    }
    return normalizeHelpLinks(baseLinks, locale);
}

/**
 * @param {unknown} links
 * @param {string} locale
 * @returns {SpHelpLink[]}
 */
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
        if (!href) {
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

/**
 * @param {unknown} links
 * @param {string} locale
 * @returns {SpHelpLink[]}
 */
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
        return 1;
    }
    const msPerDay = 24 * 60 * 60 * 1000;
    const days = Math.floor((now.getTime() - start.getTime()) / msPerDay);
    const length = unit === 'week' ? 7 : 7;
    const number = Math.floor(days / length) + 1;
    return Math.min(Math.max(number, 1), sprintCount);
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
