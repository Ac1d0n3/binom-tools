<?php

namespace Tests\Unit\Playbooks;

use App\Playbooks\PlaybookCodeFenceParser;
use PHPUnit\Framework\TestCase;

class PlaybookCodeFenceParserTest extends TestCase
{
    public function test_parses_language_title_and_highlight_lines(): void
    {
        $meta = PlaybookCodeFenceParser::parse('php title="PlaybookRepository.php" {3-6}');

        $this->assertSame('php', $meta['language']);
        $this->assertSame('PlaybookRepository.php', $meta['title']);
        $this->assertSame('3-6', $meta['highlight']);
    }
}
