<?php

namespace Tests\Unit\Calendar;

use App\Calendar\CalendarHolidayImportService;
use App\Calendar\Contracts\CalendarHolidayStoreInterface;
use App\Calendar\HolidaySourceDefaults;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

final class CalendarHolidayImportTest extends TestCase
{
    private string $accountsPath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->accountsPath = storage_path('app/bn-tools-holiday-test-'.uniqid('', true));
        File::ensureDirectoryExists($this->accountsPath);
        config([
            'accounts.path' => $this->accountsPath,
            'storage.driver' => 'file',
        ]);
        $this->app->forgetInstance(CalendarHolidayStoreInterface::class);
        $this->app->forgetInstance(\App\Calendar\FileCalendarHolidayStore::class);
        $this->app->forgetInstance(\App\Accounts\AccountsConfig::class);
    }

    protected function tearDown(): void
    {
        File::deleteDirectory($this->accountsPath);
        parent::tearDown();
    }

    public function test_imports_all_day_events_day_by_day_from_ics(): void
    {
        /** @var CalendarHolidayStoreInterface $store */
        $store = app(CalendarHolidayStoreInterface::class);
        $source = $store->upsertSource([
            'id' => 'test-school-holidays',
            'name' => 'Schulferien Test',
            'type' => 'ical',
            'country' => 'DE',
            'region' => 'DE-NW',
            'url' => 'https://www.feiertage-deutschland.de/example.ics',
            'is_active' => true,
            'settings' => ['color' => '#f59e0b'],
        ]);

        $ics = file_get_contents(base_path('tests/fixtures/calendar/sample-holidays.ics'));
        $this->assertNotFalse($ics);

        $count = app(CalendarHolidayImportService::class)->importFromContent($source, $ics);
        $this->assertSame(4, $count);

        $holidays = $store->listHolidaysInRange('2026-01-01', '2026-12-31');
        $names = array_map(static fn (array $row): string => (string) $row['name'], $holidays);
        $this->assertSame([
            'Tag der Arbeit',
            'Sommerferien Test',
            'Sommerferien Test',
            'Sommerferien Test',
        ], $names);

        $summer = array_values(array_filter(
            $holidays,
            static fn (array $row): bool => ($row['name'] ?? null) === 'Sommerferien Test',
        ));
        $this->assertSame('school_holiday', $summer[0]['type'] ?? null);
        $this->assertSame('2026-07-13', $summer[0]['date'] ?? null);
    }

    public function test_ensure_preset_sources_creates_nrw_feeds(): void
    {
        app(CalendarHolidayImportService::class)->ensurePresetSources();

        /** @var CalendarHolidayStoreInterface $store */
        $store = app(CalendarHolidayStoreInterface::class);
        $sources = $store->listSources();
        $this->assertCount(2, $sources);
        $ids = array_map(static fn (array $row): string => (string) ($row['id'] ?? ''), $sources);
        $this->assertContains(HolidaySourceDefaults::PRESET_PUBLIC_HOLIDAYS_ID, $ids);
        $this->assertContains(HolidaySourceDefaults::PRESET_SCHOOL_HOLIDAYS_ID, $ids);
        $urls = array_map(static fn (array $row): string => (string) ($row['url'] ?? ''), $sources);
        $this->assertTrue(
            (bool) array_filter($urls, static fn (string $url): bool => str_contains($url, 'schulferien-nordrhein-westfalen.ics')),
        );
    }

    public function test_holidays_api_returns_imported_file_store_data(): void
    {
        /** @var CalendarHolidayStoreInterface $store */
        $store = app(CalendarHolidayStoreInterface::class);
        $source = $store->upsertSource([
            'id' => HolidaySourceDefaults::PRESET_PUBLIC_HOLIDAYS_ID,
            'name' => 'Feiertage Test',
            'type' => 'ical',
            'country' => 'DE',
            'region' => 'DE-NW',
            'url' => 'https://www.feiertage-deutschland.de/example.ics',
            'is_active' => true,
            'settings' => ['color' => '#ef4444'],
        ]);

        $store->upsertHolidayDay($source['id'], 'uid-1-2026-05-01', '2026-05-01', [
            'name' => 'Tag der Arbeit',
            'type' => 'public_holiday',
            'all_day' => true,
            'country' => 'DE',
            'region' => 'DE-NW',
        ]);

        $response = $this->getJson('/api/calendar/holidays?from=2026-05-01&to=2026-05-01');
        $response->assertOk();
        $data = $response->json('data');
        $this->assertIsArray($data);
        $this->assertNotEmpty($data);
        $this->assertSame('Tag der Arbeit', $data[0]['name'] ?? null);
        $this->assertSame(HolidaySourceDefaults::PRESET_PUBLIC_HOLIDAYS_ID, $data[0]['source_id'] ?? null);
    }
}
