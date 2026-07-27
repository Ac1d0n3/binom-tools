@extends('foundations.layouts.tools', [
    'mainClass' => 'tools-shell__main--overview',
])

@php
    $id = (string) ($item['id'] ?? '');
    $labelEn = $item['label']['en'] ?? $id;
    $labelDe = $item['label']['de'] ?? $labelEn;
    $purposeEn = $item['shortPurpose']['en'] ?? '';
    $purposeDe = $item['shortPurpose']['de'] ?? $purposeEn;
    $whyEn = $item['whyItMatters']['en'] ?? '';
    $whyDe = $item['whyItMatters']['de'] ?? $whyEn;
    $appliesEn = $item['appliesTo']['en'] ?? '';
    $appliesDe = $item['appliesTo']['de'] ?? $appliesEn;
    $categoryId = is_string($item['category'] ?? null) ? $item['category'] : '';
    $regionId = is_string($item['region'] ?? null) ? $item['region'] : '';
    $typeId = is_string($item['type'] ?? null) ? $item['type'] : '';
    $depth = is_string($item['depth'] ?? null) ? $item['depth'] : 'full';
    $categoryLabel = $categories[$categoryId] ?? ['de' => $categoryId, 'en' => $categoryId];
    $regionLabel = $regions[$regionId] ?? ['de' => $regionId, 'en' => $regionId];
    $typeLabel = $types[$typeId] ?? ['de' => $typeId, 'en' => $typeId];

    $normalizeCards = static function (array $deItems, array $enItems): array {
        $cards = [];
        $count = max(count($deItems), count($enItems));
        for ($i = 0; $i < $count; $i++) {
            $de = $deItems[$i] ?? ($enItems[$i] ?? null);
            $en = $enItems[$i] ?? $de;
            if (is_string($de) || is_string($en)) {
                $titleEn = is_string($en) ? $en : (string) $de;
                $titleDe = is_string($de) ? $de : $titleEn;
                $cards[] = [
                    'titleDe' => $titleDe,
                    'titleEn' => $titleEn,
                    'detailDe' => '',
                    'detailEn' => '',
                    'refDe' => '',
                    'refEn' => '',
                ];
                continue;
            }
            if (! is_array($de) && ! is_array($en)) {
                continue;
            }
            $deArr = is_array($de) ? $de : [];
            $enArr = is_array($en) ? $en : $deArr;
            $titleEn = (string) ($enArr['title'] ?? $deArr['title'] ?? '');
            $titleDe = (string) ($deArr['title'] ?? $titleEn);
            $detailEn = (string) ($enArr['detail'] ?? $deArr['detail'] ?? '');
            $detailDe = (string) ($deArr['detail'] ?? $detailEn);
            $refEn = (string) ($enArr['ref'] ?? $deArr['ref'] ?? '');
            $refDe = (string) ($deArr['ref'] ?? $refEn);
            if ($titleEn === '' && $titleDe === '' && $detailEn === '' && $detailDe === '') {
                continue;
            }
            $cards[] = [
                'titleDe' => $titleDe,
                'titleEn' => $titleEn,
                'detailDe' => $detailDe,
                'detailEn' => $detailEn,
                'refDe' => $refDe,
                'refEn' => $refEn,
            ];
        }

        return $cards;
    };

    $keyRules = $normalizeCards(
        is_array($item['keyRules']['de'] ?? null) ? $item['keyRules']['de'] : [],
        is_array($item['keyRules']['en'] ?? null) ? $item['keyRules']['en'] : []
    );
    $platformCards = $normalizeCards(
        is_array($item['platformImplications']['de'] ?? null) ? $item['platformImplications']['de'] : [],
        is_array($item['platformImplications']['en'] ?? null) ? $item['platformImplications']['en'] : []
    );
    $checklistCards = $normalizeCards(
        is_array($item['checklist']['de'] ?? null) ? $item['checklist']['de'] : [],
        is_array($item['checklist']['en'] ?? null) ? $item['checklist']['en'] : []
    );
    $pitfallCards = $normalizeCards(
        is_array($item['commonPitfalls']['de'] ?? null) ? $item['commonPitfalls']['de'] : [],
        is_array($item['commonPitfalls']['en'] ?? null) ? $item['commonPitfalls']['en'] : []
    );

    $scopeNotesDe = is_array($item['scopeNotes']['de'] ?? null) ? $item['scopeNotes']['de'] : [];
    $scopeNotesEn = is_array($item['scopeNotes']['en'] ?? null) ? $item['scopeNotes']['en'] : $scopeNotesDe;
    $scopeCards = [];
    $scopeCount = max(count($scopeNotesDe), count($scopeNotesEn));
    for ($i = 0; $i < $scopeCount; $i++) {
        $noteEn = is_string($scopeNotesEn[$i] ?? null) ? $scopeNotesEn[$i] : '';
        $noteDe = is_string($scopeNotesDe[$i] ?? null) ? $scopeNotesDe[$i] : $noteEn;
        if ($noteEn === '' && $noteDe === '') {
            continue;
        }
        $scopeCards[] = ['detailDe' => $noteDe, 'detailEn' => $noteEn];
    }

    $sources = is_array($item['officialSources'] ?? null) ? $item['officialSources'] : [];
    $activeLocale = current_locale();
