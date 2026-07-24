@props(['series'])

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
        <div class="tools-series-card__hero">
            <x-playbooks.responsive-image
                :src="$series->heroUrl"
                alt=""
                class="tools-series-card__hero-image"
                loading="lazy"
                decoding="async"
            />
            <x-playbooks.product-marks :products="$products" class="tools-card__purpose--on-series-hero" />
        </div>
    @else
        <div class="tools-series-card__hero tools-series-card__hero--placeholder" aria-hidden="true">
            <div class="tools-card__icon-wrap tools-card__icon-wrap--primary">
                <i class="fa-solid fa-layer-group tools-card__icon"></i>
            </div>
            <x-playbooks.product-marks :products="$products" class="tools-card__purpose--on-series-hero" />
        </div>
    @endif

    <div class="tools-series-card__body">
        <h3
            class="tools-series-card__title"
            data-playbook-series-card-title
            data-text-de="{{ $series->titleDe }}"
            data-text-en="{{ $series->titleEn }}"
        >{{ $series->titleEn }}</h3>

        <p
            class="tools-series-card__meta"
            data-playbook-series-card-meta
            data-part-count="{{ $series->partCount() }}"
            data-reading-time-de="{{ $series->totalReadingTimeDe }}"
            data-reading-time-en="{{ $series->totalReadingTimeEn }}"
        >
            {{ $series->partCount() }} parts · {{ $series->totalReadingTimeEn }} min total
        </p>

        @if (count($productLabels) > 0)
            <p class="tools-series-card__products">{{ implode(' · ', $productLabels) }}</p>
        @endif

        <ol class="tools-series-card__parts">
            @foreach ($series->parts as $part)
                <li class="tools-series-card__part">
                    <a
                        href="{{ locale_route('playbooks.show', ['slug' => $part->slug]) }}"
                        class="tools-series-card__part-link"
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

        @if ($firstPart)
            <div class="tools-series-card__actions">
                <a
                    href="{{ locale_route('playbooks.show', ['slug' => $firstPart->slug]) }}"
                    class="tools-series-card__start"
                    data-i18n="overview.seriesStart"
                >Start series</a>
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
            </div>
        @endif
    </div>
</article>
