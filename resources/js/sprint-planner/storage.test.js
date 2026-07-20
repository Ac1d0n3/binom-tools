import { beforeEach, describe, expect, it, vi } from 'vitest';
import {
    WORKSPACE_KEY,
    DEMO_WORKSPACE_KEY,
    createDefaultWorkspace,
    createDefaultPreferences,
    loadWorkspace,
    loadPreferences,
    normalizeWorkspace,
    saveWorkspace,
    savePreferences,
    setLastOpenedPlanId,
    clearLastOpenedPlanIdIf,
} from './storage.js';
import { createPlanId, createPersonId, statusKey } from './ids.js';
import {
    calculateCurrentSprintNumber,
    calculateOverallProgress,
    resolveSprints,
} from './progress.js';
import { countActiveItemFilters, emptyPlanFilters, filterSprints, filtersToRevealAssignee, hasActiveItemFilters, itemMatchesFilters, normalizePlanFilters } from './filters.js';
import {
    EXPORT_TYPE_WORKSPACE,
    applyImport,
    buildWorkspaceExport,
    validateImportPayload,
} from './export-import.js';
import { addCustomSprint, addSprintFromTemplate, claimAllUnassigned, claimItem, setPlanPassword, startInstanceFromTemplate, toggleCompleted, canUndoInstance, undoLastInstanceChange, updateItemMeta } from './instance-manager.js';
import {
    createLinkAttachment,
    isAllowedAttachment,
    normalizeAttachments,
    validateAttachmentFile,
} from './attachments.js';
import { hashPassword, isPasswordProtected, verifyPassword } from './plan-password.js';
import { ensureDefaultCatalog, DEFAULT_PEOPLE, DEFAULT_TEAMS } from './defaults.js';
import {
    collectTimeRows,
    normalizeMinutes,
    resolveActualMinutes,
    resolvePlannedMinutes,
    timeVariance,
} from './time.js';

function memoryStorage() {
    const map = new Map();
    return {
        getItem: (key) => (map.has(key) ? map.get(key) : null),
        setItem: (key, value) => map.set(key, String(value)),
        removeItem: (key) => map.delete(key),
        clear: () => map.clear(),
    };
}

function stubAccountsRoot({ accountsEnabled = false, accountUser = null } = {}) {
    const root = {
        dataset: {
            accountsEnabled: accountsEnabled ? '1' : '0',
            accountUser: accountUser ? JSON.stringify(accountUser) : 'null',
            plansApiUrl: '',
            storiesApiUrl: '',
            loginUrl: '/login',
            accountPeople: '[]',
            accountTeams: '[]',
            serverPlans: '[]',
            readSlugs: '[]',
        },
    };
    vi.stubGlobal('document', {
        getElementById: (id) => (id === 'sp-app' ? root : null),
        querySelector: () => null,
    });
}

beforeEach(() => {
    vi.stubGlobal('localStorage', memoryStorage());
    vi.stubGlobal('sessionStorage', memoryStorage());
    vi.stubGlobal('window', {
        dispatchEvent: () => true,
        CustomEvent: class CustomEvent {
            constructor(type, init) {
                this.type = type;
                this.detail = init?.detail;
            }
        },
    });
    stubAccountsRoot({ accountsEnabled: false });
});

describe('storage', () => {
    it('loads default workspace when empty', () => {
        const result = loadWorkspace();
        expect(result.ok).toBe(true);
        expect(result.data.schemaVersion).toBe(1);
        expect(result.data.instances).toEqual({});
        expect(result.data.people.person_thomas_a.displayName).toBe('Thomas A');
        expect(result.data.teams.team_q.name.en).toBe('Team Q');
        expect(result.data.workspace.activePersonId).toBe('person_thomas_a');
    });

    it('roundtrips workspace data', () => {
        const workspace = loadWorkspace().data;
        workspace.people.person_01 = {
            id: 'person_01',
            displayName: 'Ada',
            shortName: 'A',
            email: '',
            role: 'Lead',
            colorToken: 'accent-1',
            archived: false,
        };
        expect(saveWorkspace(workspace).ok).toBe(true);
        const loaded = loadWorkspace();
        expect(loaded.data.people.person_01.displayName).toBe('Ada');
        expect(loaded.data.people.person_thomas_a.displayName).toBe('Thomas A');
    });

    it('recovers from corrupt JSON', () => {
        localStorage.setItem(WORKSPACE_KEY, '{not-json');
        const loaded = loadWorkspace();
        expect(loaded.ok).toBe(false);
        expect(loaded.error).toBe('storage-corrupt');
        expect(loaded.data.people.person_matthias.displayName).toBe('Matthias');
    });

    it('normalizes partial payloads', () => {
        const normalized = normalizeWorkspace({ schemaVersion: 1, people: { x: { id: 'x' } } });
        expect(normalized.workspace.id).toBe('workspace_default');
        expect(normalized.people.x.id).toBe('x');
    });

    it('stores guest demo workspace in sessionStorage when accounts are on', () => {
        stubAccountsRoot({ accountsEnabled: true, accountUser: null });
        const workspace = loadWorkspace().data;
        workspace.instances.plan_demo = {
            id: 'plan_demo',
            templateSlug: 'demo',
            templateVersion: 1,
            translations: { de: { title: 'Demo' }, en: { title: 'Demo' } },
            startedAt: '2026-08-17',
            status: 'active',
            teamIds: [],
            participantIds: [],
            completedTasks: [],
            completedDeliverables: [],
            fieldValues: {},
            sprintNotes: {},
            customTasks: {},
            customDeliverables: {},
            customSprints: [],
            sprintOverrides: {},
            itemOverrides: {},
            ephemeral: true,
            archived: false,
            createdAt: '2026-08-17T00:00:00.000Z',
            updatedAt: '2026-08-17T00:00:00.000Z',
        };
        expect(saveWorkspace(workspace).ok).toBe(true);
        expect(sessionStorage.getItem(DEMO_WORKSPACE_KEY)).toBeTruthy();
        expect(localStorage.getItem(WORKSPACE_KEY)).toBeNull();
        expect(loadWorkspace().data.instances.plan_demo.id).toBe('plan_demo');
    });
});

describe('ids', () => {
    it('creates stable prefixed ids', () => {
        expect(createPersonId().startsWith('person_')).toBe(true);
        expect(createPlanId().startsWith('plan_')).toBe(true);
        expect(statusKey('slug', 'week-01', 'task', 'a')).toBe('slug:week-01:task:a');
    });
});

