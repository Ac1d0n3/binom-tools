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
import { normalizeTeamIds } from '../trigram.js';
import { effortFacts } from '../time.js';
import {
    applySpI18n,
    planOwnershipLabel,
    planUrl,
    readTemplatesFromDom,
    showToast,
    spT,
    storageErrorMessage,
} from './helpers.js';
import { getLocale, withAppBasePath } from '../../locale.js';

/**
 * Shared overview/template card rendering and start-plan dialog.
 */
export function initOverviewPage({ templatesOnly = false } = {}) {
    const root = document.getElementById('sp-app');
    if (!root) {
        return;
    }

    if (!templatesOnly) {
        const params = new URLSearchParams(window.location.search);
        if (!params.has('list')) {
            const prefs = loadPreferences();
            const lastId = String(prefs.lastOpenedPlanId || '');
            if (lastId) {
                const { data } = loadWorkspace();
                if (data.instances[lastId]) {
                    window.location.replace(planUrl(lastId));
                    return;
                }
                savePreferences({ ...prefs, lastOpenedPlanId: null });
            }
        }
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

    const templateLandscapeFilter = document.getElementById('sp-template-landscape-filter');
    if (templateLandscapeFilter) {
        templateLandscapeFilter.value = String(prefs.templateLandscapeFilter || 'all');
        templateLandscapeFilter.addEventListener('change', () => {
            savePreferences({ ...loadPreferences(), templateLandscapeFilter: templateLandscapeFilter.value });
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

        if (templatesOnly) {
            renderTemplateCards(templates, locale, workspace);
        } else {
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

    syncTemplateLandscapeFilter(templates, locale);
    const filteredTemplates = filterTemplatesForCatalog(templates, locale);
    if (filteredTemplates.length === 0) {
        if (empty) {
            empty.hidden = false;
        }
        return;
    }

    for (const group of groupTemplatesForCatalog(filteredTemplates, locale)) {
        const section = document.createElement('section');
        section.className = 'sp-template-roadmap';
        const heading = document.createElement('div');
        heading.className = 'sp-template-roadmap__head';
        heading.innerHTML = `
            <div>
                <h3 class="sp-template-roadmap__title"></h3>
            </div>
            <p class="sp-template-roadmap__count"></p>
        `;
        heading.querySelector('.sp-template-roadmap__title').textContent = group.title;
        heading.querySelector('.sp-template-roadmap__count').textContent = spT('sp.template.roadmapCount', { count: group.templates.length });
        section.appendChild(heading);
        for (const track of group.tracks) {
            const trackSection = document.createElement('details');
            trackSection.className = 'sp-template-track';
            const trackTitle = document.createElement('summary');
            trackTitle.className = 'sp-template-track__title';
            trackTitle.innerHTML = `
                <span></span>
                <span class="sp-template-track__count"></span>
            `;
            trackTitle.querySelector('span').textContent = track.title;
            trackTitle.querySelector('.sp-template-track__count').textContent = spT('sp.template.roadmapCount', { count: track.templates.length });
            const grid = document.createElement('div');
            grid.className = 'sp-card-grid sp-card-grid--roadmap';
            for (const template of track.templates) {
                grid.appendChild(renderTemplateCard(template, locale, workspace, templates));
            }
            trackSection.append(trackTitle, grid);
            section.appendChild(trackSection);
        }
        container.appendChild(section);
    }
}

function syncTemplateLandscapeFilter(templates, locale) {
    const select = document.getElementById('sp-template-landscape-filter');
    if (!select) {
        return;
    }
    const current = select.value || 'all';
    const tracks = new Map();
    for (const template of templates) {
        const id = templateTrackId(template, locale);
        tracks.set(id, templateTrackTitle(template, locale));
    }
    select.innerHTML = '';
    const all = document.createElement('option');
    all.value = 'all';
    all.textContent = spT('sp.template.filterAll');
    select.appendChild(all);
    for (const [id, title] of Array.from(tracks.entries()).sort((a, b) => a[1].localeCompare(b[1]))) {
        const option = document.createElement('option');
        option.value = id;
        option.textContent = title;
        select.appendChild(option);
    }
    select.value = tracks.has(current) ? current : 'all';
}

function filterTemplatesForCatalog(templates, locale) {
    const selected = document.getElementById('sp-template-landscape-filter')?.value || 'all';
    if (selected === 'all') {
        return templates;
    }
    return templates.filter((template) => templateTrackId(template, locale) === selected);
}

function groupTemplatesForCatalog(templates, locale) {
    const buckets = new Map();
    for (const template of templates) {
        const bucket = templateDurationBucket(template);
        if (!buckets.has(bucket.id)) {
            buckets.set(bucket.id, {
                ...bucket,
                tracks: new Map(),
                templates: [],
            });
        }
        const group = buckets.get(bucket.id);
        group.templates.push(template);
        const trackId = templateTrackId(template, locale);
        if (!group.tracks.has(trackId)) {
            group.tracks.set(trackId, {
                id: trackId,
                title: templateTrackTitle(template, locale),
                templates: [],
            });
        }
        group.tracks.get(trackId).templates.push(template);
    }

    return Array.from(buckets.values()).map((group) => ({
        ...group,
        tracks: Array.from(group.tracks.values()).map((track) => ({
            ...track,
            templates: track.templates.sort(compareRoadmapTemplates),
        })).sort((a, b) => a.title.localeCompare(b.title)),
    })).sort((a, b) => a.order - b.order || a.title.localeCompare(b.title));
}

function templateDurationBucket(template) {
    const unit = String(template.unit || 'week');
    const duration = Number(template.duration || template.sprintCount || template.sprints?.length || 0);
    if (unit === 'week' && duration >= 10) {
        return { id: 'quarter', order: 1, title: spT('sp.template.bucketQuarter') };
    }
    if ((unit === 'week' && duration >= 3) || unit === 'month') {
        return { id: 'month', order: 2, title: spT('sp.template.bucketMonth') };
    }
    if (unit === 'week' || unit === 'day') {
        return { id: 'week', order: 3, title: spT('sp.template.bucketWeek') };
    }
    return { id: 'other', order: 4, title: spT('sp.template.bucketOther') };
}

function templateTrackId(template, locale) {
    return String(template.roadmapTrack || template.roadmapFamily || template.category || localizedRoadmapTitle(template, locale) || 'other');
}

function templateTrackTitle(template, locale) {
    return localizedRoadmapTrackTitle(template, locale)
        || localizedRoadmapTitle(template, locale)
        || template.category
        || spT('sp.template.trackOther');
}

function compareRoadmapTemplates(a, b) {
    const phaseA = Number.isFinite(Number(a.roadmapPhase)) ? Number(a.roadmapPhase) : 999;
    const phaseB = Number.isFinite(Number(b.roadmapPhase)) ? Number(b.roadmapPhase) : 999;
    if (phaseA !== phaseB) {
        return phaseA - phaseB;
    }
    return String(a.roadmapOption || a.slug).localeCompare(String(b.roadmapOption || b.slug));
}

function roadmapFallbackTitle(template, locale) {
    const title = template.locales?.[locale]?.title || template.locales?.en?.title || template.slug;
    return title.replace(/\s+[—-]\s+.*/, '');
}

function renderTemplateCard(template, locale, workspace, templates = []) {
    const title = template.locales?.[locale]?.title || template.locales?.en?.title || template.slug;
    const description = template.locales?.[locale]?.description || template.locales?.en?.description || '';
    const card = document.createElement('article');
    card.className = 'sp-card';
    const isUser = Boolean(template.userTemplate || template.id?.startsWith?.('utpl_'));
    card.innerHTML = `
        <div class="sp-card__head">
            <h3 class="sp-card__title"></h3>
            <span class="sp-card__phase" hidden></span>
        </div>
        <p class="sp-card__roadmap" hidden></p>
        <p class="sp-card__desc"></p>
        <p class="sp-card__meta"></p>
        <div class="sp-card__actions"></div>
    `;
    card.querySelector('.sp-card__title').textContent = title;
    card.querySelector('.sp-card__desc').textContent = description;
    const phase = card.querySelector('.sp-card__phase');
    if (template.roadmapPhase) {
        phase.hidden = false;
        phase.textContent = spT('sp.template.phase', { phase: template.roadmapPhase });
    }
    const roadmap = card.querySelector('.sp-card__roadmap');
    const roadmapParts = [
        localizedRoadmapOption(template, locale) ? spT('sp.template.option', { option: localizedRoadmapOption(template, locale) }) : '',
        roadmapFollowsLabel(template, templates, locale),
    ].filter(Boolean);
    if (roadmapParts.length > 0) {
        roadmap.hidden = false;
        roadmap.textContent = roadmapParts.join(' · ');
    }
    card.querySelector('.sp-card__meta').textContent = [
        spT('sp.card.duration', { count: template.duration || template.sprintCount || template.sprints?.length || 0 }),
        ...effortFacts(template.sprints || [], template, spT),
        isUser ? spT('sp.creator.userTemplateBadge') : '',
    ].filter(Boolean).join(' · ');
    const actions = card.querySelector('.sp-card__actions');
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'tools-btn tools-btn--primary';
    btn.textContent = spT('sp.action.startPlan');
    btn.addEventListener('click', () => openStartDialog(template, workspace));
    actions.appendChild(btn);

    if (isUser && isLoggedIn()) {
        const edit = document.createElement('a');
        edit.className = 'tools-btn tools-btn--secondary';
        edit.href = createPlanUrl(template.id);
        edit.textContent = spT('sp.action.edit');
        actions.appendChild(edit);

        const del = document.createElement('button');
        del.type = 'button';
        del.className = 'tools-btn tools-btn--secondary';
        del.textContent = spT('sp.action.delete');
        del.addEventListener('click', async () => {
            if (!window.confirm(spT('sp.creator.confirmDeleteTemplate'))) {
                return;
            }
            try {
                const { deleteUserTemplate } = await import('../user-templates.js');
                await deleteUserTemplate(template.id);
                showToast(spT('sp.toast.deleted'));
                window.location.reload();
            } catch {
                showToast(spT('sp.creator.templateSaveFailed'));
            }
        });
        actions.appendChild(del);
    }

    return card;
}

function localizedRoadmapTitle(template, locale) {
    return template.locales?.[locale]?.roadmapTitle || template.locales?.en?.roadmapTitle || template.roadmapTitle || '';
}

function localizedRoadmapTrackTitle(template, locale) {
    return template.locales?.[locale]?.roadmapTrackTitle || template.locales?.en?.roadmapTrackTitle || template.roadmapTrackTitle || '';
}

function localizedRoadmapOption(template, locale) {
    return template.locales?.[locale]?.roadmapOption || template.locales?.en?.roadmapOption || template.roadmapOption || '';
}

function roadmapFollowsLabel(template, templates, locale) {
    const follows = Array.isArray(template.roadmapFollows) ? template.roadmapFollows : [];
    if (follows.length === 0) {
        return '';
    }
    const titles = follows.map((slug) => {
        const found = templates.find((entry) => entry.slug === slug);
        return found?.locales?.[locale]?.title || found?.locales?.en?.title || slug;
    });
    return spT('sp.template.follows', { titles: titles.join(', ') });
}

function createPlanUrl(templateId = '') {
    const root = document.getElementById('sp-app');
    const base = root?.dataset?.createPlanUrl || withAppBasePath('/sprint-planner/create');
    if (!templateId) {
        return base;
    }
    const sep = base.includes('?') ? '&' : '?';
    return `${base}${sep}template=${encodeURIComponent(templateId)}`;
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
        const template = templateBySlug[instance.templateSlug] || instance.templateSnapshot;
        const sprints = template ? resolveSprints(template, instance, locale) : [];
        const progress = calculateOverallProgress(sprints);
        const current = calculateCurrentSprintNumber(
            instance.startedAt,
            sprints.length || template?.duration || 1,
            template?.unit || 'week',
        );
        const cardData = {
            instance, template, sprints, progress, current, locale, workspace,
        };

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
            const ownerId = String(instance.ownerUserId || '');
            const participants = Array.isArray(instance.participantIds) ? instance.participantIds.map(String) : [];
            if (
                !activeId
                || (
                    ownerId !== String(activeId)
                    && !participants.includes(String(activeId))
                    && !sprintsContainAssignee(sprints, activeId)
                )
            ) {
                continue;
            }
        }
        if (filter === 'team') {
            if (!normalizeTeamIds(instance).length) {
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

function createPlanCard({
    instance, template, sprints, progress, current, locale, workspace,
}) {
    const title = instance.translations?.[locale]?.title
        || instance.translations?.en?.title
        || template?.locales?.[locale]?.title
        || instance.templateSlug;
    const description = instance.translations?.[locale]?.description
        || template?.locales?.[locale]?.description
        || '';
    const teamIds = normalizeTeamIds(instance);
    const teamName = teamIds.length
        ? teamIds.map((id) => {
            const team = workspace.teams[id];
            return team ? localizedText(team.name, locale) : id;
        }).join(', ')
        : '—';

    const card = document.createElement('article');
    card.className = 'sp-card';
    card.innerHTML = `
        <h3 class="sp-card__title"></h3>
        <p class="sp-card__desc"></p>
        <ul class="sp-card__facts">
            <li data-fact="owner"></li>
            <li data-fact="duration"></li>
            <li data-fact="effort"></li>
            <li data-fact="staffing"></li>
            <li data-fact="capacity"></li>
            <li data-fact="scenarios"></li>
            <li data-fact="progress"></li>
            <li data-fact="current"></li>
            <li data-fact="status"></li>
            <li data-fact="team"></li>
            <li data-fact="updated"></li>
        </ul>
        <div class="sp-card__actions">
            <a class="tools-btn tools-btn--primary" data-open></a>
        </div>
    `;
    card.querySelector('.sp-card__title').textContent = title;
    card.querySelector('.sp-card__desc').textContent = description;
    card.querySelector('[data-fact="owner"]').textContent = planOwnershipLabel(instance, workspace);
    card.querySelector('[data-fact="duration"]').textContent = spT('sp.card.duration', {
        count: template?.duration || 0,
    });
    const facts = effortFacts(sprints, template || {}, spT);
    card.querySelector('[data-fact="effort"]').textContent = facts[0] || '';
    card.querySelector('[data-fact="staffing"]').textContent = facts[1] || '';
    card.querySelector('[data-fact="capacity"]').textContent = facts[2] || '';
    card.querySelector('[data-fact="scenarios"]').textContent = facts.slice(3).join(' · ');
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

function sprintsContainAssignee(sprints, assigneeId) {
    const needle = String(assigneeId || '');
    if (!needle) {
        return false;
    }
    for (const sprint of sprints || []) {
        for (const kind of /** @type {const} */ (['tasks', 'deliverables'])) {
            for (const item of sprint[kind] || []) {
                if (String(item?.assigneeId || '') === needle) {
                    return true;
                }
            }
        }
    }

    return false;
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
        const teamIds = [...document.querySelectorAll('#sp-start-teams input:checked')].map((el) => el.value);
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
            teamIds,
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

    const teamsHost = document.getElementById('sp-start-teams');
    const defaultTeamId = workspace.workspace.defaultTeamId || '';
    const selectedTeams = () => [...document.querySelectorAll('#sp-start-teams input:checked')].map((el) => el.value);

    const participants = document.getElementById('sp-start-participants');
    const renderParticipants = (preselectIds = []) => {
        const selected = new Set(preselectIds.map(String));
        if (workspace.workspace.activePersonId) {
            selected.add(String(workspace.workspace.activePersonId));
        }
        participants.innerHTML = '';
        for (const person of listPeople()) {
            const label = document.createElement('label');
            label.className = 'sp-check';
            const input = document.createElement('input');
            input.type = 'checkbox';
            input.value = person.id;
            input.checked = selected.has(person.id);
            label.append(input, document.createTextNode(` ${person.displayName}`));
            participants.appendChild(label);
        }
    };

    const syncParticipantsFromTeams = () => {
        const memberSet = new Set();
        for (const tid of selectedTeams()) {
            for (const mid of listTeams().find((t) => t.id === tid)?.memberIds || []) {
                memberSet.add(String(mid));
            }
        }
        renderParticipants([...memberSet]);
    };

    if (teamsHost) {
        teamsHost.innerHTML = '';
        for (const team of listTeams()) {
            const label = document.createElement('label');
            label.className = 'sp-check';
            const input = document.createElement('input');
            input.type = 'checkbox';
            input.value = team.id;
            input.checked = team.id === defaultTeamId;
            input.addEventListener('change', syncParticipantsFromTeams);
            label.append(input, document.createTextNode(` ${localizedText(team.name, locale)}`));
            teamsHost.appendChild(label);
        }
    }

    syncParticipantsFromTeams();

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
