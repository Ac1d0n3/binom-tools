@php
    use App\Support\Locale;

    $navItems = \App\Support\ToolsNav::withRegisteredRoutes(config('tools.nav', []));
    $currentSlug = request()->route('slug');
    $currentSeriesId = request()->route('seriesId');
    $playbookRepository = app(\App\Playbooks\PlaybookRepository::class);
    $sidebarCards = $playbookRepository->latestCatalogCards(
        \App\Playbooks\PlaybookRepository::SIDEBAR_INDEX_LIMIT,
        is_string($currentSlug) ? $currentSlug : null,
    );
    $allIndex = $playbookRepository->allForIndex();

    if (! empty($accountsEnabled)) {
        $acl = app(\App\Accounts\Contracts\StoryAclRepositoryInterface::class);
        $account = ! empty($accountUser)
            ? app(\App\Accounts\AccountAuth::class)->user()
            : null;
        $sidebarCards = array_values(array_filter(
            $sidebarCards,
            static function (array $card) use ($acl, $account): bool {
                if (($card['type'] ?? '') === 'series') {
                    $series = $card['series'] ?? null;
                    if (! $series instanceof \App\Playbooks\PlaybookSeriesOverview) {
                        return false;
                    }

                    foreach ($series->parts as $part) {
                        if ($acl->canAccess($account, $part->slug)) {
                            return true;
                        }
                    }

                    return false;
                }

                return $acl->canAccess($account, (string) (($card['item']['slug'] ?? '')));
            },
        ));
        $allIndex = array_values(array_filter(
            $allIndex,
            static fn (array $item): bool => $acl->canAccess($account, (string) ($item['slug'] ?? '')),
        ));
    }

    $activeSeriesId = is_string($currentSeriesId) && $currentSeriesId !== '' ? $currentSeriesId : null;
    if ($activeSeriesId === null && is_string($currentSlug) && $currentSlug !== '') {
        $currentPlaybook = $playbookRepository->find($currentSlug);
        if ($currentPlaybook !== null && is_string($currentPlaybook->seriesId) && $currentPlaybook->seriesId !== '') {
            $activeSeriesId = $currentPlaybook->seriesId;
        }
    }

    $totalStoryCount = count($allIndex);
    $remainingStoryCount = max(0, $totalStoryCount - count($sidebarCards));

    $toolGroups = \App\Support\ToolsNav::groupByProduct($navItems);
    $routeBase = Locale::routeBaseName(request()->route()?->getName());
    $governanceActive = str_starts_with((string) $routeBase, 'governance.') && $routeBase !== 'governance.radar';
    $resourcesActive = str_starts_with((string) $routeBase, 'resources.');
    $suppliersActive = str_starts_with((string) $routeBase, 'suppliers.');
    $complianceActive = str_starts_with((string) $routeBase, 'compliance.');
    $sprintPlannerActive = str_starts_with((string) $routeBase, 'sprint-planner.');
    $calendarActive = str_starts_with((string) $routeBase, 'calendar.');
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
            @foreach ($sidebarCards as $card)
                @if (($card['type'] ?? '') === 'series' && ($card['series'] ?? null) instanceof \App\Playbooks\PlaybookSeriesOverview)
                    @php
                        $series = $card['series'];
                        $seriesActive = $activeSeriesId === $series->id;
                    @endphp
                    <li>
                        <a
                            href="{{ locale_route('playbooks.series', ['seriesId' => $series->id]) }}"
                            class="tools-sidenav__link {{ $seriesActive ? 'tools-sidenav__link--active' : '' }}"
                            data-playbook-nav-title
                            data-text-de="{{ $series->titleDe }}"
                            data-text-en="{{ $series->titleEn }}"
                        >
                            {{ $series->titleEn }}
                        </a>
                    </li>
                @else
                    @php
                        $item = $card['item'] ?? [];
                        $de = $item['locales']['de'] ?? null;
                        $en = $item['locales']['en'] ?? null;
                        $titleEn = $en['title'] ?? ($de['title'] ?? ($item['slug'] ?? ''));
                        $itemSlug = (string) ($item['slug'] ?? '');
                    @endphp
                    <li>
                        <a
                            href="{{ locale_route('playbooks.show', ['slug' => $itemSlug]) }}"
                            class="tools-sidenav__link {{ Locale::routeIs('playbooks.show') && $currentSlug === $itemSlug ? 'tools-sidenav__link--active' : '' }}"
                            data-playbook-nav-title
                            data-text-de="{{ $de['title'] ?? '' }}"
                            data-text-en="{{ $titleEn }}"
                        >
                            {{ $titleEn }}
                        </a>
                    </li>
                @endif
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
        <p class="tools-sidenav__section" data-i18n="nav.hubs">Hubs</p>
        <ul class="tools-sidenav__list">
            <li>
                <a
                    href="{{ locale_route('governance.index') }}"
                    class="tools-sidenav__link {{ $governanceActive ? 'tools-sidenav__link--active' : '' }}"
                    data-text-de="Governance"
                    data-text-en="Governance"
                >
                    Governance
                </a>
            </li>
            <li>
                <a
                    href="{{ locale_route('governance.radar') }}"
                    class="tools-sidenav__link {{ Locale::routeIs('governance.radar') ? 'tools-sidenav__link--active' : '' }}"
                    data-text-de="Governance Radar"
                    data-text-en="Governance Radar"
                >
                    Governance Radar
                </a>
            </li>
            <li>
                <a
                    href="{{ locale_route('resources.index') }}"
                    class="tools-sidenav__link {{ $resourcesActive ? 'tools-sidenav__link--active' : '' }}"
                    data-i18n="nav.resources"
                >
                    Resources
                </a>
            </li>
            <li>
                <a
                    href="{{ locale_route('suppliers.index') }}"
                    class="tools-sidenav__link {{ $suppliersActive ? 'tools-sidenav__link--active' : '' }}"
                    data-i18n="nav.suppliers"
                >
                    Suppliers
                </a>
            </li>
            <li>
                <a
                    href="{{ locale_route('compliance.index') }}"
                    class="tools-sidenav__link {{ $complianceActive ? 'tools-sidenav__link--active' : '' }}"
                    data-i18n="nav.compliance"
                >
                    Compliance
                </a>
            </li>
            <li>
                <a
                    href="{{ locale_route('sprint-planner.index') }}"
                    class="tools-sidenav__link {{ $sprintPlannerActive ? 'tools-sidenav__link--active' : '' }}"
                    data-i18n="nav.sprintPlanner"
                >
                    Sprint Planner
                </a>
            </li>
            <li>
                <a
                    href="{{ locale_route('calendar.index') }}"
                    class="tools-sidenav__link {{ $calendarActive ? 'tools-sidenav__link--active' : '' }}"
                    data-i18n="nav.calendar"
                >
                    Calendar
                </a>
            </li>
        </ul>
    </div>

    <div class="tools-sidenav__group phone-hide-tools" data-phone-hide-tools>
        <p class="tools-sidenav__section" data-i18n="nav.tools">Binom-Tools</p>
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

            @foreach ($toolGroups as $group)
                @php
                    $groupOpen = false;
                    foreach ($group['items'] as $item) {
                        if (Locale::routeIs($item['route'])) {
                            $groupOpen = true;
                            break;
                        }
                    }
                    $labelEn = $group['label']['en'] ?? ($group['label']['de'] ?? $group['id']);
                    $labelDe = $group['label']['de'] ?? $labelEn;
                    $panelId = 'sidenav-tools-'.$group['id'];
                @endphp
                <li
                    class="tools-sidenav__accordion"
                    data-sidenav-accordion="{{ $group['id'] }}"
                >
                    <input
                        type="checkbox"
                        class="tools-sidenav__accordion-input"
                        id="{{ $panelId }}-toggle"
                        @if ($groupOpen) checked @endif
                    >
                    <label
                        class="tools-sidenav__accordion-summary"
                        for="{{ $panelId }}-toggle"
                    >
                        <span
                            class="tools-sidenav__accordion-label"
                            data-sidenav-bilingual
                            data-text-de="{{ $labelDe }}"
                            data-text-en="{{ $labelEn }}"
                        >{{ $labelEn }}</span>
                        <i class="fa-solid fa-chevron-down tools-sidenav__accordion-chevron" aria-hidden="true"></i>
                    </label>
                    <ul
                        id="{{ $panelId }}"
                        class="tools-sidenav__list tools-sidenav__list--nested"
                    >
                        @foreach ($group['items'] as $item)
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
                </li>
            @endforeach
        </ul>
    </div>
</nav>
