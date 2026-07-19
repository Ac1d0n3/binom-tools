<?php

namespace App\Console\Commands;

use App\Accounts\AccountsConfig;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class BackupPlansCommand extends Command
{
    protected $signature = 'bn-tools:backup-plans
                            {--retention=14 : Days of daily backups to keep}';

    protected $description = 'Copy active plan JSON files into a dated daily backup folder';

    public function handle(AccountsConfig $config): int
    {
        $retention = max(1, (int) $this->option('retention'));
        $plansDir = $config->plansDirectory();
        $date = now()->format('Y-m-d');
        $backupDir = $config->planBackupsDirectory($date);

        if (! is_dir($plansDir)) {
            $this->warn("Plans directory missing: {$plansDir}");
            $this->pruneOldBackups($config->planBackupsRoot(), $retention);

            return self::SUCCESS;
        }

        if (! File::isDirectory($backupDir) && ! File::makeDirectory($backupDir, 0775, true) && ! is_dir($backupDir)) {
            $this->error("Unable to create backup directory: {$backupDir}");

            return self::FAILURE;
        }

        $files = glob($plansDir.DIRECTORY_SEPARATOR.'*.json') ?: [];
        $copied = 0;
        foreach ($files as $file) {
            $name = basename($file);
            if (! preg_match('/^plan_[a-zA-Z0-9_]+\.json$/', $name)) {
                continue;
            }
            $target = $backupDir.DIRECTORY_SEPARATOR.$name;
            if (! @copy($file, $target)) {
                $this->error("Failed to copy {$name}");

                return self::FAILURE;
            }
            $copied++;
        }

        $pruned = $this->pruneOldBackups($config->planBackupsRoot(), $retention);
        $this->info("Backed up {$copied} plan(s) to {$backupDir}".($pruned > 0 ? ", pruned {$pruned} old day(s)" : '').'.');

        return self::SUCCESS;
    }

    private function pruneOldBackups(string $root, int $retentionDays): int
    {
        if (! is_dir($root)) {
            return 0;
        }

        $cutoff = now()->subDays($retentionDays)->startOfDay();
        $dirs = glob($root.DIRECTORY_SEPARATOR.'*', GLOB_ONLYDIR) ?: [];
        $pruned = 0;
        foreach ($dirs as $dir) {
            $name = basename($dir);
            if (! preg_match('/^\d{4}-\d{2}-\d{2}$/', $name)) {
                continue;
            }
            try {
                $day = \Carbon\Carbon::createFromFormat('Y-m-d', $name)->startOfDay();
            } catch (\Throwable) {
                continue;
            }
            if ($day->lt($cutoff)) {
                File::deleteDirectory($dir);
                $pruned++;
            }
        }

        return $pruned;
    }
}
