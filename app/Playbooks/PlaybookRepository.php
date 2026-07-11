<?php

namespace App\Playbooks;

use Carbon\Carbon;
use Illuminate\Support\Collection;

final class PlaybookRepository
{
    private const LOCALES = ['de', 'en'];

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
        return $this->sortedSlugs()
            ->map(fn (string $slug): array => $this->buildPlaybook($slug)->toIndexArray())
            ->values()
            ->all();
    }

    public function find(string $slug): ?Playbook
    {
        if (! $this->hasSlug($slug)) {
            return null;
        }

        $ordered = $this->sortedSlugs()->values();
        $playbook = $this->buildPlaybook($slug);
        $index = $ordered->search(fn (string $item): bool => $item === $slug);

        if ($index === false) {
            return $playbook;
        }

        $prev = $index > 0 ? $ordered[$index - 1] : null;
        $next = $index < $ordered->count() - 1 ? $ordered[$index + 1] : null;

        return new Playbook(
            slug: $playbook->slug,
            heroUrl: $playbook->heroUrl,
            order: $playbook->order,
            modifiedAt: $playbook->modifiedAt,
            variants: $playbook->variants,
            prev: $prev ? new PlaybookNavRef($prev, $this->buildPlaybook($prev)->title()) : null,
            next: $next ? new PlaybookNavRef($next, $this->buildPlaybook($next)->title()) : null,
        );
    }

    private function buildPlaybook(string $slug): Playbook
    {
        $variants = [];
        $heroUrl = null;
        $order = 0;
        $modifiedAt = null;

        foreach (self::LOCALES as $locale) {
            $path = $this->contentPath($slug, $locale);

            if (! is_file($path)) {
                continue;
            }

            $variant = $this->buildLocaleVariant($path, $slug, $locale);
            $variants[$locale] = $variant;

            if ($heroUrl === null && $slug) {
                $parsed = $this->frontmatterParser->parse(file_get_contents($path) ?: '', $slug);
                $hero = $parsed['meta']['hero'] ?? null;
                $heroUrl = is_string($hero) && $hero !== '' ? asset($hero) : null;
                $order = (int) ($parsed['meta']['order'] ?? 0);
            }

            $fileTime = Carbon::createFromTimestamp(filemtime($path) ?: time());
            $modifiedAt = $modifiedAt === null || $fileTime->gt($modifiedAt) ? $fileTime : $modifiedAt;
        }

        return new Playbook(
            slug: $slug,
            heroUrl: $heroUrl,
            order: $order,
            modifiedAt: $modifiedAt ?? now(),
            variants: $variants,
        );
    }

    private function buildLocaleVariant(string $path, string $slug, string $locale): PlaybookLocaleVariant
    {
        $raw = file_get_contents($path) ?: '';
        $parsed = $this->frontmatterParser->parse($raw, $slug);
        $meta = $parsed['meta'];
            $rendered = $this->markdownRenderer->render($parsed['body'], $locale);

        /** @var list<string> $tags */
        $tags = is_array($meta['tags'] ?? null) ? $meta['tags'] : [];

        return new PlaybookLocaleVariant(
            locale: $locale,
            title: (string) ($meta['title'] ?? $slug),
            description: (string) ($meta['description'] ?? ''),
            category: is_string($meta['category'] ?? null) ? $meta['category'] : null,
            tags: $tags,
            bodyHtml: $rendered['html'],
            toc: $rendered['toc'],
            readingTimeMinutes: $this->markdownRenderer->readingTimeMinutes($parsed['body']),
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
                ->sortBy(function (string $slug): array {
                    $path = $this->contentPath($slug, 'de');

                    if (! is_file($path)) {
                        $path = $this->contentPath($slug, 'en');
                    }

                    $parsed = $this->frontmatterParser->parse(file_get_contents($path) ?: '', $slug);

                    return [
                        (int) ($parsed['meta']['order'] ?? 0),
                        (string) ($parsed['meta']['title'] ?? $slug),
                    ];
                })
                ->values();

            return $slugs;
        });
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
        return base_path('content');
    }
}
