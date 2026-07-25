<?php

namespace App\Console\Commands;

use App\Calendar\CalendarHolidayImportService;
use App\Models\BnTools\BnCalendarHolidaySource;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class SyncCalendarHolidaysCommand extends Command
{
    protected $signature = 'calendar:holidays-sync
                            {--seed : Ensure NRW preset holiday sources exist first}
                            {--source= : Specific holiday source ID}';

    protected $description = 'Sync calendar holiday sources from configured iCal URLs';

    public function handle(CalendarHolidayImportService $import): int
    {
        if (! Schema::hasTable('bn_calendar_holiday_sources')) {
            $this->error('Holiday tables are missing. Run migrations first.');

            return self::FAILURE;
        }

        if ($this->option('seed')) {
            $import->ensurePresetSources();
            $this->info('NRW holiday source presets ensured.');
        }

        $query = BnCalendarHolidaySource::query()->where('is_active', true);

        if ($sourceId = $this->option('source')) {
            $query->whereKey($sourceId);
        }

        $sources = $query->get();
        $total = 0;

        foreach ($sources as $source) {
            if ($source->url === null || $source->url === '') {
                continue;
            }

            try {
                $count = $import->syncSource($source);
                $total += $count;
                $this->info("Synced source #{$source->id}: {$count} holidays");
            } catch (\Throwable $e) {
                $this->error("Source #{$source->id} failed: {$e->getMessage()}");
            }
        }

        $this->info("Total imported/updated: {$total}");

        return self::SUCCESS;
    }
}
