<?php

namespace App\Foundations;

/**
 * Thin TaxonomyFoundation helper — file config only, no database.
 */
final class Taxonomy
{
    /**
     * @return list<string>
     */
    public static function ids(string $facet): array
    {
        $bucket = config('foundations.taxonomy.'.$facet, []);

        if (! is_array($bucket)) {
            return [];
        }

        return array_values(array_map('strval', array_keys($bucket)));
    }

    public static function label(string $facet, string $id, string $locale = 'en'): string
    {
        $locale = str_starts_with(strtolower($locale), 'de') ? 'de' : 'en';
        $entry = config('foundations.taxonomy.'.$facet.'.'.$id);

        if (! is_array($entry)) {
            return $id;
        }

        $label = $entry[$locale] ?? $entry['en'] ?? $entry['de'] ?? $id;

        return is_string($label) && $label !== '' ? $label : $id;
    }

    /**
     * @return array<string, array{de: string, en: string}>
     */
    public static function facet(string $facet): array
    {
        $bucket = config('foundations.taxonomy.'.$facet, []);

        return is_array($bucket) ? $bucket : [];
    }
}
