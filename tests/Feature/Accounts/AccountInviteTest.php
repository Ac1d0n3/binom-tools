<?php

namespace Tests\Feature\Accounts;

use App\Accounts\AccountAuth;
use App\Accounts\UserRepository;
use App\Mail\AccountWelcomeMail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AccountInviteTest extends TestCase
{
    private string $basePath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->basePath = sys_get_temp_dir().'/bn-tools-invite-'.bin2hex(random_bytes(4));
        mkdir($this->basePath, 0775, true);
        Config::set('accounts.enabled', true);
        Config::set('accounts.path', $this->basePath);

        app(UserRepository::class)->upsert([
            'id' => 'user_admin',
            'email' => 'admin@example.com',
            'displayName' => 'Admin',
            'passwordHash' => password_hash('password123', PASSWORD_DEFAULT),
            'canManageUsers' => true,
            'canManageTeams' => true,
            'active' => true,
        ]);
    }

    protected function tearDown(): void
    {
        $this->removeDir($this->basePath);
        parent::tearDown();
    }

    public function test_admin_can_create_user_with_invite_mail_and_forced_password_change(): void
    {
        Mail::fake();
        $this->loginAdmin();

        $this->post('/account/users', [
            'email' => 'new@example.com',
            'displayName' => 'New Person',
            'generatePassword' => '1',
            'sendInvite' => '1',
            'mustChangePassword' => '1',
            'active' => '1',
        ])->assertRedirect('/account/users');

        $user = app(UserRepository::class)->findByEmail('new@example.com');
        $this->assertNotNull($user);
        $this->assertTrue($user->mustChangePassword);

        Mail::assertSent(AccountWelcomeMail::class, function (AccountWelcomeMail $mail) {
            return $mail->emailAddress === 'new@example.com'
                && $mail->mustChangePassword === true
                && strlen($mail->temporaryPassword) >= 8;
        });

        $plain = null;
        Mail::assertSent(AccountWelcomeMail::class, function (AccountWelcomeMail $mail) use (&$plain) {
            $plain = $mail->temporaryPassword;

            return true;
        });

        $this->post('/logout');
        $this->post('/login', [
            'email' => 'new@example.com',
            'password' => $plain,
        ])->assertRedirect('/account');

        $this->get('/tools/prompt-studio')->assertRedirect('/account');

        $this->put('/account', [
            'displayName' => 'New Person',
            'current_password' => $plain,
            'password' => 'new-secret-99',
            'password_confirmation' => 'new-secret-99',
        ])->assertRedirect();

        $updated = app(UserRepository::class)->findByEmail('new@example.com');
        $this->assertNotNull($updated);
        $this->assertFalse($updated->mustChangePassword);
        $this->assertTrue(password_verify('new-secret-99', $updated->passwordHash));

        $this->get('/tools/prompt-studio')->assertOk();
    }

    public function test_create_without_generate_still_accepts_manual_password(): void
    {
        Mail::fake();
        $this->loginAdmin();

        $this->post('/account/users', [
            'email' => 'manual@example.com',
            'displayName' => 'Manual',
            'generatePassword' => '0',
            'sendInvite' => '0',
            'mustChangePassword' => '0',
            'password' => 'manual-pass-12',
            'active' => '1',
        ])->assertRedirect('/account/users');

        Mail::assertNothingSent();
        $user = app(UserRepository::class)->findByEmail('manual@example.com');
        $this->assertNotNull($user);
        $this->assertFalse($user->mustChangePassword);
        $this->assertTrue(password_verify('manual-pass-12', $user->passwordHash));
    }

    private function loginAdmin(): void
    {
        $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);
        $this->assertTrue(app(AccountAuth::class)->check());
    }

    private function removeDir(string $path): void
    {
        if (! is_dir($path)) {
            return;
        }
        foreach (scandir($path) ?: [] as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $full = $path.DIRECTORY_SEPARATOR.$item;
            is_dir($full) ? $this->removeDir($full) : @unlink($full);
        }
        @rmdir($path);
    }
}
