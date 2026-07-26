<?php

namespace App\Governance;

use Illuminate\Support\Facades\Http;
use InvalidArgumentException;

final class GovernanceRadarFeedFetchGuard
{
    public function validateUrl(string $url): void
    {
        $parts = parse_url($url);
        if (! in_array($parts['scheme'] ?? '', ['http', 'https'], true)) {
            throw new InvalidArgumentException('Only http and https feed URLs are allowed.');
        }

        $host = $parts['host'] ?? '';
        if ($host === '') {
            throw new InvalidArgumentException('Invalid feed URL host.');
        }

        if (filter_var($host, FILTER_VALIDATE_IP)) {
            if ($this->isPrivateIp($host)) {
                throw new InvalidArgumentException('Feed URL resolves to a private or reserved IP address.');
            }
        } else {
            $ips = collect((array) gethostbynamel($host))->unique();
            foreach ($ips as $ip) {
                if ($this->isPrivateIp((string) $ip)) {
                    throw new InvalidArgumentException('Feed URL resolves to a private or reserved IP address.');
                }
            }
        }
    }

    public function fetch(string $url): string
    {
        $this->validateUrl($url);

        $timeout = (int) config('governance-radar.ingest.timeout_seconds', 12);
        $maxSize = (int) config('governance-radar.ingest.max_bytes', 1_048_576);

        $response = Http::timeout($timeout)
            ->withHeaders([
                'Accept' => 'application/rss+xml, application/atom+xml, application/xml, text/xml, */*',
                'User-Agent' => 'binom-tools-governance-radar/1.0',
            ])
            ->get($url);

        if (! $response->successful()) {
            throw new InvalidArgumentException('Failed to fetch feed URL: HTTP '.$response->status());
        }

        $body = $response->body();
        if (strlen($body) > $maxSize) {
            throw new InvalidArgumentException('Feed exceeds maximum allowed size.');
        }

        return $body;
    }

    private function isPrivateIp(string $ip): bool
    {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false;
    }
}
