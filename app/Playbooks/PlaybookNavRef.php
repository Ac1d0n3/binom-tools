<?php

namespace App\Playbooks;

final readonly class PlaybookNavRef
{
    public function __construct(
        public string $slug,
        public string $title,
    ) {}
}
