import { getLocale } from '../../locale.js';
import { applyPlaybookFocus, getPlaybookFocus } from '../../shell-layout.js';
import {
    isAccountsMode,
    readAccountsBootstrap,
    fetchPlanHistory,
    fetchPlanRevision,
    restorePlanRevision,
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
    parseTableColumnsText,
    renderInlineTableEditor,
    renderItemTableReadonly,
    renderTableEditor,
} from '../item-table.js';
import {
    formatExternalLinksTextarea,
    parseExternalLinksTextarea,
    resolveExternalHelpHref,
} from '../external-links.js';
import { emptyPlanFilters, filterSprints, filtersToRevealAssignee, hasActiveItemFilters, normalizePlanFilters } from '../filters.js';
import {
    addCustomItem,
    addCustomSprint,
    addSprintFromTemplate,
    assignItem,
    backfillParticipantsFromAssignees,
    claimAllUnassigned,
    claimItem,
    deleteCustomSprint,
    removePlanItem,
    duplicateSprint,
    moveSprint,
    resetSprint,
    setFieldValue,
    setSprintNote,
    toggleCompleted,
    updateCustomOrOverrideSprint,
    updateInstance,
    updateItemMeta,
    canUndoInstance,
    undoLastInstanceChange,
} from '../instance-manager.js';
import { listPeople, listTeams, localizedText, upsertPerson } from '../people-teams.js';
import { normalizeTeamIds, normalizeTrigram } from '../trigram.js';
import {
    calculateCurrentSprintNumber,
    calculateOverallProgress,
    collectBlockers,
    collectItemsByStatus,
    computeScheduleHealth,
    formatDateRangeShort,
    formatDateShort,
    formatIsoWeekLabel,
    isPlanStarted,
    normalizeStories,
    resolveSprints,
    sprintDateRange,
} from '../progress.js';
import { loadPreferences, loadWorkspace, savePreferences, saveWorkspace } from '../storage.js';
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
    setLastOpenedPlanId,
    showToast,
    spT,
    storageErrorMessage,
} from './helpers.js';
import { renderAssigneeChip, renderParticipantChips, renderTeamChips } from './avatars.js';
import { createIconButton } from './icons.js';
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
/** Session UI state for the status banner (prefs are synced, but memory wins within the tab). */
/** @type {Map<string, {status: string|null, expanded: boolean}>} */
const statusBannerSession = new Map();
/** @type {Set<string>} */
const healedIdentityPlanIds = new Set();
/** @type {Set<string>} */
const strippedTemplateOverridePlanIds = new Set();
/** @type {Set<string>} */
const backfilledParticipantsPlanIds = new Set();
/** @type {Set<string>} */
const healedTeamPlanIds = new Set();

export function initPlanShowPage() {
    const root = document.getElementById('sp-app');
    if (!root) {
        return;
    }

    const instanceId = root.dataset.spInstanceId;
    if (instanceId) {
        setLastOpenedPlanId(instanceId);
    }

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
        bindPlanChrome(instanceId, render);
        bindDialogs(instanceId, render);
        bindHelpPanelChrome();
        bindStatusReport();
        bindUndoAndHistory(instanceId);
        bindClaimAll(instanceId);
        bindTemplateRecover(instanceId, render);
        document.getElementById('sp-add-sprint')?.addEventListener('click', () => {
            openAddSprintFromTemplateDialog();
        });
        bindAddSprintFromTemplateDialog(instanceId, render);
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
    if (
        !healedTeamPlanIds.has(String(instance.id))
        && !normalizeTeamIds(instance).length
    ) {
        healedTeamPlanIds.add(String(instance.id));
        const fallbackTeamId = workspace.workspace.defaultTeamId
            && workspace.teams?.[workspace.workspace.defaultTeamId]
            ? String(workspace.workspace.defaultTeamId)
            : (workspace.teams?.team_q ? 'team_q' : null);
        if (fallbackTeamId) {
            updateInstance(instance.id, (plan) => {
                plan.teamIds = [fallbackTeamId];
                plan.teamId = null;
            });
            instance.teamIds = [fallbackTeamId];
            instance.teamId = null;
        }
    } else {
        healedTeamPlanIds.add(String(instance.id));
    }
    if (
        template
        && !backfilledParticipantsPlanIds.has(String(instance.id))
        && backfillParticipantsFromAssignees(instance, sprints)
    ) {
        backfilledParticipantsPlanIds.add(String(instance.id));
        updateInstance(instance.id, (plan) => {
            plan.participantIds = [...(instance.participantIds || [])];
        });
    } else if (template) {
        backfilledParticipantsPlanIds.add(String(instance.id));
    }
    const progress = calculateOverallProgress(sprints);
    const unit = template?.unit || 'week';
    const currentNumber = calculateCurrentSprintNumber(
        instance.startedAt,
        sprints.length || template?.duration || 1,
        unit,
    );
    const planStarted = isPlanStarted(instance.startedAt);
    const displaySprintNumber = currentNumber > 0 ? currentNumber : 1;
    const currentRange = sprintDateRange(instance.startedAt, displaySprintNumber, unit);
    const schedule = currentNumber > 0
        ? computeScheduleHealth(sprints, currentNumber)
        : { weeksBehind: 0, openCount: 0, onTrack: true, sources: [] };

    const planTitle = instance.translations?.[locale]?.title
        || template?.locales?.[locale]?.title
        || instance.templateSlug;
    document.getElementById('sp-plan-title').textContent = planTitle;
    document.getElementById('sp-plan-description').textContent =
        instance.translations?.[locale]?.description
        || template?.locales?.[locale]?.description
        || '';
    document.getElementById('sp-plan-started').textContent = instance.startedAt;
    const ownerEl = document.getElementById('sp-plan-owner');
    if (ownerEl) {
        ownerEl.textContent = planOwnershipLabel(instance, workspace);
    }
    const currentSprintEl = document.getElementById('sp-plan-current-sprint');
    if (currentSprintEl) {
        if (currentRange && !planStarted) {
            currentSprintEl.textContent = spT('sp.schedule.notStarted', {
                week: formatIsoWeekLabel(currentRange.isoWeek, locale),
                range: formatDateRangeShort(currentRange.start, currentRange.end, locale),
                date: instance.startedAt,
            });
        } else if (currentRange && currentNumber > 0) {
            currentSprintEl.textContent = spT('sp.schedule.currentSprint', {
                number: currentNumber,
                week: formatIsoWeekLabel(currentRange.isoWeek, locale),
                range: formatDateRangeShort(currentRange.start, currentRange.end, locale),
            });
        } else {
            currentSprintEl.textContent = String(displaySprintNumber);
        }
    }
    document.getElementById('sp-plan-status').textContent = spT(
        instance.archived ? 'sp.status.archived' : 'sp.status.active',
    );

    const teamHost = document.getElementById('sp-plan-team');
    if (teamHost) {
        teamHost.replaceChildren(renderTeamChips(normalizeTeamIds(instance), workspace, locale));
    }
    const participantsHost = document.getElementById('sp-plan-participants');
    if (participantsHost) {
        participantsHost.replaceChildren(renderParticipantChips(instance.participantIds || [], workspace));
    }

    lastRenderContext = { instance, template, sprints, workspace, locale };

    renderPlanStatusOverview(sprints, instance);

    const progressEl = document.getElementById('sp-plan-progress');
    const bar = document.getElementById('sp-plan-progress-bar');
    const label = document.getElementById('sp-plan-progress-label');
    const progressText = `${progress.percent}% (${progress.done}/${progress.total})`;
    progressEl?.setAttribute('aria-valuenow', String(progress.percent));
    progressEl?.setAttribute('aria-label', spT('sp.card.progress', { percent: progress.percent }));
    if (bar) {
        bar.style.width = `${progress.percent}%`;
    }
    if (label) {
        label.textContent = progressText;
    }
    applyPlanHeaderState(instance.id);
    applyFilterSidebarState(instance.id);

    renderScheduleBanner(schedule);

    populateFilterPeopleTeams(locale);

    const prefs = loadPreferences();
    const filters = readFilters(prefs);
    syncFilterActiveChrome(filters);
    const filtered = filterSprints(sprints, filters, currentNumber, {
        activePersonId: resolveClaimPersonId(workspace),
        teams: workspace.teams || {},
    });

    renderStatusBanner(sprints, workspace, locale, instance);
    renderUnassignedBanner(sprints, workspace);
    renderSprints(filtered, currentNumber, instance, template, locale, workspace);
    renderFilterEmpty(filtered, sprints, filters);
    syncUndoHistoryChrome(instanceId);
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
    if (show && (normalized.myTasks || normalized.unassigned) && !normalized.personId && !normalized.teamId) {
        empty.textContent = spT('sp.filter.emptyMyOrUnassigned');
    } else if (show) {
        empty.textContent = spT('sp.filter.empty');
    }
}

