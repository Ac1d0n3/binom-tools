<?php

namespace Tests\Unit\Admin;

use App\Admin\Content\MarkdownContentWriter;
use App\Admin\Content\StoryDraftTemplates;
use PHPUnit\Framework\TestCase;

class StoryDraftTemplatesTest extends TestCase
{
    private string $dir;

    private StoryDraftTemplates $drafts;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dir = sys_get_temp_dir().'/bn-story-drafts-'.bin2hex(random_bytes(4));
        mkdir($this->dir, 0775, true);
        $writer = new MarkdownContentWriter($this->dir);
        $this->drafts = new StoryDraftTemplates($writer);

        $writer->write('pillar-one', 'en', <<<'MD'
---
title: Pillar One
author: Thomas Lindackers
category: Data Governance
tags:
  - data-governance
  - pillars
order: -1
publishedAt: 2026-01-01
series: governance-pillars
seriesPart: 1
seriesTitle: The 8 Pillars of Data Governance
hero: images/playbooks/p1.png
---

## One
MD);
        $writer->write('pillar-one', 'de', <<<'MD'
---
title: Säule Eins
author: Thomas Lindackers
category: Data Governance
tags:
  - data-governance
series: governance-pillars
seriesPart: 1
seriesTitle: Die 8 Säulen der Data Governance
---

## Eins
MD);
        $writer->write('pillar-two', 'en', <<<'MD'
---
title: Pillar Two
series: governance-pillars
seriesPart: 2
seriesTitle: The 8 Pillars of Data Governance
category: Data Governance
tags:
  - data-governance
---

## Two
MD);
        $writer->write('standalone', 'en', <<<'MD'
---
title: Alone
author: Someone
---

Body
MD);
    }

    protected function tearDown(): void
    {
        foreach (glob($this->dir.DIRECTORY_SEPARATOR.'*') ?: [] as $file) {
            @unlink($file);
        }
        @rmdir($this->dir);
        parent::tearDown();
    }

    public function test_single_draft_has_header_without_series(): void
    {
        $draft = $this->drafts->draft('single');

        $this->assertSame('single', $draft['template']);
        $this->assertNull($draft['seriesId']);
        $this->assertStringContainsString('title: ""', $draft['bodyEn']);
        $this->assertStringContainsString('author: Thomas Lindackers', $draft['bodyEn']);
        $this->assertStringContainsString('hero: images/playbooks/', $draft['bodyEn']);
        $this->assertStringNotContainsString('series:', $draft['bodyEn']);
        $this->assertStringContainsString('## Headline', $draft['bodyEn']);
        $this->assertStringContainsString('## Überschrift', $draft['bodyDe']);
    }

    public function test_lists_existing_series_with_next_part(): void
    {
        $series = $this->drafts->listSeries();

        $this->assertCount(1, $series);
        $this->assertSame('governance-pillars', $series[0]['id']);
        $this->assertSame('The 8 Pillars of Data Governance', $series[0]['title']);
        $this->assertSame(2, $series[0]['parts']);
        $this->assertSame(3, $series[0]['nextPart']);
    }

    public function test_series_draft_inherits_shared_fields(): void
    {
        $draft = $this->drafts->draft('series', 'governance-pillars');

        $this->assertSame('series', $draft['template']);
        $this->assertSame('governance-pillars', $draft['seriesId']);
        $this->assertStringContainsString('series: governance-pillars', $draft['bodyEn']);
        $this->assertStringContainsString('seriesPart: 3', $draft['bodyEn']);
        $this->assertStringContainsString('seriesTitle: The 8 Pillars of Data Governance', $draft['bodyEn']);
        $this->assertStringContainsString('seriesTitle: Die 8 Säulen der Data Governance', $draft['bodyDe']);
        $this->assertStringContainsString('category: Data Governance', $draft['bodyEn']);
        $this->assertStringContainsString('- data-governance', $draft['bodyEn']);
        $this->assertStringContainsString('title: ""', $draft['bodyEn']);
        $this->assertStringContainsString('## Headline', $draft['bodyEn']);
    }

    public function test_new_series_draft_leaves_series_fields_blank(): void
    {
        $draft = $this->drafts->draft('series', null);

        $this->assertSame('series', $draft['template']);
        $this->assertNull($draft['seriesId']);
        $this->assertStringContainsString('series: ""', $draft['bodyEn']);
        $this->assertStringContainsString('seriesPart: 1', $draft['bodyEn']);
        $this->assertStringContainsString('seriesTitle: ""', $draft['bodyEn']);
    }
}
