@extends('layouts.tools', [
    'mainClass' => 'tools-shell__main--overview',
])

@section('title', 'Glossary — '.config('app.name'))
@section('meta_description', 'Governance glossary — steward, lineage, DSDR, grain, data product, PII, catalog and more, linked to stories and tools.')

@section('content')
    @php
        $azLetters = is_array($azLetters ?? null) ? $azLetters : array_merge(range('A', 'Z'), ['#']);
        $availableLettersEn = is_array($availableLettersEn ?? null) ? $availableLettersEn : [];
        $availableLettersDe = is_array($availableLettersDe ?? null) ? $availableLettersDe : [];
    @endphp
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
                <button
                    type="button"
                    class="glossary-az-toggle"
                    data-glossary-az-toggle
                    aria-expanded="false"
                    aria-controls="glossary-az-panel"
                    data-i18n-aria="glossary.azToggle"
                    aria-label="A–Z"
                    title="A–Z"
                >
                    <span data-i18n="glossary.azToggle">A–Z</span>
                    <i class="fa-solid fa-chevron-down glossary-az-toggle__icon" aria-hidden="true"></i>
                </button>
                <div
                    class="tools-overview-layout-toggle"
                    role="group"
                >
                    <button
                        type="button"
                        class="tools-overview-layout-toggle__button tools-overview-layout-toggle__button--active"
                        data-overview-layout-toggle="grid"
                        aria-pressed="true"
                        data-i18n-aria="overview.layoutGrid"
                        aria-label="Grid view"
                        title="Grid view"
                    >
                        <i class="fa-solid fa-grip" aria-hidden="true"></i>
                        <span class="sr-only" data-i18n="overview.layoutGrid">Grid view</span>
                    </button>
                    <button
                        type="button"
                        class="tools-overview-layout-toggle__button"
                        data-overview-layout-toggle="list"
                        aria-pressed="false"
                        data-i18n-aria="overview.layoutList"
                        aria-label="List view"
                        title="List view"
                    >
                        <i class="fa-solid fa-list" aria-hidden="true"></i>
                        <span class="sr-only" data-i18n="overview.layoutList">List view</span>
                    </button>
                </div>
                <div class="glossary-hub-icon-actions" role="group">
                    <button
                        type="button"
                        class="glossary-hub-icon-btn"
                        data-glossary-quiz-open
                        data-tooltip-css
                        data-i18n-aria="glossary.quiz.cta"
                        aria-label="Buzzword Quiz"
                        aria-haspopup="dialog"
                        aria-controls="glossary-quiz-modal"
                    >
                        <i class="fa-solid fa-clipboard-question" aria-hidden="true"></i>
                        <span class="sr-only" data-i18n="glossary.quiz.cta">Buzzword Quiz</span>
                    </button>
                    <a
                        href="{{ locale_route('glossary.bingo') }}"
                        class="glossary-hub-icon-btn"
                        data-tooltip-css
                        data-i18n-aria="glossary.bingo.cta"
                        aria-label="Funny Meeting Bingo"
                    >
                        <i class="fa-solid fa-table-cells-large" aria-hidden="true"></i>
                        <span class="sr-only" data-i18n="glossary.bingo.cta">Funny Meeting Bingo</span>
                    </a>
                </div>
                <span
                    class="tools-overview-count-badge"
                    data-overview-result-count
                    data-overview-count-mode="items"
                    data-overview-count-badge
                    aria-live="polite"
                >{{ count($terms) }}</span>
            </div>

            <div
                id="glossary-az-panel"
                class="glossary-az-filter"
                data-glossary-az-panel
                hidden
            >
                <span class="sr-only" data-i18n="glossary.azLabel">Filter by letter</span>
                <div class="glossary-az-filter__chips" role="group" aria-label="A–Z">
                    <button
                        type="button"
                        class="tools-filter-chip glossary-az-chip tools-filter-chip--active"
                        data-glossary-letter="all"
                        data-i18n="glossary.azAll"
                    >All</button>
                    @foreach ($azLetters as $letter)
                        @php
                            $hasEn = in_array($letter, $availableLettersEn, true);
                            $hasDe = in_array($letter, $availableLettersDe, true);
                        @endphp
                        <button
                            type="button"
                            class="tools-filter-chip glossary-az-chip"
                            data-glossary-letter="{{ $letter }}"
                            data-letter-available-en="{{ $hasEn ? '1' : '0' }}"
                            data-letter-available-de="{{ $hasDe ? '1' : '0' }}"
                            @if (! $hasEn && ! $hasDe) disabled aria-disabled="true" @endif
                        >{{ $letter }}</button>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="tools-overview-scroll">
            <p class="tools-overview-empty" data-overview-empty hidden data-i18n="overview.noResults">
                No matches for your search.
            </p>

            <div class="glossary-hub-grid" role="list" data-overview-stories-grid>
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

                        $letterFrom = static function (string $label): string {
                            $trimmed = trim($label);
                            if ($trimmed === '') {
                                return '#';
                            }
                            $first = mb_strtoupper(mb_substr($trimmed, 0, 1));
                            $folded = match ($first) {
                                'Ä' => 'A',
                                'Ö' => 'O',
                                'Ü' => 'U',
                                default => $first,
                            };

                            return preg_match('/^[A-Z]$/', $folded) === 1 ? $folded : '#';
                        };
                        $letterEn = $letterFrom($termEn);
                        $letterDe = $letterFrom($termDe);
                    @endphp
                    <a
                        href="{{ locale_route('glossary.show', ['slug' => $id]) }}"
                        class="glossary-hub-card"
                        role="listitem"
                        data-overview-item
                        data-search-text="{{ $searchText }}"
                        data-products="{{ $categoryId }}"
                        data-letter-en="{{ $letterEn }}"
                        data-letter-de="{{ $letterDe }}"
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

    @php
        $questionCount = (int) request()->query('count', \App\Glossary\BuzzwordQuizGenerator::DEFAULT_COUNT);
        $questionCount = \App\Glossary\BuzzwordQuizGenerator::clampQuestionCount($questionCount);
        $countOptions = range(
            \App\Glossary\BuzzwordQuizGenerator::MIN_COUNT,
            \App\Glossary\BuzzwordQuizGenerator::MAX_COUNT,
            5
        );
        if (! in_array($questionCount, $countOptions, true)) {
            $countOptions[] = $questionCount;
            sort($countOptions);
        }
        $openQuiz = request()->boolean('quiz');
        $isDe = current_locale() === 'de';
    @endphp
    <dialog
        id="glossary-quiz-modal"
        class="glossary-quiz-modal"
        data-glossary-quiz-modal
        @if ($openQuiz) data-glossary-quiz-autopen="1" @endif
        aria-labelledby="glossary-quiz-modal-title"
    >
        <div
            class="glossary-quiz-modal__panel"
            data-glossary-quiz
            data-quiz-data-url="{{ $quizDataUrl }}"
            data-can-save="{{ ! empty($canSaveQuizToProfile) ? '1' : '0' }}"
            @if (! empty($quizResultsUrl))
                data-quiz-save-url="{{ $quizResultsUrl }}"
            @endif
        >
            <header class="glossary-quiz-modal__header">
                <h2 id="glossary-quiz-modal-title" class="glossary-quiz-modal__title" data-i18n="glossary.quiz.title">
                    Buzzword Quiz
                </h2>
                <button
                    type="button"
                    class="tools-btn tools-btn--ghost tools-btn--compact glossary-quiz-modal__close"
                    data-glossary-quiz-close
                    data-i18n-aria="glossary.quiz.close"
                    aria-label="{{ $isDe ? 'Schließen' : 'Close' }}"
                >
                    <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                </button>
            </header>

            <p class="glossary-quiz-modal__lead" data-i18n="glossary.quiz.lead">
                {{ $isDe
                    ? 'Gemischte Single- und Multi-Choice-Fragen aus dem Glossar — neue Begriffe fließen automatisch ein.'
                    : 'Mixed single- and multi-choice questions from the live glossary — new terms are included automatically.' }}
            </p>

            <form class="glossary-setup-form" data-quiz-setup-form>
                <label class="glossary-setup-form__field">
                    <span data-i18n="glossary.quiz.countLabel">{{ $isDe ? 'Fragen' : 'Questions' }}</span>
                    <span class="tools-overview-sort__field">
                        <select class="tools-overview-sort__select" name="count" data-quiz-count-select>
                            @foreach ($countOptions as $option)
                                <option value="{{ $option }}" @selected($option === $questionCount)>{{ $option }}</option>
                            @endforeach
                        </select>
                        <i class="fa-solid fa-chevron-down tools-overview-sort__icon" aria-hidden="true"></i>
                    </span>
                </label>
                @include('glossary.partials.category-picks', [
                    'availableCategories' => $availableCategories ?? [],
                    'categories' => $categories ?? [],
                    'selectedCategories' => $selectedQuizCategories ?? null,
                    'selectAllWhenEmpty' => false,
                    'inputName' => 'categories[]',
                    'dataAttr' => 'data-quiz-category',
                    'legendI18n' => 'glossary.quiz.categoriesLabel',
                    'legendFallback' => $isDe ? 'Kategorien' : 'Categories',
                    'hintI18n' => 'glossary.quiz.categoriesHint',
                    'hintFallback' => $isDe
                        ? 'Optional — ohne Auswahl spielen alle Kategorien mit.'
                        : 'Optional — with no selection, all categories are included.',
                ])
                <button type="submit" class="tools-btn tools-btn--primary" data-quiz-start>
                    <i class="fa-solid fa-play" aria-hidden="true"></i>
                    <span data-i18n="glossary.quiz.start">{{ $isDe ? 'Quiz starten' : 'Start quiz' }}</span>
                </button>
            </form>

            <script type="application/json" data-glossary-quiz-questions>[]</script>

            <div class="glossary-quiz" data-glossary-quiz-ui hidden>
                <div class="glossary-quiz__progress" data-quiz-progress aria-live="polite"></div>
                <p class="glossary-quiz__stem" data-quiz-stem></p>
                <blockquote class="glossary-quiz__prompt" data-quiz-prompt hidden></blockquote>
                <p class="glossary-quiz__hint" data-quiz-hint hidden></p>
                <div class="glossary-quiz__choices" data-quiz-choices role="group"></div>
                <div class="glossary-quiz__feedback" data-quiz-feedback hidden aria-live="polite"></div>
                <div class="glossary-quiz__actions">
                    <button type="button" class="tools-btn tools-btn--primary" data-quiz-check hidden data-i18n="glossary.quiz.check">
                        Check answer
                    </button>
                    <button type="button" class="tools-btn tools-btn--primary" data-quiz-next hidden data-i18n="glossary.quiz.next">
                        Next
                    </button>
                </div>
                <div class="glossary-quiz__result" data-quiz-result hidden>
                    <p class="glossary-quiz__score" data-quiz-score></p>
                    <div class="glossary-quiz__result-actions">
                        <button type="button" class="tools-btn tools-btn--primary" data-quiz-save hidden data-i18n="glossary.quiz.save">
                            Save to profile
                        </button>
                        <button type="button" class="tools-btn" data-quiz-retry data-i18n="glossary.quiz.retry">
                            Play again
                        </button>
                    </div>
                    <p class="glossary-quiz__save-status" data-quiz-save-status hidden aria-live="polite"></p>
                </div>
            </div>
        </div>
    </dialog>
@endsection
