@php
    use App\Support\Locale;

    $avatarLabel = '';
    $avatarColor = 'accent-1';
    $avatarIcon = '';
    $avatarInitials = '';
    $avatarHex = [
        'accent-1' => '#2563eb',
        'accent-2' => '#0d9488',
        'accent-3' => '#c2410c',
        'accent-4' => '#7c3aed',
        'accent-5' => '#be185d',
        'accent-6' => '#475569',
        'accent-7' => '#15803d',
        'accent-8' => '#d97706',
        'accent-9' => '#0891b2',
        'accent-10' => '#4338ca',
        'accent-11' => '#e11d48',
        'accent-12' => '#65a30d',
        'outline-1' => '#2563eb',
        'outline-2' => '#0d9488',
        'outline-3' => '#c2410c',
        'outline-4' => '#7c3aed',
        'outline-5' => '#be185d',
        'outline-6' => '#475569',
        'dotted-1' => '#2563eb',
        'dotted-2' => '#0d9488',
        'dotted-3' => '#c2410c',
        'dotted-4' => '#7c3aed',
        'dotted-5' => '#be185d',
        'dotted-6' => '#475569',
        'dashed-1' => '#2563eb',
        'dashed-2' => '#0d9488',
        'dashed-3' => '#c2410c',
        'dashed-4' => '#7c3aed',
        'dashed-5' => '#be185d',
        'dashed-6' => '#475569',
    ];

    if (! empty($accountUser)) {
        $avatarLabel = (string) ($accountUser['displayName'] ?? $accountUser['email'] ?? '');
        $token = (string) ($accountUser['colorToken'] ?? 'accent-1');
        $avatarColor = array_key_exists($token, $avatarHex) ? $token : 'accent-1';
        $avatarIcon = trim((string) ($accountUser['avatarIcon'] ?? ''));
        $short = strtoupper(preg_replace('/[^A-Za-z]/', '', (string) ($accountUser['shortName'] ?? '')) ?? '');
        if (strlen($short) >= 2) {
            $avatarInitials = substr($short, 0, 3);
        } else {
            $parts = preg_split('/\s+/', trim($avatarLabel)) ?: [];
            $letters = '';
            foreach ($parts as $part) {
                $clean = preg_replace('/[^A-Za-z]/', '', $part) ?? '';
                if ($clean !== '') {
                    $letters .= strtoupper($clean[0]);
                }
                if (strlen($letters) >= 3) {
                    break;
                }
            }
            $avatarInitials = $letters !== '' ? substr($letters, 0, 3) : '??';
        }
    }

    $avatarBg = $avatarHex[$avatarColor] ?? '#2563eb';
    $avatarBordered = str_starts_with($avatarColor, 'outline-')
        || str_starts_with($avatarColor, 'dotted-')
        || str_starts_with($avatarColor, 'dashed-');
    $avatarBorderStyle = str_starts_with($avatarColor, 'dotted-')
        ? 'dotted'
        : (str_starts_with($avatarColor, 'dashed-') ? 'dashed' : 'solid');
