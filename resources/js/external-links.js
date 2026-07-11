export const BINOM_NGX_DOCS_URL = 'https://ngx-docs.binom.net';

const STALE_BINOM_NGX_HREFS = new Set([
    'http://localhost:4200',
    'https://localhost:4200',
    'http://127.0.0.1:4200',
]);

/** Correct stale binom-ngx doc links left over from cached server config. */
export function initExternalLinks() {
    document.querySelectorAll('a[href]').forEach((link) => {
        if (!STALE_BINOM_NGX_HREFS.has(link.getAttribute('href') ?? '')) {
            return;
        }

        link.href = BINOM_NGX_DOCS_URL;
    });
}
