import { getLocale } from '../../locale.js';
import {
    isAccountsMode,
    readAccountsBootstrap,
    uploadAttachmentToServer,
    usesServerPlans,
} from '../accounts-bridge.js';
import {
    createFileAttachmentMeta,
    createLinkAttachment,
    normalizeAttachments,
    validateAttachmentFile,
} from '../attachments.js';
import {
    deleteLocalBlob,
    putLocalBlob,
    resolveAttachmentHref,
} from '../attachment-store.js';
import {
    readTableEditor,
    renderItemTableReadonly,
    renderTableEditor,
} from '../item-table.js';
import {
    formatExternalLinksTextarea,
    parseExternalLinksTextarea,
    resolveExternalHelpHref,
} from '../external-links.js';
import { filterSprints, hasActiveItemFilters, normalizePlanFilters } from '../filters.js';
import {
    addCustomItem,
    addCustomSprint,
    assignItem,
    claimAllUnassigned,
    claimItem,
    deleteCustomItem,
    deleteCustomSprint,
    duplicateSprint,
    moveSprint,
    resetSprint,
    setFieldValue,
    setSprintNote,
    toggleCompleted,
    updateCustomOrOverrideSprint,
    updateInstance,
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
} from './help-panel.js';

const noteTimers = {};
let sprintDialogState = { sprint: null, isCustom: false };
let itemDialogState = {};
/** @type {{sprintId: string, kind: string, statusKey: string, custom: boolean}|null} */
let assignDialogState = null;
/** @type {import('../attachments.js').SpAttachment[]} */
let dialogAttachments = [];
let bound = false;
/** @type {'executive'|'detailed'|'documentation'} */
let reportMode = 'executive';
/** @type {{instance: object, template: object|undefined, sprints: object[], workspace: object, locale: 'de'|'en'}|null} */
let lastRenderContext = null;
/** @type {Set<string>} */
const healedIdentityPlanIds = new Set();
/** @type {Set<string>} */
const strippedTemplateOverridePlanIds = new Set();

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
        bindClaimAll(instanceId);
        bindTemplateRecover(instanceId, render);
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

    // If the page bootstrap lost identity but local cache / merge restored it, write back once.
    const bootstrapPlan = (readAccountsBootstrap().serverPlans || [])
        .find((plan) => plan && String(plan.id) === String(instanceId));
    if (
        !healedIdentityPlanIds.has(String(instanceId))
        && bootstrapPlan
        && !String(bootstrapPlan.templateSlug || '').trim()
        && String(instance.templateSlug || '').trim()
    ) {
        healedIdentityPlanIds.add(String(instanceId));
        updateInstance(instanceId, (plan) => {
            plan.templateSlug = instance.templateSlug;
            if (instance.templateSnapshot) {
                plan.templateSnapshot = instance.templateSnapshot;
            }
            if (instance.translations) {
                plan.translations = instance.translations;
            }
            if (instance.startedAt) {
                plan.startedAt = instance.startedAt;
            }
        });
    }

    // Older plan edits stored help/labels in overrides and forced DE=EN — strip once.
    if (!strippedTemplateOverridePlanIds.has(String(instanceId))) {
        strippedTemplateOverridePlanIds.add(String(instanceId));
        const overrides = instance.itemOverrides || {};
        const dirtyKeys = Object.keys(overrides).filter((key) => {
            const row = overrides[key];
            return row && (
                row.helpText !== undefined
                || row.helpLinks !== undefined
                || row.demoCode !== undefined
                || row.stories !== undefined
                || row.linkedStorySlugs !== undefined
                || row.label !== undefined
            );
        });
        if (dirtyKeys.length > 0) {
            updateInstance(instanceId, (plan) => {
                for (const key of dirtyKeys) {
                    const row = plan.itemOverrides?.[key];
                    if (!row) {
                        continue;
                    }
                    delete row.helpText;
                    delete row.helpLinks;
                    delete row.demoCode;
                    delete row.stories;
                    delete row.linkedStorySlugs;
                    delete row.label;
                }
            });
            Object.assign(instance, loadWorkspace().data.instances[instanceId] || instance);
        }
    }

    const repairedSlug = repairTemplateSlug(instance, templates);
    if (repairedSlug && repairedSlug !== instance.templateSlug) {
        instance.templateSlug = repairedSlug;
        const snapshot = templates.find((item) => item.slug === repairedSlug) || instance.templateSnapshot;
        // Persist repair so later assign/sync keeps the slug.
        updateInstance(instanceId, (plan) => {
            plan.templateSlug = repairedSlug;
            if (snapshot && !plan.templateSnapshot) {
                plan.templateSnapshot = structuredClone(snapshot);
            }
            if ((!plan.translations || Object.keys(plan.translations).length === 0) && snapshot?.locales) {
                plan.translations = {
                    de: {
                        title: snapshot.locales?.de?.title || repairedSlug,
                        description: snapshot.locales?.de?.description || '',
                    },
                    en: {
                        title: snapshot.locales?.en?.title || repairedSlug,
                        description: snapshot.locales?.en?.description || '',
                    },
                };
            }
            if (!plan.startedAt) {
                plan.startedAt = new Date().toISOString().slice(0, 10);
            }
        });
        if (snapshot) {
            instance.templateSnapshot = snapshot;
        }
    }

    // Prefer live catalog, then snapshot (self-contained plans).
    const template = templates.find((item) => item.slug === instance.templateSlug)
        || (instance.templateSnapshot?.sprints ? instance.templateSnapshot : null)
        || null;

    const templateMissingEl = document.getElementById('sp-template-missing');
    const templateMissingText = document.getElementById('sp-template-missing-text');
    if (templateMissingEl) {
        templateMissingEl.hidden = Boolean(template);
        if (!template) {
            if (templateMissingText) {
                templateMissingText.textContent = spT('sp.error.templateMissing', {
                    slug: instance.templateSlug || '—',
                });
            }
            fillRecoverTemplateSelect(templates, locale);
        }
    }

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
    renderUnassignedBanner(sprints, workspace);
    renderSprints(filtered, currentNumber, instance, template, locale, workspace);
    renderFilterEmpty(filtered, sprints, filters);

    lastRenderContext = { instance, template, sprints, workspace, locale };
}

