import { getLocale, withAppBasePath } from '../../locale.js';
import { isAccountsMode, readAccountsBootstrap } from '../accounts-bridge.js';
import { filterSprints } from '../filters.js';
import {
    addCustomItem,
    addCustomSprint,
    deleteCustomItem,
    deleteCustomSprint,
    duplicateSprint,
    moveSprint,
    resetSprint,
    setFieldValue,
    setSprintNote,
    toggleCompleted,
    updateCustomOrOverrideSprint,
    updateItemMeta,
} from '../instance-manager.js';
import { listPeople, listTeams, localizedText } from '../people-teams.js';
import {
    calculateCurrentSprintNumber,
    calculateOverallProgress,
    resolveSprints,
} from '../progress.js';
import { loadPreferences, loadWorkspace, savePreferences } from '../storage.js';
import {
    isPasswordProtected,
    isPlanUnlocked,
    unlockPlan,
    verifyPassword,
} from '../plan-password.js';
import {
    applySpI18n,
    fillSelect,
    planOwnershipLabel,
    readTemplatesFromDom,
    showToast,
    spT,
    storageErrorMessage,
} from './helpers.js';
import { isPlaybookRead } from '../../playbooks/read-state.js';

const noteTimers = {};
let sprintDialogState = { sprint: null, isCustom: false };
let itemDialogState = {};
let bound = false;
let helpPanelBound = false;

export function initPlanShowPage() {
    const root = document.getElementById('sp-app');
    if (!root) {
        return;
    }

    const instanceId = root.dataset.spInstanceId;

    const render = () => renderPlan(instanceId);

    if (!bound) {
        bound = true;
        applySpI18n(root);
        window.addEventListener('binom-tools:locale', () => {
            applySpI18n(root);
            render();
        });
        bindFilters(render);
        bindDialogs(instanceId, render);
        bindHelpPanel();
        document.getElementById('sp-add-sprint')?.addEventListener('click', () => {
            openSprintDialog(null, false);
        });
        document.getElementById('sp-unlock-form')?.addEventListener('submit', async (event) => {
            event.preventDefault();
            const { data } = loadWorkspace();
            const instance = data.instances[instanceId];
            const password = document.getElementById('sp-unlock-password')?.value || '';
            const errorEl = document.getElementById('sp-unlock-error');
            const ok = await verifyPassword(password, instance || {});
            if (!ok) {
                if (errorEl) {
                    errorEl.hidden = false;
                    errorEl.textContent = spT('sp.password.wrong');
                }
                return;
            }
            unlockPlan(instanceId);
            if (errorEl) {
                errorEl.hidden = true;
            }
            render();
        });
    }

    root.__spRender = render;
    render();
}

function rerender() {
    document.getElementById('sp-app')?.__spRender?.();
}

