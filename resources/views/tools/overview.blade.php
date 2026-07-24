@extends('layouts.tools', [
    'mainClass' => 'tools-shell__main--overview',
])

@section('title', 'Governance — ' . config('app.name'))
@section('meta_description', 'Interactive governance workflows — dbt macros, PII policies, schema editors, and workflow examples you can copy into your stack.')

@section('content')
    <div class="tools-content tools-content--overview tools-content--tools-overview" data-overview-filter-root>
        @if (config('tools.overview.show_title'))
            <h1
                class="tools-page-title"
                @if (filled(config('tools.overview.title_en')) || filled(config('tools.overview.title_de')))
                    data-text-de="{{ config('tools.overview.title_de') ?? config('tools.overview.title_en') }}"
                    data-text-en="{{ config('tools.overview.title_en') ?? config('tools.overview.title_de') }}"
                @else
                    data-i18n="tools.overviewTitle"
                @endif
            >{{ config('tools.overview.title_en') ?: 'Governance' }}</h1>
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

        @php
            $navById = collect($navItems)->keyBy('id');
            $productLabels = [
                'dbt' => 'dbt',
                'fabric' => 'Fabric',
                'databricks' => 'Databricks',
                'pureview' => 'PureView',
                'qlik' => 'Qlik',
                'tableau' => 'Tableau',
                'powerbi' => 'Power BI',
                'ai' => 'AI',
                'other' => 'Other',
            ];
            $productsForItem = static function (array $item): array {
                $products = [];

                if (\App\Support\ToolsNav::showsDbtBadge($item)) {
                    $products[] = 'dbt';
                }

                foreach (($item['for'] ?? []) as $target) {
                    if (! is_string($target)) {
                        continue;
                    }

                    $key = strtolower(trim($target));
                    if (in_array($key, ['fabric', 'databricks', 'pureview', 'qlik', 'tableau', 'powerbi', 'ai'], true)) {
                        $products[] = $key;
                    }
                }

                return array_values(array_unique($products ?: ['other']));
            };
            $availableProducts = collect($navItems)
                ->flatMap(fn (array $item) => $productsForItem($item))
                ->unique()
                ->sortBy(fn (string $key) => array_search($key, array_keys($productLabels), true))
                ->values()
                ->all();
        @endphp

        <div class="tools-overview-sticky-header">
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
                <label class="tools-overview-product-filter">
                    <span class="sr-only" data-i18n="overview.productLabel">Product</span>
                    <span class="tools-overview-sort__field">
                        <select class="tools-overview-sort__select" data-overview-product>
                            <option value="all" data-i18n="overview.productAll">All products</option>
                            @foreach ($availableProducts as $product)
                                <option value="{{ $product }}">{{ $productLabels[$product] ?? ucfirst($product) }}</option>
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
                        $itemProducts = $productsForItem($item);
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
                        :products="$itemProducts"
                        :dbt-badge="\App\Support\ToolsNav::showsDbtBadge($item)"
                        :platform-marks="\App\Support\ToolsNav::platformMarks($item)"
                        :meta="isset($item['workflowStep'], $stepTotal) ? 'Step ' . $item['workflowStep'] . '/' . $stepTotal : null"
                    />
                @endforeach
            </div>

            @foreach ($workflows as $workflowId => $workflow)
                @php
                    $steps = $workflow['steps'] ?? [];
                    $workflowProducts = collect($steps)
                        ->map(fn (string $stepId) => $navById->get($stepId))
                        ->filter()
                        ->flatMap(fn (array $step) => $productsForItem($step))
                        ->unique()
                        ->values()
                        ->all();
                @endphp
                <section class="tools-workflow-section" aria-labelledby="workflow-{{ $workflowId }}-title" data-overview-workflow-section data-products="{{ implode(',', $workflowProducts) }}">
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
    </div>
@endsection
