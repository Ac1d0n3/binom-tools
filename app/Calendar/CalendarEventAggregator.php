<?php

namespace App\Calendar;

use App\Accounts\AccountUser;
use App\Accounts\Contracts\PlanStoreInterface;
use App\Accounts\Contracts\StoryAclRepositoryInterface;
use App\Calendar\Contracts\CalendarHolidayStoreInterface;
use App\Playbooks\PlaybookRepository;
use App\Support\LocaleUrl;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

final class CalendarEventAggregator
{
    public const STORIES_CALENDAR_ID = 1;

    private const PLAN_COLORS = [
        '#0d9488',
        '#7c3aed',
        '#db2777',
        '#ea580c',
        '#2563eb',
        '#65a30d',
        '#0891b2',
        '#c026d3',
    ];

    public function __construct(
        private readonly PlaybookRepository $playbooks,
        private readonly PlanStoreInterface $plans,
        private readonly StoryAclRepositoryInterface $storyAcl,
        private readonly CalendarHolidayStoreInterface $holidays,
    ) {}

    /**
     * @return list<array<string, mixed>>
     */
    public function storyEvents(?AccountUser $accountUser, Carbon $from, Carbon $to): array
    {
        $locale = current_locale();
        $calendarId = (int) config('calendar.stories.calendar_id', self::STORIES_CALENDAR_ID);
        $color = (string) config('calendar.stories.color', '#3b82f6');
        $fromDay = $from->copy()->startOfDay();
        $toDay = $to->copy()->endOfDay();

        $seriesById = [];
        foreach ($this->playbooks->allSeries() as $series) {
            $seriesById[$series->id] = $series;
        }

        /** @var list<array<string, mixed>> $stories */
        $stories = [];

        foreach ($this->playbooks->allForIndex() as $item) {
            $slug = (string) ($item['slug'] ?? '');
            if ($slug === '') {
                continue;
            }

            if (accounts_enabled() && ! $this->storyAcl->canAccess($accountUser, $slug)) {
                continue;
            }

            $sortDate = $item['sortDate'] ?? null;
            if (! $sortDate instanceof \Carbon\CarbonInterface) {
                try {
                    $sortDate = Carbon::parse((string) $sortDate);
                } catch (\Throwable) {
                    continue;
                }
            }

            $day = $sortDate->copy()->startOfDay();
            if ($day->lt($fromDay) || $day->gt($toDay)) {
                continue;
            }

            $title = $item['locales'][$locale]['title']
                ?? $item['locales']['en']['title']
                ?? $item['locales']['de']['title']
                ?? $slug;

            $seriesId = is_string($item['seriesId'] ?? null) && $item['seriesId'] !== ''
                ? (string) $item['seriesId']
                : null;
            $seriesTitle = $item['locales'][$locale]['seriesTitle']
                ?? $item['locales']['en']['seriesTitle']
                ?? $item['locales']['de']['seriesTitle']
                ?? null;
            if (($seriesTitle === null || $seriesTitle === '') && $seriesId !== null && isset($seriesById[$seriesId])) {
                $seriesTitle = $seriesById[$seriesId]->title($locale);
            }

            $date = $day->toDateString();
            $stories[] = [
                'id' => 'story:'.$slug,
                'title' => $title,
                'starts_at' => $date,
                'ends_at' => $date,
                'all_day' => true,
                'calendar_id' => $calendarId,
                'calendar_color' => $color,
                'url' => locale_route('playbooks.show', ['slug' => $slug]),
                'is_recurring' => false,
                'kind' => 'story',
                'completed' => false,
                'series_id' => $seriesId,
                'series_part' => is_numeric($item['seriesPart'] ?? null) ? (int) $item['seriesPart'] : null,
                'series_title' => is_string($seriesTitle) && $seriesTitle !== '' ? $seriesTitle : null,
            ];
        }

        return $this->collapseCompleteSeriesOnSameDay($stories, $seriesById, $locale);
    }

