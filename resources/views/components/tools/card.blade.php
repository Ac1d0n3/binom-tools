@props([
    'href',
    'title',
    'description',
    'meta' => null,
    'icon' => '🔧',
    'accent' => 'primary',
    'featured' => false,
    'external' => false,
    'cardId' => null,
    'example' => false,
    'overviewItem' => false,
    'searchText' => null,
    'tags' => [],
    'dbtBadge' => false,
    'platformMarks' => [],
])

@php
    $platformMarkAssets = [
        'Fabric' => 'images/fabric-badge.svg',
        'Databricks' => 'images/databricks-badge.svg',
    ];
@endphp

<a
    href="{{ $href }}"
    class="tools-card {{ $featured ? 'tools-card--featured' : '' }} {{ $dbtBadge ? 'tools-card--dbt' : '' }}"
    @if ($cardId) data-card-id="{{ $cardId }}" @endif
    @if ($overviewItem) data-overview-item @endif
    @if ($searchText) data-search-text="{{ $searchText }}" @endif
    @if (count($tags) > 0) data-tags="{{ implode(',', $tags) }}" @endif
    @if ($external) target="_blank" rel="noopener noreferrer" @endif
>
    <div class="tools-card__main">
        <div class="tools-card__icon-wrap tools-card__icon-wrap--{{ $accent }}" aria-hidden="true">
            @if (str_starts_with($icon, 'fa-'))
                <i class="fa-solid {{ $icon }} tools-card__icon"></i>
            @else
                <span class="tools-card__icon">{{ $icon }}</span>
            @endif
        </div>
        <div class="tools-card__body">
            <div class="tools-card__title-row">
                <h3 class="tools-card__title">{{ $title }}</h3>
                @if ($example)
                    <span class="tools-card__badge" data-i18n="card.exampleBadge">Example</span>
                @endif
            </div>
            @if ($meta)
                <p class="tools-card__meta">{{ $meta }}</p>
            @endif
            <p class="tools-card__desc">{{ $description }}</p>
        </div>
        @if ($external)
            <i class="fa-solid fa-arrow-up-right-from-square tools-card__arrow" aria-hidden="true"></i>
        @else
            <i class="fa-solid fa-arrow-right tools-card__arrow" aria-hidden="true"></i>
        @endif
    </div>
    @if ($dbtBadge || count($platformMarks) > 0)
        <div class="tools-card__purpose" aria-label="Tool target">
            @if (count($platformMarks) > 0)
                <span class="tools-card__platform-marks">
                    @foreach ($platformMarks as $mark)
                        @if (isset($platformMarkAssets[$mark]))
                            <img
                                src="{{ asset($platformMarkAssets[$mark]) }}"
                                alt=""
                                class="tools-card__platform-mark"
                                loading="lazy"
                                decoding="async"
                            />
                        @endif
                    @endforeach
                </span>
            @endif
            @if ($dbtBadge)
            <img
                src="{{ asset('images/dbt-badge.svg') }}"
                alt=""
                class="tools-card__dbt-badge"
                width="32"
                height="11"
                loading="lazy"
                decoding="async"
            />
            @endif
        </div>
    @endif
</a>
