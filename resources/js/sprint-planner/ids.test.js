import { describe, expect, it } from 'vitest';
import {
    createPlanId,
    inferStartedAtFromPlanId,
    resolvePlanStartedAt,
} from './ids.js';

describe('plan id / startedAt helpers', () => {
    it('infers YYYY-MM-DD from plan id', () => {
        expect(inferStartedAtFromPlanId('plan_20260817_acid1')).toBe('2026-08-17');
        expect(inferStartedAtFromPlanId('plan_hollow_heal_1')).toBe('');
        expect(inferStartedAtFromPlanId('')).toBe('');
    });

    it('resolves empty startedAt from plan id', () => {
        expect(resolvePlanStartedAt('plan_20260817_acid1', '', new Date('2026-07-20T12:00:00Z')))
            .toBe('2026-08-17');
    });

    it('undoes recover fill of today when id encodes another day', () => {
        expect(resolvePlanStartedAt(
            'plan_20260817_acid1',
            '2026-07-20',
            new Date('2026-07-20T12:00:00Z'),
        )).toBe('2026-08-17');
    });

    it('keeps an intentional non-today startedAt', () => {
        expect(resolvePlanStartedAt(
            'plan_20260817_acid1',
            '2026-07-01',
            new Date('2026-07-20T12:00:00Z'),
        )).toBe('2026-07-01');
    });

    it('createPlanId can encode the start date', () => {
        expect(createPlanId('2026-08-17').startsWith('plan_20260817_')).toBe(true);
    });
});
