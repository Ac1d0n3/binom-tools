@props([
    'href',
    'title',
    'description',
    'titleKey' => null,
    'descriptionKey' => null,
    'meta' => null,
    'metaKey' => null,
    'count' => null,
    'countLabelKey' => null,
    'icon' => '🔧',
    'accent' => 'primary',
    'featured' => false,
    'hub' => false,
    'external' => false,
    'cardId' => null,
    'example' => false,
    'overviewItem' => false,
    'searchText' => null,
    'tags' => [],
    'products' => [],
    'dbtBadge' => false,
    'platformMarks' => [],
    'titleBadge' => null,
    'titleBadgeDe' => null,
    'titleBadgeEn' => null,
    'titleBadgeIcon' => null,
])

@php
    $platformMarkAssets = [
        'Fabric' => 'images/fabric-badge.svg',
        'Databricks' => 'images/databricks-badge.svg',
        'Qlik' => 'images/qlik-badge.svg',
        'AI' => 'images/ai-badge.svg',
    ];
    $hasCount = $count !== null && $count !== '';
    $badgeEn = trim((string) ($titleBadgeEn ?? $titleBadge ?? ''));
    $badgeDe = trim((string) ($titleBadgeDe ?? $badgeEn));
    $hasTitleBadge = $badgeEn !== '';
    $badgeIcon = is_string($titleBadgeIcon) ? trim($titleBadgeIcon) : '';
@endphp

<a
    href="{{ $href }}"
    @class([
        'tools-card',
        'tools-card--featured' => $featured,
        'tools-card--hub' => $hub,
        'tools-card--hub-primary' => $hub && $accent === 'primary',
        'tools-card--hub-accent' => $hub && $accent === 'accent',
        'tools-card--dbt' => $dbtBadge,
    ])
    @if ($cardId) data-card-id="{{ $cardId }}" @endif
    @if ($overviewItem) data-overview-item @endif
    @if ($searchText) data-search-text="{{ $searchText }}" @endif
    @if (count($tags) > 0) data-tags="{{ implode(',', $tags) }}" @endif
    @if (count($products) > 0) data-products="{{ implode(',', $products) }}" @endif
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
                <h3 class="tools-card__title" @if ($titleKey) data-i18n="{{ $titleKey }}" @endif>{{ $title }}</h3>
                @if ($example)
                    <span class="tools-card__badge" data-i18n="card.exampleBadge">Example</span>
                @endif
                @if ($hasTitleBadge)
                    <span class="tools-card__badge tools-card__badge--date">
                        @if ($badgeIcon !== '')
                            <i class="fa-solid {{ $badgeIcon }} tools-card__badge-icon" aria-hidden="true"></i>
                        @endif
                        <span
                            class="tools-card__badge-text"
                            @if ($badgeDe !== '' && $badgeEn !== '')
                                data-text-de="{{ $badgeDe }}"
                                data-text-en="{{ $badgeEn }}"
                            @endif
                        >{{ $badgeEn }}</span>
                    </span>
                @endif
            </div>
            @if ($hasCount)
                <p class="tools-card__meta tools-card__meta--kpi">
                    <span class="tools-card__count">{{ $count }}</span>
                    @if ($countLabelKey)
                        <span data-i18n="{{ $countLabelKey }}">items</span>
                    @endif
                </p>
            @elseif ($meta)
                <p class="tools-card__meta" @if ($metaKey) data-i18n="{{ $metaKey }}" @endif>{{ $meta }}</p>
            @endif
            <p class="tools-card__desc" @if ($descriptionKey) data-i18n="{{ $descriptionKey }}" @endif>{{ $description }}</p>
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
