<?php

namespace App\SprintPlanner;

use Illuminate\Support\Collection;

final class SprintPlanRepository
{
    private const LOCALES = ['de', 'en'];

    /** @var array<string, SprintPlan> */
    private static array $memoryCache = [];

    public function __construct(
        private readonly SprintPlanFrontmatterParser $frontmatterParser,
        private readonly SprintFenceParser $fenceParser,
        private readonly SprintPlanValidator $validator,
    ) {}

    /**
     * @return list<SprintPlan>
     */
    public function all(): array
    {
        return $this->sortedSlugs()
            ->map(fn (string $slug): SprintPlan => $this->find($slug))
            ->filter()
            ->values()
            ->all();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function allForIndex(): array
    {
        return array_map(
            static fn (SprintPlan $plan): array => $plan->toIndexArray(),
            array_values(array_filter($this->all(), static fn (SprintPlan $p): bool => ! $p->hasErrors())),
        );
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function allForClient(): array
    {
        return array_map(
            static fn (SprintPlan $plan): array => $plan->toClientArray(),
            array_values(array_filter($this->all(), static fn (SprintPlan $p): bool => ! $p->hasErrors())),
        );
    }

    public function find(string $slug): ?SprintPlan
    {
        if (isset(self::$memoryCache[$slug])) {
            return self::$memoryCache[$slug];
        }

        $built = $this->buildPlan($slug);
        if ($built === null) {
            return null;
        }

        self::$memoryCache[$slug] = $built;

        return $built;
    }

    public function clearCache(): void
    {
        self::$memoryCache = [];
    }

    /**
     * @return Collection<int, string>
     */
    private function sortedSlugs(): Collection
    {
        $dir = $this->contentDirectory();
        if (! is_dir($dir)) {
            return collect();
        }

        return collect(glob($dir.'/*.{de,en}.md', GLOB_BRACE) ?: [])
            ->map(function (string $path): ?string {
                $base = basename($path);
                if (! preg_match('/^([a-z0-9-]+)\.(de|en)\.md$/', $base, $m)) {
                    return null;
                }

                return $m[1];
            })
            ->filter()
            ->unique()
            ->sort()
            ->values();
    }

    private function buildPlan(string $slug): ?SprintPlan
    {
        $locales = [];
        $errors = [];
        $warnings = [];
        $sprintsByLocale = [];
        $metaByLocale = [];

        foreach (self::LOCALES as $locale) {
            $path = $this->contentDirectory().'/'.$slug.'.'.$locale.'.md';
            if (! is_file($path)) {
                $errors[] = "Missing template file for locale {$locale}: {$slug}.{$locale}.md";

                continue;
            }

            $raw = file_get_contents($path);
            if ($raw === false) {
                $errors[] = "Unable to read template file: {$slug}.{$locale}.md";

                continue;
            }

            $parsed = $this->frontmatterParser->parse($raw, $slug);
            $meta = $parsed['meta'];
            $fence = $this->fenceParser->parse($parsed['body']);
            $validation = $this->validator->validateLocaleVariant($meta, $fence['sprints']);

            foreach ($fence['errors'] as $error) {
                $errors[] = "[{$locale}] {$error}";
            }
            foreach ($fence['warnings'] as $warning) {
                $warnings[] = "[{$locale}] {$warning}";
            }
            foreach ($validation['errors'] as $error) {
                $errors[] = "[{$locale}] {$error}";
            }
            foreach ($validation['warnings'] as $warning) {
                $warnings[] = "[{$locale}] {$warning}";
            }

            $metaByLocale[$locale] = $meta;
            $sprintsByLocale[$locale] = $fence['sprints'];
            $locales[$locale] = [
                'title' => (string) ($meta['title'] ?? ''),
                'description' => (string) ($meta['description'] ?? ''),
                'category' => $meta['category'] ?? null,
                'sprints' => $this->localizeSprintTexts($fence['sprints']),
            ];
        }

        if (! isset($metaByLocale['de'], $metaByLocale['en'], $sprintsByLocale['de'], $sprintsByLocale['en'])) {
            if ($errors === []) {
                return null;
            }

            return new SprintPlan([
                'slug' => $slug,
                'version' => (int) ($metaByLocale['en']['version'] ?? $metaByLocale['de']['version'] ?? 1),
                'duration' => (int) ($metaByLocale['en']['duration'] ?? $metaByLocale['de']['duration'] ?? 0),
                'unit' => (string) ($metaByLocale['en']['unit'] ?? $metaByLocale['de']['unit'] ?? 'week'),
                'category' => $metaByLocale['en']['category'] ?? $metaByLocale['de']['category'] ?? null,
                'author' => $metaByLocale['en']['author'] ?? $metaByLocale['de']['author'] ?? null,
                'tags' => $metaByLocale['en']['tags'] ?? $metaByLocale['de']['tags'] ?? [],
                'locales' => $locales,
                'sprints' => $this->mergeStructuralSprints($sprintsByLocale),
                'errors' => $errors,
                'warnings' => $warnings,
            ]);
        }

        foreach ($this->validator->compareLocaleStructures(
            $sprintsByLocale['de'],
            $sprintsByLocale['en'],
            $metaByLocale['de'],
            $metaByLocale['en'],
        ) as $error) {
            $errors[] = $error;
        }

        $enMeta = $metaByLocale['en'];

        return new SprintPlan([
            'slug' => $slug,
            'version' => (int) ($enMeta['version'] ?? 1),
            'duration' => (int) ($enMeta['duration'] ?? 0),
            'unit' => (string) ($enMeta['unit'] ?? 'week'),
            'category' => $enMeta['category'] ?? null,
            'author' => $enMeta['author'] ?? null,
            'tags' => is_array($enMeta['tags'] ?? null) ? $enMeta['tags'] : [],
            'locales' => $locales,
            'sprints' => $this->mergeStructuralSprints($sprintsByLocale),
            'errors' => $errors,
            'warnings' => $warnings,
        ]);
    }

    /**
     * Structural sprints (IDs) with EN labels as default; locale texts live under locales.*.sprints.
     *
     * @param  array<string, list<array<string, mixed>>>  $sprintsByLocale
     * @return list<array<string, mixed>>
     */
    private function mergeStructuralSprints(array $sprintsByLocale): array
    {
        $base = $sprintsByLocale['en'] ?? $sprintsByLocale['de'] ?? [];

        return array_map(function (array $sprint): array {
            $stories = $this->normalizeStructuralStories($sprint['stories'] ?? []);

            return [
                'id' => $sprint['id'],
                'number' => $sprint['number'],
                'notes' => (bool) ($sprint['notes'] ?? false),
                'estimated_effort' => $sprint['estimated_effort'] ?? null,
                'stories' => $stories,
                // Derived from `stories` for backward compat with older clients/content.
                'linkedStorySlugs' => array_values(array_map(static fn (array $s): string => $s['slug'], $stories)),
                'flowVariant' => (string) ($sprint['flowVariant'] ?? 'linear'),
                'flowLayout' => (string) ($sprint['flowLayout'] ?? 'vertical'),
                'flowSteps' => array_values(array_map('strval', $sprint['flowSteps'] ?? [])),
                'links' => $this->normalizeStructuralHrefLinks($sprint['links'] ?? []),
                'tasks' => array_map(function (array $t): array {
                    $taskStories = $this->normalizeStructuralStories($t['stories'] ?? []);

                    return [
                        'id' => $t['id'],
                        'assigneeType' => $t['assigneeType'] ?? 'person',
                        'assigneeId' => $t['assigneeId'] ?? null,
                        'stories' => $taskStories,
                        'linkedStorySlugs' => array_values(array_map(static fn (array $s): string => $s['slug'], $taskStories)),
                        'helpLinks' => $this->normalizeStructuralHrefLinks($t['helpLinks'] ?? []),
                        'table' => $this->normalizeStructuralTable($t['table'] ?? null),
                    ];
                }, $sprint['tasks'] ?? []),
                'deliverables' => array_map(function (array $d): array {
                    return [
                        'id' => $d['id'],
                        'stories' => $this->normalizeStructuralStories($d['stories'] ?? []),
                        'helpLinks' => $this->normalizeStructuralHrefLinks($d['helpLinks'] ?? []),
                        'table' => $this->normalizeStructuralTable($d['table'] ?? null),
                    ];
                }, $sprint['deliverables'] ?? []),
                'fields' => array_map(static fn (array $f): array => [
                    'id' => $f['id'],
                    'type' => $f['type'] ?? 'text',
                    'options' => $f['options'] ?? null,
                ], $sprint['fields'] ?? []),
            ];
        }, $base);
    }

    /**
     * @return array{columns: list<array{id: string, label: string}>, rows: list<array{id: string, cells: array<string, string>}>}|null
     */
    private function normalizeStructuralTable(mixed $table): ?array
    {
        if (! is_array($table)) {
            return null;
        }
        $columns = $table['columns'] ?? null;
        if (! is_array($columns) || $columns === []) {
            return null;
        }
        $normalizedColumns = [];
        foreach ($columns as $index => $col) {
            if (! is_array($col)) {
                continue;
            }
            $id = trim((string) ($col['id'] ?? ''));
            $label = trim((string) ($col['label'] ?? $id));
            if ($id === '' && $label === '') {
                continue;
            }
            if ($id === '') {
                $id = 'col_'.($index + 1);
            }
            if ($label === '') {
                $label = $id;
            }
            $normalizedColumns[] = ['id' => $id, 'label' => $label];
        }
        if ($normalizedColumns === []) {
            return null;
        }

        return [
            'columns' => $normalizedColumns,
            'rows' => [],
        ];
    }

    /**
     * @return list<array{slug: string, required: bool}>
     */
    private function normalizeStructuralStories(mixed $stories): array
    {
        if (! is_array($stories)) {
            return [];
        }

        return array_values(array_map(static fn (array $s): array => [
            'slug' => (string) ($s['slug'] ?? ''),
            'required' => (bool) ($s['required'] ?? false),
        ], array_filter(
            $stories,
            static fn ($s): bool => is_array($s) && (string) ($s['slug'] ?? '') !== '',
        )));
    }

    /**
     * @return list<array{href: string}>
     */
    private function normalizeStructuralHrefLinks(mixed $links): array
    {
        if (! is_array($links)) {
            return [];
        }

        return array_values(array_map(static fn (array $link): array => [
            'href' => (string) ($link['href'] ?? ''),
        ], array_filter(
            $links,
            static fn ($link): bool => is_array($link) && ($link['href'] ?? '') !== '',
        )));
    }

    /**
     * @param  list<array<string, mixed>>  $sprints
     * @return list<array<string, mixed>>
     */
    private function localizeSprintTexts(array $sprints): array
    {
        return array_map(static function (array $sprint): array {
            $sprintLinks = [];
            foreach ($sprint['links'] ?? [] as $link) {
                if (! is_array($link) || ($link['href'] ?? '') === '') {
                    continue;
                }
                $sprintLinks[] = [
                    'label' => (string) ($link['label'] ?? ''),
                    'href' => (string) $link['href'],
                ];
            }

            return [
                'id' => $sprint['id'],
                'title' => $sprint['title'] ?? '',
                'goal' => $sprint['goal'] ?? '',
                'description' => $sprint['description'] ?? null,
                'links' => $sprintLinks,
                'tasks' => array_map(static function (array $t): array {
                    $helpLinks = [];
                    foreach ($t['helpLinks'] ?? [] as $link) {
                        if (! is_array($link) || ($link['href'] ?? '') === '') {
                            continue;
                        }
                        $helpLinks[] = [
                            'label' => (string) ($link['label'] ?? ''),
                            'href' => (string) $link['href'],
                        ];
                    }

                    return [
                        'id' => $t['id'],
                        'label' => $t['label'] ?? '',
                        'helpText' => $t['helpText'] ?? null,
                        'helpLinks' => $helpLinks,
                        'demoCode' => $t['demoCode'] ?? null,
                    ];
                }, $sprint['tasks'] ?? []),
                'deliverables' => array_map(static function (array $d): array {
                    $helpLinks = [];
                    foreach ($d['helpLinks'] ?? [] as $link) {
                        if (! is_array($link) || ($link['href'] ?? '') === '') {
                            continue;
                        }
                        $helpLinks[] = [
                            'label' => (string) ($link['label'] ?? ''),
                            'href' => (string) $link['href'],
                        ];
                    }

                    return [
                        'id' => $d['id'],
                        'label' => $d['label'] ?? '',
                        'helpText' => $d['helpText'] ?? null,
                        'helpLinks' => $helpLinks,
                        'demoCode' => $d['demoCode'] ?? null,
                    ];
                }, $sprint['deliverables'] ?? []),
                'fields' => array_map(static fn (array $f): array => [
                    'id' => $f['id'],
                    'label' => $f['label'] ?? '',
                    'placeholder' => $f['placeholder'] ?? null,
                ], $sprint['fields'] ?? []),
            ];
        }, $sprints);
    }

    private function contentDirectory(): string
    {
        return config('sprint-planner.content_path', base_path('content/sprint-plans'));
    }
}
