<?php

namespace App\Support;

final class AvatarIcons
{
    /**
     * Curated Font Awesome solid icon names (without fa-solid prefix).
     *
     * @var list<string>
     */
    public const OPTIONS = [
        'user',
        'user-tie',
        'user-gear',
        'user-graduate',
        'user-astronaut',
        'user-ninja',
        'user-secret',
        'user-doctor',
        'users',
        'people-group',
        'person',
        'face-smile',
        'hat-wizard',
        'crown',
        'rocket',
        'flask',
        'code',
        'laptop-code',
        'database',
        'chart-line',
        'shield-halved',
        'lightbulb',
        'star',
        'heart',
        'paw',
        'cat',
        'dog',
        'mug-hot',
        'headphones',
        'camera',
        'gamepad',
        'music',
        'brush',
        'compass',
        'sitemap',
        'brain',
    ];

    public static function normalize(?string $icon): string
    {
        $value = strtolower(trim((string) $icon));
        $value = preg_replace('/^fa-(solid|regular|brands)\s+/', '', $value) ?? '';
        $value = preg_replace('/^fa-/', '', $value) ?? '';
        $value = trim($value);

        if ($value === '' || $value === 'none' || $value === 'trigram') {
            return '';
        }

        return in_array($value, self::OPTIONS, true) ? $value : '';
    }

    public static function cssClass(?string $icon): string
    {
        $normalized = self::normalize($icon);

        return $normalized === '' ? '' : 'fa-solid fa-'.$normalized;
    }

    /**
     * Public URL path for the synced FA solid SVG (no app base prefix).
     */
    public static function svgPublicPath(?string $icon): string
    {
        $normalized = self::normalize($icon);

        return $normalized === '' ? '' : 'icons/avatar/'.$normalized.'.svg';
    }

    /**
     * Inline SVG markup for avatar pickers/chips (uses fill=currentColor).
     */
    public static function svgMarkup(?string $icon): string
    {
        $normalized = self::normalize($icon);
        if ($normalized === '') {
            return '';
        }

        $path = dirname(__DIR__, 2).DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'icons'.DIRECTORY_SEPARATOR.'avatar'.DIRECTORY_SEPARATOR.$normalized.'.svg';
        if (! is_file($path)) {
            return '';
        }

        $svg = file_get_contents($path);
        if ($svg === false || $svg === '') {
            return '';
        }

        $svg = preg_replace('/<!--.*?-->/s', '', $svg) ?? $svg;
        $svg = preg_replace(
            '/<svg\b/',
            '<svg class="sp-avatar-icon-svg" aria-hidden="true" focusable="false"',
            $svg,
            1,
        ) ?? $svg;

        return trim($svg);
    }
}
