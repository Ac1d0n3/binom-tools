import { afterEach, beforeEach, describe, expect, it } from 'vitest';
import {
    getLocaleFromPath,
    localeUrlForPath,
    stripAppBasePath,
    stripLocalePrefix,
    withAppBasePath,
} from './locale.js';

describe('locale path helpers with app base', () => {
    const base = '/binom-tools';

    beforeEach(() => {
        document.documentElement.dataset.appBase = base;
    });

    afterEach(() => {
        delete document.documentElement.dataset.appBase;
    });

    it('detects locale after app base path', () => {
        expect(getLocaleFromPath('/binom-tools/de/playbooks/eight-pillars')).toBe('de');
        expect(getLocaleFromPath('/binom-tools/playbooks/eight-pillars')).toBe(null);
        expect(getLocaleFromPath('/binom-tools/en/tools')).toBe('en');
    });

    it('builds localized urls after app base path', () => {
        expect(localeUrlForPath('/binom-tools/playbooks/eight-pillars', 'de')).toBe(
            '/binom-tools/de/playbooks/eight-pillars',
        );
        expect(localeUrlForPath('/binom-tools/de/playbooks/eight-pillars', 'en')).toBe(
            '/binom-tools/playbooks/eight-pillars',
        );
        expect(localeUrlForPath('/binom-tools/en/playbooks/eight-pillars', 'en')).toBe(
            '/binom-tools/playbooks/eight-pillars',
        );
    });

    it('strips app base and locale prefix', () => {
        expect(stripAppBasePath('/binom-tools/playbooks/eight-pillars')).toBe('/playbooks/eight-pillars');
        expect(stripLocalePrefix('/binom-tools/de/playbooks/eight-pillars')).toBe('/playbooks/eight-pillars');
        expect(withAppBasePath('/de/playbooks/eight-pillars')).toBe('/binom-tools/de/playbooks/eight-pillars');
    });
});

describe('locale path helpers at domain root', () => {
    afterEach(() => {
        delete document.documentElement.dataset.appBase;
    });

    it('works without app base', () => {
        expect(stripAppBasePath('/playbooks/eight-pillars')).toBe('/playbooks/eight-pillars');
        expect(stripLocalePrefix('/de/playbooks/eight-pillars')).toBe('/playbooks/eight-pillars');
        expect(withAppBasePath('/de/playbooks/eight-pillars')).toBe('/de/playbooks/eight-pillars');
        expect(localeUrlForPath('/playbooks/eight-pillars', 'de')).toBe('/de/playbooks/eight-pillars');
    });
});
