<?php

namespace App\SprintPlanner;

use App\Accounts\AccountsConfig;
use App\Accounts\JsonFileStore;
use RuntimeException;

/**
 * Package local bn-tools accounts/plans/templates for FTP deploy (like Playbook stats-seed).
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
            $this->hydrateUsersJson(
                $seedRoot.DIRECTORY_SEPARATOR.'users.json',
                $this->config->usersPath(),
            );
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

            $this->hydrateDirectoryTreeIfMissing(
                $seedRoot.DIRECTORY_SEPARATOR.'plans',
                $this->config->plansDirectory(),
            );
            $this->hydrateDirectoryTreeIfMissing(
                $seedRoot.DIRECTORY_SEPARATOR.'read-state',
                $this->config->readStateDirectory(),
            );
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

        foreach (['user-templates', 'plans', 'read-state'] as $dirName) {
            $srcDir = $this->config->basePath().DIRECTORY_SEPARATOR.$dirName;
            $destDir = $seedRoot.DIRECTORY_SEPARATOR.$dirName;
            if (! is_dir($srcDir)) {
                continue;
            }
            $copied = $this->copyDirectoryTree($srcDir, $destDir);
            $files += $copied;
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

    /**
     * Copy users.json when missing, empty, or still the example.com placeholder set.
     * Never overwrite a runtime file that already has real (non-example) accounts.
     */
    private function hydrateUsersJson(string $source, string $destination): void
    {
        if (! is_file($source)) {
            return;
        }

        if (! is_file($destination) || $this->usersFileLooksEmptyOrExample($destination)) {
            $dir = dirname($destination);
            if (! is_dir($dir) && ! mkdir($dir, 0775, true) && ! is_dir($dir)) {
                return;
            }
            @copy($source, $destination);
        }
    }

    private function usersFileLooksEmptyOrExample(string $path): bool
    {
        $raw = @file_get_contents($path);
        if ($raw === false || trim($raw) === '') {
            return true;
        }

        $decoded = json_decode($raw, true);
        if (! is_array($decoded)) {
            return true;
        }

        $users = $decoded['users'] ?? null;
        if (! is_array($users) || $users === []) {
            return true;
        }

        foreach ($users as $user) {
            if (! is_array($user)) {
                continue;
            }
            $email = strtolower((string) ($user['email'] ?? ''));
            if ($email !== '' && ! str_ends_with($email, '@example.com')) {
                return false;
            }
        }

        // Only example.com addresses (or unreadable rows) → treat as placeholder.
        return true;
    }

    /**
     * Copy files/dirs from seed into runtime when the destination path is missing.
     */
    private function hydrateDirectoryTreeIfMissing(string $seedDir, string $runtimeDir): void
    {
        if (! is_dir($seedDir)) {
            return;
        }

        $this->store->ensureDirectory($runtimeDir);

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($seedDir, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST,
        );

        foreach ($iterator as $item) {
            /** @var \SplFileInfo $item */
            $relative = substr($item->getPathname(), strlen($seedDir) + 1);
            if ($relative === false || $relative === '') {
                continue;
            }
            $dest = $runtimeDir.DIRECTORY_SEPARATOR.$relative;
            if ($item->isDir()) {
                if (! is_dir($dest)) {
                    @mkdir($dest, 0775, true);
                }

                continue;
            }
            $this->copyIfMissing($item->getPathname(), $dest);
        }
    }

    private function copyDirectoryTree(string $sourceDir, string $destinationDir): int
    {
        if (! is_dir($destinationDir) && ! mkdir($destinationDir, 0775, true) && ! is_dir($destinationDir)) {
            throw new RuntimeException("Unable to create seed directory: {$destinationDir}");
        }

        $copied = 0;
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
                if (! is_dir($dest) && ! mkdir($dest, 0775, true) && ! is_dir($dest)) {
                    throw new RuntimeException("Unable to create seed directory: {$dest}");
                }

                continue;
            }
            $dir = dirname($dest);
            if (! is_dir($dir) && ! mkdir($dir, 0775, true) && ! is_dir($dir)) {
                throw new RuntimeException("Unable to create seed directory: {$dir}");
            }
            if (! copy($item->getPathname(), $dest)) {
                throw new RuntimeException('Unable to copy seed file: '.$relative);
            }
            $copied++;
        }

        return $copied;
    }
}