function renderPlan(instanceId) {
    const locale = getLocale();
    const { data: workspace, ok, error } = loadWorkspace();
    if (!ok && error) {
        showToast(storageErrorMessage(error));
    }
    const instance = workspace.instances[instanceId];
    const missing = document.getElementById('sp-plan-missing');
    const locked = document.getElementById('sp-plan-locked');
    const view = document.getElementById('sp-plan-view');
    if (!instance) {
        missing.hidden = false;
        if (locked) {
            locked.hidden = true;
        }
        view.hidden = true;
        return;
    }
    missing.hidden = true;

    if (isPasswordProtected(instance) && !isPlanUnlocked(instanceId)) {
        if (locked) {
            locked.hidden = false;
        }
        view.hidden = true;
        return;
    }
    if (locked) {
        locked.hidden = true;
    }
    view.hidden = false;

    const ephemeralBanner = document.getElementById('sp-ephemeral-banner');
    if (ephemeralBanner) {
        ephemeralBanner.hidden = ! instance.ephemeral;
    }

    const templates = readTemplatesFromDom();
    const template = templates.find((item) => item.slug === instance.templateSlug);

    const sprints = template ? resolveSprints(template, instance, locale) : [];
    const progress = calculateOverallProgress(sprints);
    const currentNumber = calculateCurrentSprintNumber(
        instance.startedAt,
        sprints.length || template?.duration || 1,
        template?.unit || 'week',
    );

    document.getElementById('sp-plan-title').textContent =
        instance.translations?.[locale]?.title
        || template?.locales?.[locale]?.title
        || instance.templateSlug;
    document.getElementById('sp-plan-description').textContent =
        instance.translations?.[locale]?.description
        || template?.locales?.[locale]?.description
        || '';
    document.getElementById('sp-plan-started').textContent = instance.startedAt;
    const ownerEl = document.getElementById('sp-plan-owner');
    if (ownerEl) {
        ownerEl.textContent = planOwnershipLabel(instance, workspace);
    }
    document.getElementById('sp-plan-current-sprint').textContent = String(currentNumber);
    document.getElementById('sp-plan-status').textContent = spT(
        instance.archived ? 'sp.status.archived' : 'sp.status.active',
    );
    const team = instance.teamId ? workspace.teams[instance.teamId] : null;
    document.getElementById('sp-plan-team').textContent = team
        ? localizedText(team.name, locale)
        : '—';
    document.getElementById('sp-plan-participants').textContent = (instance.participantIds || [])
        .map((id) => workspace.people[id]?.displayName || id)
        .join(', ') || '—';

    const progressEl = document.getElementById('sp-plan-progress');
    const bar = document.getElementById('sp-plan-progress-bar');
    const label = document.getElementById('sp-plan-progress-label');
    progressEl.setAttribute('aria-valuenow', String(progress.percent));
    progressEl.setAttribute('aria-label', spT('sp.card.progress', { percent: progress.percent }));
    bar.style.width = `${progress.percent}%`;
    label.textContent = `${progress.percent}% (${progress.done}/${progress.total})`;

    populateFilterPeopleTeams(locale);

    const prefs = loadPreferences();
    const filters = readFilters(prefs);
    const filtered = filterSprints(sprints, filters, currentNumber, {
        activePersonId: workspace.workspace.activePersonId,
    });

    renderSprints(filtered, currentNumber, instance, template, locale, workspace);
}

function bindFilters(render) {
    const prefs = loadPreferences();
    const planFilters = prefs.planFilters || {};
    const map = {
        'sp-filter-current-week': 'currentWeek',
        'sp-filter-hide-done': 'hideDone',
        'sp-filter-open-only': 'openOnly',
        'sp-filter-blocked': 'blocked',
        'sp-filter-my-tasks': 'myTasks',
    };
    for (const [id, key] of Object.entries(map)) {
        const el = document.getElementById(id);
        if (!el) {
            continue;
        }
        el.checked = Boolean(planFilters[key]);
        el.addEventListener('change', () => {
            persistFilters();
            render();
        });
    }
    for (const id of ['sp-filter-person', 'sp-filter-team', 'sp-filter-status', 'sp-filter-priority']) {
        const el = document.getElementById(id);
        if (!el) {
            continue;
        }
        const key = id.replace('sp-filter-', '');
        const prefKey = key === 'person' ? 'personId' : key === 'team' ? 'teamId' : key;
        el.value = planFilters[prefKey] || '';
        el.addEventListener('change', () => {
            persistFilters();
            render();
        });
    }
}

function persistFilters() {
    const prefs = loadPreferences();
    prefs.planFilters = readFilters(prefs);
    savePreferences(prefs);
}

function readFilters(prefs) {
    return {
        currentWeek: document.getElementById('sp-filter-current-week')?.checked
            ?? prefs.planFilters?.currentWeek
            ?? false,
        hideDone: document.getElementById('sp-filter-hide-done')?.checked
            ?? prefs.planFilters?.hideDone
            ?? false,
        openOnly: document.getElementById('sp-filter-open-only')?.checked
            ?? prefs.planFilters?.openOnly
            ?? false,
        blocked: document.getElementById('sp-filter-blocked')?.checked
            ?? prefs.planFilters?.blocked
            ?? false,
        myTasks: document.getElementById('sp-filter-my-tasks')?.checked
            ?? prefs.planFilters?.myTasks
            ?? false,
        personId: document.getElementById('sp-filter-person')?.value || '',
        teamId: document.getElementById('sp-filter-team')?.value || '',
        status: document.getElementById('sp-filter-status')?.value || '',
        priority: document.getElementById('sp-filter-priority')?.value || '',
    };
}

