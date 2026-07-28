import { describe, expect, it } from 'vitest';
import {
    contentCardCandidates,
    matchesContentWhen,
    normalizeContentCard,
} from './advisor-content-cards.js';

describe('matchesContentWhen', () => {
    it('allows empty when', () => {
        expect(matchesContentWhen({}, { goal: 'stack' })).toBe(true);
        expect(matchesContentWhen(null, { goal: 'stack' })).toBe(true);
    });

    it('requires listed dimensions to match', () => {
        expect(matchesContentWhen(
            { goals: ['stack', 'supplier'], scenarios: [] },
            { goal: 'stack', scenario: 'new' },
        )).toBe(true);

        expect(matchesContentWhen(
            { goals: ['stack'], scenarios: ['extend'] },
            { goal: 'stack', scenario: 'new' },
        )).toBe(false);

        expect(matchesContentWhen(
            { scenarios: ['extend'] },
            { goal: 'kpi', scenario: 'extend' },
        )).toBe(true);
    });
});

describe('contentCardCandidates', () => {
    it('reads links.contentCards', () => {
        const cards = contentCardCandidates({
            links: {
                contentCards: [
                    { id: 'a', kind: 'story', ref: 'x', url: '/playbooks/x' },
                    null,
                ],
            },
        });
        expect(cards).toHaveLength(1);
        expect(cards[0].id).toBe('a');
    });

    it('returns empty for missing config', () => {
        expect(contentCardCandidates({})).toEqual([]);
    });
});

describe('normalizeContentCard', () => {
    it('normalizes locale fields and base score', () => {
        const card = normalizeContentCard({
            id: 'story-x',
            kind: 'story',
            group: 'resources',
            score: 82,
            tags: ['help'],
            title: { de: 'DE', en: 'EN' },
            reason: 'plain',
            url: '/playbooks/x',
        });
        expect(card.baseScore).toBe(82);
        expect(card.title).toEqual({ de: 'DE', en: 'EN' });
        expect(card.reason).toEqual({ de: 'plain', en: 'plain' });
        expect(card.tags).toEqual(['help']);
    });
});
