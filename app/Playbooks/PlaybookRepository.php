<?php

namespace App\Playbooks;

use App\Support\PlaybookImagePath;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

final class PlaybookRepository
{
    private const LOCALES = ['de', 'en'];

    /** @var array<string, Playbook> */
    private static array $memoryCache = [];

    /** Max story links in the global sidebar (Overview link is separate). */
    public const SIDEBAR_INDEX_LIMIT = 10;

    public function __construct(
        private readonly PlaybookFrontmatterParser $frontmatterParser,
        private readonly PlaybookMarkdownRenderer $markdownRenderer,
    ) {}

    /**
     * @return list<Playbook>
     */
    public function all(): array
    {
        return $this->sortedSlugs()
            ->map(fn (string $slug): Playbook => $this->buildPlaybook($slug))
            ->values()
            ->all();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function allForIndex(): array
    {
        $items = collect($this->sortedSlugs())
            ->map(fn (string $slug): array => $this->buildPlaybook($slug)->toIndexArray())
            ->all();

        return $this->sortIndexEntries($items);
    }

    /**
     * Index entries for grids: standalone stories plus the first part of each series.
     *
     * @return list<array<string, mixed>>
     */
    public function allForIndexCatalog(): array
    {
        $all = collect($this->allForIndex());

        $firstSeriesParts = $all
            ->filter(fn (array $item): bool => is_string($item['seriesId'] ?? null) && $item['seriesId'] !== '')
            ->groupBy('seriesId')
            ->map(
                fn ($group) => $group
                    ->sortBy(fn (array $item): array => [
                        $item['seriesPart'] ?? PHP_INT_MAX,
                        $item['slug'] ?? '',
                    ])
                    ->first(),
            );

        return $this->sortIndexEntries(
            $all
                ->filter(fn (array $item): bool => ! is_string($item['seriesId'] ?? null) || $item['seriesId'] === '')
                ->merge($firstSeriesParts)
                ->values()
                ->all(),
        );
    }

    /**
     * Latest stories for sidebar navigation (newest first).
     *
     * @return list<array<string, mixed>>
     */
    public function latestForIndex(int $limit = self::SIDEBAR_INDEX_LIMIT, ?string $ensureSlug = null): array
    {
        $all = $this->allForIndex();
        $items = collect($all)->take($limit);

        if ($ensureSlug !== null && $ensureSlug !== '') {
            $containsCurrent = $items->contains(
                static fn (array $item): bool => ($item['slug'] ?? '') === $ensureSlug,
            );

            if (! $containsCurrent) {
                $current = collect($all)->first(
                    static fn (array $item): bool => ($item['slug'] ?? '') === $ensureSlug,
                );

                if (is_array($current)) {
                    $items = $items->take(max(0, $limit - 1))->prepend($current);
                }
            }
        }

        return $items->values()->all();
    }

    /**
     * @return list<PlaybookSeriesOverview>
     */
    public function allSeries(): array
    {
        $grouped = collect($this->all())
            ->filter(fn (Playbook $playbook): bool => is_string($playbook->seriesId) && $playbook->seriesId !== '')
            ->groupBy(fn (Playbook $playbook): string => $playbook->seriesId);

        return $grouped
            ->map(fn (Collection $playbooks, string $seriesId): PlaybookSeriesOverview => $this->buildSeriesOverview(
                $seriesId,
                $playbooks->values()->all(),
            ))
            ->sortByDesc(fn (PlaybookSeriesOverview $overview): int => $overview->modifiedAt)
            ->values()
            ->all();
    }

    public function find(string $slug): ?Playbook
    {
        if (! $this->hasSlug($slug)) {
            return null;
        }

        $playbook = $this->buildPlaybook($slug);
        $series = $this->buildSeriesForPlaybook($playbook);
        $pagerMode = $this->seriesPagerMode();

        $prev = null;
        $next = null;

        if ($pagerMode === 'global' || $pagerMode === 'both' || ($pagerMode === 'series' && $series === null)) {
            [$prev, $next] = $this->globalNeighbors($slug);
        }

        if ($pagerMode === 'series' && $series !== null) {
            [$prev, $next] = $this->seriesNeighbors($series);
        }

        return new Playbook(
            slug: $playbook->slug,
            heroUrl: $playbook->heroUrl,
            order: $playbook->order,
            modifiedAt: $playbook->modifiedAt,
            variants: $playbook->variants,
            publishedAt: $playbook->publishedAt,
            seriesId: $playbook->seriesId,
            seriesPart: $playbook->seriesPart,
            series: $series,
            prev: $prev,
            next: $next,
        );
    }

    private function navRefForSlug(string $slug): PlaybookNavRef
    {
        $linked = $this->buildPlaybook($slug);

        return new PlaybookNavRef(
            slug: $slug,
            titleDe: $linked->title('de'),
            titleEn: $linked->title('en'),
        );
    }

    /**
     * @return array{0: ?PlaybookNavRef, 1: ?PlaybookNavRef}
     */
    private function globalNeighbors(string $slug): array
    {
        $ordered = $this->sortedSlugs()->values();
        $index = $ordered->search(fn (string $item): bool => $item === $slug);

        if ($index === false) {
            return [null, null];
        }

        $prev = $index > 0 ? $this->navRefForSlug($ordered[$index - 1]) : null;
        $next = $index < $ordered->count() - 1 ? $this->navRefForSlug($ordered[$index + 1]) : null;

        return [$prev, $next];
    }

    /**
     * @return array{0: ?PlaybookNavRef, 1: ?PlaybookNavRef}
     */
    private function seriesNeighbors(PlaybookSeries $series): array
    {
        $currentIndex = null;

        foreach ($series->parts as $index => $part) {
            if ($part->isCurrent) {
                $currentIndex = $index;
                break;
            }
        }

        if ($currentIndex === null) {
            return [null, null];
        }

        $prev = $currentIndex > 0
            ? $this->navRefForSlug($series->parts[$currentIndex - 1]->slug)
            : null;
        $next = $currentIndex < count($series->parts) - 1
            ? $this->navRefForSlug($series->parts[$currentIndex + 1]->slug)
            : null;

        return [$prev, $next];
    }

    private function buildPlaybook(string $slug): Playbook
    {
        $cacheKey = $this->playbookCacheKey($slug);

        if (isset(self::$memoryCache[$cacheKey])) {
            return self::$memoryCache[$cacheKey];
        }

        $cached = $this->readPlaybookCache($cacheKey);

        if ($cached instanceof Playbook) {
            self::$memoryCache[$cacheKey] = $cached;

            return $cached;
        }

        $playbook = $this->buildPlaybookUncached($slug);
        $this->writePlaybookCache($cacheKey, $playbook);
        self::$memoryCache[$cacheKey] = $playbook;

        return $playbook;
    }

    private function playbookCacheKey(string $slug): string
    {
        $mtime = 0;

        foreach (self::LOCALES as $locale) {
            $path = $this->contentPath($slug, $locale);

            if (is_file($path)) {
                $mtime = max($mtime, filemtime($path) ?: 0);
            }
        }

        // Bust cache when Markdown rendering / flowchart HTML changes.
        $mtime = max($mtime, $this->playbookRendererMtime());

        // Absolute asset URLs in cached HTML depend on APP_URL / asset base.
        $urlFingerprint = sha1((string) config('app.url').'|'.(string) config('app.asset_url'));

        return "playbook:{$slug}:{$mtime}:{$urlFingerprint}";
    }

    private function playbookRendererMtime(): int
    {
        $files = [
            app_path('Playbooks/PlaybookFlowchartFenceExtension.php'),
            app_path('Playbooks/PlaybookFlowchartParser.php'),
            app_path('Playbooks/PlaybookFrontmatterParser.php'),
            app_path('Playbooks/PlaybookLocaleVariant.php'),
            app_path('Playbooks/PlaybookMarkdownRenderer.php'),
            app_path('Playbooks/PlaybookProducts.php'),
            app_path('Playbooks/PlaybookVideoFenceExtension.php'),
        ];

        $mtime = 0;

        foreach ($files as $file) {
            if (is_file($file)) {
                $mtime = max($mtime, filemtime($file) ?: 0);
            }
        }

        return $mtime;
    }

    private function playbookCachePath(string $cacheKey): string
    {
        $safeKey = preg_replace('/[^a-zA-Z0-9._-]+/', '_', $cacheKey) ?: 'playbook';

        return storage_path('framework/cache/playbooks/'.$safeKey.'.cache');
    }

    private function readPlaybookCache(string $cacheKey): ?Playbook
    {
        $path = $this->playbookCachePath($cacheKey);

        if (! is_file($path)) {
            return null;
        }

        $payload = file_get_contents($path);

        if ($payload === false || $payload === '') {
            return null;
        }

        $playbook = unserialize($payload, ['allowed_classes' => true]);

        return $playbook instanceof Playbook ? $playbook : null;
    }

    private function writePlaybookCache(string $cacheKey, Playbook $playbook): void
    {
        $path = $this->playbookCachePath($cacheKey);
        $directory = dirname($path);

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        file_put_contents($path, serialize($playbook));
    }

    private function buildPlaybookUncached(string $slug): Playbook
    {
        $variants = [];
        $heroUrl = null;
        $order = null;
        $modifiedAt = null;
        $publishedAt = null;
        $seriesId = null;
        $seriesPart = null;

        foreach (self::LOCALES as $locale) {
            $path = $this->contentPath($slug, $locale);

            if (! is_file($path)) {
                continue;
            }

            $variant = $this->buildLocaleVariant($path, $slug, $locale);
            $variants[$locale] = $variant;

            $parsed = $this->frontmatterParser->parse(file_get_contents($path) ?: '', $slug);

            if ($order === null) {
                $order = (int) ($parsed['meta']['order'] ?? 0);
            }

            if ($publishedAt === null) {
                $publishedAt = $this->parsePublishedAt($parsed['meta']['publishedat'] ?? null);
            }

            if ($seriesId === null && is_string($variant->series) && $variant->series !== '') {
                $seriesId = $variant->series;
                $seriesPart = $variant->seriesPart;
            }

            $fileTime = Carbon::createFromTimestamp(filemtime($path) ?: time());
            $modifiedAt = $modifiedAt === null || $fileTime->gt($modifiedAt) ? $fileTime : $modifiedAt;
        }

        if ($seriesId === null) {
            foreach (self::LOCALES as $locale) {
                $variant = $variants[$locale] ?? null;

                if ($variant !== null && is_string($variant->series) && $variant->series !== '') {
                    $seriesId = $variant->series;
                    $seriesPart = $variant->seriesPart;
                    break;
                }
            }
        }

        $heroUrl = $variants['de']->heroUrl ?? $variants['en']->heroUrl ?? null;

        return new Playbook(
            slug: $slug,
            heroUrl: $heroUrl,
            order: $order ?? 0,
            modifiedAt: $modifiedAt ?? now(),
            variants: $variants,
            publishedAt: $publishedAt,
            seriesId: $seriesId,
            seriesPart: $seriesPart,
        );
    }

    private function buildSeriesForPlaybook(Playbook $playbook): ?PlaybookSeries
    {
        if (! is_string($playbook->seriesId) || $playbook->seriesId === '') {
            return null;
        }

        $members = collect($this->all())
            ->filter(fn (Playbook $item): bool => $item->seriesId === $playbook->seriesId)
            ->sortBy(fn (Playbook $item): array => [
                $item->seriesPart ?? PHP_INT_MAX,
                $item->title('en'),
            ])
            ->values();

        if ($members->isEmpty()) {
            return null;
        }

        $parts = $members
            ->map(fn (Playbook $item): PlaybookSeriesPart => new PlaybookSeriesPart(
                slug: $item->slug,
                part: $item->seriesPart ?? 0,
                titleDe: $item->title('de'),
                titleEn: $item->title('en'),
                readingTimeDe: $item->variant('de')?->readingTimeMinutes ?? 0,
                readingTimeEn: $item->variant('en')?->readingTimeMinutes ?? 0,
                isCurrent: $item->slug === $playbook->slug,
            ))
            ->values()
            ->all();

        $firstMember = $members->first();
        $titleDe = $this->seriesTitleForLocale($members->all(), 'de')
            ?? $firstMember?->title('de')
            ?? Str::headline(str_replace('-', ' ', $playbook->seriesId));
        $titleEn = $this->seriesTitleForLocale($members->all(), 'en')
            ?? $firstMember?->title('en')
            ?? Str::headline(str_replace('-', ' ', $playbook->seriesId));

        return new PlaybookSeries(
            id: $playbook->seriesId,
            titleDe: $titleDe,
            titleEn: $titleEn,
            parts: $parts,
            currentPart: $playbook->seriesPart ?? 0,
        );
    }

    /**
     * @param  list<Playbook>  $playbooks
     */
    private function buildSeriesOverview(string $seriesId, array $playbooks): PlaybookSeriesOverview
    {
        usort($playbooks, fn (Playbook $a, Playbook $b): int => [
            $a->seriesPart ?? PHP_INT_MAX,
            $a->title('en'),
        ] <=> [
            $b->seriesPart ?? PHP_INT_MAX,
            $b->title('en'),
        ]);

        $parts = array_map(
            fn (Playbook $playbook): PlaybookSeriesPart => new PlaybookSeriesPart(
                slug: $playbook->slug,
                part: $playbook->seriesPart ?? 0,
                titleDe: $playbook->title('de'),
                titleEn: $playbook->title('en'),
                readingTimeDe: $playbook->variant('de')?->readingTimeMinutes ?? 0,
                readingTimeEn: $playbook->variant('en')?->readingTimeMinutes ?? 0,
            ),
            $playbooks,
        );

        $heroPlaybook = collect($playbooks)
            ->sortBy(fn (Playbook $playbook): array => [
                $playbook->seriesPart ?? PHP_INT_MAX,
                $playbook->slug,
            ])
            ->first();

        $titleDe = $this->seriesTitleForLocale($playbooks, 'de')
            ?? $heroPlaybook?->title('de')
            ?? Str::headline(str_replace('-', ' ', $seriesId));
        $titleEn = $this->seriesTitleForLocale($playbooks, 'en')
            ?? $heroPlaybook?->title('en')
            ?? Str::headline(str_replace('-', ' ', $seriesId));

        $totalReadingTimeDe = array_sum(array_map(
            fn (Playbook $playbook): int => $playbook->variant('de')?->readingTimeMinutes ?? 0,
            $playbooks,
        ));
        $totalReadingTimeEn = array_sum(array_map(
            fn (Playbook $playbook): int => $playbook->variant('en')?->readingTimeMinutes ?? 0,
            $playbooks,
        ));

        // Prefer editorial publishedAt (via sortDate) so series order matches story "newest" sorting
        // instead of bouncing to the top whenever a content file is touched.
        $modifiedAt = max(array_map(
            fn (Playbook $playbook): int => $playbook->sortDate()->getTimestamp(),
            $playbooks,
        ));

        $products = PlaybookProducts::union(...array_map(
            function (Playbook $playbook): array {
                $variant = $playbook->variant('en') ?? $playbook->variant('de');

                return $variant?->products ?? [];
            },
            $playbooks,
        ));

        return new PlaybookSeriesOverview(
            id: $seriesId,
            titleDe: $titleDe,
            titleEn: $titleEn,
            heroUrl: $heroPlaybook?->heroUrl,
            modifiedAt: $modifiedAt,
            totalReadingTimeDe: $totalReadingTimeDe,
            totalReadingTimeEn: $totalReadingTimeEn,
            parts: $parts,
            products: $products,
        );
    }

    /**
     * @param  list<Playbook>  $playbooks
     */
    private function seriesTitleForLocale(array $playbooks, string $locale): ?string
    {
        $sorted = $playbooks;
        usort($sorted, fn (Playbook $a, Playbook $b): int => ($a->seriesPart ?? PHP_INT_MAX) <=> ($b->seriesPart ?? PHP_INT_MAX));

        foreach ($sorted as $playbook) {
            $title = $playbook->variant($locale)?->seriesTitle;

            if (is_string($title) && $title !== '') {
                return $title;
            }
        }

        return null;
    }

    private function buildLocaleVariant(string $path, string $slug, string $locale): PlaybookLocaleVariant
    {
        $raw = file_get_contents($path) ?: '';
        $parsed = $this->frontmatterParser->parse($raw, $slug);
        $meta = $parsed['meta'];
        $rendered = $this->markdownRenderer->render($parsed['body'], $locale);

        /** @var list<string> $tags */
        $tags = is_array($meta['tags'] ?? null) ? $meta['tags'] : [];
        $products = PlaybookProducts::resolve($meta['products'] ?? [], $tags);

        $hero = $meta['hero'] ?? null;
        $heroUrl = PlaybookImagePath::assetUrl(is_string($hero) ? $hero : null);

        $series = $meta['series'] ?? null;
        $seriesPart = $meta['seriespart'] ?? $meta['seriesPart'] ?? null;
        $seriesTitle = $meta['seriestitle'] ?? $meta['seriesTitle'] ?? null;
        $author = $meta['author'] ?? null;
        $resolvedAuthor = is_string($author) && trim($author) !== ''
            ? trim($author)
            : (string) config('playbooks.default_author', '');

        return new PlaybookLocaleVariant(
            locale: $locale,
            title: (string) ($meta['title'] ?? $slug),
            description: (string) ($meta['description'] ?? ''),
            category: is_string($meta['category'] ?? null) ? $meta['category'] : null,
            tags: $tags,
            products: $products,
            bodyHtml: $rendered['html'],
            toc: $rendered['toc'],
            readingTimeMinutes: $this->markdownRenderer->readingTimeMinutes($parsed['body']),
            heroUrl: $heroUrl,
            series: is_string($series) && $series !== '' ? $series : null,
            seriesPart: is_numeric($seriesPart) ? (int) $seriesPart : null,
            seriesTitle: is_string($seriesTitle) && $seriesTitle !== '' ? $seriesTitle : null,
            author: $resolvedAuthor !== '' ? $resolvedAuthor : null,
        );
    }

    /**
     * @return Collection<int, string>
     */
    private function sortedSlugs(): Collection
    {
        return once(function (): Collection {
            $slugs = collect(glob($this->contentDirectory().'/*.{de,en}.md', GLOB_BRACE) ?: [])
                ->map(fn (string $path): ?string => $this->parseSlugFromPath($path))
                ->filter()
                ->unique()
                ->sortBy(fn (string $slug): array => $this->sortIndexKeyForSlug($slug))
                ->values();

            return $slugs;
        });
    }

    /**
     * Sort key for story index: order, then series group + part, then title.
     *
     * @return array{0: int, 1: string, 2: int, 3: string}
     */
    private function sortIndexKeyForSlug(string $slug): array
    {
        $path = $this->contentPath($slug, 'de');

        if (! is_file($path)) {
            $path = $this->contentPath($slug, 'en');
        }

        $parsed = $this->frontmatterParser->parse(file_get_contents($path) ?: '', $slug);
        $meta = $parsed['meta'];

        $order = (int) ($meta['order'] ?? 0);
        $title = (string) ($meta['title'] ?? $slug);
        $series = $meta['series'] ?? null;
        $seriesPart = $meta['seriespart'] ?? $meta['seriesPart'] ?? null;

        $seriesKey = is_string($series) && $series !== '' ? $series : $slug;
        $partKey = is_numeric($seriesPart) ? (int) $seriesPart : 0;

        return [$order, $seriesKey, $partKey, $title];
    }

    private function seriesPagerMode(): string
    {
        $mode = (string) config('playbooks.series_pager', 'both');

        return in_array($mode, ['both', 'series', 'global'], true) ? $mode : 'both';
    }

    private function parsePublishedAt(mixed $value): ?Carbon
    {
        if (! is_string($value) || trim($value) === '') {
            return null;
        }

        $value = trim($value);

        try {
            $parsed = Carbon::parse($value);

            // Date-only frontmatter → end of day so same-day series parts can use part offsets.
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value) === 1) {
                return $parsed->endOfDay();
            }

            return $parsed;
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * @param  list<array<string, mixed>>  $items
     * @return list<array<string, mixed>>
     */
    private function sortIndexEntries(array $items): array
    {
        usort($items, fn (array $a, array $b): int => $this->compareIndexEntries($a, $b));

        return array_values($items);
    }

    /**
     * @param  array<string, mixed>  $a
     * @param  array<string, mixed>  $b
     */
    private function compareIndexEntries(array $a, array $b): int
    {
        $cmp = $this->indexSortTimestamp($b) <=> $this->indexSortTimestamp($a);

        if ($cmp !== 0) {
            return $cmp;
        }

        return strcmp((string) ($a['slug'] ?? ''), (string) ($b['slug'] ?? ''));
    }

    /**
     * @param  array<string, mixed>  $item
     */
    private function indexSortTimestamp(array $item): int
    {
        if (isset($item['indexSortTimestamp']) && is_int($item['indexSortTimestamp'])) {
            return $item['indexSortTimestamp'];
        }

        $timestamp = $item['sortDate']->getTimestamp();
        $seriesPart = $item['seriesPart'] ?? null;

        if (is_int($seriesPart) && $seriesPart > 0) {
            $timestamp += $seriesPart;
        }

        return $timestamp;
    }

    private function hasSlug(string $slug): bool
    {
        foreach (self::LOCALES as $locale) {
            if (is_file($this->contentPath($slug, $locale))) {
                return true;
            }
        }

        return false;
    }

    private function parseSlugFromPath(string $path): ?string
    {
        $basename = basename($path);

        if (preg_match('/^(.+)\.(de|en)\.md$/', $basename, $matches) !== 1) {
            return null;
        }

        return $matches[1];
    }

    private function contentPath(string $slug, string $locale): string
    {
        return $this->contentDirectory().'/'.$slug.'.'.$locale.'.md';
    }

    private function contentDirectory(): string
    {
        return config('playbooks.content_path', base_path('content'));
    }
}