function populateFilterPeopleTeams(locale) {
    const personSelect = document.getElementById('sp-filter-person');
    const teamSelect = document.getElementById('sp-filter-team');
    if (personSelect) {
        const current = personSelect.value;
        fillSelect(
            personSelect,
            listPeople().map((p) => ({ id: p.id, label: p.displayName })),
            current,
            true,
        );
    }
    if (teamSelect) {
        const current = teamSelect.value;
        fillSelect(
            teamSelect,
            listTeams().map((team) => ({ id: team.id, label: localizedText(team.name, locale) })),
            current,
            true,
        );
    }
}

function renderSprints(sprints, currentNumber, instance, template, locale, workspace) {
    const host = document.getElementById('sp-sprints');
    const prefs = loadPreferences();
    const expanded = prefs.expandedSprints?.[instance.id] || {};
    host.innerHTML = '';

    for (const sprint of sprints) {
        const details = document.createElement('details');
        details.className = 'sp-sprint';
        details.open = expanded[sprint.id] !== false
            && (expanded[sprint.id] === true || sprint.number === currentNumber);
        details.addEventListener('toggle', () => {
            const next = loadPreferences();
            next.expandedSprints = next.expandedSprints || {};
            next.expandedSprints[instance.id] = next.expandedSprints[instance.id] || {};
            next.expandedSprints[instance.id][sprint.id] = details.open;
            savePreferences(next);
        });

        const summary = document.createElement('summary');
        summary.className = 'sp-sprint__summary';
        summary.innerHTML = `
            <span class="sp-sprint__number">#${sprint.number}</span>
            <span class="sp-sprint__title"></span>
            <span class="sp-sprint__progress">${sprint.progress.percent}% (${sprint.progress.done}/${sprint.progress.total})</span>
            ${sprint.number === currentNumber ? '<span class="sp-sprint__badge"></span>' : ''}
        `;
        summary.querySelector('.sp-sprint__title').textContent = sprint.title;
        const badge = summary.querySelector('.sp-sprint__badge');
        if (badge) {
            badge.textContent = spT('sp.sprint.currentBadge');
        }
        details.appendChild(summary);

        const body = document.createElement('div');
        body.className = 'sp-sprint__body';
        const goal = document.createElement('p');
        goal.className = 'sp-sprint__goal';
        goal.textContent = sprint.goal;
        body.appendChild(goal);
        const sprintLinks = [
            ...(sprint.linkedStorySlugs || []),
            ...(instance.linkedStorySlugs || []),
        ];
        const uniqueSprintLinks = [...new Set(sprintLinks)];
        if (uniqueSprintLinks.length > 0) {
            body.appendChild(renderLinkedStories(uniqueSprintLinks));
        }
        if (Array.isArray(sprint.links) && sprint.links.length > 0) {
            body.appendChild(renderGenericLinks(sprint.links, 'sp-sprint-links'));
        }
        body.appendChild(renderItemSection('tasks', sprint, instance, locale, workspace));
        body.appendChild(renderItemSection('deliverables', sprint, instance, locale, workspace));
        body.appendChild(renderFields(sprint, instance));
        if (sprint.notesEnabled) {
            body.appendChild(renderNotes(sprint, instance));
        }
        body.appendChild(renderSprintActions(sprint, instance, template, locale));
        details.appendChild(body);
        host.appendChild(details);
    }
}

function renderItemSection(kind, sprint, instance, locale, workspace) {
    const section = document.createElement('section');
    section.className = 'sp-sprint__section';
    const title = document.createElement('div');
    title.className = 'sp-sprint__section-head';
    const heading = document.createElement('h3');
    heading.textContent = spT(kind === 'tasks' ? 'sp.sprint.tasks' : 'sp.sprint.deliverables');
    const addBtn = document.createElement('button');
    addBtn.type = 'button';
    addBtn.className = 'tools-btn tools-btn--secondary tools-btn--small';
    addBtn.textContent = spT(kind === 'tasks' ? 'sp.action.addTask' : 'sp.action.addDeliverable');
    addBtn.addEventListener('click', () => openItemDialog({
        sprintId: sprint.id,
        kind: kind === 'tasks' ? 'task' : 'deliverable',
        custom: true,
        create: true,
    }));
    title.append(heading, addBtn);
    section.appendChild(title);

    const list = document.createElement('ul');
    list.className = 'sp-item-list';
    const items = kind === 'tasks' ? sprint.tasks : sprint.deliverables;
    for (const item of items) {
        list.appendChild(renderItemRow(item, sprint, instance, locale, workspace));
    }
    section.appendChild(list);
    return section;
}

