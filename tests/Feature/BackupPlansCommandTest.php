<?php

namespace Tests\Feature;

use App\Accounts\AccountsConfig;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class BackupPlansCommandTest extends TestCase
{
    private string $basePath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->basePath = sys_get_temp_dir().'/bn-tools-backup-'.bin2hex(random_bytes(4));
        mkdir($this->basePath.'/plans', 0775, true);
        Config::set('accounts.path', $this->basePath);
    }

    protected function tearDown(): void
    {
        File::deleteDirectory($this->basePath);
        parent::tearDown();
    }

    public function test_backup_command_copies_plan_json_and_prunes_old_days(): void
    {
        $today = now()->format('Y-m-d');
        $old = now()->subDays(20)->format('Y-m-d');
        $config = new AccountsConfig;

        file_put_contents(
            $config->plansDirectory().'/plan_20260719_demo.json',
            json_encode(['id' => 'plan_20260719_demo', 'templateSlug' => 'demo'], JSON_THROW_ON_ERROR),
        );
        file_put_contents(
            $config->plansDirectory().'/notes.txt',
            'ignore me',
        );

        $oldDir = $config->planBackupsDirectory($old);
        File::makeDirectory($oldDir, 0775, true);
        file_put_contents($oldDir.'/plan_old.json', '{}');

        $exit = Artisan::call('bn-tools:backup-plans', ['--retention' => 14]);
        $this->assertSame(0, $exit);

        $backup = $config->planBackupsDirectory($today).'/plan_20260719_demo.json';
        $this->assertFileExists($backup);
        $this->assertFileDoesNotExist($config->planBackupsDirectory($today).'/notes.txt');
        $this->assertDirectoryDoesNotExist($oldDir);
    }
}
