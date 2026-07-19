<?php

namespace App\Support;

final class AccentColors
{
    /** @var list<string> */
    public const SOLID_TOKENS = [
        'accent-1',
        'accent-2',
        'accent-3',
        'accent-4',
        'accent-5',
        'accent-6',
        'accent-7',
        'accent-8',
        'accent-9',
        'accent-10',
        'accent-11',
        'accent-12',
    ];

    /** @var list<string> */
    public const OUTLINE_TOKENS = [
        'outline-1',
        'outline-2',
        'outline-3',
        'outline-4',
        'outline-5',
        'outline-6',
    ];

    /** @var list<string> */
    public const TOKENS = [
        ...self::SOLID_TOKENS,
        ...self::OUTLINE_TOKENS,
    ];

    /** Team picker stays on the first six solid accents. */
    public const TEAM_TOKENS = [
        'accent-1',
        'accent-2',
        'accent-3',
        'accent-4',
        'accent-5',
        'accent-6',
    ];

    /** @var array<string, string> */
    public const HEX = [
        'accent-1' => '#2563eb',
        'accent-2' => '#0d9488',
        'accent-3' => '#c2410c',
        'accent-4' => '#7c3aed',
        'accent-5' => '#be185d',
        'accent-6' => '#475569',
        'accent-7' => '#15803d',
        'accent-8' => '#d97706',
        'accent-9' => '#0891b2',
        'accent-10' => '#4338ca',
        'accent-11' => '#e11d48',
        'accent-12' => '#65a30d',
        'outline-1' => '#2563eb',
        'outline-2' => '#0d9488',
        'outline-3' => '#c2410c',
        'outline-4' => '#7c3aed',
        'outline-5' => '#be185d',
        'outline-6' => '#475569',
    ];

    public static function normalize(?string $token): string
    {
        $value = trim((string) $token);

        return in_array($value, self::TOKENS, true) ? $value : 'accent-1';
    }

    public static function isOutline(?string $token): bool
    {
        return str_starts_with(self::normalize($token), 'outline-');
    }

    public static function hex(?string $token): string
    {
        $normalized = self::normalize($token);

        return self::HEX[$normalized];
    }

    /**
     * Inline styles for avatar chips (solid fill or white + colored border).
     */
    public static function chipStyle(?string $token): string
    {
        $normalized = self::normalize($token);
        $hex = self::HEX[$normalized];

        if (self::isOutline($normalized)) {
            return 'background-color:#fff;color:'.$hex.';border:2px solid '.$hex.';';
        }

        return 'background-color:'.$hex.';color:#fff;border:2px solid transparent;';
    }

    /**
     * @param  list<string>  $usedTokens
     * @param  list<string>|null  $pool
     */
    public static function nextUnused(array $usedTokens, int $existingCount = 0, ?array $pool = null): string
    {
        $pool ??= self::TEAM_TOKENS;
        $used = [];
        foreach ($usedTokens as $token) {
            $used[self::normalize($token)] = true;
        }

        foreach ($pool as $token) {
            if (! isset($used[$token])) {
                return $token;
            }
        }

        return $pool[$existingCount % count($pool)];
    }
}