function renderItemRow(item, sprint, instance, locale, workspace) {
    const li = document.createElement('li');
    li.className = `sp-item sp-item--${item.status}`;
    const check = document.createElement('input');
    check.type = 'checkbox';
    check.checked = item.completed;
    check.setAttribute('aria-label', item.label);
    check.addEventListener('change', () => {
        const result = toggleCompleted(instance.id, item.kind, item.statusKey, check.checked);
        if (!result.ok) {
            showToast(storageErrorMessage(result.error));
            check.checked = !check.checked;
            return;
        }
        setSaveStatus('saved');
        rerender();
    });

    const main = document.createElement('div');
    main.className = 'sp-item__main';
    const label = document.createElement('span');
    label.className = 'sp-item__label';
    label.textContent = item.label;
    const meta = document.createElement('span');
    meta.className = 'sp-item__meta';
    const assignee = item.assigneeType === 'person'
        ? workspace.people[item.assigneeId]?.displayName
        : item.assigneeType === 'team'
            ? localizedText(workspace.teams[item.assigneeId]?.name, locale)
            : '';
    meta.textContent = [
        spT(`sp.status.${statusKeyToLabel(item.status)}`),
        spT(`sp.priority.${item.priority}`),
        assignee,
        item.dueDate,
    ].filter(Boolean).join(' · ');
    main.append(label, meta);
    if (Array.isArray(item.linkedStorySlugs) && item.linkedStorySlugs.length > 0) {
        main.appendChild(renderLinkedStories(item.linkedStorySlugs));
    }

    const actions = document.createElement('div');
    actions.className = 'sp-item__actions';
    if (itemHasHelp(item)) {
        const help = document.createElement('button');
        help.type = 'button';
        help.className = 'tools-btn tools-btn--secondary tools-btn--small sp-help-btn';
        help.textContent = '?';
        help.title = spT('sp.help.open');
        help.setAttribute('aria-label', spT('sp.help.open'));
        help.addEventListener('click', () => openHelpPanel(item));
        actions.appendChild(help);
    }

    const edit = document.createElement('button');
    edit.type = 'button';
    edit.className = 'tools-btn tools-btn--secondary tools-btn--small';
    edit.textContent = spT('sp.action.edit');
    edit.addEventListener('click', () => openItemDialog({
        sprintId: sprint.id,
        kind: item.kind,
        custom: item.custom,
        item,
    }));
    actions.appendChild(edit);

    if (item.custom) {
        const del = document.createElement('button');
        del.type = 'button';
        del.className = 'tools-btn tools-btn--secondary tools-btn--small';
        del.textContent = spT('sp.action.delete');
        del.addEventListener('click', () => {
            deleteCustomItem(instance.id, sprint.id, item.kind, item.id, item.statusKey);
            rerender();
        });
        actions.appendChild(del);
    }

    li.append(check, main, actions);

    return li;
}

/**
 * @param {import('../progress.js').ResolvedItem} item
 */
function itemHasHelp(item) {
    return Boolean(
        (item.helpText && String(item.helpText).trim())
        || (Array.isArray(item.helpLinks) && item.helpLinks.length > 0)
        || (Array.isArray(item.linkedStorySlugs) && item.linkedStorySlugs.length > 0),
    );
}

/**
 * @param {string[]} slugs
 */
