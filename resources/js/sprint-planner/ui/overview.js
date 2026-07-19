import {
    applyImport,
    buildWorkspaceExport,
    downloadJson,
    validateImportPayload,
} from '../export-import.js';
import { startInstanceFromTemplate } from '../instance-manager.js';
import { isAccountsMode, isLoggedIn, readAccountsBootstrap, usesServerPlans } from '../accounts-bridge.js';
import { listPeople, listTeams, localizedText } from '../people-teams.js';
import { calculateCurrentSprintNumber, calculateOverallProgress, resolveSprints } from '../progress.js';
import { loadPreferences, loadWorkspace, savePreferences } from '../storage.js';
import {
    applySpI18n,
    fillSelect,
    planOwnershipLabel,
    planUrl,
    readTemplatesFromDom,
    showToast,
    spT,
    storageErrorMessage,
} from './helpers.js';
import { getLocale } from '../../locale.js';

/**
 * Shared overview/template card rendering and start-plan dialog.
 */
export function initOverviewPage({ templatesOnly = false } = {}) {
    const root = document.getElementById('sp-app');
    if (!root) {
        return;
    }

    applySpI18n(root);
    window.addEventListener('binom-tools:locale', () => {
        applySpI18n(root);
        render();
    });

    const prefs = loadPreferences();
    const filterSelect = document.getElementById('sp-overview-filter');
    if (filterSelect) {
        filterSelect.value = String(prefs.overviewFilter || 'all');
        filterSelect.addEventListener('change', () => {
            savePreferences({ ...loadPreferences(), overviewFilter: filterSelect.value });
            render();
        });
    }

    document.getElementById('sp-export-workspace')?.addEventListener('click', () => {
        const data = buildWorkspaceExport();
        downloadJson(data, `bn-tools-sprint-workspace-${new Date().toISOString().slice(0, 10)}.json`);
        showToast(spT('sp.toast.exported'));
    });

    const importBtn = document.getElementById('sp-import-workspace');
    const importFile = document.getElementById('sp-import-file');
    importBtn?.addEventListener('click', () => importFile?.click());
    importFile?.addEventListener('change', async () => {
        const file = importFile.files?.[0];
        if (!file) {
            return;
        }
        try {
            const raw = JSON.parse(await file.text());
            const validated = validateImportPayload(raw);
            if (!validated.ok) {
                showToast(storageErrorMessage(validated.error));
                return;
            }
            const mode = window.prompt(spT('sp.import.modePrompt'), 'merge');
            if (!mode || mode === 'cancel') {
                return;
            }
            const normalized = ['replace', 'merge', 'new-ids'].includes(mode) ? mode : 'merge';
            const result = applyImport(normalized, raw);
            if (!result.ok) {
                showToast(storageErrorMessage(result.error));
                return;
            }
            showToast(spT('sp.toast.imported'));
            render();
        } catch {
            showToast(spT('sp.error.importInvalid'));
        } finally {
            importFile.value = '';
        }
    });

    setupStartDialog(render);
    render();

    function render() {
        const locale = getLocale();
        const templates = readTemplatesFromDom();
        const { data: workspace, ok, error } = loadWorkspace();
        if (!ok && error) {
            showToast(storageErrorMessage(error));
        }

        renderTemplateCards(templates, locale, workspace);
        if (!templatesOnly) {
            renderPlanCards(workspace, templates, locale);
        }
    }
}

function renderTemplateCards(templates, locale, workspace) {
    const container = document.getElementById('sp-template-cards');
    const empty = document.getElementById('sp-templates-empty');
    if (!container) {
        return;
    }
    container.innerHTML = '';
    if (templates.length === 0) {
        if (empty) {
            empty.hidden = false;
        }
        return;
    }
    if (empty) {
        empty.hidden = true;
    }

    for (const template of templates) {
        const title = template.locales?.[locale]?.title || template.locales?.en?.title || template.slug;
        const description = template.locales?.[locale]?.description || template.locales?.en?.description || '';
        const card = document.createElement('article');
        card.className = 'sp-card';
        card.innerHTML = `
            <h3 class="sp-card__title"></h3>
            <p class="sp-card__desc"></p>
            <p class="sp-card__meta"></p>
            <button type="button" class="tools-btn tools-btn--primary" data-start-slug=""></button>
        `;
        card.querySelector('.sp-card__title').textContent = title;
        card.querySelector('.sp-card__desc').textContent = description;
        card.querySelector('.sp-card__meta').textContent = spT('sp.card.duration', {
            count: template.duration || template.sprintCount || 0,
        });
        const btn = card.querySelector('button');
        btn.dataset.startSlug = template.slug;
        btn.textContent = spT('sp.action.startPlan');
        btn.addEventListener('click', () => openStartDialog(template, workspace));
        container.appendChild(card);
    }
}

