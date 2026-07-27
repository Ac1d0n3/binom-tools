@php
    $layerType = $layerType ?? 'calendar';
    $items = $items ?? [];
    $hideTitle = $hideTitle ?? false;
    $titleKey = $layerType === 'holiday' ? 'calendar.sidebar.holiday_layers' : 'calendar.sidebar.calendar_layers';
@endphp
<div class="calendar-sidebar__layers" data-calendar-layers="{{ $layerType }}">
    @unless ($hideTitle)
        <h2 class="calendar-sidebar__title">{{ __($titleKey) }}</h2>
    @endunless
    <fieldset class="calendar-layer-fieldset">
        <legend class="sr-only">{{ __($titleKey) }}</legend>
        <ul class="calendar-layer-list" role="list">
            @foreach ($items as $item)
                <li>
                    <label class="calendar-layer-toggle">
                        <input type="checkbox"
                               class="calendar-layer-toggle__input"
                               data-layer-type="{{ $layerType }}"
                               data-layer-id="{{ $item['id'] }}"
                               checked>
                        <span class="calendar-layer-toggle__swatch" style="--calendar-layer-color: {{ $item['color'] ?? '#94a3b8' }}"></span>
                        <span class="calendar-layer-toggle__label">{{ $item['title'] ?? $item['name'] }}</span>
                    </label>
                </li>
            @endforeach
        </ul>
    </fieldset>
</div>
