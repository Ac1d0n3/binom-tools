import { getLocale } from '../locale';

/** @typedef {'de' | 'en'} ToolsLocale */

/** @param {ToolsLocale} locale */
export function applyPlaybookLocale(locale) {
    document.querySelectorAll('[data-playbook-locale-panel]').forEach((panel) => {
        panel.hidden = panel.dataset.playbookLocalePanel !== locale;
    });

    document.querySelectorAll('[data-playbook-nav-title]').forEach((link) => {
        const text = link.getAttribute(`data-text-${locale}`);
        if (text) {
            link.textContent = text;
        }
    });

    document.querySelectorAll('[data-playbook-card-title], [data-playbook-card-description], [data-playbook-card-meta]').forEach((element) => {
        const text = element.getAttribute(`data-text-${locale}`);
        if (text !== null) {
            element.textContent = text;
        }
    });

    const detailRoot = document.querySelector('[data-playbook-root]');

    if (detailRoot) {
        const pageTitle = detailRoot.getAttribute(`data-title-${locale}`);

        if (pageTitle) {
            const suffix = detailRoot.getAttribute('data-title-suffix') || '';
            document.title = `${pageTitle}${suffix}`;
        }

        window.dispatchEvent(new CustomEvent('binom-tools:playbook-locale', { detail: { locale } }));
    }
}

export function initPlaybookLocale() {
    if (
        !document.querySelector('[data-playbook-locale-panel], [data-playbook-nav-title], [data-playbook-index-card]')
    ) {
        return;
    }

    applyPlaybookLocale(getLocale());

    window.addEventListener('binom-tools:locale', (event) => {
        const detail = /** @type {CustomEvent<{ locale: ToolsLocale }>} */ (event).detail;
        applyPlaybookLocale(detail.locale);
    });
}
