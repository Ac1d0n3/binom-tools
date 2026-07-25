<?php

namespace App\Support;

final class ReadingTime
{
    /**
     * Format minutes as "12 min", "1 Std 5 min" / "1 h 5 min", or whole hours without minutes.
     */
    public static function format(int $minutes, string $locale = 'en'): string
    {
        $minutes = max(0, $minutes);

        if ($minutes < 60) {
            return $minutes.' min';
        }

        $hours = intdiv($minutes, 60);
        $remainder = $minutes % 60;
        $hourUnit = $locale === 'de' ? 'Std' : 'h';

        if ($remainder === 0) {
            return $hours.' '.$hourUnit;
        }

        return $hours.' '.$hourUnit.' '.$remainder.' min';
    }
}