/**
 * @param {ReturnType<typeof filterSprints>} filtered
 * @param {ReturnType<typeof resolveSprints>} allSprints
 * @param {ReturnType<typeof normalizePlanFilters>} filters
 */
function renderFilterEmpty(filtered, allSprints, filters) {
    const empty = document.getElementById('sp-filter-empty');
    if (!empty) {
        return;
    }
    const hasItems = allSprints.some((s) => s.tasks.length + s.deliverables.length > 0);
    const normalized = normalizePlanFilters(filters);
    const show = hasItems && filtered.length === 0 && hasActiveItemFilters(filters);
    empty.hidden = !show;
    if (show && normalized.myTasks && normalized.unassigned) {
        empty.textContent = spT('sp.filter.emptyMyAndUnassigned');
    } else if (show) {
        empty.textContent = spT('sp.filter.empty');
    }
}

/**
 * @param {ReturnType<typeof resolveSprints>} sprints
 * @param {import('../storage.js').SpWorkspaceRoot} workspace
 */
function renderUnassignedBanner(sprints, workspace) {
    const banner = document.getElementById('sp-unassigned-banner');
    const text = document.getElementById('sp-unassigned-banner-text');
    const claimBtn = document.getElementById('sp-claim-all-btn');
    if (!banner) {
        return;
    }
    let total = 0;
    let unassigned = 0;
    for (const sprint of sprints) {
        for (const item of [...sprint.tasks, ...sprint.deliverables]) {
            total += 1;
            if (!item.assigneeId) {
                unassigned += 1;
            }
        }
    }
    const personId = resolveClaimPersonId(workspace);
    const show = total > 0 && unassigned > 0;
    banner.hidden = !show;
    if (show && text) {
        text.textContent = personId
            ? spT('sp.unassigned.bannerCount', { count: unassigned, total })
            : spT('sp.unassigned.needActivePerson');
    }
    if (claimBtn) {
        claimBtn.disabled = !personId || unassigned === 0;
        claimBtn.hidden = !show;
    }
}

/**
 * Person to assign when claiming (active person, else signed-in account).
 * @param {import('../storage.js').SpWorkspaceRoot} workspace
 * @returns {string|null}
 */
function resolveClaimPersonId(workspace) {
    const active = workspace?.workspace?.activePersonId
        ? String(workspace.workspace.activePersonId)
        : '';
    if (active) {
        return active;
    }
    const accountId = readAccountsBootstrap().accountUser?.id;
    return accountId ? String(accountId) : null;
}

/**
 * @param {string} instanceId
 * @param {'task'|'deliverable'} kind
 * @param {string} statusKey
 * @param {boolean} custom
 * @param {string} sprintId
 * @param {import('../storage.js').SpWorkspaceRoot} workspace
 * @returns {boolean}
 */
function claimForActivePerson(instanceId, kind, statusKey, custom, sprintId, workspace) {
    const personId = resolveClaimPersonId(workspace);
    if (!personId) {
        showToast(spT('sp.error.activePersonMissing'));
        return false;
    }
    const result = claimItem(instanceId, kind, statusKey, custom, sprintId, personId);
    if (!result.ok) {
        showToast(storageErrorMessage(result.error));
        return false;
    }
    return true;
}

function bindClaimAll(instanceId) {
    document.getElementById('sp-claim-all-btn')?.addEventListener('click', () => {
        const ctx = lastRenderContext;
        if (!ctx || ctx.instance.id !== instanceId) {
            return;
        }
        const personId = resolveClaimPersonId(ctx.workspace);
        if (!personId) {
            showToast(spT('sp.error.activePersonMissing'));
            return;
        }
        const items = [];
        for (const sprint of ctx.sprints) {
            for (const item of [...sprint.tasks, ...sprint.deliverables]) {
                items.push({
                    kind: item.kind,
                    statusKey: item.statusKey,
                    custom: item.custom,
                    sprintId: item.sprintId,
                    assigneeId: item.assigneeId,
                });
            }
        }
        const result = claimAllUnassigned(instanceId, items, personId);
        if (!result.ok) {
            showToast(storageErrorMessage(result.error));
            return;
        }
        showToast(spT('sp.toast.claimedAll'));
        rerender();
    });
}

