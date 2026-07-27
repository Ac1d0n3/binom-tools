<?php

namespace App\Playbooks;

final class PlaybookVideoEmbedResolver
{
    private const LOCAL_PREFIX = 'videos/playbooks/';

    private const LOCAL_EXTENSIONS = [
        'mp4' => 'video/mp4',
        'webm' => 'video/webm',
        'ogg' => 'video/ogg',
    ];

    /**
     * @return array{
     *     platform: string,
     *     id: string,
     *     embedUrl?: string,
     *     privacyEmbedUrl?: string|null,
     *     srcUrl?: string,
     *     mimeType?: string,
     *     publicPath?: string
     * }|null
     */
    public static function resolve(string $url): ?array
    {
        $url = trim($url);

        if ($url === '') {
            return null;
        }

        $local = self::resolveLocal($url);

        if ($local !== null) {
            return $local;
        }

        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            return null;
        }

        $parts = parse_url($url);

        if ($parts === false || ! isset($parts['host'])) {
            return null;
        }

        $host = strtolower($parts['host']);
        $path = $parts['path'] ?? '';

        if (str_contains($host, 'youtube.com') || $host === 'youtu.be') {
            $id = self::youtubeId($host, $path, $parts['query'] ?? '');

            if ($id === null) {
                return null;
            }

            return [
                'platform' => 'youtube',
                'id' => $id,
                'embedUrl' => 'https://www.youtube.com/embed/'.$id,
                'privacyEmbedUrl' => 'https://www.youtube-nocookie.com/embed/'.$id,
            ];
        }

        if (str_contains($host, 'vimeo.com')) {
            if (preg_match('#/(?:video/)?(\d+)#', $path, $matches) !== 1) {
                return null;
            }

            $id = $matches[1];

            return [
                'platform' => 'vimeo',
                'id' => $id,
                'embedUrl' => 'https://player.vimeo.com/video/'.$id,
                'privacyEmbedUrl' => 'https://player.vimeo.com/video/'.$id,
            ];
        }

        if (str_contains($host, 'loom.com')) {
            if (preg_match('#/share/([a-zA-Z0-9]+)#', $path, $matches) !== 1) {
                return null;
            }

            $id = $matches[1];

            return [
                'platform' => 'loom',
                'id' => $id,
                'embedUrl' => 'https://www.loom.com/embed/'.$id,
                'privacyEmbedUrl' => 'https://www.loom.com/embed/'.$id,
            ];
        }

        if (str_contains($host, 'wistia.com') || str_contains($host, 'wistia.net')) {
            if (preg_match('#/(?:medias|embed/iframe)/([a-zA-Z0-9]+)#', $path, $matches) !== 1) {
                return null;
            }

            $id = $matches[1];

            return [
                'platform' => 'wistia',
                'id' => $id,
                'embedUrl' => 'https://fast.wistia.net/embed/iframe/'.$id,
                'privacyEmbedUrl' => 'https://fast.wistia.net/embed/iframe/'.$id,
            ];
        }

        return null;
    }

    /**
     * @return array{
     *     platform: string,
     *     id: string,
     *     srcUrl: string,
     *     mimeType: string,
     *     publicPath: string
     * }|null
     */
    public static function resolveLocal(string $raw): ?array
    {
        $path = trim($raw);

        if ($path === '' || str_contains($path, '://') || str_contains($path, '..')) {
            return null;
        }

        $relative = ltrim($path, '/');

        if (! str_starts_with($relative, self::LOCAL_PREFIX)) {
            return null;
        }

        $basename = basename($relative);

        if ($basename === '' || $basename === '.' || $basename === '..') {
            return null;
        }

        if (preg_match('/^[\w.-]+$/u', $basename) !== 1) {
            return null;
        }

        $extension = strtolower(pathinfo($basename, PATHINFO_EXTENSION));

        if (! isset(self::LOCAL_EXTENSIONS[$extension])) {
            return null;
        }

        // Only a single file under videos/playbooks/ (no nested folders).
        if ($relative !== self::LOCAL_PREFIX.$basename) {
            return null;
        }

        return [
            'platform' => 'local',
            'id' => $basename,
            'srcUrl' => asset($relative),
            'mimeType' => self::LOCAL_EXTENSIONS[$extension],
            'publicPath' => $relative,
        ];
    }

    private static function youtubeId(string $host, string $path, string $query): ?string
    {
        if ($host === 'youtu.be') {
            $id = ltrim($path, '/');

            return $id !== '' ? $id : null;
        }

        if (preg_match('#/embed/([a-zA-Z0-9_-]{11})#', $path, $matches) === 1) {
            return $matches[1];
        }

        parse_str($query, $params);

        $id = $params['v'] ?? null;

        return is_string($id) && $id !== '' ? $id : null;
    }
}
