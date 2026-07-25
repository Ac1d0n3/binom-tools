<?php

namespace App\Support;

use InvalidArgumentException;
use RuntimeException;

/**
 * Resolves BINOM_TOOLS_STORAGE_DRIVER (file | mysql).
 * Default is file — production can run without a database.
 */
final class StorageDriver
{
    public const FILE = 'file';

    public const MYSQL = 'mysql';

    public static function current(): string
    {
        $driver = strtolower((string) config('storage.driver', self::FILE));

        if (! in_array($driver, [self::FILE, self::MYSQL], true)) {
            throw new InvalidArgumentException(
                "Invalid BINOM_TOOLS_STORAGE_DRIVER [{$driver}]. Allowed: file, mysql."
            );
        }

        return $driver;
    }

    public static function isFile(): bool
    {
        return self::current() === self::FILE;
    }

    public static function isMysql(): bool
    {
        return self::current() === self::MYSQL;
    }

    /**
     * Fail fast when mysql is selected but the connection is unusable.
     */
    public static function assertMysqlReady(): void
    {
        if (! self::isMysql()) {
            return;
        }

        try {
            \Illuminate\Support\Facades\DB::connection()->getPdo();
        } catch (\Throwable $e) {
            throw new RuntimeException(
                'BINOM_TOOLS_STORAGE_DRIVER=mysql requires a working database connection. '
                .'Configure DB_* and run migrations. '.$e->getMessage(),
                0,
                $e
            );
        }
    }
}
