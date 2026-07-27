import { describe, expect, it } from 'vitest';
import { buildGuidance } from './advisor-guidance.js';

describe('buildGuidance', () => {
    it('returns lean certs for sme', () => {
        const { certs } = buildGuidance({ orgContext: 'sme', goal: 'stack', platform: 'unknown' });
        expect(certs.length).toBeGreaterThanOrEqual(2);
        expect(certs.some((c) => c.id === 'cert-pillars')).toBe(true);
        expect(certs.some((c) => c.id === 'cert-cdmp')).toBe(false);
    });

    it('prioritizes privacy and security for bank-finance', () => {
        const { certs } = buildGuidance({ orgContext: 'bank-finance', goal: 'pii', platform: 'fabric' });
        expect(certs.some((c) => c.id === 'cert-cippe-bank')).toBe(true);
        expect(certs.some((c) => c.id === 'cert-iso-bank')).toBe(true);
        const { gaps } = buildGuidance({ orgContext: 'bank-finance', goal: 'pii', platform: 'fabric' });
        expect(gaps.some((g) => g.id === 'gap-pii-compliance')).toBe(true);
    });

    it('flags stack gap when platform is unknown and goal is stack', () => {
        const { gaps } = buildGuidance({ orgContext: 'unknown', goal: 'stack', platform: 'unknown' });
        expect(gaps.some((g) => g.id === 'gap-stack-unknown')).toBe(true);
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

    it('uses provided guidance links', () => {
        const { certs } = buildGuidance(
            { orgContext: 'enterprise' },
            { cdmp: '/custom/cdmp' },
        );
        const cdmp = certs.find((c) => c.id === 'cert-cdmp');
        expect(cdmp?.url).toBe('/custom/cdmp');
    });
});
