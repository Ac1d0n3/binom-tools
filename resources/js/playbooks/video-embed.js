import {
    COOKIE_CONSENT_EVENT,
    hasExternalMediaConsent,
} from '../cookie-consent';

/** @param {HTMLElement} placeholder */
function activateVideoEmbed(placeholder) {
    if (placeholder.dataset.videoActivated === 'true') {
        return;
    }

    const embedSrc = placeholder.getAttribute('data-embed-src');

    if (!embedSrc) {
        return;
    }

    const title = placeholder.getAttribute('data-video-title') ?? 'Embedded video';
    const iframe = document.createElement('iframe');
    iframe.src = embedSrc;
    iframe.title = title;
    iframe.className = 'playbook-video__iframe';
    iframe.setAttribute('allow', 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share');
    iframe.setAttribute('allowfullscreen', 'true');
    iframe.setAttribute('loading', 'lazy');

    placeholder.replaceChildren(iframe);
    placeholder.dataset.videoActivated = 'true';
    placeholder.classList.add('playbook-video--active');
}

/** @param {ParentNode} root */
export function initPlaybookVideoEmbeds(root = document) {
    const placeholders = root.querySelectorAll('[data-video-embed]');

    placeholders.forEach((placeholder) => {
        if (!(placeholder instanceof HTMLElement)) {
            return;
        }

        const trigger = placeholder.querySelector('.playbook-video__consent-trigger');

        trigger?.addEventListener('click', () => {
            if (hasExternalMediaConsent()) {
                activateVideoEmbed(placeholder);
                return;
            }

            window.dispatchEvent(new CustomEvent('binom-tools:cookie-consent-required'));
        });
    });

    if (hasExternalMediaConsent()) {
        placeholders.forEach((placeholder) => {
            if (placeholder instanceof HTMLElement) {
                activateVideoEmbed(placeholder);
            }
        });
    }
}

window.addEventListener(COOKIE_CONSENT_EVENT, (event) => {
    const detail = /** @type {CustomEvent<{ externalMedia?: boolean }>} */ (event).detail;

    if (detail?.externalMedia !== true) {
        return;
    }

    initPlaybookVideoEmbeds(document);
});

window.addEventListener('binom-tools:cookie-consent-required', () => {
    const banner = document.querySelector('[data-cookie-banner]');

    if (banner instanceof HTMLElement) {
        banner.hidden = false;
        banner.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
    }
});
