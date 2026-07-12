@props(['item'])

@php
    $de = $item['locales']['de'] ?? null;
    $en = $item['locales']['en'] ?? null;
    $titleDe = $de['title'] ?? '';
    $titleEn = $en['title'] ?? $titleDe;
    $descDe = $de['description'] ?? '';
    $descEn = $en['description'] ?? $descDe;
    $categoryDe = $de['category'] ?? null;
    $categoryEn = $en['category'] ?? $categoryDe;
    $readingDe = ($de['readingTimeMinutes'] ?? null) ? ($de['readingTimeMinutes'] . ' min') : null;
    $readingEn = ($en['readingTimeMinutes'] ?? null) ? ($en['readingTimeMinutes'] . ' min') : null;
    $seriesId = $item['seriesId'] ?? null;
    $seriesPart = $item['seriesPart'] ?? null;
    $seriesTitleDe = $de['seriesTitle'] ?? null;
    $seriesTitleEn = $en['seriesTitle'] ?? $seriesTitleDe;
    $inSeries = filled($seriesId);

    $seriesBadgeDe = null;
    $seriesBadgeEn = null;

    if ($inSeries && is_numeric($seriesPart)) {
        $seriesLabelDe = $seriesTitleDe ?: 'Serie';
        $seriesLabelEn = $seriesTitleEn ?: 'Series';
        $seriesBadgeDe = "{$seriesLabelDe} · Teil {$seriesPart}";
        $seriesBadgeEn = "{$seriesLabelEn} · Part {$seriesPart}";
    }

    $metaDe = collect([$categoryDe, $readingDe])->filter()->implode(' · ');
    $metaEn = collect([$categoryEn, $readingEn])->filter()->implode(' · ');

    $tags = $item['tags'] ?? [];
    $heroUrl = $item['heroUrl'] ?? null;
    $searchText = strtolower(implode(' ', array_filter([
        $titleDe,
        $titleEn,
        $descDe,
        $descEn,
        $categoryDe ?? '',
        $categoryEn ?? '',
        $seriesBadgeDe ?? '',
        $seriesBadgeEn ?? '',
        $item['slug'] ?? '',
        implode(' ', $tags),
    ])));
@endphp

<a
    href="{{ locale_route('playbooks.show', ['slug' => $item['slug']]) }}"
    @class([
        'tools-card',
        'tools-card--story',
        'tools-card--story-has-hero' => filled($heroUrl),
        'tools-card--story-in-series' => $inSeries,
    ])
    data-playbook-index-card
    data-overview-item
    data-playbook-slug="{{ $item['slug'] }}"
    data-card-id="playbook-{{ $item['slug'] }}"
    data-search-text="{{ $searchText }}"
    data-sort-date="{{ $item['sortDate']->getTimestamp() }}"
    data-sort-title-de="{{ $titleDe }}"
    data-sort-title-en="{{ $titleEn }}"
    data-sort-series-id="{{ $seriesId ?? '' }}"
    data-sort-series-part="{{ $seriesPart ?? 0 }}"
    @if (count($tags) > 0) data-tags="{{ implode(',', $tags) }}" @endif
>
    <div class="tools-card__media">
        @if ($heroUrl)
            <div class="tools-card__hero">
                <img
                    src="{{ $heroUrl }}"
                    alt=""
                    class="tools-card__hero-image"
                    loading="lazy"
                />
            </div>
        @else
            <div class="tools-card__hero tools-card__hero--placeholder" aria-hidden="true">
                <div class="tools-card__icon-wrap tools-card__icon-wrap--primary">
                    <i class="fa-solid fa-book-open tools-card__icon"></i>
                </div>
            </div>
        @endif

        @if ($seriesBadgeDe !== null && $seriesBadgeEn !== null)
            <span
                class="tools-card__series-badge"
                data-playbook-card-series-badge
                data-text-de="{{ $seriesBadgeDe }}"
                data-text-en="{{ $seriesBadgeEn }}"
            >
                <i class="fa-solid fa-layer-group" aria-hidden="true"></i>
                <span>{{ $seriesBadgeEn }}</span>
            </span>
        @endif
    </div>

    <div class="tools-card__story-body">
        @if ($metaDe !== '' || $metaEn !== '')
            <p
                class="tools-card__meta tools-card__meta--story"
                data-playbook-card-meta
                data-text-de="{{ $metaDe }}"
                data-text-en="{{ $metaEn }}"
            >{{ $metaEn }}</p>
        @endif

        <h3
            class="tools-card__title tools-card__title--story"
            data-playbook-card-title
            data-text-de="{{ $titleDe }}"
            data-text-en="{{ $titleEn }}"
        >{{ $titleEn }}</h3>

        <p
            class="tools-card__desc tools-card__desc--story"
            data-playbook-card-description
            data-text-de="{{ $descDe }}"
            data-text-en="{{ $descEn }}"
        >{{ $descEn }}</p>
    </div>

    <span class="tools-card__story-footer" aria-hidden="true">
        <i class="fa-solid fa-arrow-right tools-card__arrow"></i>
    </span>
</a>