function renderLinkedStories(slugs) {
    const wrap = document.createElement('div');
    wrap.className = 'sp-linked-stories';
    const readSet = new Set(readAccountsBootstrap().readSlugs || []);
    for (const slug of slugs) {
        if (!slug) {
            continue;
        }
        const link = document.createElement('a');
        link.className = 'sp-story-badge';
        link.href = withAppBasePath(`/playbooks/${encodeURIComponent(slug)}`);
        link.target = '_blank';
        link.rel = 'noopener noreferrer';
        const read = isAccountsMode()
            ? readSet.has(slug) || isPlaybookRead(slug)
            : isPlaybookRead(slug);
        link.dataset.read = read ? '1' : '0';
        link.textContent = `${slug}${read ? ` · ${spT('sp.story.read')}` : ` · ${spT('sp.story.unread')}`}`;
        wrap.appendChild(link);
    }
    return wrap;
}

/**
 * @param {Array<{label: string, href: string}>} links
 * @param {string} className
 */
function renderGenericLinks(links, className) {
    const wrap = document.createElement('div');
    wrap.className = className;
    for (const link of links) {
        if (!link?.href) {
            continue;
        }
        const a = document.createElement('a');
        a.href = resolveHelpHref(link.href);
        a.target = '_blank';
        a.rel = 'noopener noreferrer';
        a.textContent = link.label || link.href;
        wrap.appendChild(a);
    }
    return wrap;
}

/**
 * @param {string} href
 */
function resolveHelpHref(href) {
    const value = String(href || '').trim();
    if (!value) {
        return '#';
    }
    if (/^https?:\/\//i.test(value) || value.startsWith('mailto:')) {
        return value;
    }
    if (value.startsWith('/')) {
        return withAppBasePath(value);
    }
    // Bare playbook slug
    if (/^[a-z0-9-]+$/i.test(value)) {
        return withAppBasePath(`/playbooks/${encodeURIComponent(value)}`);
    }
    return value;
}

function bindHelpPanel() {
    if (helpPanelBound) {
        return;
    }
    helpPanelBound = true;
    document.getElementById('sp-help-close')?.addEventListener('click', closeHelpPanel);
    document.getElementById('sp-help-backdrop')?.addEventListener('click', closeHelpPanel);
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeHelpPanel();
        }
    });
}

/**
 * @param {import('../progress.js').ResolvedItem} item
 */
function openHelpPanel(item) {
    const panel = document.getElementById('sp-help-panel');
    const backdrop = document.getElementById('sp-help-backdrop');
    const title = document.getElementById('sp-help-panel-title');
    const body = document.getElementById('sp-help-panel-body');
    if (!panel || !body) {
        return;
    }
    if (title) {
        title.textContent = spT('sp.help.title');
    }
    body.replaceChildren();

    const taskTitle = document.createElement('p');
    taskTitle.className = 'sp-help-panel__task';
    taskTitle.textContent = item.label;
    body.appendChild(taskTitle);

    if (item.helpText) {
        const text = document.createElement('p');
        text.className = 'sp-help-panel__text';
        text.textContent = item.helpText;
        body.appendChild(text);
    }

    const links = [];
    for (const slug of item.linkedStorySlugs || []) {
        links.push({
            label: `${spT('sp.help.story')}: ${slug}`,
            href: `/playbooks/${slug}`,
        });
    }
    for (const link of item.helpLinks || []) {
        links.push(link);
    }

    if (links.length > 0) {
        const list = document.createElement('ul');
        list.className = 'sp-help-panel__links';
        for (const link of links) {
            const li = document.createElement('li');
            const a = document.createElement('a');
            a.href = resolveHelpHref(link.href);
            a.target = '_blank';
            a.rel = 'noopener noreferrer';
            a.textContent = link.label || link.href;
            li.appendChild(a);
            list.appendChild(li);
        }
        body.appendChild(list);
    } else if (!item.helpText) {
        const empty = document.createElement('p');
        empty.className = 'sp-help-panel__empty';
        empty.textContent = spT('sp.help.empty');
        body.appendChild(empty);
    }

    panel.hidden = false;
    panel.setAttribute('aria-hidden', 'false');
    if (backdrop) {
        backdrop.hidden = false;
    }
}

function closeHelpPanel() {
    const panel = document.getElementById('sp-help-panel');
    const backdrop = document.getElementById('sp-help-backdrop');
    if (panel) {
        panel.hidden = true;
        panel.setAttribute('aria-hidden', 'true');
    }
    if (backdrop) {
        backdrop.hidden = true;
    }
}

