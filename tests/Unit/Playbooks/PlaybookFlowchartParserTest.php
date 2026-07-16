<?php

namespace Tests\Unit\Playbooks;

use App\Playbooks\PlaybookFlowchartParser;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PlaybookFlowchartParserTest extends TestCase
{
    public function test_parses_chevron_flowchart_steps(): void
    {
        $parsed = PlaybookFlowchartParser::parse('flowchart chevron', <<<'MD'
1. Collect metadata
Profile data [active]
Publish catalog
MD);

        $this->assertNotNull($parsed);
        $this->assertSame('chevron', $parsed['variant']);
        $this->assertSame('horizontal', $parsed['layout']);
        $this->assertCount(3, $parsed['steps']);
        $this->assertSame('Collect metadata', $parsed['steps'][0]['label']);
        $this->assertNull($parsed['steps'][0]['state']);
        $this->assertSame('active', $parsed['steps'][1]['state']);
        $this->assertSame('Publish catalog', $parsed['steps'][2]['label']);
        $this->assertNull($parsed['steps'][2]['state']);
    }

    public function test_accepts_flow_alias_and_linear_variant(): void
    {
        $parsed = PlaybookFlowchartParser::parse('flow linear', <<<'MD'
- Source
- Transform
- Report
MD);

        $this->assertNotNull($parsed);
        $this->assertSame('linear', $parsed['variant']);
        $this->assertSame('horizontal', $parsed['layout']);
        $this->assertSame('Transform', $parsed['steps'][1]['label']);
    }

    public function test_parses_vertical_layout_in_any_token_order(): void
    {
        $parsed = PlaybookFlowchartParser::parse('flow vertical linear', <<<'MD'
Focused departmental facts
Shared governed enterprise model
Purpose-built applications
MD);

        $this->assertNotNull($parsed);
        $this->assertSame('linear', $parsed['variant']);
        $this->assertSame('vertical', $parsed['layout']);
        $this->assertCount(3, $parsed['steps']);
    }

    public function test_skips_arrow_only_rows(): void
    {
        $parsed = PlaybookFlowchartParser::parse('flow linear vertical', <<<'MD'
Step A
            ↓
Step B
            →
Step C
            ↺
MD);

        $this->assertNotNull($parsed);
        $this->assertCount(3, $parsed['steps']);
        $this->assertSame('Step A', $parsed['steps'][0]['label']);
        $this->assertSame('Step B', $parsed['steps'][1]['label']);
        $this->assertSame('Step C', $parsed['steps'][2]['label']);
    }

    #[DataProvider('invalidFlowchartBodies')]
    public function test_rejects_invalid_flowcharts(string $body): void
    {
        $this->assertNull(PlaybookFlowchartParser::parse('flowchart', $body));
    }

    /** @return array<string, array{0: string}> */
    public static function invalidFlowchartBodies(): array
    {
        return [
            'single step' => ['Only one step'],
            'empty' => [''],
        ];
    }

    public function test_returns_null_for_non_flowchart_fence(): void
    {
        $this->assertFalse(PlaybookFlowchartParser::isFlowchartFence('video'));
        $this->assertNull(PlaybookFlowchartParser::parse('video', "Step A\nStep B"));
    }

    public function test_marks_completed_steps(): void
    {
        $parsed = PlaybookFlowchartParser::parse('flowchart', "Start\nReview [done]\nFinish");

        $this->assertNotNull($parsed);
        $this->assertSame('completed', $parsed['steps'][1]['state']);
    }
}