@endphp

@section('title', $labelEn . ' — Compliance — ' . config('app.name'))
@section('meta_description', $purposeEn)

@section('content')
    <div
        class="tools-content tools-content--compliance-detail compliance-detail--{{ $categoryId }}"
        data-page-title-root
        data-title-de="{{ $labelDe }}"
        data-title-en="{{ $labelEn }}"
        data-title-suffix=" — Compliance — {{ config('app.name') }}"
    >
        <p class="compliance-detail__back">
            <a href="{{ locale_route('compliance.index') }}" data-i18n="compliance.backToIndex">
                ← All compliance frameworks
            </a>
        </p>

        <header class="compliance-detail__header">
            <div class="compliance-hub-card__meta">
                <span class="compliance-hub-chip compliance-hub-chip--category">
                    <span data-text-de="{{ $categoryLabel['de'] ?? $categoryId }}" data-text-en="{{ $categoryLabel['en'] ?? $categoryId }}">
                        {{ $categoryLabel['en'] ?? $categoryId }}
                    </span>
                </span>
                <span class="compliance-hub-chip compliance-hub-chip--region">
                    <span data-text-de="{{ $regionLabel['de'] ?? $regionId }}" data-text-en="{{ $regionLabel['en'] ?? $regionId }}">
                        {{ $regionLabel['en'] ?? $regionId }}
                    </span>
                </span>
                <span class="compliance-hub-chip compliance-hub-chip--type">
                    <span data-text-de="{{ $typeLabel['de'] ?? $typeId }}" data-text-en="{{ $typeLabel['en'] ?? $typeId }}">
                        {{ $typeLabel['en'] ?? $typeId }}
                    </span>
                </span>
                @if ($depth === 'short')
                    <span class="compliance-hub-chip compliance-hub-chip--short" data-i18n="compliance.depthShort">Short</span>
                @endif
            </div>

            <h1
                class="tools-page-title"
                data-text-de="{{ $labelDe }}"
                data-text-en="{{ $labelEn }}"
            >{{ $activeLocale === 'de' ? $labelDe : $labelEn }}</h1>

            <p
                class="tools-page-lead"
                data-text-de="{{ $purposeDe }}"
                data-text-en="{{ $purposeEn }}"
            >{{ $activeLocale === 'de' ? $purposeDe : $purposeEn }}</p>

            <p class="compliance-hub-disclaimer" data-i18n="compliance.disclaimer">
                Learning and orientation only — not legal advice.
            </p>
        </header>

        <div class="compliance-detail__sections">
            <section class="compliance-detail__section">
                <div class="compliance-detail-card-grid compliance-detail-card-grid--intro">
                    @include('compliance::partials.info-card', [
                        'variant' => 'intro',
                        'titleDe' => 'Warum das zählt',
                        'titleEn' => 'Why it matters',
                        'titleI18n' => 'compliance.whyItMatters',
                        'detailDe' => $whyDe,
                        'detailEn' => $whyEn,
                    ])
                    @include('compliance::partials.info-card', [
                        'variant' => 'intro',
                        'titleDe' => 'Für wen gilt es',
                        'titleEn' => 'Who it applies to',
                        'titleI18n' => 'compliance.appliesTo',
                        'detailDe' => $appliesDe,
                        'detailEn' => $appliesEn,
                    ])
                </div>
            </section>

            @if ($scopeCards !== [])
                <section class="compliance-detail__section">
                    <h2 data-i18n="compliance.scopeNotes">Scope & boundaries</h2>
                    <div class="compliance-detail-card-grid compliance-detail-card-grid--scope">
                        @foreach ($scopeCards as $card)
                            @include('compliance::partials.info-card', [
                                'variant' => 'scope',
                                'titleDe' => '',
                                'titleEn' => '',
                                'detailDe' => $card['detailDe'],
                                'detailEn' => $card['detailEn'],
                            ])
                        @endforeach
                    </div>
                </section>
            @endif

            @if ($keyRules !== [])
                <section class="compliance-detail__section">
                    <h2 data-i18n="compliance.keyRules">Key rules</h2>
                    <div class="compliance-detail-card-grid">
                        @foreach ($keyRules as $card)
                            @include('compliance::partials.info-card', [
                                'variant' => 'rule',
                                'titleDe' => $card['titleDe'],
                                'titleEn' => $card['titleEn'],
                                'detailDe' => $card['detailDe'],
                                'detailEn' => $card['detailEn'],
                                'refDe' => $card['refDe'],
                                'refEn' => $card['refEn'],
                            ])
                        @endforeach
                    </div>
                </section>
            @endif

            @if ($platformCards !== [])
                <section class="compliance-detail__section">
                    <h2 data-i18n="compliance.platformImplications">What it means for data platforms</h2>
                    <div class="compliance-detail-card-grid">
                        @foreach ($platformCards as $card)
                            @include('compliance::partials.info-card', [
                                'variant' => 'platform',
                                'titleDe' => $card['titleDe'],
                                'titleEn' => $card['titleEn'],
                                'detailDe' => $card['detailDe'],
                                'detailEn' => $card['detailEn'],
                            ])
                        @endforeach
                    </div>
                </section>
            @endif

            @if ($checklistCards !== [])
                <section class="compliance-detail__section">
                    <h2 data-i18n="compliance.checklist">Orientation checklist</h2>
                    <div class="compliance-detail-card-grid">
                        @foreach ($checklistCards as $card)
                            @include('compliance::partials.info-card', [
                                'variant' => 'checklist',
                                'titleDe' => $card['titleDe'],
                                'titleEn' => $card['titleEn'],
                                'detailDe' => $card['detailDe'],
                                'detailEn' => $card['detailEn'],
                            ])
                        @endforeach
                    </div>
                </section>
            @endif

            @if ($pitfallCards !== [])
                <section class="compliance-detail__section">
                    <h2 data-i18n="compliance.commonPitfalls">Common pitfalls</h2>
                    <div class="compliance-detail-card-grid">
                        @foreach ($pitfallCards as $card)
                            @include('compliance::partials.info-card', [
                                'variant' => 'pitfall',
                                'titleDe' => $card['titleDe'],
                                'titleEn' => $card['titleEn'],
                                'detailDe' => $card['detailDe'],
                                'detailEn' => $card['detailEn'],
                            ])
                        @endforeach
                    </div>
                </section>
            @endif

            @if ($sources !== [])
                <section class="compliance-detail__section">
                    <h2 data-i18n="compliance.officialSources">Official sources</h2>
                    <div class="compliance-detail-card-grid compliance-detail-card-grid--links">
                        @foreach ($sources as $source)
                            @php
                                $href = is_string($source['href'] ?? null) ? $source['href'] : '';
                                $sLabelEn = $source['label']['en'] ?? $href;
                                $sLabelDe = $source['label']['de'] ?? $sLabelEn;
                            @endphp
                            @if ($href !== '')
                                @include('compliance::partials.info-card', [
                                    'variant' => 'source',
                                    'titleDe' => $sLabelDe,
                                    'titleEn' => $sLabelEn,
                                    'detailDe' => '',
                                    'detailEn' => '',
                                    'href' => $href,
                                ])
                            @endif
                        @endforeach
                    </div>
                </section>
            @endif

            @if ($relatedPlaybooks !== [])
                <section class="compliance-detail__section">
                    <h2 data-i18n="compliance.relatedPlaybooks">Related stories</h2>
                    <div class="compliance-detail-card-grid compliance-detail-card-grid--links">
                        @foreach ($relatedPlaybooks as $related)
                            <a
                                class="compliance-detail-card compliance-detail-card--related compliance-detail-card--link"
                                href="{{ locale_route('playbooks.show', ['slug' => $related['slug']]) }}"
                            >
                                <h3
                                    class="compliance-detail-card__title"
                                    data-text-de="{{ $related['titleDe'] }}"
                                    data-text-en="{{ $related['titleEn'] }}"
                                >{{ $activeLocale === 'de' ? $related['titleDe'] : $related['titleEn'] }}</h3>
                                <span class="compliance-detail-card__arrow" aria-hidden="true">
                                    <i class="fa-solid fa-arrow-right"></i>
                                </span>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>

        <nav class="compliance-detail__pager" aria-label="Compliance pager">
            @if ($prev)
                @php
                    $prevId = (string) ($prev['id'] ?? '');
                    $prevEn = $prev['label']['en'] ?? $prevId;
                    $prevDe = $prev['label']['de'] ?? $prevEn;
                @endphp
                <a
                    class="compliance-detail__pager-link compliance-detail__pager-link--prev"
                    href="{{ locale_route('compliance.show', ['slug' => $prevId]) }}"
                >
                    <span class="compliance-detail__pager-label" data-i18n="compliance.prev">Previous</span>
                    <span data-text-de="{{ $prevDe }}" data-text-en="{{ $prevEn }}">{{ $activeLocale === 'de' ? $prevDe : $prevEn }}</span>
                </a>
            @else
                <span></span>
            @endif

            @if ($next)
                @php
                    $nextId = (string) ($next['id'] ?? '');
                    $nextEn = $next['label']['en'] ?? $nextId;
                    $nextDe = $next['label']['de'] ?? $nextEn;
                @endphp
                <a
                    class="compliance-detail__pager-link compliance-detail__pager-link--next"
                    href="{{ locale_route('compliance.show', ['slug' => $nextId]) }}"
                >
                    <span class="compliance-detail__pager-label" data-i18n="compliance.next">Next</span>
                    <span data-text-de="{{ $nextDe }}" data-text-en="{{ $nextEn }}">{{ $activeLocale === 'de' ? $nextDe : $nextEn }}</span>
                </a>
            @endif
        </nav>
    </div>
@endsection
