<?php

namespace Tests\Feature\Calendar;

use App\Accounts\Contracts\PlanStoreInterface;
use App\Accounts\Contracts\UserRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

final class CalendarHubTest extends TestCase
{
    use RefreshDatabase;

    private string $accountsPath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->accountsPath = storage_path('app/bn-tools-cal-test-'.uniqid('', true));
        File::ensureDirectoryExists($this->accountsPath);
        File::ensureDirectoryExists($this->accountsPath.DIRECTORY_SEPARATOR.'plans');
        config([
            'accounts.enabled' => true,
            'accounts.path' => $this->accountsPath,
            'storage.driver' => 'file',
        ]);
    }

    protected function tearDown(): void
    {
        File::deleteDirectory($this->accountsPath);
        parent::tearDown();
    }

    public function test_calendar_hub_renders_with_bootstrap_layers(): void
    {
        $response = $this->get('/calendar');
        $response->assertOk();
        $response->assertSee('id="calendar-bootstrap"', false);
        $response->assertSee('Stories', false);
        $response->assertSee('data-calendar-filter="my-tasks"', false);
    }

    public function test_events_api_includes_story_with_published_at(): void
    {
        $response = $this->getJson('/api/calendar/events?from=2020-01-01&to=2030-12-31');
        $response->assertOk();
        $data = $response->json('data');
        $this->assertIsArray($data);

        $stories = array_values(array_filter(
            $data,
            static fn (array $e): bool => ($e['kind'] ?? null) === 'story',
        ));
        $this->assertNotEmpty($stories);
        $this->assertSame(1, $stories[0]['calendar_id']);
        $this->assertNotEmpty($stories[0]['starts_at']);
    }

    public function test_events_api_collapses_complete_series_on_same_day(): void
    {
        $response = $this->getJson('/api/calendar/events?from=2026-07-13&to=2026-07-13');
        $response->assertOk();
        $data = $response->json('data');
        $this->assertIsArray($data);

        $series = array_values(array_filter(
            $data,
            static fn (array $e): bool => ($e['kind'] ?? null) === 'series'
                && ($e['series_id'] ?? null) === 'missing-pieces',
        ));
        $this->assertCount(1, $series);
        $this->assertSame(6, $series[0]['part_count']);
        $this->assertSame('2026-07-13', $series[0]['starts_at']);

        $missingPieceStories = array_values(array_filter(
            $data,
            static fn (array $e): bool => ($e['kind'] ?? null) === 'story'
                && str_starts_with((string) ($e['id'] ?? ''), 'story:missing-pieces'),
        ));
        $this->assertSame([], $missingPieceStories);
    }

    public function test_events_api_collapses_incomplete_series_on_same_day(): void
    {
        $response = $this->getJson('/api/calendar/events?from=2026-07-29&to=2026-07-29');
        $response->assertOk();
        $data = $response->json('data');
        $this->assertIsArray($data);

        $biSeries = array_values(array_filter(
            $data,
            static fn (array $e): bool => ($e['kind'] ?? null) === 'series'
                && ($e['series_id'] ?? null) === 'bi-governance-decisions',
        ));
        $this->assertCount(1, $biSeries);
        $this->assertSame(6, $biSeries[0]['part_count']);
        $this->assertSame(8, $biSeries[0]['series_total']);

        // Individual series parts must not remain as story chips once collapsed.
        $remainingBi = array_values(array_filter(
            $data,
            static fn (array $e): bool => ($e['kind'] ?? null) === 'story'
                && in_array(($e['series_id'] ?? null), ['bi-governance-decisions', 'source-load-decisions'], true),
        ));
        $this->assertSame([], $remainingBi, 'Incomplete same-day series parts must collapse to series badges');
    }

    public function test_holidays_api_bounds_each_day_ends_at(): void
    {
        $this->app->forgetInstance(\App\Calendar\Contracts\CalendarHolidayStoreInterface::class);
        $this->app->forgetInstance(\App\Calendar\FileCalendarHolidayStore::class);
        $this->app->forgetInstance(\App\Accounts\AccountsConfig::class);
        $this->app->forgetInstance(\App\Calendar\CalendarEventAggregator::class);

        app(\App\Calendar\CalendarHolidayImportService::class)->ensurePresetSources();
        $store = app(\App\Calendar\Contracts\CalendarHolidayStoreInterface::class);
        $source = $store->findSource(\App\Calendar\HolidaySourceDefaults::PRESET_SCHOOL_HOLIDAYS_ID)
            ?? $store->findSource('de-nw-school-holidays');
        $this->assertNotNull($source);

        $store->upsertHolidayDay($source['id'], 'uid-summer-2026-07-20', '2026-07-20', [
            'name' => 'Sommerferien Nordrhein-Westfalen 2026',
            'starts_at' => '2026-07-20T00:00:00+00:00',
            // Legacy multi-day VEVENT end — API must clamp to the day itself.
            'ends_at' => '2026-09-02T00:00:00+00:00',
            'type' => 'school_holiday',
            'all_day' => true,
            'country' => 'DE',
            'region' => 'DE-NW',
        ]);

        $response = $this->getJson('/api/calendar/holidays?from=2026-07-20&to=2026-07-20');
        $response->assertOk();
        $data = $response->json('data');
        $this->assertNotEmpty($data);

        $summer = array_values(array_filter(
            $data,
            static fn (array $h): bool => str_contains((string) ($h['name'] ?? ''), 'Sommerferien'),
        ));
        $this->assertNotEmpty($summer);
        $this->assertSame('2026-07-20', $summer[0]['date']);
        $this->assertSame('2026-07-20', substr((string) $summer[0]['ends_at'], 0, 10));
    }

    public function test_events_api_includes_plan_tasks_for_authenticated_owner(): void
    {
        $users = app(UserRepositoryInterface::class);
        $user = $users->upsert([
            'id' => 'user_cal',
            'email' => 'cal@example.com',
            'displayName' => 'Cal User',
            'passwordHash' => password_hash('password123', PASSWORD_DEFAULT),
            'active' => true,
            'pendingApproval' => false,
        ]);

        app(PlanStoreInterface::class)->save([
            'id' => 'plan_cal_test1',
            'templateSlug' => 'demo',
            'status' => 'active',
            'archived' => false,
            'startedAt' => '2026-06-29',
            'completedTasks' => [],
            'completedDeliverables' => [],
            'itemOverrides' => [
                'demo:week-01:task:task_a' => [
                    'assigneeType' => 'person',
                    'assigneeId' => 'user_cal',
                    'dueDate' => '2026-07-02',
                ],
            ],
            'templateSnapshot' => [
                'slug' => 'demo',
                'unit' => 'week',
                'locales' => [
                    'en' => [
                        'title' => 'Demo Plan',
                        'sprints' => [
                            [
                                'id' => 'week-01',
                                'tasks' => [
                                    ['id' => 'task_a', 'label' => 'Calendar task A'],
                                ],
                                'deliverables' => [],
                            ],
                        ],
                    ],
                ],
                'sprints' => [
                    [
                        'id' => 'week-01',
                        'number' => 1,
                        'tasks' => [
                            [
                                'id' => 'task_a',
                                'assigneeType' => 'person',
                                'assigneeId' => null,
                            ],
                        ],
                        'deliverables' => [],
                    ],
                ],
            ],
        ], $user);

        $this->post('/login', [
            'email' => 'cal@example.com',
            'password' => 'password123',
        ])->assertRedirect();

        $response = $this->getJson('/api/calendar/events?from=2026-06-01&to=2026-07-31');
        $response->assertOk();
        $tasks = array_values(array_filter(
            $response->json('data'),
            static fn (array $e): bool => ($e['kind'] ?? null) === 'task',
        ));
        $this->assertNotEmpty($tasks);
        $this->assertSame('2026-07-02', $tasks[0]['starts_at']);
        $this->assertSame('Calendar task A', $tasks[0]['title']);
        $this->assertSame('user_cal', $tasks[0]['assignee_user_id']);
    }

    public function test_localized_calendar_route_works(): void
    {
        $this->get('/de/calendar')->assertOk();
    }

    public function test_calendar_bootstrap_exposes_string_nrw_holiday_source_ids(): void
    {
        config([
            'accounts.path' => $this->accountsPath,
            'storage.driver' => 'file',
        ]);
        $this->app->forgetInstance(\App\Calendar\Contracts\CalendarHolidayStoreInterface::class);
        $this->app->forgetInstance(\App\Calendar\FileCalendarHolidayStore::class);
        $this->app->forgetInstance(\App\Accounts\AccountsConfig::class);
        $this->app->forgetInstance(\App\Calendar\CalendarEventAggregator::class);

        app(\App\Calendar\CalendarHolidayImportService::class)->ensurePresetSources();

        $store = app(\App\Calendar\Contracts\CalendarHolidayStoreInterface::class);
        $source = $store->findSource(\App\Calendar\HolidaySourceDefaults::PRESET_PUBLIC_HOLIDAYS_ID);
        $this->assertNotNull($source);
        $store->upsertHolidayDay($source['id'], 'uid-neujahr-2026', '2026-01-01', [
            'name' => 'Neujahr',
            'type' => 'public_holiday',
            'all_day' => true,
            'country' => 'DE',
            'region' => 'DE-NW',
        ]);

        $response = $this->get('/calendar?year=2026&month=1');
        $response->assertOk();
        $response->assertSee('de-nw-public-holidays', false);
        $response->assertSee('de-nw-school-holidays', false);
        $response->assertSee('Neujahr', false);

        $api = $this->getJson('/api/calendar/holidays?from=2026-01-01&to=2026-01-01');
        $api->assertOk();
        $this->assertSame('de-nw-public-holidays', $api->json('data.0.source_id'));
    }
}
