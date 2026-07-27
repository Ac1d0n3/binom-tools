<aside class="calendar-sidebar" aria-label="{{ __('calendar.sidebar.aria') }}">
    @foreach ($sidebar['sections'] ?? [] as $section)
        @switch($section['type'] ?? '')
            @case('mini_month')
                @include('calendar::partials.sidebar-mini-month', ['section' => $section])
                @break

            @case('filters')
                <details class="calendar-sidebar__section calendar-sidebar__fold" data-calendar-collapsible="filters" open>
                    <summary class="calendar-sidebar__fold-summary">
                        <h2 class="calendar-sidebar__title">{{ __('calendar.sidebar.filters') }}</h2>
                        <span class="calendar-sidebar__fold-arrow" aria-hidden="true"></span>
                    </summary>
                    <ul class="calendar-sidebar__filter-list">
                        <li>
                            <label class="calendar-layer-toggle">
                                <input type="checkbox" data-calendar-filter="my-tasks">
                                <span class="calendar-layer-toggle__label">{{ __('calendar.sidebar.my_tasks') }}</span>
                            </label>
                        </li>
                        <li>
                            <label class="calendar-layer-toggle">
                                <input type="checkbox" data-calendar-filter="hide-done" checked>
                                <span class="calendar-layer-toggle__label">{{ __('calendar.sidebar.hide_done') }}</span>
                            </label>
                        </li>
                    </ul>
                    @if (! empty($section['plans_login_hint']))
                        <p class="calendar-sidebar__hint">{{ $section['plans_login_hint'] }}</p>
                    @endif
                </details>
                @break

            @case('layers')
                <details class="calendar-sidebar__section calendar-sidebar__fold" data-calendar-collapsible="layers" open>
                    <summary class="calendar-sidebar__fold-summary">
                        <h2 class="calendar-sidebar__title">{{ __('calendar.sidebar.layers') }}</h2>
                        <span class="calendar-sidebar__fold-arrow" aria-hidden="true"></span>
                    </summary>
                    @if (! empty($section['holiday_sources']))
                        <div class="calendar-sidebar__layer-group">
                            <h3 class="calendar-sidebar__layer-group-title">{{ __('calendar.sidebar.holiday_layers') }}</h3>
                            @include('calendar::partials.sidebar-layers', [
                                'layerType' => 'holiday',
                                'items' => $section['holiday_sources'],
                                'hideTitle' => true,
                            ])
                        </div>
                    @endif
                    @if (! empty($section['calendars']))
                        <div class="calendar-sidebar__layer-group">
                            <h3 class="calendar-sidebar__layer-group-title">{{ __('calendar.sidebar.calendar_layers') }}</h3>
                            @include('calendar::partials.sidebar-layers', [
                                'layerType' => 'calendar',
                                'items' => $section['calendars'],
                                'hideTitle' => true,
                            ])
                        </div>
                    @endif
                </details>
                @break
        @endswitch
    @endforeach
</aside>
