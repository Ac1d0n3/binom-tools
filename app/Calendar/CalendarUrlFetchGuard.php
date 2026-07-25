<?php

namespace App\Calendar;

use Illuminate\Support\Facades\Http;
use InvalidArgumentException;

final class CalendarUrlFetchGuard
{
    public function validateUrl(string $url): void
    {
        $parts = parse_url($url);
        if (! in_array($parts['scheme'] ?? '', ['http', 'https'], true)) {
            throw new InvalidArgumentException('Only http and https URLs are allowed.');
        }

        $host = $parts['host'] ?? '';
        if ($host === '') {
            throw new InvalidArgumentException('Invalid URL host.');
        }

        if (filter_var($host, FILTER_VALIDATE_IP)) {
            if ($this->isPrivateIp($host)) {
                throw new InvalidArgumentException('URL resolves to a private or reserved IP address.');
            }
        } else {
            $ips = collect((array) gethostbynamel($host))->unique();
            foreach ($ips as $ip) {
                if ($this->isPrivateIp((string) $ip)) {
                    throw new InvalidArgumentException('URL resolves to a private or reserved IP address.');
                }
            }
        }

        $allowed = config('calendar.holidays.allowed_import_domains', []);
        if (is_array($allowed) && $allowed !== []) {
            $allowedHost = collect($allowed)->contains(
                fn (string $domain): bool => $host === $domain || str_ends_with($host, '.'.$domain),
            );
            if (! $allowedHost) {
                throw new InvalidArgumentException('URL domain is not in the allowed import list.');
            }
        }
    }

    public function fetch(string $url): string
    {
        $this->validateUrl($url);

        $timeout = (int) config('calendar.holidays.import_timeout_seconds', 15);
        $maxSize = (int) config('calendar.holidays.import_max_file_size', 1048576);

        $response = Http::timeout($timeout)
            ->withHeaders(['Accept' => 'text/calendar, application/octet-stream, */*'])
            ->get($url);

        if (! $response->successful()) {
            throw new InvalidArgumentException('Failed to fetch iCal URL: HTTP '.$response->status());
        }

        $body = $response->body();
        if (strlen($body) > $maxSize) {
            throw new InvalidArgumentException('iCal file exceeds maximum allowed size.');
        }

        return $body;
    }

    private function isPrivateIp(string $ip): bool
    {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false;
    }
}
