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
}
