<?php

namespace App\Admin\Content;

use RuntimeException;

/**
 * File-first markdown writer for stories and sprint plan templates (never DB).
 */
final class MarkdownContentWriter
{
    public function __construct(
        private readonly string $directory,
    ) {}

    /**
     * @return list<array{slug: string, de: bool, en: bool, updatedAt: ?int}>
     */
    public function listSlugs(): array
    {
        if (! is_dir($this->directory)) {
            return [];
        }

        /** @var array<string, array{slug: string, de: bool, en: bool, updatedAt: ?int}> $map */
        $map = [];
        foreach (glob($this->directory.DIRECTORY_SEPARATOR.'*.md') ?: [] as $file) {
            $base = basename($file);
            if (! preg_match('/^([a-z0-9-]+)\.(de|en)\.md$/', $base, $m)) {
                continue;
            }
            $slug = $m[1];
            $locale = $m[2];
            $map[$slug] ??= ['slug' => $slug, 'de' => false, 'en' => false, 'updatedAt' => null];
            $map[$slug][$locale] = true;
            $mtime = @filemtime($file) ?: null;
            if ($mtime !== null && ($map[$slug]['updatedAt'] === null || $mtime > $map[$slug]['updatedAt'])) {
                $map[$slug]['updatedAt'] = $mtime;
            }
        }

        $rows = array_values($map);
        usort($rows, static fn (array $a, array $b): int => strcmp($a['slug'], $b['slug']));

        return $rows;
    }

    public function read(string $slug, string $locale): ?string
    {
        $path = $this->path($slug, $locale);
        if (! is_file($path)) {
            return null;
        }
        $raw = file_get_contents($path);

        return $raw === false ? null : $raw;
    }

    public function write(string $slug, string $locale, string $body): void
    {
        $this->assertSlug($slug);
        $this->assertLocale($locale);
        if (! is_dir($this->directory) && ! mkdir($this->directory, 0775, true) && ! is_dir($this->directory)) {
            throw new RuntimeException('Unable to create content directory.');
        }
        $path = $this->path($slug, $locale);
        $tmp = $path.'.tmp';
        if (file_put_contents($tmp, $body) === false) {
            throw new RuntimeException('Unable to write markdown file.');
        }
        if (! rename($tmp, $path)) {
            @unlink($tmp);
            throw new RuntimeException('Unable to finalize markdown file.');
        }
    }

    public function delete(string $slug): void
    {
        $this->assertSlug($slug);
        foreach (['de', 'en'] as $locale) {
            $path = $this->path($slug, $locale);
            if (is_file($path)) {
                @unlink($path);
            }
        }
    }

    public function path(string $slug, string $locale): string
    {
        return $this->directory.DIRECTORY_SEPARATOR.$slug.'.'.$locale.'.md';
    }

    private function assertSlug(string $slug): void
    {
        if (! preg_match('/^[a-z0-9-]+$/', $slug)) {
            throw new RuntimeException('Invalid slug.');
        }
    }

    private function assertLocale(string $locale): void
    {
        if ($locale !== 'de' && $locale !== 'en') {
            throw new RuntimeException('Invalid locale.');
        }
    }
}
