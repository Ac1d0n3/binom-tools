import { withAppBasePath } from '../../locale.js';
import { isAccountsMode, isLoggedIn, usesServerPlans } from '../accounts-bridge.js';
import { parseExternalLinksTextarea } from '../external-links.js';
import { createSprintId, createTaskId, createUserTemplateId } from '../ids.js';
import { startInstanceFromTemplate } from '../instance-manager.js';
import { parseTableColumnsText } from '../item-table.js';
import { normalizeStories } from '../progress.js';
import { upsertUserTemplate } from '../user-templates.js';
import { applySpI18n, planUrl, showToast, spT } from './helpers.js';

/** @type {{ id: string, titleDe: string, titleEn: string, goalDe: string, goalEn: string, linksRaw: string, tasks: CreatorTask[] }[]} */
let sprintsState = [];
/** @type {string|null} */
let editingTemplateId = null;

/**
 * @typedef {{ id: string, labelDe: string, labelEn: string, helpDe: string, helpEn: string, linksRaw: string, storiesRaw: string, tableColumnsRaw: string }} CreatorTask
 */

export function initPlanCreatorPage() {
    const root = document.getElementById('sp-app');
    if (!root) {
        return;
    }

    applySpI18n(root);
    window.addEventListener('binom-tools:locale', () => {
        applySpI18n(root);
        renderSprints();
    });

    const startDate = document.getElementById('sp-creator-start-date');
    if (startDate && !startDate.value) {
        startDate.value = new Date().toISOString().slice(0, 10);
    }

    hydrateFromEditTemplate(root);
    if (!sprintsState.length) {
        addSprint();
    } else {
        renderSprints();
    }

    document.getElementById('sp-creator-add-sprint')?.addEventListener('click', () => {
        addSprint();
    });
    document.getElementById('sp-creator-save-template')?.addEventListener('click', async () => {
        await saveAsTemplate();
    });
    document.getElementById('sp-creator-start')?.addEventListener('click', async () => {
        await startPlan();
    });

    const saveBtn = document.getElementById('sp-creator-save-template');
    if (saveBtn && isAccountsMode() && !isLoggedIn()) {
        saveBtn.hidden = true;
    }
}

/**
 * @param {HTMLElement} root
 */
function hydrateFromEditTemplate(root) {
    let raw = null;
    const editEl = document.getElementById('sp-bootstrap-edit-template');
    if (editEl?.textContent?.trim()) {
        try {
            raw = JSON.parse(editEl.textContent);
        } catch {
            raw = null;
        }
    }
    if (!raw) {
        try {
            raw = JSON.parse(root.dataset.spEditTemplate || 'null');
        } catch {
            raw = null;
        }
    }
    if (!raw || typeof raw !== 'object') {
        return;
    }
    editingTemplateId = String(raw.id || '') || null;
    document.getElementById('sp-creator-title-de').value = raw.locales?.de?.title || '';
    document.getElementById('sp-creator-title-en').value = raw.locales?.en?.title || '';
    document.getElementById('sp-creator-desc-de').value = raw.locales?.de?.description || '';
    document.getElementById('sp-creator-desc-en').value = raw.locales?.en?.description || '';

    sprintsState = (Array.isArray(raw.sprints) ? raw.sprints : []).map((sprint, index) => ({
        id: String(sprint.id || createSprintId()),
        titleDe: sprint.title?.de || sprint.title || `Sprint ${index + 1}`,
        titleEn: sprint.title?.en || sprint.title || `Sprint ${index + 1}`,
        goalDe: sprint.goal?.de || sprint.goal || '',
        goalEn: sprint.goal?.en || sprint.goal || '',
        linksRaw: formatLinks(sprint.links || []),
        tasks: (Array.isArray(sprint.tasks) ? sprint.tasks : []).map((task) => ({
            id: String(task.id || createTaskId()),
            labelDe: task.label?.de || task.label || '',
            labelEn: task.label?.en || task.label || '',
            helpDe: task.helpText?.de || (typeof task.helpText === 'string' ? task.helpText : '') || '',
            helpEn: task.helpText?.en || '',
            linksRaw: formatLinks(task.helpLinks || []),
            storiesRaw: (task.stories || task.linkedStorySlugs || [])
                .map((s) => (typeof s === 'string' ? s : s.slug))
                .filter(Boolean)
                .join('\n'),
            tableColumnsRaw: (task.table?.columns || []).map((c) => c.label || c.id).join(', '),
        })),
    }));
}

