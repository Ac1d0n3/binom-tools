@extends('foundations.layouts.tools', [
    'mainClass' => 'tools-shell__main--overview',
])

@section('title', 'Compliance — ' . config('app.name'))
@section('meta_description', 'Compliance frameworks for data platforms — GDPR, BSI C5, EU AI Act, ISO 27001 and more. CDMP/DMBOK bridge via the certification roadmap and the 8 pillars.')

@section('content')
    <div class="tools-content tools-content--overview tools-content--compliance" data-overview-filter-root>
        <div class="tools-overview-sticky-header compliance-hub-sticky">
            <h1 class="tools-page-title" data-i18n="compliance.indexTitle">Compliance</h1>
            <p class="tools-page-lead compliance-hub-sticky__lead" data-hub-lead data-i18n="compliance.indexLead">
                Frameworks and regulations that shape data, privacy, security and AI governance — purpose, key rules and official sources.
            </p>
            <p class="compliance-hub-disclaimer" data-i18n="compliance.disclaimer">
                Learning and orientation only — not legal advice.
            </p>

            <p class="compliance-hub-roadmap-link">
                <a href="{{ locale_route('compliance.roadmap') }}" data-i18n="compliance.roadmapCta">
                    Consultant certification roadmap →
                </a>
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
                        data-i18n-placeholder="compliance.searchPlaceholder"
                        placeholder="Search GDPR, C5, AI Act…"
                    />
                </label>
                <label class="tools-overview-product-filter">
                    <span class="sr-only" data-i18n="compliance.categoryLabel">Category</span>
                    <span class="tools-overview-sort__field">
                        <select class="tools-overview-sort__select" data-overview-product>
                            <option value="all" data-i18n="compliance.categoryAll">All categories</option>
                            @foreach ($availableCategories as $categoryId)
                                @php
                                    $categoryLabel = $categories[$categoryId] ?? ['de' => $categoryId, 'en' => $categoryId];
                                    $cLabelEn = $categoryLabel['en'] ?? $categoryId;
                                    $cLabelDe = $categoryLabel['de'] ?? $cLabelEn;
                                @endphp
                                <option
                                    value="{{ $categoryId }}"
                                    data-text-de="{{ $cLabelDe }}"
                                    data-text-en="{{ $cLabelEn }}"
                                >{{ $cLabelEn }}</option>
                            @endforeach
                        </select>
                        <i class="fa-solid fa-chevron-down tools-overview-sort__icon" aria-hidden="true"></i>
                    </span>
                </label>
                <label class="tools-overview-product-filter">
                    <span class="sr-only" data-i18n="compliance.regionLabel">Region</span>
                    <span class="tools-overview-sort__field">
                        <select class="tools-overview-sort__select" data-overview-residency>
                            <option value="all" data-i18n="compliance.regionAll">All regions</option>
                            @foreach ($availableRegions as $regionId)
                                @php
                                    $regionLabel = $regions[$regionId] ?? ['de' => $regionId, 'en' => $regionId];
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
            <p class="tools-overview-empty" data-overview-empty hidden data-i18n="overview.noResults">
                No matches for your search.
            </p>

            <div class="compliance-hub-grid" role="list">
                @foreach ($items as $item)
                    @php
                        $id = (string) ($item['id'] ?? '');
                        $categoryId = is_string($item['category'] ?? null) ? $item['category'] : '';
                        $regionId = is_string($item['region'] ?? null) ? $item['region'] : '';
                        $typeId = is_string($item['type'] ?? null) ? $item['type'] : '';
                        $depth = is_string($item['depth'] ?? null) ? $item['depth'] : 'full';
                        $labelEn = $item['label']['en'] ?? $id;
                        $labelDe = $item['label']['de'] ?? $labelEn;
                        $purposeEn = $item['shortPurpose']['en'] ?? '';
                        $purposeDe = $item['shortPurpose']['de'] ?? $purposeEn;
                        $categoryLabel = $categories[$categoryId] ?? ['de' => $categoryId, 'en' => $categoryId];
                        $regionLabel = $regions[$regionId] ?? ['de' => $regionId, 'en' => $regionId];
                        $typeLabel = $types[$typeId] ?? ['de' => $typeId, 'en' => $typeId];
                        $keyRulesEn = is_array($item['keyRules']['en'] ?? null) ? $item['keyRules']['en'] : [];
                        $keyRulesDe = is_array($item['keyRules']['de'] ?? null) ? $item['keyRules']['de'] : $keyRulesEn;
                        $ruleText = static function (mixed $rule, string $fallbackLocale = 'en'): string {
                            if (is_string($rule)) {
                                return $rule;
                            }
                            if (! is_array($rule)) {
                                return '';
                            }
                            $title = $rule['title'] ?? '';
                            $detail = $rule['detail'] ?? '';
                            $ref = $rule['ref'] ?? '';

                            return trim(implode(' ', array_filter([
                                is_string($title) ? $title : '',
                                is_string($detail) ? $detail : '',
                                is_string($ref) ? $ref : '',
                            ], static fn (string $v): bool => $v !== '')));
                        };
                        $ruleTitle = static function (mixed $rule): string {
                            if (is_string($rule)) {
                                return $rule;
                            }
                            if (is_array($rule) && is_string($rule['title'] ?? null)) {
                                return $rule['title'];
                            }

                            return '';
                        };
                        $previewEn = array_values(array_filter(
                            array_map($ruleTitle, array_slice($keyRulesEn, 0, 3)),
                            static fn (string $v): bool => $v !== ''
                        ));
                        $previewDe = [];
                        foreach (array_slice($keyRulesDe, 0, 3) as $i => $ruleDe) {
                            $titleDe = $ruleTitle($ruleDe);
                            $titleEn = $previewEn[$i] ?? $titleDe;
                            $previewDe[] = $titleDe !== '' ? $titleDe : $titleEn;
                        }
                        $searchRuleParts = [];
                        foreach ($keyRulesDe as $rule) {
                            $searchRuleParts[] = $ruleText($rule);
                        }
                        foreach ($keyRulesEn as $rule) {
                            $searchRuleParts[] = $ruleText($rule);
                        }
                        $searchParts = [
                            $id,
                            $categoryId,
                            $regionId,
                            $typeId,
                            $depth,
                            $labelDe,
                            $labelEn,
                            $purposeDe,
                            $purposeEn,
                            $categoryLabel['de'] ?? '',
                            $categoryLabel['en'] ?? '',
                            $regionLabel['de'] ?? '',
                            $regionLabel['en'] ?? '',
                            $typeLabel['de'] ?? '',
                            $typeLabel['en'] ?? '',
                            ...$searchRuleParts,
                        ];
                        $searchText = strtolower(implode(' ', array_filter($searchParts, static fn ($v) => is_string($v) && $v !== '')));
                    @endphp
                    @php
                        $categoryIcons = [
                            'privacy' => 'fa-user-shield',
                            'security' => 'fa-shield-halved',
                            'ai' => 'fa-robot',
                            'retention' => 'fa-clock-rotate-left',
                            'sector' => 'fa-building-columns',
                        ];
                        $categoryIcon = $categoryIcons[$categoryId] ?? 'fa-scale-balanced';
                    @endphp
                    <article
                        class="compliance-hub-card compliance-hub-card--{{ $categoryId }}"
                        role="listitem"
                        data-overview-item
                        data-products="{{ $categoryId }}"
                        data-residency="{{ $regionId }}"
                        data-search-text="{{ $searchText }}"
                        data-sort-title-en="{{ $labelEn }}"
                        data-sort-title-de="{{ $labelDe }}"
                    >
                        <aside class="compliance-hub-card__aside">
                            <div class="compliance-hub-card__mark" aria-hidden="true">
                                <i class="fa-solid {{ $categoryIcon }}"></i>
                            </div>
                            <p
                                class="compliance-hub-card__aside-label"
                                data-text-de="{{ $categoryLabel['de'] ?? $categoryId }}"
                                data-text-en="{{ $categoryLabel['en'] ?? $categoryId }}"
                            >{{ $categoryLabel['en'] ?? $categoryId }}</p>
                        </aside>
                        <div class="compliance-hub-card__body">
                            <div class="compliance-hub-card__meta">
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

                            <h2 class="compliance-hub-card__title">
                                <a
                                    href="{{ locale_route('compliance.show', ['slug' => $id]) }}"
                                    class="compliance-hub-card__link"
                                    data-text-de="{{ $labelDe }}"
                                    data-text-en="{{ $labelEn }}"
                                >{{ $labelEn }}</a>
                            </h2>

                            @if ($purposeEn !== '')
                                <p
                                    class="compliance-hub-card__purpose"
                                    data-text-de="{{ $purposeDe }}"
                                    data-text-en="{{ $purposeEn }}"
                                >{{ $purposeEn }}</p>
                            @endif

                            @if ($previewEn !== [])
                                <div class="compliance-hub-card__rules">
                                    <p class="compliance-hub-card__rules-label" data-i18n="compliance.keyRulesPreview">Key rules</p>
                                    <ul class="compliance-hub-card__rules-list">
                                        @foreach ($previewEn as $i => $ruleEn)
                                            @php
                                                $ruleDe = $previewDe[$i] ?? $ruleEn;
                                            @endphp
                                            <li data-text-de="{{ $ruleDe }}" data-text-en="{{ $ruleEn }}">{{ $ruleEn }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <p class="compliance-hub-card__cta">
                                <a
                                    href="{{ locale_route('compliance.show', ['slug' => $id]) }}"
                                    class="compliance-hub-card__cta-link"
                                >
                                    <span data-i18n="compliance.readMore">Read overview</span>
                                    <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                                </a>
                            </p>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
@endsection
