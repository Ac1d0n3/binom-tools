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
        public ?string $seriesId = null,
        public ?int $seriesPart = null,
        public ?PlaybookSeries $series = null,
        public ?PlaybookNavRef $prev = null,
        public ?PlaybookNavRef $next = null,
    ) {}

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

        foreach ($this->variants as $locale => $variant) {
            $locales[$locale] = [
                'title' => $variant->title,
                'description' => $variant->description,
                'category' => $variant->category,
                'readingTimeMinutes' => $variant->readingTimeMinutes,
                'tags' => $variant->tags,
            ];

            if ($tags === [] && $variant->tags !== []) {
                $tags = $variant->tags;
            }
        }

        return [
            'slug' => $this->slug,
            'heroUrl' => $this->heroUrl,
            'order' => $this->order,
            'modifiedAt' => $this->modifiedAt,
            'locales' => $locales,
            'tags' => $tags,
        ];
    }
}
