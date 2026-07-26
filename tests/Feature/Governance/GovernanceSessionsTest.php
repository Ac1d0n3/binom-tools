<?php

namespace Tests\Feature\Governance;

use App\Accounts\UserRepository;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class GovernanceSessionsTest extends TestCase
{
    private string $basePath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->basePath = sys_get_temp_dir().'/bn-tools-gov-sessions-'.bin2hex(random_bytes(4));
        mkdir($this->basePath, 0775, true);
        Config::set('accounts.enabled', true);
        Config::set('accounts.path', $this->basePath);
        Config::set('storage.driver', 'file');

        app(UserRepository::class)->upsert([
            'id' => 'user_gov_owner',
            'email' => 'gov-owner@example.com',
            'displayName' => 'Governance Owner',
            'passwordHash' => password_hash('password123', PASSWORD_DEFAULT),
            'canManageUsers' => false,
            'canManageTeams' => false,
            'active' => true,
            'teamIds' => [],
        ]);
    }

    protected function tearDown(): void
    {
        $this->removeDir($this->basePath);
        parent::tearDown();
    }

    public function test_guest_cannot_persist_governance_session(): void
    {
        $this->postJson('/api/governance/sessions', [
            'session' => $this->payload(),
        ])->assertUnauthorized();
    }

    public function test_demo_report_shows_a_filled_example_session(): void
    {
        $this->get('/governance/demo-report')
            ->assertOk()
            ->assertSee('Report view')
            ->assertSee('Demo: Finance Governance Discovery')
            ->assertSee('Acme GmbH')
            ->assertSee('Management Reporting 2026')
            ->assertSee('Net Revenue')
            ->assertSee('SAP S/4HANA')
            ->assertSee('Executive Finance Dashboard')
            ->assertSee('Decision brief')
            ->assertSee('Eigene Session starten', false)
            ->assertDontSee('data-session-id="demo_finance_governance"', false);
    }

    public function test_signed_in_user_can_save_manage_report_and_create_workflow(): void
    {
        $this->login();

        $response = $this->postJson('/api/governance/sessions', [
            'session' => $this->payload(),
        ])
            ->assertOk()
            ->assertJsonPath('session.ownerUserId', 'user_gov_owner')
            ->assertJsonPath('session.validationSummary.state', 'decision_ready');

        $this->assertIsString($response->json('reportUrl'));
        $this->assertStringContainsString('/governance/sessions/gov_', $response->json('reportUrl'));
        $this->assertStringContainsString('/report', $response->json('reportUrl'));
        $this->assertIsString($response->json('sessionsUrl'));
        $this->assertStringContainsString('/governance/sessions', $response->json('sessionsUrl'));

        $saved = $response->json('session');

        $sessionId = $saved['id'];
        $this->assertMatchesRegularExpression('/^gov_/', $sessionId);

        $this->get('/governance/sessions')
            ->assertOk()
            ->assertSee('ERP Data Mart Discovery')
            ->assertSee('Beispiel-Report ansehen', false)
            ->assertSee('Governance Sessions verwalten', false);

        $this->get('/governance')
            ->assertOk()
            ->assertSee('tools-header__account-menu-item', false)
            ->assertSee('Governance Sessions')
            ->assertDontSee('tools-sidenav__link--active" data-text-de="Governance Sessions"', false);

        $this->get('/governance/sessions/'.$sessionId.'/report')
            ->assertOk()
            ->assertSee('Report view')
            ->assertSee('Print/PDF')
            ->assertSee('Governance Stack Advisor')
            ->assertSee('KPI cards')
            ->assertSee('Net Revenue')
            ->assertSee('Source scope')
            ->assertSee('SAP S/4HANA')
            ->assertSee('PII/DSDR')
            ->assertSee('Decision brief')
            ->assertSee('Fabric finance mart')
            ->assertSee('In Workflow übernehmen', false);

        $plan = $this->postJson('/api/governance/sessions/'.$sessionId.'/create-plan')
            ->assertOk()
            ->assertJsonPath('plan.templateSlug', 'governance-discovery-session')
            ->json('plan');

        $this->assertSame($sessionId, $plan['fieldValues']['governanceSessionId']);
        $this->assertNotEmpty($plan['templateSnapshot']['sprints']);

        $copy = $this->postJson('/api/governance/sessions/'.$sessionId.'/duplicate')
            ->assertOk()
            ->json('session');
        $this->assertNotSame($sessionId, $copy['id']);

        $this->postJson('/api/governance/sessions/'.$sessionId.'/archive')
            ->assertOk()
            ->assertJsonPath('session.status', 'archived');
    }

    public function test_data_quality_session_is_reported_and_carried_into_workflow(): void
    {
        $this->login();

        $saved = $this->postJson('/api/governance/sessions', [
            'session' => $this->dataQualityPayload(),
        ])
            ->assertOk()
            ->assertJsonPath('session.validationSummary.state', 'decision_ready')
            ->assertJsonPath('session.payload.dataQuality.mode', 'report_stabilization')
            ->assertJsonPath('session.payload.dataQuality.layer', 'bi')
            ->json('session');

        $sessionId = $saved['id'];

        $this->get('/governance/sessions/'.$sessionId.'/report')
            ->assertOk()
            ->assertSee('Data quality')
            ->assertSee('report_stabilization')
            ->assertSee('quality_gate')
            ->assertSee('critical report freshness');

        $plan = $this->postJson('/api/governance/sessions/'.$sessionId.'/create-plan')
            ->assertOk()
            ->assertJsonPath('plan.fieldValues.dataQualityMode', 'report_stabilization')
            ->assertJsonPath('plan.fieldValues.dataQualityLayer', 'bi')
            ->assertJsonPath('plan.fieldValues.changeApprovalRequired', true)
            ->json('plan');

        $this->assertContains('metadata-driven-governance-with-dbt-meta', $plan['linkedStorySlugs']);
        $this->assertSame('quality-and-model', $plan['templateSnapshot']['sprints'][2]['id']);
    }

    private function login(): void
    {
        $this->post('/login', [
            'email' => 'gov-owner@example.com',
            'password' => 'password123',
        ])->assertRedirect('/');
    }

    private function payload(): array
    {
        return [
            'title' => 'ERP Data Mart Discovery',
            'companyName' => 'Acme GmbH',
            'projectName' => 'Finance Mart',
            'scenario' => 'new',
            'payload' => [
                'advisor' => [
                    'scenario' => 'new',
                    'goal' => 'stack',
                    'domain' => 'erp',
                    'platform' => 'fabric',
                ],
                'recommendations' => [
                    [
                        'id' => 'governance-stack-advisor',
                        'group' => 'tools',
                        'title' => 'Governance Stack Advisor',
                        'reason' => 'Stack shortlist.',
                        'url' => '/tools/governance-stack-advisor',
                    ],
                ],
                'kpis' => [
                    [
                        'name' => 'Net Revenue',
                        'formula' => 'Invoice amount minus credit notes.',
                        'grain' => 'Company, customer, month',
                        'owner' => 'Finance Owner',
                        'status' => 'agreed',
                    ],
                ],
                'sourceScope' => [
                    'supplier' => 'SAP S/4HANA',
                    'mustHave' => ['Billing documents'],
                    'optional' => ['Sales orders'],
                    'skip' => ['Attachments'],
                    'owners' => ['Finance Owner'],
                ],
                'pii' => [
                    'fields' => ['contact_email'],
                    'dsdrKeys' => ['customer_id'],
                    'controls' => ['masking'],
                ],
                'decisionBrief' => [
                    'recommendation' => 'Stabilize Fabric finance mart first.',
                    'openQuestions' => ['cancellation logic'],
                    'nextSprint' => ['DQ rules'],
                ],
            ],
        ];
    }

    private function dataQualityPayload(): array
    {
        return [
            'title' => 'DQ Report Stabilisierung',
            'companyName' => 'Acme GmbH',
            'projectName' => 'Management Reporting',
            'scenario' => 'help',
            'payload' => [
                'advisor' => [
                    'scenario' => 'help',
                    'goal' => 'dq',
                    'domain' => 'erp',
                    'platform' => 'fabric',
                    'dqMode' => 'report_stabilization',
                    'dqLayer' => 'bi',
                    'dqIssues' => ['freshness', 'business_rule'],
                ],
                'dataQuality' => [
                    'mode' => 'report_stabilization',
                    'layer' => 'bi',
                    'issueTypes' => ['freshness', 'business_rule'],
                    'affectedSources' => ['SAP S/4HANA'],
                    'affectedKpis' => ['Net revenue'],
                    'affectedReports' => ['Executive dashboard'],
                    'proposedRules' => ['critical report freshness', 'quality_gate'],
                    'validationFindings' => [],
                    'decisionStatus' => 'draft',
                ],
                'recommendations' => [
                    [
                        'id' => 'dbt-dq-history-generator',
                        'group' => 'tools',
                        'title' => 'DQ History Generator',
                        'reason' => 'Track freshness and recurring report issues.',
                        'url' => '/tools/dbt-dq-history-generator',
                    ],
                ],
            ],
        ];
    }

    private function removeDir(string $dir): void
    {
        if (! is_dir($dir)) {
            return;
        }
        $items = scandir($dir) ?: [];
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $path = $dir.DIRECTORY_SEPARATOR.$item;
            if (is_dir($path)) {
                $this->removeDir($path);
            } else {
                @unlink($path);
            }
        }
        @rmdir($dir);
    }
}