describe('progress', () => {
    const template = {
        slug: 'demo',
        duration: 2,
        unit: 'week',
        sprints: [
            {
                id: 'week-01',
                number: 1,
                notes: true,
                tasks: [{ id: 't1', assigneeType: 'person', assigneeId: null }],
                deliverables: [{ id: 'd1' }],
                fields: [],
            },
            {
                id: 'week-02',
                number: 2,
                notes: true,
                tasks: [{ id: 't2', assigneeType: 'person', assigneeId: null }],
                deliverables: [],
                fields: [],
            },
        ],
        locales: {
            en: {
                title: 'Demo',
                description: '',
                sprints: [
                    {
                        id: 'week-01',
                        title: 'W1',
                        goal: 'G1',
                        tasks: [{ id: 't1', label: 'Task 1' }],
                        deliverables: [{ id: 'd1', label: 'Del 1' }],
                        fields: [],
                    },
                    {
                        id: 'week-02',
                        title: 'W2',
                        goal: 'G2',
                        tasks: [{ id: 't2', label: 'Task 2' }],
                        deliverables: [],
                        fields: [],
                    },
                ],
            },
            de: {
                title: 'Demo DE',
                description: '',
                sprints: [
                    {
                        id: 'week-01',
                        title: 'W1 DE',
                        goal: 'G1',
                        tasks: [{ id: 't1', label: 'Aufgabe 1' }],
                        deliverables: [{ id: 'd1', label: 'Ergebnis 1' }],
                        fields: [],
                    },
                    {
                        id: 'week-02',
                        title: 'W2 DE',
                        goal: 'G2',
                        tasks: [{ id: 't2', label: 'Aufgabe 2' }],
                        deliverables: [],
                        fields: [],
                    },
                ],
            },
        },
    };

    it('calculates overall progress across all items', () => {
        const instance = {
            ...createDefaultWorkspace().instances,
            id: 'plan_x',
            templateSlug: 'demo',
            templateVersion: 1,
            translations: {},
            startedAt: '2026-01-01',
            status: 'active',
            teamIds: [],
            participantIds: [],
            completedTasks: [statusKey('demo', 'week-01', 'task', 't1')],
            completedDeliverables: [],
            fieldValues: {},
            sprintNotes: {},
            customTasks: {},
            customDeliverables: {},
            customSprints: [],
            sprintOverrides: {},
            itemOverrides: {},
            archived: false,
            createdAt: '',
            updatedAt: '',
        };
        const sprints = resolveSprints(template, instance, 'en');
        expect(calculateOverallProgress(sprints)).toEqual({ done: 1, total: 3, percent: 33 });
    });

    it('shows status completed when completedTasks wins over stale open override', () => {
        const key = statusKey('demo', 'week-01', 'task', 't1');
        const instance = {
            id: 'plan_x',
            templateSlug: 'demo',
            templateVersion: 1,
            translations: {},
            startedAt: '2026-01-01',
            status: 'active',
            teamIds: [],
            participantIds: [],
            completedTasks: [key],
            completedDeliverables: [],
            fieldValues: {},
            sprintNotes: {},
            customTasks: {},
            customDeliverables: {},
            customSprints: [],
            sprintOverrides: {},
            itemOverrides: {
                [key]: { status: 'open', assigneeType: 'person', assigneeId: 'person_1' },
            },
            archived: false,
            createdAt: '',
            updatedAt: '',
        };
        const sprints = resolveSprints(template, instance, 'en');
        expect(sprints[0].tasks[0].completed).toBe(true);
        expect(sprints[0].tasks[0].status).toBe('completed');
    });

    it('keeps progress keys stable across locale switch', () => {
        const instance = {
            id: 'plan_x',
            templateSlug: 'demo',
            templateVersion: 1,
            translations: {},
            startedAt: '2026-01-01',
            status: 'active',
            teamIds: [],
            participantIds: [],
            completedTasks: [statusKey('demo', 'week-01', 'task', 't1')],
            completedDeliverables: [],
            fieldValues: {},
            sprintNotes: {},
            customTasks: {},
            customDeliverables: {},
            customSprints: [],
            sprintOverrides: {},
            itemOverrides: {},
            archived: false,
            createdAt: '',
            updatedAt: '',
        };
        const en = resolveSprints(template, instance, 'en');
        const de = resolveSprints(template, instance, 'de');
        expect(en[0].tasks[0].statusKey).toBe(de[0].tasks[0].statusKey);
        expect(en[0].tasks[0].completed).toBe(true);
        expect(de[0].tasks[0].completed).toBe(true);
        expect(de[0].tasks[0].label).toBe('Aufgabe 1');
    });

    it('keeps snapshot assignees when live template metadata changes', () => {
        const liveTemplate = structuredClone(template);
        liveTemplate.sprints[0].tasks[0].assigneeId = null;
        const instance = {
            id: 'plan_x',
            templateSlug: 'demo',
            templateVersion: 1,
            translations: {},
            startedAt: '2026-01-01',
            status: 'active',
            teamIds: [],
            participantIds: [],
            completedTasks: [],
            completedDeliverables: [],
            fieldValues: {},
            sprintNotes: {},
            customTasks: {},
            customDeliverables: {},
            customSprints: [],
            sprintOverrides: {},
            itemOverrides: {},
            templateSnapshot: structuredClone(template),
            archived: false,
            createdAt: '',
            updatedAt: '',
        };
        instance.templateSnapshot.sprints[0].tasks[0].assigneeType = 'person';
        instance.templateSnapshot.sprints[0].tasks[0].assigneeId = 'person_01';

        const sprints = resolveSprints(liveTemplate, instance, 'en');

        expect(sprints[0].tasks[0].assigneeType).toBe('person');
        expect(sprints[0].tasks[0].assigneeId).toBe('person_01');
    });

    it('calculates current sprint by week', () => {
        expect(calculateCurrentSprintNumber('2026-01-01', 13, 'week', new Date(2026, 0, 1))).toBe(1);
        expect(calculateCurrentSprintNumber('2026-01-01', 13, 'week', new Date(2026, 0, 8))).toBe(2);
        expect(calculateCurrentSprintNumber('2026-01-01', 13, 'week', new Date(2025, 11, 31))).toBe(0);
        expect(calculateCurrentSprintNumber('2026-08-17', 13, 'week', new Date(2026, 6, 19))).toBe(0);
        expect(calculateCurrentSprintNumber('2026-01-01', 13, 'week', new Date(2026, 11, 31))).toBe(13);
    });

    it('migrates legacy teamId into teamIds', () => {
        const ws = normalizeWorkspace({
            schemaVersion: 1,
            workspace: {},
            people: {},
            teams: {},
            instances: {
                plan_legacy: {
                    id: 'plan_legacy',
                    templateSlug: 'x',
                    startedAt: '2026-01-01',
                    teamId: 'team_q',
                    participantIds: [],
                },
            },
        });
        expect(ws.instances.plan_legacy.teamIds).toEqual(['team_q']);
        expect(ws.instances.plan_legacy.teamId).toBeNull();
    });

    it('backfills empty teamIds to Team Q via ensureDefaultCatalog', () => {
        const base = createDefaultWorkspace();
        base.instances.plan_empty = {
            id: 'plan_empty',
            templateSlug: 'x',
            templateVersion: 1,
            translations: {},
            startedAt: '2026-01-01',
            status: 'active',
            teamIds: [],
            teamId: null,
            participantIds: [],
            completedTasks: [],
            completedDeliverables: [],
            fieldValues: {},
            sprintNotes: {},
            customTasks: {},
            customDeliverables: {},
            customSprints: [],
            sprintOverrides: {},
            itemOverrides: {},
            templateSnapshot: null,
            passwordHash: null,
            passwordSalt: null,
            ownerUserId: null,
            viewerUserIds: [],
            viewerTeamIds: [],
            linkedStorySlugs: [],
            ephemeral: false,
            archived: false,
            createdAt: '2026-01-01T00:00:00.000Z',
            updatedAt: '2026-01-01T00:00:00.000Z',
        };
        const { workspace, changed } = ensureDefaultCatalog(base);
        expect(changed).toBe(true);
        expect(workspace.workspace.defaultTeamId).toBe('team_q');
        expect(workspace.instances.plan_empty.teamIds).toEqual(['team_q']);
    });
});

