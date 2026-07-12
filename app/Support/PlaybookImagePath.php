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
            return 'images/playbooks/'.substr($path, strlen('images/stories/'));
        }

        return $path;
    }

    public static function assetUrl(?string $path): ?string
    {
        $normalized = self::normalize($path);

        return $normalized !== null ? asset($normalized) : null;
    }
}
