<?php

namespace App\Glossary;

/**
 * Builds quiz questions live from config('glossary.terms') — no curated question bank.
 */
final class BuzzwordQuizGenerator
{
    public const DEFAULT_COUNT = 10;

    public const MIN_COUNT = 5;

    public const MAX_COUNT = 30;

    public const DEFAULT_BINGO_CARDS = 1;

    public const MIN_BINGO_CARDS = 1;

    public const MAX_BINGO_CARDS = 12;

    public const BINGO_SIZE_CLASSIC = 5;

    public const BINGO_SIZE_MINI = 3;

    /** @var list<int> */
    public const BINGO_SIZES = [self::BINGO_SIZE_MINI, self::BINGO_SIZE_CLASSIC];

    public const SINGLE_RATIO = 0.7;

    public static function clampQuestionCount(int $count): int
    {
        return max(self::MIN_COUNT, min(self::MAX_COUNT, $count));
    }

    public static function clampBingoCardCount(int $count): int
    {
        return max(self::MIN_BINGO_CARDS, min(self::MAX_BINGO_CARDS, $count));
    }

    public static function normalizeBingoSize(mixed $raw): int
    {
        $size = is_numeric($raw) ? (int) $raw : self::BINGO_SIZE_CLASSIC;
        if (in_array($size, self::BINGO_SIZES, true)) {
            return $size;
        }

        return self::BINGO_SIZE_CLASSIC;
    }

    public static function bingoTermSlots(int $size): int
    {
        $size = self::normalizeBingoSize($size);

        return ($size * $size) - 1;
    }

    public static function bingoFreeIndex(int $size): int
    {
        $size = self::normalizeBingoSize($size);

        return intdiv($size * $size, 2);
    }

    /**
     * Normalize a categories query value to known glossary category ids.
     * Empty / invalid selection means “all categories” (null).
     *
     * @param  mixed  $raw
     * @return list<string>|null
     */
    public static function normalizeCategoryIds(mixed $raw): ?array
    {
        if ($raw === null || $raw === '' || $raw === []) {
            return null;
        }

        if (is_string($raw)) {
            $raw = preg_split('/[,\s]+/', $raw) ?: [];
        }

        if (! is_array($raw)) {
            return null;
        }

        $known = config('glossary.categories', []);
        $knownKeys = is_array($known) ? array_keys($known) : [];
        $knownSet = array_fill_keys(array_map('strval', $knownKeys), true);

        $ids = [];
        foreach ($raw as $value) {
            if (! is_string($value) && ! is_int($value)) {
                continue;
            }
            $id = trim((string) $value);
            if ($id === '' || ! isset($knownSet[$id]) || in_array($id, $ids, true)) {
                continue;
            }
            $ids[] = $id;
        }

        return $ids === [] ? null : $ids;
    }

    /**
     * @param  list<string>|null  $categoryIds
     * @return array{seed: string, questions: list<array<string, mixed>>, categories: list<string>|null}
     */
    public function generate(
        string $locale = 'en',
        int $count = self::DEFAULT_COUNT,
        ?string $seed = null,
        ?array $categoryIds = null,
    ): array {
        $locale = $locale === 'de' ? 'de' : 'en';
        $count = self::clampQuestionCount($count);
        $seed = $seed !== null && $seed !== '' ? $seed : bin2hex(random_bytes(8));
        $rng = $this->seededRng($seed);
        $categoryIds = self::normalizeCategoryIds($categoryIds);

        $terms = $this->usableTerms($categoryIds);
        $categories = config('glossary.categories', []);
        if (! is_array($categories)) {
            $categories = [];
        }

        $byCategory = [];
        foreach ($terms as $term) {
            $cat = (string) ($term['category'] ?? '');
            if ($cat === '') {
                continue;
            }
            $byCategory[$cat][] = $term;
        }

        $multiEligible = array_keys(array_filter(
            $byCategory,
            static fn (array $group): bool => count($group) >= 3,
        ));
        // Multi-choice needs distractors from other selected categories.
        if (count($byCategory) < 2) {
            $multiEligible = [];
        }

        $questions = [];
        $targetMulti = (int) round($count * (1 - self::SINGLE_RATIO));
        $multiMade = 0;

        for ($i = 0; $i < $count; $i++) {
            $wantMulti = $multiMade < $targetMulti
                && $multiEligible !== []
                && ($i >= $count - $targetMulti || $rng() < (1 - self::SINGLE_RATIO));

            if ($wantMulti) {
                $question = $this->buildMultiQuestion($byCategory, $multiEligible, $categories, $locale, $rng);
                if ($question !== null) {
                    $questions[] = $question;
                    $multiMade++;

                    continue;
                }
            }

            $question = $this->buildSingleQuestion($terms, $byCategory, $locale, $rng);
            if ($question !== null) {
                $questions[] = $question;
            }
        }

        while (count($questions) < $count) {
            $question = $this->buildSingleQuestion($terms, $byCategory, $locale, $rng);
            if ($question === null) {
                break;
            }
            $questions[] = $question;
        }

        return [
            'seed' => $seed,
            'questions' => $questions,
            'categories' => $categoryIds,
        ];
    }

