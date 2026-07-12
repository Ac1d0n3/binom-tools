<?php

namespace App\Playbooks;

final readonly class PlaybookSeriesPart
{
    public function __construct(
        public string $slug,
        public int $part,
        public string $titleDe,
        public string $titleEn,
        public int $readingTimeDe = 0,
        public int $readingTimeEn = 0,
        public bool $isCurrent = false,
    ) {}

    public function title(string $locale = 'en'): string
    {
        return $locale === 'de' ? $this->titleDe : $this->titleEn;
    }

    public function readingTime(string $locale = 'en'): int
    {
        return $locale === 'de' ? $this->readingTimeDe : $this->readingTimeEn;
    }
}
