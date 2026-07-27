import { afterEach, describe, expect, it } from 'vitest';
import { BINOM_NGX_DOCS_URL, initExternalLinks } from './external-links';

describe('initExternalLinks', () => {
    afterEach(() => {
        document.body.innerHTML = '';
    });

    it('rewrites stale localhost binom-ngx doc links', () => {
        document.body.innerHTML = `
            <a href="http://localhost:4200" id="hero">binom-ngx SDKs</a>
            <a href="https://binom.net" id="website">binom.net</a>
        `;

        initExternalLinks();

        expect(document.getElementById('hero').href).toBe(`${BINOM_NGX_DOCS_URL}/`);
        expect(document.getElementById('website').href).toBe('https://binom.net/');
    });
});
