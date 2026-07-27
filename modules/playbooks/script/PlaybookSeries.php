<?php

namespace App\Playbooks;

final readonly class PlaybookSeries
{
    /**
     * @param  list<PlaybookSeriesPart>  $parts
     */
    public function __construct(
        public string $id,
        public string $titleDe,
        public string $titleEn,
        public array $parts,
        public int $currentPart,
    ) {}

    public function title(string $locale = 'en'): string
    {
        return $locale === 'de' ? $this->titleDe : $this->titleEn;
    }

    public function totalParts(): int
    {
        return count($this->parts);
    }
}