function statusKeyToLabel(status) {
    return status === 'in_progress' ? 'inProgress' : status;
}

/**
 * Parse "Label | href" lines, or bare slugs / hrefs.
 * @param {string} raw
 * @returns {{links: Array<{label: string, href: string}>, storySlugs: string[]}}
 */
function parseLinksTextarea(raw) {
    /** @type {Array<{label: string, href: string}>} */
    const links = [];
    /** @type {string[]} */
    const storySlugs = [];
    for (const line of String(raw || '').split('\n')) {
        const trimmed = line.trim();
        if (!trimmed) {
            continue;
        }
        const pipe = trimmed.indexOf('|');
        if (pipe >= 0) {
            const label = trimmed.slice(0, pipe).trim();
            const href = trimmed.slice(pipe + 1).trim();
            if (href) {
                links.push({ label: label || href, href });
            }
            continue;
        }
        if (/^[a-z0-9-]+$/i.test(trimmed) && !trimmed.includes('/')) {
            storySlugs.push(trimmed);
            continue;
        }
        links.push({ label: trimmed, href: trimmed });
    }
    return { links, storySlugs };
}

/**
 * @param {Array<{label: string, href: string}>} links
 * @param {string[]} [storySlugs]
 */
function formatLinksTextarea(links = [], storySlugs = []) {
    const lines = [
        ...storySlugs.map(String),
        ...links.map((link) => (link.label && link.label !== link.href
            ? `${link.label} | ${link.href}`
            : link.href)),
    ];
    return lines.filter(Boolean).join('\n');
}

/**
 * @param {string} raw
 * @returns {string[]}
 */
function parseSlugLines(raw) {
    return String(raw || '')
        .split(/[\n,]+/)
        .map((part) => part.trim())
        .filter((slug) => /^[a-z0-9-]+$/i.test(slug));
}

function renderFields(sprint, instance) {
    const section = document.createElement('section');
    section.className = 'sp-sprint__section';
    const heading = document.createElement('h3');
    heading.textContent = spT('sp.sprint.fields');
    section.appendChild(heading);
    for (const field of sprint.fields) {
        const label = document.createElement('label');
        label.className = 'sp-field';
        const span = document.createElement('span');
        span.textContent = field.label;
        let input;
        if (field.type === 'textarea') {
            input = document.createElement('textarea');
            input.rows = 3;
            input.value = field.value == null ? '' : String(field.value);
        } else if (field.type === 'checkbox') {
            input = document.createElement('input');
            input.type = 'checkbox';
            input.checked = Boolean(field.value);
        } else {
            input = document.createElement('input');
            input.type = ['number', 'date', 'url'].includes(field.type) ? field.type : 'text';
            input.value = field.value == null ? '' : String(field.value);
        }
        if (field.placeholder && 'placeholder' in input) {
            input.placeholder = field.placeholder;
        }
        input.className = 'tools-input';
        input.addEventListener('change', () => {
            const value = field.type === 'checkbox' ? input.checked : input.value;
            const result = setFieldValue(instance.id, field.statusKey, value);
            setSaveStatus(result.ok ? 'saved' : 'error');
        });
        label.append(span, input);
        section.appendChild(label);
    }
    return section;
}

function renderNotes(sprint, instance) {
    const section = document.createElement('section');
    section.className = 'sp-sprint__section';
    const heading = document.createElement('h3');
    heading.textContent = spT('sp.sprint.notes');
    const area = document.createElement('textarea');
    area.className = 'tools-input';
    area.rows = 4;
    area.maxLength = 8000;
    area.value = sprint.note || '';
    area.addEventListener('input', () => {
        setSaveStatus('saving');
        window.clearTimeout(noteTimers[sprint.id]);
        noteTimers[sprint.id] = window.setTimeout(() => {
            const result = setSprintNote(instance.id, sprint.id, area.value);
            setSaveStatus(result.ok ? 'saved' : 'error');
        }, 400);
    });
    section.append(heading, area);
    return section;
}

