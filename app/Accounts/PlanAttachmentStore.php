<?php

namespace App\Accounts;

use App\Accounts\Contracts\PlanAttachmentStoreInterface;

/**
 * File-based attachment metadata (index.json) + binaries on disk.
 */
final class PlanAttachmentStore implements PlanAttachmentStoreInterface
{
    public function __construct(
        private readonly AccountsConfig $config,
        private readonly JsonFileStore $store,
    ) {}

    public function index(string $planId): array
    {
        $path = $this->indexPath($planId);
        if (! is_file($path)) {
            return [];
        }
        $data = $this->store->read($path, []);

        return is_array($data) ? $data : [];
    }

    public function put(string $planId, string $attachmentId, array $meta): void
    {
        $index = $this->index($planId);
        $index[$attachmentId] = $meta;
        $this->ensureDirectory($planId);
        $this->store->write($this->indexPath($planId), $index);
    }

    public function remove(string $planId, string $attachmentId): void
    {
        $index = $this->index($planId);
        $meta = $index[$attachmentId] ?? null;
        if ($meta !== null) {
            $path = $this->filePath($planId, (string) ($meta['storedName'] ?? ''));
            if (is_file($path)) {
                @unlink($path);
            }
            unset($index[$attachmentId]);
            $this->ensureDirectory($planId);
            $this->store->write($this->indexPath($planId), $index);
        }
    }

    public function filePath(string $planId, string $storedName): string
    {
        return $this->config->planAttachmentsDirectory($planId).DIRECTORY_SEPARATOR.$storedName;
    }

    public function ensureDirectory(string $planId): string
    {
        $dir = $this->config->planAttachmentsDirectory($planId);
        $this->store->ensureDirectory($dir);

        return $dir;
    }

    private function indexPath(string $planId): string
    {
        return $this->config->planAttachmentsDirectory($planId).DIRECTORY_SEPARATOR.'index.json';
    }
}
