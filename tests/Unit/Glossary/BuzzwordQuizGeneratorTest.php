<?php

namespace Tests\Unit\Glossary;

use App\Glossary\BuzzwordQuizGenerator;
use Tests\TestCase;

class BuzzwordQuizGeneratorTest extends TestCase
{
    public function test_generate_returns_mixed_single_and_multi_questions(): void
    {
        $generator = new BuzzwordQuizGenerator();
        $bundle = $generator->generate('en', 10, 'test-seed-alpha');

        $this->assertSame('test-seed-alpha', $bundle['seed']);
        $this->assertCount(10, $bundle['questions']);

        $types = [];
        foreach ($bundle['questions'] as $question) {
            $this->assertContains($question['type'], ['single', 'multi']);
            $stemOrPrompt = trim((string) ($question['stem'] ?? '').(string) ($question['prompt'] ?? ''));
            $this->assertNotSame('', $stemOrPrompt);
            $this->assertIsArray($question['choices']);
            $this->assertIsArray($question['correctIds']);
            $types[$question['type']] = true;

            $choiceIds = array_map(
                static fn (array $c): string => (string) ($c['id'] ?? ''),
                $question['choices'],
            );
            $this->assertSame(count($choiceIds), count(array_unique($choiceIds)));

            foreach ($question['correctIds'] as $correctId) {
                $this->assertContains($correctId, $choiceIds);
            }

            if ($question['type'] === 'single') {
                $this->assertCount(1, $question['correctIds']);
                $this->assertCount(4, $question['choices']);
                $this->assertContains($question['promptKind'], ['def_to_term', 'term_to_def']);
                $this->assertNotSame('', (string) ($question['stem'] ?? ''));
                $this->assertStringContainsString('?', (string) $question['stem']);
            } else {
                $this->assertGreaterThanOrEqual(2, count($question['correctIds']));
                $this->assertCount(6, $question['choices']);
                $this->assertSame('category_members', $question['promptKind']);
                $this->assertStringContainsString('?', (string) ($question['stem'] ?? $question['prompt'] ?? ''));
            }
        }

        $this->assertArrayHasKey('single', $types);
        $this->assertArrayHasKey('multi', $types);
    }

    public function test_same_seed_is_reproducible(): void
    {
        $generator = new BuzzwordQuizGenerator();
        $a = $generator->generate('de', 8, 'repro-seed');
        $b = $generator->generate('de', 8, 'repro-seed');

        $this->assertSame($a['questions'], $b['questions']);
    }

    public function test_exact_match_scoring(): void
    {
        $this->assertTrue(BuzzwordQuizGenerator::isCorrect(['a', 'b'], ['b', 'a']));
        $this->assertFalse(BuzzwordQuizGenerator::isCorrect(['a'], ['a', 'b']));
        $this->assertFalse(BuzzwordQuizGenerator::isCorrect(['a', 'b', 'c'], ['a', 'b']));
        $this->assertTrue(BuzzwordQuizGenerator::isCorrect(['x'], ['x']));
    }

    public function test_bingo_card_has_free_center_and_twenty_four_terms(): void
    {
        $generator = new BuzzwordQuizGenerator();
        $card = $generator->bingoCard('en', 'bingo-seed');

        $this->assertSame('bingo-seed', $card['seed']);
        $this->assertSame(5, $card['size']);
        $this->assertCount(25, $card['cells']);
        $this->assertNull($card['cells'][12]);

        $termCells = array_values(array_filter($card['cells'], static fn ($c) => is_array($c)));
        $this->assertCount(24, $termCells);
        foreach ($termCells as $cell) {
            $this->assertNotSame('', (string) ($cell['id'] ?? ''));
            $this->assertNotSame('', (string) ($cell['label'] ?? ''));
        }
    }

