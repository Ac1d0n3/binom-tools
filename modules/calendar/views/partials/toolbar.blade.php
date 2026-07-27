@php
    $yearFrom = $anchor->year - 5;
    $yearTo = $anchor->year + 5;
@endphp
<nav class="calendar-toolbar" data-calendar-toolbar aria-label="{{ __('calendar.navigation.label') }}">
    <div class="calendar-toolbar__nav">
        <button
            type="button"
            class="calendar-toolbar__btn calendar-toolbar__btn--icon"
            data-calendar-nav="prev"
            rel="prev"
            title="{{ __('calendar.navigation.prev_month') }}"
            aria-label="{{ __('calendar.navigation.prev_month') }}"
        >
            <i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
        </button>
        <button type="button" class="calendar-toolbar__btn calendar-toolbar__btn--today" data-calendar-nav="today">
            {{ __('calendar.navigation.today') }}
        </button>
        <button
            type="button"
            class="calendar-toolbar__btn calendar-toolbar__btn--icon"
            data-calendar-nav="next"
            rel="next"
            title="{{ __('calendar.navigation.next_month') }}"
            aria-label="{{ __('calendar.navigation.next_month') }}"
        >
            <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
        </button>
    </div>

    <div class="calendar-toolbar__period" data-calendar-period-form>
        <label class="calendar-toolbar__field">
            <span class="calendar-toolbar__field-label">{{ __('calendar.navigation.month') }}</span>
            <select name="month" class="binom-select calendar-toolbar__select" data-calendar-month aria-label="{{ __('calendar.navigation.month') }}">
                @for ($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" @selected($anchor->month === $m)>
                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                    </option>
                @endfor
            </select>
        </label>

        <label class="calendar-toolbar__field">
            <span class="calendar-toolbar__field-label">{{ __('calendar.navigation.year') }}</span>
            <select name="year" class="binom-select calendar-toolbar__select calendar-toolbar__select--year" data-calendar-year aria-label="{{ __('calendar.navigation.year') }}">
                @for ($y = $yearFrom; $y <= $yearTo; $y++)
                    <option value="{{ $y }}" @selected($anchor->year === $y)>{{ $y }}</option>
                @endfor
            </select>
        </label>
    </div>
</nav>
