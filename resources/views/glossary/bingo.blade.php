@extends('foundations.layouts.tools', [
    'mainClass' => 'tools-shell__main--overview',
])

@section('title', 'Funny Meeting Bingo — '.config('app.name'))
@section('meta_description', 'Printable funny meeting bingo cards generated from the glossary.')

@section('content')
    @php
        $cardCount = (int) ($cardCount ?? 1);
        $bingoSize = \App\Glossary\BuzzwordQuizGenerator::normalizeBingoSize($bingoSize ?? 5);
        $cards = is_array($cards ?? null) ? $cards : [];
        $baseSeed = (string) ($baseSeed ?? '');
        $cardOptions = range(
            \App\Glossary\BuzzwordQuizGenerator::MIN_BINGO_CARDS,
            \App\Glossary\BuzzwordQuizGenerator::MAX_BINGO_CARDS
        );
        $sizeOptions = \App\Glossary\BuzzwordQuizGenerator::BINGO_SIZES;
        $isDe = current_locale() === 'de';
        $selectedCategories = $selectedCategories ?? null;
        $poolSize = (int) ($poolSize ?? 0);
        $poolNeeded = (int) ($poolNeeded ?? \App\Glossary\BuzzwordQuizGenerator::bingoTermSlots($bingoSize));
        $bingoQuery = ['cards' => $cardCount, 'size' => $bingoSize];
        if (is_array($selectedCategories) && $selectedCategories !== []) {
            $bingoQuery['categories'] = $selectedCategories;
        }
        $newCardsQuery = $bingoQuery;
        $resetQuery = array_merge($bingoQuery, ['seed' => $baseSeed]);
    @endphp
    <div class="tools-content tools-content--overview tools-content--glossary-bingo" data-glossary-bingo>
        <div class="tools-overview-sticky-header glossary-bingo__no-print glossary-bingo__sticky">
            <div class="glossary-bingo__header">
                <div class="glossary-bingo__title-row">
                    <nav class="glossary-bingo__nav">
                        <a href="{{ locale_route('glossary.index') }}" data-i18n="glossary.backToIndex">{{ $isDe ? '← Glossar' : '← Glossary' }}</a>
                    </nav>
                    <h1 class="glossary-bingo__title" data-i18n="glossary.bingo.title">Funny Meeting Bingo</h1>
                    <p class="glossary-bingo__lead" data-i18n="glossary.bingo.lead">
                        {{ $isDe
                            ? 'Buzzwords abhaken — Free Space in der Mitte.'
                            : 'Mark buzzwords as they appear — free space in the center.' }}
                    </p>
                </div>

                <form method="get" action="{{ locale_route('glossary.bingo') }}" class="glossary-bingo__controls">
                    <div class="glossary-bingo__controls-row">
                        <label class="glossary-setup-form__field glossary-bingo__cards-field">
                            <span data-i18n="glossary.bingo.cardsLabel">{{ $isDe ? 'Karten' : 'Cards' }}</span>
                            <span class="tools-overview-sort__field">
                                <select class="tools-overview-sort__select" name="cards" data-bingo-cards-select>
                                    @foreach ($cardOptions as $option)
                                        <option value="{{ $option }}" @selected($option === $cardCount)>{{ $option }}</option>
                                    @endforeach
                                </select>
                                <i class="fa-solid fa-chevron-down tools-overview-sort__icon" aria-hidden="true"></i>
                            </span>
                        </label>
                        <label class="glossary-setup-form__field glossary-bingo__size-field">
                            <span data-i18n="glossary.bingo.sizeLabel">{{ $isDe ? 'Raster' : 'Grid' }}</span>
                            <span class="tools-overview-sort__field">
                                <select class="tools-overview-sort__select" name="size" data-bingo-size-select>
                                    @foreach ($sizeOptions as $option)
                                        <option value="{{ $option }}" @selected($option === $bingoSize)>
                                            {{ $option }}×{{ $option }}
                                        </option>
                                    @endforeach
                                </select>
                                <i class="fa-solid fa-chevron-down tools-overview-sort__icon" aria-hidden="true"></i>
                            </span>
                        </label>
                        <button type="submit" class="tools-btn tools-btn--primary tools-btn--compact">
                            <i class="fa-solid fa-shuffle" aria-hidden="true"></i>
                            <span data-i18n="glossary.bingo.generate">{{ $isDe ? 'Erzeugen' : 'Generate' }}</span>
                        </button>
                        <button type="button" class="tools-btn tools-btn--compact" data-bingo-print>
                            <i class="fa-solid fa-print" aria-hidden="true"></i>
                            <span data-i18n="glossary.bingo.print">{{ $isDe ? 'Drucken' : 'Print' }}</span>
                        </button>
                        <a class="tools-btn tools-btn--compact" href="{{ locale_route('glossary.bingo', $newCardsQuery) }}">
                            <i class="fa-solid fa-rotate" aria-hidden="true"></i>
                            <span data-i18n="glossary.bingo.newCard">{{ $isDe ? 'Neu' : 'New' }}</span>
                        </a>
                        <a class="tools-btn tools-btn--compact" href="{{ locale_route('glossary.bingo', $resetQuery) }}">
                            <i class="fa-solid fa-eraser" aria-hidden="true"></i>
                            <span data-i18n="glossary.bingo.reset">{{ $isDe ? 'Reset' : 'Reset' }}</span>
                        </a>
                    </div>
                    @include('glossary.partials.category-picks', [
                        'availableCategories' => $availableCategories ?? [],
                        'categories' => $categories ?? [],
                        'selectedCategories' => $selectedCategories,
                        'selectAllWhenEmpty' => false,
                        'inputName' => 'categories[]',
                        'fieldsetClass' => 'glossary-category-picks--compact',
                        'legendI18n' => 'glossary.bingo.categoriesLabel',
                        'legendFallback' => $isDe ? 'Kategorien' : 'Categories',
                        'hintI18n' => 'glossary.bingo.categoriesHint',
                        'hintFallback' => $isDe
                            ? 'Ohne Auswahl = alle. Für volle Karten mind. '.$poolNeeded.' Begriffe.'
                            : 'No selection = all. Need '.$poolNeeded.'+ terms for full cards.',
                    ])
                </form>

                @if ($poolSize > 0 && $poolSize < $poolNeeded)
                    <p class="glossary-bingo__pool-warning" data-i18n="glossary.bingo.tooFew">
                        {{ $isDe
                            ? 'Nur '.$poolSize.' Begriffe — Karten mit Platzhaltern.'
                            : 'Only '.$poolSize.' terms — cards padded with placeholders.' }}
                    </p>
                @endif
            </div>
        </div>

        <div class="tools-overview-scroll">
            <div
                class="glossary-bingo-deck glossary-bingo-deck--size-{{ $bingoSize }}"
                data-bingo-deck
                data-bingo-base-seed="{{ $baseSeed }}"
                data-bingo-size="{{ $bingoSize }}"
            >
                @foreach ($cards as $card)
                    <article
                        class="glossary-bingo-card glossary-bingo-card--size-{{ $bingoSize }}"
                        data-bingo-card
                        data-bingo-seed="{{ $card['seed'] }}"
                        data-bingo-size="{{ $bingoSize }}"
                    >
                        <h2 class="glossary-bingo-card__title">
                            <span data-i18n="glossary.bingo.cardTitle">Funny Meeting Bingo</span>
                            <span class="glossary-bingo-card__size">{{ $bingoSize }}×{{ $bingoSize }}</span>
                            @if ($cardCount > 1)
                                <span class="glossary-bingo-card__number">#{{ $card['number'] }}</span>
                            @endif
                        </h2>
                        <div
                            class="glossary-bingo-grid glossary-bingo-grid--{{ $bingoSize }}"
                            role="grid"
                            aria-label="Bingo {{ $bingoSize }}×{{ $bingoSize }}"
                        >
                            @foreach ($card['cells'] as $cell)
                                @if ($cell === null)
                                    <button
                                        type="button"
                                        class="glossary-bingo-cell glossary-bingo-cell--free glossary-bingo-cell--marked"
                                        data-bingo-cell
                                        data-bingo-free="1"
                                        aria-pressed="true"
                                        disabled
                                    >
                                        <span data-i18n="glossary.bingo.free">{{ $isDe ? 'FREI' : 'FREE' }}</span>
                                    </button>
                                @else
                                    <button
                                        type="button"
                                        class="glossary-bingo-cell"
                                        data-bingo-cell
                                        data-term-id="{{ $cell['id'] }}"
                                        aria-pressed="false"
                                    >
                                        <span class="glossary-bingo-cell__label">{{ $cell['label'] }}</span>
                                    </button>
                                @endif
                            @endforeach
                        </div>
                        <p class="glossary-bingo-card__seed glossary-bingo__no-print">
                            <span data-i18n="glossary.bingo.seedLabel">Seed</span>: <code>{{ $card['seed'] }}</code>
                        </p>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
@endsection
