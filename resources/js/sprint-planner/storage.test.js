import { beforeEach, describe, expect, it, vi } from 'vitest';
import {
    WORKSPACE_KEY,
    DEMO_WORKSPACE_KEY,
    createDefaultWorkspace,
    loadWorkspace,
    normalizeWorkspace,
    saveWorkspace,
} from './storage.js';
import { createPlanId, createPersonId, statusKey } from './ids.js';
import {
    calculateCurrentSprintNumber,
    calculateOverallProgress,
    resolveSprints,
} from './progress.js';
import { filterSprints, itemMatchesFilters } from './filters.js';
import {
    EXPORT_TYPE_WORKSPACE,
    applyImport,
    buildWorkspaceExport,
    validateImportPayload,
} from './export-import.js';
import { addCustomSprint, setPlanPassword, startInstanceFromTemplate } from './instance-manager.js';
import { hashPassword, isPasswordProtected, verifyPassword } from './plan-password.js';
import { ensureDefaultCatalog, DEFAULT_PEOPLE, DEFAULT_TEAMS } from './defaults.js';

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
            teamId: null,
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

    it('keeps progress keys stable across locale switch', () => {
        const instance = {
            id: 'plan_x',
            templateSlug: 'demo',
            templateVersion: 1,
            translations: {},
            startedAt: '2026-01-01',
            status: 'active',
            teamId: null,
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

    it('calculates current sprint by week', () => {
        expect(calculateCurrentSprintNumber('2026-01-01', 13, 'week', new Date(2026, 0, 1))).toBe(1);
        expect(calculateCurrentSprintNumber('2026-01-01', 13, 'week', new Date(2026, 0, 8))).toBe(2);
        expect(calculateCurrentSprintNumber('2026-01-01', 13, 'week', new Date(2025, 11, 31))).toBe(1);
        expect(calculateCurrentSprintNumber('2026-01-01', 13, 'week', new Date(2026, 11, 31))).toBe(13);
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
