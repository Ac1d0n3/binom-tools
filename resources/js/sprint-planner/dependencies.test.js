import { describe, expect, it } from 'vitest';
import {
    applySprintDependencyBlocks,
    buildDependsOnGraph,
    collectSprintItemKeys,
    resolveDependsOnKeys,
    validateDependsOn,
    wouldCreateCycle,
} from './dependencies.js';
import { statusKey } from './ids.js';

describe('dependencies', () => {
    const slug = 'demo';
    const sprintId = 'sprint_1';
    const taskA = statusKey(slug, sprintId, 'task', 'a');
    const taskB = statusKey(slug, sprintId, 'task', 'b');
    const taskC = statusKey(slug, sprintId, 'task', 'c');
    const otherSprint = statusKey(slug, 'sprint_2', 'task', 'x');

    it('resolves local template ids to status keys within the sprint', () => {
        const allowed = new Set([taskA, taskB]);
        expect(resolveDependsOnKeys(['a', taskB], {
            templateSlug: slug,
            sprintId,
            allowedKeys: allowed,
            selfKey: taskC,
        })).toEqual([taskA, taskB]);
    });

    it('drops cross-sprint and self references', () => {
        const allowed = new Set([taskA, taskB, otherSprint]);
        expect(resolveDependsOnKeys([otherSprint, taskA, taskB], {
            templateSlug: slug,
            sprintId,
            allowedKeys: allowed,
            selfKey: taskB,
        })).toEqual([taskA]);
    });

    it('detects cycles', () => {
        const graph = new Map([
            [taskA, [taskB]],
            [taskB, []],
            [taskC, []],
        ]);
        expect(wouldCreateCycle(graph, taskB, [taskA])).toBe(true);
        expect(wouldCreateCycle(graph, taskC, [taskA])).toBe(false);
        expect(wouldCreateCycle(graph, taskA, [taskA])).toBe(true);
    });

    it('validateDependsOn rejects cycles and sanitizes keys', () => {
        const allowed = [taskA, taskB, taskC];
        const graph = buildDependsOnGraph([
            { statusKey: taskA, dependsOn: [taskB] },
            { statusKey: taskB, dependsOn: [] },
            { statusKey: taskC, dependsOn: [] },
        ]);
        expect(validateDependsOn(taskB, [taskA], { sprintId, allowedKeys: allowed, graph }))
            .toEqual({ ok: false, error: 'deps-cycle' });
        expect(validateDependsOn(taskC, [taskA, otherSprint], { sprintId, allowedKeys: allowed, graph }))
            .toEqual({ ok: true, dependsOn: [taskA] });
    });

    it('applySprintDependencyBlocks auto-blocks open dependents', () => {
        const items = [
            {
                statusKey: taskA,
                label: 'A',
                completed: false,
                status: 'open',
                dependsOn: [],
                blockerReason: '',
            },
            {
                statusKey: taskB,
                label: 'B',
                completed: false,
                status: 'in_progress',
                dependsOn: [taskA],
                blockerReason: '',
            },
        ];
        applySprintDependencyBlocks(items, (labels) => `Waiting on: ${labels.join(', ')}`);
        expect(items[1].dependencyBlocked).toBe(true);
        expect(items[1].status).toBe('blocked');
        expect(items[1].storedStatus).toBe('in_progress');
        expect(items[1].blockerReason).toBe('Waiting on: A');

        items[0].completed = true;
        items[0].status = 'completed';
        items[1].status = 'in_progress';
        items[1].blockerReason = '';
        applySprintDependencyBlocks(items, (labels) => `Waiting on: ${labels.join(', ')}`);
        expect(items[1].dependencyBlocked).toBe(false);
        expect(items[1].status).toBe('in_progress');
    });

    it('collectSprintItemKeys includes template and custom items', () => {
        const keys = collectSprintItemKeys({
            templateSlug: slug,
            sprintId,
            templateTasks: [{ id: 'a' }],
            templateDeliverables: [{ id: 'd1' }],
            customTasks: [{ id: 'custom', statusKey: taskC }],
            removedItemKeys: [statusKey(slug, sprintId, 'deliverable', 'd1')],
        });
        expect(keys.has(taskA)).toBe(true);
        expect(keys.has(taskC)).toBe(true);
        expect(keys.has(statusKey(slug, sprintId, 'deliverable', 'd1'))).toBe(false);
    });
});
