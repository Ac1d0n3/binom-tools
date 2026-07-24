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

    $categoryKey = playbook_category_key(
        is_string($categoryEn) ? $categoryEn : null,
        is_string($categoryDe) ? $categoryDe : null,
    );

    $tags = $item['tags'] ?? [];
    $products = $item['products'] ?? [];
    $heroUrl = $item['heroUrl'] ?? null;
    $views = max(0, (int) ($item['stats']['views'] ?? 0));
    $likes = max(0, (int) ($item['stats']['likes'] ?? 0));
    $productLabels = collect($products)
        ->map(fn (string $id): string => \App\Playbooks\PlaybookProducts::label($id))
        ->implode(' ');
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
        $productLabels,
        implode(' ', $products),
    ])));
    $titleId = 'playbook-card-title-'.$item['slug'];
@endphp

<article
    @class([
        'tools-card',
        'tools-card--story',
        'tools-card--story-has-hero' => filled($heroUrl),
        'tools-card--story-in-series' => $inSeries,
    ])
    data-playbook-index-card
    data-overview-item
    data-playbook-slug="{{ $item['slug'] }}"
    data-stats-show-url="{{ locale_route('playbooks.stats.show', ['slug' => $item['slug']]) }}"
    data-stats-like-url="{{ locale_route('playbooks.stats.like', ['slug' => $item['slug']]) }}"
    data-card-id="playbook-{{ $item['slug'] }}"
    data-search-text="{{ $searchText }}"
    data-sort-date="{{ $item['indexSortTimestamp'] ?? $item['sortDate']->getTimestamp() }}"
    data-sort-title-de="{{ $titleDe }}"
    data-sort-title-en="{{ $titleEn }}"
    data-sort-series-id="{{ $seriesId ?? '' }}"
    data-sort-series-part="{{ $seriesPart ?? 0 }}"
    @if ($categoryKey) data-category-key="{{ $categoryKey }}" @endif
    @if (count($tags) > 0) data-tags="{{ implode(',', $tags) }}" @endif
    @if (count($products) > 0) data-products="{{ implode(',', $products) }}" @endif
>
    <a
        href="{{ locale_route('playbooks.show', ['slug' => $item['slug']]) }}"
        class="tools-card__story-link"
        aria-labelledby="{{ $titleId }}"
    >
        <div class="tools-card__media">
            @if ($heroUrl)
                <div class="tools-card__hero">
                    <x-playbooks.responsive-image
                        :src="$heroUrl"
                        alt=""
                        class="tools-card__hero-image"
                        loading="lazy"
                        decoding="async"
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

            <x-playbooks.product-marks :products="$products" />
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
                id="{{ $titleId }}"
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
    </a>

    <div class="tools-card__story-footer">
        <span class="tools-card__story-meta-row">
            <span class="tools-card__story-stats">
                <span class="tools-card__story-stat" title="Views">
                    <i class="fa-solid fa-eye" aria-hidden="true"></i>
                    <span data-playbook-card-views>{{ number_format($views) }}</span>
                </span>
                <button
                    type="button"
                    class="tools-card__story-stat tools-card__story-like"
                    data-playbook-card-like
                    aria-pressed="false"
                    data-i18n-aria="playbooks.like"
                    aria-label="Like"
                    title="Like"
                >
                    <i class="fa-regular fa-heart" aria-hidden="true" data-like-icon></i>
                    <span data-playbook-card-likes>{{ number_format($likes) }}</span>
                </button>
            </span>

            <span class="tools-card__offline-actions">
                <span
                    class="tools-card__offline-badge"
                    data-playbook-offline-badge
                    hidden
                    data-i18n="playbooks.offline.badge"
                >Offline</span>
                <button
                    type="button"
                    class="tools-card__offline-btn"
                    data-playbook-card-offline
                    data-i18n-aria="playbooks.offline.save"
                    aria-label="Save offline"
                    title="Save offline"
                >
                    <i class="fa-solid fa-download" data-offline-icon="save" aria-hidden="true"></i>
                    <i class="fa-solid fa-trash-can" data-offline-icon="remove" hidden aria-hidden="true"></i>
                </button>
            </span>
        </span>

        <a
            href="{{ locale_route('playbooks.show', ['slug' => $item['slug']]) }}"
            class="tools-card__arrow-link"
            aria-label="{{ $titleEn }}"
            data-i18n-aria="playbooks.openStory"
        >
            <i class="fa-solid fa-arrow-right tools-card__arrow" aria-hidden="true"></i>
        </a>
    </div>
</article>
