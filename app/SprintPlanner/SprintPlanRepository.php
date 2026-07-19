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

        return array_map(static function (array $sprint): array {
            return [
                'id' => $sprint['id'],
                'number' => $sprint['number'],
                'notes' => (bool) ($sprint['notes'] ?? false),
                'estimated_effort' => $sprint['estimated_effort'] ?? null,
                'tasks' => array_map(static fn (array $t): array => [
                    'id' => $t['id'],
                    'assigneeType' => $t['assigneeType'] ?? 'person',
                    'assigneeId' => $t['assigneeId'] ?? null,
                ], $sprint['tasks'] ?? []),
                'deliverables' => array_map(static fn (array $d): array => [
                    'id' => $d['id'],
                ], $sprint['deliverables'] ?? []),
                'fields' => array_map(static fn (array $f): array => [
                    'id' => $f['id'],
                    'type' => $f['type'] ?? 'text',
                    'options' => $f['options'] ?? null,
                ], $sprint['fields'] ?? []),
            ];
        }, $base);
    }

    /**
     * @param  list<array<string, mixed>>  $sprints
     * @return list<array<string, mixed>>
     */
    private function localizeSprintTexts(array $sprints): array
    {
        return array_map(static function (array $sprint): array {
            return [
                'id' => $sprint['id'],
                'title' => $sprint['title'] ?? '',
                'goal' => $sprint['goal'] ?? '',
                'description' => $sprint['description'] ?? null,
                'tasks' => array_map(static fn (array $t): array => [
                    'id' => $t['id'],
                    'label' => $t['label'] ?? '',
                ], $sprint['tasks'] ?? []),
                'deliverables' => array_map(static fn (array $d): array => [
                    'id' => $d['id'],
                    'label' => $d['label'] ?? '',
                ], $sprint['deliverables'] ?? []),
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