describe('schedule helpers', () => {
    it('resolves sprint date range and ISO week from start', async () => {
        const { sprintDateRange, formatIsoWeekLabel, computeScheduleHealth } = await import('./progress.js');
        const range = sprintDateRange('2026-06-29', 1, 'week');
        expect(range).not.toBeNull();
        expect(range.isoWeek).toBeGreaterThan(0);
        expect(formatIsoWeekLabel(28, 'de')).toBe('KW 28');
        expect(formatIsoWeekLabel(28, 'en')).toBe('W28');

        const health = computeScheduleHealth([
            {
                number: 1,
                title: 'One',
                tasks: [{ completed: false, status: 'open' }],
                deliverables: [],
            },
            {
                number: 2,
                title: 'Two',
                tasks: [{ completed: true, status: 'completed' }],
                deliverables: [],
            },
        ], 3);
        expect(health.onTrack).toBe(false);
        expect(health.openCount).toBe(1);
        expect(health.weeksBehind).toBe(2);
        expect(health.sources[0].sprintNumber).toBe(1);
    });
});

describe('trigram', () => {
    it('normalizes short names to three characters', async () => {
        const { normalizeTrigram, trigramFromName, normalizeTeamIds } = await import('./trigram.js');
        expect(trigramFromName('Thomas Lindackers')).toMatch(/^[A-ZÀ-ÿ0-9]{3}$/);
        expect(normalizeTrigram('TL', 'Thomas Lindackers')).toBe('TL');
        expect(normalizeTrigram('TL', 'Thomas Lindackers')).toMatch(/^[A-Z]{2,3}$/);
        expect(normalizeTrigram('ABCD', 'X')).toBe('ABC');
        expect(normalizeTeamIds({ teamId: 'team_q' })).toEqual(['team_q']);
        expect(normalizeTeamIds({ teamIds: ['a', 'b'], teamId: 'c' })).toEqual(['a', 'b']);
    });
});

