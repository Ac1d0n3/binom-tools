<?php

namespace App\Playbooks;

use App\Support\LocaleUrl;
use App\Support\PlaybookImagePath;
use Illuminate\Support\Facades\Vite;
use Throwable;

final class PlaybookOfflineManifestBuilder
{
    private const SHELL_ENTRIES = [
        'resources/css/app.css',
        'resources/js/app.js',
        'resources/css/playbooks.css',
        'resources/js/playbooks/show.js',
    ];

    /**
     * @return array{
     *     slug: string,
     *     title: string,
     *     titleDe: string,
     *     titleEn: string,
     *     modifiedAt: string,
     *     bytesEstimate: int,
     *     pageUrl: string,
     *     urls: list<string>
     * }
     */
    public function forPlaybook(Playbook $playbook): array
    {
        $urls = array_merge(
            $this->shellUrls(),
            $this->pageUrls($playbook->slug),
            $this->contentUrls($playbook),
        );

        $unique = $this->uniqueUrls($urls);

        return [
            'slug' => $playbook->slug,
            'title' => $playbook->title(),
            'titleDe' => $playbook->title('de'),
            'titleEn' => $playbook->title('en'),
            'modifiedAt' => $playbook->modifiedAt->toIso8601String(),
            'bytesEstimate' => $this->estimateBytes($unique),
            'pageUrl' => LocaleUrl::path('/playbooks/'.$playbook->slug),
            'urls' => $unique,
        ];
    }

    /**
     * @param  list<Playbook>  $playbooks
     * @return array{
     *     bytesEstimate: int,
     *     shellUrls: list<string>,
     *     indexUrls: list<string>,
     *     stories: list<array{
     *         slug: string,
     *         title: string,
     *         titleDe: string,
     *         titleEn: string,
     *         modifiedAt: string,
     *         bytesEstimate: int,
     *         pageUrl: string,
     *         urls: list<string>
     *     }>
     * }
     */
    public function forPlaybooks(array $playbooks): array
    {
        $shell = $this->shellUrls();
        $indexUrls = array_merge(
            $shell,
            [
                LocaleUrl::path('/playbooks', 'en'),
                LocaleUrl::path('/playbooks', 'de'),
            ],
            $this->staticOfflineUrls(),
        );

        $stories = [];
        $contentBytes = 0;

        foreach ($playbooks as $playbook) {
            $story = $this->forPlaybook($playbook);
            $storyContentUrls = array_values(array_filter(
                $story['urls'],
                fn (string $url): bool => ! $this->isShellUrl($url, $shell),
            ));
            $story['urls'] = $this->uniqueUrls(array_merge($shell, $storyContentUrls));
            $story['bytesEstimate'] = $this->estimateBytes($storyContentUrls);
            $stories[] = $story;
            $contentBytes += $story['bytesEstimate'];
        }

        $shellAndIndexBytes = $this->estimateBytes($this->uniqueUrls($indexUrls));

        return [
            'bytesEstimate' => $shellAndIndexBytes + $contentBytes,
            'shellUrls' => $this->uniqueUrls($shell),
            'indexUrls' => $this->uniqueUrls($indexUrls),
            'stories' => $stories,
        ];
    }