    /**
     * Score a selected set against correct ids (exact match only).
     *
     * @param  list<string>  $selectedIds
     * @param  list<string>  $correctIds
     */
    public static function isCorrect(array $selectedIds, array $correctIds): bool
    {
        $selected = array_values(array_unique(array_map('strval', $selectedIds)));
        $correct = array_values(array_unique(array_map('strval', $correctIds)));
        sort($selected);
        sort($correct);

        return $selected === $correct;
    }

    /**
     * @param  list<string>|null  $categoryIds
     * @return list<array<string, mixed>>
     */
    public function usableTerms(?array $categoryIds = null): array
    {
        $categoryIds = self::normalizeCategoryIds($categoryIds);
        $allowed = $categoryIds !== null ? array_fill_keys($categoryIds, true) : null;

        /** @var list<array<string, mixed>> $raw */
        $raw = config('glossary.terms', []);
        $out = [];
        foreach ($raw as $term) {
            if (! is_array($term)) {
                continue;
            }
            $id = (string) ($term['id'] ?? '');
            if ($id === '') {
                continue;
            }
            if ($allowed !== null) {
                $cat = (string) ($term['category'] ?? '');
                if ($cat === '' || ! isset($allowed[$cat])) {
                    continue;
                }
            }
            $termEn = trim((string) ($term['term']['en'] ?? ''));
            $termDe = trim((string) ($term['term']['de'] ?? $termEn));
            $defEn = trim((string) ($term['definition']['en'] ?? ''));
            $defDe = trim((string) ($term['definition']['de'] ?? $defEn));
            if ($termEn === '' || $defEn === '' || $termDe === '' || $defDe === '') {
                continue;
            }
            $out[] = $term;
        }

        return $out;
    }

    /**
     * Pick labels for a bingo card (locale-aware). Free cell sits in the center.
     *
     * @param  list<string>|null  $categoryIds
     * @return array{seed: string, cells: list<array{id: string, label: string}|null>, categories: list<string>|null, size: int}
     */
    public function bingoCard(
        string $locale = 'en',
        ?string $seed = null,
        ?array $categoryIds = null,
        int $size = self::BINGO_SIZE_CLASSIC,
    ): array {
        $locale = $locale === 'de' ? 'de' : 'en';
        $seed = $seed !== null && $seed !== '' ? $seed : bin2hex(random_bytes(8));
        $rng = $this->seededRng($seed);
        $categoryIds = self::normalizeCategoryIds($categoryIds);
        $size = self::normalizeBingoSize($size);
        $termSlots = self::bingoTermSlots($size);
        $freeIndex = self::bingoFreeIndex($size);
        $totalCells = $size * $size;

        $terms = $this->usableTerms($categoryIds);
        $this->shuffleInPlace($terms, $rng);
        $picked = array_slice($terms, 0, $termSlots);

        $cells = [];
        $index = 0;
        for ($i = 0; $i < $totalCells; $i++) {
            if ($i === $freeIndex) {
                $cells[] = null;

                continue;
            }
            $term = $picked[$index] ?? null;
            $index++;
            if ($term === null) {
                $cells[] = ['id' => '', 'label' => '—'];

                continue;
            }
            $cells[] = [
                'id' => (string) $term['id'],
                'label' => $this->localized($term['term'] ?? [], $locale),
            ];
        }

        return [
            'seed' => $seed,
            'cells' => $cells,
            'categories' => $categoryIds,
            'size' => $size,
        ];
    }

