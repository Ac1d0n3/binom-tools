<?php

namespace Tests\Feature\Tools;

use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class GovernanceAdvisoryToolsTest extends TestCase
{
    /**
     * @return list<array{0: string, 1: string}>
     */
    public static function toolPages(): array
    {
        return [
            ['/tools/kpi-requirements-intake', 'KPI Requirements Intake Form'],
            ['/tools/source-scope-builder', 'Source Scope Builder'],
            ['/tools/mart-design-brief-generator', 'Mart Design Brief Generator'],
            ['/tools/governance-stack-advisor', 'Governance Stack Advisor'],
            ['/tools/pii-dsdr-readiness-checker', 'PII/DSDR Readiness Checker'],
            ['/tools/decision-brief-generator', 'Decision Brief Generator'],
            ['/tools/vendor-learning-path-builder', 'Vendor Learning Path Builder'],
        ];
    }

    #[DataProvider('toolPages')]
    public function test_governance_advisory_tool_pages_render(string $path, string $title): void
    {
        $response = $this->get($path);

        $response->assertOk();
        $response->assertSee($title);
        $response->assertSee('governance-advisory-tool', false);
        $response->assertSee('Decision question');
        $response->assertSee('What does this tool help decide?');
        $response->assertSee('Inputs');
        $response->assertSee('Outputs');
        $response->assertSee('Capture structure');
        $response->assertSee('Back to Governance Hub');
        $response->assertSee('/governance', false);
        $response->assertSee('rel="canonical"', false);
    }
}
