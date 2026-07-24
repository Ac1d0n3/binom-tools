<?php

namespace App\Playbooks;

use Carbon\CarbonInterface;

final readonly class Playbook
{
    /**
     * @param  array<string, PlaybookLocaleVariant>  $variants
     */
    public function __construct(
        public string $slug,
        public ?string $heroUrl,
        public int $order,
        public CarbonInterface $modifiedAt,
        public array $variants,
        public ?CarbonInterface $publishedAt = null,
        public ?string $seriesId = null,
        public ?int $seriesPart = null,
        public ?PlaybookSeries $series = null,
        public ?PlaybookNavRef $prev = null,
        public ?PlaybookNavRef $next = null,
    ) {}

    public function sortDate(): CarbonInterface
    {
        return $this->publishedAt ?? $this->modifiedAt;
    }

    /**
     * Timestamp for overview sorting (also exposed as data-sort-date in cards).
     * Series parts get a small offset so equal deploy/file times still sort by part number.
     */
    public function indexSortTimestamp(): int
    {
        $timestamp = $this->sortDate()->getTimestamp();

        if ($this->seriesPart !== null && $this->seriesPart > 0) {
            $timestamp += $this->seriesPart;
        }

        return $timestamp;
    }

    public function variant(string $locale): ?PlaybookLocaleVariant
    {
        return $this->variants[$locale] ?? null;
    }

    public function title(string $locale = 'en'): string
    {
        return $this->variant($locale)?->title
            ?? $this->variant('en')?->title
            ?? $this->variant('de')?->title
            ?? $this->slug;
    }

    /**
     * @return array<string, mixed>
     */
    public function toIndexArray(): array
    {
        $locales = [];
        $tags = [];
        $products = [];

        foreach ($this->variants as $locale => $variant) {
            $locales[$locale] = [
                'title' => $variant->title,
                'description' => $variant->description,
                'category' => $variant->category,
                'readingTimeMinutes' => $variant->readingTimeMinutes,
                'tags' => $variant->tags,
                'products' => $variant->products,
                'seriesTitle' => $variant->seriesTitle,
            ];

            if ($tags === [] && $variant->tags !== []) {
                $tags = $variant->tags;
            }

            if ($products === [] && $variant->products !== []) {
                $products = $variant->products;
            }
        }

        return [
            'slug' => $this->slug,
            'heroUrl' => $this->heroUrl,
            'order' => $this->order,
            'modifiedAt' => $this->modifiedAt,
            'sortDate' => $this->sortDate(),
            'indexSortTimestamp' => $this->indexSortTimestamp(),
            'locales' => $locales,
            'tags' => $tags,
            'products' => $products,
            'seriesId' => $this->seriesId,
            'seriesPart' => $this->seriesPart,
        ];
    }
}
