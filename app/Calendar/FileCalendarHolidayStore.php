<?php

namespace App\Calendar;

use App\Accounts\AccountsConfig;
use App\Accounts\JsonFileStore;
use App\Calendar\Contracts\CalendarHolidayStoreInterface;

final class FileCalendarHolidayStore implements CalendarHolidayStoreInterface
{
    public function __construct(
        private readonly AccountsConfig $config,
        private readonly JsonFileStore $files,
    ) {}

    public function isReady(): bool
    {
        return true;
    }

    public function listActiveSources(): array
    {
        return array_values(array_filter(
            $this->listSources(),
            static fn (array $source): bool => (bool) ($source['is_active'] ?? false),
        ));
    }

    public function listSources(): array
    {
        $sources = $this->readSources();
        usort($sources, static function (array $a, array $b): int {
            return strcmp((string) ($a['name'] ?? ''), (string) ($b['name'] ?? ''));
        });

        return $sources;
    }

    public function findSource(int|string $id): ?array
    {
        $needle = (string) $id;
        foreach ($this->readSources() as $source) {
            if ((string) ($source['id'] ?? '') === $needle) {
                return $source;
            }
        }

        return null;
    }

    public function upsertSource(array $preset): array
    {
        $sources = $this->readSources();
        $url = is_string($preset['url'] ?? null) ? (string) $preset['url'] : '';
        $presetId = is_string($preset['id'] ?? null) && $preset['id'] !== ''
            ? (string) $preset['id']
            : null;

        $index = null;
        foreach ($sources as $i => $source) {
            $sameUrl = $url !== '' && ($source['url'] ?? null) === $url;
            $sameId = $presetId !== null && (string) ($source['id'] ?? '') === $presetId;
            if ($sameUrl || $sameId) {
                $index = $i;
                break;
            }
        }

        $existing = $index !== null ? $sources[$index] : [];
        $id = $presetId
            ?? (is_string($existing['id'] ?? null) && $existing['id'] !== '' ? (string) $existing['id'] : null)
            ?? $this->newSourceId($url);

        $stored = [
            'id' => $id,
            'name' => (string) ($preset['name'] ?? $existing['name'] ?? 'Holiday source'),
            'type' => (string) ($preset['type'] ?? $existing['type'] ?? 'ical'),
            'country' => $preset['country'] ?? $existing['country'] ?? null,
            'region' => $preset['region'] ?? $existing['region'] ?? null,
            'url' => $url !== '' ? $url : ($existing['url'] ?? null),
            'is_active' => (bool) ($preset['is_active'] ?? $existing['is_active'] ?? true),
            'sync_interval_hours' => isset($preset['sync_interval_hours'])
                ? (int) $preset['sync_interval_hours']
                : (isset($existing['sync_interval_hours']) ? (int) $existing['sync_interval_hours'] : null),
            'last_synced_at' => $existing['last_synced_at'] ?? null,
            'settings' => is_array($preset['settings'] ?? null)
                ? $preset['settings']
                : (is_array($existing['settings'] ?? null) ? $existing['settings'] : []),
        ];

        if ($index === null) {
            $sources[] = $stored;
        } else {
            $sources[$index] = $stored;
        }

        $this->writeSources($sources);

        return $stored;
    }

    public function markSyncSuccess(array $source): void
    {
        $id = (string) ($source['id'] ?? '');
        if ($id === '') {
            return;
        }

        $sources = $this->readSources();
        foreach ($sources as $i => $row) {
            if ((string) ($row['id'] ?? '') !== $id) {
                continue;
            }
            $settings = is_array($row['settings'] ?? null) ? $row['settings'] : [];
            unset($settings['last_error']);
            $sources[$i]['settings'] = $settings;
            $sources[$i]['last_synced_at'] = now()->toIso8601String();
            $this->writeSources($sources);

            return;
        }
    }

    public function markSyncFailure(array $source, string $error): void
    {
        $id = (string) ($source['id'] ?? '');
        if ($id === '') {
            return;
        }

        $sources = $this->readSources();
        foreach ($sources as $i => $row) {
            if ((string) ($row['id'] ?? '') !== $id) {
                continue;
            }
            $settings = is_array($row['settings'] ?? null) ? $row['settings'] : [];
            $settings['last_error'] = $error;
            $sources[$i]['settings'] = $settings;
            $this->writeSources($sources);

            return;
        }
    }

