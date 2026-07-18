<?php

namespace App\Support;

final class PlaybookImagePath
{
    /**
     * Normalize legacy or mistaken playbook image paths to the canonical location.
     */
    public static function normalize(?string $path): ?string
    {
        if (! is_string($path) || $path === '') {
            return null;
        }

        if (str_starts_with($path, 'images/stories/')) {
            $path = 'images/playbooks/'.substr($path, strlen('images/stories/'));
        }

        // Guard against accidental "name..png" / "name..webp" uploads.
        $path = preg_replace('/\.\.(png|webp|jpe?g|gif|svg)$/i', '.$1', $path) ?? $path;

        // Bare filenames in markdown (e.g. "hero.png") resolve under images/playbooks/.
        if (! str_contains($path, '/') && preg_match('/\.(png|webp|jpe?g|gif|svg)$/i', $path) === 1) {
            $candidate = 'images/playbooks/'.$path;
            if (is_file(public_path($candidate))) {
                return $candidate;
            }
        }

        return $path;
    }

    public static function assetUrl(?string $path): ?string
    {
        $normalized = self::publicRelativePath($path);

        return $normalized !== null ? asset($normalized) : null;
    }

    public static function webpPath(?string $path): ?string
    {
        $normalized = self::publicRelativePath($path);

        if ($normalized === null || ! str_ends_with(strtolower($normalized), '.png')) {
            return null;
        }

        return substr($normalized, 0, -4).'.webp';
    }

    public static function webpUrl(?string $path): ?string
    {
        $webpPath = self::webpPath($path);

        if ($webpPath === null) {
            return null;
        }

        return is_file(public_path($webpPath)) ? asset($webpPath) : null;
    }

    /**
     * @return array{webp: string, fallback: string}|null
     */
    public static function pictureSources(?string $path): ?array
    {
        $fallback = self::assetUrl($path);
        $webp = self::webpUrl($path);

        if ($fallback === null) {
            return null;
        }

        if ($webp === null) {
            return null;
        }

        return [
            'webp' => $webp,
            'fallback' => $fallback,
        ];
    }

    public static function publicRelativePath(?string $pathOrUrl): ?string
    {
        if (! is_string($pathOrUrl) || $pathOrUrl === '') {
            return null;
        }

        if (str_contains($pathOrUrl, '://')) {
            $parsedPath = parse_url($pathOrUrl, PHP_URL_PATH);

            if (! is_string($parsedPath) || $parsedPath === '') {
                return null;
            }

            $pathOrUrl = $parsedPath;
        }

        $relative = ltrim($pathOrUrl, '/');

        foreach (self::publicPathPrefixes() as $prefix) {
            if ($prefix !== '' && str_starts_with($relative, $prefix.'/')) {
                $relative = substr($relative, strlen($prefix) + 1);
                break;
            }
        }

        return self::normalize($relative);
    }

    /**
     * @return list<string>
     */
    private static function publicPathPrefixes(): array
    {
        $prefixes = [];

        $configured = ltrim((string) (parse_url((string) config('app.url'), PHP_URL_PATH) ?? ''), '/');

        if ($configured !== '') {
            $prefixes[] = $configured;
        }

        $runtime = ltrim(AppBase::path(), '/');

        if ($runtime !== '' && ! in_array($runtime, $prefixes, true)) {
            $prefixes[] = $runtime;
        }

        return $prefixes;
    }
}
