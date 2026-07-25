@php
    $yearFrom = $anchor->year - 5;
    $yearTo = $anchor->year + 5;
@endphp
<nav class="calendar-toolbar" data-calendar-toolbar aria-label="{{ __('calendar.navigation.label') }}">
    <div class="calendar-toolbar__nav">
        <button type="button" class="calendar-toolbar__btn" data-calendar-nav="prev" rel="prev">{{ __('calendar.navigation.prev_month') }}</button>
        <button type="button" class="calendar-toolbar__btn calendar-toolbar__btn--today" data-calendar-nav="today">{{ __('calendar.navigation.today') }}</button>
        <button type="button" class="calendar-toolbar__btn" data-calendar-nav="next" rel="next">{{ __('calendar.navigation.next_month') }}</button>
    </div>

    <div class="calendar-toolbar__period" data-calendar-period-form>
        <label class="calendar-toolbar__field">
            <span class="calendar-toolbar__field-label">{{ __('calendar.navigation.month') }}</span>
            <select name="month" class="binom-select calendar-toolbar__select" data-calendar-month>
                @for ($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" @selected($anchor->month === $m)>
                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                    </option>
                @endfor
            </select>
        </label>

        <label class="calendar-toolbar__field">
            <span class="calendar-toolbar__field-label">{{ __('calendar.navigation.year') }}</span>
            <select name="year" class="binom-select calendar-toolbar__select" data-calendar-year>
                @for ($y = $yearFrom; $y <= $yearTo; $y++)
                    <option value="{{ $y }}" @selected($anchor->year === $y)>{{ $y }}</option>
                @endfor
            </select>
        </label>
    </div>
</nav>
