import { describe, it, expect } from 'vitest';
import { resolveConfigBase } from './config-loader.js';

describe('resolveConfigBase', () => {
    it('uses app base fallback when raw base is missing', () => {
        expect(resolveConfigBase(undefined, '/binom-tools')).toBe('/binom-tools/tools/prompt-studio/config');
        expect(resolveConfigBase('', '')).toBe('/tools/prompt-studio/config');
    });

    it('keeps path-absolute config base', () => {
        expect(resolveConfigBase('/binom-tools/tools/prompt-studio/config', '')).toBe(
            '/binom-tools/tools/prompt-studio/config',
        );
    });

    it('prepends app base when server rendered path omits subfolder prefix', () => {
        expect(resolveConfigBase('/tools/prompt-studio/config', '/binom-tools')).toBe(
            '/binom-tools/tools/prompt-studio/config',
        );
    });

    it('strips origin from legacy asset() absolute URLs', () => {
        expect(resolveConfigBase('http://localhost/binom-tools/tools/prompt-studio/config', '')).toBe(
            '/binom-tools/tools/prompt-studio/config',
        );
    });
});