describe('filters', () => {
    it('filters my tasks by active person', () => {
        const item = {
            completed: false,
            status: 'open',
            priority: 'normal',
            assigneeType: 'person',
            assigneeId: 'person_01',
        };
        expect(itemMatchesFilters(item, { myTasks: true }, { activePersonId: 'person_01' })).toBe(true);
        expect(itemMatchesFilters(item, { myTasks: true }, { activePersonId: 'person_02' })).toBe(false);
    });

    it('treats currentWeek as an active filter', () => {
        expect(hasActiveItemFilters({ currentWeek: true })).toBe(true);
        expect(hasActiveItemFilters({})).toBe(false);
        expect(hasActiveItemFilters({ myTasks: true })).toBe(true);
        expect(countActiveItemFilters({ currentWeek: true, hideDone: true, search: 'api' })).toBe(3);
    });

    it('emptyPlanFilters clears all facets', () => {
        const empty = emptyPlanFilters();
        expect(empty.myTasks).toBe(false);
        expect(empty.unassigned).toBe(false);
        expect(empty.personId).toBe('');
        expect(empty.teamId).toBe('');
        expect(empty.filterLogic).toBe('or');
        expect(hasActiveItemFilters(empty)).toBe(false);
    });

    it('ORs myTasks with unassigned as one focus facet (even when filterLogic is and)', () => {
        const mine = {
            completed: false,
            status: 'open',
            priority: 'normal',
            assigneeType: 'person',
            assigneeId: 'person_01',
        };
        const open = {
            completed: false,
            status: 'open',
            priority: 'normal',
            assigneeType: 'person',
            assigneeId: null,
        };
        const other = {
            completed: false,
            status: 'open',
            priority: 'normal',
            assigneeType: 'person',
            assigneeId: 'person_02',
        };
        const filters = { myTasks: true, unassigned: true, filterLogic: 'and' };
        expect(itemMatchesFilters(mine, filters, { activePersonId: 'person_01' })).toBe(true);
        expect(itemMatchesFilters(open, filters, { activePersonId: 'person_01' })).toBe(true);
        expect(itemMatchesFilters(other, filters, { activePersonId: 'person_01' })).toBe(false);
    });

    it('treats blocked checkbox as structural AND (not assignee OR)', () => {
        const blockedOther = {
            completed: false,
            status: 'blocked',
            priority: 'normal',
            assigneeType: 'person',
            assigneeId: 'person_02',
        };
        const openMine = {
            completed: false,
            status: 'open',
            priority: 'normal',
            assigneeType: 'person',
            assigneeId: 'person_01',
        };
        const filters = { blocked: true, myTasks: true, filterLogic: 'or' };
        expect(itemMatchesFilters(blockedOther, filters, { activePersonId: 'person_01' })).toBe(false);
        expect(itemMatchesFilters(openMine, filters, { activePersonId: 'person_01' })).toBe(false);
        expect(itemMatchesFilters({
            ...openMine,
            status: 'blocked',
        }, filters, { activePersonId: 'person_01' })).toBe(true);
    });

    it('person dropdown overrides myTasks+unassigned focus', () => {
        const other = {
            completed: false,
            status: 'open',
            priority: 'normal',
            assigneeType: 'person',
            assigneeId: 'person_02',
        };
        const mine = {
            completed: false,
            status: 'open',
            priority: 'normal',
            assigneeType: 'person',
            assigneeId: 'person_01',
        };
        const open = {
            completed: false,
            status: 'open',
            priority: 'normal',
            assigneeType: 'person',
            assigneeId: null,
        };
        const filters = {
            myTasks: true,
            unassigned: true,
            personId: 'person_02',
            filterLogic: 'and',
        };
        expect(itemMatchesFilters(other, filters, { activePersonId: 'person_01' })).toBe(true);
        expect(itemMatchesFilters(mine, filters, { activePersonId: 'person_01' })).toBe(false);
        expect(itemMatchesFilters(open, filters, { activePersonId: 'person_01' })).toBe(false);
    });

    it('team filter matches tasks of team members (not team-as-assignee)', () => {
        const teams = {
            team_q: { memberIds: ['person_01', 'person_02'] },
        };
        const memberTask = {
            completed: false,
            status: 'open',
            priority: 'normal',
            assigneeType: 'person',
            assigneeId: 'person_02',
        };
        const outsider = {
            completed: false,
            status: 'open',
            priority: 'normal',
            assigneeType: 'person',
            assigneeId: 'person_99',
        };
        const legacyTeamAssignee = {
            completed: false,
            status: 'open',
            priority: 'normal',
            assigneeType: 'team',
            assigneeId: 'team_q',
        };
        const filters = { teamId: 'team_q', myTasks: true };
        expect(itemMatchesFilters(memberTask, filters, { activePersonId: 'person_01', teams })).toBe(true);
        expect(itemMatchesFilters(outsider, filters, { activePersonId: 'person_01', teams })).toBe(false);
        expect(itemMatchesFilters(legacyTeamAssignee, filters, { activePersonId: 'person_01', teams })).toBe(true);
    });

    it('ORs myTasks with unassigned when filterLogic is or', () => {
        const mine = {
            completed: false,
            status: 'open',
            priority: 'normal',
            assigneeType: 'person',
            assigneeId: 'person_01',
        };
        const open = {
            completed: false,
            status: 'open',
            priority: 'normal',
            assigneeType: 'person',
            assigneeId: null,
        };
        const other = {
            completed: false,
            status: 'open',
            priority: 'normal',
            assigneeType: 'person',
            assigneeId: 'person_02',
        };
        const filters = { myTasks: true, unassigned: true, filterLogic: 'or' };
        expect(itemMatchesFilters(mine, filters, { activePersonId: 'person_01' })).toBe(true);
        expect(itemMatchesFilters(open, filters, { activePersonId: 'person_01' })).toBe(true);
        expect(itemMatchesFilters(other, filters, { activePersonId: 'person_01' })).toBe(false);
    });

    it('widens myTasks+unassigned OR filters after assigning to someone else', () => {
        const filters = { myTasks: true, unassigned: true, filterLogic: 'or' };
        const revealed = filtersToRevealAssignee(
            filters,
            { assigneeType: 'person', assigneeId: 'person_02' },
            { activePersonId: 'person_01' },
        );
        expect(revealed.changed).toBe(true);
        expect(revealed.filters.myTasks).toBe(false);
        expect(revealed.filters.unassigned).toBe(false);
        expect(itemMatchesFilters({
            completed: false,
            status: 'open',
            priority: 'normal',
            assigneeType: 'person',
            assigneeId: 'person_02',
        }, revealed.filters, { activePersonId: 'person_01' })).toBe(true);
    });

    it('keeps structural status filter as AND even in OR mode', () => {
        const blockedMine = {
            completed: false,
            status: 'blocked',
            priority: 'normal',
            assigneeType: 'person',
            assigneeId: 'person_01',
        };
        const openMine = {
            completed: false,
            status: 'open',
            priority: 'normal',
            assigneeType: 'person',
            assigneeId: 'person_01',
        };
        const filters = { myTasks: true, status: 'blocked', filterLogic: 'or' };
        expect(itemMatchesFilters(blockedMine, filters, { activePersonId: 'person_01' })).toBe(true);
        expect(itemMatchesFilters(openMine, filters, { activePersonId: 'person_01' })).toBe(false);
    });

    it('normalizes filterLogic to and by default', () => {
        expect(normalizePlanFilters({}).filterLogic).toBe('and');
        expect(normalizePlanFilters({ filterLogic: 'or' }).filterLogic).toBe('or');
        expect(normalizePlanFilters({ filterLogic: 'xor' }).filterLogic).toBe('and');
    });

    it('ANDs person filter with status', () => {
        const item = {
            completed: false,
            status: 'open',
            priority: 'normal',
            assigneeType: 'person',
            assigneeId: 'person_01',
        };
        expect(itemMatchesFilters(item, { personId: 'person_01', status: 'open' }, { activePersonId: null })).toBe(true);
        expect(itemMatchesFilters(item, { personId: 'person_01', status: 'blocked' }, { activePersonId: null })).toBe(false);
    });

    it('filters current week sprints', () => {
        const sprints = [
            { number: 1, tasks: [{ completed: false, status: 'open', priority: 'normal' }], deliverables: [] },
            { number: 2, tasks: [{ completed: false, status: 'open', priority: 'normal' }], deliverables: [] },
        ];
        const filtered = filterSprints(sprints, { currentWeek: true }, 2, { activePersonId: null });
        expect(filtered).toHaveLength(1);
        expect(filtered[0].number).toBe(2);
    });
});

describe('export-import', () => {
    it('validates and applies workspace export', () => {
        const workspace = createDefaultWorkspace();
        workspace.people.person_01 = {
            id: 'person_01',
            displayName: 'Ada',
            shortName: 'A',
            email: '',
            role: '',
            colorToken: 'accent-1',
            archived: false,
        };
        saveWorkspace(workspace);
        const bundle = buildWorkspaceExport();
        expect(bundle.exportType).toBe(EXPORT_TYPE_WORKSPACE);
        expect(validateImportPayload(bundle).ok).toBe(true);

        localStorage.removeItem(WORKSPACE_KEY);
        const result = applyImport('replace', bundle);
        expect(result.ok).toBe(true);
        expect(loadWorkspace().data.people.person_01.displayName).toBe('Ada');
    });

    it('rejects unsupported schema', () => {
        expect(validateImportPayload({
            exportType: EXPORT_TYPE_WORKSPACE,
            schemaVersion: 99,
            workspace: {},
        }).ok).toBe(false);
    });
});

