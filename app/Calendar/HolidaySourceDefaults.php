<?php

namespace App\Calendar;

final class HolidaySourceDefaults
{
    public const IMPORT_CATEGORY_HOLIDAYS = 'holidays';

    public const IMPORT_CATEGORY_CUSTOM = 'custom';

    /**
     * @return list<array{
     *     name: string,
     *     type: string,
     *     country: string,
     *     region: string,
     *     url: string,
     *     is_active: bool,
     *     sync_interval_hours: int,
     *     settings?: array{color_id?: string, import_category?: string, color?: string}
     * }>
     */
    public static function all(): array
    {
        return [
            [
                'name' => 'Feiertage Deutschland (NRW)',
                'type' => 'ical',
                'country' => 'DE',
                'region' => 'DE-NW',
                'url' => 'https://www.feiertage-deutschland.de/kalender-download/ics/feiertage-deutschland.ics',
                'is_active' => true,
                'sync_interval_hours' => 168,
                'settings' => [
                    'color' => '#ef4444',
                    'import_category' => self::IMPORT_CATEGORY_HOLIDAYS,
                ],
            ],
            [
                'name' => 'Schulferien NRW',
                'type' => 'ical',
                'country' => 'DE',
                'region' => 'DE-NW',
                'url' => 'https://www.feiertage-deutschland.de/kalender-download/ics/schulferien-nordrhein-westfalen.ics',
                'is_active' => true,
                'sync_interval_hours' => 168,
                'settings' => [
                    'color' => '#f59e0b',
                    'import_category' => self::IMPORT_CATEGORY_HOLIDAYS,
                ],
            ],
        ];
    }

    /**
     * @return list<string>
     */
    public static function presetUrls(): array
    {
        return array_column(self::all(), 'url');
    }

    public static function isPresetUrl(?string $url): bool
    {
        return $url !== null && $url !== '' && in_array($url, self::presetUrls(), true);
    }

    /**
     * @param  array<string, mixed>|object  $source
     */
    public static function importCategoryForSource(array|object $source): string
    {
        $settings = is_array($source)
            ? ($source['settings'] ?? [])
            : ($source->settings ?? []);
        $stored = is_array($settings) ? ($settings['import_category'] ?? null) : null;

        if (in_array($stored, [self::IMPORT_CATEGORY_HOLIDAYS, self::IMPORT_CATEGORY_CUSTOM], true)) {
            return $stored;
        }

        $url = is_array($source) ? ($source['url'] ?? null) : ($source->url ?? null);
        if (self::isPresetUrl(is_string($url) ? $url : null)) {
            return self::IMPORT_CATEGORY_HOLIDAYS;
        }

        if (is_string($url) && $url !== '') {
            return self::IMPORT_CATEGORY_CUSTOM;
        }

        return self::IMPORT_CATEGORY_HOLIDAYS;
    }
}
