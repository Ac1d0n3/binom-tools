<div class="tools-header">
    <div class="tools-header__brand">
        <button
            type="button"
            class="tools-icon-button tools-header__menu-btn"
            data-tools-sidebar-toggle
            aria-controls="tools-main-content"
            aria-expanded="false"
            data-i18n-aria="nav.openMenu"
            title="Open navigation"
        >
            <i class="fa-solid fa-bars" aria-hidden="true"></i>
        </button>
        <x-tools.brand />
    </div>

    <div class="tools-header__actions">
        <div class="tools-header__settings" data-header-settings>
            <button
                type="button"
                class="tools-icon-button tools-header__settings-toggle"
                data-header-settings-toggle
                aria-haspopup="menu"
                aria-expanded="false"
                aria-controls="tools-header-settings-menu"
                data-i18n-aria="settings.openMenu"
                title="Settings"
            >
                <i class="fa-solid fa-gear" aria-hidden="true"></i>
            </button>
            <div
                id="tools-header-settings-menu"
                class="tools-header__settings-menu"
                data-header-settings-menu
                role="menu"
                hidden
            >
                <label class="tools-header__settings-option" role="menuitemcheckbox">
                    <input type="checkbox" data-shell-full-width-toggle />
                    <span data-i18n="settings.fullWidth">Full width</span>
                </label>
            </div>
        </div>
        <button
            type="button"
            class="tools-icon-button tools-header__theme-toggle"
            data-theme-toggle
            aria-pressed="false"
            data-i18n-aria="theme.toggleToDark"
            title="Toggle color scheme"
        >
            <i class="fa-solid fa-sun tools-header__theme-icon" data-theme-icon aria-hidden="true"></i>
        </button>
        <div class="tools-header__locale" role="group" aria-label="Language">
            <button
                type="button"
                class="tools-btn tools-btn--ghost"
                data-locale="de"
                data-locale-url="{{ $localeSwitchUrls['de'] ?? '' }}"
                aria-pressed="false"
            >DE</button>
            <button
                type="button"
                class="tools-btn tools-btn--ghost"
                data-locale="en"
                data-locale-url="{{ $localeSwitchUrls['en'] ?? '' }}"
                aria-pressed="false"
            >EN</button>
        </div>
    </div>
</div>
