<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    <script>
        (function () {
            var key = 'binom-tools-color-scheme';
            var stored = localStorage.getItem(key);
            var scheme = stored === 'light' || stored === 'dark'
                ? stored
                : (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            document.documentElement.dataset.colorScheme = scheme;
            var themeClass = scheme === 'dark' ? 'bn-theme-blue-water-dark' : 'bn-theme-blue-water-light';
            document.documentElement.classList.add(themeClass);
            document.body.classList.add(themeClass);
        })();
    </script>
    @vite(array_merge(['resources/css/app.css', 'resources/js/app.js'], $viteEntries ?? []))
    @stack('head')
</head>
<body>
    <div class="tools-shell" id="tools-shell">
        <header class="tools-shell__header">
            <x-tools.header />
        </header>

        <div class="tools-sidebar-backdrop" data-tools-sidebar-backdrop hidden></div>

        <div class="tools-shell__body">
            <aside class="tools-shell__sidebar" aria-label="Tools navigation">
                <x-tools.sidebar />
            </aside>

            <main class="tools-shell__main" id="tools-main-content" tabindex="-1">
                @yield('content')
            </main>
        </div>

        <footer class="tools-shell__footer">
            <x-tools.footer />
        </footer>
    </div>

    @stack('scripts')
</body>
</html>
