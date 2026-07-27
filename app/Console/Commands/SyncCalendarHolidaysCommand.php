<?php

namespace App\Console\Commands;

use App\Calendar\CalendarHolidayImportService;
use App\Calendar\Contracts\CalendarHolidayStoreInterface;
use Illuminate\Console\Command;

class SyncCalendarHolidaysCommand extends Command
{
    protected $signature = 'calendar:holidays-sync
                            {--seed : Ensure NRW preset holiday sources exist first}
                            {--source= : Specific holiday source ID}';

    protected $description = 'Sync calendar holiday sources from configured iCal URLs';

    public function handle(
        CalendarHolidayImportService $import,
        CalendarHolidayStoreInterface $store,
    ): int {
        if (! $store->isReady()) {
            $this->error('Holiday storage is not ready. For mysql, run migrations first.');

            return self::FAILURE;
        }

        if ($this->option('seed')) {
            $import->ensurePresetSources();
            $this->info('NRW holiday source presets ensured.');
        }

        $sources = $store->listActiveSources();
        if ($sourceId = $this->option('source')) {
            $sources = array_values(array_filter(
                $sources,
                static fn (array $source): bool => (string) ($source['id'] ?? '') === (string) $sourceId,
            ));
        }

        $total = 0;

        foreach ($sources as $source) {
            $url = is_string($source['url'] ?? null) ? (string) $source['url'] : '';
            if ($url === '') {
                continue;
            }

            $id = (string) ($source['id'] ?? '');

            try {
                $count = $import->syncSource($source);
                $total += $count;
                $this->info("Synced source {$id}: {$count} holidays");
            } catch (\Throwable $e) {
                $this->error("Source {$id} failed: {$e->getMessage()}");
            }
        }

        $this->info("Total imported/updated: {$total}");

        return self::SUCCESS;
    }
}