    /**
     * @param  list<string>  $shell
     */
    private function isShellUrl(string $url, array $shell): bool
    {
        $key = parse_url($url, PHP_URL_PATH) ?: $url;

        foreach ($shell as $shellUrl) {
            $shellKey = parse_url($shellUrl, PHP_URL_PATH) ?: $shellUrl;
            if ($shellKey === $key) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return list<string>
     */
    private function shellUrls(): array
    {
        $urls = $this->staticOfflineUrls();

        foreach (self::SHELL_ENTRIES as $entry) {
            foreach ($this->viteEntryUrls($entry) as $url) {
                $urls[] = $url;
            }
        }

        $urls[] = asset('favicon.ico');
        $urls[] = asset('favicon.svg');
        $urls[] = asset('apple-touch-icon.png');

        return $urls;
    }

    /**
     * @return list<string>
     */
    private function staticOfflineUrls(): array
    {
        return [
            asset('sw-playbooks.js'),
            asset('playbooks-offline-fallback.html'),
        ];
    }

    /**
     * @return list<string>
     */
    private function pageUrls(string $slug): array
    {
        return [
            LocaleUrl::path('/playbooks/'.$slug, 'en'),
            LocaleUrl::path('/playbooks/'.$slug, 'de'),
        ];
    }

    /**
     * @return list<string>
     */
    private function contentUrls(Playbook $playbook): array
    {
        $urls = [];

        foreach ($playbook->variants as $variant) {
            if (is_string($variant->heroUrl) && $variant->heroUrl !== '') {
                $urls[] = $variant->heroUrl;
                $webp = PlaybookImagePath::webpUrl(
                    PlaybookImagePath::publicRelativePath($variant->heroUrl)
                );
                if (is_string($webp)) {
                    $urls[] = $webp;
                }
            }

            $urls = array_merge($urls, $this->extractAssetUrlsFromHtml($variant->bodyHtml));
        }

        if (is_string($playbook->heroUrl) && $playbook->heroUrl !== '') {
            $urls[] = $playbook->heroUrl;
            $webp = PlaybookImagePath::webpUrl(
                PlaybookImagePath::publicRelativePath($playbook->heroUrl)
            );
            if (is_string($webp)) {
                $urls[] = $webp;
            }
        }

        return $urls;
    }

    /**
     * @return list<string>
     */
    private function extractAssetUrlsFromHtml(string $html): array
    {
        $urls = [];

        if (preg_match_all(
            '/(?:src|data-video-src|href)=["\']([^"\']+)["\']/i',
            $html,
            $matches,
        ) > 0) {
            foreach ($matches[1] as $candidate) {
                if ($this->isCacheableContentUrl($candidate)) {
                    $urls[] = $candidate;
                }
            }
        }

        if (preg_match_all('/srcset=["\']([^"\']+)["\']/i', $html, $srcsetMatches) > 0) {
            foreach ($srcsetMatches[1] as $srcset) {
                foreach (preg_split('/\s*,\s*/', $srcset) ?: [] as $part) {
                    $url = trim(explode(' ', trim($part))[0] ?? '');
                    if ($url !== '' && $this->isCacheableContentUrl($url)) {
                        $urls[] = $url;
                    }
                }
            }
        }

        return $urls;
    }

    private function isCacheableContentUrl(string $url): bool
    {
        if ($url === '' || str_starts_with($url, 'data:') || str_starts_with($url, 'blob:')) {
            return false;
        }

        if (str_contains($url, 'youtube') || str_contains($url, 'vimeo') || str_contains($url, 'loom.com') || str_contains($url, 'wistia')) {
            return false;
        }

        $relative = PlaybookImagePath::publicRelativePath($url) ?? ltrim((string) (parse_url($url, PHP_URL_PATH) ?: $url), '/');

        return str_starts_with($relative, 'images/playbooks/')
            || str_starts_with($relative, 'videos/playbooks/')
            || str_starts_with($relative, 'build/');
    }

    /**
     * @return list<string>
     */
    private function viteEntryUrls(string $entry): array
    {
        $urls = [];

        try {
            $urls[] = Vite::asset($entry);
        } catch (Throwable) {
            // Manifest missing (tests / before first build).
        }

        try {
            $manifestPath = public_path('build/manifest.json');

            if (! is_file($manifestPath)) {
                return $this->uniqueUrls($urls);
            }

            $manifest = json_decode((string) file_get_contents($manifestPath), true);

            if (! is_array($manifest) || ! isset($manifest[$entry]) || ! is_array($manifest[$entry])) {
                return $this->uniqueUrls($urls);
            }

            $chunk = $manifest[$entry];
            $file = $chunk['file'] ?? null;

            if (is_string($file) && $file !== '') {
                $urls[] = asset('build/'.$file);
            }

            foreach ($chunk['css'] ?? [] as $css) {
                if (is_string($css) && $css !== '') {
                    $urls[] = asset('build/'.$css);
                }
            }

            foreach ($chunk['imports'] ?? [] as $import) {
                if (! is_string($import) || ! isset($manifest[$import]) || ! is_array($manifest[$import])) {
                    continue;
                }

                $importFile = $manifest[$import]['file'] ?? null;
                if (is_string($importFile) && $importFile !== '') {
                    $urls[] = asset('build/'.$importFile);
                }

                foreach ($manifest[$import]['css'] ?? [] as $css) {
                    if (is_string($css) && $css !== '') {
                        $urls[] = asset('build/'.$css);
                    }
                }
            }

            foreach ($manifest as $key => $item) {
                if (! is_string($key) || ! is_array($item)) {
                    continue;
                }

                if (! str_contains($key, 'playbooks/prism')) {
                    continue;
                }

                $file = $item['file'] ?? null;
                if (is_string($file) && $file !== '') {
                    $urls[] = asset('build/'.$file);
                }

                foreach ($item['css'] ?? [] as $css) {
                    if (is_string($css) && $css !== '') {
                        $urls[] = asset('build/'.$css);
                    }
                }
            }
        } catch (Throwable) {
            // Ignore vite manifest issues; HTML parse will still catch linked assets.
        }

        return $this->uniqueUrls($urls);
    }

    /**
     * @param  list<string>  $urls
     * @return list<string>
     */
    private function uniqueUrls(array $urls): array
    {
        $seen = [];
        $result = [];

        foreach ($urls as $url) {
            if (! is_string($url) || $url === '') {
                continue;
            }

            $key = parse_url($url, PHP_URL_PATH) ?: $url;

            if (isset($seen[$key])) {
                continue;
            }

            $seen[$key] = true;
            $result[] = $url;
        }

        return $result;
    }

    /**
     * @param  list<string>  $urls
     */
    private function estimateBytes(array $urls): int
    {
        $total = 0;

        foreach ($urls as $url) {
            $relative = PlaybookImagePath::publicRelativePath($url);

            if ($relative === null) {
                $path = parse_url($url, PHP_URL_PATH);
                $relative = is_string($path) ? ltrim($path, '/') : null;
            }

            if ($relative === null || $relative === '') {
                $total += 80_000;

                continue;
            }

            $file = public_path($relative);

            if (is_file($file)) {
                $total += (int) filesize($file);

                continue;
            }

            if (str_contains($relative, 'playbooks/') || str_contains($relative, '/playbooks')) {
                $total += 80_000;
            } else {
                $total += 16_000;
            }
        }

        return $total;
    }
}
