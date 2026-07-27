import { describe, expect, it } from 'vitest';
import {
    buildSections,
    getPlatform,
    getSectionQuery,
    groupedPlatforms,
    normalizePlatformId,
    platformIds,
    sectionIds,
} from './sql-builders.js';

describe('meta-export sql-builders', () => {
    it('lists ten platforms including fabric and mongodb', () => {
        expect(platformIds).toHaveLength(10);
        expect(platformIds).toContain('fabric');
        expect(platformIds).toContain('mongodb');
        expect(platformIds).toContain('mysql');
        expect(platformIds).toContain('oracle');
        expect(platformIds).toContain('sqlserver');
    });

    it('normalizes unknown platforms to snowflake', () => {
        expect(normalizePlatformId('nope')).toBe('snowflake');
        expect(getPlatform('nope').id).toBe('snowflake');
    });

    it('groups platforms', () => {
        const groups = groupedPlatforms();
        expect(groups.map((g) => g.id)).toEqual(['cloud', 'relational', 'document']);
        expect(groups.find((g) => g.id === 'document')?.platforms.map((p) => p.id)).toEqual(['mongodb']);
    });

    it('builds all sections for every platform', () => {
        for (const id of platformIds) {
            const sections = buildSections(id);
            expect(sections.map((s) => s.id)).toEqual(sectionIds);
            for (const section of sections) {
                expect(section.query.trim().length).toBeGreaterThan(20);
            }
        }
    });

    it('returns mongodb shell snippets rather than SELECT for schemas', () => {
        const query = getSectionQuery('mongodb', 'schemas');
        expect(query).toContain('listDatabases');
        expect(query.toLowerCase()).not.toContain('select ');
    });
});
