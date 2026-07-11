<div class="tools-header">
    <div class="tools-header__brand">
        <button
            type="button"
            class="tools-btn tools-btn--ghost tools-header__menu-btn"
            data-tools-sidebar-toggle
            aria-controls="tools-main-content"
            aria-expanded="false"
            aria-label="Toggle navigation"
        >
            ☰
        </button>
        <x-tools.brand />
    </div>

    <div class="tools-header__actions">
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
            <button type="button" class="tools-btn tools-btn--ghost" data-locale="de" aria-pressed="false">DE</button>
            <button type="button" class="tools-btn tools-btn--ghost" data-locale="en" aria-pressed="false">EN</button>
        </div>
    </div>
</div>
