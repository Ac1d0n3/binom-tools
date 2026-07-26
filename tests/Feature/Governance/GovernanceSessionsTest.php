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

    public function test_signed_in_user_can_save_manage_report_and_create_workflow(): void
    {
        $this->login();

        $saved = $this->postJson('/api/governance/sessions', [
            'session' => $this->payload(),
        ])
            ->assertOk()
            ->assertJsonPath('session.ownerUserId', 'user_gov_owner')
            ->assertJsonPath('session.validationSummary.state', 'decision_ready')
            ->json('session');

        $sessionId = $saved['id'];
        $this->assertMatchesRegularExpression('/^gov_/', $sessionId);

        $this->get('/governance/sessions')
            ->assertOk()
            ->assertSee('ERP Data Mart Discovery')
            ->assertSee('Governance Sessions verwalten', false);

        $this->get('/governance/sessions/'.$sessionId.'/report')
            ->assertOk()
            ->assertSee('Printable report')
            ->assertSee('Governance Stack Advisor')
            ->assertSee('In Workflow uebernehmen', false);

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
