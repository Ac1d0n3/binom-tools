import { describe, expect, it } from 'vitest';
import { createDefaultDqModelState } from './dq-demo-model.js';
import { getDqRegion, normalizeDqRegionId } from './dq-regions.js';
import {
    applyPackToDqModelState,
    buildPackExtraChecks,
    listPacksForRegion,
} from './dq-rule-packs.js';

describe('dq regions', () => {
    it('normalizes unknown regions to DE', () => {
        expect(normalizeDqRegionId('xx')).toBe('DE');
        expect(normalizeDqRegionId('nl')).toBe('NL');
    });

    it('exposes regional postal patterns', () => {
        expect(getDqRegion('DE').postalPattern).toContain('{5}');
        expect(getDqRegion('NL').postalPattern).toContain('[A-Z]{2}');
        expect(getDqRegion('US').postalPattern).toContain('-');
        expect(getDqRegion('GB').addressOrderHint).toContain('postal_code');
        expect(getDqRegion('US').addressOrderHint).toContain('zip');
    });
});

describe('dq rule packs', () => {
    it('lists packs for every region', () => {
        expect(listPacksForRegion('DE').length).toBeGreaterThanOrEqual(5);
        expect(listPacksForRegion('US').some((p) => p.id === 'address-format')).toBe(true);
    });

    it('applies PII pack without wiping existing columns', () => {
        const base = createDefaultDqModelState();
        const next = applyPackToDqModelState(base, 'pii-detection', 'DE', 'en');
        expect(next.columns.some((c) => c.name === 'email')).toBe(true);
        expect(next.columns.some((c) => c.name === 'iban')).toBe(true);
        expect(next.columns.find((c) => c.name === 'amount')).toBeTruthy();
        const emailRules = next.columns.find((c) => c.name === 'email')?.dqRules || [];
        expect(emailRules.some((r) => r.type === 'regex')).toBe(true);
    });

    it('builds DE vs US address extra checks differently', () => {
        const de = buildPackExtraChecks('address-format', 'DE');
        const us = buildPackExtraChecks('address-format', 'US');
        expect(de.extraChecks.some((c) => c.column === 'postal_code' && c.type === 'regex')).toBe(true);
        expect(us.extraChecks.some((c) => c.column === 'state')).toBe(true);
        expect(de.notes[0]).toContain('DE');
        expect(us.notes[0]).toContain('US');
        expect(getDqRegion('DE').postalPattern).not.toBe(getDqRegion('US').postalPattern);
    });

    it('skips IBAN column for US PII pack', () => {
        const us = applyPackToDqModelState(createDefaultDqModelState(), 'pii-detection', 'US', 'en');
        expect(us.columns.some((c) => c.name === 'iban')).toBe(false);
    });
});
