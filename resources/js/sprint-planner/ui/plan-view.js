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

    li.append(check, main, edit);

    if (item.custom) {
        const del = document.createElement('button');
        del.type = 'button';
        del.className = 'tools-btn tools-btn--secondary tools-btn--small';
        del.textContent = spT('sp.action.delete');
        del.addEventListener('click', () => {
            deleteCustomItem(instance.id, sprint.id, item.kind, item.id, item.statusKey);
            rerender();
        });
        li.appendChild(del);
    }

    return li;
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
        const read = isAccountsMode()
            ? readSet.has(slug) || isPlaybookRead(slug)
            : isPlaybookRead(slug);
        link.dataset.read = read ? '1' : '0';
        link.textContent = `${slug}${read ? ` · ${spT('sp.story.read')}` : ` · ${spT('sp.story.unread')}`}`;
        wrap.appendChild(link);
    }
    return wrap;
}

function statusKeyToLabel(status) {
    return status === 'in_progress' ? 'inProgress' : status;
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
        const data = {
            titleDe: document.getElementById('sp-sprint-title-de').value,
            titleEn: document.getElementById('sp-sprint-title-en').value,
            goalDe: document.getElementById('sp-sprint-goal-de').value,
            goalEn: document.getElementById('sp-sprint-goal-en').value,
            number: Number(document.getElementById('sp-sprint-position').value) || undefined,
            notes: document.getElementById('sp-sprint-notes-enabled').checked,
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
