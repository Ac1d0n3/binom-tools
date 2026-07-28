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

    public function test_signed_in_user_can_manage_governance_radar_sources(): void
    {
        $this->login();

        $this->get('/governance/radar')
            ->assertOk()
            ->assertSee('data-governance-radar-admin', false)
            ->assertSee('RSS-Quellen verwalten', false)
            ->assertDontSee('Feed Registry');

        $response = $this->postJson('/api/governance/radar/sources', [
            'name' => 'Acme Governance News',
            'feedUrl' => 'https://example.com/governance/rss.xml',
            'type' => 'Eigene Quelle',
            'topics' => ['Data Quality', 'PII'],
        ])
            ->assertOk()
            ->assertJsonPath('source.name', 'Acme Governance News')
            ->assertJsonPath('source.feedUrl', 'https://example.com/governance/rss.xml');

        $sourceId = $response->json('source.id');
        $this->assertMatchesRegularExpression('/^radsrc_/', $sourceId);

        $this->getJson('/api/governance/radar/sources')
            ->assertOk()
            ->assertJsonCount(1, 'sources')
            ->assertJsonPath('sources.0.name', 'Acme Governance News');

        $this->deleteJson('/api/governance/radar/sources/'.$sourceId)
            ->assertOk()
            ->assertJsonCount(0, 'sources');
    }

    public function test_non_admin_cannot_manage_radar_item_overlays(): void
    {
        $this->login();

        $this->get('/governance/radar')
            ->assertOk()
            ->assertDontSee('data-governance-radar-enrich', false)
            ->assertDontSee('data-radar-overlays-api-url', false);

        $this->putJson('/api/governance/radar/items/edpb-ai-anonymisierung-blockchain/overlay', [
            'titleDe' => 'Test',
        ])->assertForbidden();
    }

    public function test_admin_can_save_and_delete_radar_item_overlay(): void
    {
        app(UserRepository::class)->upsert([
            'id' => 'user_gov_admin',
            'email' => 'gov-admin@example.com',
            'displayName' => 'Governance Admin',
            'passwordHash' => password_hash('password123', PASSWORD_DEFAULT),
            'canManageUsers' => true,
            'canManageTeams' => true,
            'active' => true,
            'teamIds' => [],
        ]);

        $this->post('/login', [
            'email' => 'gov-admin@example.com',
            'password' => 'password123',
        ])->assertRedirect();

        $this->get('/governance/radar')
            ->assertOk()
            ->assertSee('data-radar-overlays-api-url', false)
            ->assertSee('data-governance-radar-enrich', false)
            ->assertSee('data-origin="vendor"', false);

        $itemId = 'edpb-ai-anonymisierung-blockchain';
        $this->putJson('/api/governance/radar/items/'.$itemId.'/overlay', [
            'titleDe' => 'EDPB Leitlinien kuratiert',
            'summaryDe' => 'Kurz angereicherte DE-Zusammenfassung für Governance-Reviews.',
            'recommendedActionDe' => 'Gegen eigene AI-Use-Cases spiegeln.',
            'editorialNote' => 'Admin-Notiz',
            'impact' => 'Prüfen',
        ])
            ->assertOk()
            ->assertJsonPath('overlay.titleDe', 'EDPB Leitlinien kuratiert')
            ->assertJsonPath('overlay.editorialNote', 'Admin-Notiz');

        app()->setLocale('de');
        $this->get('/governance/radar')
            ->assertOk()
            ->assertSee('EDPB Leitlinien kuratiert', false)
            ->assertSee('Kuratiert', false)
            ->assertSee('Admin-Notiz', false);

        $this->deleteJson('/api/governance/radar/items/'.$itemId.'/overlay')
            ->assertOk()
            ->assertJsonPath('overlay', null);
    }

    public function test_admin_can_post_radar_news(): void
    {
        app(UserRepository::class)->upsert([
            'id' => 'user_gov_news',
            'email' => 'gov-news@example.com',
            'displayName' => 'Governance News Admin',
            'passwordHash' => password_hash('password123', PASSWORD_DEFAULT),
            'canManageUsers' => true,
            'canManageTeams' => true,
            'active' => true,
            'teamIds' => [],
        ]);

        $this->post('/login', [
            'email' => 'gov-news@example.com',
            'password' => 'password123',
        ])->assertRedirect();

        $this->get('/governance/radar')
            ->assertOk()
            ->assertSee('data-radar-news-api-url', false)
            ->assertSee('data-governance-radar-news-open', false);

        $response = $this->postJson('/api/governance/radar/news', [
            'title_de' => 'UX Radar News DE',
            'title_en' => 'UX Radar News EN',
            'summary_de' => 'Zusammenfassung',
            'summary_en' => 'Summary',
            'url' => 'https://example.com/ux-radar-news',
            'language' => 'de',
        ])
            ->assertOk()
            ->assertJsonPath('ok', true);

        $itemId = (string) $response->json('item.id');
        $this->assertNotSame('', $itemId);

        $writer = new \App\Admin\Content\CatalogJsonWriter(base_path('content/catalogs/governance-radar'));
        $doc = $writer->read();
        $items = array_values(array_filter(
            array_values($doc['items'] ?? []),
            static fn (array $item): bool => ($item['id'] ?? '') !== $itemId
        ));
        $doc['items'] = $items;
        $writer->write($doc);
        \App\Catalog\CatalogJsonLoader::clearCache();
    }

    public function test_demo_report_shows_a_filled_example_session(): void
    {
        $this->get('/governance/demo-report')
            ->assertOk()
            ->assertSee('Sample report / proof path')
            ->assertSee('Demo: Finance Governance Discovery')
            ->assertSee('Anonymized sample artifact from a guided discovery', false)
            ->assertSee('bank-finance', false)
            ->assertSee('regulated', false)
            ->assertSee('Evidence &amp; certifications', false)
            ->assertSee('Gaps &amp; bridges', false)
            ->assertSee('Stack rationale', false)
            ->assertSee('CIPP/E', false)
            ->assertSee('DORA', false)
            ->assertSee('Net Revenue')
            ->assertSee('SAP S/4HANA')
            ->assertSee('Executive Finance Dashboard')
            ->assertSee('Decision brief')
            ->assertSee('Zurück zum Hub', false)
            ->assertSee('/governance', false)
            ->assertSee('Aktive Pläne weiterführen', false)
            ->assertSee('/sprint-planner?list=1', false)
            ->assertSee('Eigene Session starten', false)
            ->assertDontSee('data-session-id="demo_finance_governance"', false);
    }

    public function test_demo_workspace_shows_connected_plan_learning_kpis_and_generators(): void
    {
        $this->get('/governance/demo-workspace')
            ->assertOk()
            ->assertSee('Governance Demo Workspace')
            ->assertSee('Proof path / example')
            ->assertSee('Aktiver Hauptplan', false)
            ->assertSee('Finance Mart Governance Implementation')
            ->assertSee('Paralleler Lernplan', false)
            ->assertSee('dbt + Fabric Enablement &amp; Certification', false)
            ->assertSee('Microsoft DP-600')
            ->assertSee('KPI Cards mit echten Werten', false)
            ->assertSee('Net Revenue')
            ->assertSee('Offene Forderungen')
            ->assertSee('Invoice Count')
            ->assertSee('Gefüllte Tools öffnen', false)
            ->assertSee('KPI Requirements Intake')
            ->assertSee('/tools/kpi-requirements-intake?demo=finance', false)
            ->assertSee('/tools/source-scope-builder?demo=finance', false)
            ->assertSee('/governance/demo-report', false);
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
            ->assertSee('Saved discoveries', false)
            ->assertSee('Continue in Governance Hub', false)
            ->assertSee('tools-sidenav__link--active', false);

        $this->get('/governance')
            ->assertOk()
            ->assertSee('tools-header__account-menu-item', false)
            ->assertSee('Saved discoveries', false)
            ->assertDontSee('tools-sidenav__link--active" data-text-de="Gespeicherte Discoveries"', false);

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
