@php
    $anchor = $section['anchor'] ?? now();
    if (! $anchor instanceof \Carbon\CarbonInterface) {
        $anchor = \Carbon\Carbon::parse($anchor);
    }
    $startOfMonth = $anchor->copy()->startOfMonth();
    $daysInMonth = $startOfMonth->daysInMonth;
    $weekStart = ($section['week_start'] ?? 'monday') === 'sunday' ? 'sunday' : 'monday';
    $leadingPads = $weekStart === 'sunday'
        ? $startOfMonth->dayOfWeek
        : $startOfMonth->dayOfWeekIso - 1;
    $today = now()->toDateString();
    $weekdays = $section['weekdays'] ?? [];
@endphp
<section class="calendar-sidebar__section calendar-sidebar__mini-month" data-calendar-mini-month>
    <div class="calendar-mini-month__toolbar">
        <button type="button" class="calendar-mini-month__nav" data-calendar-mini-prev aria-label="{{ __('calendar.navigation.prev_month') }}">
            <i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
        </button>
        <span class="calendar-mini-month__label" data-calendar-mini-label>{{ $startOfMonth->locale(app()->getLocale())->translatedFormat('F Y') }}</span>
        <button type="button" class="calendar-mini-month__nav" data-calendar-mini-next aria-label="{{ __('calendar.navigation.next_month') }}">
            <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
        </button>
    </div>
    <div class="calendar-mini-month" role="grid" aria-label="{{ __('calendar.sidebar.mini_month') }}" data-calendar-mini-grid>
        @foreach ($weekdays as $weekday)
            <span class="calendar-mini-month__weekday" role="columnheader">{{ $weekday }}</span>
        @endforeach
        @for ($i = 0; $i < $leadingPads; $i++)
            <span class="calendar-mini-month__pad" aria-hidden="true"></span>
        @endfor
        @for ($d = 1; $d <= $daysInMonth; $d++)
            @php
                $date = $startOfMonth->copy()->day($d)->toDateString();
                $isToday = $date === $today;
            @endphp
            <button
                type="button"
                class="calendar-mini-month__day{{ $isToday ? ' calendar-mini-month__day--today' : '' }}"
                data-calendar-date="{{ $date }}"
                role="gridcell"
            ><span class="calendar-mini-month__num">{{ $d }}</span></button>
        @endfor
    </div>
</section>
