/** Cookie consent for external media embeds (videos). */
export const COOKIE_CONSENT_STORAGE_KEY = 'binom-tools-cookie-consent';
export const COOKIE_CONSENT_EVENT = 'binom-tools:cookie-consent';

/** @typedef {{ essential: true, externalMedia: boolean, ts: number }} CookieConsentState */

/** @returns {CookieConsentState | null} */
export function readCookieConsent() {
    try {
        const raw = localStorage.getItem(COOKIE_CONSENT_STORAGE_KEY);
        if (!raw) return null;

        const parsed = JSON.parse(raw);

        if (typeof parsed?.externalMedia !== 'boolean') {
            return null;
        }

        return {
            essential: true,
            externalMedia: parsed.externalMedia,
            ts: typeof parsed.ts === 'number' ? parsed.ts : Date.now(),
        };
    } catch {
        return null;
    }
}

/** @param {boolean} externalMedia */
export function saveCookieConsent(externalMedia) {
    /** @type {CookieConsentState} */
    const state = {
        essential: true,
        externalMedia,
        ts: Date.now(),
    };

    localStorage.setItem(COOKIE_CONSENT_STORAGE_KEY, JSON.stringify(state));
    window.dispatchEvent(
        new CustomEvent(COOKIE_CONSENT_EVENT, { detail: state }),
    );

    return state;
}

/** @returns {boolean} */
export function hasExternalMediaConsent() {
    return readCookieConsent()?.externalMedia === true;
}

export function initCookieConsent() {
    const banner = document.querySelector('[data-cookie-banner]');
    if (!(banner instanceof HTMLElement)) return;

    const essentialButton = banner.querySelector('[data-cookie-consent-essential]');
    const acceptAllButton = banner.querySelector('[data-cookie-consent-all]');

    const hideBanner = () => {
        banner.hidden = true;
    };

    const showBanner = () => {
        banner.hidden = false;
    };

    if (readCookieConsent()) {
        hideBanner();
    } else {
        showBanner();
    }

    essentialButton?.addEventListener('click', () => {
        saveCookieConsent(false);
        hideBanner();
    });

    acceptAllButton?.addEventListener('click', () => {
        saveCookieConsent(true);
        hideBanner();
    });
}