describe('instance-manager', () => {
    it('starts instance and adds custom sprint without mutating template', () => {
        const template = {
            slug: 'demo',
            version: 1,
            locales: {
                de: { title: 'DE', description: '' },
                en: { title: 'EN', description: '' },
            },
        };
        const started = startInstanceFromTemplate(template, {
            startedAt: '2026-07-01',
            participantIds: [],
        });
        expect(started.ok).toBe(true);
        const added = addCustomSprint(started.instance.id, {
            titleDe: 'Extra',
            titleEn: 'Extra',
            goalDe: 'Ziel',
            goalEn: 'Goal',
            number: 99,
        });
        expect(added.ok).toBe(true);
        const instance = loadWorkspace().data.instances[started.instance.id];
        expect(instance.customSprints).toHaveLength(1);
        expect(instance.customSprints[0].title.en).toBe('Extra');
        expect(template.locales.en.title).toBe('EN');
    });

    it('adds a sprint from another template with tasks and deliverables', () => {
        const base = startInstanceFromTemplate({
            slug: 'base-plan',
            version: 1,
            sprints: [{ id: 'week-01', number: 1, tasks: [], deliverables: [] }],
            locales: {
                de: { title: 'Basis', description: '', sprints: [] },
                en: { title: 'Base', description: '', sprints: [] },
            },
        }, { startedAt: '2026-07-01' });
        expect(base.ok).toBe(true);

        const donor = {
            slug: 'planning-month',
            version: 1,
            sprints: [
                {
                    id: 'week-02',
                    number: 2,
                    notes: true,
                    stories: [{ slug: 'eight-pillars', required: false }],
                    linkedStorySlugs: ['eight-pillars'],
                    links: [],
                    tasks: [
                        { id: 't1', assigneeType: 'person', assigneeId: null, helpLinks: [], stories: [] },
                    ],
                    deliverables: [
                        { id: 'd1', helpLinks: [], stories: [] },
                    ],
                },
            ],
            locales: {
                de: {
                    title: 'Monat',
                    description: '',
                    sprints: [{
                        id: 'week-02',
                        title: 'Umsetzung',
                        goal: 'Liefern',
                        tasks: [{ id: 't1', label: 'Aufgabe DE', helpText: 'Hilfe DE' }],
                        deliverables: [{ id: 'd1', label: 'Ergebnis DE', helpText: '' }],
                    }],
                },
                en: {
                    title: 'Month',
                    description: '',
                    sprints: [{
                        id: 'week-02',
                        title: 'Execution',
                        goal: 'Deliver',
                        tasks: [{ id: 't1', label: 'Task EN', helpText: 'Help EN' }],
                        deliverables: [{ id: 'd1', label: 'Outcome EN', helpText: '' }],
                    }],
                },
            },
        };

        const added = addSprintFromTemplate(base.instance.id, donor, 'week-02');
        expect(added.ok).toBe(true);
        const instance = loadWorkspace().data.instances[base.instance.id];
        expect(instance.customSprints).toHaveLength(1);
        const sprint = instance.customSprints[0];
        expect(sprint.title.de).toBe('Umsetzung');
        expect(sprint.title.en).toBe('Execution');
        expect(sprint.number).toBe(2);
        expect(instance.customTasks[sprint.id]).toHaveLength(1);
        expect(instance.customTasks[sprint.id][0].label.de).toBe('Aufgabe DE');
        expect(instance.customTasks[sprint.id][0].label.en).toBe('Task EN');
        expect(instance.customDeliverables[sprint.id]).toHaveLength(1);
        expect(instance.customDeliverables[sprint.id][0].label.en).toBe('Outcome EN');
    });

    it('leaves template items unassigned on start', () => {
        const template = {
            slug: 'demo-assign',
            version: 1,
            sprints: [
                {
                    id: 'sprint_1',
                    number: 1,
                    tasks: [{ id: 'task_a', assigneeType: 'person', assigneeId: null }],
                    deliverables: [{ id: 'del_a', assigneeType: 'person', assigneeId: null }],
                },
            ],
            locales: {
                de: { title: 'DE', description: '', sprints: [] },
                en: { title: 'EN', description: '', sprints: [] },
            },
        };
        const started = startInstanceFromTemplate(template, { startedAt: '2026-07-01' });
        expect(started.ok).toBe(true);
        const taskKey = statusKey('demo-assign', 'sprint_1', 'task', 'task_a');
        const delKey = statusKey('demo-assign', 'sprint_1', 'deliverable', 'del_a');
        expect(started.instance.itemOverrides[taskKey]).toBeUndefined();
        expect(started.instance.itemOverrides[delKey]).toBeUndefined();
        expect(started.instance.templateSnapshot?.slug).toBe('demo-assign');
        expect(started.instance.templateSnapshot?.sprints).toHaveLength(1);
    });

    it('claim all only takes unassigned items and never steals assignees', () => {
        const template = {
            slug: 'demo-claim-all',
            version: 1,
            sprints: [
                {
                    id: 'sprint_1',
                    number: 1,
                    tasks: [{ id: 'task_open' }, { id: 'task_taken' }],
                    deliverables: [],
                },
            ],
            locales: {
                de: {
                    title: 'DE',
                    description: '',
                    sprints: [{
                        id: 'sprint_1',
                        title: 'S1',
                        goal: 'G',
                        tasks: [
                            { id: 'task_open', label: 'Open' },
                            { id: 'task_taken', label: 'Taken' },
                        ],
                    }],
                },
                en: {
                    title: 'EN',
                    description: '',
                    sprints: [{
                        id: 'sprint_1',
                        title: 'S1',
                        goal: 'G',
                        tasks: [
                            { id: 'task_open', label: 'Open' },
                            { id: 'task_taken', label: 'Taken' },
                        ],
                    }],
                },
            },
        };
        const started = startInstanceFromTemplate(template, { startedAt: '2026-07-01' });
        const openKey = statusKey('demo-claim-all', 'sprint_1', 'task', 'task_open');
        const takenKey = statusKey('demo-claim-all', 'sprint_1', 'task', 'task_taken');

        const assigned = claimItem(started.instance.id, 'task', takenKey, false, 'sprint_1', 'person_matthias');
        expect(assigned.ok).toBe(true);
        expect(assigned.claimed).toBe(true);

        const all = claimAllUnassigned(
            started.instance.id,
            null,
            'person_thomas_a',
            template,
        );
        expect(all.ok).toBe(true);
        expect(all.claimed).toBe(1);
        const instance = loadWorkspace().data.instances[started.instance.id];
        expect(instance.itemOverrides[openKey].assigneeId).toBe('person_thomas_a');
        expect(instance.itemOverrides[takenKey].assigneeId).toBe('person_matthias');
    });

    it('keeps templateSlug when claim mutates overrides', () => {
        const template = {
            slug: 'demo-keep-slug',
            version: 1,
            sprints: [{ id: 'sprint_1', number: 1, tasks: [{ id: 'task_b' }], deliverables: [] }],
            locales: {
                de: { title: 'DE', description: '', sprints: [] },
                en: { title: 'EN', description: '', sprints: [] },
            },
        };
        const started = startInstanceFromTemplate(template, { startedAt: '2026-07-01' });
        const key = statusKey('demo-keep-slug', 'sprint_1', 'task', 'task_b');
        const claimed = claimItem(started.instance.id, 'task', key, false, 'sprint_1', 'person_matthias');
        expect(claimed.ok).toBe(true);
        const again = loadWorkspace().data.instances[started.instance.id];
        expect(again.templateSlug).toBe('demo-keep-slug');
        expect(again.startedAt).toBe('2026-07-01');
        expect(again.templateSnapshot?.slug).toBe('demo-keep-slug');
    });

    it('claims an unassigned override item', () => {
        const template = {
            slug: 'demo-claim',
            version: 1,
            sprints: [
                {
                    id: 'sprint_1',
                    number: 1,
                    tasks: [{ id: 'task_b' }],
                    deliverables: [],
                },
            ],
            locales: {
                de: { title: 'DE', description: '', sprints: [{ id: 'sprint_1', title: 'S1', goal: 'G', tasks: [{ id: 'task_b', label: 'T' }] }] },
                en: { title: 'EN', description: '', sprints: [{ id: 'sprint_1', title: 'S1', goal: 'G', tasks: [{ id: 'task_b', label: 'T' }] }] },
            },
        };
        const started = startInstanceFromTemplate(template, { startedAt: '2026-07-01' });
        const key = statusKey('demo-claim', 'sprint_1', 'task', 'task_b');
        expect(started.instance.itemOverrides[key]).toBeUndefined();

        const claimed = claimItem(started.instance.id, 'task', key, false, 'sprint_1', 'person_matthias');
        expect(claimed.ok).toBe(true);
        expect(loadWorkspace().data.instances[started.instance.id].itemOverrides[key].assigneeId)
            .toBe('person_matthias');

        // Second claim must not steal an existing assignee.
        const stolen = claimItem(started.instance.id, 'task', key, false, 'sprint_1', 'person_thomas_a');
        expect(stolen.ok).toBe(true);
        expect(stolen.claimed).toBe(false);
        expect(loadWorkspace().data.instances[started.instance.id].itemOverrides[key].assigneeId)
            .toBe('person_matthias');

        const again = loadWorkspace().data;
        again.instances[started.instance.id].itemOverrides[key].assigneeId = null;
        saveWorkspace(again);

        const all = claimAllUnassigned(
            started.instance.id,
            null,
            'person_thomas_a',
            template,
        );
        expect(all.ok).toBe(true);
        expect(all.claimed).toBe(1);
        expect(loadWorkspace().data.instances[started.instance.id].itemOverrides[key].assigneeId)
            .toBe('person_thomas_a');
    });

    it('prefers full local plan over hollow newer server shell', () => {
        const accountUser = { id: 'user_acidone', displayName: 'Acidone', email: 'a@x.test' };
        const planId = 'plan_hollow_heal_1';
        const hollowServer = {
            id: planId,
            templateSlug: '',
            templateVersion: 1,
            translations: {},
            startedAt: '',
            status: 'active',
            teamIds: ['team_q'],
            participantIds: [],
            completedTasks: [],
            completedDeliverables: [],
            fieldValues: {},
            sprintNotes: {},
            customTasks: {},
            customDeliverables: {},
            customSprints: [],
            sprintOverrides: {},
            itemOverrides: {},
            templateSnapshot: null,
            ownerUserId: 'user_acidone',
            viewerUserIds: [],
            viewerTeamIds: ['team_q'],
            linkedStorySlugs: [],
            ephemeral: false,
            archived: false,
            createdAt: '2026-07-01T00:00:00.000Z',
            updatedAt: '2026-07-20T12:00:00.000Z',
        };
        const localFull = {
            ...hollowServer,
            templateSlug: 'data-reporting-first-quarter',
            startedAt: '2026-08-17',
            updatedAt: '2026-07-19T10:00:00.000Z',
            itemOverrides: {
                'data-reporting-first-quarter:week-01:task:t1': {
                    assigneeType: 'person',
                    assigneeId: 'user_acidone',
                },
            },
            completedTasks: ['data-reporting-first-quarter:week-01:task:t1'],
            templateSnapshot: { slug: 'data-reporting-first-quarter', sprints: [{ id: 'week-01' }] },
            translations: {
                de: { title: 'FQ', description: '' },
                en: { title: 'FQ', description: '' },
            },
        };

        const root = {
            dataset: {
                accountsEnabled: '1',
                accountUser: JSON.stringify(accountUser),
                plansApiUrl: '/api/plans',
                storiesApiUrl: '',
                loginUrl: '/login',
                accountPeople: '[]',
                accountTeams: '[]',
                serverPlans: JSON.stringify([hollowServer]),
                readSlugs: '[]',
            },
        };
        vi.stubGlobal('document', {
            getElementById: (id) => (id === 'sp-app' ? root : null),
            querySelector: () => null,
        });
        const fetchMock = vi.fn(async () => ({
            ok: true,
            json: async () => ({ plan: localFull }),
        }));
        vi.stubGlobal('fetch', fetchMock);

        localStorage.setItem(WORKSPACE_KEY, JSON.stringify(normalizeWorkspace({
            schemaVersion: 1,
            workspace: {
                id: 'workspace_default',
                name: 'Shared',
                locale: 'en',
                activePersonId: 'user_acidone',
                defaultTeamId: null,
            },
            people: {},
            teams: {},
            instances: { [planId]: localFull },
        })));

        const loaded = loadWorkspace();
        expect(loaded.ok).toBe(true);
        const plan = loaded.data.instances[planId];
        expect(plan.templateSlug).toBe('data-reporting-first-quarter');
        expect(plan.startedAt).toBe('2026-08-17');
        expect(plan.completedTasks).toEqual(['data-reporting-first-quarter:week-01:task:t1']);
        expect(plan.itemOverrides['data-reporting-first-quarter:week-01:task:t1'].assigneeId)
            .toBe('user_acidone');
    });

    it('keeps claim overrides across reload in accounts mode (local newer than bootstrap)', () => {
        const accountUser = { id: 'user_acidone', displayName: 'Acidone', email: 'a@x.test' };
        const planId = 'plan_claim_persist_1';
        const stalePlan = {
            id: planId,
            templateSlug: 'demo-claim-persist',
            templateVersion: 1,
            translations: { en: { title: 'T', description: '' }, de: { title: 'T', description: '' } },
            startedAt: '2026-07-01',
            status: 'active',
            teamIds: [],
            participantIds: [],
            completedTasks: [],
            completedDeliverables: [],
            fieldValues: {},
            sprintNotes: {},
            customTasks: {},
            customDeliverables: {},
            customSprints: [],
            sprintOverrides: {},
            itemOverrides: {},
            templateSnapshot: null,
            ownerUserId: 'user_acidone',
            viewerUserIds: [],
            viewerTeamIds: [],
            linkedStorySlugs: [],
            ephemeral: false,
            archived: false,
            createdAt: '2026-07-01T00:00:00.000Z',
            updatedAt: '2026-07-01T00:00:00.000Z',
        };

        const root = {
            dataset: {
                accountsEnabled: '1',
                accountUser: JSON.stringify(accountUser),
                plansApiUrl: '/api/plans',
                storiesApiUrl: '',
                loginUrl: '/login',
                accountPeople: '[]',
                accountTeams: '[]',
                serverPlans: JSON.stringify([stalePlan]),
                readSlugs: '[]',
            },
        };
        vi.stubGlobal('document', {
            getElementById: (id) => (id === 'sp-app' ? root : null),
            querySelector: () => null,
        });
        vi.stubGlobal('fetch', vi.fn(async () => ({
            ok: true,
            json: async () => ({ plan: stalePlan }),
        })));

        // Seed local cache as the accounts workspace would after a claim.
        const claimedKey = statusKey('demo-claim-persist', 'sprint_1', 'task', 'task_a');
        const claimedPlan = {
            ...stalePlan,
            updatedAt: '2026-07-19T12:00:00.000Z',
            itemOverrides: {
                [claimedKey]: { assigneeType: 'person', assigneeId: 'user_acidone' },
            },
        };
        localStorage.setItem(WORKSPACE_KEY, JSON.stringify(normalizeWorkspace({
            schemaVersion: 1,
            workspace: {
                id: 'workspace_default',
                name: 'Shared',
                locale: 'en',
                activePersonId: 'user_acidone',
                defaultTeamId: null,
            },
            people: {},
            teams: {},
            instances: { [planId]: claimedPlan },
        })));

        const loaded = loadWorkspace();
        expect(loaded.ok).toBe(true);
        expect(loaded.data.instances[planId].itemOverrides[claimedKey].assigneeId)
            .toBe('user_acidone');
        expect(loaded.data.workspace.activePersonId).toBe('user_acidone');
    });

    it('keeps local assignees when a newer server plan has empty assignment metadata', () => {
        const accountUser = { id: 'user_acidone', displayName: 'Acidone', email: 'a@x.test' };
        const planId = 'plan_claim_merge_1';
        const serverKey = statusKey('demo-claim-persist', 'sprint_1', 'task', 'task_a');
        const localKey = statusKey('demo-claim-persist', 'sprint_1', 'task', 'task_b');
        const serverPlan = {
            id: planId,
            templateSlug: 'demo-claim-persist',
            templateVersion: 1,
            translations: { en: { title: 'T', description: '' }, de: { title: 'T', description: '' } },
            startedAt: '2026-07-01',
            status: 'active',
            teamIds: [],
            participantIds: [],
            completedTasks: [],
            completedDeliverables: [],
            fieldValues: {},
            sprintNotes: {},
            customTasks: {},
            customDeliverables: {},
            customSprints: [],
            sprintOverrides: {},
            itemOverrides: {
                [serverKey]: { assigneeType: 'person', assigneeId: null },
            },
            templateSnapshot: null,
            ownerUserId: 'user_acidone',
            viewerUserIds: [],
            viewerTeamIds: [],
            linkedStorySlugs: [],
            ephemeral: false,
            archived: false,
            createdAt: '2026-07-01T00:00:00.000Z',
            updatedAt: '2026-07-20T12:00:00.000Z',
        };

        const root = {
            dataset: {
                accountsEnabled: '1',
                accountUser: JSON.stringify(accountUser),
                plansApiUrl: '/api/plans',
                storiesApiUrl: '',
                loginUrl: '/login',
                accountPeople: '[]',
                accountTeams: '[]',
                serverPlans: JSON.stringify([serverPlan]),
                readSlugs: '[]',
            },
        };
        vi.stubGlobal('document', {
            getElementById: (id) => (id === 'sp-app' ? root : null),
            querySelector: () => null,
        });

        localStorage.setItem(WORKSPACE_KEY, JSON.stringify(normalizeWorkspace({
            schemaVersion: 1,
            workspace: {
                id: 'workspace_default',
                name: 'Shared',
                locale: 'en',
                activePersonId: 'user_acidone',
                defaultTeamId: null,
            },
            people: {},
            teams: {},
            instances: {
                [planId]: {
                    ...serverPlan,
                    updatedAt: '2026-07-19T12:00:00.000Z',
                    participantIds: ['person_thomas', 'person_matthias'],
                    itemOverrides: {
                        [serverKey]: { assigneeType: 'person', assigneeId: 'person_thomas' },
                        [localKey]: { assigneeType: 'person', assigneeId: 'person_matthias' },
                    },
                },
            },
        })));

        const loaded = loadWorkspace();

        expect(loaded.ok).toBe(true);
        expect(loaded.data.instances[planId].itemOverrides[serverKey].assigneeId).toBe('person_thomas');
        expect(loaded.data.instances[planId].itemOverrides[localKey].assigneeId).toBe('person_matthias');
        expect(loaded.data.instances[planId].participantIds).toContain('person_matthias');
    });

    it('stores a password hash and verifies it', async () => {
        const template = {
            slug: 'demo',
            version: 1,
            locales: {
                de: { title: 'DE', description: '' },
                en: { title: 'EN', description: '' },
            },
        };
        const started = startInstanceFromTemplate(template, { startedAt: '2026-07-01' });
        const result = await setPlanPassword(started.instance.id, 'secret');
        expect(result.ok).toBe(true);
        const instance = loadWorkspace().data.instances[started.instance.id];
        expect(isPasswordProtected(instance)).toBe(true);
        expect(await verifyPassword('secret', instance)).toBe(true);
        expect(await verifyPassword('wrong', instance)).toBe(false);
        expect(instance.passwordHash).not.toBe('secret');
    });
});

