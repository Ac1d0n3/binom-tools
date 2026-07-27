<?php

namespace App\Calendar;

use App\Calendar\Contracts\CalendarHolidayStoreInterface;
use App\Models\BnTools\BnCalendarHoliday;
use App\Models\BnTools\BnCalendarHolidaySource;
use Illuminate\Support\Facades\Schema;

final class DatabaseCalendarHolidayStore implements CalendarHolidayStoreInterface
{
    public function isReady(): bool
    {
        try {
            return Schema::hasTable('bn_calendar_holidays')
                && Schema::hasTable('bn_calendar_holiday_sources');
        } catch (\Throwable) {
            return false;
        }
    }

    public function listActiveSources(): array
    {
        if (! $this->isReady()) {
            return [];
        }

        return BnCalendarHolidaySource::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get()
            ->map(fn (BnCalendarHolidaySource $source): array => $this->sourceToArray($source))
            ->all();
    }

    public function listSources(): array
    {
        if (! $this->isReady()) {
            return [];
        }

        return BnCalendarHolidaySource::query()
            ->orderBy('name')
            ->get()
            ->map(fn (BnCalendarHolidaySource $source): array => $this->sourceToArray($source))
            ->all();
    }

    public function findSource(int|string $id): ?array
    {
        if (! $this->isReady()) {
            return null;
        }

        $source = BnCalendarHolidaySource::query()->find($id);

        return $source instanceof BnCalendarHolidaySource ? $this->sourceToArray($source) : null;
    }

    public function upsertSource(array $preset): array
    {
        $url = is_string($preset['url'] ?? null) ? (string) $preset['url'] : '';
        $source = BnCalendarHolidaySource::query()->updateOrCreate(
            ['url' => $url !== '' ? $url : null],
            [
                'name' => (string) ($preset['name'] ?? 'Holiday source'),
                'type' => (string) ($preset['type'] ?? 'ical'),
                'country' => $preset['country'] ?? null,
                'region' => $preset['region'] ?? null,
                'is_active' => (bool) ($preset['is_active'] ?? true),
                'sync_interval_hours' => isset($preset['sync_interval_hours'])
                    ? (int) $preset['sync_interval_hours']
                    : null,
                'settings' => is_array($preset['settings'] ?? null) ? $preset['settings'] : [],
            ],
        );

        return $this->sourceToArray($source);
    }

    public function markSyncSuccess(array $source): void
    {
        $model = $this->resolveModel($source);
        if ($model === null) {
            return;
        }

        $settings = is_array($model->settings) ? $model->settings : [];
        unset($settings['last_error']);
        $model->update([
            'last_synced_at' => now(),
            'settings' => $settings,
        ]);
    }

    public function markSyncFailure(array $source, string $error): void
    {
        $model = $this->resolveModel($source);
        if ($model === null) {
            return;
        }

        $settings = is_array($model->settings) ? $model->settings : [];
        $settings['last_error'] = $error;
        $model->update(['settings' => $settings]);
    }

    public function upsertHolidayDay(int|string $sourceId, ?string $importedUid, string $date, array $payload): void
    {
        BnCalendarHoliday::query()->updateOrCreate(
            [
                'imported_uid' => $importedUid,
                'date' => $date,
                'source_id' => $sourceId,
            ],
            [
                'name' => (string) ($payload['name'] ?? 'Holiday'),
                'starts_at' => $payload['starts_at'] ?? null,
                'ends_at' => $payload['ends_at'] ?? null,
                'country' => $payload['country'] ?? null,
                'region' => $payload['region'] ?? null,
                'type' => (string) ($payload['type'] ?? 'public_holiday'),
                'all_day' => (bool) ($payload['all_day'] ?? true),
                'metadata' => is_array($payload['metadata'] ?? null) ? $payload['metadata'] : null,
            ],
        );
    }

    public function listHolidaysInRange(string $from, string $to): array
    {
        if (! $this->isReady()) {
            return [];
        }

        return BnCalendarHoliday::query()
            ->inRange($from, $to)
            ->orderBy('date')
            ->get()
            ->map(function (BnCalendarHoliday $holiday): array {
                return [
                    'id' => $holiday->id,
                    'source_id' => $holiday->source_id,
                    'imported_uid' => $holiday->imported_uid,
                    'date' => $holiday->date?->toDateString(),
                    'name' => $holiday->name,
                    'starts_at' => $holiday->starts_at?->toIso8601String(),
                    'ends_at' => $holiday->ends_at?->toIso8601String(),
                    'country' => $holiday->country,
                    'region' => $holiday->region,
                    'type' => $holiday->type,
                    'all_day' => (bool) $holiday->all_day,
                    'metadata' => is_array($holiday->metadata) ? $holiday->metadata : [],
                ];
            })
            ->all();
    }

    /**
     * @param  array<string, mixed>  $source
     */
    private function resolveModel(array $source): ?BnCalendarHolidaySource
    {
        if (! $this->isReady()) {
            return null;
        }

        $id = $source['id'] ?? null;
        if ($id === null || $id === '') {
            return null;
        }

        $model = BnCalendarHolidaySource::query()->find($id);

        return $model instanceof BnCalendarHolidaySource ? $model : null;
    }

    /**
     * @return array<string, mixed>
     */
    private function sourceToArray(BnCalendarHolidaySource $source): array
    {
        return [
            'id' => $source->id,
            'name' => $source->name,
            'type' => $source->type,
            'country' => $source->country,
            'region' => $source->region,
            'url' => $source->url,
            'is_active' => (bool) $source->is_active,
            'sync_interval_hours' => $source->sync_interval_hours,
            'last_synced_at' => $source->last_synced_at?->toIso8601String(),
            'settings' => is_array($source->settings) ? $source->settings : [],
        ];
    }
}
