<?php

namespace App\Console\Commands;

use App\Governance\GovernanceRadarFeedSync;
use Illuminate\Console\Command;

class SyncGovernanceRadarFeedsCommand extends Command
{
    protected $signature = 'bn-tools:sync-governance-radar-feeds
                            {--source= : Sync only this source id}';

    protected $description = 'Sync governance radar RSS/Atom feeds into cached feed items';

    public function handle(GovernanceRadarFeedSync $sync): int
    {
        $only = $this->option('source');
        $onlyIds = is_string($only) && $only !== '' ? [$only] : null;

        $result = $sync->sync(null, $onlyIds);
        $this->info("Synced: {$result['synced']}, failed: {$result['failed']}, skipped: {$result['skipped']}");
        foreach ($result['errors'] as $error) {
            $this->error($error);
        }

        return $result['failed'] > 0 && $result['synced'] === 0 ? self::FAILURE : self::SUCCESS;
    }
}