    /**
     * Build several distinct bingo cards from a shared base seed.
     *
     * @param  list<string>|null  $categoryIds
     * @return array{baseSeed: string, cards: list<array{seed: string, cells: list<array{id: string, label: string}|null>, number: int, size: int}>, categories: list<string>|null, size: int}
     */
    public function bingoCards(
        string $locale = 'en',
        int $cardCount = self::DEFAULT_BINGO_CARDS,
        ?string $baseSeed = null,
        ?array $categoryIds = null,
        int $size = self::BINGO_SIZE_CLASSIC,
    ): array {
        $cardCount = self::clampBingoCardCount($cardCount);
        $baseSeed = $baseSeed !== null && $baseSeed !== '' ? $baseSeed : bin2hex(random_bytes(8));
        $categoryIds = self::normalizeCategoryIds($categoryIds);
        $size = self::normalizeBingoSize($size);
        $cards = [];
        for ($i = 1; $i <= $cardCount; $i++) {
            $cardSeed = $cardCount === 1 ? $baseSeed : $baseSeed.'#'.$i;
            $card = $this->bingoCard($locale, $cardSeed, $categoryIds, $size);
            $cards[] = [
                'seed' => $card['seed'],
                'cells' => $card['cells'],
                'number' => $i,
                'size' => $size,
            ];
        }

        return [
            'baseSeed' => $baseSeed,
            'cards' => $cards,
            'categories' => $categoryIds,
            'size' => $size,
        ];
    }

    /**
     * @param  list<array<string, mixed>>  $terms
     * @param  array<string, list<array<string, mixed>>>  $byCategory
     * @param  callable(): float  $rng
     * @return array<string, mixed>|null
     */
    private function buildSingleQuestion(array $terms, array $byCategory, string $locale, callable $rng): ?array
    {
        if (count($terms) < 4) {
            return null;
        }

        $correct = $terms[(int) floor($rng() * count($terms))];
        $cat = (string) ($correct['category'] ?? '');
        $pool = $byCategory[$cat] ?? [];
        $distractors = [];

        $sameCat = array_values(array_filter(
            $pool,
            static fn (array $t): bool => ($t['id'] ?? '') !== ($correct['id'] ?? ''),
        ));
        $this->shuffleInPlace($sameCat, $rng);
        foreach ($sameCat as $candidate) {
            if (count($distractors) >= 3) {
                break;
            }
            $distractors[] = $candidate;
        }

        if (count($distractors) < 3) {
            $others = array_values(array_filter(
                $terms,
                static function (array $t) use ($correct, $distractors): bool {
                    $id = (string) ($t['id'] ?? '');
                    if ($id === '' || $id === (string) ($correct['id'] ?? '')) {
                        return false;
                    }
                    foreach ($distractors as $d) {
                        if ((string) ($d['id'] ?? '') === $id) {
                            return false;
                        }
                    }

                    return true;
                },
            ));
            $this->shuffleInPlace($others, $rng);
            foreach ($others as $candidate) {
                if (count($distractors) >= 3) {
                    break;
                }
                $distractors[] = $candidate;
            }
        }

        if (count($distractors) < 3) {
            return null;
        }

        $promptKind = $rng() < 0.5 ? 'def_to_term' : 'term_to_def';
        $choices = array_merge([$correct], array_slice($distractors, 0, 3));
        $this->shuffleInPlace($choices, $rng);

        if ($promptKind === 'def_to_term') {
            $stem = $locale === 'de'
                ? 'Welcher Begriff passt zu dieser Definition?'
                : 'Which term matches this definition?';
            $prompt = $this->localized($correct['definition'] ?? [], $locale);
            $choiceItems = array_map(
                fn (array $t): array => [
                    'id' => (string) $t['id'],
                    'label' => $this->localized($t['term'] ?? [], $locale),
                ],
                $choices,
            );
        } else {
            $termLabel = $this->localized($correct['term'] ?? [], $locale);
            $stem = $locale === 'de'
                ? 'Welche Definition passt zu „'.$termLabel.'“?'
                : 'Which definition matches “'.$termLabel.'”?';
            $prompt = '';
            $choiceItems = array_map(
                fn (array $t): array => [
                    'id' => (string) $t['id'],
                    'label' => $this->localized($t['definition'] ?? [], $locale),
                ],
                $choices,
            );
        }

        return [
            'type' => 'single',
            'promptKind' => $promptKind,
            'stem' => $stem,
            'prompt' => $prompt,
            'choices' => $choiceItems,
            'correctIds' => [(string) $correct['id']],
        ];
    }

