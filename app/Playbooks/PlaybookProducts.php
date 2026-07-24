<?php

namespace App\Playbooks;

/**
 * Canonical product IDs for story/series badges and overview filters.
 */
final class PlaybookProducts
{
    /**
     * Stable display order for product badges and filter options.
     *
     * @var list<string>
     */
    public const ORDERED_IDS = [
        'snowflake',
        'dbt',
        'qlik',
        'fabric',
        'databricks',
        'powerbi',
        'pureview',
        'ai',
    ];

    /**
     * @var array<string, string>
     */
    public const LABELS = [
        'snowflake' => 'Snowflake',
        'dbt' => 'dbt',
        'qlik' => 'Qlik',
        'fabric' => 'Fabric',
        'databricks' => 'Databricks',
        'powerbi' => 'Power BI',
        'pureview' => 'PureView',
        'ai' => 'AI',
    ];

    /**
     * Badge image under public/ when available (dbt uses tools-card__dbt-badge styling).
     *
     * @var array<string, string>
     */
    public const BADGE_ASSETS = [
        'dbt' => 'images/dbt-badge.svg',
        'fabric' => 'images/fabric-badge.svg',
        'databricks' => 'images/databricks-badge.svg',
        'qlik' => 'images/qlik-badge.svg',
        'ai' => 'images/ai-badge.svg',
    ];

    /**
     * Map free-form tags to canonical product IDs.
     *
     * @var array<string, string>
     */
    public const TAG_ALIASES = [
        'snowflake' => 'snowflake',
        'dbt' => 'dbt',
        'qlik' => 'qlik',
        'qlik-sense' => 'qlik',
        'fabric' => 'fabric',
        'microsoft-fabric' => 'fabric',
        'fabric-lakehouse' => 'fabric',
        'fabric-warehouse' => 'fabric',
        'databricks' => 'databricks',
        'powerbi' => 'powerbi',
        'power-bi' => 'powerbi',
        'pureview' => 'pureview',
        'microsoft-purview' => 'pureview',
        'purview' => 'pureview',
        'ai' => 'ai',
    ];

    /**
     * Resolve products from explicit frontmatter, falling back to known tags.
     *
     * @param  list<string>|mixed  $products
     * @param  list<string>|mixed  $tags
     * @return list<string>
     */
    public static function resolve(mixed $products, mixed $tags = []): array
    {
        $fromProducts = self::normalizeList($products);

        if ($fromProducts !== []) {
            return $fromProducts;
        }

        return self::fromTags($tags);
    }

    /**
     * @param  list<string>|mixed  $tags
     * @return list<string>
     */
    public static function fromTags(mixed $tags): array
    {
        if (! is_array($tags)) {
            return [];
        }

        $resolved = [];

        foreach ($tags as $tag) {
            if (! is_string($tag)) {
                continue;
            }

            $key = self::normalizeId($tag);

            if ($key !== null && ! in_array($key, $resolved, true)) {
                $resolved[] = $key;
            }
        }

        return self::sort($resolved);
    }

    /**
     * @param  list<string>|mixed  $values
     * @return list<string>
     */
    public static function normalizeList(mixed $values): array
    {
        if (! is_array($values)) {
            return [];
        }

        $resolved = [];

        foreach ($values as $value) {
            if (! is_string($value)) {
                continue;
            }

            $key = self::normalizeId($value);

            if ($key !== null && ! in_array($key, $resolved, true)) {
                $resolved[] = $key;
            }
        }

        return self::sort($resolved);
    }

    public static function normalizeId(string $value): ?string
    {
        $key = strtolower(trim($value));

        if ($key === '') {
            return null;
        }

        $key = str_replace([' ', '_'], '-', $key);

        if (isset(self::TAG_ALIASES[$key])) {
            return self::TAG_ALIASES[$key];
        }

        if (in_array($key, self::ORDERED_IDS, true)) {
            return $key;
        }

        return null;
    }

    /**
     * @param  list<string>  $products
     * @return list<string>
     */
    public static function sort(array $products): array
    {
        $order = array_flip(self::ORDERED_IDS);

        usort($products, static function (string $a, string $b) use ($order): int {
            $posA = $order[$a] ?? PHP_INT_MAX;
            $posB = $order[$b] ?? PHP_INT_MAX;

            if ($posA !== $posB) {
                return $posA <=> $posB;
            }

            return $a <=> $b;
        });

        return array_values($products);
    }

    /**
     * @param  list<string>  $products
     * @return list<string>
     */
    public static function union(array ...$lists): array
    {
        $merged = [];

        foreach ($lists as $list) {
            foreach ($list as $product) {
                if (is_string($product) && $product !== '' && ! in_array($product, $merged, true)) {
                    $merged[] = $product;
                }
            }
        }

        return self::sort($merged);
    }

    public static function label(string $productId): string
    {
        return self::LABELS[$productId] ?? ucfirst($productId);
    }

    public static function badgeAsset(string $productId): ?string
    {
        return self::BADGE_ASSETS[$productId] ?? null;
    }
}
