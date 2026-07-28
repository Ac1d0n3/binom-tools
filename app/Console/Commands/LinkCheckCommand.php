<?php

namespace App\Console\Commands;

use App\Catalog\LinkCheckRunner;
use App\Catalog\LinkCheckStore;
use App\Catalog\LinkInventoryScanner;
use Illuminate\Console\Command;

class LinkCheckCommand extends Command
{
    protected $signature = 'bn-tools:link-check
        {--limit=0 : Max URLs to check (0 = all)}
        {--inventory : Only list inventoried URLs, do not HTTP-check}';

    protected $description = 'Scan catalog/markdown external links and optionally HTTP-check them';

    public function handle(
        LinkInventoryScanner $scanner,
        LinkCheckRunner $runner,
        LinkCheckStore $store,
    ): int {
        $inventory = $scanner->scan();
        $this->info('Inventoried '.count($inventory).' URL occurrences ('.count(array_unique(array_column($inventory, 'url'))).' unique).');

        if ($this->option('inventory')) {
            foreach (array_slice($inventory, 0, 30) as $hit) {
                $this->line($hit['url'].'  ← '.$hit['source']);
            }
            if (count($inventory) > 30) {
                $this->line('…');
            }

            return self::SUCCESS;
        }

        $limit = (int) $this->option('limit');
        $payload = $runner->run($inventory, function (int $done, int $total, array $row): void {
            if ($done === 1 || $done === $total || $done % 25 === 0) {
                $this->line("[{$done}/{$total}] ".($row['bucket'] ?? '?').' '.$row['url']);
            }
        }, $limit);
        $store->save($payload);

        $summary = $payload['summary'] ?? [];
        $this->info('Saved. ok='.($summary['ok'] ?? 0)
            .' redirect='.($summary['redirect'] ?? 0)
            .' broken='.($summary['broken'] ?? 0)
            .' error='.($summary['error'] ?? 0));

        return self::SUCCESS;
    }
}
