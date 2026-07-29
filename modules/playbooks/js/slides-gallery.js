import { getLocale, getShellLabel } from '../../../resources/js/shell/locale.js';
import { bindSharedModal, closeSharedModal, openSharedModal } from '../../../resources/js/shared/modal.js';

/**
 * @typedef {{
 *   src: string,
 *   alt: string,
 *   caption: string,
 *   storySlug: string,
 *   storyTitle: string,
 *   storyUrl: string,
 *   seriesId: string | null,
 *   seriesTitle: string | null,
 *   seriesUrl: string | null,
 *   seriesPart: number | null
 * }} PlaybookSlide
 */

/**
 * @param {ParentNode} [root]
 */
export function initPlaybookSlidesGallery(root = document) {
    const openBtn = root.querySelector('[data-playbook-slides-open]');
    const modal = root.querySelector('[data-playbook-slides-modal]');
    const payloadEl = root.querySelector('[data-playbook-slides]');

    if (!(openBtn instanceof HTMLButtonElement) || !(modal instanceof HTMLDialogElement) || !payloadEl) {
        return;
    }

    /** @type {PlaybookSlide[]} */
    let slides = [];
    try {
        const parsed = JSON.parse(payloadEl.textContent || '[]');
        slides = Array.isArray(parsed) ? parsed : [];
    } catch {
        slides = [];
    }

    if (slides.length === 0) {
        openBtn.hidden = true;
        return;
    }

    const imageEl = /** @type {HTMLImageElement | null} */ (modal.querySelector('[data-playbook-slides-image]'));
    const captionEl = modal.querySelector('[data-playbook-slides-caption]');
    const counterEl = modal.querySelector('[data-playbook-slides-counter]');
    const groupEl = modal.querySelector('[data-playbook-slides-group]');
    const storyLink = /** @type {HTMLAnchorElement | null} */ (modal.querySelector('[data-playbook-slides-story-link]'));
    const storyTitleEl = modal.querySelector('[data-playbook-slides-story-title]');
    const seriesLink = /** @type {HTMLAnchorElement | null} */ (modal.querySelector('[data-playbook-slides-series-link]'));
    const seriesTitleEl = modal.querySelector('[data-playbook-slides-series-title]');
    const partEl = modal.querySelector('[data-playbook-slides-part]');
    const prevBtn = /** @type {HTMLButtonElement | null} */ (modal.querySelector('[data-playbook-slides-prev]'));
    const nextBtn = /** @type {HTMLButtonElement | null} */ (modal.querySelector('[data-playbook-slides-next]'));

    let index = 0;
    let bound = false;

    const applyLabels = () => {
        const locale = getLocale();
        openBtn.setAttribute('aria-label', getShellLabel('playbooks.slides.open', locale));
        openBtn.setAttribute('title', getShellLabel('playbooks.slides.open', locale));
        if (prevBtn) {
            prevBtn.setAttribute('aria-label', getShellLabel('playbooks.slides.prev', locale));
        }
        if (nextBtn) {
            nextBtn.setAttribute('aria-label', getShellLabel('playbooks.slides.next', locale));
        }
        const closeBtn = modal.querySelector('[data-playbook-slides-close]');
        if (closeBtn instanceof HTMLButtonElement) {
            closeBtn.setAttribute('aria-label', getShellLabel('playbooks.slides.close', locale));
        }
    };

    const render = () => {
        const slide = slides[index];
        if (!slide || !imageEl) {
            return;
        }

        imageEl.src = slide.src;
        imageEl.alt = slide.alt || slide.caption || slide.storyTitle || '';

        if (counterEl) {
            counterEl.textContent = `${index + 1} / ${slides.length}`;
        }

        if (groupEl) {
            if (slide.seriesTitle) {
                groupEl.hidden = false;
                groupEl.textContent = slide.seriesTitle;
            } else {
                groupEl.hidden = true;
                groupEl.textContent = '';
            }
        }

        if (captionEl) {
            captionEl.textContent = slide.caption || slide.alt || '';
            captionEl.hidden = !captionEl.textContent;
        }

        if (storyLink && storyTitleEl) {
            storyLink.href = slide.storyUrl;
            storyTitleEl.textContent = slide.storyTitle ? ` — ${slide.storyTitle}` : '';
        }

        if (seriesLink && seriesTitleEl) {
            if (slide.seriesUrl && slide.seriesTitle) {
                seriesLink.hidden = false;
                seriesLink.href = slide.seriesUrl;
                seriesTitleEl.textContent = ` — ${slide.seriesTitle}`;
            } else {
                seriesLink.hidden = true;
                seriesLink.removeAttribute('href');
                seriesTitleEl.textContent = '';
            }
        }

        if (partEl) {
            if (slide.seriesPart != null && Number(slide.seriesPart) > 0) {
                const locale = getLocale();
                partEl.hidden = false;
                partEl.textContent = `${getShellLabel('playbooks.seriesPartLabel', locale)} ${slide.seriesPart}`;
            } else {
                partEl.hidden = true;
                partEl.textContent = '';
            }
        }

        if (prevBtn) {
            prevBtn.disabled = slides.length < 2;
            prevBtn.hidden = slides.length < 2;
        }
        if (nextBtn) {
            nextBtn.disabled = slides.length < 2;
            nextBtn.hidden = slides.length < 2;
        }
    };

    const go = (delta) => {
        if (slides.length === 0) {
            return;
        }
        index = (index + delta + slides.length) % slides.length;
        render();
    };

    const onKeydown = (event) => {
        if (!modal.open) {
            return;
        }
        if (event.key === 'ArrowLeft') {
            event.preventDefault();
            go(-1);
        } else if (event.key === 'ArrowRight') {
            event.preventDefault();
            go(1);
        }
    };

    const openAt = (startIndex = 0) => {
        index = Math.max(0, Math.min(startIndex, slides.length - 1));
        applyLabels();
        render();
        openSharedModal(modal);
        if (!bound) {
            bindSharedModal(modal, { closeSelector: '[data-playbook-slides-close], [data-shared-modal-close]' });
            bound = true;
        }
    };

    openBtn.addEventListener('click', (event) => {
        event.preventDefault();
        openAt(0);
    });

    prevBtn?.addEventListener('click', (event) => {
        event.preventDefault();
        go(-1);
    });

    nextBtn?.addEventListener('click', (event) => {
        event.preventDefault();
        go(1);
    });

    document.addEventListener('keydown', onKeydown);

    modal.addEventListener('close', () => {
        // Keep listeners; modal may reopen.
    });

    applyLabels();
}

export function closePlaybookSlidesGallery(root = document) {
    const modal = root.querySelector('[data-playbook-slides-modal]');
    if (modal instanceof HTMLDialogElement) {
        closeSharedModal(modal);
    }
}
