@php
    $navItems = config('tools.nav', []);
    $currentRoute = request()->route()?->getName();
@endphp

<nav class="tools-sidenav">
    <p class="tools-sidenav__section" data-i18n="nav.tools">Tools</p>
    <ul class="tools-sidenav__list">
        <li>
            <a
                href="{{ route('tools.home') }}"
                class="tools-sidenav__link {{ $currentRoute === 'tools.home' ? 'tools-sidenav__link--active' : '' }}"
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
