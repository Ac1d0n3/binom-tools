<?php

namespace App\Catalog;

use App\Playbooks\PlaybookRepository;
use App\Support\ToolsNav;

final class SearchIndex
{
    public function __construct(
        private readonly PlaybookRepository $playbooks,
    ) {}

    /**
     * @return list<array{
     *   type: string,
     *   id: string,
     *   title: array{de: string, en: string},
     *   description: array{de: string, en: string},
     *   route: string,
     *   params: array<string, string>,
     *   search_text: string,
     *   icon: string
     * }>
     */
    public function all(): array
    {
        return array_values(array_merge(
            $this->storyEntries(),
            $this->seriesEntries(),
            $this->toolEntries(),
            $this->resourceEntries(),
            $this->supplierEntries(),
            $this->complianceEntries(),
            $this->radarEntries(),
            $this->glossaryEntries(),
            $this->learningPathEntries(),
            $this->roleEntries(),
        ));
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function search(string $query, ?string $type = null, int $limit = 60): array
    {
        $normalized = $this->normalize($query);
        $entries = $this->all();

        if ($type !== null && $type !== '' && $type !== 'all') {
            $entries = array_values(array_filter(
                $entries,
                static fn (array $entry): bool => ($entry['type'] ?? '') === $type,
            ));
        }

        if ($normalized === '') {
            return array_slice($entries, 0, max(0, $limit));
        }

        $tokens = preg_split('/\s+/', $normalized) ?: [];
        $tokens = array_values(array_filter($tokens, static fn (string $token): bool => $token !== ''));

        $scored = [];
        foreach ($entries as $entry) {
            $haystack = (string) ($entry['search_text'] ?? '');
            $score = 0;

            foreach ($tokens as $token) {
                if (! str_contains($haystack, $token)) {
                    continue 2;
                }
            }

            $titleEn = $this->normalize((string) ($entry['title']['en'] ?? ''));
            $titleDe = $this->normalize((string) ($entry['title']['de'] ?? ''));
            if (str_contains($titleEn, $normalized) || str_contains($titleDe, $normalized)) {
                $score += 40;
            }

            foreach ($tokens as $token) {
                if (str_contains($titleEn, $token) || str_contains($titleDe, $token)) {
                    $score += 12;
                }
                $score += 4;
            }

            $entry['_score'] = $score;
            $scored[] = $entry;
        }

        usort($scored, static function (array $a, array $b): int {
            $scoreDiff = ((int) ($b['_score'] ?? 0)) <=> ((int) ($a['_score'] ?? 0));
            if ($scoreDiff !== 0) {
                return $scoreDiff;
            }

            return strcasecmp((string) ($a['title']['en'] ?? ''), (string) ($b['title']['en'] ?? ''));
        });

        return array_map(static function (array $entry): array {
            unset($entry['_score']);

            return $entry;
        }, array_slice($scored, 0, max(0, $limit)));
    }

    /**
     * @return list<string>
     */
    public function types(): array
    {
        return ['story', 'series', 'tool', 'resource', 'supplier', 'compliance', 'radar', 'glossary', 'path', 'role'];
    }

    private function normalize(string $value): string
    {
        return mb_strtolower(trim($value));
    }

    /**
     * @param  array{de?: string, en?: string}|string|null  $value
     * @return array{de: string, en: string}
     */
    private function bilingual(array|string|null $value, string $fallback = ''): array
    {
        if (is_string($value)) {
            $text = trim($value);

            return ['de' => $text !== '' ? $text : $fallback, 'en' => $text !== '' ? $text : $fallback];
        }

        $en = trim((string) ($value['en'] ?? ''));
        $de = trim((string) ($value['de'] ?? ''));
        if ($en === '') {
            $en = $de !== '' ? $de : $fallback;
        }
        if ($de === '') {
            $de = $en;
        }

        return ['de' => $de, 'en' => $en];
    }

    /**
     * @param  list<string>  $parts
     */
    private function searchText(array $parts): string
    {
        return $this->normalize(implode(' ', array_filter($parts, static fn ($part): bool => is_string($part) && trim($part) !== '')));
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function storyEntries(): array
    {
        $entries = [];
        foreach ($this->playbooks->allForIndex() as $item) {
            $slug = (string) ($item['slug'] ?? '');
            if ($slug === '') {
                continue;
            }

            $title = [
                'en' => (string) ($item['locales']['en']['title'] ?? $item['locales']['de']['title'] ?? $slug),
                'de' => (string) ($item['locales']['de']['title'] ?? $item['locales']['en']['title'] ?? $slug),
            ];
            $description = [
                'en' => (string) ($item['locales']['en']['description'] ?? ''),
                'de' => (string) ($item['locales']['de']['description'] ?? ''),
            ];
            $tags = is_array($item['tags'] ?? null) ? $item['tags'] : [];
            $products = is_array($item['products'] ?? null) ? $item['products'] : [];

            $entries[] = [
                'type' => 'story',
                'id' => $slug,
                'title' => $title,
                'description' => $description,
                'route' => 'playbooks.show',
                'params' => ['slug' => $slug],
                'query' => null,
                'search_text' => $this->searchText([
                    $title['en'],
                    $title['de'],
                    $description['en'],
                    $description['de'],
                    $slug,
                    implode(' ', $tags),
                    implode(' ', $products),
                ]),
                'icon' => 'fa-book-open',
            ];
        }

        return $entries;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function seriesEntries(): array
    {
        $entries = [];
        foreach ($this->playbooks->allSeries() as $series) {
            $entries[] = [
                'type' => 'series',
                'id' => $series->id,
                'title' => ['de' => $series->titleDe, 'en' => $series->titleEn],
                'description' => [
                    'de' => $series->partCount().' Teile',
                    'en' => $series->partCount().' parts',
                ],
                'route' => 'playbooks.series',
                'params' => ['seriesId' => $series->id],
                'query' => null,
                'search_text' => $this->searchText([
                    $series->titleDe,
                    $series->titleEn,
                    $series->id,
                    implode(' ', $series->products ?? []),
                ]),
                'icon' => 'fa-layer-group',
            ];
        }

        return $entries;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function toolEntries(): array
    {
        $entries = [];
        foreach (ToolsNav::withRegisteredRoutes(config('tools.nav', [])) as $item) {
            $id = (string) ($item['id'] ?? '');
            $route = (string) ($item['route'] ?? '');
            if ($id === '' || $route === '') {
                continue;
            }

            $title = $this->bilingual($item['label'] ?? null, $id);
            $description = $this->bilingual($item['description'] ?? null);
            $for = is_array($item['for'] ?? null) ? $item['for'] : [];

            $entries[] = [
                'type' => 'tool',
                'id' => $id,
                'title' => $title,
                'description' => $description,
                'route' => $route,
                'params' => [],
                'query' => null,
                'search_text' => $this->searchText([
                    $title['en'],
                    $title['de'],
                    $description['en'],
                    $description['de'],
                    $id,
                    implode(' ', array_map('strval', $for)),
                ]),
                'icon' => is_string($item['icon'] ?? null) ? (string) $item['icon'] : 'fa-screwdriver-wrench',
            ];
        }

        return $entries;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function resourceEntries(): array
    {
        $entries = [];
        foreach (config('vendor-resources.products', []) as $product) {
            if (! is_array($product)) {
                continue;
            }
            $id = (string) ($product['id'] ?? '');
            if ($id === '') {
                continue;
            }

            $title = $this->bilingual($product['label'] ?? $product['name'] ?? null, $id);
            $description = $this->bilingual($product['purpose'] ?? $product['description'] ?? null);

            $entries[] = [
                'type' => 'resource',
                'id' => $id,
                'title' => $title,
                'description' => $description,
                'route' => 'resources.index',
                'params' => [],
                'query' => ['q' => $title['en']],
                'search_text' => $this->searchText([
                    $title['en'],
                    $title['de'],
                    $description['en'],
                    $description['de'],
                    $id,
                    (string) ($product['vendor'] ?? ''),
                    (string) ($product['family'] ?? ''),
                ]),
                'icon' => 'fa-link',
            ];
        }

        return $entries;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function supplierEntries(): array
    {
        $entries = [];
        foreach (config('suppliers.products', []) as $product) {
            if (! is_array($product)) {
                continue;
            }
            $id = (string) ($product['id'] ?? '');
            if ($id === '') {
                continue;
            }

            $title = $this->bilingual($product['label'] ?? null, $id);
            $description = $this->bilingual($product['shortPurpose'] ?? $product['description'] ?? null);

            $entries[] = [
                'type' => 'supplier',
                'id' => $id,
                'title' => $title,
                'description' => $description,
                'route' => 'suppliers.show',
                'params' => ['slug' => $id],
                'query' => null,
                'search_text' => $this->searchText([
                    $title['en'],
                    $title['de'],
                    $description['en'],
                    $description['de'],
                    $id,
                    (string) ($product['domain'] ?? ''),
                ]),
                'icon' => 'fa-database',
            ];
        }

        return $entries;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function complianceEntries(): array
    {
        $entries = [];
        foreach (config('compliance.items', []) as $item) {
            if (! is_array($item)) {
                continue;
            }
            $slug = (string) ($item['id'] ?? $item['slug'] ?? '');
            if ($slug === '') {
                continue;
            }

            $title = $this->bilingual($item['label'] ?? $item['title'] ?? null, $slug);
            $description = $this->bilingual($item['shortPurpose'] ?? $item['summary'] ?? $item['description'] ?? null);

            $entries[] = [
                'type' => 'compliance',
                'id' => $slug,
                'title' => $title,
                'description' => $description,
                'route' => 'compliance.show',
                'params' => ['slug' => $slug],
                'query' => null,
                'search_text' => $this->searchText([
                    $title['en'],
                    $title['de'],
                    $description['en'],
                    $description['de'],
                    $slug,
                    (string) ($item['category'] ?? ''),
                    (string) ($item['region'] ?? ''),
                ]),
                'icon' => 'fa-scale-balanced',
            ];
        }

        return $entries;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function radarEntries(): array
    {
        $entries = [];
        foreach (config('governance-radar.sources', []) as $source) {
            if (! is_array($source)) {
                continue;
            }
            $id = (string) ($source['id'] ?? '');
            if ($id === '') {
                continue;
            }

            $title = $this->bilingual(
                [
                    'en' => (string) ($source['short_name'] ?? $source['name'] ?? $id),
                    'de' => (string) ($source['short_name'] ?? $source['name'] ?? $id),
                ],
                $id,
            );
            $description = $this->bilingual(
                [
                    'en' => (string) ($source['note'] ?? $source['name'] ?? ''),
                    'de' => (string) ($source['note'] ?? $source['name'] ?? ''),
                ],
            );

            $entries[] = [
                'type' => 'radar',
                'id' => $id,
                'title' => $title,
                'description' => $description,
                'route' => 'governance.radar',
                'params' => [],
                'query' => ['q' => $title['en']],
                'search_text' => $this->searchText([
                    $title['en'],
                    $title['de'],
                    $description['en'],
                    $description['de'],
                    (string) ($source['name'] ?? ''),
                    $id,
                    (string) ($source['type'] ?? ''),
                    implode(' ', is_array($source['topics'] ?? null) ? $source['topics'] : []),
                ]),
                'icon' => 'fa-satellite-dish',
            ];
        }

        return $entries;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function glossaryEntries(): array
    {
        $entries = [];
        foreach (config('glossary.terms', []) as $term) {
            if (! is_array($term)) {
                continue;
            }
            $id = (string) ($term['id'] ?? '');
            if ($id === '') {
                continue;
            }

            $title = $this->bilingual($term['term'] ?? null, $id);
            $description = $this->bilingual($term['definition'] ?? null);

            $entries[] = [
                'type' => 'glossary',
                'id' => $id,
                'title' => $title,
                'description' => $description,
                'route' => 'glossary.show',
                'params' => ['slug' => $id],
                'query' => null,
                'search_text' => $this->searchText([
                    $title['en'],
                    $title['de'],
                    $description['en'],
                    $description['de'],
                    $id,
                    implode(' ', is_array($term['aliases'] ?? null) ? $term['aliases'] : []),
                ]),
                'icon' => 'fa-book',
            ];
        }

        return $entries;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function learningPathEntries(): array
    {
        $entries = [];
        foreach (config('learning-paths.paths', []) as $path) {
            if (! is_array($path)) {
                continue;
            }
            $id = (string) ($path['id'] ?? '');
            if ($id === '') {
                continue;
            }

            $title = $this->bilingual($path['title'] ?? null, $id);
            $description = $this->bilingual($path['lead'] ?? $path['description'] ?? null);

            $entries[] = [
                'type' => 'path',
                'id' => $id,
                'title' => $title,
                'description' => $description,
                'route' => 'learning-paths.show',
                'params' => ['slug' => $id],
                'query' => null,
                'search_text' => $this->searchText([
                    $title['en'],
                    $title['de'],
                    $description['en'],
                    $description['de'],
                    $id,
                    (string) ($path['audience']['en'] ?? ''),
                    (string) ($path['audience']['de'] ?? ''),
                ]),
                'icon' => 'fa-route',
            ];
        }

        return $entries;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function roleEntries(): array
    {
        $entries = [];
        foreach (config('roles.roles', []) as $role) {
            if (! is_array($role)) {
                continue;
            }
            $id = (string) ($role['id'] ?? '');
            if ($id === '') {
                continue;
            }

            $title = $this->bilingual($role['title'] ?? null, $id);
            $description = $this->bilingual($role['lead'] ?? null);

            $entries[] = [
                'type' => 'role',
                'id' => $id,
                'title' => $title,
                'description' => $description,
                'route' => 'roles.show',
                'params' => ['slug' => $id],
                'query' => null,
                'search_text' => $this->searchText([
                    $title['en'],
                    $title['de'],
                    $description['en'],
                    $description['de'],
                    $id,
                    (string) ($role['persona'] ?? ''),
                    (string) ($role['glossaryId'] ?? ''),
                ]),
                'icon' => 'fa-user-group',
            ];
        }

        return $entries;
    }
}