function addSprint() {
    const n = sprintsState.length + 1;
    sprintsState.push({
        id: createSprintId(),
        titleDe: `Sprint ${n}`,
        titleEn: `Sprint ${n}`,
        goalDe: '',
        goalEn: '',
        linksRaw: '',
        tasks: [blankTask()],
    });
    renderSprints();
}

function blankTask() {
    return {
        id: createTaskId(),
        labelDe: '',
        labelEn: '',
        helpDe: '',
        helpEn: '',
        linksRaw: '',
        storiesRaw: '',
        tableColumnsRaw: '',
    };
}

function renderSprints() {
    const host = document.getElementById('sp-creator-sprints');
    if (!host) {
        return;
    }
    syncFromDom();
    host.innerHTML = '';

    sprintsState.forEach((sprint, sprintIndex) => {
        const card = document.createElement('article');
        card.className = 'sp-creator-sprint';
        card.dataset.sprintIndex = String(sprintIndex);

        const head = document.createElement('div');
        head.className = 'sp-creator-sprint__head';
        const title = document.createElement('h3');
        title.textContent = `#${sprintIndex + 1}`;
        const remove = document.createElement('button');
        remove.type = 'button';
        remove.className = 'tools-btn tools-btn--secondary tools-btn--small';
        remove.textContent = spT('sp.action.delete');
        remove.addEventListener('click', () => {
            syncFromDom();
            sprintsState.splice(sprintIndex, 1);
            renderSprints();
        });
        head.append(title, remove);
        card.appendChild(head);

        card.appendChild(fieldInput('titleDe', spT('sp.field.titleDe'), sprint.titleDe, `s${sprintIndex}-title-de`));
        card.appendChild(fieldInput('titleEn', spT('sp.field.titleEn'), sprint.titleEn, `s${sprintIndex}-title-en`));
        card.appendChild(fieldTextarea('goalDe', spT('sp.field.goalDe'), sprint.goalDe, `s${sprintIndex}-goal-de`));
        card.appendChild(fieldTextarea('goalEn', spT('sp.field.goalEn'), sprint.goalEn, `s${sprintIndex}-goal-en`));
        card.appendChild(fieldTextarea('linksRaw', spT('sp.field.sprintLinks'), sprint.linksRaw, `s${sprintIndex}-links`, spT('sp.field.linksHint')));

        const tasksHead = document.createElement('div');
        tasksHead.className = 'sp-creator__sprints-head';
        const tasksTitle = document.createElement('h4');
        tasksTitle.textContent = spT('sp.creator.tasks');
        const addTask = document.createElement('button');
        addTask.type = 'button';
        addTask.className = 'tools-btn tools-btn--secondary tools-btn--small';
        addTask.textContent = spT('sp.action.addTask');
        addTask.addEventListener('click', () => {
            syncFromDom();
            sprintsState[sprintIndex].tasks.push(blankTask());
            renderSprints();
        });
        tasksHead.append(tasksTitle, addTask);
        card.appendChild(tasksHead);

        sprint.tasks.forEach((task, taskIndex) => {
            const taskCard = document.createElement('div');
            taskCard.className = 'sp-creator-task';
            taskCard.dataset.taskIndex = String(taskIndex);
            const taskRemove = document.createElement('button');
            taskRemove.type = 'button';
            taskRemove.className = 'tools-btn tools-btn--secondary tools-btn--small';
            taskRemove.textContent = spT('sp.action.delete');
            taskRemove.addEventListener('click', () => {
                syncFromDom();
                sprintsState[sprintIndex].tasks.splice(taskIndex, 1);
                renderSprints();
            });
            taskCard.appendChild(taskRemove);
            taskCard.appendChild(fieldInput('labelDe', spT('sp.field.labelDe'), task.labelDe, `s${sprintIndex}-t${taskIndex}-label-de`));
            taskCard.appendChild(fieldInput('labelEn', spT('sp.field.labelEn'), task.labelEn, `s${sprintIndex}-t${taskIndex}-label-en`));
            taskCard.appendChild(fieldTextarea('helpDe', spT('sp.field.helpTextDe'), task.helpDe, `s${sprintIndex}-t${taskIndex}-help-de`));
            taskCard.appendChild(fieldTextarea('helpEn', spT('sp.field.helpTextEn'), task.helpEn, `s${sprintIndex}-t${taskIndex}-help-en`));
            taskCard.appendChild(fieldTextarea('linksRaw', spT('sp.field.helpLinks'), task.linksRaw, `s${sprintIndex}-t${taskIndex}-links`, spT('sp.field.linksHint')));
            taskCard.appendChild(fieldTextarea('storiesRaw', spT('sp.field.itemStories'), task.storiesRaw, `s${sprintIndex}-t${taskIndex}-stories`, spT('sp.field.sprintStoriesHint')));
            taskCard.appendChild(fieldInput('tableColumnsRaw', spT('sp.field.tableColumns'), task.tableColumnsRaw, `s${sprintIndex}-t${taskIndex}-cols`, spT('sp.field.tableColumnsHint')));
            card.appendChild(taskCard);
        });

        host.appendChild(card);
    });
}

