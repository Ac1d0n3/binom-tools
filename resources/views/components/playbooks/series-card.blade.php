@props(['series'])

@php
    $searchText = strtolower(implode(' ', array_filter([
        $series->titleDe,
        $series->titleEn,
        $series->id,
        ...collect($series->parts)->flatMap(fn ($part) => [$part->titleDe, $part->titleEn])->all(),
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
>
    @if ($series->heroUrl)
        <div class="tools-series-card__hero">
            <img
                src="{{ $series->heroUrl }}"
                alt=""
                class="tools-series-card__hero-image"
                loading="lazy"
            />
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
            <a
                href="{{ locale_route('playbooks.show', ['slug' => $firstPart->slug]) }}"
                class="tools-series-card__start"
                data-i18n="overview.seriesStart"
            >Start series</a>
        @endif
    </div>
</article>