describe('attachments', () => {
    it('normalizes link and file metadata', () => {
        const link = createLinkAttachment({ label: 'Deck', href: 'https://example.com/a.pptx' });
        expect(link.kind).toBe('link');
        expect(link.name).toBe('Deck');
        const list = normalizeAttachments([
            link,
            { id: 'att_x', name: 'shot.png', mime: 'image/png', kind: 'file', href: '', previewable: true },
            { id: 'att_x', name: 'dup' },
        ]);
        expect(list).toHaveLength(2);
        expect(list[1].previewable).toBe(true);
    });

    it('validates mime and size', () => {
        expect(isAllowedAttachment('application/pdf', 'a.pdf')).toBe(true);
        expect(isAllowedAttachment('application/zip', 'a.zip')).toBe(false);
        const ok = validateAttachmentFile({ name: 'a.pdf', type: 'application/pdf', size: 100 });
        expect(ok.ok).toBe(true);
        const big = validateAttachmentFile({
            name: 'a.pdf',
            type: 'application/pdf',
            size: 20 * 1024 * 1024,
        });
        expect(big.ok).toBe(false);
        expect(big.error).toBe('attachment-too-large');
    });
});

describe('defaults', () => {
    it('seeds stable people and teams without overwriting existing ones', () => {
        const base = createDefaultWorkspace();
        base.people.person_thomas_a = {
            id: 'person_thomas_a',
            displayName: 'Custom Thomas',
            shortName: 'CT',
            email: '',
            role: '',
            colorToken: 'accent-1',
            archived: false,
        };
        const { workspace, changed } = ensureDefaultCatalog(base);
        expect(changed).toBe(true);
        expect(workspace.people.person_thomas_a.displayName).toBe('Custom Thomas');
        expect(workspace.people.person_matthias.displayName).toBe('Matthias');
        expect(Object.keys(workspace.teams)).toHaveLength(DEFAULT_TEAMS.length);
        expect(DEFAULT_PEOPLE).toHaveLength(3);
    });
});

