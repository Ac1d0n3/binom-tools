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
        $response->assertSee('How to use this tool');
        $response->assertSee('View report');
        $response->assertSee('Decision, inputs, and outputs');
        $response->assertSee('What does this tool help decide?');
        $response->assertSee('Why these inputs?');
        $response->assertSee('What comes out?');
        $response->assertSee('governance-advisory-tool__explain-list', false);
        $response->assertSee('Inputs and report');
        $response->assertSee('Report summary');
        $response->assertSee('Report block');
        $response->assertSee('Save to plan');
        $response->assertSee('Back to plan');
        $response->assertSee('Copy report');
        $response->assertSee('Print/PDF');
        $response->assertSee('Save demo');
        $response->assertSee('data-governance-tool-workbench', false);
        $response->assertSee('data-apply-to-plan', false);
        $response->assertSee('data-governance-tool-print', false);
        $response->assertSee('governance-advisory-tool__field-help', false);
        $response->assertSee('data-field-help', false);
        $response->assertSee('Capture structure and field help');
        $response->assertSee('Which information belongs in the report');
        $response->assertSee('Back to Governance Hub');
        $response->assertSee('/governance', false);
        $response->assertSee('rel="canonical"', false);
    }

    public function test_governance_advisory_tool_can_render_filled_demo_values(): void
    {
        $this->get('/tools/kpi-requirements-intake?demo=finance')
            ->assertOk()
            ->assertSee('Demo-Daten geladen', false)
            ->assertSee('Net Revenue')
            ->assertSee('Welche Umsatzentwicklung soll im Monatsabschluss wirklich entschieden werden?', false)
            ->assertSee('demoPrefill', false);
    }
}