function fieldInput(key, label, value, id, placeholder = '') {
    const el = document.createElement('label');
    el.className = 'sp-field';
    el.dataset.field = key;
    const span = document.createElement('span');
    span.textContent = label;
    const input = document.createElement('input');
    input.type = 'text';
    input.className = 'tools-input';
    input.id = id;
    input.value = value || '';
    if (placeholder) {
        input.placeholder = placeholder;
    }
    el.append(span, input);
    return el;
}

function fieldTextarea(key, label, value, id, placeholder = '') {
    const el = document.createElement('label');
    el.className = 'sp-field';
    el.dataset.field = key;
    const span = document.createElement('span');
    span.textContent = label;
    const input = document.createElement('textarea');
    input.className = 'tools-input';
    input.rows = 2;
    input.id = id;
    input.value = value || '';
    if (placeholder) {
        input.placeholder = placeholder;
    }
    el.append(span, input);
    return el;
}

function syncFromDom() {
    const host = document.getElementById('sp-creator-sprints');
    if (!host) {
        return;
    }
    for (const card of host.querySelectorAll('.sp-creator-sprint')) {
        const sprintIndex = Number(card.dataset.sprintIndex);
        const sprint = sprintsState[sprintIndex];
        if (!sprint) {
            continue;
        }
        sprint.titleDe = card.querySelector('#s' + sprintIndex + '-title-de')?.value || '';
        sprint.titleEn = card.querySelector('#s' + sprintIndex + '-title-en')?.value || '';
        sprint.goalDe = card.querySelector('#s' + sprintIndex + '-goal-de')?.value || '';
        sprint.goalEn = card.querySelector('#s' + sprintIndex + '-goal-en')?.value || '';
        sprint.linksRaw = card.querySelector('#s' + sprintIndex + '-links')?.value || '';
        for (const taskCard of card.querySelectorAll('.sp-creator-task')) {
            const taskIndex = Number(taskCard.dataset.taskIndex);
            const task = sprint.tasks[taskIndex];
            if (!task) {
                continue;
            }
            const p = `s${sprintIndex}-t${taskIndex}`;
            task.labelDe = taskCard.querySelector(`#${p}-label-de`)?.value || '';
            task.labelEn = taskCard.querySelector(`#${p}-label-en`)?.value || '';
            task.helpDe = taskCard.querySelector(`#${p}-help-de`)?.value || '';
            task.helpEn = taskCard.querySelector(`#${p}-help-en`)?.value || '';
            task.linksRaw = taskCard.querySelector(`#${p}-links`)?.value || '';
            task.storiesRaw = taskCard.querySelector(`#${p}-stories`)?.value || '';
            task.tableColumnsRaw = taskCard.querySelector(`#${p}-cols`)?.value || '';
        }
    }
}

/**
 * @returns {object}
 */