/**
 * @param {ReturnType<typeof computeScheduleHealth>} schedule
 */
function renderScheduleBanner(schedule) {
    let banner = document.getElementById('sp-schedule-banner');
    if (!banner) {
        const progressWrap = document.getElementById('sp-plan-progress')?.parentElement
            || document.querySelector('.sp-plan-header');
        if (!progressWrap) {
            return;
        }
        banner = document.createElement('div');
        banner.id = 'sp-schedule-banner';
        banner.className = 'sp-schedule-banner';
        progressWrap.insertAdjacentElement('afterend', banner);
    }
    if (!schedule || schedule.onTrack) {
        banner.hidden = true;
        banner.textContent = '';
        banner.classList.remove('sp-schedule-banner--behind');
        return;
    }
    banner.hidden = false;
    banner.classList.add('sp-schedule-banner--behind');
    const sources = schedule.sources
        .map((s) => spT('sp.schedule.sourceSprint', { number: s.sprintNumber, count: s.openCount }))
        .join(', ');
    banner.textContent = spT('sp.schedule.behind', {
        weeks: schedule.weeksBehind,
        count: schedule.openCount,
        sources,
    });
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
 * Ensure the claim person exists in the local people catalog (trigram chips need it).
 * @param {string} personId
 * @param {import('../storage.js').SpWorkspaceRoot} workspace
 */
function ensureClaimPersonRecord(personId, workspace) {
    if (!personId || workspace.people?.[personId]) {
        return;
    }
    const account = readAccountsBootstrap().accountUser;
    const displayName = account && String(account.id) === String(personId)
        ? String(account.displayName || account.email || personId)
        : String(personId);
    upsertPerson({
        id: personId,
        displayName,
        shortName: normalizeTrigram('', displayName),
        email: account && String(account.id) === String(personId)
            ? String(account.email || '')
            : '',
        role: '',
        colorToken: 'accent-1',
    });
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
    ensureClaimPersonRecord(personId, workspace);
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
        ensureClaimPersonRecord(personId, ctx.workspace);
        const result = claimAllUnassigned(instanceId, null, personId, ctx.template || ctx.instance.templateSnapshot);
        if (!result.ok) {
            showToast(storageErrorMessage(result.error));
            return;
        }
        const claimed = Number(result.claimed) || 0;
        if (claimed === 0) {
            showToast(spT('sp.toast.claimedAllNone'));
        } else {
            showToast(spT('sp.toast.claimedAllCount', { count: claimed }));
        }
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
            filterLogic: 'and',
        });
        prefs.planFiltersVersion = 3;
        savePreferences(prefs);
    }
    if (Number(prefs.planFiltersVersion) < 4) {
        prefs.planFilters = normalizePlanFilters({
            ...(prefs.planFilters || {}),
            filterLogic: prefs.planFilters?.filterLogic === 'or' ? 'or' : 'and',
        });
        prefs.planFiltersVersion = 4;
        savePreferences(prefs);
    }
    // Default view: OR + my tasks + unassigned (once per prefs upgrade).
    if (Number(prefs.planFiltersVersion) < 5) {
        prefs.planFilters = normalizePlanFilters({
            ...(prefs.planFilters || {}),
            myTasks: true,
            unassigned: true,
            filterLogic: 'or',
        });
        prefs.planFiltersVersion = 5;
        savePreferences(prefs);
    }
    // Soften assignee filters: myTasks+unassigned OR hid assignments to other people.
    if (Number(prefs.planFiltersVersion) < 6) {
        prefs.planFilters = normalizePlanFilters({
            ...(prefs.planFilters || {}),
            myTasks: false,
            unassigned: false,
            personId: '',
            teamId: '',
        });
        prefs.planFiltersVersion = 6;
        savePreferences(prefs);
    }
    // Restore default focus view: my tasks + unassigned combined with OR.
    if (Number(prefs.planFiltersVersion) < 7) {
        prefs.planFilters = normalizePlanFilters({
            ...(prefs.planFilters || {}),
            myTasks: true,
            unassigned: true,
            personId: '',
            teamId: '',
            filterLogic: 'or',
        });
        prefs.planFiltersVersion = 7;
        savePreferences(prefs);
    }
    const planFilters = normalizePlanFilters(prefs.planFilters || {});

    applyPlanFilterControls(planFilters);

    const checkboxMap = {
        'sp-filter-current-week': 'currentWeek',
        'sp-filter-hide-done': 'hideDone',
        'sp-filter-open-only': 'openOnly',
        'sp-filter-blocked': 'blocked',
        'sp-filter-my-tasks': 'myTasks',
        'sp-filter-unassigned': 'unassigned',
    };
    for (const id of Object.keys(checkboxMap)) {
        const el = document.getElementById(id);
        el?.addEventListener('change', () => {
            persistFilters();
            render();
        });
    }

    const logicAnd = document.getElementById('sp-filter-logic-and');
    const logicOr = document.getElementById('sp-filter-logic-or');
    for (const el of [logicAnd, logicOr]) {
        el?.addEventListener('change', () => {
            persistFilters();
            render();
        });
    }

    for (const id of ['sp-filter-person', 'sp-filter-team', 'sp-filter-status', 'sp-filter-priority']) {
        const el = document.getElementById(id);
        el?.addEventListener('change', () => {
            // Person and team are mutually exclusive picks (dropdown "Any" clears).
            if (id === 'sp-filter-person' && el.value) {
                const team = document.getElementById('sp-filter-team');
                if (team) {
                    team.value = '';
                }
            }
            if (id === 'sp-filter-team' && el.value) {
                const person = document.getElementById('sp-filter-person');
                if (person) {
                    person.value = '';
                }
            }
            persistFilters();
            render();
        });
    }

    const search = document.getElementById('sp-plan-search');
    if (search) {
        let searchTimer = 0;
        search.addEventListener('input', () => {
            window.clearTimeout(searchTimer);
            searchTimer = window.setTimeout(() => {
                persistFilters();
                render();
            }, 180);
        });
    }

    document.getElementById('sp-filter-reset')?.addEventListener('click', () => {
        resetPlanFilters(render);
    });
}

/**
 * Sync filter form controls from prefs (without rebinding listeners).
 * @param {ReturnType<typeof normalizePlanFilters>} planFilters
 */
function applyPlanFilterControls(planFilters) {
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
        if (el) {
            el.checked = Boolean(planFilters[key]);
        }
    }

    const logicAnd = document.getElementById('sp-filter-logic-and');
    const logicOr = document.getElementById('sp-filter-logic-or');
    if (logicAnd && logicOr) {
        const logic = planFilters.filterLogic === 'or' ? 'or' : 'and';
        logicAnd.checked = logic === 'and';
        logicOr.checked = logic === 'or';
    }

    for (const id of ['sp-filter-person', 'sp-filter-team', 'sp-filter-status', 'sp-filter-priority']) {
        const el = document.getElementById(id);
        if (!el) {
            continue;
        }
        const key = id.replace('sp-filter-', '');
        const prefKey = key === 'person' ? 'personId' : key === 'team' ? 'teamId' : key;
        el.value = planFilters[prefKey] || '';
    }

    const search = document.getElementById('sp-plan-search');
    if (search) {
        search.value = planFilters.search || '';
    }
}

/**
 * Keep assignee filters from hiding a task right after assigning it.
 * @param {{assigneeType?: string|null, assigneeId?: string|null}} assignment
 * @returns {boolean} true when filters were changed
 */
function revealAssignedItemInFilters(assignment) {
    const prefs = loadPreferences();
    const workspace = loadWorkspace().data;
    const { filters, changed } = filtersToRevealAssignee(
        prefs.planFilters || {},
        assignment,
        {
            activePersonId: resolveClaimPersonId(workspace),
            teams: workspace.teams || {},
        },
    );
    if (!changed) {
        return false;
    }
    prefs.planFilters = filters;
    savePreferences(prefs);
    applyPlanFilterControls(filters);
    return true;
}

/**
 * @param {string} instanceId
 * @param {() => void} render
 */
