@extends('layouts.tools')

@section('title', 'Tools — ' . config('app.name'))

@section('content')
    <div class="tools-content" data-overview-filter-root>
        @if (config('tools.overview.show_title'))
            <h1
                class="tools-page-title"
                @if (filled(config('tools.overview.title_en')) || filled(config('tools.overview.title_de')))
                    data-text-de="{{ config('tools.overview.title_de') ?? config('tools.overview.title_en') }}"
                    data-text-en="{{ config('tools.overview.title_en') ?? config('tools.overview.title_de') }}"
                @else
                    data-i18n="tools.overviewTitle"
                @endif
            >{{ config('tools.overview.title_en') ?? 'Tools' }}</h1>
        @endif

        @if (config('tools.overview.show_lead'))
            <p
                class="tools-page-lead"
                @if (filled(config('tools.overview.lead_en')) || filled(config('tools.overview.lead_de')))
                    data-text-de="{{ config('tools.overview.lead_de') ?? config('tools.overview.lead_en') }}"
                    data-text-en="{{ config('tools.overview.lead_en') ?? config('tools.overview.lead_de') }}"
                @else
                    data-i18n="tools.overviewLead"
                @endif
            >Interactive reference workflows — step by step, copy-paste ready.</p>
        @endif

        @php $navById = collect($navItems)->keyBy('id'); @endphp

        <div class="tools-overview-toolbar">
            <label class="tools-overview-search">
                <span class="sr-only" data-i18n="overview.searchLabel">Search</span>
                <i class="fa-solid fa-magnifying-glass tools-overview-search__icon" aria-hidden="true"></i>
                <input
                    type="search"
                    class="tools-overview-search__input"
                    data-overview-search
                    autocomplete="off"
                    data-i18n-placeholder="overview.searchPlaceholder"
                    placeholder="Search tools…"
                />
            </label>
        </div>

        <p class="tools-overview-empty" data-overview-empty hidden data-i18n="overview.noResults">
            No matches for your search.
        </p>

        @php
            $workflowStepTotals = collect($workflows)->mapWithKeys(
                fn (array $workflow, string $id) => [$id => count($workflow['steps'] ?? [])],
            );
        @endphp

        <div class="tools-card-grid">
            @foreach ($navItems as $item)
                @php
                    $searchText = strtolower(implode(' ', [
                        $item['label']['de'] ?? '',
                        $item['label']['en'] ?? '',
                        $item['description']['de'] ?? '',
                        $item['description']['en'] ?? '',
                        $item['id'] ?? '',
                    ]));
                    $workflowId = $item['workflow'] ?? null;
                    $stepTotal = $workflowId ? ($workflowStepTotals[$workflowId] ?? null) : null;
                @endphp
                <x-tools.card
                    :href="locale_route($item['route'])"
                    :title="$item['label']['en']"
                    :description="$item['description']['en']"
                    :icon="$item['icon']"
                    :accent="$item['accent']"
                    :card-id="$item['id']"
                    :example="$item['example'] ?? false"
                    :overview-item="true"
                    :search-text="$searchText"
                    :dbt-badge="isset($item['workflow']) || ($item['dbt'] ?? false)"
                    :meta="isset($item['workflowStep'], $stepTotal) ? 'Step ' . $item['workflowStep'] . '/' . $stepTotal : null"
                />
            @endforeach
        </div>

        @foreach ($workflows as $workflowId => $workflow)
            @php $steps = $workflow['steps'] ?? []; @endphp
            <section class="tools-workflow-section" aria-labelledby="workflow-{{ $workflowId }}-title">
                <h2 id="workflow-{{ $workflowId }}-title" class="tools-section__title tools-section__title--with-icon">
                    @if (! empty($workflow['icon']))
                        <i class="fa-solid {{ $workflow['icon'] }} tools-section__title-icon" aria-hidden="true"></i>
                    @endif
                    <span>{{ $workflow['label']['en'] ?? $workflowId }}</span>
                </h2>
                @if (! empty($workflow['description']['en']))
                    <p class="tools-section__lead">{{ $workflow['description']['en'] }}</p>
                @endif
                <ol class="tools-workflow-steps">
                    @foreach ($steps as $index => $stepId)
                        @php $step = $navById->get($stepId); @endphp
                        @if ($step)
                            <li class="tools-workflow-steps__item">
                                <span class="tools-workflow-steps__num">{{ $index + 1 }}</span>
                                <a href="{{ locale_route($step['route']) }}" class="tools-workflow-steps__link">
                                    {{ $step['label']['en'] }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ol>
            </section>
        @endforeach
    </div>
@endsection
