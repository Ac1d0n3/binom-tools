<?php

namespace App\Http\Controllers\Glossary;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class GlossaryController extends Controller
{
    public function index(): View
    {
        /** @var array<string, array{de: string, en: string}> $categories */
        $categories = config('glossary.categories', []);

        /** @var list<array<string, mixed>> $terms */
        $terms = config('glossary.terms', []);

        usort($terms, static function (array $a, array $b): int {
            $order = ((int) ($a['order'] ?? 0)) <=> ((int) ($b['order'] ?? 0));
            if ($order !== 0) {
                return $order;
            }

            return strcasecmp((string) ($a['term']['en'] ?? $a['id'] ?? ''), (string) ($b['term']['en'] ?? $b['id'] ?? ''));
        });

        $availableCategories = [];
        foreach ($terms as $term) {
            $category = is_string($term['category'] ?? null) ? $term['category'] : '';
            if ($category !== '' && isset($categories[$category]) && ! in_array($category, $availableCategories, true)) {
                $availableCategories[] = $category;
            }
        }

        $categoryOrder = array_keys($categories);
        usort($availableCategories, static function (string $a, string $b) use ($categoryOrder): int {
            $posA = array_search($a, $categoryOrder, true);
            $posB = array_search($b, $categoryOrder, true);

            return ($posA === false ? PHP_INT_MAX : $posA) <=> ($posB === false ? PHP_INT_MAX : $posB);
        });

        $lettersEn = [];
        $lettersDe = [];
        foreach ($terms as $term) {
            $termEn = (string) ($term['term']['en'] ?? $term['id'] ?? '');
            $termDe = (string) ($term['term']['de'] ?? $termEn);
            $lettersEn[$this->glossaryLetter($termEn)] = true;
            $lettersDe[$this->glossaryLetter($termDe)] = true;
        }

        return view('glossary.index', [
            'terms' => $terms,
            'categories' => $categories,
            'availableCategories' => $availableCategories,
            'availableLettersEn' => array_keys($lettersEn),
            'availableLettersDe' => array_keys($lettersDe),
            'azLetters' => array_merge(range('A', 'Z'), ['#']),
        ]);
    }

    /**
     * First index letter for A–Z filter (umlauts fold to A/O/U).
     */
    private function glossaryLetter(string $term): string
    {
        $trimmed = trim($term);
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

        if (preg_match('/^[A-Z]$/', $folded) === 1) {
            return $folded;
        }

        return '#';
    }

    public function show(Request $request): View|Response
    {
        $slug = (string) $request->route('slug');

        /** @var list<array<string, mixed>> $terms */
        $terms = config('glossary.terms', []);

        $item = null;
        foreach ($terms as $term) {
            if (($term['id'] ?? '') === $slug) {
                $item = $term;
                break;
            }
        }

        if ($item === null) {
            abort(404);
        }

        /** @var array<string, array{de: string, en: string}> $categories */
        $categories = config('glossary.categories', []);

        return view('glossary.show', [
            'item' => $item,
            'categories' => $categories,
            'relatedLinks' => $this->resolveRelatedLinks(is_array($item['related'] ?? null) ? $item['related'] : []),
        ]);
    }

    /**
     * @param  list<array<string, mixed>>  $related
     * @return list<array{label: array{de: string, en: string}, href: string, kind: string}>
     */
    private function resolveRelatedLinks(array $related): array
    {
        $links = [];
        foreach ($related as $entry) {
            if (! is_array($entry)) {
                continue;
            }

            $kind = (string) ($entry['type'] ?? '');
            $id = (string) ($entry['id'] ?? '');
            $label = [
                'en' => (string) ($entry['label']['en'] ?? $entry['label'] ?? $id),
                'de' => (string) ($entry['label']['de'] ?? $entry['label']['en'] ?? $entry['label'] ?? $id),
            ];

            $href = match ($kind) {
                'story' => $id !== '' ? locale_route('playbooks.show', ['slug' => $id]) : null,
                'series' => $id !== '' ? locale_route('playbooks.series', ['seriesId' => $id]) : null,
                'tool' => is_string($entry['route'] ?? null) ? locale_route((string) $entry['route']) : null,
                'compliance' => $id !== '' ? locale_route('compliance.show', ['slug' => $id]) : null,
                'path' => $id !== '' ? locale_route('learning-paths.show', ['slug' => $id]) : null,
                'glossary' => $id !== '' ? locale_route('glossary.show', ['slug' => $id]) : null,
                'route' => is_string($entry['route'] ?? null)
                    ? locale_route(
                        (string) $entry['route'],
                        is_array($entry['params'] ?? null) ? $entry['params'] : [],
                    )
                    : null,
                default => null,
            };

            if (! is_string($href) || $href === '') {
                continue;
            }

            $links[] = [
                'label' => $label,
                'href' => $href,
                'kind' => $kind !== '' ? $kind : 'link',
            ];
        }

        return $links;
    }
}
