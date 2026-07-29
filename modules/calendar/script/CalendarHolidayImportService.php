<?php

namespace App\Calendar;

use App\Calendar\Contracts\CalendarHolidayStoreInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Sabre\VObject\Reader;
use Throwable;

final class CalendarHolidayImportService
{
    public function __construct(
        private readonly CalendarUrlFetchGuard $urlGuard,
        private readonly CalendarHolidayStoreInterface $store,
    ) {}

    /**
     * @param  array<string, mixed>  $source
     */
    public function syncSource(array $source): int
    {
        $url = is_string($source['url'] ?? null) ? (string) $source['url'] : '';
        if ($url === '') {
            return 0;
        }

        try {
            $content = $this->urlGuard->fetch($url);
            $imported = $this->importFromContent($source, $content);
            $this->store->markSyncSuccess($source);

            return $imported;
        } catch (Throwable $e) {
            Log::error('Calendar holiday import failed', [
                'source_id' => $source['id'] ?? null,
                'url' => $url,
                'error' => $e->getMessage(),
            ]);
            $this->store->markSyncFailure($source, $e->getMessage());

            throw $e;
        }
    }

    /**
     * @param  array<string, mixed>  $source
     */
    public function importFromContent(array $source, string $content): int
    {
        $vcalendar = Reader::read($content);
        $count = 0;
        $holidayType = $this->resolveHolidayType($source);
        $sourceId = $source['id'] ?? null;
        if ($sourceId === null || $sourceId === '') {
            return 0;
        }

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

                $this->store->upsertHolidayDay($sourceId, $importedUid, $date, [
                    'name' => $summary,
                    'starts_at' => $day->copy()->startOfDay()->toIso8601String(),
                    // Bound each stored day to itself — do not reuse the multi-day VEVENT DTEND.
                    'ends_at' => $day->copy()->endOfDay()->toIso8601String(),
                    'country' => $source['country'] ?? null,
                    'region' => $source['region'] ?? null,
                    'type' => $holidayType,
                    'all_day' => true,
                ]);

                $count++;
            }
        }

        return $count;
    }

    public function ensurePresetSources(): void
    {
        foreach (HolidaySourceDefaults::all() as $preset) {
            $this->store->upsertSource($preset);
        }
    }

    /**
     * @param  array<string, mixed>  $source
     */
    private function resolveHolidayType(array $source): string
    {
        $name = strtolower((string) ($source['name'] ?? ''));
        if (str_contains($name, 'schulferien') || str_contains($name, 'school')) {
            return 'school_holiday';
        }

        if (HolidaySourceDefaults::importCategoryForSource($source) === HolidaySourceDefaults::IMPORT_CATEGORY_CUSTOM) {
            return 'custom';
        }

        return 'public_holiday';
    }
}
