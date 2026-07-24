@extends('layouts.tools', [
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
    $keyRulesEn = is_array($item['keyRules']['en'] ?? null) ? $item['keyRules']['en'] : [];
    $keyRulesDe = is_array($item['keyRules']['de'] ?? null) ? $item['keyRules']['de'] : $keyRulesEn;
    $platformEn = is_array($item['platformImplications']['en'] ?? null) ? $item['platformImplications']['en'] : [];
    $platformDe = is_array($item['platformImplications']['de'] ?? null) ? $item['platformImplications']['de'] : $platformEn;
    $sources = is_array($item['officialSources'] ?? null) ? $item['officialSources'] : [];
    $activeLocale = current_locale();
@endphp

@section('title', $labelEn . ' — Compliance — ' . config('app.name'))
@section('meta_description', $purposeEn)

@section('content')
    <div
        class="tools-content tools-content--compliance-detail"
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
                <h2 data-i18n="compliance.whyItMatters">Why it matters</h2>
                <p data-text-de="{{ $whyDe }}" data-text-en="{{ $whyEn }}">
                    {{ $activeLocale === 'de' ? $whyDe : $whyEn }}
                </p>
            </section>

            <section class="compliance-detail__section">
                <h2 data-i18n="compliance.appliesTo">Who it applies to</h2>
                <p data-text-de="{{ $appliesDe }}" data-text-en="{{ $appliesEn }}">
                    {{ $activeLocale === 'de' ? $appliesDe : $appliesEn }}
                </p>
            </section>

            <section class="compliance-detail__section">
                <h2 data-i18n="compliance.keyRules">Key rules</h2>
                <ul class="compliance-detail__list">
                    @foreach ($keyRulesEn as $i => $ruleEn)
                        @php $ruleDe = $keyRulesDe[$i] ?? $ruleEn; @endphp
                        <li data-text-de="{{ $ruleDe }}" data-text-en="{{ $ruleEn }}">
                            {{ $activeLocale === 'de' ? $ruleDe : $ruleEn }}
                        </li>
                    @endforeach
                </ul>
            </section>

            <section class="compliance-detail__section">
                <h2 data-i18n="compliance.platformImplications">What it means for data platforms</h2>
                <ul class="compliance-detail__list">
                    @foreach ($platformEn as $i => $lineEn)
                        @php $lineDe = $platformDe[$i] ?? $lineEn; @endphp
                        <li data-text-de="{{ $lineDe }}" data-text-en="{{ $lineEn }}">
                            {{ $activeLocale === 'de' ? $lineDe : $lineEn }}
                        </li>
                    @endforeach
                </ul>
            </section>

            @if ($sources !== [])
                <section class="compliance-detail__section">
                    <h2 data-i18n="compliance.officialSources">Official sources</h2>
                    <ul class="compliance-detail__sources">
                        @foreach ($sources as $source)
                            @php
                                $href = is_string($source['href'] ?? null) ? $source['href'] : '';
                                $sLabelEn = $source['label']['en'] ?? $href;
                                $sLabelDe = $source['label']['de'] ?? $sLabelEn;
                            @endphp
                            @if ($href !== '')
                                <li>
                                    <a
                                        href="{{ $href }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        data-text-de="{{ $sLabelDe }}"
                                        data-text-en="{{ $sLabelEn }}"
                                    >{{ $activeLocale === 'de' ? $sLabelDe : $sLabelEn }}</a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </section>
            @endif

            @if ($relatedPlaybooks !== [])
                <section class="compliance-detail__section">
                    <h2 data-i18n="compliance.relatedPlaybooks">Related stories</h2>
                    <ul class="compliance-detail__related">
                        @foreach ($relatedPlaybooks as $related)
                            <li>
                                <a
                                    href="{{ locale_route('playbooks.show', ['slug' => $related['slug']]) }}"
                                    data-text-de="{{ $related['titleDe'] }}"
                                    data-text-en="{{ $related['titleEn'] }}"
                                >{{ $activeLocale === 'de' ? $related['titleDe'] : $related['titleEn'] }}</a>
                            </li>
                        @endforeach
                    </ul>
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
