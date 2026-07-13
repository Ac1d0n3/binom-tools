import { getLocale, getShellLabel } from '../locale.js';

/** @param {HTMLElement} panel */
function collectDiagramImages(panel) {
    return [...panel.querySelectorAll('.playbook-prose img.playbook-prose__image--diagram')];
}

/** @param {HTMLImageElement} image */
function captionForImage(image) {
    const figure = image.closest('figure.playbook-prose__figure');
    const caption = figure?.querySelector('.playbook-prose__figure-caption');

    return caption?.textContent?.trim() || image.getAttribute('alt') || '';
}

function ensureLightboxModal() {
    let modal = document.querySelector('[data-playbook-lightbox]');

    if (modal instanceof HTMLElement) {
        return modal;
    }

    modal = document.createElement('div');
    modal.className = 'playbook-lightbox';
    modal.hidden = true;
    modal.setAttribute('data-playbook-lightbox', '');
    modal.setAttribute('role', 'dialog');
    modal.setAttribute('aria-modal', 'true');
    modal.innerHTML = `
        <div class="playbook-lightbox__backdrop" data-playbook-lightbox-close tabindex="-1"></div>
        <div class="playbook-lightbox__panel">
            <button type="button" class="playbook-lightbox__close" data-playbook-lightbox-close aria-label="">
                <i class="fa-solid fa-xmark" aria-hidden="true"></i>
            </button>
            <button type="button" class="playbook-lightbox__nav playbook-lightbox__nav--prev" data-playbook-lightbox-prev hidden aria-label="">
                <i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
            </button>
            <button type="button" class="playbook-lightbox__nav playbook-lightbox__nav--next" data-playbook-lightbox-next hidden aria-label="">
                <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
            </button>
            <figure class="playbook-lightbox__figure">
                <img class="playbook-lightbox__image" alt="" />
                <figcaption class="playbook-lightbox__caption"></figcaption>
            </figure>
            <p class="playbook-lightbox__counter" hidden></p>
        </div>
    `;

    document.body.appendChild(modal);

    return modal;
}

function applyLightboxLabels(modal) {
    const locale = getLocale();
    const close = modal.querySelector('[data-playbook-lightbox-close].playbook-lightbox__close');
    const prev = modal.querySelector('[data-playbook-lightbox-prev]');
    const next = modal.querySelector('[data-playbook-lightbox-next]');

    if (close instanceof HTMLButtonElement) {
        close.setAttribute('aria-label', getShellLabel('playbooks.lightbox.close', locale));
    }
    if (prev instanceof HTMLButtonElement) {
        prev.setAttribute('aria-label', getShellLabel('playbooks.lightbox.prev', locale));
    }
    if (next instanceof HTMLButtonElement) {
        next.setAttribute('aria-label', getShellLabel('playbooks.lightbox.next', locale));
    }
}

/**
 * @param {HTMLElement} panel
 * @returns {{ disconnect: () => void }}
 */
