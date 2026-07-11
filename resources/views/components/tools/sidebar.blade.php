@php
    $navItems = \App\Support\ToolsNav::withRegisteredRoutes(config('tools.nav', []));
    $currentRoute = request()->route()?->getName();
    $currentSlug = request()->route('slug');
    $sidebarPlaybooks = app(\App\Playbooks\PlaybookRepository::class)->allForIndex();
@endphp

<nav class="tools-sidenav">
    <ul class="tools-sidenav__list tools-sidenav__list--home">
        <li>
            <a
                href="{{ route('tools.landing') }}"
                class="tools-sidenav__link {{ $currentRoute === 'tools.landing' ? 'tools-sidenav__link--active' : '' }}"
                data-i18n="nav.home"
            >
                Startseite
            </a>
        </li>
    </ul>

    <p class="tools-sidenav__section" data-i18n="nav.playbooks">Playbooks</p>
    <ul class="tools-sidenav__list">
        <li>
            <a
                href="{{ route('playbooks.index') }}"
                class="tools-sidenav__link {{ $currentRoute === 'playbooks.index' ? 'tools-sidenav__link--active' : '' }}"
                data-i18n="nav.playbooksOverview"
            >
                Overview
            </a>
        </li>
        @foreach ($sidebarPlaybooks as $item)
            @php
                $de = $item['locales']['de'] ?? null;
                $en = $item['locales']['en'] ?? null;
            @endphp
            <li>
                <a
                    href="{{ route('playbooks.show', $item['slug']) }}"
                    class="tools-sidenav__link {{ $currentRoute === 'playbooks.show' && $currentSlug === $item['slug'] ? 'tools-sidenav__link--active' : '' }}"
                    data-playbook-nav-title
                    data-text-de="{{ $de['title'] ?? '' }}"
                    data-text-en="{{ $en['title'] ?? ($de['title'] ?? '') }}"
                >
                    {{ $de['title'] ?? $en['title'] ?? $item['slug'] }}
                </a>
            </li>
        @endforeach
    </ul>

    <p class="tools-sidenav__section" data-i18n="nav.tools">Tools</p>
    <ul class="tools-sidenav__list">
        <li>
            <a
                href="{{ route('tools.overview') }}"
                class="tools-sidenav__link {{ $currentRoute === 'tools.overview' ? 'tools-sidenav__link--active' : '' }}"
                data-i18n="nav.overview"
            >
                Overview
            </a>
        </li>
        @foreach ($navItems as $item)
            <li>
                <a
                    href="{{ route($item['route']) }}"
                    class="tools-sidenav__link {{ $currentRoute === $item['route'] ? 'tools-sidenav__link--active' : '' }}"
                    data-i18n-nav="{{ $item['id'] }}"
                >
                    {{ $item['label']['en'] }}
                </a>
            </li>
        @endforeach
    </ul>
</nav>
