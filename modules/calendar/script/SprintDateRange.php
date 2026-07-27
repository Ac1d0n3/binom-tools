<?php

namespace App\Calendar;

use Carbon\Carbon;

final class SprintDateRange
{
    /**
     * Planned calendar range for sprint number N (1-based) from plan start.
     * Matches modules/sprint-planner/js/progress.js sprintDateRange().
     *
     * @return array{start: Carbon, end: Carbon}|null
     */
    public static function compute(?string $startedAt, int $sprintNumber, string $unit = 'week'): ?array
    {
        if ($startedAt === null || $startedAt === '' || $sprintNumber < 1) {
            return null;
        }

        $start = self::parseDateOnly($startedAt);
        if ($start === null) {
            return null;
        }

        $length = $unit === 'week' ? 7 : 7;
        $rangeStart = $start->copy()->addDays(($sprintNumber - 1) * $length);
        $rangeEnd = $rangeStart->copy()->addDays($length - 1);

        return [
            'start' => $rangeStart,
            'end' => $rangeEnd,
        ];
    }

    private static function parseDateOnly(string $value): ?Carbon
    {
        $raw = trim($value);
        if ($raw === '') {
            return null;
        }

        if (preg_match('/^(\d{4}-\d{2}-\d{2})/', $raw, $matches) === 1) {
            try {
                return Carbon::createFromFormat('Y-m-d', $matches[1])->startOfDay();
            } catch (\Throwable) {
                return null;
            }
        }

        try {
            return Carbon::parse($raw)->startOfDay();
        } catch (\Throwable) {
            return null;
        }
    }
}
