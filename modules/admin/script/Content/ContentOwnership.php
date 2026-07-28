<?php

namespace App\Admin\Content;

/**
 * Read/write createdByUserId on markdown front matter and catalog rows.
 */
final class ContentOwnership
{
    public static function ownerFromMarkdown(string $raw): ?string
    {
        if (preg_match('/(?:^|\n)createdByUserId:\s*["\']?([a-zA-Z0-9_-]+)["\']?\s*(?:\n|$)/', $raw, $matches) !== 1) {
            return null;
        }

        $id = trim((string) $matches[1]);

        return $id !== '' ? $id : null;
    }

    /**
     * Ensure createdByUserId is present in YAML front matter (insert or keep).
     */
    public static function ensureMarkdownOwner(string $raw, string $userId, bool $onlyIfMissing = true): string
    {
        $userId = trim($userId);
        if ($userId === '') {
            return $raw;
        }

        $existing = self::ownerFromMarkdown($raw);
        if ($onlyIfMissing && $existing !== null) {
            return $raw;
        }

        $raw = ltrim($raw, "\xEF\xBB\xBF");
        if (! str_starts_with($raw, "---\n") && ! str_starts_with($raw, "---\r\n")) {
            return "---\ncreatedByUserId: {$userId}\n---\n\n".$raw;
        }

        if ($existing !== null) {
            return (string) preg_replace(
                '/(?:^|\n)createdByUserId:\s*["\']?[a-zA-Z0-9_-]+["\']?\s*(?=\n|$)/',
                "\ncreatedByUserId: {$userId}",
                $raw,
                1
            );
        }

        // Insert after opening ---
        return (string) preg_replace(
            '/^---\r?\n/',
            "---\ncreatedByUserId: {$userId}\n",
            $raw,
            1
        );
    }

    /**
     * @param  array<string, mixed>  $row
     */
    public static function ownerFromRow(array $row): ?string
    {
        $id = $row['createdByUserId'] ?? null;
        if (! is_string($id)) {
            return null;
        }
        $id = trim($id);

        return $id !== '' ? $id : null;
    }

    /**
     * @param  array<string, mixed>  $row
     * @return array<string, mixed>
     */
    public static function stampRow(array $row, string $userId, bool $onlyIfMissing = true): array
    {
        if ($onlyIfMissing && self::ownerFromRow($row) !== null) {
            return $row;
        }
        $row['createdByUserId'] = $userId;

        return $row;
    }
}