    public function upsertHolidayDay(int|string $sourceId, ?string $importedUid, string $date, array $payload): void
    {
        $sourceKey = (string) $sourceId;
        $holidays = $this->readHolidays();
        $index = null;

        foreach ($holidays as $i => $row) {
            if ((string) ($row['source_id'] ?? '') !== $sourceKey) {
                continue;
            }
            if ((string) ($row['date'] ?? '') !== $date) {
                continue;
            }
            $rowUid = isset($row['imported_uid']) ? (string) $row['imported_uid'] : null;
            if ($rowUid === $importedUid || ($importedUid === null && ($rowUid === null || $rowUid === ''))) {
                $index = $i;
                break;
            }
        }

        $id = $index !== null && is_string($holidays[$index]['id'] ?? null)
            ? (string) $holidays[$index]['id']
            : $this->holidayId($sourceKey, $importedUid, $date);

        $stored = [
            'id' => $id,
            'source_id' => $sourceKey,
            'imported_uid' => $importedUid,
            'date' => $date,
            'name' => (string) ($payload['name'] ?? 'Holiday'),
            'starts_at' => $payload['starts_at'] ?? null,
            'ends_at' => $payload['ends_at'] ?? null,
            'country' => $payload['country'] ?? null,
            'region' => $payload['region'] ?? null,
            'type' => (string) ($payload['type'] ?? 'public_holiday'),
            'all_day' => (bool) ($payload['all_day'] ?? true),
            'metadata' => is_array($payload['metadata'] ?? null) ? $payload['metadata'] : [],
        ];

        if ($index === null) {
            $holidays[] = $stored;
        } else {
            $holidays[$index] = $stored;
        }

        $this->writeHolidays($holidays);
    }

    public function listHolidaysInRange(string $from, string $to): array
    {
        $out = [];
        foreach ($this->readHolidays() as $holiday) {
            $date = (string) ($holiday['date'] ?? '');
            if ($date === '' || $date < $from || $date > $to) {
                continue;
            }
            $out[] = $holiday;
        }

        usort($out, static function (array $a, array $b): int {
            return strcmp((string) ($a['date'] ?? ''), (string) ($b['date'] ?? ''));
        });

        return $out;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function readSources(): array
    {
        $raw = $this->files->read($this->config->calendarHolidaySourcesPath(), [
            'schemaVersion' => 1,
            'sources' => [],
        ]);
        $sources = $raw['sources'] ?? [];

        return is_array($sources) ? array_values(array_filter($sources, 'is_array')) : [];
    }

    /**
     * @param  list<array<string, mixed>>  $sources
     */
    private function writeSources(array $sources): void
    {
        $this->files->ensureDirectory($this->config->calendarDirectory());
        $this->files->write($this->config->calendarHolidaySourcesPath(), [
            'schemaVersion' => 1,
            'sources' => array_values($sources),
        ]);
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function readHolidays(): array
    {
        $raw = $this->files->read($this->config->calendarHolidaysPath(), [
            'schemaVersion' => 1,
            'holidays' => [],
        ]);
        $holidays = $raw['holidays'] ?? [];

        return is_array($holidays) ? array_values(array_filter($holidays, 'is_array')) : [];
    }

    /**
     * @param  list<array<string, mixed>>  $holidays
     */
    private function writeHolidays(array $holidays): void
    {
        $this->files->ensureDirectory($this->config->calendarDirectory());
        $this->files->write($this->config->calendarHolidaysPath(), [
            'schemaVersion' => 1,
            'holidays' => array_values($holidays),
        ]);
    }

    private function newSourceId(string $url): string
    {
        if ($url !== '') {
            return 'source-'.substr(sha1($url), 0, 12);
        }

        return 'source-'.bin2hex(random_bytes(6));
    }

    private function holidayId(string $sourceId, ?string $importedUid, string $date): string
    {
        return 'h-'.substr(sha1($sourceId.'|'.($importedUid ?? '').'|'.$date), 0, 16);
    }
}
