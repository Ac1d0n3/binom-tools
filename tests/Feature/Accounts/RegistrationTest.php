<?php

namespace Tests\Feature\Accounts;

use App\Accounts\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

final class RegistrationTest extends TestCase
{
    private string $accountsPath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->accountsPath = storage_path('app/bn-tools-reg-test-'.uniqid('', true));
        File::ensureDirectoryExists($this->accountsPath);
        config([
            'accounts.enabled' => true,
            'accounts.registration_enabled' => true,
            'accounts.path' => $this->accountsPath,
            'storage.driver' => 'file',
        ]);
    }

    protected function tearDown(): void
    {
        File::deleteDirectory($this->accountsPath);
        parent::tearDown();
    }

    public function test_registration_creates_pending_user_and_blocks_login_until_approved(): void
    {
        $this->get('/register')->assertOk();

        $this->post('/register', [
            'email' => 'newbie@example.com',
            'displayName' => 'Newbie',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect('/login');

        $user = app(UserRepositoryInterface::class)->findByEmail('newbie@example.com');
        $this->assertNotNull($user);
        $this->assertFalse($user->active);
        $this->assertTrue($user->pendingApproval);

        $this->post('/login', [
            'email' => 'newbie@example.com',
            'password' => 'password123',
        ])->assertSessionHasErrors('email');

        $adminHash = password_hash('adminpass1', PASSWORD_DEFAULT);
        app(UserRepositoryInterface::class)->upsert([
            'id' => 'user_admin',
            'email' => 'admin@example.com',
            'displayName' => 'Admin',
            'passwordHash' => $adminHash,
            'canManageUsers' => true,
            'canManageTeams' => true,
            'active' => true,
            'pendingApproval' => false,
        ]);

        $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'adminpass1',
        ])->assertRedirect();

        $this->post('/account/users/'.$user->id.'/approve')->assertRedirect('/admin/users');

        $approved = app(UserRepositoryInterface::class)->findById($user->id);
        $this->assertNotNull($approved);
        $this->assertTrue($approved->active);
        $this->assertFalse($approved->pendingApproval);

        $this->post('/logout');

        $this->post('/login', [
            'email' => 'newbie@example.com',
            'password' => 'password123',
        ])->assertRedirect();
    }

    public function test_register_404_when_registration_disabled(): void
    {
        config(['accounts.registration_enabled' => false]);
        $this->get('/register')->assertNotFound();
    }
}
