<?php

namespace App\Accounts\Contracts;

/**
 * Plan attachment metadata index. Binary files always live on disk.
 */
interface PlanAttachmentStoreInterface
{
    /**
     * @return array<string, array<string, mixed>>
     */
    public function index(string $planId): array;

    /**
     * @param  array<string, mixed>  $meta
     */
    public function put(string $planId, string $attachmentId, array $meta): void;

    public function remove(string $planId, string $attachmentId): void;

    /**
     * Absolute path to the stored binary (may not exist yet).
     */
    public function filePath(string $planId, string $storedName): string;

    public function ensureDirectory(string $planId): string;
}
