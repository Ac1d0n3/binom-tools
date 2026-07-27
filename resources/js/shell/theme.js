import { applyThemeToggleAria, getLocale } from './locale';

/**
 * ThemeFoundation registry — add themes later with same token contract.
 * UI toggle remains light/dark only in this wave.
 */
const THEME_STORAGE_KEY = 'binom-tools-color-scheme';
const THEME_ID_STORAGE_KEY = 'binom-tools-theme-id';
const DEFAULT_THEME_ID = 'blue-water';

/** @type {Record<string, { light: string, dark: string }>} */
const THEME_REGISTRY = {
    'blue-water': {
        light: 'bn-theme-blue-water-light',
        dark: 'bn-theme-blue-water-dark',
    },
};

/** @typedef {'light' | 'dark'} ColorScheme */

/** @returns {string} */
export function getThemeId() {
    const stored = localStorage.getItem(THEME_ID_STORAGE_KEY);
    if (stored && THEME_REGISTRY[stored]) {
        return stored;
    }

    return DEFAULT_THEME_ID;
}

/** @param {string} themeId */
export function setThemeId(themeId) {
    if (!THEME_REGISTRY[themeId]) {
        return;
    }
    localStorage.setItem(THEME_ID_STORAGE_KEY, themeId);
    applyColorScheme(getColorScheme());
}

/** @returns {ColorScheme} */
export function getColorScheme() {
    const stored = localStorage.getItem(THEME_STORAGE_KEY);
    if (stored === 'light' || stored === 'dark') return stored;

    if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
        return 'dark';
    }

    return 'light';
}

/** @returns {{ light: string, dark: string }} */
function activeThemeClasses() {
    return THEME_REGISTRY[getThemeId()] || THEME_REGISTRY[DEFAULT_THEME_ID];
}

/** @param {ColorScheme} scheme */
export function applyColorScheme(scheme) {
    const { light: lightClass, dark: darkClass } = activeThemeClasses();
    const allClasses = Object.values(THEME_REGISTRY).flatMap((entry) => [entry.light, entry.dark]);
    document.documentElement.classList.remove(...allClasses);
    document.body.classList.remove(...allClasses);
    const themeClass = scheme === 'dark' ? darkClass : lightClass;
    document.documentElement.classList.add(themeClass);
    document.body.classList.add(themeClass);
    document.documentElement.dataset.colorScheme = scheme;
    document.documentElement.dataset.themeId = getThemeId();
}

/** @param {ColorScheme} scheme */
export function setColorScheme(scheme) {
    localStorage.setItem(THEME_STORAGE_KEY, scheme);
    applyColorScheme(scheme);
    updateThemeToggleButton(scheme);
    window.dispatchEvent(new CustomEvent('binom-tools:color-scheme', { detail: { scheme, themeId: getThemeId() } }));
}

/** @returns {ColorScheme} */
export function toggleColorScheme() {
    const next = getColorScheme() === 'dark' ? 'light' : 'dark';
    setColorScheme(next);
    return next;
}

/** @param {ColorScheme} [scheme] */
export function updateThemeToggleButton(scheme = getColorScheme()) {
    const toggle = document.querySelector('[data-theme-toggle]');
    if (!toggle) return;

    const isDark = scheme === 'dark';
    const icon = toggle.querySelector('[data-theme-icon]');

    if (icon) {
        icon.classList.remove('fa-sun', 'fa-moon');
        icon.classList.add(isDark ? 'fa-moon' : 'fa-sun');
    }

    toggle.setAttribute('aria-pressed', String(isDark));
    toggle.setAttribute('data-i18n-aria', isDark ? 'theme.toggleToLight' : 'theme.toggleToDark');
    applyThemeToggleAria(getLocale(), isDark);
}

export function initThemeControls() {
    const scheme = getColorScheme();
    applyColorScheme(scheme);
    updateThemeToggleButton(scheme);

    document.querySelector('[data-theme-toggle]')?.addEventListener('click', () => {
        toggleColorScheme();
    });

    window.addEventListener('binom-tools:color-scheme', (event) => {
        const detail = /** @type {CustomEvent<{ scheme: ColorScheme }>} */ (event).detail;
        updateThemeToggleButton(detail.scheme);
    });
}

/** Apply theme before paint when loaded from inline script in layout. */
export function bootstrapColorScheme() {
    applyColorScheme(getColorScheme());
}