function renderSprintActions(sprint, instance, template, locale) {
    const row = document.createElement('div');
    row.className = 'sp-action-row';
    const actions = [
        ['edit', () => openSprintDialog(sprint, sprint.custom)],
        ['duplicate', () => {
            duplicateSprint(instance.id, sprint, locale);
            rerender();
        }],
        ['moveUp', () => {
            moveSprint(instance.id, sprint.id, 'up', sprint.custom, sprint.number);
            rerender();
        }],
        ['moveDown', () => {
            moveSprint(instance.id, sprint.id, 'down', sprint.custom, sprint.number);
            rerender();
        }],
        ['reset', () => {
            if (!window.confirm(spT('sp.confirm.resetSprint'))) {
                return;
            }
            resetSprint(instance.id, sprint.id, template?.slug || instance.templateSlug);
            rerender();
        }],
    ];
    if (sprint.custom) {
        actions.push(['delete', () => {
            if (!window.confirm(spT('sp.confirm.deleteSprint'))) {
                return;
            }
            deleteCustomSprint(instance.id, sprint.id);
            rerender();
        }]);
    }
    for (const [key, handler] of actions) {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'tools-btn tools-btn--secondary tools-btn--small';
        btn.textContent = spT(`sp.action.${key}`);
        btn.addEventListener('click', handler);
        row.appendChild(btn);
    }
    return row;
}

function bindDialogs(instanceId, render) {
    const sprintDialog = document.getElementById('sp-sprint-dialog');
    sprintDialog?.addEventListener('close', () => {
        if (sprintDialog.returnValue !== 'confirm') {
            return;
        }
        const stories = parseSlugLines(document.getElementById('sp-sprint-stories')?.value || '');
        const parsedLinks = parseLinksTextarea(document.getElementById('sp-sprint-links')?.value || '');
        const data = {
            titleDe: document.getElementById('sp-sprint-title-de').value,
            titleEn: document.getElementById('sp-sprint-title-en').value,
            goalDe: document.getElementById('sp-sprint-goal-de').value,
            goalEn: document.getElementById('sp-sprint-goal-en').value,
            number: Number(document.getElementById('sp-sprint-position').value) || undefined,
            notes: document.getElementById('sp-sprint-notes-enabled').checked,
            linkedStorySlugs: stories,
            links: parsedLinks.links,
        };
        const result = sprintDialogState.sprint
            ? updateCustomOrOverrideSprint(
                instanceId,
                sprintDialogState.sprint.id,
                data,
                sprintDialogState.isCustom,
            )
            : addCustomSprint(instanceId, data);
        if (!result.ok) {
            showToast(storageErrorMessage(result.error));
            return;
        }
        render();
    });

    const itemDialog = document.getElementById('sp-item-dialog');
    itemDialog?.addEventListener('close', () => {
        if (itemDialog.returnValue !== 'confirm') {
            return;
        }
        const stories = parseSlugLines(document.getElementById('sp-item-stories')?.value || '');
        const parsedLinks = parseLinksTextarea(document.getElementById('sp-item-help-links')?.value || '');
        const data = {
            id: document.getElementById('sp-item-id').value,
            labelDe: document.getElementById('sp-item-label-de').value,
            labelEn: document.getElementById('sp-item-label-en').value,
            assigneeType: document.getElementById('sp-item-assignee-type').value,
            assigneeId: document.getElementById('sp-item-assignee-id').value || null,
            status: document.getElementById('sp-item-status').value,
            priority: document.getElementById('sp-item-priority').value,
            dueDate: document.getElementById('sp-item-due').value || null,
            note: document.getElementById('sp-item-note').value,
            helpTextDe: document.getElementById('sp-item-help-de')?.value || '',
            helpTextEn: document.getElementById('sp-item-help-en')?.value || '',
            linkedStorySlugs: stories,
            helpLinks: parsedLinks.links,
        };
        const kind = document.getElementById('sp-item-type').value;
        const sprintId = document.getElementById('sp-item-sprint-id').value;
        const inst = loadWorkspace().data.instances[instanceId];
        const template = readTemplatesFromDom().find((t) => t.slug === inst?.templateSlug);
        const result = itemDialogState.create
            ? addCustomItem(instanceId, sprintId, kind, data, template?.slug || 'custom')
            : updateItemMeta(
                instanceId,
                kind,
                itemDialogState.item.statusKey,
                data,
                itemDialogState.custom,
                sprintId,
            );
        if (!result.ok) {
            showToast(storageErrorMessage(result.error));
            return;
        }
        render();
    });

    document.getElementById('sp-item-assignee-type')?.addEventListener('change', () => refreshAssigneeOptions());
}

