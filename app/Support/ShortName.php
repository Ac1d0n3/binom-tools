<?php

namespace App\Support;

/**
 * Shared short-name (trigram) normalization: empty or 2–3 A–Z letters.
 */
final class ShortName
{
    public static function normalize(?string $value): string
    {
        $raw = strtoupper(preg_replace('/[^A-Za-z]/', '', (string) $value) ?? '');
        $len = strlen($raw);
        if ($len < 2) {
            return '';
        }

        return substr($raw, 0, 3);
    }

    /**
     * @return list<string>
     */
    public static function rules(): array
    {
        return ['nullable', 'string', 'max:3', 'regex:/^$|^[A-Za-z]{2,3}$/'];
    }
}
