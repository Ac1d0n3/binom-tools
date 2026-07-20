<?php

namespace App\Console\Commands;

use App\Accounts\AccountsConfig;
use App\SprintPlanner\BnToolsSeedStore;
use Illuminate\Console\Command;

class HydrateBnToolsAccountsCommand extends Command
{
    protected $signature = 'bn-tools:hydrate-accounts
                            {--force : Overwrite runtime users/teams/acl/plans from seed even if present}';

    protected $description = 'Copy bn-tools-seed accounts/plans into storage/app/bn-tools (missing by default; --force overwrites)';

    public function handle(AccountsConfig $config, BnToolsSeedStore $seeds): int
    {
        $seedRoot = $seeds->seedDirectory();
        if (! is_dir($seedRoot)) {
            $this->error("Seed directory missing: {$seedRoot}");

            return self::FAILURE;
        }

        $force = (bool) $this->option('force');
        $runtime = $config->basePath();

        if (! is_dir($runtime) && ! mkdir($runtime, 0775, true) && ! is_dir($runtime)) {
            $this->error("Unable to create runtime path: {$runtime}");

            return self::FAILURE;
        }

        if ($force) {
            $copied = 0;
            foreach (['users.json', 'teams.json', 'story-acl.json'] as $name) {
                $src = $seedRoot.DIRECTORY_SEPARATOR.$name;
                $dest = $runtime.DIRECTORY_SEPARATOR.$name;
                if (! is_file($src)) {
                    continue;
                }
                if (! copy($src, $dest)) {
                    $this->error("Failed to copy {$name}");

                    return self::FAILURE;
                }
                $copied++;
                $this->line("Forced: {$name}");
            }

            foreach (['user-templates', 'plans', 'read-state'] as $dirName) {
                $srcDir = $seedRoot.DIRECTORY_SEPARATOR.$dirName;
                $destDir = $runtime.DIRECTORY_SEPARATOR.$dirName;
                if (! is_dir($srcDir)) {
                    continue;
                }
                $this->recurseCopy($srcDir, $destDir, true);
                $copied++;
                $this->line("Forced tree: {$dirName}/");
            }

            $this->info("Force-hydrated {$copied} seed item(s) into {$runtime}");

            return self::SUCCESS;
        }

        $seeds->hydrateRuntimeFromSeeds();
        $this->info("Hydrated missing files from {$seedRoot} into {$runtime}");
        $this->comment('Tip: use --force to overwrite existing users.json / plans from seed.');

        return self::SUCCESS;
    }

    private function recurseCopy(string $sourceDir, string $destinationDir, bool $overwrite): void
    {
        if (! is_dir($destinationDir) && ! mkdir($destinationDir, 0775, true) && ! is_dir($destinationDir)) {
            throw new \RuntimeException("Unable to create {$destinationDir}");
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($sourceDir, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST,
        );

        foreach ($iterator as $item) {
            /** @var \SplFileInfo $item */
            $relative = substr($item->getPathname(), strlen($sourceDir) + 1);
            if ($relative === false || $relative === '') {
                continue;
            }
            $dest = $destinationDir.DIRECTORY_SEPARATOR.$relative;
            if ($item->isDir()) {
                if (! is_dir($dest)) {
                    mkdir($dest, 0775, true);
                }

                continue;
            }
            if (! $overwrite && is_file($dest)) {
                continue;
            }
            $dir = dirname($dest);
            if (! is_dir($dir)) {
                mkdir($dir, 0775, true);
            }
            copy($item->getPathname(), $dest);
        }
    }
}
