<?php

namespace App\Playbooks;

final readonly class PlaybookSeriesOverview
{
    /**
     * @param  list<PlaybookSeriesPart>  $parts
     * @param  list<string>  $products
     */
    public function __construct(
        public string $id,
        public string $titleDe,
        public string $titleEn,
        public ?string $heroUrl,
        public int $modifiedAt,
        public int $totalReadingTimeDe,
        public int $totalReadingTimeEn,
        public array $parts,
        public array $products = [],
    ) {}

    public function title(string $locale = 'en'): string
    {
        return $locale === 'de' ? $this->titleDe : $this->titleEn;
    }

    public function totalReadingTime(string $locale = 'en'): int
    {
        return $locale === 'de' ? $this->totalReadingTimeDe : $this->totalReadingTimeEn;
    }

    public function partCount(): int
    {
        return count($this->parts);
    }

    public function firstPart(): ?PlaybookSeriesPart
    {
        return $this->parts[0] ?? null;
    }
}
