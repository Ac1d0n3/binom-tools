import { stripLocalePrefix } from './locale';

const STORAGE_KEY = 'binom-tools-disclaimer-banner-hidden';

const visiblePathPrefixes = [
    '/tools',
    '/governance',
    '/resources',
    '/suppliers',
    '/compliance',
    '/playbooks',
    '/sprint-planner',
];

const hiddenPaths = new Set([
    '/about',
    '/impressum',
    '/datenschutz',
    '/disclaimer',
]);

function shouldShowForPath(pathname) {
    const path = stripLocalePrefix(pathname).replace(/\/$/, '') || '/';

    if (hiddenPaths.has(path)) {
        return false;
    }

    return path === '/' || visiblePathPrefixes.some((prefix) => path === prefix || path.startsWith(`${prefix}/`));
}

function isDismissed() {
    try {
        return localStorage.getItem(STORAGE_KEY) === 'true';
    } catch {
        return false;
    }
}

function dismiss() {
    try {
        localStorage.setItem(STORAGE_KEY, 'true');
    } catch {
        // Storage can be unavailable in strict privacy contexts; hiding still works for this page view.
    }
}

export function initDisclaimerBanner() {
    const banner = document.querySelector('[data-disclaimer-banner]');
    if (!banner || isDismissed() || !shouldShowForPath(window.location.pathname)) {
        return;
    }

    banner.hidden = false;
    banner.querySelector('[data-disclaimer-dismiss]')?.addEventListener('click', () => {
        dismiss();
        banner.hidden = true;
    });
}
