<?php

namespace App\Admin\Content;

use RuntimeException;
use Symfony\Component\Process\Process;

/**
 * Save uploaded raster next to playbook images and generate WebP (no git).
 */
final class PlaybookImageUploader
{
    public function __construct(
        private readonly string $imagesDirectory,
    ) {}

    /**
     * @return array{path: string, webp: ?string, url: string}
     */
    public function store(string $originalName, string $tmpPath): array
    {
        if (! is_file($tmpPath)) {
            throw new RuntimeException('Upload missing.');
        }
        if (! is_dir($this->imagesDirectory) && ! mkdir($this->imagesDirectory, 0775, true) && ! is_dir($this->imagesDirectory)) {
            throw new RuntimeException('Unable to create images directory.');
        }

        $base = pathinfo($originalName, PATHINFO_FILENAME);
        $base = strtolower((string) preg_replace('/[^a-zA-Z0-9_-]+/', '-', $base));
        $base = trim($base, '-') ?: 'image';
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION) ?: 'png');
        if (! in_array($ext, ['png', 'jpg', 'jpeg', 'gif', 'webp'], true)) {
            throw new RuntimeException('Unsupported image type.');
        }

        $filename = $base.'.'.$ext;
        $target = $this->imagesDirectory.DIRECTORY_SEPARATOR.$filename;
        $i = 1;
        while (is_file($target)) {
            $filename = $base.'-'.$i.'.'.$ext;
            $target = $this->imagesDirectory.DIRECTORY_SEPARATOR.$filename;
            $i++;
        }

        if (! move_uploaded_file($tmpPath, $target) && ! rename($tmpPath, $target)) {
            if (! copy($tmpPath, $target)) {
                throw new RuntimeException('Unable to store image.');
            }
            @unlink($tmpPath);
        }

        $webp = $this->makeWebp($target);

        return [
            'path' => $target,
            'webp' => $webp,
            'url' => '/images/playbooks/'.$filename,
        ];
    }

    private function makeWebp(string $sourcePath): ?string
    {
        $ext = strtolower(pathinfo($sourcePath, PATHINFO_EXTENSION));
        if ($ext === 'webp') {
            return $sourcePath;
        }
        $webpPath = preg_replace('/\.[^.]+$/', '.webp', $sourcePath) ?: ($sourcePath.'.webp');
        $node = base_path('node_modules/sharp');
        if (! is_dir($node)) {
            return null;
        }

        $script = <<<'JS'
const sharp = require('sharp');
const src = process.argv[1];
const dest = process.argv[2];
sharp(src).webp({ quality: 82, effort: 4 }).toFile(dest).then(() => process.exit(0)).catch((e) => { console.error(e); process.exit(1); });
JS;
        $process = new Process(['node', '-e', $script, $sourcePath, $webpPath], base_path());
        $process->setTimeout(60);
        $process->run();
        if (! $process->isSuccessful() || ! is_file($webpPath)) {
            return null;
        }

        return $webpPath;
    }
}
