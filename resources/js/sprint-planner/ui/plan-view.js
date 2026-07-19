import { getLocale } from '../../locale.js';
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
    collectBlockers,
    normalizeStories,
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
import {
    bindHelpPanelChrome,
    closeHelpPanel,
    fillHelpPanel,
    readStoryTitlesFromDom,
    renderFlow,
    renderHelpButton,
    resolveHelpHref,
} from './help-panel.js';

const noteTimers = {};
let sprintDialogState = { sprint: null, isCustom: false };
let itemDialogState = {};
let bound = false;
/** @type {{instance: object, template: object|undefined, sprints: object[], workspace: object, locale: 'de'|'en'}|null} */
let lastRenderContext = null;

export function initPlanShowPage() {
    const root = document.getElementById('sp-app');
    if (!root) {
        return;
    }

    const instanceId = root.dataset.spInstanceId;

    const render = () => renderPlan(instanceId);

    if (!bound) {
        bound = true;
        readStoryTitlesFromDom(root);
        applySpI18n(root);
        window.addEventListener('binom-tools:locale', () => {
            applySpI18n(root);
            render();
        });
        bindFilters(render);
        bindDialogs(instanceId, render);
        bindHelpPanelChrome();
        bindStatusReport();
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

function isStoryRead(slug) {
    const readSet = new Set(readAccountsBootstrap().readSlugs || []);
    return isAccountsMode()
        ? readSet.has(slug) || isPlaybookRead(slug)
        : isPlaybookRead(slug);
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

    renderBlockers(sprints, workspace, locale);
    renderSprints(filtered, currentNumber, instance, template, locale, workspace);

    lastRenderContext = { instance, template, sprints, workspace, locale };
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
        'sp-filter-unassigned': 'unassigned',
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
        unassigned: document.getElementById('sp-filter-unassigned')?.checked
            ?? prefs.planFilters?.unassigned
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

/**
 * @param {import('../progress.js').ResolvedItem} item
 * @param {import('../storage.js').SpWorkspaceRoot} workspace
 * @param {'de'|'en'} locale
 */
function resolveAssigneeName(item, workspace, locale) {
    if (item.assigneeType === 'person') {
        return workspace.people[item.assigneeId]?.displayName || '';
    }
    if (item.assigneeType === 'team') {
        return localizedText(workspace.teams[item.assigneeId]?.name, locale) || '';
    }
    return '';
}

function ensureBlockersHost() {
    let host = document.getElementById('sp-blockers');
    if (!host) {
        host = document.createElement('div');
        host.id = 'sp-blockers';
        host.className = 'sp-blockers';
        const sprintsHost = document.getElementById('sp-sprints');
        sprintsHost?.parentNode?.insertBefore(host, sprintsHost);
    }
    return host;
}

/**
 * @param {ReturnType<typeof resolveSprints>} sprints
 * @param {import('../storage.js').SpWorkspaceRoot} workspace
 * @param {'de'|'en'} locale
 */
function renderBlockers(sprints, workspace, locale) {
    const host = ensureBlockersHost();
    const blockers = collectBlockers(sprints);
    host.innerHTML = '';
    if (!blockers.length) {
        host.hidden = true;
        return;
    }
    host.hidden = false;

    const heading = document.createElement('h2');
    heading.className = 'sp-blockers__title';
    heading.textContent = spT('sp.blockers.title');
    host.appendChild(heading);

    const list = document.createElement('ul');
    list.className = 'sp-blockers__list';
    for (const blocker of blockers) {
        const li = document.createElement('li');
        li.className = 'sp-blockers__item';

        const main = document.createElement('div');
        main.className = 'sp-blockers__main';
        const title = document.createElement('strong');
        title.textContent = `#${blocker.sprintNumber} · ${blocker.item.label}`;
        const meta = document.createElement('span');
        meta.className = 'sp-blockers__meta';
        meta.textContent = [blocker.sprintTitle, resolveAssigneeName(blocker.item, workspace, locale)]
            .filter(Boolean)
            .join(' · ');
        main.append(title, meta);
        li.appendChild(main);

        if (blocker.item.blockerReason) {
            const reason = document.createElement('p');
            reason.className = 'sp-blockers__reason';
            reason.textContent = blocker.item.blockerReason;
            li.appendChild(reason);
        }
        list.appendChild(li);
    }
    host.appendChild(list);
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

        if (sprint.flow) {
            const flowEl = renderFlow(sprint.flow);
            if (flowEl) {
                body.appendChild(flowEl);
            }
        }

        if (sprint.stories?.length) {
            body.appendChild(renderSprintStoriesRow(sprint));
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

/**
 * @param {ReturnType<typeof resolveSprints>[number]} sprint
 */
function renderSprintStoriesRow(sprint) {
    const row = document.createElement('div');
    row.className = 'sp-sprint__stories-btn';
    const label = document.createElement('span');
    label.textContent = spT('sp.sprint.storiesTitle');
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'tools-btn tools-btn--secondary tools-btn--small';
    btn.textContent = spT('sp.action.readStories');
    btn.addEventListener('click', () => openSprintHelp(sprint));
    row.append(label, btn);
    return row;
}

/**
 * @param {ReturnType<typeof resolveSprints>[number]} sprint
 */
function openSprintHelp(sprint) {
    fillHelpPanel({
        title: sprint.title,
        stories: sprint.stories,
        helpLinks: sprint.links,
        flow: sprint.flow,
    }, () => rerender());
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
    const assignee = resolveAssigneeName(item, workspace, locale);
    meta.textContent = [
        spT(`sp.status.${statusKeyToLabel(item.status)}`),
        spT(`sp.priority.${item.priority}`),
        assignee,
        item.dueDate,
    ].filter(Boolean).join(' · ');
    main.append(label, meta);

    if (item.status === 'blocked' && item.blockerReason) {
        const reason = document.createElement('p');
        reason.className = 'sp-item__blocker-reason';
        reason.textContent = item.blockerReason;
        main.appendChild(reason);
    }

    const actions = document.createElement('div');
    actions.className = 'sp-item__actions';
    const helpBtn = renderHelpButton(item, (it) => openItemHelp(it));
    if (helpBtn) {
        actions.appendChild(helpBtn);
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
function openItemHelp(item) {
    fillHelpPanel({
        title: item.label,
        helpText: item.helpText,
        stories: item.stories,
        helpLinks: item.helpLinks,
        demoCode: item.demoCode,
    }, () => rerender());
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
 * Parse story lines: `slug | required`, `slug | optional`, or a bare slug (= required).
 * @param {string} raw
 * @returns {import('../progress.js').SpStoryRef[]}
 */
function parseStoriesTextarea(raw) {
    /** @type {Array<{slug: string, required: boolean}>} */
    const entries = [];
    for (const line of String(raw || '').split(/[\n,]+/)) {
        const trimmed = line.trim();
        if (!trimmed) {
            continue;
        }
        const pipe = trimmed.indexOf('|');
        if (pipe >= 0) {
            const slug = trimmed.slice(0, pipe).trim();
            const flag = trimmed.slice(pipe + 1).trim().toLowerCase();
            entries.push({ slug, required: flag !== 'optional' });
            continue;
        }
        entries.push({ slug: trimmed, required: true });
    }
    return normalizeStories(entries);
}

/**
 * @param {import('../progress.js').SpStoryRef[]} stories
 */
function formatStoriesTextarea(stories = []) {
    return stories
        .map((story) => (story.required === false ? `${story.slug} | optional` : story.slug))
        .join('\n');
}

function todayIso() {
    return new Date().toISOString().slice(0, 10);
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
        const stories = parseStoriesTextarea(document.getElementById('sp-sprint-stories')?.value || '');
        const parsedLinks = parseLinksTextarea(document.getElementById('sp-sprint-links')?.value || '');
        const data = {
            titleDe: document.getElementById('sp-sprint-title-de').value,
            titleEn: document.getElementById('sp-sprint-title-en').value,
            goalDe: document.getElementById('sp-sprint-goal-de').value,
            goalEn: document.getElementById('sp-sprint-goal-en').value,
            number: Number(document.getElementById('sp-sprint-position').value) || undefined,
            notes: document.getElementById('sp-sprint-notes-enabled').checked,
            stories,
            linkedStorySlugs: stories.map((story) => story.slug),
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
        const stories = parseStoriesTextarea(document.getElementById('sp-item-stories')?.value || '');
        const parsedLinks = parseLinksTextarea(document.getElementById('sp-item-help-links')?.value || '');
        const status = document.getElementById('sp-item-status').value;
        const previousStatus = itemDialogState.item?.status;
        const blockerReason = document.getElementById('sp-item-blocker-reason')?.value || '';
        const blockerSince = status === 'blocked'
            ? (previousStatus === 'blocked' ? (itemDialogState.item?.blockerSince || todayIso()) : todayIso())
            : null;
        const data = {
            id: document.getElementById('sp-item-id').value,
            labelDe: document.getElementById('sp-item-label-de').value,
            labelEn: document.getElementById('sp-item-label-en').value,
            assigneeType: document.getElementById('sp-item-assignee-type').value,
            assigneeId: document.getElementById('sp-item-assignee-id').value || null,
            status,
            priority: document.getElementById('sp-item-priority').value,
            dueDate: document.getElementById('sp-item-due').value || null,
            note: document.getElementById('sp-item-note').value,
            helpTextDe: document.getElementById('sp-item-help-de')?.value || '',
            helpTextEn: document.getElementById('sp-item-help-en')?.value || '',
            stories,
            linkedStorySlugs: stories.map((story) => story.slug),
            helpLinks: parsedLinks.links,
            demoCode: document.getElementById('sp-item-demo-code')?.value || '',
            blockerReason,
            blockerSince,
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
    document.getElementById('sp-item-status')?.addEventListener('change', () => updateBlockerFieldVisibility());
}

function openSprintDialog(sprint, isCustom) {
    closeHelpPanel();
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
        storiesEl.value = formatStoriesTextarea(sprint?.stories || []);
    }
    const linksEl = document.getElementById('sp-sprint-links');
    if (linksEl) {
        linksEl.value = formatLinksTextarea(sprint?.links || []);
    }
    document.getElementById('sp-sprint-dialog').showModal();
}

function updateBlockerFieldVisibility() {
    const status = document.getElementById('sp-item-status')?.value;
    const field = document.getElementById('sp-item-blocker-field');
    if (field) {
        field.hidden = status !== 'blocked';
    }
}

function openItemDialog(state) {
    closeHelpPanel();
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
        storiesEl.value = formatStoriesTextarea(state.item?.stories || []);
    }
    const linksEl = document.getElementById('sp-item-help-links');
    if (linksEl) {
        linksEl.value = formatLinksTextarea(state.item?.helpLinks || []);
    }
    const demoCodeEl = document.getElementById('sp-item-demo-code');
    if (demoCodeEl) {
        const demoCode = state.item?.demoCode || '';
        demoCodeEl.value = typeof demoCode === 'object'
            ? (demoCode.de || demoCode.en || '')
            : demoCode;
    }
    const blockerReasonEl = document.getElementById('sp-item-blocker-reason');
    if (blockerReasonEl) {
        blockerReasonEl.value = state.item?.blockerReason || '';
    }
    refreshAssigneeOptions(state.item?.assigneeId || '');
    updateBlockerFieldVisibility();
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

function bindStatusReport() {
    document.getElementById('sp-status-report-btn')?.addEventListener('click', () => {
        if (!lastRenderContext) {
            return;
        }
        const { instance, template, sprints, workspace, locale } = lastRenderContext;
        renderStatusReport(instance, template, sprints, workspace, locale);
        showStatusReport();
    });
    document.getElementById('sp-status-report-close')?.addEventListener('click', closeStatusReport);
    document.getElementById('sp-status-report-print')?.addEventListener('click', () => window.print());
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeStatusReport();
        }
    });
}

function showStatusReport() {
    const el = document.getElementById('sp-status-report');
    if (el) {
        el.hidden = false;
        el.setAttribute('aria-hidden', 'false');
    }
}

function closeStatusReport() {
    const el = document.getElementById('sp-status-report');
    if (el) {
        el.hidden = true;
        el.setAttribute('aria-hidden', 'true');
    }
}

/**
 * @param {import('../storage.js').SpPlanInstance} instance
 * @param {object|undefined} template
 * @param {ReturnType<typeof resolveSprints>} sprints
 * @param {import('../storage.js').SpWorkspaceRoot} workspace
 * @param {'de'|'en'} locale
 */
function renderStatusReport(instance, template, sprints, workspace, locale) {
    const body = document.getElementById('sp-status-report-body');
    if (!body) {
        return;
    }
    body.replaceChildren();

    const progress = calculateOverallProgress(sprints);
    const currentNumber = calculateCurrentSprintNumber(
        instance.startedAt,
        sprints.length || template?.duration || 1,
        template?.unit || 'week',
    );
    const currentSprint = sprints.find((sprint) => sprint.number === currentNumber);

    const title = document.createElement('h1');
    title.className = 'tools-page-title';
    title.textContent = instance.translations?.[locale]?.title
        || template?.locales?.[locale]?.title
        || instance.templateSlug;
    body.appendChild(title);

    body.appendChild(buildReportSection(spT('sp.report.progress'), () => {
        const p = document.createElement('p');
        p.textContent = `${progress.percent}% (${progress.done}/${progress.total})`;
        return [p];
    }));

    body.appendChild(buildReportSection(spT('sp.report.currentSprint'), () => {
        const p = document.createElement('p');
        p.textContent = currentSprint
            ? `#${currentSprint.number} — ${currentSprint.title} (${currentSprint.progress.percent}%)`
            : '—';
        return [p];
    }));

    const blockers = collectBlockers(sprints);
    body.appendChild(buildReportSection(spT('sp.report.blockers'), () => {
        if (!blockers.length) {
            return [buildEmptyParagraph()];
        }
        return [buildReportTable(
            [spT('sp.report.colSprint'), spT('sp.report.colItem'), spT('sp.report.colAssignee'), spT('sp.report.colReason')],
            blockers.map((blocker) => [
                `#${blocker.sprintNumber}`,
                blocker.item.label,
                resolveAssigneeName(blocker.item, workspace, locale) || spT('sp.report.unassigned'),
                blocker.item.blockerReason || '',
            ]),
        )];
    }));

    body.appendChild(buildReportSection(spT('sp.report.sprintProgress'), () => [
        buildReportTable(
            [spT('sp.report.colSprint'), spT('sp.field.status'), spT('sp.report.colTotal')],
            sprints.map((sprint) => [
                `#${sprint.number} ${sprint.title}`,
                `${sprint.progress.percent}%`,
                `${sprint.progress.done}/${sprint.progress.total}`,
            ]),
        ),
    ]));

    const requiredUnread = collectRequiredUnreadStories(sprints);
    body.appendChild(buildReportSection(spT('sp.report.requiredStories'), () => {
        if (!requiredUnread.length) {
            return [buildEmptyParagraph()];
        }
        return [buildReportTable(
            [spT('sp.report.colSprint'), spT('sp.report.colItem'), spT('sp.report.colStory')],
            requiredUnread.map((entry) => [`#${entry.sprintNumber}`, entry.itemLabel, entry.slug]),
        )];
    }));

    const tasksByPerson = collectTasksByPerson(sprints, workspace);
    body.appendChild(buildReportSection(spT('sp.report.tasksByPerson'), () => {
        if (!tasksByPerson.length) {
            return [buildEmptyParagraph()];
        }
        return [buildReportTable(
            [
                spT('sp.report.colPerson'),
                spT('sp.report.colOpen'),
                spT('sp.report.colBlocked'),
                spT('sp.report.colCompleted'),
                spT('sp.report.colTotal'),
            ],
            tasksByPerson.map((row) => [
                row.name,
                String(row.open),
                String(row.blocked),
                String(row.completed),
                String(row.total),
            ]),
        )];
    }));
}

/**
 * @param {string} titleText
 * @param {() => HTMLElement[]} buildContent
 */
function buildReportSection(titleText, buildContent) {
    const section = document.createElement('section');
    section.className = 'sp-report-section';
    const heading = document.createElement('h3');
    heading.textContent = titleText;
    section.appendChild(heading);
    for (const node of buildContent()) {
        section.appendChild(node);
    }
    return section;
}

/**
 * @param {string[]} headers
 * @param {string[][]} rows
 */
function buildReportTable(headers, rows) {
    const table = document.createElement('table');
    table.className = 'sp-report-table';
    const thead = document.createElement('thead');
    const headRow = document.createElement('tr');
    for (const header of headers) {
        const th = document.createElement('th');
        th.textContent = header;
        headRow.appendChild(th);
    }
    thead.appendChild(headRow);
    const tbody = document.createElement('tbody');
    for (const row of rows) {
        const tr = document.createElement('tr');
        for (const cell of row) {
            const td = document.createElement('td');
            td.textContent = cell;
            tr.appendChild(td);
        }
        tbody.appendChild(tr);
    }
    table.append(thead, tbody);
    return table;
}

function buildEmptyParagraph() {
    const p = document.createElement('p');
    p.textContent = spT('sp.report.empty');
    return p;
}

/**
 * @param {ReturnType<typeof resolveSprints>} sprints
 */
function collectRequiredUnreadStories(sprints) {
    const out = [];
    for (const sprint of sprints) {
        for (const item of [...sprint.tasks, ...sprint.deliverables]) {
            for (const story of item.stories || []) {
                if (story.required && !isStoryRead(story.slug)) {
                    out.push({
                        sprintNumber: sprint.number,
                        itemLabel: item.label,
                        slug: story.slug,
                    });
                }
            }
        }
    }
    return out;
}

/**
 * @param {ReturnType<typeof resolveSprints>} sprints
 * @param {import('../storage.js').SpWorkspaceRoot} workspace
 */
function collectTasksByPerson(sprints, workspace) {
    /** @type {Record<string, {open: number, blocked: number, completed: number, total: number}>} */
    const counts = {};
    const ensure = (id) => {
        if (!counts[id]) {
            counts[id] = { open: 0, blocked: 0, completed: 0, total: 0 };
        }
        return counts[id];
    };
    for (const sprint of sprints) {
        for (const item of [...sprint.tasks, ...sprint.deliverables]) {
            if (item.assigneeType !== 'person' || !item.assigneeId) {
                continue;
            }
            const bucket = ensure(item.assigneeId);
            bucket.total += 1;
            if (item.status === 'blocked') {
                bucket.blocked += 1;
            } else if (item.completed || item.status === 'completed') {
                bucket.completed += 1;
            } else {
                bucket.open += 1;
            }
        }
    }
    return Object.entries(counts)
        .map(([personId, bucket]) => ({
            name: workspace.people[personId]?.displayName || personId,
            ...bucket,
        }))
        .sort((a, b) => a.name.localeCompare(b.name));
}
