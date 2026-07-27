<?php

namespace App\Calendar\Contracts;

/**
 * Pluggable holiday source + event storage (file JSON or MySQL).
 */
interface CalendarHolidayStoreInterface
{
    public function isReady(): bool;

    /**
     * @return list<array<string, mixed>>
     */
    public function listActiveSources(): array;

    /**
     * @return list<array<string, mixed>>
     */
    public function listSources(): array;

    /**
     * @return array<string, mixed>|null
     */
    public function findSource(int|string $id): ?array;

    /**
     * Upsert a preset (or custom) source definition. Returns the stored source.
     *
     * @param  array<string, mixed>  $preset
     * @return array<string, mixed>
     */
    public function upsertSource(array $preset): array;

    /**
     * @param  array<string, mixed>  $source
     */
    public function markSyncSuccess(array $source): void;

    /**
     * @param  array<string, mixed>  $source
     */
    public function markSyncFailure(array $source, string $error): void;

    /**
     * @param  array<string, mixed>  $payload
     */
    public function upsertHolidayDay(int|string $sourceId, ?string $importedUid, string $date, array $payload): void;

    /**
     * @return list<array<string, mixed>>
     */
    public function listHolidaysInRange(string $from, string $to): array;
}