    /**
     * When every part of a series lands on the same day, emit one series badge instead of many story badges.
     *
     * @param  list<array<string, mixed>>  $stories
     * @param  array<string, \App\Playbooks\PlaybookSeriesOverview>  $seriesById
     * @return list<array<string, mixed>>
     */
    private function collapseCompleteSeriesOnSameDay(array $stories, array $seriesById, string $locale): array
    {
        $grouped = [];
        foreach ($stories as $story) {
            $seriesId = $story['series_id'] ?? null;
            $date = (string) ($story['starts_at'] ?? '');
            $key = is_string($seriesId) && $seriesId !== ''
                ? 'series:'.$seriesId.':'.$date
                : 'story:'.($story['id'] ?? uniqid('s', true));
            $grouped[$key][] = $story;
        }

        $entries = [];
        foreach ($grouped as $group) {
            $first = $group[0];
            $seriesId = $first['series_id'] ?? null;
            $series = is_string($seriesId) ? ($seriesById[$seriesId] ?? null) : null;

            if (
                $series !== null
                && count($group) >= 2
                && count($group) === $series->partCount()
            ) {
                usort(
                    $group,
                    static fn (array $a, array $b): int => ($a['series_part'] ?? PHP_INT_MAX) <=> ($b['series_part'] ?? PHP_INT_MAX),
                );
                $lead = $group[0];
                $title = $lead['series_title']
                    ?? $series->title($locale)
                    ?? (string) $seriesId;
                $date = (string) $lead['starts_at'];

                $entries[] = [
                    'id' => 'series:'.$seriesId.':'.$date,
                    'title' => $title,
                    'starts_at' => $date,
                    'ends_at' => $date,
                    'all_day' => true,
                    'calendar_id' => $lead['calendar_id'],
                    'calendar_color' => $lead['calendar_color'],
                    'url' => $lead['url'],
                    'is_recurring' => false,
                    'kind' => 'series',
                    'completed' => false,
                    'series_id' => $seriesId,
                    'part_count' => count($group),
                ];

                continue;
            }

            foreach ($group as $story) {
                unset($story['series_id'], $story['series_part'], $story['series_title']);
                $entries[] = $story;
            }
        }

        return $entries;
    }

    /**
     * @param  array<string, mixed>  $opts
     * @return list<array<string, mixed>>
     */
    public function planEvents(AccountUser $user, Carbon $from, Carbon $to, array $opts = []): array
    {
        $locale = (string) ($opts['locale'] ?? current_locale());
        $fromDay = $from->copy()->startOfDay();
        $toDay = $to->copy()->endOfDay();
        $entries = [];

        foreach ($this->plans->listVisibleTo($user) as $plan) {
            $status = (string) ($plan['status'] ?? 'active');
            if ($status !== 'active' || ! empty($plan['archived'])) {
                continue;
            }

            $planId = (string) ($plan['id'] ?? '');
            if ($planId === '') {
                continue;
            }

            $calendarId = $this->planCalendarId($planId);
            $color = $this->planColor($planId);
            $templateSlug = (string) ($plan['templateSlug'] ?? ($plan['templateSnapshot']['slug'] ?? 'plan'));
            $snapshot = is_array($plan['templateSnapshot'] ?? null) ? $plan['templateSnapshot'] : [];
            $unit = (string) ($snapshot['unit'] ?? 'week');
            $startedAt = isset($plan['startedAt']) ? (string) $plan['startedAt'] : null;
            $removed = array_fill_keys(array_map('strval', $plan['removedItemKeys'] ?? []), true);
            $overrides = is_array($plan['itemOverrides'] ?? null) ? $plan['itemOverrides'] : [];
            $completedTasks = array_fill_keys(array_map('strval', $plan['completedTasks'] ?? []), true);
            $completedDeliverables = array_fill_keys(array_map('strval', $plan['completedDeliverables'] ?? []), true);
            $customTasks = is_array($plan['customTasks'] ?? null) ? $plan['customTasks'] : [];
            $customDeliverables = is_array($plan['customDeliverables'] ?? null) ? $plan['customDeliverables'] : [];

            $sprints = $this->resolveSprints($snapshot, $plan);
            $planUrl = locale_route('sprint-planner.show', ['instanceId' => $planId]);

            foreach ($sprints as $sprint) {
                $sprintId = (string) ($sprint['id'] ?? '');
                $sprintNumber = (int) ($sprint['number'] ?? 0);
                $range = SprintDateRange::compute($startedAt, $sprintNumber, $unit);
                $defaultDue = $range !== null ? $range['end']->toDateString() : null;

                foreach (['task', 'deliverable'] as $kind) {
                    $bag = $kind === 'task' ? ($sprint['tasks'] ?? []) : ($sprint['deliverables'] ?? []);
                    if (! is_array($bag)) {
                        $bag = [];
                    }

                    $customBag = $kind === 'task'
                        ? ($customTasks[$sprintId] ?? [])
                        : ($customDeliverables[$sprintId] ?? []);
                    if (! is_array($customBag)) {
                        $customBag = [];
                    }

                    $items = array_merge(
                        array_map(static fn (array $item): array => $item + ['_custom' => false], $bag),
                        array_map(static fn (array $item): array => $item + ['_custom' => true], $customBag),
                    );

                    foreach ($items as $item) {
                        if (! is_array($item)) {
                            continue;
                        }

                        $itemId = (string) ($item['id'] ?? '');
                        if ($itemId === '') {
                            continue;
                        }

                        $statusKey = (string) ($item['statusKey'] ?? $this->statusKey($templateSlug, $sprintId, $kind, $itemId));
                        if (isset($removed[$statusKey])) {
                            continue;
                        }

                        $override = is_array($overrides[$statusKey] ?? null) ? $overrides[$statusKey] : [];
                        $due = $this->resolveDueDate($override, $item, $defaultDue);
                        if ($due === null) {
                            continue;
                        }

                        try {
                            $dueDay = Carbon::createFromFormat('Y-m-d', $due)->startOfDay();
                        } catch (\Throwable) {
                            continue;
                        }

                        if ($dueDay->lt($fromDay) || $dueDay->gt($toDay)) {
                            continue;
                        }

                        $completedList = $kind === 'task' ? $completedTasks : $completedDeliverables;
                        $completed = isset($completedList[$statusKey])
                            || (($override['status'] ?? null) === 'completed')
                            || (($item['status'] ?? null) === 'completed');

                        $title = $this->resolveItemTitle($override, $item, $locale, $itemId, $snapshot, $sprintId, $kind);
                        $assignee = $override['assigneeId'] ?? $item['assigneeId'] ?? null;
                        $assignee = $assignee !== null && trim((string) $assignee) !== '' && (string) $assignee !== 'null'
                            ? (string) $assignee
                            : null;

                        $entries[] = [
                            'id' => 'plan:'.$planId.':'.$statusKey,
                            'title' => $title,
                            'starts_at' => $due,
                            'ends_at' => $due,
                            'all_day' => true,
                            'calendar_id' => $calendarId,
                            'calendar_color' => $color,
                            'url' => $planUrl,
                            'is_recurring' => false,
                            'assignee_user_id' => $assignee,
                            'completed' => $completed,
                            'kind' => $kind,
                        ];
                    }
                }
            }
        }

        return $entries;
    }

