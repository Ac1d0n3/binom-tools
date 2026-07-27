<?php

namespace App\Admin\Content;

use RuntimeException;

/**
 * Read/write catalog JSON documents under content/catalogs (file-first, no git).
 */
final class CatalogJsonWriter
{
    public function __construct(
        private readonly string $catalogDirectory,
        private readonly string $documentFile = 'document.json',
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function read(): array
    {
        $path = $this->path();
        if (! is_file($path)) {
            return [];
        }
        $raw = file_get_contents($path);
        if ($raw === false) {
            throw new RuntimeException('Unable to read catalog.');
        }
        $decoded = json_decode($raw, true);
        if (! is_array($decoded)) {
            throw new RuntimeException('Invalid catalog JSON.');
        }

        return $decoded;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function write(array $data): void
    {
        if (! is_dir($this->catalogDirectory) && ! mkdir($this->catalogDirectory, 0775, true) && ! is_dir($this->catalogDirectory)) {
            throw new RuntimeException('Unable to create catalog directory.');
        }
        $path = $this->path();
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        if ($json === false) {
            throw new RuntimeException('Unable to encode catalog JSON.');
        }
        $tmp = $path.'.tmp';
        if (file_put_contents($tmp, $json."\n") === false) {
            throw new RuntimeException('Unable to write catalog.');
        }
        if (! rename($tmp, $path)) {
            @unlink($tmp);
            throw new RuntimeException('Unable to finalize catalog.');
        }
    }

    public function path(): string
    {
        return $this->catalogDirectory.DIRECTORY_SEPARATOR.$this->documentFile;
    }
}