describe('plan-password', () => {
    it('hashes deterministically for the same salt', async () => {
        const a = await hashPassword('demo', 'salt');
        const b = await hashPassword('demo', 'salt');
        expect(a).toBe(b);
        expect(a).not.toBe(await hashPassword('demo', 'other'));
    });
});

describe('session undo', () => {
    it('undoes the last plan mutation in the tab', () => {
        const workspace = createDefaultWorkspace();
        const planId = 'plan_undo_1';
        workspace.instances[planId] = {
            id: planId,
            templateSlug: 'demo',
            startedAt: '2026-01-01',
            completedTasks: [],
            completedDeliverables: [],
            itemOverrides: {},
            fieldValues: {},
            sprintNotes: {},
            customTasks: {},
            customDeliverables: {},
            customSprints: [],
            sprintOverrides: {},
            removedItemKeys: [],
            translations: { de: {}, en: {} },
            status: 'active',
            archived: false,
            ephemeral: true,
            updatedAt: new Date().toISOString(),
            createdAt: new Date().toISOString(),
        };
        saveWorkspace(workspace);

        expect(canUndoInstance(planId)).toBe(false);
        const toggled = toggleCompleted(planId, 'task', 'task_a', true);
        expect(toggled.ok).toBe(true);
        expect(canUndoInstance(planId)).toBe(true);
        expect(loadWorkspace().data.instances[planId].completedTasks).toContain('task_a');

        const undone = undoLastInstanceChange(planId);
        expect(undone.ok).toBe(true);
        expect(loadWorkspace().data.instances[planId].completedTasks).toEqual([]);
        expect(canUndoInstance(planId)).toBe(false);
    });
});