function renderPlanCards(workspace, templates, locale) {
    const filter = document.getElementById('sp-overview-filter')?.value || 'all';
    const activeHost = document.getElementById('sp-active-plans');
    const archiveHost = document.getElementById('sp-archived-plans');
    const activeEmpty = document.getElementById('sp-active-empty');
    const archiveEmpty = document.getElementById('sp-archive-empty');
    if (!activeHost || !archiveHost) {
        return;
    }

    activeHost.innerHTML = '';
    archiveHost.innerHTML = '';

    const instances = Object.values(workspace.instances);
    const templateBySlug = Object.fromEntries(templates.map((t) => [t.slug, t]));

    const active = [];
    const archived = [];

    for (const instance of instances) {
        const template = templateBySlug[instance.templateSlug];
        const sprints = template ? resolveSprints(template, instance, locale) : [];
        const progress = calculateOverallProgress(sprints);
        const current = calculateCurrentSprintNumber(
            instance.startedAt,
            sprints.length || template?.duration || 1,
            template?.unit || 'week',
        );
        const cardData = { instance, template, progress, current, locale, workspace };

        if (instance.archived || instance.status === 'archived') {
            if (filter === 'all' || filter === 'archived') {
                archived.push(cardData);
            }
            continue;
        }

        if (filter === 'archived') {
            continue;
        }
        if (filter === 'completed' && progress.percent < 100 && instance.status !== 'completed') {
            continue;
        }
        if (filter === 'mine') {
            const activeId = workspace.workspace.activePersonId;
            if (!activeId || !instance.participantIds.includes(activeId)) {
                continue;
            }
        }
        if (filter === 'team') {
            if (!instance.teamId) {
                continue;
            }
        }
        if (filter === 'completed') {
            // already filtered incomplete above when needed
        }
        active.push(cardData);
    }

    for (const item of active.sort((a, b) => b.instance.updatedAt.localeCompare(a.instance.updatedAt))) {
        activeHost.appendChild(createPlanCard(item));
    }
    for (const item of archived.sort((a, b) => b.instance.updatedAt.localeCompare(a.instance.updatedAt))) {
        archiveHost.appendChild(createPlanCard(item));
    }

    if (activeEmpty) {
        if (active.length > 0) {
            activeEmpty.hidden = true;
        } else {
            activeEmpty.hidden = false;
            activeEmpty.textContent = isAccountsMode() && ! isLoggedIn()
                ? spT('sp.empty.activePlansDemo')
                : spT('sp.empty.activePlans');
        }
    }
    if (archiveEmpty) {
        archiveEmpty.hidden = archived.length > 0;
    }
}

function createPlanCard({ instance, template, progress, current, locale, workspace }) {
    const title = instance.translations?.[locale]?.title
        || instance.translations?.en?.title
        || template?.locales?.[locale]?.title
        || instance.templateSlug;
    const description = instance.translations?.[locale]?.description
        || template?.locales?.[locale]?.description
        || '';
    const team = instance.teamId ? workspace.teams[instance.teamId] : null;
    const teamName = team ? localizedText(team.name, locale) : '—';

    const card = document.createElement('article');
    card.className = 'sp-card';
    card.innerHTML = `
        <h3 class="sp-card__title"></h3>
        <p class="sp-card__desc"></p>
        <ul class="sp-card__facts">
            <li data-fact="owner"></li>
            <li data-fact="duration"></li>
            <li data-fact="progress"></li>
            <li data-fact="current"></li>
            <li data-fact="status"></li>
            <li data-fact="team"></li>
            <li data-fact="updated"></li>
        </ul>
        <a class="tools-btn tools-btn--primary" data-open></a>
    `;
    card.querySelector('.sp-card__title').textContent = title;
    card.querySelector('.sp-card__desc').textContent = description;
    card.querySelector('[data-fact="owner"]').textContent = planOwnershipLabel(instance, workspace);
    card.querySelector('[data-fact="duration"]').textContent = spT('sp.card.duration', {
        count: template?.duration || 0,
    });
    card.querySelector('[data-fact="progress"]').textContent = spT('sp.card.progress', {
        percent: progress.percent,
    });
    card.querySelector('[data-fact="current"]').textContent = spT('sp.card.currentSprint', {
        number: current,
    });
    card.querySelector('[data-fact="status"]').textContent = spT(
        instance.archived ? 'sp.status.archived' : 'sp.status.active',
    );
    card.querySelector('[data-fact="team"]').textContent = teamName;
    card.querySelector('[data-fact="updated"]').textContent = new Date(instance.updatedAt).toLocaleString(locale);
    if (instance.passwordHash) {
        const lock = document.createElement('li');
        lock.textContent = spT('sp.password.lockedBadge');
        card.querySelector('.sp-card__facts').appendChild(lock);
    }
    if (instance.ephemeral) {
        const demo = document.createElement('li');
        demo.textContent = spT('sp.ownership.demo');
        card.querySelector('.sp-card__facts').appendChild(demo);
    } else if (isAccountsMode() && isLoggedIn()) {
        const me = readAccountsBootstrap().accountUser?.id;
        const ownership = document.createElement('li');
        ownership.textContent = me && instance.ownerUserId === me
            ? spT('sp.ownership.mine')
            : spT('sp.ownership.shared');
        card.querySelector('.sp-card__facts').appendChild(ownership);
    }
    const link = card.querySelector('[data-open]');
    link.href = planUrl(instance.id);
    link.textContent = spT('sp.action.open');
    return card;
}

