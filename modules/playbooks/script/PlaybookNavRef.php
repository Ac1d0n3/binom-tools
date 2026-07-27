<?php

namespace App\Playbooks;

final readonly class PlaybookNavRef
{
    public function __construct(
        public string $slug,
        public string $titleDe,
        public string $titleEn,
    ) {}

    public function title(string $locale = 'en'): string
    {
        return $locale === 'de' ? $this->titleDe : $this->titleEn;
    }
}
