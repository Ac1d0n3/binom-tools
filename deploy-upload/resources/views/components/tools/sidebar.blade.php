@php
    $navItems = \App\Support\ToolsNav::withRegisteredRoutes(config('tools.nav', []));
    $workflows = \App\Support\ToolsNav::workflowsWithRegisteredRoutes(config('tools.workflows', []));
    $navById = collect($navItems)->keyBy('id');
    $currentRoute = request()->route()?->getName();
    $currentSlug = request()->route('slug');
    $sidebarPlaybooks = app(\App\Playbooks\PlaybookRepository::class)->allForIndex();

    $workflowStepIds = [];
    foreach ($workflows as $workflow) {
        foreach ($workflow['steps'] ?? [] as $stepId) {
            $workflowStepIds[] = $stepId;
        }
    }

    $standaloneItems = array_values(array_filter(
        $navItems,
        static fn (array $item): bool => ! in_array($item['id'], $workflowStepIds, true),
    ));

    /** @var list<array<string, mixed>> */
    $orderedToolLinks = [];

    foreach ($workflows as $workflow) {
        foreach ($workflow['steps'] as $stepId) {
            $step = $navById->get($stepId);
            if ($step) {
                $orderedToolLinks[] = $step;
            }
        }
    }

    foreach ($standaloneItems as $item) {
        $orderedToolLinks[] = $item;
    }
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

    <div class="tools-sidenav__group">
        <p class="tools-sidenav__section" data-i18n="nav.playbooks">Playbooks</p>
        <ul class="tools-sidenav__list">
            <li>
                <a
                    href="{{ route('playbooks.index') }}"
                    class="tools-sidenav__link tools-sidenav__link--overview {{ $currentRoute === 'playbooks.index' ? 'tools-sidenav__link--active' : '' }}"
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
    </div>

    <div class="tools-sidenav__group">
        <p class="tools-sidenav__section" data-i18n="nav.tools">Tools</p>
        <ul class="tools-sidenav__list">
            <li>
                <a
                    href="{{ route('tools.overview') }}"
                    class="tools-sidenav__link tools-sidenav__link--overview {{ $currentRoute === 'tools.overview' ? 'tools-sidenav__link--active' : '' }}"
                    data-i18n="nav.overview"
                >
                    Overview
                </a>
            </li>

            @foreach ($orderedToolLinks as $item)
                @php
                    $icon = $item['icon'] ?? null;
                @endphp
                <li>
                    <a
                        href="{{ route($item['route']) }}"
                        class="tools-sidenav__link tools-sidenav__link--tool {{ $currentRoute === $item['route'] ? 'tools-sidenav__link--active' : '' }}"
                    >
                        @if ($icon)
                            <i class="fa-solid {{ $icon }} tools-sidenav__link-icon" aria-hidden="true"></i>
                        @endif
                        <span class="tools-sidenav__link-label" data-i18n-nav="{{ $item['id'] }}">{{ $item['navLabel']['en'] ?? $item['label']['en'] }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</nav>
