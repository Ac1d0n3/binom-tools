@extends('layouts.tools', [
    'mainClass' => 'tools-shell__main--overview',
])

@section('title', 'Certification roadmap — Compliance — ' . config('app.name'))
@section('meta_description', 'Certification roadmap for data and governance consultants — CIPP/E, CDMP (DAMA/DMBOK), ISO 27001, AIGP and more. Practical start: the 8 pillars. Learning orientation only.')

@section('content')
    <div class="tools-content tools-content--overview tools-content--compliance tools-content--compliance-roadmap" data-overview-filter-root>
        <div class="tools-overview-sticky-header compliance-hub-sticky">
            <p class="compliance-detail__back compliance-roadmap__back">
                <a href="{{ locale_route('compliance.index') }}" data-i18n="compliance.backToIndex">
                    ← All compliance frameworks
                </a>
            </p>
            <h1 class="tools-page-title" data-i18n="compliance.roadmapTitle">Consultant certification roadmap</h1>
            <p class="tools-page-lead compliance-hub-sticky__lead" data-hub-lead data-i18n="compliance.roadmapLead">
                Which credentials help as a data/governance consultant — by region and learning phase. Orientation only.
            </p>
            <p class="compliance-hub-disclaimer" data-i18n="compliance.roadmapDisclaimer">
                Learning and orientation only — not career, hiring or legal advice. Requirements change; always check the issuer.
            </p>

            <div class="tools-overview-toolbar">
                <label class="tools-overview-search">
                    <span class="sr-only" data-i18n="overview.searchLabel">Search</span>
                    <i class="fa-solid fa-magnifying-glass tools-overview-search__icon" aria-hidden="true"></i>
                    <input
                        type="search"
                        class="tools-overview-search__input"
                        data-overview-search
                        autocomplete="off"
                        data-i18n-placeholder="compliance.roadmapSearchPlaceholder"
                        placeholder="Search CIPP/E, CDMP, ISO…"
                    />
                </label>
                <label class="tools-overview-product-filter">
                    <span class="sr-only" data-i18n="compliance.roadmapRegionLabel">Focus region</span>
                    <span class="tools-overview-sort__field">
                        <select class="tools-overview-sort__select" data-overview-residency>
                            <option value="all" data-i18n="compliance.roadmapRegionAll">All regions</option>
                            @foreach ($availableFocusRegions as $regionId)
                                @php
                                    $regionLabel = $focusRegions[$regionId] ?? ['de' => $regionId, 'en' => $regionId];
                                    $rLabelEn = $regionLabel['en'] ?? $regionId;
                                    $rLabelDe = $regionLabel['de'] ?? $rLabelEn;
                                @endphp
                                <option
                                    value="{{ $regionId }}"
                                    data-text-de="{{ $rLabelDe }}"
                                    data-text-en="{{ $rLabelEn }}"
                                >{{ $rLabelEn }}</option>
                            @endforeach
                        </select>
                        <i class="fa-solid fa-chevron-down tools-overview-sort__icon" aria-hidden="true"></i>
                    </span>
                </label>
            </div>
        </div>

        <div class="tools-overview-scroll compliance-hub-scroll">
            <section class="compliance-roadmap-tips">
                <h2 data-i18n="compliance.roadmapTipsTitle">Suggested path</h2>
                <ul class="compliance-roadmap-tips__list">
                    @foreach (($tips['en'] ?? []) as $i => $tipEn)
                        @php $tipDe = $tips['de'][$i] ?? $tipEn; @endphp
                        <li data-text-de="{{ $tipDe }}" data-text-en="{{ $tipEn }}">{{ $tipEn }}</li>
                    @endforeach
                </ul>
            </section>

            <p class="tools-overview-empty" data-overview-empty hidden data-i18n="overview.noResults">
                No matches for your search.
            </p>

            @foreach ($phases as $phaseId => $phase)
                @php
                    $phaseCerts = $certsByPhase[$phaseId] ?? [];
                    $phaseLabelEn = $phase['label']['en'] ?? $phaseId;
                    $phaseLabelDe = $phase['label']['de'] ?? $phaseLabelEn;
                    $phaseLeadEn = $phase['lead']['en'] ?? '';
                    $phaseLeadDe = $phase['lead']['de'] ?? $phaseLeadEn;
                @endphp
                @if ($phaseCerts !== [])
                    <section class="compliance-roadmap-phase" data-overview-workflow-section data-products="{{ $phaseId }}">
                        <header class="compliance-roadmap-phase__header">
                            <h2
                                class="compliance-roadmap-phase__title"
                                data-text-de="{{ $phaseLabelDe }}"
                                data-text-en="{{ $phaseLabelEn }}"
                            >{{ $phaseLabelEn }}</h2>
                            @if ($phaseLeadEn !== '')
                                <p
                                    class="compliance-roadmap-phase__lead"
                                    data-text-de="{{ $phaseLeadDe }}"
                                    data-text-en="{{ $phaseLeadEn }}"
                                >{{ $phaseLeadEn }}</p>
                            @endif
                        </header>

                        <div class="compliance-hub-grid compliance-roadmap-grid" role="list">
                            @foreach ($phaseCerts as $cert)
                                @php
                                    $id = (string) ($cert['id'] ?? '');
                                    $priorityId = is_string($cert['priority'] ?? null) ? $cert['priority'] : '';
                                    $focus = is_array($cert['focusRegions'] ?? null)
                                        ? array_values(array_filter($cert['focusRegions'], static fn ($r) => is_string($r) && $r !== ''))
                                        : [];
                                    $labelEn = $cert['label']['en'] ?? $id;
                                    $labelDe = $cert['label']['de'] ?? $labelEn;
                                    $issuerEn = $cert['issuer']['en'] ?? '';
                                    $issuerDe = $cert['issuer']['de'] ?? $issuerEn;
                                    $purposeEn = $cert['shortPurpose']['en'] ?? '';
                                    $purposeDe = $cert['shortPurpose']['de'] ?? $purposeEn;
                                    $whyEn = $cert['whyForConsultant']['en'] ?? '';
                                    $whyDe = $cert['whyForConsultant']['de'] ?? $whyEn;
                                    $needEn = is_array($cert['whatYouNeed']['en'] ?? null) ? $cert['whatYouNeed']['en'] : [];
                                    $needDe = is_array($cert['whatYouNeed']['de'] ?? null) ? $cert['whatYouNeed']['de'] : $needEn;
                                    $sources = is_array($cert['officialSources'] ?? null) ? $cert['officialSources'] : [];
                                    $relatedFw = is_array($cert['relatedFrameworks'] ?? null) ? $cert['relatedFrameworks'] : [];
                                    $relatedPb = is_array($cert['relatedPlaybooks'] ?? null) ? $cert['relatedPlaybooks'] : [];
                                    $priorityLabel = $priorities[$priorityId] ?? ['de' => $priorityId, 'en' => $priorityId];
                                    $searchParts = [
                                        $id,
                                        $phaseId,
                                        $priorityId,
                                        $labelDe,
                                        $labelEn,
                                        $issuerDe,
                                        $issuerEn,
                                        $purposeDe,
                                        $purposeEn,
                                        $whyDe,
                                        $whyEn,
                                        implode(' ', $needDe),
                                        implode(' ', $needEn),
                                        implode(' ', $focus),
                                    ];
                                    foreach ($focus as $focusId) {
                                        $searchParts[] = $focusRegions[$focusId]['de'] ?? '';
                                        $searchParts[] = $focusRegions[$focusId]['en'] ?? '';
                                    }
                                    $searchText = strtolower(implode(' ', array_filter($searchParts, static fn ($v) => is_string($v) && $v !== '')));
                                @endphp
                                <article
                                    class="compliance-hub-card compliance-roadmap-card compliance-hub-card--{{ $phaseId }} compliance-roadmap-card--{{ $priorityId }}"
                                    role="listitem"
                                    data-overview-item
                                    data-residency="{{ implode(',', $focus) }}"
                                    data-search-text="{{ $searchText }}"
                                    data-sort-title-en="{{ $labelEn }}"
                                    data-sort-title-de="{{ $labelDe }}"
                                >
                                    <aside class="compliance-hub-card__aside">
                                        <div class="compliance-hub-card__mark" aria-hidden="true">
                                            <i class="fa-solid fa-certificate"></i>
                                        </div>
                                        <p class="compliance-hub-card__aside-label">
                                            <span data-text-de="{{ $priorityLabel['de'] ?? $priorityId }}" data-text-en="{{ $priorityLabel['en'] ?? $priorityId }}">
                                                {{ $priorityLabel['en'] ?? $priorityId }}
                                            </span>
                                        </p>
                                    </aside>
                                    <div class="compliance-hub-card__body">
                                        <div class="compliance-hub-card__meta">
                                            @foreach ($focus as $focusId)
                                                @php
                                                    $fLabel = $focusRegions[$focusId] ?? ['de' => $focusId, 'en' => $focusId];
                                                @endphp
                                                <span class="compliance-hub-chip compliance-hub-chip--region">
                                                    <span data-text-de="{{ $fLabel['de'] ?? $focusId }}" data-text-en="{{ $fLabel['en'] ?? $focusId }}">
                                                        {{ $fLabel['en'] ?? $focusId }}
                                                    </span>
                                                </span>
                                            @endforeach
                                        </div>

                                        <h3
                                            class="compliance-hub-card__title"
                                            data-text-de="{{ $labelDe }}"
                                            data-text-en="{{ $labelEn }}"
                                        >{{ $labelEn }}</h3>

                                        @if ($issuerEn !== '')
                                            <p
                                                class="compliance-roadmap-card__issuer"
                                                data-text-de="{{ $issuerDe }}"
                                                data-text-en="{{ $issuerEn }}"
                                            >{{ $issuerEn }}</p>
                                        @endif

                                        @if ($purposeEn !== '')
                                            <p
                                                class="compliance-hub-card__purpose"
                                                data-text-de="{{ $purposeDe }}"
                                                data-text-en="{{ $purposeEn }}"
                                            >{{ $purposeEn }}</p>
                                        @endif

                                        @if ($whyEn !== '')
                                            <div class="compliance-hub-card__rules">
                                                <p class="compliance-hub-card__rules-label" data-i18n="compliance.roadmapWhy">Why it helps</p>
                                                <p
                                                    class="compliance-roadmap-card__why"
                                                    data-text-de="{{ $whyDe }}"
                                                    data-text-en="{{ $whyEn }}"
                                                >{{ $whyEn }}</p>
                                            </div>
                                        @endif

                                        @if ($needEn !== [])
                                            <div class="compliance-roadmap-card__need">
                                                <p class="compliance-hub-card__rules-label" data-i18n="compliance.roadmapNeed">What you need</p>
                                                <ul class="compliance-hub-card__rules-list">
                                                    @foreach ($needEn as $i => $lineEn)
                                                        @php $lineDe = $needDe[$i] ?? $lineEn; @endphp
                                                        <li data-text-de="{{ $lineDe }}" data-text-en="{{ $lineEn }}">{{ $lineEn }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        @if ($sources !== [])
                                            <ul class="compliance-roadmap-card__sources">
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
                                                            >{{ $sLabelEn }}</a>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        @endif

                                        @if ($relatedFw !== [] || $relatedPb !== [])
                                            <div class="compliance-roadmap-card__links">
                                                @foreach ($relatedFw as $fwId)
                                                    @if (is_string($fwId) && isset($frameworkLabels[$fwId]))
                                                        <a
                                                            class="compliance-hub-chip"
                                                            href="{{ locale_route('compliance.show', ['slug' => $fwId]) }}"
                                                            data-text-de="{{ $frameworkLabels[$fwId]['de'] }}"
                                                            data-text-en="{{ $frameworkLabels[$fwId]['en'] }}"
                                                        >{{ $frameworkLabels[$fwId]['en'] }}</a>
                                                    @endif
                                                @endforeach
                                                @foreach ($relatedPb as $pbSlug)
                                                    @if (is_string($pbSlug) && isset($playbookTitles[$pbSlug]))
                                                        <a
                                                            class="compliance-hub-chip compliance-hub-chip--type"
                                                            href="{{ locale_route('playbooks.show', ['slug' => $pbSlug]) }}"
                                                            data-text-de="{{ $playbookTitles[$pbSlug]['titleDe'] }}"
                                                            data-text-en="{{ $playbookTitles[$pbSlug]['titleEn'] }}"
                                                        >{{ $playbookTitles[$pbSlug]['titleEn'] }}</a>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </section>
                @endif
            @endforeach
        </div>
    </div>
@endsection
