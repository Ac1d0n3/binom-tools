<?php

namespace App\Accounts;

/**
 * Admin content area keys and helpers for canManageContent / contentAreas.
 */
final class ContentAreas
{
    public const STORIES = 'stories';

    public const PLAN_TEMPLATES = 'planTemplates';

    public const VENDORS_SOURCES = 'vendorsSources';

    public const NEWS = 'news';

    public const GLOSSARY = 'glossary';

    /**
     * @return list<string>
     */
    public static function keys(): array
    {
        return [
            self::STORIES,
            self::PLAN_TEMPLATES,
            self::VENDORS_SOURCES,
            self::NEWS,
            self::GLOSSARY,
        ];
    }

    /**
     * @param  array<string, mixed>|null  $raw
     * @return array<string, bool>
     */
    public static function normalize(?array $raw, bool $defaultAll = false): array
    {
        $out = [];
        foreach (self::keys() as $key) {
            if (is_array($raw) && array_key_exists($key, $raw)) {
                $out[$key] = (bool) $raw[$key];
            } else {
                $out[$key] = $defaultAll;
            }
        }

        return $out;
    }

    /**
     * @param  array<string, mixed>  $input  Request-style contentAreas[stories]=1
     * @return array<string, bool>
     */
    public static function fromRequestInput(mixed $input): array
    {
        $raw = is_array($input) ? $input : [];

        return self::normalize($raw, false);
    }
}
