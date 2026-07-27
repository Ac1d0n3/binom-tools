<?php

namespace App\Catalog;

/**
 * Collect http(s) URLs from catalog JSON, markdown content, and vendor-resources config.
 */
final class LinkInventoryScanner
{
    /**
     * @return list<array{url: string, source: string}>
     */
    public function scan(): array
    {
        $found = [];

        foreach ($this->scanDirectory(base_path('content/catalogs')) as $hit) {
            $found[] = $hit;
        }
        foreach ($this->scanMarkdown(base_path('content')) as $hit) {
            $found[] = $hit;
        }
        foreach ($this->scanPhpConfigValue(config('vendor-resources', []), 'modules/resources/vendor-resources.config.php') as $hit) {
            $found[] = $hit;
        }
        foreach ($this->scanPhpConfigValue(config('compliance', []), 'modules/compliance/config.php') as $hit) {
            $found[] = $hit;
        }

        $dedup = [];
        foreach ($found as $hit) {
            $key = $hit['url'].'|'.$hit['source'];
            $dedup[$key] = $hit;
        }

        $list = array_values($dedup);
        usort($list, static fn (array $a, array $b): int => strcmp($a['url'], $b['url']) ?: strcmp($a['source'], $b['source']));

        return $list;
    }

    /**
     * @return list<array{url: string, source: string}>
     */
    private function scanDirectory(string $dir): array
    {
        if (! is_dir($dir)) {
            return [];
        }

        $hits = [];
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if (! $file instanceof \SplFileInfo || ! $file->isFile()) {
                continue;
            }
            if (strtolower($file->getExtension()) !== 'json') {
                continue;
            }
            $relative = 'content/catalogs/'.ltrim(str_replace(base_path('content/catalogs'), '', $file->getPathname()), '/');
            $raw = file_get_contents($file->getPathname());
            if ($raw === false || $raw === '') {
                continue;
            }
            try {
                $decoded = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
            } catch (\JsonException) {
                continue;
            }
            foreach ($this->extractUrls($decoded) as $url) {
                $hits[] = ['url' => $url, 'source' => $relative];
            }
        }

        return $hits;
    }

    /**
     * @return list<array{url: string, source: string}>
     */
    private function scanMarkdown(string $dir): array
    {
        if (! is_dir($dir)) {
            return [];
        }

        $hits = [];
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if (! $file instanceof \SplFileInfo || ! $file->isFile()) {
                continue;
            }
            $ext = strtolower($file->getExtension());
            if ($ext !== 'md') {
                continue;
            }
            // Skip catalogs if nested under content (none today)
            $path = $file->getPathname();
            if (str_contains($path, DIRECTORY_SEPARATOR.'catalogs'.DIRECTORY_SEPARATOR)) {
                continue;
            }
            $raw = file_get_contents($path);
            if ($raw === false || $raw === '') {
                continue;
            }
            $relative = 'content/'.ltrim(str_replace(base_path('content'), '', $path), '/\\');
            if (preg_match_all('/https?:\/\/[^\s\)\]\>\"\']+/i', $raw, $matches) > 0) {
                foreach ($matches[0] as $url) {
                    $clean = $this->normalizeUrl($url);
                    if ($clean !== null) {
                        $hits[] = ['url' => $clean, 'source' => $relative];
                    }
                }
            }
        }

        return $hits;
    }

    /**
     * @param  mixed  $value
     * @return list<array{url: string, source: string}>
     */
    private function scanPhpConfigValue(mixed $value, string $source): array
    {
        $hits = [];
        foreach ($this->extractUrls($value) as $url) {
            $hits[] = ['url' => $url, 'source' => $source];
        }

        return $hits;
    }

    /**
     * @param  mixed  $node
     * @return list<string>
     */
    private function extractUrls(mixed $node): array
    {
        $urls = [];
        if (is_string($node)) {
            if (preg_match_all('/https?:\/\/[^\s\)\]\>\"\']+/i', $node, $matches) > 0) {
                foreach ($matches[0] as $url) {
                    $clean = $this->normalizeUrl($url);
                    if ($clean !== null) {
                        $urls[] = $clean;
                    }
                }
            }

            return $urls;
        }

        if (! is_array($node)) {
            return [];
        }

        foreach ($node as $child) {
            foreach ($this->extractUrls($child) as $url) {
                $urls[] = $url;
            }
        }

        return $urls;
    }

    private function normalizeUrl(string $url): ?string
    {
        $url = rtrim($url, '.,;:!?)}]');
        if (! str_starts_with($url, 'http://') && ! str_starts_with($url, 'https://')) {
            return null;
        }
        if (str_starts_with(strtolower($url), 'mailto:')) {
            return null;
        }

        return $url;
    }
}
