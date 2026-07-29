<?php

namespace Tests\Unit\Playbooks;

use App\Playbooks\Playbook;
use App\Playbooks\PlaybookLocaleVariant;
use App\Playbooks\PlaybookSlidesCatalog;
use Carbon\Carbon;
use Tests\TestCase;

class PlaybookSlidesCatalogTest extends TestCase
{
    public function test_skips_hero_images_and_extracts_body_diagrams(): void
    {
        $playbook = $this->playbook(
            slug: 'demo-story',
            bodyHtml: <<<'HTML'
<p>Intro</p>
<img src="images/playbooks/demo-story-hero.png" alt="Hero" class="playbook-prose__image" />
<figure class="playbook-prose__figure">
  <img src="images/playbooks/demo-story-img1-en.png" alt="Flow" class="playbook-prose__image playbook-prose__image--diagram" />
  <figcaption class="playbook-prose__figure-caption">Architecture flow</figcaption>
</figure>
HTML,
            seriesId: null,
            seriesPart: null,
            seriesTitle: null,
            title: 'Demo Story',
        );

        $slides = (new PlaybookSlidesCatalog)->build([$playbook], 'en');

        $this->assertCount(1, $slides);
        $this->assertStringContainsString('demo-story-img1-en.png', $slides[0]['src']);
        $this->assertSame('Architecture flow', $slides[0]['caption']);
        $this->assertSame('demo-story', $slides[0]['storySlug']);
        $this->assertSame('Demo Story', $slides[0]['storyTitle']);
        $this->assertNull($slides[0]['seriesId']);
        $this->assertStringContainsString('/playbooks/demo-story', $slides[0]['storyUrl']);
    }

    public function test_sorts_by_series_then_part_then_standalone_by_title(): void
    {
        $seriesLate = $this->playbook(
            slug: 'zebra-part-2',
            bodyHtml: '<img src="images/playbooks/zebra-part-2-img1-en.png" alt="Z2" class="playbook-prose__image" />',
            seriesId: 'zebra-series',
            seriesPart: 2,
            seriesTitle: 'Zebra Series',
            title: 'Zebra Part 2',
        );
        $seriesEarly = $this->playbook(
            slug: 'zebra-part-1',
            bodyHtml: '<img src="images/playbooks/zebra-part-1-img1-en.png" alt="Z1" class="playbook-prose__image" />',
            seriesId: 'zebra-series',
            seriesPart: 1,
            seriesTitle: 'Zebra Series',
            title: 'Zebra Part 1',
        );
        $alphaSeries = $this->playbook(
            slug: 'alpha-part-1',
            bodyHtml: '<img src="images/playbooks/alpha-part-1-img1-en.png" alt="A1" class="playbook-prose__image" />',
            seriesId: 'alpha-series',
            seriesPart: 1,
            seriesTitle: 'Alpha Series',
            title: 'Alpha Part 1',
        );
        $standaloneB = $this->playbook(
            slug: 'standalone-b',
            bodyHtml: '<img src="images/playbooks/standalone-b-img1-en.png" alt="B" class="playbook-prose__image" />',
            seriesId: null,
            seriesPart: null,
            seriesTitle: null,
            title: 'Bravo Standalone',
        );
        $standaloneA = $this->playbook(
            slug: 'standalone-a',
            bodyHtml: '<img src="images/playbooks/standalone-a-img1-en.png" alt="A" class="playbook-prose__image" />',
            seriesId: null,
            seriesPart: null,
            seriesTitle: null,
            title: 'Alpha Standalone',
        );

        $slides = (new PlaybookSlidesCatalog)->build(
            [$seriesLate, $standaloneB, $seriesEarly, $standaloneA, $alphaSeries],
            'en',
        );

        $this->assertSame(
            [
                'alpha-part-1',
                'zebra-part-1',
                'zebra-part-2',
                'standalone-a',
                'standalone-b',
            ],
            array_column($slides, 'storySlug'),
        );
        $this->assertSame('Alpha Series', $slides[0]['seriesTitle']);
        $this->assertStringContainsString('/playbooks/series/alpha-series', (string) $slides[0]['seriesUrl']);
        $this->assertSame(1, $slides[1]['seriesPart']);
        $this->assertSame(2, $slides[2]['seriesPart']);
        $this->assertNull($slides[3]['seriesId']);
    }

    public function test_preserves_document_order_within_same_story(): void
    {
        $playbook = $this->playbook(
            slug: 'ordered',
            bodyHtml: <<<'HTML'
<img src="images/playbooks/ordered-img1-en.png" alt="One" class="playbook-prose__image" />
<img src="images/playbooks/ordered-img2-en.png" alt="Two" class="playbook-prose__image" />
HTML,
            seriesId: null,
            seriesPart: null,
            seriesTitle: null,
            title: 'Ordered',
        );

        $slides = (new PlaybookSlidesCatalog)->build([$playbook], 'en');

        $this->assertCount(2, $slides);
        $this->assertStringContainsString('ordered-img1-en.png', $slides[0]['src']);
        $this->assertStringContainsString('ordered-img2-en.png', $slides[1]['src']);
    }

    private function playbook(
        string $slug,
        string $bodyHtml,
        ?string $seriesId,
        ?int $seriesPart,
        ?string $seriesTitle,
        string $title,
    ): Playbook {
        $variant = new PlaybookLocaleVariant(
            locale: 'en',
            title: $title,
            description: 'Desc',
            category: null,
            tags: [],
            products: [],
            bodyHtml: $bodyHtml,
            toc: [],
            readingTimeMinutes: 1,
            heroUrl: null,
            series: $seriesId,
            seriesPart: $seriesPart,
            seriesTitle: $seriesTitle,
        );

        return new Playbook(
            slug: $slug,
            heroUrl: null,
            order: 0,
            modifiedAt: Carbon::now(),
            variants: ['en' => $variant],
            publishedAt: Carbon::now(),
            seriesId: $seriesId,
            seriesPart: $seriesPart,
        );
    }
}
