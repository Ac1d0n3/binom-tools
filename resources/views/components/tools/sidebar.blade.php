@php
    use App\Support\Locale;

    $navItems = \App\Support\ToolsNav::withRegisteredRoutes(config('tools.nav', []));
    $workflows = \App\Support\ToolsNav::workflowsWithRegisteredRoutes(config('tools.workflows', []));
    $navById = collect($navItems)->keyBy('id');
    $currentSlug = request()->route('slug');
    $playbookRepository = app(\App\Playbooks\PlaybookRepository::class);
    $sidebarPlaybooks = $playbookRepository->latestForIndex(
        \App\Playbooks\PlaybookRepository::SIDEBAR_INDEX_LIMIT,
        is_string($currentSlug) ? $currentSlug : null,
    );
    $totalStoryCount = count($playbookRepository->allForIndex());
    $remainingStoryCount = max(0, $totalStoryCount - count($sidebarPlaybooks));

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
                href="{{ locale_route('tools.landing') }}"
                class="tools-sidenav__link {{ Locale::routeIs('tools.landing') ? 'tools-sidenav__link--active' : '' }}"
                data-i18n="nav.home"
            >
                Startseite
            </a>
        </li>
    </ul>

    <div class="tools-sidenav__group">
        <p class="tools-sidenav__section" data-i18n="nav.stories">Stories</p>
        <ul class="tools-sidenav__list">
            <li>
                <a
                    href="{{ locale_route('playbooks.index') }}"
                    class="tools-sidenav__link tools-sidenav__link--overview {{ Locale::routeIs('playbooks.index') ? 'tools-sidenav__link--active' : '' }}"
                    data-i18n="nav.storiesOverview"
                >
                    Overview
                </a>
            </li>
            @foreach ($sidebarPlaybooks as $item)
                @php
                    $de = $item['locales']['de'] ?? null;
                    $en = $item['locales']['en'] ?? null;
                    $titleEn = $en['title'] ?? ($de['title'] ?? $item['slug']);
                @endphp
                <li>
                    <a
                        href="{{ locale_route('playbooks.show', ['slug' => $item['slug']]) }}"
                        class="tools-sidenav__link {{ Locale::routeIs('playbooks.show') && $currentSlug === $item['slug'] ? 'tools-sidenav__link--active' : '' }}"
                        data-playbook-nav-title
                        data-text-de="{{ $de['title'] ?? '' }}"
                        data-text-en="{{ $titleEn }}"
                    >
                        {{ $titleEn }}
                    </a>
                </li>
            @endforeach
            @if ($remainingStoryCount > 0)
                <li class="tools-sidenav__more">
                    <a
                        href="{{ locale_route('playbooks.index') }}"
                        class="tools-sidenav__link tools-sidenav__link--more {{ Locale::routeIs('playbooks.index') ? 'tools-sidenav__link--active' : '' }}"
                        data-i18n="nav.storiesMore"
                        data-i18n-count="{{ $remainingStoryCount }}"
                    >
                        + {{ $remainingStoryCount }} more stories
                    </a>
                </li>
            @endif
        </ul>
    </div>

    <div class="tools-sidenav__group">
        <p class="tools-sidenav__section" data-i18n="nav.tools">Tools</p>
        <ul class="tools-sidenav__list">
            <li>
                <a
                    href="{{ locale_route('tools.overview') }}"
                    class="tools-sidenav__link tools-sidenav__link--overview {{ Locale::routeIs('tools.overview') ? 'tools-sidenav__link--active' : '' }}"
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
                        href="{{ locale_route($item['route']) }}"
                        class="tools-sidenav__link tools-sidenav__link--tool {{ Locale::routeIs($item['route']) ? 'tools-sidenav__link--active' : '' }}"
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