    /**
     * @return list<array{id: int, title: string, color: string, key: string}>
     */
    public function calendarsForUser(?AccountUser $user, ?string $locale = null): array
    {
        $locale ??= current_locale();
        $calendars = [
            [
                'id' => (int) config('calendar.stories.calendar_id', self::STORIES_CALENDAR_ID),
                'title' => $locale === 'de' ? 'Stories' : 'Stories',
                'color' => (string) config('calendar.stories.color', '#3b82f6'),
                'key' => 'stories',
            ],
        ];

        if ($user === null || ! accounts_enabled()) {
            return $calendars;
        }

        foreach ($this->plans->listVisibleTo($user) as $plan) {
            $status = (string) ($plan['status'] ?? 'active');
            if ($status !== 'active' || ! empty($plan['archived'])) {
                continue;
            }

            $planId = (string) ($plan['id'] ?? '');
            if ($planId === '') {
                continue;
            }

            $calendars[] = [
                'id' => $this->planCalendarId($planId),
                'title' => $this->planTitle($plan, $locale),
                'color' => $this->planColor($planId),
                'key' => 'plan:'.$planId,
            ];
        }

        return $calendars;
    }

    /**
     * @param  array<string, mixed>  $opts
     * @return array<string, mixed>
     */
    public function buildBootstrap(?AccountUser $user, Carbon $from, Carbon $to, string $view, Carbon $anchor, array $opts = []): array
    {
        $locale = (string) ($opts['locale'] ?? current_locale());
        $weekStart = ($opts['week_start'] ?? 'monday') === 'sunday' ? 'sunday' : 'monday';
        $allowedViews = $opts['allowed_views'] ?? ['month', 'week', 'day', 'list'];

        $entries = $this->storyEvents($user, $from, $to);
        if ($user !== null && accounts_enabled()) {
            $entries = array_merge($entries, $this->planEvents($user, $from, $to, ['locale' => $locale]));
        }

        $calendars = $this->calendarsForUser($user, $locale);
        $holidaySources = $this->holidaySources();
        $holidays = $this->holidaysForRange($from, $to);

        return [
            'view' => $view,
            'week_start' => $weekStart,
            'allowed_views' => $allowedViews,
            'anchor' => [
                'year' => $anchor->year,
                'month' => $anchor->month,
                'date' => $anchor->toDateString(),
            ],
            'from' => $from->toDateString(),
            'to' => $to->toDateString(),
            'entries' => $entries,
            'calendars' => $calendars,
            'holiday_sources' => $holidaySources,
            'holidays' => $holidays,
            'layer_defaults' => [
                'calendars' => [],
                'holiday_sources' => [],
            ],
            'holidays_enabled' => true,
            'locale' => $locale,
            'weekdays' => $this->weekdayLabels($weekStart),
            'current_user_id' => $user?->id,
            'urls' => [
                'index' => $this->safeRoute('calendar.index', '/calendar'),
                'entries' => $this->safeRoute('calendar.events', '/api/calendar/events'),
                'holidays' => $this->safeRoute('calendar.holidays', '/api/calendar/holidays'),
            ],
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function holidaySources(): array
    {
        if (! $this->holidays->isReady()) {
            return [];
        }

        return array_map(static function (array $source): array {
            $settings = is_array($source['settings'] ?? null) ? $source['settings'] : [];

            return [
                'id' => $source['id'] ?? null,
                'name' => $source['name'] ?? '',
                'title' => $source['name'] ?? '',
                'type' => $source['type'] ?? 'ical',
                'country' => $source['country'] ?? null,
                'region' => $source['region'] ?? null,
                'color' => $settings['color'] ?? '#94a3b8',
            ];
        }, $this->holidays->listActiveSources());
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function holidaysForRange(Carbon $from, Carbon $to): array
    {
        if (! $this->holidays->isReady()) {
            return [];
        }

        $sourcesById = [];
        foreach ($this->holidays->listSources() as $source) {
            $id = $source['id'] ?? null;
            if ($id === null || $id === '') {
                continue;
            }
            $sourcesById[(string) $id] = $source;
        }

        return array_map(static function (array $holiday) use ($sourcesById): array {
            $date = (string) ($holiday['date']
                ?? (is_string($holiday['starts_at'] ?? null) ? substr((string) $holiday['starts_at'], 0, 10) : null)
                ?? now()->toDateString());
            $sourceId = $holiday['source_id'] ?? null;
            $source = $sourceId !== null ? ($sourcesById[(string) $sourceId] ?? null) : null;
            $settings = is_array($source['settings'] ?? null) ? $source['settings'] : [];

            return [
                'id' => $holiday['id'] ?? null,
                'source_id' => $sourceId,
                'name' => $holiday['name'] ?? '',
                'date' => $date,
                'starts_at' => $holiday['starts_at'] ?? null,
                'ends_at' => $holiday['ends_at'] ?? null,
                'all_day' => (bool) ($holiday['all_day'] ?? true),
                'type' => $holiday['type'] ?? 'public_holiday',
                'country' => $holiday['country'] ?? null,
                'region' => $holiday['region'] ?? null,
                'color' => $settings['color'] ?? '#94a3b8',
            ];
        }, $this->holidays->listHolidaysInRange($from->toDateString(), $to->toDateString()));
    }

    public function planCalendarId(string $planId): int
    {
        return 100 + (abs(crc32($planId)) % 999900);
    }

    private function planColor(string $planId): string
    {
        $index = abs(crc32($planId)) % count(self::PLAN_COLORS);

        return self::PLAN_COLORS[$index];
    }

    /**
     * @param  array<string, mixed>  $plan
     */
    private function planTitle(array $plan, string $locale): string
    {
        $translations = is_array($plan['translations'] ?? null) ? $plan['translations'] : [];
        $snapshot = is_array($plan['templateSnapshot'] ?? null) ? $plan['templateSnapshot'] : [];
        $locales = is_array($snapshot['locales'] ?? null) ? $snapshot['locales'] : [];

        return (string) (
            $translations[$locale]['title']
            ?? $translations['en']['title']
            ?? $translations['de']['title']
            ?? $locales[$locale]['title']
            ?? $locales['en']['title']
            ?? $locales['de']['title']
            ?? $snapshot['title']
            ?? $plan['id']
            ?? 'Plan'
        );
    }

    /**
     * @param  array<string, mixed>  $snapshot
     * @param  array<string, mixed>  $plan
     * @return list<array<string, mixed>>
     */
    private function resolveSprints(array $snapshot, array $plan): array
    {
        $templateSprints = is_array($snapshot['sprints'] ?? null) ? $snapshot['sprints'] : [];
        $customSprints = is_array($plan['customSprints'] ?? null) ? $plan['customSprints'] : [];
        $sprints = array_merge($templateSprints, $customSprints);

        usort($sprints, static fn (array $a, array $b): int => ((int) ($a['number'] ?? 0)) <=> ((int) ($b['number'] ?? 0)));

        return $sprints;
    }

    /**
     * @param  array<string, mixed>  $override
     * @param  array<string, mixed>  $item
     */
    private function resolveDueDate(array $override, array $item, ?string $defaultDue): ?string
    {
        foreach ([$override['dueDate'] ?? null, $item['dueDate'] ?? null, $defaultDue] as $candidate) {
            if (! is_string($candidate) || trim($candidate) === '') {
                continue;
            }

            if (preg_match('/^(\d{4}-\d{2}-\d{2})/', trim($candidate), $matches) === 1) {
                return $matches[1];
            }
        }

        return null;
    }

    /**
     * @param  array<string, mixed>  $override
     * @param  array<string, mixed>  $item
     * @param  array<string, mixed>  $snapshot
     */
    private function resolveItemTitle(
        array $override,
        array $item,
        string $locale,
        string $fallback,
        array $snapshot = [],
        string $sprintId = '',
        string $kind = 'task',
    ): string {
        foreach (['label', 'title'] as $field) {
            if (isset($override[$field])) {
                $resolved = $this->localize($override[$field], $locale);
                if ($resolved !== '') {
                    return $resolved;
                }
            }
        }

        $resolved = $this->localize($item['label'] ?? $item['title'] ?? null, $locale);
        if ($resolved !== '') {
            return $resolved;
        }

        $locales = is_array($snapshot['locales'] ?? null) ? $snapshot['locales'] : [];
        $bag = $kind === 'task' ? 'tasks' : 'deliverables';
        foreach ([$locale, 'en', 'de'] as $loc) {
            $sprints = $locales[$loc]['sprints'] ?? null;
            if (! is_array($sprints)) {
                continue;
            }
            foreach ($sprints as $sprint) {
                if (! is_array($sprint) || (string) ($sprint['id'] ?? '') !== $sprintId) {
                    continue;
                }
                foreach ($sprint[$bag] ?? [] as $localized) {
                    if (! is_array($localized) || (string) ($localized['id'] ?? '') !== $fallback) {
                        continue;
                    }
                    $label = $this->localize($localized['label'] ?? $localized['title'] ?? null, $locale);
                    if ($label !== '') {
                        return $label;
                    }
                }
            }
        }

        return $fallback;
    }

    private function localize(mixed $value, string $locale): string
    {
        if (is_string($value)) {
            return trim($value);
        }

        if (is_array($value)) {
            return trim((string) ($value[$locale] ?? $value['en'] ?? $value['de'] ?? ''));
        }

        return '';
    }

    private function statusKey(string $templateSlug, string $sprintId, string $kind, string $itemId): string
    {
        return $templateSlug.':'.$sprintId.':'.$kind.':'.$itemId;
    }

    /**
     * @return list<string>
     */
    private function weekdayLabels(string $weekStart = 'monday'): array
    {
        $labels = [
            __('calendar.weekdays.mon'),
            __('calendar.weekdays.tue'),
            __('calendar.weekdays.wed'),
            __('calendar.weekdays.thu'),
            __('calendar.weekdays.fri'),
            __('calendar.weekdays.sat'),
            __('calendar.weekdays.sun'),
        ];

        if ($weekStart === 'sunday') {
            return [$labels[6], $labels[0], $labels[1], $labels[2], $labels[3], $labels[4], $labels[5]];
        }

        return $labels;
    }

    private function safeRoute(string $name, string $fallbackPath): string
    {
        if (Route::has($name) || Route::has('localized.'.$name)) {
            return locale_route($name);
        }

        return LocaleUrl::path($fallbackPath);
    }
}