export function initPlaybookImageLightbox(panel) {
    const modal = ensureLightboxModal();
    applyLightboxLabels(modal);

    const imageEl = /** @type {HTMLImageElement | null} */ (modal.querySelector('.playbook-lightbox__image'));
    const captionEl = modal.querySelector('.playbook-lightbox__caption');
    const counterEl = modal.querySelector('.playbook-lightbox__counter');
    const prevBtn = modal.querySelector('[data-playbook-lightbox-prev]');
    const nextBtn = modal.querySelector('[data-playbook-lightbox-next]');

    /** @type {HTMLImageElement[]} */
    let slides = [];
    let activeIndex = 0;
    /** @type {HTMLImageElement | null} */
    let lastTrigger = null;

    const renderSlide = () => {
        const slide = slides[activeIndex];

        if (!slide || !imageEl) {
            return;
        }

        imageEl.src = slide.currentSrc || slide.src;
        imageEl.alt = slide.alt;

        if (captionEl instanceof HTMLElement) {
            captionEl.textContent = captionForImage(slide);
            captionEl.hidden = captionEl.textContent === '';
        }

        const hasMultiple = slides.length > 1;

        if (prevBtn instanceof HTMLButtonElement) {
            prevBtn.hidden = !hasMultiple;
        }
        if (nextBtn instanceof HTMLButtonElement) {
            nextBtn.hidden = !hasMultiple;
        }
        if (counterEl instanceof HTMLElement) {
            counterEl.hidden = !hasMultiple;
            counterEl.textContent = hasMultiple ? `${activeIndex + 1} / ${slides.length}` : '';
        }
    };

    const openAt = (index, trigger) => {
        slides = collectDiagramImages(panel);

        if (slides.length === 0) {
            return;
        }

        activeIndex = Math.max(0, Math.min(index, slides.length - 1));
        lastTrigger = trigger;
        renderSlide();
        modal.hidden = false;
        document.body.classList.add('playbook-lightbox-open');

        const closeBtn = modal.querySelector('.playbook-lightbox__close');
        if (closeBtn instanceof HTMLButtonElement) {
            closeBtn.focus();
        }
    };

    const close = () => {
        modal.hidden = true;
        document.body.classList.remove('playbook-lightbox-open');

        if (lastTrigger instanceof HTMLElement) {
            lastTrigger.focus();
        }
    };

    const step = (delta) => {
        if (slides.length <= 1) {
            return;
        }

        activeIndex = (activeIndex + delta + slides.length) % slides.length;
        renderSlide();
    };

    const onPanelClick = (event) => {
        const target = event.target;

        if (!(target instanceof HTMLImageElement)) {
            return;
        }

        if (!target.matches('.playbook-prose__image--diagram') || !panel.contains(target)) {
            return;
        }

        slides = collectDiagramImages(panel);
        const index = slides.indexOf(target);

        if (index >= 0) {
            openAt(index, target);
        }
    };

    const onPanelKeydown = (event) => {
        const target = event.target;

        if (!(target instanceof HTMLImageElement)) {
            return;
        }

        if (!target.matches('.playbook-prose__image--diagram') || !panel.contains(target)) {
            return;
        }

        if (event.key !== 'Enter' && event.key !== ' ') {
            return;
        }

        event.preventDefault();
        slides = collectDiagramImages(panel);
        const index = slides.indexOf(target);

        if (index >= 0) {
            openAt(index, target);
        }
    };

    const onModalClick = (event) => {
        if (event.target instanceof HTMLElement && event.target.closest('[data-playbook-lightbox-close]')) {
            close();
        }
    };

    const onModalKeydown = (event) => {
        if (modal.hidden) {
            return;
        }

        if (event.key === 'Escape') {
            event.preventDefault();
            close();
            return;
        }

        if (event.key === 'ArrowLeft') {
            event.preventDefault();
            step(-1);
            return;
        }

        if (event.key === 'ArrowRight') {
            event.preventDefault();
            step(1);
        }
    };

    const onPrev = () => step(-1);
    const onNext = () => step(1);

    const onLocale = () => applyLightboxLabels(modal);

    panel.addEventListener('click', onPanelClick);
    panel.addEventListener('keydown', onPanelKeydown);
    modal.addEventListener('click', onModalClick);
    modal.addEventListener('keydown', onModalKeydown);
    prevBtn?.addEventListener('click', onPrev);
    nextBtn?.addEventListener('click', onNext);
    window.addEventListener('binom-tools:locale', onLocale);
    window.addEventListener('binom-tools:playbook-locale', onLocale);

    return {
        disconnect: () => {
            panel.removeEventListener('click', onPanelClick);
            panel.removeEventListener('keydown', onPanelKeydown);
            modal.removeEventListener('click', onModalClick);
            modal.removeEventListener('keydown', onModalKeydown);
            prevBtn?.removeEventListener('click', onPrev);
            nextBtn?.removeEventListener('click', onNext);
            window.removeEventListener('binom-tools:locale', onLocale);
            window.removeEventListener('binom-tools:playbook-locale', onLocale);

            if (!modal.hidden) {
                close();
            }
        },
    };
}