    /**
     * @param  array<string, list<array<string, mixed>>>  $byCategory
     * @param  list<string>  $multiEligible
     * @param  array<string, mixed>  $categories
     * @param  callable(): float  $rng
     * @return array<string, mixed>|null
     */
    private function buildMultiQuestion(
        array $byCategory,
        array $multiEligible,
        array $categories,
        string $locale,
        callable $rng,
    ): ?array {
        if ($multiEligible === []) {
            return null;
        }

        $catId = $multiEligible[(int) floor($rng() * count($multiEligible))];
        $inCat = $byCategory[$catId] ?? [];
        if (count($inCat) < 2) {
            return null;
        }

        $correctCount = count($inCat) >= 3 && $rng() < 0.5 ? 3 : 2;
        $correctCount = min($correctCount, count($inCat));

        $this->shuffleInPlace($inCat, $rng);
        $correctTerms = array_slice($inCat, 0, $correctCount);

        $distractorPool = [];
        foreach ($byCategory as $otherCat => $group) {
            if ($otherCat === $catId) {
                continue;
            }
            foreach ($group as $term) {
                $distractorPool[] = $term;
            }
        }
        if (count($distractorPool) < 6 - $correctCount) {
            return null;
        }

        $this->shuffleInPlace($distractorPool, $rng);
        $distractors = array_slice($distractorPool, 0, 6 - $correctCount);
        $choices = array_merge($correctTerms, $distractors);
        $this->shuffleInPlace($choices, $rng);

        $catLabel = $categories[$catId] ?? null;
        $categoryName = is_array($catLabel)
            ? $this->localized($catLabel, $locale)
            : $catId;

        $prompt = $locale === 'de'
            ? 'Welche Begriffe gehören zur Kategorie „'.$categoryName.'“?'
            : 'Which terms belong to the category “'.$categoryName.'”?';

        return [
            'type' => 'multi',
            'promptKind' => 'category_members',
            'stem' => $prompt,
            'prompt' => '',
            'categoryId' => $catId,
            'choices' => array_map(
                fn (array $t): array => [
                    'id' => (string) $t['id'],
                    'label' => $this->localized($t['term'] ?? [], $locale),
                ],
                $choices,
            ),
            'correctIds' => array_values(array_map(
                static fn (array $t): string => (string) $t['id'],
                $correctTerms,
            )),
        ];
    }

    /**
     * @param  array{de?: string, en?: string}|array<string, mixed>  $localized
     */
    private function localized(array $localized, string $locale): string
    {
        $primary = trim((string) ($localized[$locale] ?? ''));
        if ($primary !== '') {
            return $primary;
        }

        return trim((string) ($localized['en'] ?? $localized['de'] ?? ''));
    }

    /**
     * @return callable(): float
     */
    private function seededRng(string $seed): callable
    {
        $state = unpack('N*', hash('sha256', $seed, true));
        if ($state === false || $state === []) {
            $state = [1, 2, 3, 4];
        }
        $a = (int) ($state[1] ?? 1);
        $b = (int) ($state[2] ?? 2);
        $c = (int) ($state[3] ?? 3);
        $d = (int) ($state[4] ?? 4);

        return static function () use (&$a, &$b, &$c, &$d): float {
            $a = ($a ^ ($a << 11)) & 0xFFFFFFFF;
            $a ^= ($a >> 8);
            $t = $a;
            $a = $b;
            $b = $c;
            $c = $d;
            $d = ($d ^ ($d >> 19) ^ $t ^ ($t >> 8)) & 0xFFFFFFFF;

            return ($d & 0x7FFFFFFF) / 0x7FFFFFFF;
        };
    }

    /**
     * @param  list<mixed>  $items
     * @param  callable(): float  $rng
     */
    private function shuffleInPlace(array &$items, callable $rng): void
    {
        $n = count($items);
        for ($i = $n - 1; $i > 0; $i--) {
            $j = (int) floor($rng() * ($i + 1));
            [$items[$i], $items[$j]] = [$items[$j], $items[$i]];
        }
    }
}
