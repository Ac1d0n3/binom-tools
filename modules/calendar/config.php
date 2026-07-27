<?php

return [
    'holidays' => [
        'allowed_import_domains' => array_values(array_filter(array_map(
            'trim',
            explode(',', (string) env('BINOM_TOOLS_CALENDAR_ICAL_DOMAINS', 'feiertage-deutschland.de')),
        ))),
        'import_timeout_seconds' => (int) env('BINOM_TOOLS_CALENDAR_ICAL_TIMEOUT', 15),
        'import_max_file_size' => (int) env('BINOM_TOOLS_CALENDAR_ICAL_MAX_SIZE', 1048576),
    ],
    'stories' => [
        'calendar_id' => 1,
        'color' => '#3b82f6',
    ],
];