function bindPlanChrome(instanceId, render) {
    const root = document.getElementById('sp-app');
    if (!root) {
        return;
    }

    if (root.dataset.spChromeBound !== '1') {
        root.dataset.spChromeBound = '1';
        root.addEventListener('click', (event) => {
            const target = event.target;
            if (!(target instanceof Element)) {
                return;
            }
            const actionEl = target.closest('[data-sp-chrome]');
            if (!(actionEl instanceof HTMLElement)) {
                return;
            }
            const action = actionEl.getAttribute('data-sp-chrome');
            if (!action) {
                return;
            }
            event.preventDefault();
            event.stopPropagation();

            if (action === 'header-toggle') {
                const prefs = loadPreferences();
                const map = { ...(prefs.planHeaderExpanded || {}) };
                const nextExpanded = !Boolean(map[instanceId]);
                map[instanceId] = nextExpanded;
                prefs.planHeaderExpanded = map;
                savePreferences(prefs);
                applyPlanHeaderState(instanceId);
                // Collapsed plan header → banner falls back to blocked summary (or hidden).
                if (!nextExpanded) {
                    setStatusBannerState(instanceId, null, false);
                    refreshStatusBannerUi();
                }
                return;
            }

            if (action === 'filters-toggle') {
                const prefs = loadPreferences();
                const map = { ...(prefs.planFilterSidebarCollapsed || {}) };
                map[instanceId] = !Boolean(map[instanceId]);
                prefs.planFilterSidebarCollapsed = map;
                savePreferences(prefs);
                applyFilterSidebarState(instanceId);
                return;
            }

            if (action === 'expand-all') {
                setAllSprintsExpanded(instanceId, true);
                render();
                return;
            }

            if (action === 'collapse-all') {
                setAllSprintsExpanded(instanceId, false);
                render();
            }
        });
    }

    applyPlanHeaderState(instanceId);
    applyFilterSidebarState(instanceId);
    applyPlaybookFocus(getPlaybookFocus());
}

/**
 * @param {string} instanceId
 */
function applyPlanHeaderState(instanceId) {
    const prefs = loadPreferences();
    const expanded = Boolean(prefs.planHeaderExpanded?.[instanceId]);
    const header = document.getElementById('sp-plan-header');
    const expandedEl = document.getElementById('sp-plan-header-expanded');
    const toggle = document.getElementById('sp-header-size-toggle');
    if (header) {
        header.dataset.expanded = expanded ? '1' : '0';
    }
    if (expandedEl) {
        expandedEl.hidden = !expanded;
        expandedEl.toggleAttribute('hidden', !expanded);
    }
    if (toggle) {
        const label = expanded ? spT('sp.action.collapseHeader') : spT('sp.action.expandHeader');
        toggle.setAttribute('aria-expanded', expanded ? 'true' : 'false');
        toggle.setAttribute('aria-label', label);
        toggle.setAttribute('title', label);
        toggle.setAttribute('data-i18n-aria', expanded ? 'sp.action.collapseHeader' : 'sp.action.expandHeader');
        const sr = toggle.querySelector('.sr-only');
        if (sr) {
            sr.textContent = label;
            sr.setAttribute('data-i18n', expanded ? 'sp.action.collapseHeader' : 'sp.action.expandHeader');
        }
        const icon = toggle.querySelector('[data-sp-header-icon]');
        if (icon) {
            icon.className = expanded
                ? 'fa-solid fa-chevron-up'
                : 'fa-solid fa-chevron-down';
        }
    }
}

/**
 * @param {string} instanceId
 */
function applyFilterSidebarState(instanceId) {
    const prefs = loadPreferences();
    const collapsed = Boolean(prefs.planFilterSidebarCollapsed?.[instanceId]);
    const layout = document.getElementById('sp-plan-layout');
    const sidebar = document.getElementById('sp-filter-sidebar');
    const toggle = document.getElementById('sp-filter-sidebar-toggle');
    layout?.classList.toggle('sp-plan-layout--filters-collapsed', collapsed);
    if (sidebar) {
        sidebar.hidden = collapsed;
        sidebar.toggleAttribute('hidden', collapsed);
    }
    if (toggle) {
        const label = collapsed ? spT('sp.action.showFilters') : spT('sp.action.hideFilters');
        toggle.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
        toggle.setAttribute('aria-label', label);
        toggle.setAttribute('title', label);
        toggle.setAttribute('data-i18n-aria', collapsed ? 'sp.action.showFilters' : 'sp.action.hideFilters');
        toggle.classList.toggle('sp-filter-toggle--collapsed', collapsed);
        const icon = toggle.querySelector('[data-sp-filter-icon]');
        if (icon) {
            icon.className = collapsed
                ? 'fa-solid fa-filter'
                : 'fa-solid fa-filter-circle-xmark';
        }
    }
    syncFilterActiveChrome(normalizePlanFilters(prefs.planFilters || {}));
}

/**
 * Highlight filter toggle + enable reset when any plan filter is active.
 * @param {ReturnType<typeof normalizePlanFilters>} filters
 */
function syncFilterActiveChrome(filters) {
    const active = hasActiveItemFilters(filters);
    const toggle = document.getElementById('sp-filter-sidebar-toggle');
    if (toggle) {
        toggle.classList.toggle('sp-filter-toggle--active', active);
        const baseLabel = toggle.getAttribute('aria-expanded') === 'false'
            ? spT('sp.action.showFilters')
            : spT('sp.action.hideFilters');
        const title = active ? `${baseLabel} — ${spT('sp.filter.activeHint')}` : baseLabel;
        toggle.setAttribute('title', title);
        toggle.setAttribute('aria-label', title);
    }
    const reset = document.getElementById('sp-filter-reset');
    if (reset) {
        reset.disabled = !active;
    }
}

/**
 * Clear all plan filters and re-render.
 * @param {() => void} render
 */
function resetPlanFilters(render) {
    const prefs = loadPreferences();
    prefs.planFilters = emptyPlanFilters();
    savePreferences(prefs);
    applyPlanFilterControls(prefs.planFilters);
    syncFilterActiveChrome(prefs.planFilters);
    render();
}

/**
 * @param {string} instanceId
 * @param {boolean} open
 */
function setAllSprintsExpanded(instanceId, open) {
    const prefs = loadPreferences();
    prefs.expandedSprints = prefs.expandedSprints || {};
    /** @type {Record<string, boolean>} */
    const map = { ...(prefs.expandedSprints[instanceId] || {}) };
    const fromDom = [...(document.querySelectorAll('#sp-sprints details.sp-sprint[data-sprint-id]') || [])]
        .map((el) => el.getAttribute('data-sprint-id'))
        .filter(Boolean);
    const fromContext = (lastRenderContext?.sprints || []).map((sprint) => String(sprint.id));
    const ids = [...new Set([...fromDom, ...fromContext])];
    for (const id of ids) {
        map[id] = open;
    }
    prefs.expandedSprints[instanceId] = map;
    savePreferences(prefs);

    document.querySelectorAll('#sp-sprints details.sp-sprint').forEach((details) => {
        if (details instanceof HTMLDetailsElement) {
            details.open = open;
        }
    });
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
    const logicOr = document.getElementById('sp-filter-logic-or')?.checked;
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
        search: document.getElementById('sp-plan-search')?.value || prefs.planFilters?.search || '',
        filterLogic: logicOr
            ? 'or'
            : (document.getElementById('sp-filter-logic-and')
                ? 'and'
                : (prefs.planFilters?.filterLogic === 'or' ? 'or' : 'and')),
    });
}

