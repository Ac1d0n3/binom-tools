@extends('layouts.tools', [
    'mainClass' => 'tools-shell__main--overview',
])

@section('title', 'Glossary — '.config('app.name'))
@section('meta_description', 'Governance glossary — steward, lineage, DSDR, grain, data product, PII, catalog and more, linked to stories and tools.')

@section('content')
    <div class="tools-content tools-content--overview tools-content--glossary" data-overview-filter-root>
        <div class="tools-overview-sticky-header glossary-hub-sticky">
            <h1 class="tools-page-title" data-i18n="glossary.indexTitle">Glossary</h1>
            <p class="tools-page-lead" data-hub-lead data-i18n="glossary.indexLead">
                Shared vocabulary for data governance — definitions with links into stories, tools, and learning paths.
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
                        data-i18n-placeholder="glossary.searchPlaceholder"
                        placeholder="Search steward, lineage, DSDR…"
                    />
                </label>
                <label class="tools-overview-product-filter">
                    <span class="sr-only" data-i18n="glossary.categoryLabel">Category</span>
                    <span class="tools-overview-sort__field">
                        <select class="tools-overview-sort__select" data-overview-product>
                            <option value="all" data-i18n="glossary.categoryAll">All categories</option>
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
            </div>
        </div>

        <div class="tools-overview-scroll">
            <p class="tools-overview-empty" data-overview-empty hidden data-i18n="overview.noResults">
                No matches for your search.
            </p>

            <div class="glossary-hub-grid" role="list">
                @foreach ($terms as $term)
                    @php
                        $id = (string) ($term['id'] ?? '');
                        $categoryId = is_string($term['category'] ?? null) ? $term['category'] : '';
                        $termEn = (string) ($term['term']['en'] ?? $id);
                        $termDe = (string) ($term['term']['de'] ?? $termEn);
                        $defEn = (string) ($term['definition']['en'] ?? '');
                        $defDe = (string) ($term['definition']['de'] ?? $defEn);
                        $aliases = is_array($term['aliases'] ?? null) ? $term['aliases'] : [];
                        $categoryLabel = $categories[$categoryId] ?? ['de' => $categoryId, 'en' => $categoryId];
                        $catEn = $categoryLabel['en'] ?? $categoryId;
                        $catDe = $categoryLabel['de'] ?? $catEn;
                        $searchText = mb_strtolower(implode(' ', array_filter([
                            $termEn,
                            $termDe,
                            $defEn,
                            $defDe,
                            $id,
                            $categoryId,
                            implode(' ', $aliases),
                        ])));
                    @endphp
                    <a
                        href="{{ locale_route('glossary.show', ['slug' => $id]) }}"
                        class="glossary-hub-card"
                        role="listitem"
                        data-overview-item
                        data-search-text="{{ $searchText }}"
                        data-products="{{ $categoryId }}"
                    >
                        <span class="glossary-hub-card__meta">
                            <span data-text-de="{{ $catDe }}" data-text-en="{{ $catEn }}">{{ $catEn }}</span>
                        </span>
                        <span class="glossary-hub-card__title" data-text-de="{{ $termDe }}" data-text-en="{{ $termEn }}">{{ $termEn }}</span>
                        <span class="glossary-hub-card__def" data-text-de="{{ $defDe }}" data-text-en="{{ $defEn }}">{{ $defEn }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endsection