function bindFilters(render) {
    const prefs = loadPreferences();
    // Reset assignee XOR radios → AND checkboxes (clear stuck Unassigned-only state).
    if (Number(prefs.planFiltersVersion) < 3) {
        prefs.planFilters = normalizePlanFilters({
            currentWeek: Boolean(prefs.planFilters?.currentWeek),
            hideDone: Boolean(prefs.planFilters?.hideDone),
            openOnly: Boolean(prefs.planFilters?.openOnly),
            blocked: Boolean(prefs.planFilters?.blocked),
            myTasks: false,
            unassigned: false,
            personId: '',
            teamId: '',
            status: prefs.planFilters?.status || '',
            priority: prefs.planFilters?.priority || '',
        });
        prefs.planFiltersVersion = 3;
        savePreferences(prefs);
    }
    const planFilters = normalizePlanFilters(prefs.planFilters || {});

    const checkboxMap = {
        'sp-filter-current-week': 'currentWeek',
        'sp-filter-hide-done': 'hideDone',
        'sp-filter-open-only': 'openOnly',
        'sp-filter-blocked': 'blocked',
        'sp-filter-my-tasks': 'myTasks',
        'sp-filter-unassigned': 'unassigned',
    };
    for (const [id, key] of Object.entries(checkboxMap)) {
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

/**
 * Recover missing templateSlug from snapshot, status keys, or title match.
 * @param {import('../storage.js').SpPlanInstance} instance
 * @param {object[]} templates
 * @returns {string}
 */
function repairTemplateSlug(instance, templates) {
    const current = String(instance.templateSlug || '').trim();
    if (current) {
        return current;
    }

    const fromSnapshot = instance.templateSnapshot?.slug
        ? String(instance.templateSnapshot.slug)
        : '';
    if (fromSnapshot) {
        return fromSnapshot;
    }

    const keyBags = [
        ...(instance.completedTasks || []),
        ...(instance.completedDeliverables || []),
        ...Object.keys(instance.itemOverrides || {}),
        ...Object.keys(instance.fieldValues || {}),
        ...Object.keys(instance.sprintNotes || {}),
        ...Object.keys(instance.sprintOverrides || {}),
    ];
    /** @type {Map<string, number>} */
    const slugVotes = new Map();
    for (const key of keyBags) {
        const slug = String(key).split(':')[0].trim();
        if (!slug || slug.includes(' ')) {
            continue;
        }
        // statusKey shape: slug:sprintId:kind:itemId
        if (String(key).split(':').length < 3) {
            continue;
        }
        slugVotes.set(slug, (slugVotes.get(slug) || 0) + 1);
    }
    if (slugVotes.size > 0) {
        const ranked = [...slugVotes.entries()].sort((a, b) => b[1] - a[1]);
        const [bestSlug] = ranked[0];
        if (templates.some((t) => t.slug === bestSlug) || bestSlug.startsWith('custom:')) {
            return bestSlug;
        }
        // Even without a catalog hit, return the slug so snapshot / recover UI can use it.
        return bestSlug;
    }

    const locale = getLocale();
    const title = instance.translations?.[locale]?.title
        || instance.translations?.en?.title
        || instance.translations?.de?.title
        || '';
    if (title) {
        const byTitle = templates.find((t) => (
            t.locales?.en?.title === title
            || t.locales?.de?.title === title
        ));
        if (byTitle?.slug) {
            return byTitle.slug;
        }
    }

    return '';
}

/**
 * @param {object[]} templates
 * @param {'de'|'en'} locale
 */
function fillRecoverTemplateSelect(templates, locale) {
    const select = document.getElementById('sp-recover-template');
    if (!select) {
        return;
    }
    const options = templates.map((t) => ({
        id: t.slug,
        label: t.locales?.[locale]?.title || t.locales?.en?.title || t.slug,
    }));
    fillSelect(select, options, select.value || options[0]?.id || '', false);
}

/**
 * @param {string} instanceId
 * @param {() => void} render
 */
function bindTemplateRecover(instanceId, render) {
    document.getElementById('sp-recover-template-btn')?.addEventListener('click', () => {
        const slug = document.getElementById('sp-recover-template')?.value || '';
        const templates = readTemplatesFromDom();
        const template = templates.find((t) => t.slug === slug);
        if (!template) {
            showToast(spT('sp.error.templateMissing', { slug: slug || '—' }));
            return;
        }
        const result = updateInstance(instanceId, (plan) => {
            plan.templateSlug = template.slug;
            plan.templateVersion = Number(template.version) || plan.templateVersion || 1;
            plan.templateSnapshot = structuredClone(template);
            if (!plan.translations || Object.keys(plan.translations).length === 0) {
                plan.translations = {
                    de: {
                        title: template.locales?.de?.title || template.slug,
                        description: template.locales?.de?.description || '',
                    },
                    en: {
                        title: template.locales?.en?.title || template.slug,
                        description: template.locales?.en?.description || '',
                    },
                };
            }
            if (!plan.startedAt) {
                plan.startedAt = new Date().toISOString().slice(0, 10);
            }
        });
        if (!result.ok) {
            showToast(storageErrorMessage(result.error));
            return;
        }
        showToast(spT('sp.recover.done'));
        render();
    });
}

function persistFilters() {
    const prefs = loadPreferences();
    prefs.planFilters = readFilters(prefs);
    savePreferences(prefs);
}

function readFilters(prefs) {
    return normalizePlanFilters({
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
    });
}

function populateFilterPeopleTeams(locale) {
    const personSelect = document.getElementById('sp-filter-person');
    const teamSelect = document.getElementById('sp-filter-team');
    const anyLabel = spT('sp.filter.any');
    if (personSelect) {
        const current = personSelect.value;
        fillSelect(
            personSelect,
            listPeople().map((p) => ({ id: p.id, label: p.displayName })),
            current,
            true,
            anyLabel,
        );
    }
    if (teamSelect) {
        const current = teamSelect.value;
        fillSelect(
            teamSelect,
            listTeams().map((team) => ({ id: team.id, label: localizedText(team.name, locale) })),
            current,
            true,
            anyLabel,
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
        assignee || spT('sp.report.unassigned'),
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

    if (!item.assigneeId) {
        const pick = document.createElement('button');
        pick.type = 'button';
        pick.className = 'tools-btn tools-btn--primary tools-btn--small sp-item__pick';
        pick.textContent = spT('sp.action.pick');
        pick.title = spT('sp.action.pickHint');
        pick.addEventListener('click', (event) => {
            event.preventDefault();
            event.stopPropagation();
            if (!claimForActivePerson(
                instance.id,
                item.kind,
                item.statusKey,
                item.custom,
                sprint.id,
                workspace,
            )) {
                return;
            }
            showToast(spT('sp.toast.claimed'));
            rerender();
        });
        actions.appendChild(pick);

        const assign = document.createElement('button');
        assign.type = 'button';
        assign.className = 'tools-btn tools-btn--secondary tools-btn--small';
        assign.textContent = spT('sp.action.assign');
        assign.addEventListener('click', () => openAssignDialog({
            sprintId: sprint.id,
            kind: item.kind,
            custom: item.custom,
            item,
        }));
        actions.appendChild(assign);
    } else {
        const reassign = document.createElement('button');
        reassign.type = 'button';
        reassign.className = 'tools-btn tools-btn--secondary tools-btn--small';
        reassign.textContent = spT('sp.action.assign');
        reassign.addEventListener('click', () => openAssignDialog({
            sprintId: sprint.id,
            kind: item.kind,
            custom: item.custom,
            item,
        }));
        actions.appendChild(reassign);
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

    if (Array.isArray(item.attachments) && item.attachments.length > 0) {
        const attBadge = document.createElement('span');
        attBadge.className = 'sp-item__att-count';
        const count = item.attachments.length;
        attBadge.textContent = count === 1
            ? spT('sp.attachments.count', { count })
            : spT('sp.attachments.countPlural', { count });
        main.appendChild(attBadge);
    }

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

    const tableEl = renderItemTableReadonly(item.table);
    if (tableEl) {
        const tableHost = document.createElement('div');
        tableHost.className = 'sp-item__table';
        tableHost.appendChild(tableEl);
        li.appendChild(tableHost);
    }

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
        a.href = resolveExternalHelpHref(link.href);
        a.target = '_blank';
        a.rel = 'noopener noreferrer';
        a.textContent = link.label || link.href;
        if (a.href === '#') {
            continue;
        }
        wrap.appendChild(a);
    }
    return wrap;
}

function statusKeyToLabel(status) {
    return status === 'in_progress' ? 'inProgress' : status;
}

/**
 * Parse "Label | https://…" lines for external help/community links only.
 * @param {string} raw
 * @returns {{links: Array<{label: string, href: string}>, rejected: string[]}}
 */
function parseLinksTextarea(raw) {
    return parseExternalLinksTextarea(raw);
}

/**
 * @param {Array<{label: string, href: string}>} links
 */
function formatLinksTextarea(links = []) {
    return formatExternalLinksTextarea(links);
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
        if (parsedLinks.rejected?.length) {
            showToast(spT('sp.toast.linksRejected'));
        }
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
        const status = document.getElementById('sp-item-status').value;
        const previousStatus = itemDialogState.item?.status;
        const blockerReason = document.getElementById('sp-item-blocker-reason')?.value || '';
        const blockerSince = status === 'blocked'
            ? (previousStatus === 'blocked' ? (itemDialogState.item?.blockerSince || todayIso()) : todayIso())
            : null;
        const allowLabels = Boolean(itemDialogState.create || itemDialogState.custom);
        const data = {
            id: document.getElementById('sp-item-id').value,
            labelDe: allowLabels
                ? (document.getElementById('sp-item-label-de').value || '')
                : (itemDialogState.item?.label || ''),
            labelEn: allowLabels
                ? (document.getElementById('sp-item-label-en').value || '')
                : (itemDialogState.item?.label || ''),
            assigneeType: document.getElementById('sp-item-assignee-type').value,
            assigneeId: document.getElementById('sp-item-assignee-id').value || null,
            status,
            priority: document.getElementById('sp-item-priority').value,
            dueDate: document.getElementById('sp-item-due').value || null,
            note: document.getElementById('sp-item-note').value,
            table: readTableEditor(document.getElementById('sp-item-table-editor')),
            blockerReason,
            blockerSince,
            attachments: normalizeAttachments(dialogAttachments),
        };
        if (allowLabels && !String(data.labelDe || data.labelEn || '').trim()) {
            showToast(spT('sp.error.labelRequired'));
            return;
        }
        const kind = document.getElementById('sp-item-type').value;
        const sprintId = document.getElementById('sp-item-sprint-id').value;
        const inst = loadWorkspace().data.instances[instanceId];
        const template = readTemplatesFromDom().find((t) => t.slug === inst?.templateSlug)
            || inst?.templateSnapshot;
        const result = itemDialogState.create
            ? addCustomItem(instanceId, sprintId, kind, data, template?.slug || inst?.templateSlug || 'custom')
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

    const assignDialog = document.getElementById('sp-assign-dialog');
    assignDialog?.addEventListener('close', () => {
        if (assignDialog.returnValue !== 'confirm' || !assignDialogState) {
            assignDialogState = null;
            return;
        }
        const result = assignItem(
            instanceId,
            /** @type {'task'|'deliverable'} */ (assignDialogState.kind),
            assignDialogState.statusKey,
            assignDialogState.custom,
            assignDialogState.sprintId,
            {
                assigneeType: document.getElementById('sp-assign-assignee-type')?.value || 'person',
                assigneeId: document.getElementById('sp-assign-assignee-id')?.value || null,
            },
        );
        assignDialogState = null;
        if (!result.ok) {
            showToast(storageErrorMessage(result.error));
            return;
        }
        showToast(spT('sp.toast.assigned'));
        render();
    });
    document.getElementById('sp-assign-assignee-type')?.addEventListener('change', () => {
        refreshAssignAssigneeOptions();
    });

    document.getElementById('sp-item-assignee-type')?.addEventListener('change', () => refreshAssigneeOptions());
    document.getElementById('sp-item-status')?.addEventListener('change', () => updateBlockerFieldVisibility());
    document.getElementById('sp-item-att-add-link')?.addEventListener('click', () => {
        const label = document.getElementById('sp-item-att-label')?.value || '';
        const href = document.getElementById('sp-item-att-url')?.value || '';
        if (!href.trim()) {
            return;
        }
        const workspace = loadWorkspace().data;
        dialogAttachments.push(createLinkAttachment({
            label,
            href: href.trim(),
            uploadedBy: workspace.workspace.activePersonId,
        }));
        document.getElementById('sp-item-att-label').value = '';
        document.getElementById('sp-item-att-url').value = '';
        renderDialogAttachments(instanceId);
        showToast(spT('sp.toast.attachmentAdded'));
    });
    document.getElementById('sp-item-att-file')?.addEventListener('change', async (event) => {
        const input = /** @type {HTMLInputElement} */ (event.target);
        const file = input.files?.[0];
        input.value = '';
        if (!file) {
            return;
        }
        const validation = validateAttachmentFile(file);
        if (!validation.ok) {
            showToast(storageErrorMessage(validation.error));
            return;
        }
        const workspace = loadWorkspace().data;
        const personId = workspace.workspace.activePersonId;
        try {
            if (usesServerPlans() && !workspace.instances[instanceId]?.ephemeral) {
                const { attachment } = await uploadAttachmentToServer(instanceId, file);
                dialogAttachments.push(normalizeAttachments([attachment])[0]);
            } else {
                const meta = createFileAttachmentMeta({
                    name: file.name,
                    mime: file.type || 'application/octet-stream',
                    size: file.size,
                    uploadedBy: personId,
                    localBlob: true,
                });
                await putLocalBlob(instanceId, meta.id, file);
                dialogAttachments.push(meta);
            }
            renderDialogAttachments(instanceId);
            showToast(spT('sp.toast.attachmentAdded'));
        } catch (err) {
            showToast(storageErrorMessage(err?.message || 'attachment-upload'));
        }
    });
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
    const workspace = loadWorkspace().data;
    const activePersonId = resolveClaimPersonId(workspace);
    itemDialogState = state;
    const isTemplateItem = Boolean(state.item && !state.custom && !state.create);
    const showLabels = Boolean(state.create || state.custom);
    const labelFields = document.getElementById('sp-item-label-fields');
    if (labelFields) {
        labelFields.hidden = !showLabels;
    }
    const planOnlyNote = document.getElementById('sp-item-plan-only-note');
    if (planOnlyNote) {
        planOnlyNote.hidden = !isTemplateItem;
    }
    const labelDe = document.getElementById('sp-item-label-de');
    const labelEn = document.getElementById('sp-item-label-en');
    if (labelDe) {
        labelDe.required = showLabels;
        labelDe.value = showLabels ? (state.item?.label || '') : '';
    }
    if (labelEn) {
        labelEn.value = showLabels ? (state.item?.label || '') : '';
    }
    document.getElementById('sp-item-sprint-id').value = state.sprintId;
    document.getElementById('sp-item-type').value = state.kind;
    document.getElementById('sp-item-id').value = state.item?.id || '';
    const defaultAssigneeType = state.create ? 'person' : (state.item?.assigneeType || 'person');
    const defaultAssigneeId = state.create
        ? activePersonId
        : (state.item?.assigneeId || '');
    document.getElementById('sp-item-assignee-type').value = defaultAssigneeType || 'person';
    document.getElementById('sp-item-status').value = state.item?.status || 'open';
    document.getElementById('sp-item-priority').value = state.item?.priority || 'normal';
    document.getElementById('sp-item-due').value = state.item?.dueDate || '';
    document.getElementById('sp-item-note').value = state.item?.note || '';
    const blockerReasonEl = document.getElementById('sp-item-blocker-reason');
    if (blockerReasonEl) {
        blockerReasonEl.value = state.item?.blockerReason || '';
    }
    dialogAttachments = normalizeAttachments(state.item?.attachments || []);
    const instanceId = document.getElementById('sp-app')?.dataset?.spInstanceId || '';
    renderDialogAttachments(instanceId);
    const tableHost = document.getElementById('sp-item-table-editor');
    if (tableHost) {
        renderTableEditor(tableHost, state.item?.table || null, { spT });
    }
    refreshAssigneeOptions(defaultAssigneeId || '');
    updateBlockerFieldVisibility();
    const title = document.getElementById('sp-item-dialog-title');
    if (title) {
        title.textContent = state.create
            ? spT('sp.dialog.itemCreateTitle')
            : spT('sp.dialog.itemTitle');
    }
    document.getElementById('sp-item-dialog').showModal();
}

/**
 * @param {{sprintId: string, kind: string, custom: boolean, item: object}} state
 */
function openAssignDialog(state) {
    closeHelpPanel();
    assignDialogState = {
        sprintId: state.sprintId,
        kind: state.kind,
        statusKey: state.item.statusKey,
        custom: Boolean(state.custom),
    };
    document.getElementById('sp-assign-sprint-id').value = state.sprintId;
    document.getElementById('sp-assign-kind').value = state.kind;
    document.getElementById('sp-assign-key').value = state.item.statusKey;
    document.getElementById('sp-assign-custom').value = state.custom ? '1' : '0';
    const labelEl = document.getElementById('sp-assign-item-label');
    if (labelEl) {
        labelEl.textContent = state.item.label || '';
    }
    const type = state.item.assigneeType || 'person';
    document.getElementById('sp-assign-assignee-type').value = type;
    refreshAssignAssigneeOptions(state.item.assigneeId || '');
    document.getElementById('sp-assign-dialog')?.showModal();
    document.getElementById('sp-assign-assignee-id')?.focus();
}

/**
 * @param {string} [selectedId]
 */
function refreshAssignAssigneeOptions(selectedId) {
    const workspace = loadWorkspace().data;
    const type = document.getElementById('sp-assign-assignee-type')?.value || 'person';
    const select = document.getElementById('sp-assign-assignee-id');
    if (!select) {
        return;
    }
    const locale = getLocale();
    if (type === 'team') {
        fillSelect(
            select,
            listTeams().map((team) => ({
                id: team.id,
                label: localizedText(team.name, locale) || team.id,
            })),
            selectedId || '',
            true,
            spT('sp.field.none'),
        );
        return;
    }
    fillSelect(
        select,
        listPeople().map((person) => ({
            id: person.id,
            label: person.displayName || person.id,
        })),
        selectedId || resolveClaimPersonId(workspace) || '',
        true,
        spT('sp.field.none'),
    );
}

/**
 * @param {string} instanceId
 */
function renderDialogAttachments(instanceId) {
    const list = document.getElementById('sp-item-attachments-list');
    if (!list) {
        return;
    }
    list.replaceChildren();
    for (const att of dialogAttachments) {
        const li = document.createElement('li');
        li.className = 'sp-attachments-list__item';
        const name = document.createElement('span');
        name.textContent = att.name;
        const remove = document.createElement('button');
        remove.type = 'button';
        remove.className = 'tools-btn tools-btn--secondary tools-btn--small';
        remove.textContent = spT('sp.action.removeAttachment');
        remove.addEventListener('click', async () => {
            dialogAttachments = dialogAttachments.filter((entry) => entry.id !== att.id);
            if (att.localBlob && instanceId) {
                try {
                    await deleteLocalBlob(instanceId, att.id);
                } catch {
                    // ignore
                }
            }
            renderDialogAttachments(instanceId);
        });
        li.append(name, remove);
        list.appendChild(li);
    }
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
        refreshStatusReport();
        showStatusReport();
    });
    document.getElementById('sp-status-report-close')?.addEventListener('click', closeStatusReport);
    document.getElementById('sp-status-report-print')?.addEventListener('click', () => window.print());
    document.getElementById('sp-report-mode-executive')?.addEventListener('click', () => {
        reportMode = 'executive';
        updateReportModeButtons();
        refreshStatusReport();
    });
    document.getElementById('sp-report-mode-detailed')?.addEventListener('click', () => {
        reportMode = 'detailed';
        updateReportModeButtons();
        refreshStatusReport();
    });
    document.getElementById('sp-report-mode-documentation')?.addEventListener('click', () => {
        reportMode = 'documentation';
        updateReportModeButtons();
        refreshStatusReport();
    });
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeStatusReport();
        }
    });
    updateReportModeButtons();
}