describe('lastOpenedPlanId preferences', () => {
    it('stores and clears the last opened plan id', () => {
        expect(createDefaultPreferences().lastOpenedPlanId).toBeNull();
        setLastOpenedPlanId('plan_abc');
        expect(loadPreferences().lastOpenedPlanId).toBe('plan_abc');
        clearLastOpenedPlanIdIf('plan_other');
        expect(loadPreferences().lastOpenedPlanId).toBe('plan_abc');
        clearLastOpenedPlanIdIf('plan_abc');
        expect(loadPreferences().lastOpenedPlanId).toBeNull();
        savePreferences({ ...loadPreferences(), lastOpenedPlanId: 'plan_keep' });
        expect(loadPreferences().lastOpenedPlanId).toBe('plan_keep');
    });
});

describe('task time tracking', () => {
    it('normalizes minutes and computes variance', () => {
        expect(normalizeMinutes('45')).toBe(45);
        expect(normalizeMinutes(-1)).toBeNull();
        expect(timeVariance(60, 90)).toBe('over');
        expect(timeVariance(60, 30)).toBe('under');
        expect(timeVariance(60, 60)).toBe('on_track');
        expect(timeVariance(0, 30)).toBeNull();
    });

    it('resolves planned from override over template and actual only from plan', () => {
        expect(resolvePlannedMinutes({ plannedMinutes: 15 }, { plannedMinutes: 120 })).toBe(15);
        expect(resolvePlannedMinutes({}, { plannedMinutes: 120 })).toBe(120);
        expect(resolvePlannedMinutes({ plannedMinutes: null }, { plannedMinutes: 120 })).toBeNull();
        expect(resolveActualMinutes({ actualMinutes: 40 })).toBe(40);
        expect(resolveActualMinutes({ plannedMinutes: 40 })).toBeNull();
    });

    it('merges planned/actual onto resolved items and persists via updateItemMeta', () => {
        const template = {
            slug: 'demo-time',
            duration: 1,
            unit: 'week',
            sprints: [{
                id: 'week-01',
                number: 1,
                notes: false,
                tasks: [{ id: 't1', assigneeType: 'person', assigneeId: null, plannedMinutes: 60 }],
                deliverables: [],
                fields: [],
            }],
            locales: {
                en: {
                    title: 'Demo',
                    description: '',
                    sprints: [{
                        id: 'week-01',
                        title: 'W1',
                        goal: '',
                        tasks: [{ id: 't1', label: 'Task 1' }],
                        deliverables: [],
                        fields: [],
                    }],
                },
                de: {
                    title: 'Demo',
                    description: '',
                    sprints: [{
                        id: 'week-01',
                        title: 'W1',
                        goal: '',
                        tasks: [{ id: 't1', label: 'Aufgabe 1' }],
                        deliverables: [],
                        fields: [],
                    }],
                },
            },
        };
        const started = startInstanceFromTemplate(template, {
            locale: 'en',
            startedAt: '2026-07-01',
        });
        expect(started.ok).toBe(true);
        const planId = started.instance.id;
        const key = statusKey('demo-time', 'week-01', 'task', 't1');

        let sprints = resolveSprints(template, loadWorkspace().data.instances[planId], 'en');
        expect(sprints[0].tasks[0].plannedMinutes).toBe(60);
        expect(sprints[0].tasks[0].actualMinutes).toBeNull();

        const updated = updateItemMeta(planId, 'task', key, {
            status: 'in_progress',
            priority: 'normal',
            assigneeType: 'person',
            assigneeId: null,
            dueDate: null,
            note: '',
            plannedMinutes: 90,
            actualMinutes: 120,
        }, false, 'week-01');
        expect(updated.ok).toBe(true);

        sprints = resolveSprints(template, loadWorkspace().data.instances[planId], 'en');
        expect(sprints[0].tasks[0].plannedMinutes).toBe(90);
        expect(sprints[0].tasks[0].actualMinutes).toBe(120);
        expect(timeVariance(90, 120)).toBe('over');

        const rows = collectTimeRows(sprints);
        expect(rows.planned).toBe(90);
        expect(rows.actual).toBe(120);
        expect(rows.over).toBe(1);
    });
});
