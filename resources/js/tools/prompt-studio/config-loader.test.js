import { describe, it, expect } from 'vitest';
import { resolveConfigBase } from './config-loader.js';

describe('resolveConfigBase', () => {
    it('uses app base fallback when raw base is missing', () => {
        expect(resolveConfigBase(undefined, '/binom-tools')).toBe('/binom-tools/prompt-studio/config');
        expect(resolveConfigBase('', '')).toBe('/prompt-studio/config');
    });

    it('keeps path-absolute config base', () => {
        expect(resolveConfigBase('/binom-tools/prompt-studio/config', '')).toBe(
            '/binom-tools/prompt-studio/config',
        );
    });

    it('strips origin from legacy asset() absolute URLs', () => {
        expect(resolveConfigBase('http://localhost/binom-tools/prompt-studio/config', '')).toBe(
            '/binom-tools/prompt-studio/config',
        );
    });
});
