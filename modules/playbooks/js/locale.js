import { getLocale } from '../../../resources/js/shell/locale';
import { isPlaybookRead } from './read-state';
import { buildSeriesCardMetaText } from './reading-time';

/** @typedef {'de' | 'en'} ToolsLocale */

/**
 * @param {Element} element
 * @param {ToolsLocale} locale
 * @returns {{ readMinutes: number, readPartCount: number }}
 */
function readProgressForSeriesCard(element, locale) {
    const card = element.closest('.tools-series-card');
    if (!card) {
        return { readMinutes: 0, readPartCount: 0 };
    }

    let readMinutes = 0;
    let readPartCount = 0;

    card.querySelectorAll('[data-playbook-series-part]').forEach((part) => {
        const slug = part.getAttribute('data-slug') ?? '';
        if (!slug || !isPlaybookRead(slug)) {
            return;
        }

        readPartCount += 1;

        const minutes = Number.parseInt(part.getAttribute(`data-reading-time-${locale}`) ?? '0', 10);
        if (Number.isFinite(minutes) && minutes > 0) {
            readMinutes += minutes;
        }
    });

    return { readMinutes, readPartCount };
}

/**
 * Apply localStorage read state to series part links (overview cards + series detail).
 */
export function syncSeriesPartReadState() {
    document.querySelectorAll('[data-playbook-series-part][data-slug]').forEach((part) => {
        const slug = part.getAttribute('data-slug') ?? '';
        const read = slug !== '' && isPlaybookRead(slug);
        part.classList.toggle('is-read', read);
        part.dataset.read = read ? '1' : '0';
    });
}

/**
 * @param {ToolsLocale} locale
 */
export function refreshSeriesCardMeta(locale = getLocale()) {
    syncSeriesPartReadState();

    document.querySelectorAll('[data-playbook-series-card-meta]').forEach((element) => {
        const partCount = Number.parseInt(element.getAttribute('data-part-count') ?? '0', 10) || 0;
        const totalMinutes = Number.parseInt(
            element.getAttribute(`data-reading-time-${locale}`) ?? '0',
            10,
        ) || 0;
        const { readMinutes, readPartCount } = readProgressForSeriesCard(element, locale);

        element.textContent = buildSeriesCardMetaText({
            partCount,
            totalMinutes,
            readMinutes,
            readPartCount,
            locale,
        });
    });
}

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

    document.querySelectorAll('[data-playbook-card-title], [data-playbook-card-description], [data-playbook-card-meta], [data-playbook-card-series-badge], [data-playbook-pager-title], [data-playbook-series-title], [data-playbook-series-part-of], [data-playbook-series-part-title], [data-playbook-series-card-title], [data-playbook-series-card-summary], [data-playbook-series-card-part-title], [data-overview-category] .tools-tag-sidebar__option-label').forEach((element) => {
        const text = element.getAttribute(`data-text-${locale}`);
        if (text !== null) {
            if (element.matches('[data-playbook-card-series-badge]')) {
                const label = element.querySelector('span');
                if (label) {
                    label.textContent = text;
                }
            } else if (element.matches('.tools-series-card__progress-dot[data-playbook-series-card-part-title]')) {
                element.setAttribute('title', text);
                const indexMatch = (element.getAttribute('aria-label') ?? '').match(/^(\d+)\.\s/);
                const indexPrefix = indexMatch ? `${indexMatch[1]}. ` : '';
                element.setAttribute('aria-label', `${indexPrefix}${text}`);
                const partIndex = element.querySelector('.sr-only');
                if (partIndex) {
                    partIndex.textContent = `${indexPrefix}${text}`;
                }
            } else {
                element.textContent = text;
            }
        }
    });

    refreshSeriesCardMeta(locale);

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
        !document.querySelector(
            '[data-playbook-locale-panel], [data-playbook-nav-title], [data-playbook-index-card], [data-playbook-series-card-meta], [data-playbook-series-page], [data-playbook-series-part]',
        )
    ) {
        return;
    }

    applyPlaybookLocale(getLocale());
    syncSeriesPartReadState();

    window.addEventListener('binom-tools:locale', (event) => {
        const detail = /** @type {CustomEvent<{ locale: ToolsLocale }>} */ (event).detail;
        applyPlaybookLocale(detail?.locale ?? getLocale());
    });

    window.addEventListener('binom-tools:playbook-read', () => {
        refreshSeriesCardMeta(getLocale());
    });

    window.addEventListener('binom-tools:playbook-read-reset', () => {
        refreshSeriesCardMeta(getLocale());
    });
}
