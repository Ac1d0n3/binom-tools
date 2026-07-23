import { describe, expect, it } from 'vitest';
import {
    getLimitKindForTask,
    modelHasPlans,
    modelsForTask,
    normalizeModelPlan,
    preferModelForTask,
    resolveCharLimit,
    shouldShowTargetAi,
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

    const chatgpt = { id: 'chatgpt', label: { de: 'ChatGPT', en: 'ChatGPT' }, sectionOrder: [] };
    const claude = { id: 'claude', label: { de: 'Claude', en: 'Claude' }, sectionOrder: [] };
    const runway = { id: 'runway', label: { de: 'Runway', en: 'Runway' }, sectionOrder: [] };

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
        const models = [suno, chatgpt];
        expect(preferModelForTask({ id: 'lyrics', modelHints: ['suno'] }, 'chatgpt', models)).toBe('suno');
        expect(preferModelForTask({ id: 'lyrics', modelHints: ['suno'] }, 'suno', models)).toBe('suno');
    });

    it('filters models by task hints', () => {
        const models = [
            { ...chatgpt, family: 'llm' },
            { ...claude, family: 'llm' },
            { ...suno, family: 'music' },
            { ...runway, family: 'video' },
        ];
        expect(modelsForTask({ id: 'lyrics', modelHints: ['suno', 'chatgpt'] }, models).map((m) => m.id)).toEqual([
            'suno',
            'chatgpt',
        ]);
        // Unknown hints must not fall back to the full catalog.
        expect(modelsForTask({ id: 'x', modelHints: ['missing'] }, models)).toEqual([]);
        // No hints → LLM family only.
        expect(modelsForTask({ id: 'plain' }, models).map((m) => m.id)).toEqual(['chatgpt', 'claude']);
    });

    it('shows Target AI for multi-model tasks and Suno plans', () => {
        expect(
            shouldShowTargetAi({
                task: { id: 'brief', modelHints: ['chatgpt', 'claude'] },
                models: [chatgpt, claude],
                modelId: 'chatgpt',
            }),
        ).toBe(true);

        expect(
            shouldShowTargetAi({
                task: { id: 'suno-style', modelHints: ['suno'] },
                models: [suno],
                modelId: 'suno',
            }),
        ).toBe(true);

        expect(
            shouldShowTargetAi({
                task: { id: 'only-one', modelHints: ['runway'] },
                models: [runway],
                modelId: 'runway',
            }),
        ).toBe(false);

        expect(shouldShowTargetAi({ task: null, models: [chatgpt], tech: true })).toBe(true);
        expect(shouldShowTargetAi({ task: null, models: [chatgpt], tech: false })).toBe(false);
    });

    it('reports plans', () => {
        expect(modelHasPlans(suno)).toBe(true);
        expect(modelHasPlans({ id: 'chatgpt', label: { de: 'x', en: 'x' }, sectionOrder: [] })).toBe(false);
    });
});
