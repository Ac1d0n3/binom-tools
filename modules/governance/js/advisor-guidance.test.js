import { describe, expect, it } from 'vitest';
import {
    buildGuidance,
    normalizeOrgContext,
    normalizeRegulation,
} from './advisor-guidance.js';

describe('normalizeOrgContext', () => {
    it('maps legacy sme to midmarket', () => {
        expect(normalizeOrgContext('sme')).toBe('midmarket');
    });

    it('keeps startup and midmarket', () => {
        expect(normalizeOrgContext('startup')).toBe('startup');
        expect(normalizeOrgContext('midmarket')).toBe('midmarket');
    });
});

describe('buildGuidance', () => {
    it('returns lean certs for startup without CDMP-first', () => {
        const { certs } = buildGuidance({ orgContext: 'startup', goal: 'stack', platform: 'unknown' });
        expect(certs.some((c) => c.id === 'cert-pillars')).toBe(true);
        expect(certs.some((c) => c.id === 'cert-cdmp')).toBe(false);
        expect(certs.some((c) => c.id === 'cert-roadmap-optional')).toBe(false);
    });

    it('returns lean certs for midmarket and legacy sme', () => {
        const mid = buildGuidance({ orgContext: 'midmarket', goal: 'stack', platform: 'unknown' });
        expect(mid.certs.some((c) => c.id === 'cert-pillars')).toBe(true);
        expect(mid.certs.some((c) => c.id === 'cert-cdmp')).toBe(false);

        const legacy = buildGuidance({ orgContext: 'sme', goal: 'stack', platform: 'unknown' });
        expect(legacy.certs.some((c) => c.id === 'cert-pillars')).toBe(true);
    });

    it('prioritizes privacy and security for bank-finance', () => {
        const { certs } = buildGuidance({ orgContext: 'bank-finance', goal: 'pii', platform: 'fabric' });
        expect(certs.some((c) => c.id === 'cert-cippe-bank')).toBe(true);
        expect(certs.some((c) => c.id === 'cert-iso-bank')).toBe(true);
        const { gaps } = buildGuidance({ orgContext: 'bank-finance', goal: 'pii', platform: 'fabric' });
        expect(gaps.some((g) => g.id === 'gap-pii-compliance')).toBe(true);
    });

    it('adds GDPR overlay certs when regulation is gdpr-heavy on lean orgs', () => {
        const { certs } = buildGuidance({
            orgContext: 'startup',
            regulationPressure: 'gdpr-heavy',
            goal: 'stack',
            platform: 'unknown',
        });
        expect(certs.some((c) => c.id === 'cert-cippe-overlay')).toBe(true);
    });

    it('adds DORA/NIS2 orientation when regulation is regulated', () => {
        const { certs } = buildGuidance({
            orgContext: 'bank-finance',
            regulationPressure: 'regulated',
            goal: 'stack',
            platform: 'fabric',
        });
        expect(certs.some((c) => c.id === 'cert-dora-nis2')).toBe(true);
    });

    it('flags stack gap when platform is unknown and goal is stack', () => {
        const { gaps } = buildGuidance({ orgContext: 'unknown', goal: 'stack', platform: 'unknown' });
        expect(gaps.some((g) => g.id === 'gap-stack-unknown')).toBe(true);
    });

    it('adds catalog/metadata bridge when platform is set', () => {
        const { gaps } = buildGuidance({
            orgContext: 'enterprise',
            goal: 'stack',
            platform: 'databricks',
        });
        expect(gaps.some((g) => g.id === 'gap-metadata-catalog')).toBe(true);
        expect(gaps.some((g) => g.id === 'gap-unity-catalog')).toBe(true);
    });

    it('suggests KPI intake when BI/stack without kpi goal', () => {
        const { gaps } = buildGuidance({
            orgContext: 'midmarket',
            goal: 'stack',
            platform: 'fabric',
        });
        expect(gaps.some((g) => g.id === 'gap-bi-kpi-governance')).toBe(true);
    });

    it('raises bridge story score for extend + lakehouse', () => {
        const { gaps } = buildGuidance({
            orgContext: 'enterprise',
            scenario: 'extend',
            goal: 'stack',
            platform: 'databricks',
        });
        const bridge = gaps.find((g) => g.id === 'gap-bridge-story');
        expect(bridge).toBeTruthy();
        expect((bridge?.score ?? 0) >= 88).toBe(true);
    });

    it('adds compliance bridge for pii in public-sector', () => {
        const { certs, gaps } = buildGuidance({
            orgContext: 'public-sector',
            goal: 'pii',
            platform: 'opensource',
            scenario: 'help',
        });
        expect(certs.some((c) => c.id === 'cert-dsb')).toBe(true);
        expect(gaps.some((g) => g.id === 'gap-pii-compliance')).toBe(true);
        expect(gaps.some((g) => g.id === 'gap-prompt-studio')).toBe(true);
    });

    it('builds stack rationale with guides link and start tools', () => {
        const { stackNote, startToolIds } = buildGuidance(
            {
                orgContext: 'bank-finance',
                regulationPressure: 'regulated',
                goal: 'stack',
                platform: 'fabric',
            },
            { guidesStacks: '/governance#guides-stacks' },
        );
        expect(stackNote).toBeTruthy();
        expect(stackNote?.url).toContain('guides-stacks');
        expect(stackNote?.reason.de).toMatch(/Bank\/Finance|fabric|Regulierungsdruck/i);
        expect(startToolIds).toContain('governance-stack-advisor');
        expect(startToolIds.length).toBeGreaterThanOrEqual(2);
        expect(startToolIds.length).toBeLessThanOrEqual(3);
    });

    it('uses provided guidance links', () => {
        const { certs } = buildGuidance(
            { orgContext: 'enterprise' },
            { cdmp: '/custom/cdmp' },
        );
        const cdmp = certs.find((c) => c.id === 'cert-cdmp');
        expect(cdmp?.url).toBe('/custom/cdmp');
    });

    it('normalizes regulation defaults', () => {
        expect(normalizeRegulation(undefined)).toBe('low');
        expect(normalizeRegulation('regulated')).toBe('regulated');
    });
});
