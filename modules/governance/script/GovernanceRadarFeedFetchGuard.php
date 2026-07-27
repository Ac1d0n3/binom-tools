<?php

namespace App\Governance;

use Illuminate\Http\Client\Response;
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

    public function fetch(string $url, ?int $timeoutSeconds = null): string
    {
        $this->validateUrl($url);

        $timeout = $timeoutSeconds ?? (int) config('governance-radar.ingest.timeout_seconds', 12);
        $maxSize = (int) config('governance-radar.ingest.max_bytes', 2_097_152);

        $response = Http::timeout($timeout)
            ->withOptions(['stream' => true])
            ->withHeaders([
                'Accept' => 'application/rss+xml, application/atom+xml, application/xml, text/xml, */*',
                'User-Agent' => 'binom-tools-governance-radar/1.0',
            ])
            ->get($url);

        if (! $response->successful()) {
            $status = $response->status();
            if (in_array($status, [401, 403, 404, 410], true)) {
                throw new GovernanceRadarFeedUnavailableException(
                    $status,
                    'Feed URL is unavailable: HTTP '.$status,
                );
            }

            throw new InvalidArgumentException('Failed to fetch feed URL: HTTP '.$status);
        }

        return $this->readBodyCapped($response, $maxSize);
    }

    private function readBodyCapped(Response $response, int $maxSize): string
    {
        $stream = $response->toPsrResponse()->getBody();
        $body = '';
        $truncated = false;

        while (! $stream->eof()) {
            $chunk = $stream->read(8192);
            if ($chunk === '') {
                break;
            }
            $body .= $chunk;
            if (strlen($body) > $maxSize) {
                $truncated = true;
                break;
            }
        }

        if (! $truncated) {
            return $body;
        }

        return $this->salvageTruncatedFeed(substr($body, 0, $maxSize));
    }

    /**
     * Large vendor feeds often ship years of history. Radar only needs recent items,
     * so keep the prefix (newest-first) up to the last complete item/entry and close XML.
     */
    private function salvageTruncatedFeed(string $xml): string
    {
        $endItem = strripos($xml, '</item>');
        $endEntry = strripos($xml, '</entry>');

        if ($endItem === false && $endEntry === false) {
            throw new InvalidArgumentException('Feed exceeds maximum allowed size.');
        }

        $useAtom = $endEntry !== false && ($endItem === false || $endEntry > $endItem);
        if ($useAtom) {
            $xml = substr($xml, 0, $endEntry + strlen('</entry>'));
            if (! preg_match('/<\/feed\s*>/i', $xml)) {
                $xml .= '</feed>';
            }

            return $xml;
        }

        $xml = substr($xml, 0, $endItem + strlen('</item>'));
        if (! preg_match('/<\/channel\s*>/i', $xml)) {
            $xml .= '</channel>';
        }
        if (! preg_match('/<\/rss\s*>/i', $xml)) {
            $xml .= '</rss>';
        }

        return $xml;
    }

    private function isPrivateIp(string $ip): bool
    {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false;
    }
}