function openSprintDialog(sprint, isCustom) {
    sprintDialogState = { sprint, isCustom: Boolean(isCustom) };
    document.getElementById('sp-sprint-edit-id').value = sprint?.id || '';
    document.getElementById('sp-sprint-title-de').value = sprint?.title || '';
    document.getElementById('sp-sprint-title-en').value = sprint?.title || '';
    document.getElementById('sp-sprint-goal-de').value = sprint?.goal || '';
    document.getElementById('sp-sprint-goal-en').value = sprint?.goal || '';
    document.getElementById('sp-sprint-position').value = sprint?.number || '';
    document.getElementById('sp-sprint-notes-enabled').checked = sprint?.notesEnabled !== false;
    const storiesEl = document.getElementById('sp-sprint-stories');
    if (storiesEl) {
        storiesEl.value = (sprint?.linkedStorySlugs || []).join('\n');
    }
    const linksEl = document.getElementById('sp-sprint-links');
    if (linksEl) {
        linksEl.value = formatLinksTextarea(sprint?.links || []);
    }
    document.getElementById('sp-sprint-dialog').showModal();
}

function openItemDialog(state) {
    itemDialogState = state;
    document.getElementById('sp-item-sprint-id').value = state.sprintId;
    document.getElementById('sp-item-type').value = state.kind;
    document.getElementById('sp-item-id').value = state.item?.id || '';
    document.getElementById('sp-item-label-de').value = state.item?.label || '';
    document.getElementById('sp-item-label-en').value = state.item?.label || '';
    document.getElementById('sp-item-assignee-type').value = state.item?.assigneeType || 'person';
    document.getElementById('sp-item-status').value = state.item?.status || 'open';
    document.getElementById('sp-item-priority').value = state.item?.priority || 'normal';
    document.getElementById('sp-item-due').value = state.item?.dueDate || '';
    document.getElementById('sp-item-note').value = state.item?.note || '';
    const helpDe = document.getElementById('sp-item-help-de');
    const helpEn = document.getElementById('sp-item-help-en');
    const helpText = state.item?.helpText || '';
    if (helpDe) {
        helpDe.value = typeof helpText === 'object'
            ? (helpText.de || '')
            : helpText;
    }
    if (helpEn) {
        helpEn.value = typeof helpText === 'object'
            ? (helpText.en || helpText.de || '')
            : helpText;
    }
    const storiesEl = document.getElementById('sp-item-stories');
    if (storiesEl) {
        storiesEl.value = (state.item?.linkedStorySlugs || []).join('\n');
    }
    const linksEl = document.getElementById('sp-item-help-links');
    if (linksEl) {
        linksEl.value = formatLinksTextarea(state.item?.helpLinks || []);
    }
    refreshAssigneeOptions(state.item?.assigneeId || '');
    document.getElementById('sp-item-dialog').showModal();
}

function refreshAssigneeOptions(selected) {
    const type = document.getElementById('sp-item-assignee-type')?.value || 'person';
    const select = document.getElementById('sp-item-assignee-id');
    const locale = getLocale();
    const selectedValue = typeof selected === 'string' ? selected : select.value;
    if (type === 'team') {
        fillSelect(
            select,
            listTeams().map((team) => ({ id: team.id, label: localizedText(team.name, locale) })),
            selectedValue,
            true,
        );
    } else {
        fillSelect(
            select,
            listPeople().map((p) => ({ id: p.id, label: p.displayName })),
            selectedValue,
            true,
        );
    }
}

function setSaveStatus(state) {
    const el = document.getElementById('sp-save-status');
    if (!el) {
        return;
    }
    el.textContent = state === 'saving'
        ? spT('sp.save.saving')
        : state === 'saved'
            ? spT('sp.save.saved')
            : spT('sp.save.error');
}
