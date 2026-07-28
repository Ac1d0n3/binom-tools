@props(['series'])

@php
    $partCount = $series->partCount();
    $href = locale_route('playbooks.series', ['seriesId' => $series->id]);
    $firstPart = $series->firstPart();
    $titleId = 'series-teaser-title-'.$series->id;
@endphp

<article
    class="tools-card tools-card--story tools-card--story-has-hero tools-card--series-teaser"
    data-series-teaser
    data-series-id="{{ $series->id }}"
>
    <a
        href="{{ $href }}"
        class="tools-card__story-link"
        aria-labelledby="{{ $titleId }}"
    >
        <div class="tools-card__media">
            @if ($series->heroUrl)
                <div class="tools-card__hero">
                    <x-playbooks.responsive-image
                        :src="$series->heroUrl"
                        alt=""
                        class="tools-card__hero-image"
                        loading="lazy"
                        decoding="async"
                    />
                </div>
            @else
                <div class="tools-card__hero tools-card__hero--placeholder" aria-hidden="true">
                    <div class="tools-card__icon-wrap tools-card__icon-wrap--primary">
                        <i class="fa-solid fa-layer-group tools-card__icon"></i>
                    </div>
                </div>
            @endif

            <span class="tools-card__series-badge">
                <i class="fa-solid fa-layer-group" aria-hidden="true"></i>
                <span
                    data-text-de="Serie · {{ $partCount }} Teile"
                    data-text-en="Series · {{ $partCount }} parts"
                >Series · {{ $partCount }} parts</span>
            </span>
        </div>

        <div class="tools-card__story-body">
            <p
                class="tools-card__meta tools-card__meta--story"
                data-playbook-series-card-meta
                data-part-count="{{ $partCount }}"
                data-reading-time-de="{{ $series->totalReadingTimeDe }}"
                data-reading-time-en="{{ $series->totalReadingTimeEn }}"
            >
                {{ $partCount }} parts · {{ format_reading_time($series->totalReadingTimeEn, 'en') }} total
            </p>

            <h3
                id="{{ $titleId }}"
                class="tools-card__title tools-card__title--story"
                data-playbook-series-card-title
                data-text-de="{{ $series->titleDe }}"
                data-text-en="{{ $series->titleEn }}"
            >{{ $series->titleEn }}</h3>

            @if ($series->descriptionEn !== '' || $series->descriptionDe !== '')
                <p
                    class="tools-card__desc tools-card__desc--story"
                    data-playbook-series-card-summary
                    data-text-de="{{ $series->descriptionDe }}"
                    data-text-en="{{ $series->descriptionEn }}"
                >{{ $series->descriptionEn !== '' ? $series->descriptionEn : $series->descriptionDe }}</p>
            @elseif ($firstPart)
                <p
                    class="tools-card__desc tools-card__desc--story"
                    data-text-de="{{ $partCount }} Teile — starten mit „{{ $firstPart->titleDe }}“."
                    data-text-en="{{ $partCount }} parts — start with “{{ $firstPart->titleEn }}”."
                >{{ $partCount }} parts — start with “{{ $firstPart->titleEn }}”.</p>
            @endif
        </div>
    </a>

    <div class="tools-card__story-footer">
        <x-playbooks.product-marks :products="$series->products" />
        <i class="fa-solid fa-arrow-right tools-card__arrow" aria-hidden="true"></i>
    </div>
</article>
