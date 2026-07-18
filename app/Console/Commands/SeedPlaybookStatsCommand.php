<?php

namespace App\Console\Commands;

use App\Playbooks\PlaybookRepository;
use App\Playbooks\PlaybookStatsStore;
use Illuminate\Console\Command;

class SeedPlaybookStatsCommand extends Command
{
    protected $signature = 'playbooks:seed-stats
                            {--force : Overwrite existing non-zero stats}';

    protected $description = 'Seed plausible random view/like counts for all playbooks';

    public function handle(PlaybookRepository $playbooks, PlaybookStatsStore $stats): int
    {
        $force = (bool) $this->option('force');
        $seeded = 0;
        $skipped = 0;
        $seedDir = app_path('Playbooks/stats-seed');

        if (! is_dir($seedDir) && ! mkdir($seedDir, 0775, true) && ! is_dir($seedDir)) {
            $this->error('Unable to create seed directory: '.$seedDir);

            return self::FAILURE;
        }

        foreach ($playbooks->all() as $playbook) {
            $slug = $playbook->slug;
            $current = $stats->get($slug);

            if (! $force && ($current['views'] > 0 || $current['likes'] > 0)) {
                $skipped++;
                continue;
            }

            [$views, $likes] = $this->randomCountsForSlug($slug);
            $stats->set($slug, $views, $likes);

            $seedPath = $seedDir.DIRECTORY_SEPARATOR.$slug.'.json';
            file_put_contents(
                $seedPath,
                json_encode(['views' => $views, 'likes' => $likes], JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT)."\n",
            );

            $this->line("{$slug}: {$views} views, {$likes} likes");
            $seeded++;
        }

        $this->info("Seeded {$seeded} playbook(s)".($skipped > 0 ? ", skipped {$skipped} existing" : '').'.');

        return self::SUCCESS;
    }

    /**
     * Deterministic-but-varied counts from the slug (stable across re-runs with --force).
     *
     * @return array{0: int, 1: int}
     */
    private function randomCountsForSlug(string $slug): array
    {
        $hash = crc32($slug);
        // Keep unsigned on 32-bit-safe math
        $n = $hash < 0 ? $hash + 4294967296 : $hash;

        $views = 48 + (int) ($n % 412); // 48–459
        $likes = 3 + (int) (($n >> 9) % 47); // 3–49
        $likes = min($likes, max(1, (int) floor($views / 8)));

        return [$views, $likes];
    }
}
