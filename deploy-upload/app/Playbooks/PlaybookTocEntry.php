<?php

namespace App\Playbooks;

final readonly class PlaybookTocEntry
{
    public function __construct(
        public string $id,
        public string $text,
        public int $level,
    ) {}
}
