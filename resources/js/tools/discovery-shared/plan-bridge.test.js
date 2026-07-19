import { describe, expect, it } from 'vitest';
import {
    appendDiscoveryRowsToItemTable,
    appendMarkdownToNote,
    mapDiscoveryColumnsToPlan,
    normalizeColumnKey,
    readPlanLaunchContext,
} from './plan-bridge.js';

describe('discovery plan bridge', () => {
    it('reads plan launch context from query', () => {
        const ctx = readPlanLaunchContext(
            '?fromPlan=1&instanceId=plan_1&itemKey=k1&kind=task&sprintId=w2&custom=0&return=%2Fde%2Fsprint-planner%2Fplan_1',
        );
        expect(ctx).toEqual({
            instanceId: 'plan_1',
            itemKey: 'k1',
            kind: 'task',
            sprintId: 'w2',
            custom: false,
            returnUrl: '/de/sprint-planner/plan_1',
        });
        expect(readPlanLaunchContext('?fromPlan=0')).toBeNull();
        expect(readPlanLaunchContext('?fromPlan=1&instanceId=x&itemKey=y&kind=task&sprintId=z&return=https://evil.test')).toBeNull();
    });

    it('maps columns by label and id', () => {
        expect(normalizeColumnKey('Geschäftsfrage')).toBe('geschaftsfrage');
        const map = mapDiscoveryColumnsToPlan(
            [
                { id: 'report', label: 'Report' },
                { id: 'rhythmus', label: 'Rhythmus' },
                { id: 'geschaftsfrage', label: 'Geschäftsfrage' },
            ],
            [
                { id: 'report', label: 'Report' },
                { id: 'cadence', label: 'Rhythmus' },
                { id: 'question', label: 'Geschäftsfrage' },
            ],
        );
        expect(map).toEqual({
            report: 'report',
            rhythmus: 'cadence',
            geschaftsfrage: 'question',
        });
    });

    it('appends mapped rows onto an item table', () => {
        const next = appendDiscoveryRowsToItemTable(
            {
                columns: [
                    { id: 'report', label: 'Report' },
                    { id: 'owner', label: 'Owner' },
                ],
                rows: [{ id: 'existing', cells: { report: 'Old', owner: 'A' } }],
            },
            [
                { id: 'report', label: 'Report' },
                { id: 'owner', label: 'Owner' },
            ],
            [{ id: 'd1', cells: { report: 'New', owner: 'B' } }],
            () => 'row_new',
        );
        expect(next.rows).toHaveLength(2);
        expect(next.rows[1]).toEqual({
            id: 'row_new',
            cells: { report: 'New', owner: 'B' },
        });
    });

    it('appends markdown to notes', () => {
        expect(appendMarkdownToNote('', '# Title')).toBe('# Title');
        expect(appendMarkdownToNote('Note', '# Title')).toBe('Note\n\n---\n\n# Title');
    });
});
