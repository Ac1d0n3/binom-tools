@extends('layouts.tools', [
    'mainClass' => 'tools-shell__main--overview',
])

@section('title', 'Learning Paths — '.config('app.name'))
@section('meta_description', 'Guided learning paths for data governance — PII in five steps, DQ with dbt, warehouse modernization, and governance foundations.')

@section('content')
    <div class="tools-content tools-content--overview tools-content--learning-paths" data-overview-filter-root>
        <div class="tools-overview-sticky-header learning-paths-hub-sticky">
            <h1 class="tools-page-title" data-i18n="learningPaths.indexTitle">Learning Paths</h1>
            <p class="tools-page-lead" data-hub-lead data-i18n="learningPaths.indexLead">
                Guided journeys by role and goal — built from stories, series, tools, and glossary terms.
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
                        data-i18n-placeholder="learningPaths.searchPlaceholder"
                        placeholder="Search PII, dbt, warehouse…"
                    />
                </label>
                <label class="tools-overview-product-filter">
                    <span class="sr-only" data-i18n="learningPaths.audienceLabel">Audience</span>
                    <span class="tools-overview-sort__field">
                        <select class="tools-overview-sort__select" data-overview-product>
                            <option value="all" data-i18n="learningPaths.audienceAll">All audiences</option>
                            @foreach ($availableAudiences as $audienceId)
                                @php
                                    $audienceLabel = $audiences[$audienceId] ?? ['de' => $audienceId, 'en' => $audienceId];
                                    $aLabelEn = $audienceLabel['en'] ?? $audienceId;
                                    $aLabelDe = $audienceLabel['de'] ?? $aLabelEn;
                                @endphp
                                <option
                                    value="{{ $audienceId }}"
                                    data-text-de="{{ $aLabelDe }}"
                                    data-text-en="{{ $aLabelEn }}"
                                >{{ $aLabelEn }}</option>
                            @endforeach
                        </select>
                        <i class="fa-solid fa-chevron-down tools-overview-sort__icon" aria-hidden="true"></i>
                    </span>
                </label>
            </div>
        </div>

        <div class="tools-overview-scroll">
            <p class="tools-overview-empty" data-overview-empty hidden data-i18n="overview.noResults">
                No matches for your search.
            </p>

            <div class="learning-paths-hub-grid" role="list">
                @foreach ($paths as $path)
                    @php
                        $id = (string) ($path['id'] ?? '');
                        $audienceId = is_string($path['audienceId'] ?? null) ? $path['audienceId'] : '';
                        $titleEn = (string) ($path['title']['en'] ?? $id);
                        $titleDe = (string) ($path['title']['de'] ?? $titleEn);
                        $leadEn = (string) ($path['lead']['en'] ?? '');
                        $leadDe = (string) ($path['lead']['de'] ?? $leadEn);
                        $audienceEn = (string) ($path['audience']['en'] ?? ($audiences[$audienceId]['en'] ?? $audienceId));
                        $audienceDe = (string) ($path['audience']['de'] ?? ($audiences[$audienceId]['de'] ?? $audienceEn));
                        $durationEn = (string) ($path['duration']['en'] ?? '');
                        $durationDe = (string) ($path['duration']['de'] ?? $durationEn);
                        $stepCount = is_array($path['steps'] ?? null) ? count($path['steps']) : 0;
                        $searchText = mb_strtolower(implode(' ', array_filter([
                            $titleEn,
                            $titleDe,
                            $leadEn,
                            $leadDe,
                            $audienceEn,
                            $audienceDe,
                            $id,
                            $audienceId,
                        ])));
                    @endphp
                    <a
                        href="{{ locale_route('learning-paths.show', ['slug' => $id]) }}"
                        class="learning-paths-hub-card"
                        role="listitem"
                        data-overview-item
                        data-search-text="{{ $searchText }}"
                        data-products="{{ $audienceId }}"
                    >
                        <span class="learning-paths-hub-card__meta">
                            <span data-text-de="{{ $audienceDe }}" data-text-en="{{ $audienceEn }}">{{ $audienceEn }}</span>
                            @if ($durationEn !== '')
                                <span data-text-de="{{ $durationDe }}" data-text-en="{{ $durationEn }}">{{ $durationEn }}</span>
                            @endif
                        </span>
                        <span class="learning-paths-hub-card__title" data-text-de="{{ $titleDe }}" data-text-en="{{ $titleEn }}">{{ $titleEn }}</span>
                        <span class="learning-paths-hub-card__lead" data-text-de="{{ $leadDe }}" data-text-en="{{ $leadEn }}">{{ $leadEn }}</span>
                        <span
                            class="learning-paths-hub-card__steps"
                            data-i18n="learningPaths.stepCount"
                            data-i18n-count="{{ $stepCount }}"
                        >{{ $stepCount }} steps</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endsection
