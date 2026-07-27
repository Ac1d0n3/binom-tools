@extends('layouts.tools', [
    'viteEntries' => [
        'resources/js/calendar/calendar-public.js',
    ],
    'mainClass' => 'tools-shell__main--calendar',
])

@section('title', __('calendar.index.title') . ' — ' . config('app.name'))

@section('content')
    @php
        $sidebar = $sidebar ?? ['enabled' => true, 'sections' => []];
        $sidebarEnabled = $sidebar['enabled'] ?? true;
        $view = $view ?? 'month';
        $allowed_views = $allowed_views ?? ['month', 'week', 'day', 'list'];
        $anchor = $anchor ?? now();
    @endphp

    <div class="tools-content tools-content--wide tools-content--calendar">
        <h1 class="sr-only">{{ __('calendar.index.title') }}</h1>

        <div
            class="calendar-layout calendar-layout--has-sidebar calendar-layout--sidebar-right"
            data-calendar-root
            data-calendar-view="{{ $view }}"
            data-empty-label="{{ __('calendar.index.empty') }}"
            data-module-sidebar-layout="calendar"
            data-sidebar-panel-collapsible="true"
            data-sidebar-mobile-open-label="{{ __('calendar.sidebar.panel_show') }}"
            data-sidebar-mobile-close-label="{{ __('calendar.sidebar.panel_hide') }}"
        >
            <div class="calendar-layout__main">
                <div class="calendar-page">
                    <div class="calendar-card">
                        <div class="calendar-card__chrome">
                            @include('calendar.partials.toolbar', ['anchor' => $anchor])
                            <nav class="calendar-view-switcher" data-calendar-view-switcher aria-label="{{ __('calendar.views.label') }}">
                                @php
                                    $viewIcons = [
                                        'month' => 'fa-calendar-days',
                                        'week' => 'fa-calendar-week',
                                        'day' => 'fa-calendar-day',
                                        'list' => 'fa-list',
                                    ];
                                @endphp
                                @foreach ($allowed_views as $viewKey)
                                    <button
                                        type="button"
                                        data-calendar-view="{{ $viewKey }}"
                                        class="calendar-view-switcher__btn calendar-view-switcher__btn--icon @if ($view === $viewKey) is-active @endif"
                                        title="{{ __('calendar.views.'.$viewKey) }}"
                                        aria-label="{{ __('calendar.views.'.$viewKey) }}"
                                        aria-pressed="{{ $view === $viewKey ? 'true' : 'false' }}"
                                    >
                                        <i class="fa-solid {{ $viewIcons[$viewKey] ?? 'fa-calendar' }}" aria-hidden="true"></i>
                                    </button>
                                @endforeach
                            </nav>
                        </div>
                        <div class="calendar-card__viewport" data-calendar-viewport>
                            <div class="calendar-month" aria-busy="true"></div>
                        </div>
                    </div>
                </div>
            </div>

            @if ($sidebarEnabled)
                <aside class="calendar-layout__sidebar" data-calendar-sidebar>
                    @include('calendar.partials.sidebar', ['sidebar' => $sidebar])
                </aside>
                <button
                    type="button"
                    class="module-sidebar-toggle"
                    data-module-sidebar-toggle
                    aria-expanded="true"
                    aria-label="{{ __('calendar.sidebar.panel_hide') }}"
                    data-label-expand="{{ __('calendar.sidebar.panel_show') }}"
                    data-label-collapse="{{ __('calendar.sidebar.panel_hide') }}"
                >
                    <span class="module-sidebar-toggle__icon" aria-hidden="true">‹</span>
                </button>
            @endif
        </div>

        @if (! empty($bootstrap))
            <script type="application/json" id="calendar-bootstrap">@json($bootstrap)</script>
        @endif
    </div>
@endsection