    public function test_bingo_mini_card_is_three_by_three_with_free_center(): void
    {
        $generator = new BuzzwordQuizGenerator();
        $card = $generator->bingoCard('de', 'bingo-3x3', null, 3);

        $this->assertSame(3, $card['size']);
        $this->assertCount(9, $card['cells']);
        $this->assertNull($card['cells'][4]);
        $this->assertSame(3, BuzzwordQuizGenerator::normalizeBingoSize(3));
        $this->assertSame(5, BuzzwordQuizGenerator::normalizeBingoSize(99));
        $this->assertSame(8, BuzzwordQuizGenerator::bingoTermSlots(3));

        $termCells = array_values(array_filter($card['cells'], static fn ($c) => is_array($c)));
        $this->assertCount(8, $termCells);
    }

    public function test_question_count_is_clamped_and_honored(): void
    {
        $generator = new BuzzwordQuizGenerator();
        $bundle = $generator->generate('en', 20, 'count-seed');
        $this->assertCount(20, $bundle['questions']);

        $clampedLow = $generator->generate('en', 1, 'count-low');
        $this->assertCount(BuzzwordQuizGenerator::MIN_COUNT, $clampedLow['questions']);

        $clampedHigh = $generator->generate('en', 999, 'count-high');
        $this->assertCount(BuzzwordQuizGenerator::MAX_COUNT, $clampedHigh['questions']);
    }

    public function test_bingo_cards_are_distinct_for_count(): void
    {
        $generator = new BuzzwordQuizGenerator();
        $bundle = $generator->bingoCards('en', 3, 'multi-bingo');

        $this->assertSame('multi-bingo', $bundle['baseSeed']);
        $this->assertCount(3, $bundle['cards']);
        $this->assertNotSame($bundle['cards'][0]['cells'], $bundle['cards'][1]['cells']);
        $this->assertNotSame($bundle['cards'][1]['cells'], $bundle['cards'][2]['cells']);
        $this->assertSame(1, $bundle['cards'][0]['number']);
        $this->assertSame(3, $bundle['cards'][2]['number']);
    }

    public function test_category_filter_limits_quiz_and_bingo_pool(): void
    {
        $generator = new BuzzwordQuizGenerator();

        $this->assertNull(BuzzwordQuizGenerator::normalizeCategoryIds(null));
        $this->assertNull(BuzzwordQuizGenerator::normalizeCategoryIds([]));
        $this->assertNull(BuzzwordQuizGenerator::normalizeCategoryIds(['nope']));
        $this->assertSame(['ai', 'bi'], BuzzwordQuizGenerator::normalizeCategoryIds('ai,bi'));
        $this->assertSame(['ai'], BuzzwordQuizGenerator::normalizeCategoryIds(['ai', 'ai', 'missing']));

        $aiTerms = $generator->usableTerms(['ai']);
        $this->assertNotEmpty($aiTerms);
        foreach ($aiTerms as $term) {
            $this->assertSame('ai', $term['category']);
        }

        $quiz = $generator->generate('en', 8, 'cat-filter-quiz', ['ai']);
        $this->assertSame(['ai'], $quiz['categories']);
        $this->assertGreaterThanOrEqual(BuzzwordQuizGenerator::MIN_COUNT, count($quiz['questions']));
        foreach ($quiz['questions'] as $question) {
            $this->assertSame('single', $question['type']);
            foreach ($question['correctIds'] as $correctId) {
                $match = null;
                foreach ($aiTerms as $term) {
                    if (($term['id'] ?? '') === $correctId) {
                        $match = $term;
                        break;
                    }
                }
                $this->assertNotNull($match);
            }
        }

        $bingo = $generator->bingoCard('en', 'cat-filter-bingo', ['ai']);
        $this->assertSame(['ai'], $bingo['categories']);
        foreach ($bingo['cells'] as $cell) {
            if (! is_array($cell) || ($cell['id'] ?? '') === '') {
                continue;
            }
            $found = false;
            foreach ($aiTerms as $term) {
                if (($term['id'] ?? '') === $cell['id']) {
                    $found = true;
                    break;
                }
            }
            $this->assertTrue($found, 'Bingo cell outside filtered categories: '.$cell['id']);
        }
    }
}