function updateReportModeButtons() {
    for (const btn of document.querySelectorAll('[data-report-mode]')) {
        const mode = btn.getAttribute('data-report-mode');
        btn.classList.toggle('tools-btn--primary', mode === reportMode);
        btn.classList.toggle('tools-btn--secondary', mode !== reportMode);
    }
}

function refreshStatusReport() {
    if (!lastRenderContext) {
        return;
    }
    const { instance, template, sprints, workspace, locale } = lastRenderContext;
    renderStatusReport(instance, template, sprints, workspace, locale);
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
    const blockers = collectBlockers(sprints);
    const unassignedCount = countUnassigned(sprints);

    const title = document.createElement('h1');
    title.className = 'tools-page-title';
    title.textContent = instance.translations?.[locale]?.title
        || template?.locales?.[locale]?.title
        || instance.templateSlug;
    body.appendChild(title);

    if (reportMode === 'executive') {
        renderExecutiveReport(body, {
            progress,
            currentSprint,
            blockers,
            unassignedCount,
            sprints,
            workspace,
            locale,
        });
        return;
    }

    if (reportMode === 'documentation') {
        renderDocumentationReport(body, {
            sprints,
            workspace,
            locale,
            instanceId: instance.id,
            description: instance.translations?.[locale]?.description
                || template?.locales?.[locale]?.description
                || '',
        });
        return;
    }

    renderDetailedReport(body, {
        progress,
        currentSprint,
        blockers,
        sprints,
        workspace,
        locale,
        instanceId: instance.id,
    });
}

