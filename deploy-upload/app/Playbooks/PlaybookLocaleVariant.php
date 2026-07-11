<?php

namespace App\Playbooks;

final readonly class PlaybookLocaleVariant
{
    /**
     * @param  list<string>  $tags
     * @param  list<PlaybookTocEntry>  $toc
     */
    public function __construct(
        public string $locale,
        public string $title,
        public string $description,
        public ?string $category,
        public array $tags,
        public string $bodyHtml,
        public array $toc,
        public int $readingTimeMinutes,
        public ?string $heroUrl = null,
    ) {}
}
