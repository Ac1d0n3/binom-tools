@extends('layouts.tools', [
    'viteEntries' => [
        'resources/css/playbooks.css',
        'resources/js/playbooks/show.js',
    ],
    'mainClass' => 'tools-shell__main--playbook',
])

@section('title', $series->titleEn . ' — ' . config('app.name'))

@php
    $firstPart = $series->firstPart();
    $partCount = $series->partCount();
@endphp

@push('head')
    <meta
        name="description"
        content="{{ $partCount }} parts · {{ format_reading_time($series->totalReadingTimeEn, 'en') }} total"
    >
@endpush

@section('content')
    <div class="tools-content playbook-series-page" data-playbook-series-page data-series-id="{{ $series->id }}">
        <nav class="playbook-series-page__nav" aria-label="Series navigation">
            <a
                href="{{ locale_route('playbooks.index') }}?view=series"
                class="playbook-series-page__back"
                data-i18n="playbooks.seriesBack"
            >All series</a>
        </nav>

        <header class="playbook-series-page__header">
            @if ($series->heroUrl)
                <div class="playbook-series-page__hero">
                    <x-playbooks.responsive-image
                        :src="$series->heroUrl"
                        alt=""
                        class="playbook-series-page__hero-image"
                        loading="eager"
                        decoding="async"
                    />
                </div>
            @endif

            <div class="playbook-series-page__intro">
                <p class="playbook-series-page__eyebrow" data-i18n="overview.viewSeries">Series</p>
                <h1
                    class="playbook-series-page__title"
                    data-playbook-series-card-title
                    data-text-de="{{ $series->titleDe }}"
                    data-text-en="{{ $series->titleEn }}"
                    data-page-title-root
                    data-title-de="{{ $series->titleDe }}"
                    data-title-en="{{ $series->titleEn }}"
                    data-title-suffix=" — {{ config('app.name') }}"
                >{{ $series->titleEn }}</h1>
                <p
                    class="playbook-series-page__meta"
                    data-playbook-series-card-meta
                    data-part-count="{{ $partCount }}"
                    data-reading-time-de="{{ $series->totalReadingTimeDe }}"
                    data-reading-time-en="{{ $series->totalReadingTimeEn }}"
                >
                    {{ $partCount }} parts · {{ format_reading_time($series->totalReadingTimeEn, 'en') }} total
                </p>

                @if ($firstPart)
                    <div class="playbook-series-page__actions">
                        <a
                            href="{{ locale_route('playbooks.show', ['slug' => $firstPart->slug]) }}"
                            class="playbook-series-page__start"
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

                <x-playbooks.product-marks :products="$series->products" />
            </div>
        </header>

        <section class="playbook-series-page__parts" aria-labelledby="playbook-series-parts-title">
            <h2 id="playbook-series-parts-title" class="playbook-series-page__parts-title" data-i18n="playbooks.seriesPartsHeading">
                Parts
            </h2>
            <ol class="playbook-series-page__parts-list">
                @foreach ($series->parts as $part)
                    @php
                        $isRead = in_array($part->slug, $serverReadSlugs ?? [], true);
                    @endphp
                    <li class="playbook-series-page__part">
                        <a
                            href="{{ locale_route('playbooks.show', ['slug' => $part->slug]) }}"
                            @class([
                                'playbook-series-page__part-link',
                                'is-read' => $isRead,
                            ])
                            data-playbook-series-part
                            data-slug="{{ $part->slug }}"
                            data-reading-time-de="{{ $part->readingTimeDe }}"
                            data-reading-time-en="{{ $part->readingTimeEn }}"
                            @if ($isRead) data-read="1" @endif
                        >
                            <span class="playbook-series-page__part-index">{{ $part->part }}.</span>
                            <span
                                class="playbook-series-page__part-title"
                                data-playbook-series-card-part-title
                                data-text-de="{{ $part->titleDe }}"
                                data-text-en="{{ $part->titleEn }}"
                            >{{ $part->titleEn }}</span>
                            <span
                                class="playbook-series-page__part-time"
                                data-playbook-series-part-time
                                data-text-de="{{ format_reading_time($part->readingTimeDe, 'de') }}"
                                data-text-en="{{ format_reading_time($part->readingTimeEn, 'en') }}"
                            >{{ format_reading_time($part->readingTimeEn, 'en') }}</span>
                            <i class="fa-solid fa-arrow-right playbook-series-page__part-arrow" aria-hidden="true"></i>
                        </a>
                    </li>
                @endforeach
            </ol>
        </section>
    </div>
@endsection
