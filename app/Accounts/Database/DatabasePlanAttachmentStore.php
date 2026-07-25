<?php

namespace App\Accounts\Database;

use App\Accounts\AccountsConfig;
use App\Accounts\Contracts\PlanAttachmentStoreInterface;
use App\Accounts\JsonFileStore;
use Illuminate\Support\Facades\DB;

/**
 * Attachment binaries stay on disk; metadata lives in bn_plan_attachments.
 */
final class DatabasePlanAttachmentStore implements PlanAttachmentStoreInterface
{
    public function __construct(
        private readonly AccountsConfig $config,
        private readonly JsonFileStore $store,
    ) {}

    public function index(string $planId): array
    {
        $out = [];
        $rows = DB::table('bn_plan_attachments')->where('plan_id', $planId)->get();
        foreach ($rows as $row) {
            $meta = json_decode((string) $row->meta, true);
            if (is_array($meta) && $meta !== []) {
                $out[(string) $row->id] = $meta;
            }
        }

        return $out;
    }

    public function put(string $planId, string $attachmentId, array $meta): void
    {
        $now = now();
        $exists = DB::table('bn_plan_attachments')
            ->where('plan_id', $planId)
            ->where('id', $attachmentId)
            ->exists();
        $payload = [
            'meta' => json_encode($meta, JSON_THROW_ON_ERROR),
            'updated_at' => $now,
        ];
        if ($exists) {
            DB::table('bn_plan_attachments')
                ->where('plan_id', $planId)
                ->where('id', $attachmentId)
                ->update($payload);
        } else {
            DB::table('bn_plan_attachments')->insert([
                'id' => $attachmentId,
                'plan_id' => $planId,
                ...$payload,
                'created_at' => $now,
            ]);
        }
    }

    public function remove(string $planId, string $attachmentId): void
    {
        $row = DB::table('bn_plan_attachments')
            ->where('plan_id', $planId)
            ->where('id', $attachmentId)
            ->first();
        if ($row === null) {
            return;
        }
        $meta = json_decode((string) $row->meta, true);
        $storedName = is_array($meta) ? (string) ($meta['storedName'] ?? '') : '';
        $path = $this->filePath($planId, $storedName);
        if (is_file($path)) {
            @unlink($path);
        }
        DB::table('bn_plan_attachments')
            ->where('plan_id', $planId)
            ->where('id', $attachmentId)
            ->delete();
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
}
