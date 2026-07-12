<?php

namespace App\Playbooks;

final readonly class PlaybookSeriesOverview
{
    /**
     * @param  list<PlaybookSeriesPart>  $parts
     */
    public function __construct(
        public string $id,
        public string $titleDe,
        public string $titleEn,
        public ?string $heroUrl,
        public int $totalReadingTimeDe,
        public int $totalReadingTimeEn,
        public array $parts,
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
