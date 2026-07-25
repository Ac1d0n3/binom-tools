<?php

namespace Tests\Unit\Calendar;

use App\Calendar\CalendarHolidayImportService;
use App\Models\BnTools\BnCalendarHoliday;
use App\Models\BnTools\BnCalendarHolidaySource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class CalendarHolidayImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_imports_all_day_events_day_by_day_from_ics(): void
    {
        $source = BnCalendarHolidaySource::query()->create([
            'name' => 'Schulferien Test',
            'type' => 'ical',
            'country' => 'DE',
            'region' => 'DE-NW',
            'url' => 'https://www.feiertage-deutschland.de/example.ics',
            'is_active' => true,
            'settings' => ['color' => '#f59e0b'],
        ]);

        $ics = file_get_contents(base_path('tests/Fixtures/calendar/sample-holidays.ics'));
        $this->assertNotFalse($ics);

        $count = app(CalendarHolidayImportService::class)->importFromContent($source, $ics);
        $this->assertSame(4, $count);

        $names = BnCalendarHoliday::query()->orderBy('date')->pluck('name')->all();
        $this->assertSame([
            'Tag der Arbeit',
            'Sommerferien Test',
            'Sommerferien Test',
            'Sommerferien Test',
        ], $names);

        $this->assertSame('school_holiday', BnCalendarHoliday::query()->where('name', 'Sommerferien Test')->value('type'));
        $this->assertSame('2026-07-13', BnCalendarHoliday::query()->where('name', 'Sommerferien Test')->orderBy('date')->value('date')?->toDateString());
    }

    public function test_ensure_preset_sources_creates_nrw_feeds(): void
    {
        app(CalendarHolidayImportService::class)->ensurePresetSources();

        $this->assertSame(2, BnCalendarHolidaySource::query()->count());
        $this->assertTrue(
            BnCalendarHolidaySource::query()->where('url', 'like', '%schulferien-nordrhein-westfalen.ics')->exists(),
        );
    }
}