function buildTemplatePayload() {
    syncFromDom();
    const titleDe = document.getElementById('sp-creator-title-de')?.value?.trim() || 'Custom plan';
    const titleEn = document.getElementById('sp-creator-title-en')?.value?.trim() || titleDe;
    const descDe = document.getElementById('sp-creator-desc-de')?.value?.trim() || '';
    const descEn = document.getElementById('sp-creator-desc-en')?.value?.trim() || descDe;
    const id = editingTemplateId || createUserTemplateId();
    const slug = `custom:${id}`;

    const sprints = sprintsState.map((sprint, index) => {
        const linksParsed = parseExternalLinksTextarea(sprint.linksRaw);
        const tasks = sprint.tasks
            .filter((t) => t.labelDe.trim() || t.labelEn.trim())
            .map((task) => {
                const helpLinks = parseExternalLinksTextarea(task.linksRaw).links;
                const stories = normalizeStories(
                    String(task.storiesRaw || '')
                        .split(/[\n,]+/)
                        .map((s) => s.trim())
                        .filter(Boolean),
                );
                const columns = parseTableColumnsText(task.tableColumnsRaw);
                /** @type {Record<string, unknown>} */
                const out = {
                    id: task.id,
                    label: { de: task.labelDe || task.labelEn, en: task.labelEn || task.labelDe },
                    helpText: { de: task.helpDe, en: task.helpEn || task.helpDe },
                    helpLinks,
                    stories,
                    linkedStorySlugs: stories.map((s) => s.slug),
                    assigneeType: 'person',
                    assigneeId: null,
                };
                if (columns.length) {
                    out.table = { columns, rows: [] };
                }
                return out;
            });

        return {
            id: sprint.id,
            number: index + 1,
            title: { de: sprint.titleDe || `Sprint ${index + 1}`, en: sprint.titleEn || sprint.titleDe || `Sprint ${index + 1}` },
            goal: { de: sprint.goalDe, en: sprint.goalEn || sprint.goalDe },
            links: linksParsed.links,
            stories: [],
            linkedStorySlugs: [],
            tasks,
            deliverables: [],
            fields: [],
            notesEnabled: true,
        };
    });

    return {
        id,
        slug,
        version: 1,
        duration: Math.max(1, sprints.length),
        unit: 'week',
        userTemplate: true,
        locales: {
            de: { title: titleDe, description: descDe },
            en: { title: titleEn, description: descEn },
        },
        sprints,
    };
}

async function saveAsTemplate() {
    if (isAccountsMode() && !isLoggedIn()) {
        showToast(spT('sp.creator.loginRequired'));
        return;
    }
    const template = buildTemplatePayload();
    if (!template.sprints.length) {
        showToast(spT('sp.creator.needSprint'));
        return;
    }
    try {
        if (usesServerPlans() || (isAccountsMode() && isLoggedIn())) {
            const result = await upsertUserTemplate(template);
            editingTemplateId = result.template?.id || template.id;
            showToast(spT('sp.creator.templateSaved'));
        } else {
            showToast(spT('sp.creator.templateLocalOnly'));
        }
    } catch {
        showToast(spT('sp.creator.templateSaveFailed'));
    }
}

async function startPlan() {
    const template = buildTemplatePayload();
    if (!template.sprints.length) {
        showToast(spT('sp.creator.needSprint'));
        return;
    }
    if (isAccountsMode() && isLoggedIn()) {
        try {
            const result = await upsertUserTemplate(template);
            Object.assign(template, result.template || {});
            editingTemplateId = template.id;
        } catch {
            // Still allow starting with snapshot if save fails.
        }
    }
    const startedAt = document.getElementById('sp-creator-start-date')?.value
        || new Date().toISOString().slice(0, 10);
    const result = startInstanceFromTemplate(template, {
        startedAt,
        ephemeral: isAccountsMode() && !usesServerPlans(),
    });
    if (!result.ok) {
        showToast(spT('sp.error.storage'));
        return;
    }
    showToast(spT('sp.toast.started'));
    const indexUrl = document.getElementById('sp-app')?.dataset?.spIndexUrl || withAppBasePath('/sprint-planner');
    window.location.href = planUrl(result.instance.id) || indexUrl;
}

/**
 * @param {Array<{label?: string, href?: string}>} links
 */
function formatLinks(links) {
    return (links || [])
        .map((link) => (link.label && link.label !== link.href
            ? `${link.label} | ${link.href}`
            : link.href))
        .filter(Boolean)
        .join('\n');
}
