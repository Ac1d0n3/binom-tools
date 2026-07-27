<?php

namespace App\Playbooks;

use Illuminate\Http\Request;

/**
 * Client engagement cookie (views-per-day + liked slugs) for playbook stats.
 */
final class PlaybookEngagementCookie
{
    public const NAME = 'bn_playbook_engagement';

    /**
     * @return array{v: array<string, string>, l: array<string, int|string>}
     */
    public static function read(Request $request): array
    {
        $raw = (string) $request->cookies->get(self::NAME, '');
        if ($raw === '') {
            return ['v' => [], 'l' => []];
        }

        try {
            $decoded = json_decode(base64_decode($raw, true) ?: '', true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            return ['v' => [], 'l' => []];
        }

        if (! is_array($decoded)) {
            return ['v' => [], 'l' => []];
        }

        $views = is_array($decoded['v'] ?? null) ? $decoded['v'] : [];
        $likes = is_array($decoded['l'] ?? null) ? $decoded['l'] : [];

        return [
            'v' => array_filter($views, static fn ($value) => is_string($value)),
            'l' => array_filter($likes, static fn ($value) => is_int($value) || is_string($value)),
        ];
    }

    public static function isLiked(Request $request, string $slug): bool
    {
        $state = self::read($request);

        return ! empty($state['l'][$slug]);
    }

    /**
     * @param  array{v: array<string, string>, l: array<string, int|string>}  $state
     */
    public static function encode(array $state): string
    {
        return base64_encode(json_encode([
            'v' => $state['v'],
            'l' => $state['l'],
        ], JSON_THROW_ON_ERROR));
    }
}
