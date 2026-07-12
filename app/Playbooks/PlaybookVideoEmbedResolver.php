<?php

namespace App\Playbooks;

final class PlaybookVideoEmbedResolver
{
    /**
     * @return array{platform: string, id: string, embedUrl: string, privacyEmbedUrl: string|null}|null
     */
    public static function resolve(string $url): ?array
    {
        $url = trim($url);

        if ($url === '') {
            return null;
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
