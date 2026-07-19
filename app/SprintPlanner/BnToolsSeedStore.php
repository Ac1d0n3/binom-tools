<?php

namespace App\SprintPlanner;

use App\Accounts\AccountsConfig;
use App\Accounts\JsonFileStore;
use RuntimeException;

/**
 * Package local bn-tools accounts/templates for FTP deploy (like Playbook stats-seed).
 *
 * Runtime: storage/app/bn-tools/...
 * Packaged seed: app/SprintPlanner/bn-tools-seed/...
 */
final class BnToolsSeedStore
{
    public function __construct(
        private readonly AccountsConfig $config,
        private readonly JsonFileStore $store,
        private readonly ?string $seedDirectory = null,
    ) {}

    public static function default(): self
    {
        return new self(
            app(AccountsConfig::class),
            app(JsonFileStore::class),
            app_path('SprintPlanner/bn-tools-seed'),
        );
    }

    public function seedDirectory(): string
    {
        return $this->seedDirectory ?: app_path('SprintPlanner/bn-tools-seed');
    }

    /**
     * Copy seed files into runtime storage when targets are missing.
     */
    public function hydrateRuntimeFromSeeds(): void
    {
        $seedRoot = $this->seedDirectory();
        if (! is_dir($seedRoot)) {
            return;
        }

        try {
            $this->store->ensureDirectory($this->config->basePath());
            $this->copyIfMissing($seedRoot.DIRECTORY_SEPARATOR.'users.json', $this->config->usersPath());
            $this->copyIfMissing($seedRoot.DIRECTORY_SEPARATOR.'teams.json', $this->config->teamsPath());
            $this->copyIfMissing($seedRoot.DIRECTORY_SEPARATOR.'story-acl.json', $this->config->storyAclPath());

            $seedTemplates = $seedRoot.DIRECTORY_SEPARATOR.'user-templates';
            if (is_dir($seedTemplates)) {
                $this->store->ensureDirectory($this->config->userTemplatesDirectory());
                foreach (glob($seedTemplates.DIRECTORY_SEPARATOR.'*.json') ?: [] as $file) {
                    $dest = $this->config->userTemplatesDirectory().DIRECTORY_SEPARATOR.basename($file);
                    $this->copyIfMissing($file, $dest);
                }
            }
        } catch (\Throwable) {
            // Storage may be missing/unwritable after FTP deploy — ignore.
        }
    }

    /**
     * Snapshot current runtime files into the seed directory (for deploy:ftp).
     *
     * @return array{files: int, skipped: int}
     */
    public function snapshotRuntimeToSeeds(): array
    {
        $seedRoot = $this->seedDirectory();
        if (! is_dir($seedRoot) && ! mkdir($seedRoot, 0775, true) && ! is_dir($seedRoot)) {
            throw new RuntimeException("Unable to create seed directory: {$seedRoot}");
        }

        $files = 0;
        $skipped = 0;

        foreach (['users.json', 'teams.json', 'story-acl.json'] as $name) {
            $src = $this->config->basePath().DIRECTORY_SEPARATOR.$name;
            $dest = $seedRoot.DIRECTORY_SEPARATOR.$name;
            if (! is_file($src)) {
                $skipped++;

                continue;
            }
            if (! copy($src, $dest)) {
                throw new RuntimeException("Unable to copy seed file: {$name}");
            }
            $files++;
        }

        $srcTemplates = $this->config->userTemplatesDirectory();
        $destTemplates = $seedRoot.DIRECTORY_SEPARATOR.'user-templates';
        if (is_dir($srcTemplates)) {
            if (! is_dir($destTemplates) && ! mkdir($destTemplates, 0775, true) && ! is_dir($destTemplates)) {
                throw new RuntimeException("Unable to create seed templates directory: {$destTemplates}");
            }
            foreach (glob($srcTemplates.DIRECTORY_SEPARATOR.'*.json') ?: [] as $file) {
                $dest = $destTemplates.DIRECTORY_SEPARATOR.basename($file);
                if (! copy($file, $dest)) {
                    throw new RuntimeException('Unable to copy user template seed: '.basename($file));
                }
                $files++;
            }
        }

        return ['files' => $files, 'skipped' => $skipped];
    }

    private function copyIfMissing(string $source, string $destination): void
    {
        if (! is_file($source) || is_file($destination)) {
            return;
        }

        $dir = dirname($destination);
        if (! is_dir($dir) && ! mkdir($dir, 0775, true) && ! is_dir($dir)) {
            return;
        }

        @copy($source, $destination);
    }
}
