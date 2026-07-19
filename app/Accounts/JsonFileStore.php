<?php

namespace App\Accounts;

use RuntimeException;

/**
 * Atomic JSON file read/write with exclusive lock.
 */
final class JsonFileStore
{
    /**
     * @return array<string, mixed>
     */
    public function read(string $path, array $default = []): array
    {
        if (! is_file($path)) {
            return $default;
        }

        $handle = fopen($path, 'rb');
        if ($handle === false) {
            throw new RuntimeException("Unable to open file for reading: {$path}");
        }

        try {
            flock($handle, LOCK_SH);
            $raw = stream_get_contents($handle);
            flock($handle, LOCK_UN);
        } finally {
            fclose($handle);
        }

        if ($raw === false || trim($raw) === '') {
            return $default;
        }

        $decoded = json_decode($raw, true);
        if (! is_array($decoded)) {
            throw new RuntimeException("Invalid JSON in file: {$path}");
        }

        return $decoded;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function write(string $path, array $data): void
    {
        $dir = dirname($path);
        if (! is_dir($dir) && ! mkdir($dir, 0775, true) && ! is_dir($dir)) {
            throw new RuntimeException("Unable to create directory: {$dir}");
        }

        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        if ($json === false) {
            throw new RuntimeException('Unable to encode JSON payload.');
        }

        $tmp = $path.'.tmp.'.bin2hex(random_bytes(4));
        if (file_put_contents($tmp, $json.PHP_EOL, LOCK_EX) === false) {
            throw new RuntimeException("Unable to write temp file: {$tmp}");
        }

        if (! rename($tmp, $path)) {
            @unlink($tmp);
            throw new RuntimeException("Unable to replace file: {$path}");
        }
    }

    public function ensureDirectory(string $path): void
    {
        if (! is_dir($path) && ! mkdir($path, 0775, true) && ! is_dir($path)) {
            throw new RuntimeException("Unable to create directory: {$path}");
        }
    }
}
