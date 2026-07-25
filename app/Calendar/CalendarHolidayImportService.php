<?php

namespace App\Calendar;

use App\Models\BnTools\BnCalendarHoliday;
use App\Models\BnTools\BnCalendarHolidaySource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Sabre\VObject\Reader;
use Throwable;

final class CalendarHolidayImportService
{
    public function __construct(
        private readonly CalendarUrlFetchGuard $urlGuard,
    ) {}

    public function syncSource(BnCalendarHolidaySource $source): int
    {
        if ($source->url === null || $source->url === '') {
            return 0;
        }

        try {
            $content = $this->urlGuard->fetch($source->url);
            $imported = $this->importFromContent($source, $content);
            $source->update([
                'last_synced_at' => now(),
                'settings' => array_merge($source->settings ?? [], ['last_error' => null]),
            ]);

            return $imported;
        } catch (Throwable $e) {
            Log::error('Calendar holiday import failed', [
                'source_id' => $source->id,
                'url' => $source->url,
                'error' => $e->getMessage(),
            ]);
            $source->update([
                'settings' => array_merge($source->settings ?? [], ['last_error' => $e->getMessage()]),
            ]);

            throw $e;
        }
    }

    public function importFromContent(BnCalendarHolidaySource $source, string $content): int
    {
        $vcalendar = Reader::read($content);
        $count = 0;
        $holidayType = $this->resolveHolidayType($source);

        foreach ($vcalendar->select('VEVENT') as $vevent) {
            $uid = (string) ($vevent->UID ?? '');
            $summary = (string) ($vevent->SUMMARY ?? 'Holiday');
            $dtStart = $vevent->DTSTART?->getDateTime();
            if ($dtStart === null) {
                continue;
            }

            $rangeStart = Carbon::instance($dtStart)->startOfDay();
            $rangeEnd = $rangeStart->copy();
            if ($vevent->DTEND !== null) {
                $dtEnd = Carbon::instance($vevent->DTEND->getDateTime());
                $rangeEnd = $dtEnd->copy()->subDay()->startOfDay();
                if ($rangeEnd->lt($rangeStart)) {
                    $rangeEnd = $rangeStart->copy();
                }
            }

            for ($day = $rangeStart->copy(); $day->lte($rangeEnd); $day->addDay()) {
                $date = $day->toDateString();
                $importedUid = $uid !== '' ? $uid.'-'.$date : null;

                BnCalendarHoliday::query()->updateOrCreate(
                    [
                        'imported_uid' => $importedUid,
                        'date' => $date,
                        'source_id' => $source->id,
                    ],
                    [
                        'name' => $summary,
                        'starts_at' => $day->copy(),
                        'ends_at' => $vevent->DTEND ? Carbon::instance($vevent->DTEND->getDateTime()) : null,
                        'country' => $source->country,
                        'region' => $source->region,
                        'type' => $holidayType,
                        'all_day' => true,
                    ],
                );

                $count++;
            }
        }

        return $count;
    }

    public function ensurePresetSources(): void
    {
        foreach (HolidaySourceDefaults::all() as $preset) {
            BnCalendarHolidaySource::query()->updateOrCreate(
                ['url' => $preset['url']],
                [
                    'name' => $preset['name'],
                    'type' => $preset['type'],
                    'country' => $preset['country'],
                    'region' => $preset['region'],
                    'is_active' => $preset['is_active'],
                    'sync_interval_hours' => $preset['sync_interval_hours'],
                    'settings' => $preset['settings'] ?? [],
                ],
            );
        }
    }

    private function resolveHolidayType(BnCalendarHolidaySource $source): string
    {
        $name = strtolower((string) ($source->name ?? ''));
        if (str_contains($name, 'schulferien') || str_contains($name, 'school')) {
            return 'school_holiday';
        }

        if (HolidaySourceDefaults::importCategoryForSource($source) === HolidaySourceDefaults::IMPORT_CATEGORY_CUSTOM) {
            return 'custom';
        }

        return 'public_holiday';
    }
}
