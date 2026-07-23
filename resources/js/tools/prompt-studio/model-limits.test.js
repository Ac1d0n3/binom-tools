import { describe, expect, it } from 'vitest';
import {
    getLimitKindForTask,
    modelHasPlans,
    normalizeModelPlan,
    preferModelForTask,
    resolveCharLimit,
} from './model-limits.js';

describe('model-limits', () => {
    const suno = {
        id: 'suno',
        label: { de: 'Suno', en: 'Suno' },
        sectionOrder: ['task'],
        defaultPlan: 'free',
        plans: [
            { id: 'free', label: { de: 'Free', en: 'Free' } },
            { id: 'paid', label: { de: 'Bezahlt', en: 'Paid' } },
        ],
        limits: {
            default: 1000,
            style: 1000,
            lyrics: { free: 3000, paid: 5000 },
        },
        formatRules: { maxLength: 1000 },
    };

    it('normalizes plans', () => {
        expect(normalizeModelPlan('paid')).toBe('paid');
        expect(normalizeModelPlan('free')).toBe('free');
        expect(normalizeModelPlan('nope')).toBe('free');
    });

    it('detects lyrics vs style tasks', () => {
        expect(getLimitKindForTask('lyrics')).toBe('lyrics');
        expect(getLimitKindForTask('song-develop')).toBe('lyrics');
        expect(getLimitKindForTask('suno-style')).toBe('style');
        expect(getLimitKindForTask('cover')).toBe('default');
    });

    it('resolves Suno lyrics limits by plan', () => {
        expect(resolveCharLimit({ model: suno, plan: 'free', taskId: 'lyrics' })).toBe(3000);
        expect(resolveCharLimit({ model: suno, plan: 'paid', taskId: 'lyrics' })).toBe(5000);
        expect(resolveCharLimit({ model: suno, plan: 'free', taskId: 'suno-style' })).toBe(1000);
    });

    it('prefers model hints for song tasks', () => {
        const models = [suno, { id: 'chatgpt', label: { de: 'ChatGPT', en: 'ChatGPT' }, sectionOrder: [] }];
        expect(preferModelForTask({ id: 'lyrics', modelHints: ['suno'] }, 'chatgpt', models)).toBe('suno');
        expect(preferModelForTask({ id: 'lyrics', modelHints: ['suno'] }, 'suno', models)).toBe('suno');
    });

    it('reports plans', () => {
        expect(modelHasPlans(suno)).toBe(true);
        expect(modelHasPlans({ id: 'chatgpt', label: { de: 'x', en: 'x' }, sectionOrder: [] })).toBe(false);
    });
});
