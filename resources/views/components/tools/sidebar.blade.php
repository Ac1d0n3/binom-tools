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
    $allIndex = $playbookRepository->allForIndex();

    if (! empty($accountsEnabled)) {
        $acl = app(\App\Accounts\StoryAclRepository::class);
        $account = ! empty($accountUser)
            ? app(\App\Accounts\AccountAuth::class)->user()
            : null;
        $sidebarPlaybooks = array_values(array_filter(
            $sidebarPlaybooks,
            static fn (array $item): bool => $acl->canAccess($account, (string) ($item['slug'] ?? '')),
        ));
        $allIndex = array_values(array_filter(
            $allIndex,
            static fn (array $item): bool => $acl->canAccess($account, (string) ($item['slug'] ?? '')),
        ));
    }

    $totalStoryCount = count($allIndex);
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
        <p class="tools-sidenav__section" data-i18n="nav.sprintPlanner">Sprint Planner</p>
        <ul class="tools-sidenav__list">
            <li>
                <a
                    href="{{ locale_route('sprint-planner.index') }}"
                    class="tools-sidenav__link tools-sidenav__link--overview {{ Locale::routeIs('sprint-planner.index') ? 'tools-sidenav__link--active' : '' }}"
                    data-i18n="nav.sprintPlannerPlans"
                >
                    My plans
                </a>
            </li>
            <li>
                <a
                    href="{{ locale_route('sprint-planner.templates') }}"
                    class="tools-sidenav__link {{ Locale::routeIs('sprint-planner.templates') ? 'tools-sidenav__link--active' : '' }}"
                    data-i18n="nav.sprintPlannerTemplates"
                >
                    Templates
                </a>
            </li>
            @php
                $showPeopleNav = empty($accountsEnabled)
                    || (
                        ! empty($accountUser)
                        && (
                            ! empty($accountUser['canManageUsers'])
                            || ! empty($accountUser['canManageTeams'])
                        )
                    );
            @endphp
            @if ($showPeopleNav)
                <li>
                    <a
                        href="{{ locale_route('sprint-planner.people') }}"
                        class="tools-sidenav__link {{ Locale::routeIs('sprint-planner.people') ? 'tools-sidenav__link--active' : '' }}"
                        data-i18n="nav.sprintPlannerPeople"
                    >
                        Teams &amp; people
                    </a>
                </li>
            @endif
        </ul>
    </div>

    @if (! empty($accountsEnabled))
        <div class="tools-sidenav__group">
            <p class="tools-sidenav__section" data-i18n="nav.account">Account</p>
            <ul class="tools-sidenav__list">
                @if (! empty($accountUser))
                    <li>
                        <a
                            href="{{ locale_route('accounts.profile') }}"
                            class="tools-sidenav__link {{ Locale::routeIs('accounts.profile') ? 'tools-sidenav__link--active' : '' }}"
                            data-i18n="nav.accountProfile"
                        >
                            Profile
                        </a>
                    </li>
                    @if (! empty($accountUser['canManageUsers']))
                        <li>
                            <a
                                href="{{ locale_route('accounts.users') }}"
                                class="tools-sidenav__link {{ Locale::routeIs('accounts.users') ? 'tools-sidenav__link--active' : '' }}"
                                data-i18n="nav.accountUsers"
                            >
                                Users
                            </a>
                        </li>
                        <li>
                            <a
                                href="{{ locale_route('accounts.story-acl') }}"
                                class="tools-sidenav__link {{ Locale::routeIs('accounts.story-acl') ? 'tools-sidenav__link--active' : '' }}"
                                data-i18n="nav.accountStoryAccess"
                            >
                                Story access
                            </a>
                        </li>
                    @endif
                    @if (! empty($accountUser['canManageTeams']))
                        <li>
                            <a
                                href="{{ locale_route('accounts.teams') }}"
                                class="tools-sidenav__link {{ Locale::routeIs('accounts.teams') ? 'tools-sidenav__link--active' : '' }}"
                                data-i18n="nav.accountTeams"
                            >
                                Teams
                            </a>
                        </li>
                    @endif
                @else
                    <li>
                        <a
                            href="{{ locale_route('accounts.login') }}"
                            class="tools-sidenav__link {{ Locale::routeIs('accounts.login') ? 'tools-sidenav__link--active' : '' }}"
                            data-i18n="nav.accountSignIn"
                        >
                            Sign in
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    @endif

    <div class="tools-sidenav__group">
        <p class="tools-sidenav__section" data-i18n="nav.tools">Governance</p>
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
