<?php

namespace App\Governance;

use Illuminate\Support\Str;

/**
 * Safe markdown for radar news summaries (plain text still works).
 */
final class RadarMarkdown
{
    public static function toHtml(?string $markdown): string
    {
        $markdown = trim((string) $markdown);
        if ($markdown === '') {
            return '';
        }

        return Str::markdown($markdown, [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);
    }
}
