@props([
    'active' => 'plans',
])

@php
    use App\Support\Locale;

    $showPeopleNav = empty($accountsEnabled)
        || (
            ! empty($accountUser)
            && (
                ! empty($accountUser['canManageUsers'])
                || ! empty($accountUser['canManageTeams'])
            )
        );
@endphp

<nav class="sp-subnav" aria-label="Sprint Planner">
    <a
        href="{{ locale_route('sprint-planner.index') }}"
        class="sp-subnav__link {{ $active === 'plans' ? 'sp-subnav__link--active' : '' }}"
        data-i18n="sp.nav.plans"
    >My plans</a>
    <a
        href="{{ locale_route('sprint-planner.templates') }}"
        class="sp-subnav__link {{ $active === 'templates' ? 'sp-subnav__link--active' : '' }}"
        data-i18n="sp.nav.templates"
    >Templates</a>
    @if ($showPeopleNav)
        <a
            href="{{ locale_route('sprint-planner.people') }}"
            class="sp-subnav__link {{ $active === 'people' ? 'sp-subnav__link--active' : '' }}"
            data-i18n="sp.nav.people"
        >Teams &amp; people</a>
    @endif
</nav>
