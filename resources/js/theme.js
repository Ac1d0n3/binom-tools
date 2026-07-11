import { applyThemeToggleAria, getLocale } from './locale';

const THEME_STORAGE_KEY = 'binom-tools-color-scheme';
const THEME_LIGHT = 'bn-theme-blue-water-light';
const THEME_DARK = 'bn-theme-blue-water-dark';

/** @typedef {'light' | 'dark'} ColorScheme */

/** @returns {ColorScheme} */
export function getColorScheme() {
    const stored = localStorage.getItem(THEME_STORAGE_KEY);
    if (stored === 'light' || stored === 'dark') return stored;

    if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
        return 'dark';
    }

    return 'light';
}

/** @param {ColorScheme} scheme */
export function applyColorScheme(scheme) {
    const lightClass = THEME_LIGHT;
    const darkClass = THEME_DARK;
    document.documentElement.classList.remove(lightClass, darkClass);
    document.body.classList.remove(lightClass, darkClass);
    const themeClass = scheme === 'dark' ? darkClass : lightClass;
    document.documentElement.classList.add(themeClass);
    document.body.classList.add(themeClass);
    document.documentElement.dataset.colorScheme = scheme;
}

/** @param {ColorScheme} scheme */
export function setColorScheme(scheme) {
    localStorage.setItem(THEME_STORAGE_KEY, scheme);
    applyColorScheme(scheme);
    updateThemeToggleButton(scheme);
    window.dispatchEvent(new CustomEvent('binom-tools:color-scheme', { detail: { scheme } }));
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
    toggle.classList.toggle('tools-header__theme-toggle--dark', isDark);
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