function populateFilterPeopleTeams(locale) {
    const personSelect = document.getElementById('sp-filter-person');
    const teamSelect = document.getElementById('sp-filter-team');
    const anyLabel = spT('sp.filter.any');
    // Prefer prefs: select options are empty until this runs, so DOM value is unreliable.
    const prefs = normalizePlanFilters(loadPreferences().planFilters || {});
    if (personSelect) {
        const current = prefs.personId || personSelect.value || '';
        fillSelect(
            personSelect,
            listPeople().map((p) => ({ id: p.id, label: p.displayName })),
            current,
            true,
            anyLabel,
        );
    }
    if (teamSelect) {
        const current = prefs.teamId || teamSelect.value || '';
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

/**
 * Status counts for the expanded plan header (clickable → status banner).
 * @param {ReturnType<typeof resolveSprints>} sprints
 * @param {import('../storage.js').SpPlanInstance} [instance]
 */
/**
 * Status counts for the expanded plan header (clickable → status banner).
 * @param {ReturnType<typeof resolveSprints>} sprints
 * @param {import('../storage.js').SpPlanInstance} [instance]
 */
function renderPlanStatusOverview(sprints, instance) {
    const host = document.getElementById('sp-plan-status-overview');
    if (!host) {
        return;
    }

    /** @type {Record<string, number>} */
    const counts = {
        open: 0,
        in_progress: 0,
        on_hold: 0,
        blocked: 0,
        completed: 0,
    };
    for (const sprint of sprints) {
        for (const item of [...sprint.tasks, ...sprint.deliverables]) {
            const key = String(item.status || 'open');
            if (key in counts) {
                counts[key] += 1;
            } else {
                counts.open += 1;
            }
        }
    }

    const total = counts.open + counts.in_progress + counts.on_hold + counts.blocked + counts.completed;
    host.innerHTML = '';
    if (total === 0) {
        host.hidden = true;
        return;
    }
    host.hidden = false;

    const planId = instance?.id || document.getElementById('sp-app')?.dataset?.spInstanceId || '';
    const view = resolveStatusBannerView(sprints, planId);
    const activeStatus = view.expanded ? view.status : null;

    const title = document.createElement('p');
    title.className = 'sp-status-overview__title';
    title.textContent = spT('sp.overview.statusTitle');
    host.appendChild(title);

    const row = document.createElement('div');
    row.className = 'sp-status-overview__row';
    row.setAttribute('role', 'toolbar');
    row.setAttribute('aria-label', spT('sp.overview.statusTitle'));

    /** @type {Array<{key: string, icon: string, count: number}>} */
    const entries = [
        { key: 'open', icon: 'fa-solid fa-circle', count: counts.open },
        { key: 'in_progress', icon: 'fa-solid fa-play', count: counts.in_progress },
        { key: 'on_hold', icon: 'fa-solid fa-pause', count: counts.on_hold },
        { key: 'blocked', icon: 'fa-solid fa-ban', count: counts.blocked },
        { key: 'completed', icon: 'fa-solid fa-check', count: counts.completed },
    ];

    for (const entry of entries) {
        const cell = document.createElement('button');
        cell.type = 'button';
        cell.className = `sp-status-overview__item sp-status-overview__item--${entry.key}`;
        if (activeStatus === entry.key) {
            cell.classList.add('sp-status-overview__item--active');
        }
        const statusLabel = spT(`sp.status.${statusKeyToLabel(entry.key)}`);
        cell.title = spT('sp.banner.showStatus', { status: statusLabel });
        cell.setAttribute(
            'aria-label',
            `${statusLabel}: ${entry.count}. ${spT('sp.banner.showStatus', { status: statusLabel })}`,
        );
        cell.setAttribute('aria-pressed', activeStatus === entry.key ? 'true' : 'false');

        const icon = document.createElement('i');
        icon.className = entry.icon;
        icon.setAttribute('aria-hidden', 'true');

        const countEl = document.createElement('span');
        countEl.className = 'sp-status-overview__count';
        countEl.textContent = String(entry.count);

        const label = document.createElement('span');
        label.className = 'sp-status-overview__label';
        label.textContent = statusLabel;

        cell.append(icon, countEl, label);
        cell.addEventListener('click', () => {
            if (!planId) {
                return;
            }
            const current = getStatusBannerState(planId);
            if (current.expanded && current.status === entry.key) {
                setStatusBannerState(planId, null, false);
            } else {
                setStatusBannerState(planId, entry.key, true);
            }
            refreshStatusBannerUi();
        });
        row.appendChild(cell);
    }

    host.appendChild(row);
}

function refreshStatusBannerUi() {
    const ctx = lastRenderContext;
    if (!ctx) {
        return;
    }
    renderPlanStatusOverview(ctx.sprints, ctx.instance);
    renderStatusBanner(ctx.sprints, ctx.workspace, ctx.locale, ctx.instance);
}

function ensureStatusBannerHost() {
    let host = document.getElementById('sp-blockers');
    if (!host) {
        host = document.createElement('div');
        host.id = 'sp-blockers';
        const sprintsHost = document.getElementById('sp-sprints');
        sprintsHost?.parentNode?.insertBefore(host, sprintsHost);
    }
    return host;
}

/**
 * @param {string} planId
 * @returns {{status: string|null, expanded: boolean}}
 */
function getStatusBannerState(planId) {
    if (!planId) {
        return { status: null, expanded: false };
    }
    if (statusBannerSession.has(planId)) {
        return /** @type {{status: string|null, expanded: boolean}} */ (statusBannerSession.get(planId));
    }
    const fromPref = readStatusBannerPref(planId);
    statusBannerSession.set(planId, fromPref);
    return fromPref;
}

/**
 * @param {string} planId
 * @param {string|null} status
 * @param {boolean} expanded
 */
function setStatusBannerState(planId, status, expanded) {
    if (!planId) {
        return;
    }
    const next = { status: status || null, expanded: Boolean(expanded) };
    statusBannerSession.set(planId, next);
    writeStatusBannerPref(planId, next.status, next.expanded);
}

/**
 * @param {ReturnType<typeof resolveSprints>} sprints
 * @param {string} planId
 * @returns {{status: string|null, expanded: boolean}}
 */
function resolveStatusBannerView(sprints, planId) {
    const state = getStatusBannerState(planId);
    if (state.expanded && state.status) {
        return { status: state.status, expanded: true };
    }
    if (collectItemsByStatus(sprints, 'blocked').length > 0) {
        return { status: 'blocked', expanded: false };
    }
    return { status: null, expanded: false };
}

/**
 * @param {string} planId
 * @returns {{status: string|null, expanded: boolean}}
 */
function readStatusBannerPref(planId) {
    if (!planId) {
        return { status: null, expanded: false };
    }
    const prefs = loadPreferences();
    const map = prefs.statusBanner && typeof prefs.statusBanner === 'object'
        ? prefs.statusBanner
        : {};
    const entry = map[planId];
    if (entry && typeof entry === 'object') {
        const status = typeof entry.status === 'string' && entry.status ? entry.status : null;
        return { status, expanded: Boolean(entry.expanded) };
    }
    return { status: null, expanded: false };
}

/**
 * @param {string} planId
 * @param {string|null} status
 * @param {boolean} expanded
 */
function writeStatusBannerPref(planId, status, expanded) {
    if (!planId) {
        return;
    }
    const next = loadPreferences();
    next.statusBanner = next.statusBanner && typeof next.statusBanner === 'object'
        ? { ...next.statusBanner }
        : {};
    next.statusBanner[planId] = {
        status: status || null,
        expanded: Boolean(expanded),
    };
    next.blockersExpanded = next.blockersExpanded && typeof next.blockersExpanded === 'object'
        ? { ...next.blockersExpanded }
        : {};
    next.blockersExpanded[planId] = Boolean(expanded && status === 'blocked');
    savePreferences(next);
}

/**
 * Banner under the status overview — controlled by status buttons (no <details> toggle).
 * @param {ReturnType<typeof resolveSprints>} sprints
 * @param {import('../storage.js').SpWorkspaceRoot} workspace
 * @param {'de'|'en'} locale
 * @param {import('../storage.js').SpPlanInstance} [instance]
 */
function renderStatusBanner(sprints, workspace, locale, instance) {
    const host = ensureStatusBannerHost();
    const planId = instance?.id || document.getElementById('sp-app')?.dataset?.spInstanceId || '';
    const view = resolveStatusBannerView(sprints, planId);

    host.innerHTML = '';
    if (!view.status) {
        host.hidden = true;
        host.className = 'sp-blockers sp-status-banner';
        return;
    }
    host.hidden = false;
    host.className = `sp-blockers sp-status-banner sp-status-banner--${view.status}`
        + (view.expanded ? ' sp-status-banner--open' : '');

    const items = collectItemsByStatus(sprints, view.status);
    const statusLabel = spT(`sp.status.${statusKeyToLabel(view.status)}`);
    const titleKey = view.status === 'open'
        ? 'sp.banner.openCount'
        : (view.status === 'in_progress'
            ? 'sp.banner.inProgressCount'
            : (view.status === 'on_hold'
                ? 'sp.banner.onHoldCount'
                : (view.status === 'completed'
                    ? 'sp.banner.completedCount'
                    : 'sp.banner.blockedCount')));

    const header = document.createElement('button');
    header.type = 'button';
    header.className = 'sp-blockers__summary';
    header.setAttribute('aria-expanded', view.expanded ? 'true' : 'false');
    const summaryIcon = document.createElement('i');
    summaryIcon.className = statusBannerIcon(view.status);
    summaryIcon.setAttribute('aria-hidden', 'true');
    const summaryText = document.createElement('span');
    summaryText.className = 'sp-blockers__summary-text';
    summaryText.textContent = spT(titleKey, { count: items.length });
    const chevron = document.createElement('i');
    chevron.className = 'fa-solid fa-chevron-down sp-blockers__chevron';
    chevron.setAttribute('aria-hidden', 'true');
    header.append(summaryIcon, summaryText, chevron);
    header.addEventListener('click', () => {
        if (!planId) {
            return;
        }
        if (view.expanded) {
            setStatusBannerState(planId, null, false);
        } else {
            setStatusBannerState(planId, view.status, true);
        }
        refreshStatusBannerUi();
    });
    host.appendChild(header);

    if (!view.expanded) {
        return;
    }

    const list = document.createElement('ul');
    list.className = 'sp-blockers__list';
    if (!items.length) {
        const empty = document.createElement('li');
        empty.className = 'sp-blockers__item sp-blockers__item--empty';
        empty.textContent = spT('sp.banner.empty');
        list.appendChild(empty);
    } else {
        for (const row of items) {
            const li = document.createElement('li');
            li.className = 'sp-blockers__item';

            const main = document.createElement('div');
            main.className = 'sp-blockers__main';
            const titleEl = document.createElement('strong');
            titleEl.textContent = `#${row.sprintNumber} · ${row.item.label}`;
            const meta = document.createElement('span');
            meta.className = 'sp-blockers__meta';
            meta.textContent = [
                row.sprintTitle,
                resolveAssigneeName(row.item, workspace, locale) || spT('sp.report.unassigned'),
                statusLabel,
            ].filter(Boolean).join(' · ');
            main.append(titleEl, meta);
            li.appendChild(main);

            const actions = document.createElement('div');
            actions.className = 'sp-blockers__actions';
            const editBtn = document.createElement('button');
            editBtn.type = 'button';
            editBtn.className = 'tools-btn tools-btn--secondary tools-btn--small';

            if (view.status === 'blocked') {
                const reason = document.createElement('p');
                reason.className = 'sp-blockers__reason';
                const reasonText = String(row.item.blockerReason || '').trim();
                if (reasonText) {
                    reason.textContent = reasonText;
                } else {
                    reason.classList.add('sp-blockers__reason--empty');
                    reason.textContent = spT('sp.blockers.noReason');
                }
                li.appendChild(reason);
                editBtn.textContent = reasonText
                    ? spT('sp.blockers.editReason')
                    : spT('sp.blockers.addReason');
            } else {
                editBtn.textContent = spT('sp.action.edit');
            }

            editBtn.addEventListener('click', (event) => {
                event.preventDefault();
                event.stopPropagation();
                openItemDialog({
                    sprintId: row.sprintId,
                    kind: row.item.kind,
                    custom: row.item.custom,
                    item: row.item,
                });
            });
            actions.appendChild(editBtn);
            li.appendChild(actions);
            list.appendChild(li);
        }
    }
    host.appendChild(list);
}

/**
 * @param {string} status
 * @returns {string}
 */
function statusBannerIcon(status) {
    if (status === 'in_progress') {
        return 'fa-solid fa-play';
    }
    if (status === 'on_hold') {
        return 'fa-solid fa-pause';
    }
    if (status === 'completed') {
        return 'fa-solid fa-check';
    }
    if (status === 'open') {
        return 'fa-solid fa-circle';
    }
    return 'fa-solid fa-ban';
}

function renderSprints(sprints, currentNumber, instance, template, locale, workspace) {
    const host = document.getElementById('sp-sprints');
    const prefs = loadPreferences();
    const expanded = prefs.expandedSprints?.[instance.id] || {};
    host.innerHTML = '';

    for (const sprint of sprints) {
        const details = document.createElement('details');
        details.className = 'sp-sprint';
        details.setAttribute('data-sprint-id', sprint.id);
        const isCurrent = currentNumber > 0 && sprint.number === currentNumber;
        const isUpcoming = currentNumber === 0 && sprint.number === 1;
        const isOverdue = currentNumber > 0
            && sprint.number < currentNumber
            && !sprint.completed;
        if (isCurrent) {
            details.classList.add('sp-sprint--current');
        } else if (isUpcoming) {
            details.classList.add('sp-sprint--upcoming');
        } else if (isOverdue) {
            details.classList.add('sp-sprint--overdue');
        }
        details.open = expanded[sprint.id] !== false
            && (expanded[sprint.id] === true
                || (currentNumber > 0 && sprint.number === currentNumber)
                || (currentNumber === 0 && sprint.number === 1));
        details.addEventListener('toggle', () => {
            const next = loadPreferences();
            next.expandedSprints = next.expandedSprints || {};
            next.expandedSprints[instance.id] = next.expandedSprints[instance.id] || {};
            next.expandedSprints[instance.id][sprint.id] = details.open;
            savePreferences(next);
        });

        const summary = document.createElement('summary');
        summary.className = 'sp-sprint__summary';
        const range = sprintDateRange(instance.startedAt, sprint.number, template?.unit || 'week');
        summary.innerHTML = `
            <span class="sp-sprint__main">
                <span class="sp-sprint__number">#${sprint.number}</span>
                <span class="sp-sprint__title"></span>
            </span>
            <span class="sp-sprint__status"></span>
            <span class="sp-sprint__assignees"></span>
            <span class="sp-sprint__schedule">
                <span class="sp-sprint__kw"></span>
                <span class="sp-sprint__dates"></span>
            </span>
            <span class="sp-sprint__progress">${sprint.progress.percent}% (${sprint.progress.done}/${sprint.progress.total})</span>
        `;
        summary.querySelector('.sp-sprint__title').textContent = sprint.title;
        const kwEl = summary.querySelector('.sp-sprint__kw');
        const datesEl = summary.querySelector('.sp-sprint__dates');
        if (range && kwEl && datesEl) {
            kwEl.textContent = formatIsoWeekLabel(range.isoWeek, locale);
            datesEl.textContent = formatDateRangeShort(range.start, range.end, locale);
        }
        const assigneesHost = summary.querySelector('.sp-sprint__assignees');
        if (assigneesHost) {
            const seen = new Set();
            for (const item of [...sprint.tasks, ...sprint.deliverables]) {
                if (!item.assigneeId || seen.has(String(item.assigneeId))) {
                    continue;
                }
                seen.add(String(item.assigneeId));
                const chip = renderAssigneeChip(item, workspace, locale);
                if (chip) {
                    assigneesHost.appendChild(chip);
                }
            }
        }
        const statusHost = summary.querySelector('.sp-sprint__status');
        if (statusHost) {
            if (isCurrent || isUpcoming || isOverdue) {
                const badge = document.createElement('span');
                badge.className = 'sp-sprint__badge';
                if (isOverdue) {
                    badge.classList.add('sp-sprint__badge--overdue');
                    badge.textContent = spT('sp.sprint.overdueBadge');
                } else if (isUpcoming) {
                    badge.classList.add('sp-sprint__badge--upcoming');
                    badge.textContent = spT('sp.sprint.upcomingBadge');
                } else {
                    badge.classList.add('sp-sprint__badge--current');
                    badge.textContent = spT('sp.sprint.currentBadge');
                }
                statusHost.appendChild(badge);
            }
            const items = [...sprint.tasks, ...sprint.deliverables];
            const blockedCount = items.filter((item) => item.status === 'blocked').length;
            const inProgressCount = items.filter((item) => item.status === 'in_progress').length;
            const onHoldCount = items.filter((item) => item.status === 'on_hold').length;
            if (blockedCount > 0) {
                statusHost.appendChild(renderSprintStatusCountChip('blocked', blockedCount));
            }
            if (inProgressCount > 0) {
                statusHost.appendChild(renderSprintStatusCountChip('in_progress', inProgressCount));
            }
            if (onHoldCount > 0) {
                statusHost.appendChild(renderSprintStatusCountChip('on_hold', onHoldCount));
            }
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
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'tools-btn tools-btn--secondary tools-btn--small';
    btn.textContent = spT('sp.action.relatedStories');
    btn.addEventListener('click', () => openSprintHelp(sprint));
    row.appendChild(btn);
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
    li.className = `sp-item-wrap sp-item-wrap--${item.status}`;

    const row = document.createElement('div');
    row.className = `sp-item sp-item--${item.status}`;

    const check = document.createElement('input');
    check.type = 'checkbox';
    check.className = 'sp-item__check';
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

    const chip = renderAssigneeChip(item, workspace, locale);
    const assigneeSlot = document.createElement('div');
    assigneeSlot.className = 'sp-item__assignee-slot';
    if (chip) {
        chip.classList.add('sp-item__assignee');
        assigneeSlot.appendChild(chip);
    }

    const main = document.createElement('div');
    main.className = 'sp-item__main';
    const label = document.createElement('span');
    label.className = 'sp-item__label';
    label.textContent = item.label;
    const meta = document.createElement('span');
    meta.className = 'sp-item__meta';
    const range = sprintDateRange(instance.startedAt, sprint.number, 'week');
    const weekLabel = range ? formatIsoWeekLabel(range.isoWeek, locale) : '';
    const plannedDue = range
        ? spT('sp.schedule.dueBy', { date: formatDateShort(range.end, locale) })
        : '';
    const explicitDue = item.dueDate
        ? spT('sp.schedule.dueBy', { date: item.dueDate })
        : '';
    const metaParts = [
        spT(`sp.status.${statusKeyToLabel(item.status)}`),
        spT(`sp.priority.${item.priority}`),
        weekLabel,
        explicitDue || plannedDue,
    ].filter(Boolean);
    meta.textContent = metaParts.join(' · ');
    const srAssignee = document.createElement('span');
    srAssignee.className = 'visually-hidden';
    srAssignee.textContent = resolveAssigneeName(item, workspace, locale) || spT('sp.report.unassigned');
    main.append(label, meta, srAssignee);

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

    actions.appendChild(createIconButton({
        icon: 'owner',
        label: spT('sp.action.owner'),
        title: spT('sp.action.ownerHint'),
        className: 'sp-item__owner',
        onClick: () => openAssignDialog({
            sprintId: sprint.id,
            kind: item.kind,
            custom: item.custom,
            item,
        }),
    }));

    actions.appendChild(createIconButton({
        icon: 'edit',
        label: spT('sp.action.edit'),
        onClick: () => openItemDialog({
            sprintId: sprint.id,
            kind: item.kind,
            custom: item.custom,
            item,
        }),
    }));

    if (Array.isArray(item.attachments) && item.attachments.length > 0) {
        const attBadge = document.createElement('span');
        attBadge.className = 'sp-item__att-count';
        const count = item.attachments.length;
        attBadge.textContent = count === 1
            ? spT('sp.attachments.count', { count })
            : spT('sp.attachments.countPlural', { count });
        main.appendChild(attBadge);
    }

    // Fixed-width slot keeps assignee + action icons aligned across rows.
    const tableSlot = document.createElement('div');
    tableSlot.className = 'sp-item__table-slot';
    tableSlot.setAttribute('aria-hidden', 'true');

    const hasTable = Boolean(item.table?.columns?.length);
    /** @type {HTMLButtonElement|null} */
    let tableToggle = null;
    /** @type {HTMLSpanElement|null} */
    let tableBadge = null;
    /** @type {HTMLDivElement|null} */
    let tablePanel = null;
    /** @type {(() => void)|null} */
    let mountTableEditor = null;

    if (hasTable) {
        tableSlot.removeAttribute('aria-hidden');
        const prefs = loadPreferences();
        const expandedKey = `${instance.id}:${item.statusKey}`;
        const initiallyOpen = Boolean(prefs.expandedItemTables?.[expandedKey]);

        const syncTableToggle = (open, rowCount = item.table.rows?.length || 0) => {
            if (!tableToggle) {
                return;
            }
            const label = open
                ? spT('sp.action.collapseTable')
                : (rowCount
                    ? spT('sp.action.expandTableRows', { count: rowCount })
                    : spT('sp.action.expandTable'));
            tableToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            tableToggle.setAttribute('aria-label', label);
            tableToggle.title = label;
            if (tableBadge) {
                tableBadge.hidden = rowCount <= 0;
                tableBadge.textContent = String(rowCount);
            }
        };

        tableToggle = createIconButton({
            icon: 'list',
            label: spT('sp.action.expandTable'),
            className: 'sp-item__table-toggle',
            onClick: () => {
                if (!tablePanel || !mountTableEditor) {
                    return;
                }
                const open = tablePanel.hidden;
                tablePanel.hidden = !open;
                syncTableToggle(open);
                const next = loadPreferences();
                next.expandedItemTables = next.expandedItemTables || {};
                if (open) {
                    next.expandedItemTables[expandedKey] = true;
                    const host = tablePanel.querySelector('.sp-item__table-editor');
                    if (host && !host.childElementCount) {
                        mountTableEditor();
                    }
                } else {
                    delete next.expandedItemTables[expandedKey];
                }
                savePreferences(next);
            },
        });
        tableToggle.setAttribute('aria-expanded', initiallyOpen ? 'true' : 'false');

        tableBadge = document.createElement('span');
        tableBadge.className = 'sp-icon-btn__badge';
        tableToggle.appendChild(tableBadge);
        syncTableToggle(initiallyOpen);
        tableSlot.appendChild(tableToggle);

        tablePanel = document.createElement('div');
        tablePanel.className = 'sp-item__table-panel';
        tablePanel.hidden = !initiallyOpen;
        const editorHost = document.createElement('div');
        editorHost.className = 'sp-item__table-editor';
        tablePanel.appendChild(editorHost);

        mountTableEditor = () => {
            renderInlineTableEditor(editorHost, item.table, {
                spT,
                onChange: (nextTable) => {
                    const result = updateItemMeta(
                        instance.id,
                        item.kind,
                        item.statusKey,
                        {
                            status: item.dependencyBlocked
                                ? (item.storedStatus || 'open')
                                : item.status,
                            priority: item.priority,
                            assigneeType: item.assigneeType,
                            assigneeId: item.assigneeId,
                            dueDate: item.dueDate,
                            note: item.note,
                            blockerReason: item.dependencyBlocked
                                && item.storedStatus !== 'blocked'
                                ? ''
                                : item.blockerReason,
                            blockerSince: item.blockerSince,
                            dependsOn: item.dependsOn || [],
                            attachments: item.attachments,
                            table: nextTable,
                            labelDe: item.label,
                            labelEn: item.label,
                        },
                        item.custom,
                        sprint.id,
                    );
                    if (!result.ok) {
                        showToast(storageErrorMessage(result.error));
                        return;
                    }
                    setSaveStatus('saved');
                    item.table = nextTable;
                    syncTableToggle(!tablePanel?.hidden, nextTable.rows?.length || 0);
                },
            });
        };

        if (initiallyOpen) {
            mountTableEditor();
        }
    }

    row.append(check, main, tableSlot, assigneeSlot, actions);
    li.appendChild(row);
    if (tablePanel) {
        li.appendChild(tablePanel);
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
    if (status === 'in_progress') {
        return 'inProgress';
    }
    if (status === 'on_hold') {
        return 'onHold';
    }
    return status;
}

/**
 * Compact sprint-summary marker: colored icon + count badge.
 * @param {'blocked'|'in_progress'|'on_hold'} status
 * @param {number} count
 * @returns {HTMLSpanElement}
 */
function renderSprintStatusCountChip(status, count) {
    const chip = document.createElement('span');
    chip.className = `sp-status-mark sp-status-mark--${status}`;
    const labelKey = status === 'blocked'
        ? 'sp.sprint.blockedCount'
        : (status === 'on_hold' ? 'sp.sprint.onHoldCount' : 'sp.sprint.inProgressCount');
    chip.title = spT(labelKey, { count });
    chip.setAttribute('aria-label', spT(labelKey, { count }));

    const icon = document.createElement('i');
    icon.className = status === 'blocked'
        ? 'fa-solid fa-ban'
        : (status === 'on_hold' ? 'fa-solid fa-pause' : 'fa-solid fa-play');
    icon.setAttribute('aria-hidden', 'true');

    const badge = document.createElement('span');
    badge.className = 'sp-status-mark__badge';
    badge.textContent = String(count);

    chip.append(icon, badge);
    return chip;
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

function openAddSprintFromTemplateDialog() {
    closeHelpPanel();
    const locale = getLocale();
    const templates = readTemplatesFromDom().filter((t) => Array.isArray(t.sprints) && t.sprints.length > 0);
    if (templates.length === 0) {
        showToast(spT('sp.empty.templates'));
        return;
    }
    const templateSelect = document.getElementById('sp-add-sprint-template');
    const sprintSelect = document.getElementById('sp-add-sprint-week');
    const errorEl = document.getElementById('sp-add-sprint-template-error');
    const dialog = document.getElementById('sp-add-sprint-template-dialog');
    if (!templateSelect || !sprintSelect || !dialog) {
        return;
    }
    if (errorEl) {
        errorEl.hidden = true;
        errorEl.textContent = '';
    }
    fillSelect(
        templateSelect,
        templates.map((t) => ({
            id: t.slug,
            label: t.locales?.[locale]?.title || t.locales?.en?.title || t.slug,
        })),
        templates[0]?.slug || '',
        false,
    );
    const fillWeeks = () => {
        const template = templates.find((t) => t.slug === templateSelect.value);
        const localePack = template?.locales?.[locale] || template?.locales?.en || {};
        const textsById = Object.fromEntries((localePack.sprints || []).map((s) => [s.id, s]));
        const options = (template?.sprints || []).map((s) => ({
            id: s.id,
            label: `${s.number}. ${textsById[s.id]?.title || s.id}`,
        }));
        fillSelect(sprintSelect, options, options[0]?.id || '', false);
    };
    templateSelect.onchange = fillWeeks;
    fillWeeks();
    dialog.showModal();
}

function bindAddSprintFromTemplateDialog(instanceId, render) {
    const dialog = document.getElementById('sp-add-sprint-template-dialog');
    dialog?.addEventListener('close', () => {
        if (dialog.returnValue !== 'confirm') {
            return;
        }
        const slug = document.getElementById('sp-add-sprint-template')?.value || '';
        const sprintId = document.getElementById('sp-add-sprint-week')?.value || '';
        const errorEl = document.getElementById('sp-add-sprint-template-error');
        const template = readTemplatesFromDom().find((t) => t.slug === slug);
        if (!template || !sprintId) {
            if (errorEl) {
                errorEl.hidden = false;
                errorEl.textContent = spT('sp.error.pickTemplateRequired');
            }
            showToast(spT('sp.error.pickTemplateRequired'));
            return;
        }
        const result = addSprintFromTemplate(instanceId, template, sprintId);
        if (!result.ok) {
            showToast(storageErrorMessage(result.error));
            return;
        }
        showToast(spT('sp.toast.sprintFromTemplate'));
        render();
    });
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

    document.getElementById('sp-item-delete')?.addEventListener('click', () => {
        if (itemDialogState.create || !itemDialogState.item) {
            return;
        }
        if (!window.confirm(spT('sp.confirm.deleteItem'))) {
            return;
        }
        const result = removePlanItem(instanceId, {
            custom: Boolean(itemDialogState.custom),
            sprintId: itemDialogState.sprintId,
            kind: itemDialogState.kind,
            itemId: itemDialogState.item.id,
            statusKey: itemDialogState.item.statusKey,
        });
        if (!result.ok) {
            showToast(storageErrorMessage(result.error));
            return;
        }
        const dialog = document.getElementById('sp-item-dialog');
        if (dialog?.open) {
            dialog.close('cancel');
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
        const isTemplateItem = Boolean(itemDialogState.item && !itemDialogState.custom && !itemDialogState.create);
        /** @type {import('../item-table.js').SpItemTable|null|undefined} */
        let table;
        if (isTemplateItem) {
            // Rows are edited inline under the item; keep existing override/template merge.
            table = undefined;
        } else {
            const columnsRaw = document.querySelector('#sp-item-table-editor [data-sp-table-columns]')?.value || '';
            const columns = parseTableColumnsText(columnsRaw);
            if (columns.length) {
                table = {
                    columns,
                    rows: itemDialogState.item?.table?.rows || [],
                };
            } else {
                table = null;
            }
        }
        const data = {
            id: document.getElementById('sp-item-id').value,
            labelDe: allowLabels
                ? (document.getElementById('sp-item-label-de').value || '')
                : (itemDialogState.item?.label || ''),
            labelEn: allowLabels
                ? (document.getElementById('sp-item-label-en').value || '')
                : (itemDialogState.item?.label || ''),
            assigneeType: 'person',
            assigneeId: document.getElementById('sp-item-assignee-id').value || null,
            status,
            priority: document.getElementById('sp-item-priority').value,
            dueDate: document.getElementById('sp-item-due').value || null,
            note: document.getElementById('sp-item-note').value,
            table,
            blockerReason,
            blockerSince,
            dependsOn: readDependsOnSelect(),
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
        revealAssignedItemInFilters({
            assigneeType: data.assigneeType,
            assigneeId: data.assigneeId,
        });
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
                assigneeType: 'person',
                assigneeId: document.getElementById('sp-assign-assignee-id')?.value || null,
            },
        );
        assignDialogState = null;
        if (!result.ok) {
            showToast(storageErrorMessage(result.error));
            return;
        }
        const assignment = {
            assigneeType: 'person',
            assigneeId: document.getElementById('sp-assign-assignee-id')?.value || null,
        };
        const filtersAdjusted = revealAssignedItemInFilters(assignment);
        showToast(filtersAdjusted ? spT('sp.toast.assignedFilterAdjusted') : spT('sp.toast.assigned'));
        render();
    });

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

/**
 * @param {{sprintId: string, kind: string, custom?: boolean, create?: boolean, item?: object}} state
 */
function fillDependsOnSelect(state) {
    const select = /** @type {HTMLSelectElement|null} */ (document.getElementById('sp-item-depends-on'));
    if (!select) {
        return;
    }
    select.innerHTML = '';
    const sprint = lastRenderContext?.sprints?.find((entry) => entry.id === state.sprintId);
    const peers = sprint
        ? [...(sprint.tasks || []), ...(sprint.deliverables || [])]
        : [];
    const selfKey = state.item?.statusKey || '';
    const selected = new Set(Array.isArray(state.item?.dependsOn) ? state.item.dependsOn : []);
    for (const peer of peers) {
        if (peer.statusKey === selfKey) {
            continue;
        }
        const opt = document.createElement('option');
        opt.value = peer.statusKey;
        const kindLabel = peer.kind === 'deliverable'
            ? spT('sp.deps.deliverable')
            : spT('sp.deps.task');
        opt.textContent = `${kindLabel}: ${peer.label}`;
        opt.selected = selected.has(peer.statusKey);
        select.appendChild(opt);
    }
}

/**
 * @returns {string[]}
 */
function readDependsOnSelect() {
    const select = /** @type {HTMLSelectElement|null} */ (document.getElementById('sp-item-depends-on'));
    if (!select) {
        return [];
    }
    return [...select.selectedOptions].map((opt) => opt.value).filter(Boolean);
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
    const defaultAssigneeId = state.create
        ? activePersonId
        : (state.item?.assigneeType === 'team' ? '' : (state.item?.assigneeId || ''));
    const typeEl = document.getElementById('sp-item-assignee-type');
    if (typeEl) {
        typeEl.value = 'person';
    }
    const storedStatus = state.item?.dependencyBlocked
        ? (state.item?.storedStatus || 'open')
        : (state.item?.status || 'open');
    document.getElementById('sp-item-status').value = storedStatus;
    document.getElementById('sp-item-priority').value = state.item?.priority || 'normal';
    document.getElementById('sp-item-due').value = state.item?.dueDate || '';
    document.getElementById('sp-item-note').value = state.item?.note || '';
    const blockerReasonEl = document.getElementById('sp-item-blocker-reason');
    if (blockerReasonEl) {
        const showManualReason = storedStatus === 'blocked'
            || (state.item?.blockerReason && !state.item?.dependencyBlocked);
        blockerReasonEl.value = showManualReason ? (state.item?.blockerReason || '') : '';
    }
    fillDependsOnSelect(state);
    dialogAttachments = normalizeAttachments(state.item?.attachments || []);
    const instanceId = document.getElementById('sp-app')?.dataset?.spInstanceId || '';
    renderDialogAttachments(instanceId);
    const tableHost = document.getElementById('sp-item-table-editor');
    const tableField = document.getElementById('sp-item-table-field');
    if (tableHost && tableField) {
        if (isTemplateItem) {
            tableField.hidden = true;
            tableHost.innerHTML = '';
        } else {
            tableField.hidden = false;
            renderTableEditor(tableHost, state.item?.table || null, {
                spT,
                columnsOnly: true,
            });
        }
    }
    refreshAssigneeOptions(defaultAssigneeId || '');
    updateBlockerFieldVisibility();
    const title = document.getElementById('sp-item-dialog-title');
    if (title) {
        title.textContent = state.create
            ? spT('sp.dialog.itemCreateTitle')
            : spT('sp.dialog.itemTitle');
    }
    const deleteBtn = document.getElementById('sp-item-delete');
    if (deleteBtn) {
        deleteBtn.hidden = Boolean(state.create || !state.item);
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
    const typeEl = document.getElementById('sp-assign-assignee-type');
    if (typeEl) {
        typeEl.value = 'person';
    }
    const selectedId = state.item.assigneeType === 'team' ? '' : (state.item.assigneeId || '');
    refreshAssignAssigneeOptions(selectedId);
    document.getElementById('sp-assign-dialog')?.showModal();
    document.getElementById('sp-assign-assignee-id')?.focus();
}

/**
 * @param {string} [selectedId]
 */
function refreshAssignAssigneeOptions(selectedId) {
    const workspace = loadWorkspace().data;
    const select = document.getElementById('sp-assign-assignee-id');
    if (!select) {
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
    const select = document.getElementById('sp-item-assignee-id');
    if (!select) {
        return;
    }
    const selectedValue = typeof selected === 'string' ? selected : select.value;
    const typeEl = document.getElementById('sp-item-assignee-type');
    if (typeEl) {
        typeEl.value = 'person';
    }
    fillSelect(
        select,
        listPeople().map((p) => ({ id: p.id, label: p.displayName })),
        selectedValue,
        true,
        spT('sp.field.none'),
    );
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
        if (event.key !== 'Escape') {
            return;
        }
        const report = document.getElementById('sp-status-report');
        if (report && !report.hidden) {
            closeStatusReport();
        }
        const historyDialog = document.getElementById('sp-history-dialog');
        if (historyDialog?.open) {
            historyDialog.close();
        }
    });
    updateReportModeButtons();
}

/**
 * @param {string} instanceId
 */
function syncUndoHistoryChrome(instanceId) {
    const undoBtn = document.getElementById('sp-undo-btn');
    if (undoBtn) {
        undoBtn.hidden = false;
        undoBtn.disabled = !canUndoInstance(instanceId);
    }
    const historyBtn = document.getElementById('sp-history-btn');
    if (historyBtn) {
        historyBtn.hidden = !usesServerPlans();
    }
}

/**
 * @param {string} instanceId
 */
function bindUndoAndHistory(instanceId) {
    document.getElementById('sp-undo-btn')?.addEventListener('click', () => {
        const result = undoLastInstanceChange(instanceId);
        if (!result.ok) {
            showToast(result.error === 'nothing-to-undo'
                ? spT('sp.history.nothingToUndo')
                : storageErrorMessage(result.error));
            return;
        }
        showToast(spT('sp.toast.undone'));
        rerender();
    });

    document.getElementById('sp-history-btn')?.addEventListener('click', () => {
        openHistoryDialog(instanceId);
    });
    document.getElementById('sp-history-close')?.addEventListener('click', () => {
        document.getElementById('sp-history-dialog')?.close();
    });
}

/**
 * @param {string} instanceId
 */
async function openHistoryDialog(instanceId) {
    const dialog = document.getElementById('sp-history-dialog');
    const list = document.getElementById('sp-history-list');
    const detail = document.getElementById('sp-history-detail');
    if (!dialog || !list || !detail) {
        return;
    }
    detail.hidden = true;
    detail.innerHTML = '';
    list.innerHTML = `<p class="sp-password-note">${spT('sp.history.loading')}</p>`;
    dialog.showModal();

    if (!usesServerPlans()) {
        list.innerHTML = `<p class="sp-password-note">${spT('sp.history.accountsOnly')}</p>`;
        return;
    }

    try {
        const data = await fetchPlanHistory(instanceId);
        const revisions = Array.isArray(data.revisions) ? data.revisions : [];
        if (!revisions.length) {
            list.innerHTML = `<p class="sp-password-note">${spT('sp.history.empty')}</p>`;
            return;
        }
        list.innerHTML = '';
        const ul = document.createElement('ul');
        ul.className = 'sp-history-entries';
        for (const rev of revisions) {
            const li = document.createElement('li');
            li.className = 'sp-history-entry';
            const when = String(rev.createdAt || '');
            const actor = String(rev.actorLabel || rev.actorUserId || '—');
            const summary = String(rev.summary || rev.action || '');
            const meta = document.createElement('div');
            meta.className = 'sp-history-entry__meta';
            meta.textContent = `${formatHistoryWhen(when)} · ${actor}`;
            const summaryEl = document.createElement('div');
            summaryEl.className = 'sp-history-entry__summary';
            summaryEl.textContent = summary;
            const actions = document.createElement('div');
            actions.className = 'sp-history-entry__actions';
            const detailBtn = document.createElement('button');
            detailBtn.type = 'button';
            detailBtn.className = 'tools-btn tools-btn--secondary tools-btn--small';
            detailBtn.textContent = spT('sp.history.showDiff');
            detailBtn.addEventListener('click', () => showHistoryDetail(instanceId, String(rev.id)));
            const restoreBtn = document.createElement('button');
            restoreBtn.type = 'button';
            restoreBtn.className = 'tools-btn tools-btn--primary tools-btn--small';
            restoreBtn.textContent = spT('sp.history.restore');
            restoreBtn.addEventListener('click', () => restoreHistoryRevision(instanceId, String(rev.id)));
            actions.append(detailBtn, restoreBtn);
            li.append(meta, summaryEl, actions);
            ul.appendChild(li);
        }
        list.appendChild(ul);
    } catch (error) {
        if (error instanceof Error && error.message === 'plans-history-forbidden') {
            list.innerHTML = `<p class="sp-password-note">${spT('sp.history.forbidden')}</p>`;
        } else if (error instanceof Error && error.message === 'plans-history-unauthorized') {
            list.innerHTML = `<p class="sp-password-note">${spT('sp.history.accountsOnly')}</p>`;
        } else if (error instanceof Error && error.message === 'plans-history-not-found') {
            list.innerHTML = `<p class="sp-password-note">${spT('sp.history.notFound')}</p>`;
        } else {
            list.innerHTML = `<p class="sp-password-note">${spT('sp.history.loadFailed')}</p>`;
        }
    }
}

/**
 * @param {string} when
 */
function formatHistoryWhen(when) {
    if (!when) {
        return '—';
    }
    try {
        return new Date(when).toLocaleString(getLocale() === 'de' ? 'de-DE' : 'en-GB');
    } catch {
        return when;
    }
}

/**
 * @param {string} instanceId
 * @param {string} revisionId
 */
async function showHistoryDetail(instanceId, revisionId) {
    const detail = document.getElementById('sp-history-detail');
    if (!detail) {
        return;
    }
    detail.hidden = false;
    detail.textContent = spT('sp.history.loading');
    try {
        const data = await fetchPlanRevision(instanceId, revisionId);
        const revision = data.revision || {};
        const snapshot = revision.snapshot && typeof revision.snapshot === 'object' ? revision.snapshot : {};
        const current = loadWorkspace().data.instances[instanceId] || {};
        const lines = buildHistoryDiffLines(current, snapshot);
        detail.innerHTML = '';
        const title = document.createElement('h3');
        title.className = 'sp-history-detail__title';
        title.textContent = spT('sp.history.diffTitle');
        detail.appendChild(title);
        if (!lines.length) {
            const empty = document.createElement('p');
            empty.className = 'sp-password-note';
            empty.textContent = spT('sp.history.diffEmpty');
            detail.appendChild(empty);
            return;
        }
        const ul = document.createElement('ul');
        ul.className = 'sp-history-diff';
        for (const line of lines) {
            const li = document.createElement('li');
            li.textContent = line;
            ul.appendChild(li);
        }
        detail.appendChild(ul);
    } catch {
        detail.textContent = spT('sp.history.loadFailed');
    }
}

/**
 * @param {Record<string, unknown>} current
 * @param {Record<string, unknown>} snapshot
 * @returns {string[]}
 */
function buildHistoryDiffLines(current, snapshot) {
    /** @type {string[]} */
    const lines = [];
    const keys = [
        'completedTasks',
        'completedDeliverables',
        'customTasks',
        'customDeliverables',
        'customSprints',
        'itemOverrides',
        'sprintOverrides',
        'removedItemKeys',
        'fieldValues',
        'sprintNotes',
        'participantIds',
        'teamIds',
        'status',
        'archived',
    ];
    for (const key of keys) {
        const a = JSON.stringify(current[key] ?? null);
        const b = JSON.stringify(snapshot[key] ?? null);
        if (a !== b) {
            lines.push(`${key}: ${summarizeJsonChange(snapshot[key], current[key])}`);
        }
    }
    return lines;
}

/**
 * @param {unknown} before
 * @param {unknown} after
 */
function summarizeJsonChange(before, after) {
    const beforeLen = Array.isArray(before)
        ? before.length
        : (before && typeof before === 'object' ? Object.keys(before).length : (before == null ? 0 : 1));
    const afterLen = Array.isArray(after)
        ? after.length
        : (after && typeof after === 'object' ? Object.keys(after).length : (after == null ? 0 : 1));
    return spT('sp.history.diffSummary', { before: beforeLen, after: afterLen });
}

/**
 * @param {string} instanceId
 * @param {string} revisionId
 */
async function restoreHistoryRevision(instanceId, revisionId) {
    if (!window.confirm(spT('sp.confirm.restoreRevision'))) {
        return;
    }
    try {
        const data = await restorePlanRevision(instanceId, revisionId);
        const plan = data.plan;
        if (!plan || typeof plan !== 'object') {
            showToast(spT('sp.history.restoreFailed'));
            return;
        }
        const loaded = loadWorkspace();
        loaded.data.instances[instanceId] = /** @type {any} */ (plan);
        const saved = saveWorkspace(loaded.data, { dirtyPlanIds: [] });
        if (!saved.ok) {
            showToast(storageErrorMessage(saved.error));
            return;
        }
        document.getElementById('sp-history-dialog')?.close();
        showToast(spT('sp.toast.restored'));
        rerender();
    } catch {
        showToast(spT('sp.history.restoreFailed'));
    }
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
        document.getElementById('sp-status-report-close')?.focus();
    }
}

function closeStatusReport() {
    const el = document.getElementById('sp-status-report');
    if (!el || el.hidden) {
        return;
    }
    // Move focus out before aria-hidden, otherwise AT warns about focused descendants.
    const opener = document.getElementById('sp-status-report-btn');
    if (el.contains(document.activeElement)) {
        if (opener) {
            opener.focus();
        } else if (document.activeElement instanceof HTMLElement) {
            document.activeElement.blur();
        }
    }
    el.hidden = true;
    el.setAttribute('aria-hidden', 'true');
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