@endphp

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

    <div class="tools-header__center">
        <p class="tools-header__mission" data-i18n="header.mission">
            Data governance online: quality, PII, catalog, compliance — hub, stories, paths, and tools.
        </p>
        <form
            class="tools-header__search"
            method="get"
            action="{{ locale_route('search.index') }}"
            role="search"
        >
            <label class="tools-header__search-field">
                <span class="sr-only" data-i18n="search.queryLabel">Search the hub</span>
                <i class="fa-solid fa-magnifying-glass tools-header__search-icon" aria-hidden="true"></i>
                <input
                    type="search"
                    name="q"
                    class="tools-header__search-input"
                    value="{{ \App\Support\Locale::routeIs('search.index') ? request('q') : '' }}"
                    autocomplete="off"
                    data-i18n-placeholder="search.headerPlaceholder"
                    placeholder="Search hub…"
                />
            </label>
        </form>
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
                <label class="tools-header__settings-option" role="menuitemcheckbox">
                    <input type="checkbox" data-shell-sidebar-toggle />
                    <span data-i18n="settings.hideNavigation">Hide navigation</span>
                </label>
                <label class="tools-header__settings-option" role="menuitemcheckbox">
                    <input type="checkbox" data-shell-hide-hub-leads-toggle />
                    <span data-i18n="settings.hideHubLeads">Hide infos</span>
                </label>
                <label
                    class="tools-header__settings-option"
                    role="menuitemcheckbox"
                    data-playbook-focus-setting
                    hidden
                >
                    <input type="checkbox" data-playbook-focus-toggle />
                    <span data-i18n="settings.hideSidebars">Hide sidebars</span>
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

        @if (! empty($accountsEnabled))
            <div class="tools-header__account" data-header-account>
                @if (! empty($accountUser))
                    <button
                        type="button"
                        class="tools-header__avatar-toggle"
                        data-header-account-toggle
                        aria-haspopup="menu"
                        aria-expanded="false"
                        aria-controls="tools-header-account-menu"
                        title="{{ $avatarLabel }}"
                    >
                        <span
                            class="tools-header__avatar {{ $avatarIcon !== '' ? 'tools-header__avatar--icon' : '' }} {{ $avatarBordered ? 'tools-header__avatar--bordered' : '' }}"
                            style="
                                --tools-avatar-bg: {{ $avatarBordered ? '#fff' : $avatarBg }};
                                --tools-avatar-fg: {{ $avatarBordered ? $avatarBg : '#fff' }};
                                --tools-avatar-border: 2px {{ $avatarBorderStyle }} {{ $avatarBg }};
                            "
                            aria-hidden="true"
                        >
                            @if ($avatarIcon !== '')
                                <span
                                    class="tools-header__avatar-icon"
                                    style="--tools-avatar-mask: url('{{ asset('icons/avatar/'.$avatarIcon.'.svg') }}')"
                                ></span>
                            @else
                                {{ $avatarInitials }}
                            @endif
                        </span>
                        <span class="sr-only">{{ $avatarLabel }}</span>
                    </button>
                    <div
                        id="tools-header-account-menu"
                        class="tools-header__account-menu"
                        data-header-account-menu
                        role="menu"
                        hidden
                    >
                        <div class="tools-header__account-menu-label" role="presentation">
                            {{ $avatarLabel }}
                        </div>
                        <a
                            href="{{ locale_route('accounts.profile') }}"
                            class="tools-header__account-menu-item {{ Locale::routeIs('accounts.profile') ? 'tools-header__account-menu-item--active' : '' }}"
                            role="menuitem"
                            data-i18n="nav.accountProfile"
                        >Profile</a>
                        <a
                            href="{{ locale_route('governance.sessions.index') }}"
                            class="tools-header__account-menu-item {{ Locale::routeIs('governance.sessions.index') || Locale::routeIs('governance.sessions.report') ? 'tools-header__account-menu-item--active' : '' }}"
                            role="menuitem"
                            data-text-de="Governance Sessions"
                            data-text-en="Governance Sessions"
                        >Governance Sessions</a>
                        @if (! empty($accountUser['canManageUsers']))
                            <a
                                href="{{ locale_route('accounts.users') }}"
                                class="tools-header__account-menu-item {{ Locale::routeIs('accounts.users') ? 'tools-header__account-menu-item--active' : '' }}"
                                role="menuitem"
                                data-i18n="nav.accountUsers"
                            >Users</a>
                            <a
                                href="{{ locale_route('accounts.story-acl') }}"
                                class="tools-header__account-menu-item {{ Locale::routeIs('accounts.story-acl') ? 'tools-header__account-menu-item--active' : '' }}"
                                role="menuitem"
                                data-i18n="nav.accountStoryAccess"
                            >Story access</a>
                        @endif
                        @if (! empty($accountUser['canManageTeams']))
                            <a
                                href="{{ locale_route('accounts.teams') }}"
                                class="tools-header__account-menu-item {{ Locale::routeIs('accounts.teams') ? 'tools-header__account-menu-item--active' : '' }}"
                                role="menuitem"
                                data-i18n="nav.accountTeams"
                            >Teams</a>
                        @endif
                        <form method="post" action="{{ locale_route('accounts.logout') }}" class="tools-header__account-menu-form">
                            @csrf
                            <button
                                type="submit"
                                class="tools-header__account-menu-item tools-header__account-menu-item--danger"
                                role="menuitem"
                                data-i18n="accounts.signOut"
                            >Sign out</button>
                        </form>
                    </div>
                @else
                    <a href="{{ locale_route('accounts.login') }}" class="tools-btn tools-btn--ghost" data-i18n="accounts.signIn">Sign in</a>
                @endif
            </div>
        @endif
    </div>
</div>