/**
 * @param {HTMLElement} body
 * @param {object} ctx
 */
function renderExecutiveReport(body, ctx) {
    const {
        progress, currentSprint, blockers, unassignedCount, sprints, workspace, locale,
    } = ctx;

    const kpi = document.createElement('div');
    kpi.className = 'sp-report-kpi';
    kpi.append(
        buildKpiCard(spT('sp.report.progress'), `${progress.percent}%`, `${progress.done}/${progress.total}`),
        buildKpiCard(
            spT('sp.report.currentSprint'),
            currentSprint ? `#${currentSprint.number}` : '—',
            currentSprint ? `${currentSprint.progress.percent}%` : '',
        ),
        buildKpiCard(spT('sp.report.kpiBlockers'), String(blockers.length), ''),
        buildKpiCard(spT('sp.report.unassignedCount'), String(unassignedCount), ''),
    );
    body.appendChild(kpi);

    if (currentSprint?.goal) {
        body.appendChild(buildReportSection(spT('sp.report.currentGoal'), () => {
            const p = document.createElement('p');
            p.textContent = currentSprint.goal;
            return [p];
        }));
    }

    const topBlockers = blockers.slice(0, 5);
    body.appendChild(buildReportSection(spT('sp.report.topBlockers'), () => {
        if (!topBlockers.length) {
            return [buildEmptyParagraph()];
        }
        return [buildReportTable(
            [spT('sp.report.colSprint'), spT('sp.report.colItem'), spT('sp.report.colAssignee'), spT('sp.report.colReason')],
            topBlockers.map((blocker) => [
                `#${blocker.sprintNumber}`,
                blocker.item.label,
                resolveAssigneeName(blocker.item, workspace, locale) || spT('sp.report.unassigned'),
                blocker.item.blockerReason || '',
            ]),
        )];
    }));

    const openInCurrent = [];
    if (currentSprint) {
        for (const item of [...currentSprint.tasks, ...currentSprint.deliverables]) {
            if (!item.completed && item.status !== 'completed') {
                openInCurrent.push(item);
            }
        }
    }
    body.appendChild(buildReportSection(spT('sp.report.thisSprint'), () => {
        if (!openInCurrent.length) {
            return [buildEmptyParagraph()];
        }
        return [buildReportTable(
            [spT('sp.report.colItem'), spT('sp.report.colStatus'), spT('sp.report.colAssignee')],
            openInCurrent.map((item) => [
                item.label,
                spT(`sp.status.${statusKeyToLabel(item.status)}`),
                resolveAssigneeName(item, workspace, locale) || spT('sp.report.unassigned'),
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
}

/**
 * @param {HTMLElement} body
 * @param {object} ctx
 */
function renderDetailedReport(body, ctx) {
    const {
        progress, currentSprint, blockers, sprints, workspace, locale, instanceId,
    } = ctx;

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

    for (const sprint of sprints) {
        const section = document.createElement('section');
        section.className = 'sp-report-section sp-report-section--detailed';
        const heading = document.createElement('h3');
        heading.textContent = `#${sprint.number} ${sprint.title}`;
        section.appendChild(heading);
        const items = [...sprint.tasks, ...sprint.deliverables];
        if (!items.length) {
            section.appendChild(buildEmptyParagraph());
            body.appendChild(section);
            continue;
        }
        for (const item of items) {
            section.appendChild(buildDetailedItemCard(item, workspace, locale, instanceId));
        }
        body.appendChild(section);
    }
}

/**
 * @param {import('../progress.js').ResolvedItem} item
 * @param {import('../storage.js').SpWorkspaceRoot} workspace
 * @param {'de'|'en'} locale
 * @param {string} instanceId
 */
function buildDetailedItemCard(item, workspace, locale, instanceId) {
    const card = document.createElement('article');
    card.className = 'sp-report-item';
    const title = document.createElement('h4');
    title.className = 'sp-report-item__title';
    title.textContent = item.label;
    card.appendChild(title);

    const meta = document.createElement('p');
    meta.className = 'sp-report-item__meta';
    meta.textContent = [
        spT(`sp.status.${statusKeyToLabel(item.status)}`),
        spT(`sp.priority.${item.priority}`),
        resolveAssigneeName(item, workspace, locale) || spT('sp.report.unassigned'),
        item.dueDate || '',
    ].filter(Boolean).join(' · ');
    card.appendChild(meta);

    if (item.note) {
        const note = document.createElement('p');
        note.className = 'sp-report-item__note';
        note.textContent = `${spT('sp.report.note')}: ${item.note}`;
        card.appendChild(note);
    }

    if (item.helpText) {
        const help = document.createElement('p');
        help.className = 'sp-report-item__help';
        help.textContent = item.helpText;
        card.appendChild(help);
    }

    const tableEl = renderItemTableReadonly(item.table);
    if (tableEl) {
        card.appendChild(tableEl);
    }

    if (Array.isArray(item.helpLinks) && item.helpLinks.length) {
        const links = document.createElement('ul');
        links.className = 'sp-report-item__links';
        for (const link of item.helpLinks) {
            const li = document.createElement('li');
            const a = document.createElement('a');
            a.href = resolveExternalHelpHref(link.href);
            if (a.href === '#') {
                continue;
            }
            a.textContent = link.label || link.href;
            a.target = '_blank';
            a.rel = 'noopener noreferrer';
            li.appendChild(a);
            links.appendChild(li);
        }
        card.appendChild(links);
    }

    if (Array.isArray(item.attachments) && item.attachments.length) {
        const attWrap = document.createElement('div');
        attWrap.className = 'sp-report-item__attachments';
        const attTitle = document.createElement('p');
        attTitle.textContent = spT('sp.report.attachments');
        attWrap.appendChild(attTitle);
        const list = document.createElement('ul');
        list.className = 'sp-report-attachments';
        for (const att of item.attachments) {
            const li = document.createElement('li');
            void appendAttachmentReportNode(li, instanceId, att);
            list.appendChild(li);
        }
        attWrap.appendChild(list);
        card.appendChild(attWrap);
    }

    return card;
}

/**
 * Documentation report: goals, help, links, tables, notes — no progress KPIs.
 * @param {HTMLElement} body
 * @param {object} ctx
 */
function renderDocumentationReport(body, ctx) {
    const { sprints, workspace, locale, instanceId, description } = ctx;
    if (description) {
        const lead = document.createElement('p');
        lead.className = 'tools-page-lead';
        lead.textContent = description;
        body.appendChild(lead);
    }

    for (const sprint of sprints) {
        const section = document.createElement('section');
        section.className = 'sp-report-section sp-report-section--docs';
        const heading = document.createElement('h3');
        heading.textContent = `#${sprint.number} ${sprint.title}`;
        section.appendChild(heading);
        if (sprint.goal) {
            const goal = document.createElement('p');
            goal.className = 'sp-report-item__help';
            goal.textContent = sprint.goal;
            section.appendChild(goal);
        }
        if (Array.isArray(sprint.links) && sprint.links.length) {
            const links = document.createElement('ul');
            links.className = 'sp-report-item__links';
            for (const link of sprint.links) {
                const href = resolveExternalHelpHref(link.href);
                if (href === '#') {
                    continue;
                }
                const li = document.createElement('li');
                const a = document.createElement('a');
                a.href = href;
                a.target = '_blank';
                a.rel = 'noopener noreferrer';
                a.textContent = link.label || link.href;
                li.appendChild(a);
                links.appendChild(li);
            }
            if (links.childNodes.length) {
                section.appendChild(links);
            }
        }
        const items = [...sprint.tasks, ...sprint.deliverables];
        for (const item of items) {
            section.appendChild(buildDocumentationItemCard(item, workspace, locale, instanceId));
        }
        body.appendChild(section);
    }
}

/**
 * @param {import('../progress.js').ResolvedItem} item
 * @param {import('../storage.js').SpWorkspaceRoot} workspace
 * @param {'de'|'en'} locale
 * @param {string} instanceId
 */
function buildDocumentationItemCard(item, workspace, locale, instanceId) {
    const card = document.createElement('article');
    card.className = 'sp-report-item sp-report-item--docs';
    const title = document.createElement('h4');
    title.className = 'sp-report-item__title';
    title.textContent = item.label;
    card.appendChild(title);

    if (item.helpText) {
        const help = document.createElement('p');
        help.className = 'sp-report-item__help';
        help.textContent = item.helpText;
        card.appendChild(help);
    }

    if (item.note) {
        const note = document.createElement('p');
        note.className = 'sp-report-item__note';
        note.textContent = item.note;
        card.appendChild(note);
    }

    const tableEl = renderItemTableReadonly(item.table);
    if (tableEl) {
        card.appendChild(tableEl);
    }

    if (Array.isArray(item.helpLinks) && item.helpLinks.length) {
        const links = document.createElement('ul');
        links.className = 'sp-report-item__links';
        for (const link of item.helpLinks) {
            const href = resolveExternalHelpHref(link.href);
            if (href === '#') {
                continue;
            }
            const li = document.createElement('li');
            const a = document.createElement('a');
            a.href = href;
            a.textContent = link.label || link.href;
            a.target = '_blank';
            a.rel = 'noopener noreferrer';
            li.appendChild(a);
            links.appendChild(li);
        }
        if (links.childNodes.length) {
            card.appendChild(links);
        }
    }

    if (Array.isArray(item.attachments) && item.attachments.length) {
        const attWrap = document.createElement('div');
        attWrap.className = 'sp-report-item__attachments';
        const list = document.createElement('ul');
        list.className = 'sp-report-attachments';
        for (const att of item.attachments) {
            const li = document.createElement('li');
            void appendAttachmentReportNode(li, instanceId, att);
            list.appendChild(li);
        }
        attWrap.appendChild(list);
        card.appendChild(attWrap);
    }

    return card;
}

/**
 * @param {HTMLElement} host
 * @param {string} instanceId
 * @param {import('../attachments.js').SpAttachment} att
 */
async function appendAttachmentReportNode(host, instanceId, att) {
    const href = await resolveAttachmentHref(instanceId, att);
    if (att.previewable && href) {
        const img = document.createElement('img');
        img.className = 'sp-report-attachment-img';
        img.src = href;
        img.alt = att.name;
        host.appendChild(img);
    }
    const a = document.createElement('a');
    if (href) {
        a.href = href;
        a.target = '_blank';
        a.rel = 'noopener noreferrer';
    }
    a.textContent = att.name + (att.blobMissing ? ` (${spT('sp.attachments.missingBlob')})` : '');
    host.appendChild(a);
}

/**
 * @param {string} label
 * @param {string} value
 * @param {string} hint
 */
function buildKpiCard(label, value, hint) {
    const card = document.createElement('div');
    card.className = 'sp-report-kpi__card';
    const dt = document.createElement('div');
    dt.className = 'sp-report-kpi__label';
    dt.textContent = label;
    const dd = document.createElement('div');
    dd.className = 'sp-report-kpi__value';
    dd.textContent = value;
    card.append(dt, dd);
    if (hint) {
        const hintEl = document.createElement('div');
        hintEl.className = 'sp-report-kpi__hint';
        hintEl.textContent = hint;
        card.appendChild(hintEl);
    }
    return card;
}

/**
 * @param {ReturnType<typeof resolveSprints>} sprints
 */
function countUnassigned(sprints) {
    let count = 0;
    for (const sprint of sprints) {
        for (const item of [...sprint.tasks, ...sprint.deliverables]) {
            if (!item.assigneeId) {
                count += 1;
            }
        }
    }
    return count;
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
