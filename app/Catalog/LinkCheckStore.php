<?php

namespace App\Catalog;

use App\Models\BnTools\BnLinkCheck;
use App\Support\StorageDriver;
use RuntimeException;

/**
 * Persist link-check runs (file always; also mysql when storage driver is mysql).
 */
final class LinkCheckStore
{
    public function path(): string
    {
        return storage_path('app/bn-tools/link-checks/latest.json');
    }

    /**
     * @return array<string, mixed>|null
     */
    public function latest(): ?array
    {
        if (StorageDriver::isMysql()) {
            try {
                $row = BnLinkCheck::query()->where('id', 'latest')->first();
                if ($row !== null && is_array($row->payload)) {
                    return $row->payload;
                }
            } catch (\Throwable) {
                // Table may not exist yet — fall through to file.
            }
        }

        $path = $this->path();
        if (! is_file($path)) {
            return null;
        }

        $raw = file_get_contents($path);
        if ($raw === false || $raw === '') {
            return null;
        }

        try {
            $decoded = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            return null;
        }

        return is_array($decoded) ? $decoded : null;
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function save(array $payload): void
    {
        $dir = dirname($this->path());
        if (! is_dir($dir) && ! mkdir($dir, 0775, true) && ! is_dir($dir)) {
            throw new RuntimeException('Cannot create link-checks directory');
        }

        $json = json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
        if (file_put_contents($this->path(), $json."\n") === false) {
            throw new RuntimeException('Cannot write link-check results');
        }

        if (StorageDriver::isMysql()) {
            try {
                BnLinkCheck::query()->updateOrCreate(
                    ['id' => 'latest'],
                    ['payload' => $payload]
                );
            } catch (\Throwable) {
                // File write already succeeded; mysql optional.
            }
        }
    }
}
