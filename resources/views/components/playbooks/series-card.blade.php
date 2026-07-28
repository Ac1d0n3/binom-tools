@props([
    'series',
])

@php
    $products = $series->products ?? [];
    $productLabels = collect($products)
        ->map(fn (string $id): string => \App\Playbooks\PlaybookProducts::label($id))
        ->all();
    $searchText = strtolower(implode(' ', array_filter([
        $series->titleDe,
        $series->titleEn,
        $series->descriptionDe,
        $series->descriptionEn,
        $series->id,
        ...collect($series->parts)->flatMap(fn ($part) => [$part->titleDe, $part->titleEn])->all(),
        ...$productLabels,
        ...$products,
    ])));
    $firstPart = $series->firstPart();
    $seriesHref = locale_route('playbooks.series', ['seriesId' => $series->id]);
    $hasSummary = $series->descriptionDe !== '' || $series->descriptionEn !== '';
@endphp

<article
    class="tools-series-card"
    data-overview-series-item
    data-search-text="{{ $searchText }}"
    data-sort-date="{{ $series->modifiedAt }}"
    data-sort-title-de="{{ $series->titleDe }}"
    data-sort-title-en="{{ $series->titleEn }}"
    data-sort-part-count="{{ $series->partCount() }}"
    @if (count($products) > 0) data-products="{{ implode(',', $products) }}" @endif
>
    @if ($series->heroUrl)
        <a href="{{ $seriesHref }}" class="tools-series-card__hero" tabindex="-1" aria-hidden="true">
            <x-playbooks.responsive-image
                :src="$series->heroUrl"
                alt=""
                class="tools-series-card__hero-image"
                loading="lazy"
                decoding="async"
            />
        </a>
    @else
        <a href="{{ $seriesHref }}" class="tools-series-card__hero tools-series-card__hero--placeholder" tabindex="-1" aria-hidden="true">
            <div class="tools-card__icon-wrap tools-card__icon-wrap--primary">
                <i class="fa-solid fa-layer-group tools-card__icon"></i>
            </div>
        </a>
    @endif

    <div class="tools-series-card__body">
        <div class="tools-series-card__main">
            <h3 class="tools-series-card__title">
                <a
                    href="{{ $seriesHref }}"
                    class="tools-series-card__title-link"
                    data-playbook-series-card-title
                    data-text-de="{{ $series->titleDe }}"
                    data-text-en="{{ $series->titleEn }}"
                >{{ $series->titleEn }}</a>
            </h3>

            @if ($hasSummary)
                <p
                    class="tools-series-card__summary"
                    data-playbook-series-card-summary
                    data-text-de="{{ $series->descriptionDe }}"
                    data-text-en="{{ $series->descriptionEn }}"
                >{{ $series->descriptionEn !== '' ? $series->descriptionEn : $series->descriptionDe }}</p>
            @endif

            <div class="tools-series-card__status">
                <p
                    class="tools-series-card__meta"
                    data-playbook-series-card-meta
                    data-part-count="{{ $series->partCount() }}"
                    data-reading-time-de="{{ $series->totalReadingTimeDe }}"
                    data-reading-time-en="{{ $series->totalReadingTimeEn }}"
                >
                    {{ $series->partCount() }} parts · {{ format_reading_time($series->totalReadingTimeEn, 'en') }} total
                </p>

                <ol
                    class="tools-series-card__progress"
                    aria-label="Series parts"
                    data-i18n-aria="overview.seriesPartsProgress"
                >
                    @foreach ($series->parts as $part)
                        <li class="tools-series-card__progress-item">
                            <a
                                href="{{ locale_route('playbooks.show', ['slug' => $part->slug]) }}"
                                class="tools-series-card__progress-dot"
                                data-playbook-series-part
                                data-slug="{{ $part->slug }}"
                                data-reading-time-de="{{ $part->readingTimeDe }}"
                                data-reading-time-en="{{ $part->readingTimeEn }}"
                                data-playbook-series-card-part-title
                                data-text-de="{{ $part->titleDe }}"
                                data-text-en="{{ $part->titleEn }}"
                                title="{{ $part->titleEn }}"
                                aria-label="{{ $part->part }}. {{ $part->titleEn }}"
                            >
                                <span class="sr-only">{{ $part->part }}. {{ $part->titleEn }}</span>
                            </a>
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>

        <div class="tools-series-card__footer">
            <x-playbooks.product-marks :products="$products" />
            <div class="tools-series-card__actions">
                @if ($firstPart)
                    <a
                        href="{{ locale_route('playbooks.show', ['slug' => $firstPart->slug]) }}"
                        class="tools-series-card__action"
                        data-tooltip-css
                        data-i18n-aria="overview.seriesStart"
                        aria-label="Start series"
                    >
                        <i class="fa-solid fa-play" aria-hidden="true"></i>
                    </a>
                @endif
                <a
                    href="{{ $seriesHref }}"
                    class="tools-series-card__action"
                    data-tooltip-css
                    data-i18n-aria="overview.seriesView"
                    aria-label="View series"
                >
                    <i class="fa-solid fa-book-open" aria-hidden="true"></i>
                </a>
                @if ($firstPart)
                    <button
                        type="button"
                        class="tools-series-card__action tools-series-card__offline-btn"
                        data-tooltip-css
                        data-playbook-series-offline
                        data-series-id="{{ $series->id }}"
                        data-series-slugs="{{ collect($series->parts)->pluck('slug')->implode(',') }}"
                        data-series-manifest-url="{{ locale_route('playbooks.offline.manifest.series', ['seriesId' => $series->id]) }}"
                        data-i18n-aria="playbooks.offline.saveSeries"
                        aria-label="Save series offline"
                    >
                        <i class="fa-solid fa-download" data-offline-icon="save" aria-hidden="true"></i>
                        <i class="fa-solid fa-trash-can" data-offline-icon="remove" hidden aria-hidden="true"></i>
                        <span class="sr-only" data-offline-label data-i18n="playbooks.offline.saveSeriesShort">Offline</span>
                    </button>
                @endif
            </div>
        </div>
    </div>
</article>
