<?php

namespace Tests\Unit\Playbooks;

use App\Playbooks\PlaybookFrontmatterParser;
use PHPUnit\Framework\TestCase;

class PlaybookFrontmatterParserTest extends TestCase
{
    public function test_parses_frontmatter_fields_and_tags_list(): void
    {
        $parser = new PlaybookFrontmatterParser;

        $raw = <<<'MD'
---
title: Custom Title
description: Custom description
hero: images/playbooks/example.svg
category: Platform
tags:
  - alpha
  - beta
order: 5
---

Body content
MD;

        $parsed = $parser->parse($raw, 'example-slug');

        $this->assertSame('Custom Title', $parsed['meta']['title']);
        $this->assertSame('Custom description', $parsed['meta']['description']);
        $this->assertSame('images/playbooks/example.svg', $parsed['meta']['hero']);
        $this->assertSame('Platform', $parsed['meta']['category']);
        $this->assertSame(['alpha', 'beta'], $parsed['meta']['tags']);
        $this->assertSame(5, $parsed['meta']['order']);
        $this->assertStringContainsString('Body content', $parsed['body']);
    }

    public function test_uses_slug_fallback_when_frontmatter_missing(): void
    {
        $parser = new PlaybookFrontmatterParser;

        $parsed = $parser->parse("# Heading\n", 'snowflake-governance');

        $this->assertSame('Snowflake Governance', $parsed['meta']['title']);
        $this->assertSame('', $parsed['meta']['description']);
        $this->assertSame([], $parsed['meta']['tags']);
    }
}
