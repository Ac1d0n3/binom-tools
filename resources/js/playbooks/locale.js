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

    document.querySelectorAll('[data-playbook-card-title], [data-playbook-card-description], [data-playbook-card-meta], [data-playbook-card-series-badge], [data-playbook-pager-title], [data-playbook-series-title], [data-playbook-series-part-of], [data-playbook-series-part-title], [data-playbook-series-card-title], [data-playbook-series-card-part-title]').forEach((element) => {
        const text = element.getAttribute(`data-text-${locale}`);
        if (text !== null) {
            if (element.matches('[data-playbook-card-series-badge]')) {
                const label = element.querySelector('span');
                if (label) {
                    label.textContent = text;
                }
            } else {
                element.textContent = text;
            }
        }
    });

    document.querySelectorAll('[data-playbook-series-card-meta]').forEach((element) => {
        const partCount = element.getAttribute('data-part-count') ?? '0';
        const readingTime = element.getAttribute(`data-reading-time-${locale}`) ?? '0';

        if (locale === 'de') {
            element.textContent = `${partCount} Teile · ${readingTime} min gesamt`;
        } else {
            element.textContent = `${partCount} parts · ${readingTime} min total`;
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
