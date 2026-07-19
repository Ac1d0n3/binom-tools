<?php

namespace Tests\Unit\Accounts;

use App\Accounts\AccountsConfig;
use App\Accounts\JsonFileStore;
use App\Accounts\UserRepository;
use Illuminate\Support\Facades\Config;
use InvalidArgumentException;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    private string $basePath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->basePath = sys_get_temp_dir().'/bn-tools-accounts-'.bin2hex(random_bytes(4));
        mkdir($this->basePath, 0775, true);
        Config::set('accounts.enabled', true);
        Config::set('accounts.path', $this->basePath);
    }

    protected function tearDown(): void
    {
        $this->removeDir($this->basePath);
        parent::tearDown();
    }

    public function test_upsert_persists_hash_only_and_rejects_plaintext_fields(): void
    {
        $repo = $this->repository();
        $hash = password_hash('secret-pass', PASSWORD_DEFAULT);

        $user = $repo->upsert([
            'email' => 'Ada@Example.com',
            'displayName' => 'Ada',
            'passwordHash' => $hash,
            'canManageUsers' => true,
        ]);

        $this->assertSame('ada@example.com', $user->email);
        $this->assertTrue(password_verify('secret-pass', $user->passwordHash));
        $this->assertStringNotContainsString('secret-pass', (string) file_get_contents($this->basePath.'/users.json'));

        $this->expectException(InvalidArgumentException::class);
        $repo->upsert([
            'email' => 'bob@example.com',
            'passwordHash' => $hash,
            'password' => 'plaintext',
        ]);
    }

    public function test_email_must_be_unique(): void
    {
        $repo = $this->repository();
        $hash = password_hash('secret-pass', PASSWORD_DEFAULT);
        $repo->upsert([
            'id' => 'user_a',
            'email' => 'same@example.com',
            'passwordHash' => $hash,
        ]);

        $this->expectException(InvalidArgumentException::class);
        $repo->upsert([
            'id' => 'user_b',
            'email' => 'SAME@example.com',
            'passwordHash' => $hash,
        ]);
    }

    private function repository(): UserRepository
    {
        return new UserRepository(new AccountsConfig, new JsonFileStore);
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
