<?php

namespace Tests\Unit\Governance;

use App\Governance\RadarMarkdown;
use Tests\TestCase;

class RadarMarkdownTest extends TestCase
{
    public function test_renders_basic_markdown_safely(): void
    {
        $html = RadarMarkdown::toHtml("**Bold** and [link](https://binom.net)\n\n- one\n- two");

        $this->assertStringContainsString('<strong>Bold</strong>', $html);
        $this->assertStringContainsString('href="https://binom.net"', $html);
        $this->assertStringContainsString('<li>one</li>', $html);
        $this->assertStringNotContainsString('<script', $html);
    }

    public function test_strips_raw_html_input(): void
    {
        $html = RadarMarkdown::toHtml('Hello <script>alert(1)</script> world');

        $this->assertStringNotContainsString('<script', $html);
        $this->assertStringContainsString('Hello', $html);
        $this->assertStringContainsString('world', $html);
    }

    public function test_empty_markdown_returns_empty_string(): void
    {
        $this->assertSame('', RadarMarkdown::toHtml(''));
        $this->assertSame('', RadarMarkdown::toHtml(null));
    }
}
