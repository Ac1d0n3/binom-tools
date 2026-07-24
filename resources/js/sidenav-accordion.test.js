import { beforeEach, describe, expect, it } from 'vitest';
import { readOpenAccordions, writeOpenAccordions } from './sidenav-accordion.js';

describe('sidenav accordion persistence', () => {
    beforeEach(() => {
        localStorage.clear();
    });

    it('returns an empty list when nothing is stored', () => {
        expect(readOpenAccordions()).toEqual([]);
    });

    it('round-trips open accordion ids', () => {
        writeOpenAccordions(['qlik', 'tableau', 'qlik']);
        expect(readOpenAccordions()).toEqual(['qlik', 'tableau']);
    });

    it('ignores invalid storage payloads', () => {
        localStorage.setItem('binom-tools-sidenav-accordions', '{bad');
        expect(readOpenAccordions()).toEqual([]);

        localStorage.setItem('binom-tools-sidenav-accordions', JSON.stringify({ qlik: true }));
        expect(readOpenAccordions()).toEqual([]);

        localStorage.setItem('binom-tools-sidenav-accordions', JSON.stringify(['qlik', 2, '', null]));
        expect(readOpenAccordions()).toEqual(['qlik']);
    });
});
