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
        $series->id,
        ...collect($series->parts)->flatMap(fn ($part) => [$part->titleDe, $part->titleEn])->all(),
        ...$productLabels,
        ...$products,
    ])));
    $firstPart = $series->firstPart();
    $seriesHref = locale_route('playbooks.series', ['seriesId' => $series->id]);
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

            <p
                class="tools-series-card__meta"
                data-playbook-series-card-meta
                data-part-count="{{ $series->partCount() }}"
                data-reading-time-de="{{ $series->totalReadingTimeDe }}"
                data-reading-time-en="{{ $series->totalReadingTimeEn }}"
            >
                {{ $series->partCount() }} parts · {{ format_reading_time($series->totalReadingTimeEn, 'en') }} total
            </p>

            <ol class="tools-series-card__parts">
                @foreach ($series->parts as $part)
                    <li class="tools-series-card__part">
                        <a
                            href="{{ locale_route('playbooks.show', ['slug' => $part->slug]) }}"
                            class="tools-series-card__part-link"
                            data-playbook-series-part
                            data-slug="{{ $part->slug }}"
                            data-reading-time-de="{{ $part->readingTimeDe }}"
                            data-reading-time-en="{{ $part->readingTimeEn }}"
                        >
                            <span class="tools-series-card__part-index">{{ $part->part }}.</span>
                            <span
                                class="tools-series-card__part-title"
                                data-playbook-series-card-part-title
                                data-text-de="{{ $part->titleDe }}"
                                data-text-en="{{ $part->titleEn }}"
                            >{{ $part->titleEn }}</span>
                            <i class="fa-solid fa-arrow-right tools-series-card__part-arrow" aria-hidden="true"></i>
                        </a>
                    </li>
                @endforeach
            </ol>
        </div>

        <div class="tools-series-card__footer">
            <x-playbooks.product-marks :products="$products" />
            <div class="tools-series-card__actions">
                @if ($firstPart)
                    <a
                        href="{{ locale_route('playbooks.show', ['slug' => $firstPart->slug]) }}"
                        class="tools-series-card__start tools-series-card__start--primary"
                        data-i18n="overview.seriesStart"
                    >Start series</a>
                @endif
                <a
                    href="{{ $seriesHref }}"
                    class="tools-series-card__start"
                    data-i18n="overview.seriesView"
                >View series</a>
                @if ($firstPart)
                    <button
                        type="button"
                        class="tools-series-card__offline-btn"
                        data-playbook-series-offline
                        data-series-id="{{ $series->id }}"
                        data-series-slugs="{{ collect($series->parts)->pluck('slug')->implode(',') }}"
                        data-series-manifest-url="{{ locale_route('playbooks.offline.manifest.series', ['seriesId' => $series->id]) }}"
                        data-i18n-aria="playbooks.offline.saveSeries"
                        aria-label="Save series offline"
                        title="Save series offline"
                    >
                        <i class="fa-solid fa-download" data-offline-icon="save" aria-hidden="true"></i>
                        <i class="fa-solid fa-trash-can" data-offline-icon="remove" hidden aria-hidden="true"></i>
                        <span class="tools-series-card__offline-label" data-offline-label data-i18n="playbooks.offline.saveSeriesShort">Offline</span>
                    </button>
                @endif
            </div>
        </div>
    </div>
</article>
