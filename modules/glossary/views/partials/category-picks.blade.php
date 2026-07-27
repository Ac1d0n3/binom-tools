@php
    /** @var list<string> $availableCategories */
    $availableCategories = is_array($availableCategories ?? null) ? $availableCategories : [];
    /** @var array<string, array{de?: string, en?: string}> $categories */
    $categories = is_array($categories ?? null) ? $categories : [];
    /** @var list<string>|null $selectedCategories */
    $selectedCategories = $selectedCategories ?? null;
    $selectAllWhenEmpty = (bool) ($selectAllWhenEmpty ?? true);
    $inputName = (string) ($inputName ?? 'categories[]');
    $fieldsetClass = trim('glossary-category-picks '.((string) ($fieldsetClass ?? '')));
@endphp
@if ($availableCategories !== [])
    <fieldset class="{{ $fieldsetClass }}">
        <legend class="glossary-category-picks__legend" data-i18n="{{ $legendI18n ?? 'glossary.quiz.categoriesLabel' }}">
            {{ $legendFallback ?? (current_locale() === 'de' ? 'Kategorien' : 'Categories') }}
        </legend>
        <p class="glossary-category-picks__hint" data-i18n="{{ $hintI18n ?? 'glossary.quiz.categoriesHint' }}">
            {{ $hintFallback ?? (current_locale() === 'de'
                ? 'Optional — ohne Auswahl spielen alle Kategorien mit.'
                : 'Optional — with no selection, all categories are included.') }}
        </p>
        <div class="glossary-category-picks__list">
            @foreach ($availableCategories as $categoryId)
                @php
                    $categoryLabel = $categories[$categoryId] ?? ['de' => $categoryId, 'en' => $categoryId];
                    $cLabelEn = (string) ($categoryLabel['en'] ?? $categoryId);
                    $cLabelDe = (string) ($categoryLabel['de'] ?? $cLabelEn);
                    $isChecked = $selectedCategories === null
                        ? $selectAllWhenEmpty
                        : in_array($categoryId, $selectedCategories, true);
                @endphp
                <label class="glossary-category-pick">
                    <input
                        type="checkbox"
                        name="{{ $inputName }}"
                        value="{{ $categoryId }}"
                        @checked($isChecked)
                        @if (! empty($dataAttr)) {{ $dataAttr }} @endif
                    />
                    <span data-text-de="{{ $cLabelDe }}" data-text-en="{{ $cLabelEn }}">{{ current_locale() === 'de' ? $cLabelDe : $cLabelEn }}</span>
                </label>
            @endforeach
        </div>
    </fieldset>
@endif