function setupStartDialog(onStarted) {
    const form = document.getElementById('sp-start-form');
    const dialog = document.getElementById('sp-start-dialog');
    if (!form || !dialog) {
        return;
    }

    dialog.addEventListener('close', () => {
        const mode = dialog.returnValue;
        if (mode !== 'confirm' && mode !== 'demo') {
            return;
        }
        const slug = document.getElementById('sp-start-slug')?.value;
        const startedAt = document.getElementById('sp-start-date')?.value;
        const teamId = document.getElementById('sp-start-team')?.value || null;
        const participants = [...document.querySelectorAll('#sp-start-participants input:checked')].map(
            (el) => el.value,
        );
        const template = readTemplatesFromDom().find((item) => item.slug === slug);
        if (!template || !startedAt) {
            return;
        }

        const wantSave = mode === 'confirm' && usesServerPlans();
        if (mode === 'confirm' && isAccountsMode() && ! isLoggedIn()) {
            const loginUrl = readAccountsBootstrap().loginUrl;
            if (loginUrl) {
                window.location.href = loginUrl;
                return;
            }
        }

        const result = startInstanceFromTemplate(template, {
            startedAt,
            teamId,
            participantIds: participants,
            ephemeral: ! wantSave,
        });
        if (!result.ok) {
            showToast(storageErrorMessage(result.error));
            return;
        }
        showToast(result.instance.ephemeral ? spT('sp.toast.demoStarted') : spT('sp.toast.started'));
        window.location.href = planUrl(result.instance.id);
        onStarted?.();
    });
}

function openStartDialog(template, workspace) {
    const dialog = document.getElementById('sp-start-dialog');
    if (!dialog) {
        return;
    }
    const locale = getLocale();
    document.getElementById('sp-start-slug').value = template.slug;
    document.getElementById('sp-start-date').value = new Date().toISOString().slice(0, 10);

    const teamSelect = document.getElementById('sp-start-team');
    fillSelect(
        teamSelect,
        listTeams().map((team) => ({ id: team.id, label: localizedText(team.name, locale) })),
        workspace.workspace.defaultTeamId || '',
        true,
    );

    const participants = document.getElementById('sp-start-participants');
    participants.innerHTML = '';
    for (const person of listPeople()) {
        const label = document.createElement('label');
        label.className = 'sp-check';
        const input = document.createElement('input');
        input.type = 'checkbox';
        input.value = person.id;
        if (workspace.workspace.activePersonId === person.id) {
            input.checked = true;
        }
        label.append(input, document.createTextNode(` ${person.displayName}`));
        participants.appendChild(label);
    }

    const saveBtn = document.getElementById('sp-start-save');
    const demoBtn = document.getElementById('sp-start-demo');
    if (usesServerPlans()) {
        if (saveBtn) {
            saveBtn.hidden = false;
            saveBtn.textContent = spT('sp.action.startSave');
        }
        if (demoBtn) {
            demoBtn.hidden = false;
        }
    } else {
        if (saveBtn) {
            // Guest / accounts off: primary starts a local (demo) plan.
            saveBtn.hidden = false;
            saveBtn.value = 'demo';
            saveBtn.textContent = isAccountsMode()
                ? spT('sp.action.startDemo')
                : spT('sp.action.start');
        }
        if (demoBtn) {
            demoBtn.hidden = true;
        }
    }

    dialog.showModal();
}
