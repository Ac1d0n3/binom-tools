@extends('layouts.tools', [
    'mainClass' => 'tools-shell__main--overview',
])

@section('title', ($query !== '' ? $query.' — ' : '').'Search — '.config('app.name'))
@section('meta_description', 'Sitewide search across stories, tools, resources, suppliers, compliance, radar, glossary, and learning paths.')

@section('content')
    <div class="tools-content tools-content--overview tools-content--search">
        <div class="tools-overview-sticky-header search-hub-sticky">
            <h1 class="tools-page-title" data-i18n="search.indexTitle">Search</h1>
            <p class="tools-page-lead" data-hub-lead data-i18n="search.indexLead">
                Find stories, tools, resources, suppliers, compliance, radar sources, glossary terms, and learning paths in one place.
            </p>

            <form class="search-hub-form" method="get" action="{{ locale_route('search.index') }}" role="search">
                <label class="tools-overview-search search-hub-form__query">
                    <span class="sr-only" data-i18n="search.queryLabel">Search the hub</span>
                    <i class="fa-solid fa-magnifying-glass tools-overview-search__icon" aria-hidden="true"></i>
                    <input
                        type="search"
                        name="q"
                        value="{{ $query }}"
                        class="tools-overview-search__input"
                        autocomplete="off"
                        autofocus
                        data-i18n-placeholder="search.placeholder"
                        placeholder="Search PII, lineage, GDPR…"
                    />
                </label>
                <label class="tools-overview-product-filter search-hub-form__type">
                    <span class="sr-only" data-i18n="search.typeLabel">Type</span>
                    <span class="tools-overview-sort__field">
                        <select class="tools-overview-sort__select" name="type">
                            <option value="all" @selected($type === 'all') data-i18n="search.typeAll">All types</option>
                            @foreach ($types as $typeId)
                                <option
                                    value="{{ $typeId }}"
                                    @selected($type === $typeId)
                                    data-i18n="search.type.{{ $typeId }}"
                                >{{ $typeId }}</option>
                            @endforeach
                        </select>
                        <i class="fa-solid fa-chevron-down tools-overview-sort__icon" aria-hidden="true"></i>
                    </span>
                </label>
                <button type="submit" class="tools-button tools-button--primary search-hub-form__submit" data-i18n="search.submit">
                    Search
                </button>
            </form>

            @if ($query !== '')
                <p
                    class="search-hub-count"
                    data-i18n="search.resultCount"
                    data-i18n-count="{{ $resultCount }}"
                >{{ $resultCount }} results</p>
            @endif
        </div>

        <div class="tools-overview-scroll">
            @if ($query === '')
                <p class="tools-overview-empty" data-i18n="search.emptyPrompt">
                    Enter a term to search the whole hub.
                </p>
            @elseif ($resultCount === 0)
                <p class="tools-overview-empty" data-i18n="search.noResults">
                    No matches for your search.
                </p>
            @else
                <div class="search-hub-results" role="list">
                    @foreach ($results as $hit)
                        @php
                            $titleEn = (string) ($hit['title']['en'] ?? $hit['id'] ?? '');
                            $titleDe = (string) ($hit['title']['de'] ?? $titleEn);
                            $descEn = (string) ($hit['description']['en'] ?? '');
                            $descDe = (string) ($hit['description']['de'] ?? $descEn);
                            $href = locale_route((string) $hit['route'], is_array($hit['params'] ?? null) ? $hit['params'] : []);
                            $queryParams = is_array($hit['query'] ?? null) ? $hit['query'] : [];
                            if ($queryParams !== []) {
                                $href .= (str_contains($href, '?') ? '&' : '?').http_build_query($queryParams);
                            }
                            $typeId = (string) ($hit['type'] ?? '');
                            $icon = (string) ($hit['icon'] ?? 'fa-magnifying-glass');
                        @endphp
                        <a href="{{ $href }}" class="search-hub-result" role="listitem">
                            <span class="search-hub-result__icon" aria-hidden="true">
                                <i class="fa-solid {{ $icon }}"></i>
                            </span>
                            <span class="search-hub-result__body">
                                <span class="search-hub-result__meta">
                                    <span class="search-hub-result__type" data-i18n="search.type.{{ $typeId }}">{{ $typeId }}</span>
                                </span>
                                <span class="search-hub-result__title" data-text-de="{{ $titleDe }}" data-text-en="{{ $titleEn }}">{{ $titleEn }}</span>
                                @if ($descEn !== '' || $descDe !== '')
                                    <span class="search-hub-result__desc" data-text-de="{{ $descDe }}" data-text-en="{{ $descEn }}">{{ $descEn }}</span>
                                @endif
                            </span>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
