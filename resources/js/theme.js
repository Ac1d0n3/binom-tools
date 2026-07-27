/** Re-export ThemeFoundation registry (stable import path for app.js). */
export {
    getThemeId,
    setThemeId,
    getColorScheme,
    applyColorScheme,
    setColorScheme,
    toggleColorScheme,
    updateThemeToggleButton,
    initThemeControls,
    bootstrapColorScheme,
} from './foundations/theme/theme.js';
