<!DOCTYPE html>
<html lang="{{ current_locale() }}" data-app-base="{{ app_base_path() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    @hasSection('meta_description')
        <meta name="description" content="@yield('meta_description')">
    @endif
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <script>
        (function () {
            var base = document.documentElement.dataset.appBase || '';
            var path = window.location.pathname;
            var relative = base && path.indexOf(base) === 0 ? path.slice(base.length) || '/' : path;
            var locale = relative === '/de' || relative.indexOf('/de/') === 0 ? 'de' : 'en';
            document.documentElement.lang = locale;

            var key = 'binom-tools-color-scheme';
            var stored = localStorage.getItem(key);
            var scheme = stored === 'light' || stored === 'dark'
                ? stored
                : (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            document.documentElement.dataset.colorScheme = scheme;
            var themeClass = scheme === 'dark' ? 'bn-theme-blue-water-dark' : 'bn-theme-blue-water-light';
            document.documentElement.classList.add(themeClass);
            document.addEventListener('DOMContentLoaded', function () {
                if (document.body) {
                    document.body.classList.add(themeClass);
                }
            });

            var fullWidthKey = 'binom-tools-shell-full-width';
            document.documentElement.dataset.shellFullWidth =
                localStorage.getItem(fullWidthKey) === 'true' ? 'true' : 'false';

            var playbookFocusKey = 'binom-tools-playbook-focus';
            var isPlaybookStory = /(?:^|\/)(?:de\/)?playbooks\/[a-z0-9-]+\/?$/.test(relative);
            document.documentElement.dataset.playbookFocus =
                isPlaybookStory && localStorage.getItem(playbookFocusKey) === 'true' ? 'true' : 'false';
        })();
    </script>
    @vite(array_merge(['resources/css/app.css', 'resources/js/app.js'], $viteEntries ?? []))
    @stack('head')
</head>
<body>
    <x-tools.app-art />

    <div class="tools-shell" id="tools-shell">
        <header class="tools-shell__header">
            <x-tools.header />
        </header>

        <div class="tools-sidebar-backdrop" data-tools-sidebar-backdrop hidden></div>

        <div class="tools-shell__body">
            <aside class="tools-shell__sidebar" aria-label="Governance navigation">
                <x-tools.sidebar />
            </aside>

            <main
                class="tools-shell__main {{ $mainClass ?? '' }}"
                id="tools-main-content"
                tabindex="-1"
            >
                @yield('content')
            </main>
        </div>

        <footer class="tools-shell__footer">
            <x-tools.footer />
        </footer>
    </div>

    <x-tools.cookie-banner />

    @stack('scripts')
</body>
</html>
