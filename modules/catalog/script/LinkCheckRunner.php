<?php

namespace App\Catalog;

use Illuminate\Support\Facades\Http;

/**
 * HTTP health check for inventoried URLs (HEAD with GET fallback).
 */
final class LinkCheckRunner
{
    public function __construct(
        private readonly int $timeoutSeconds = 8,
        private readonly int $delayMs = 50,
    ) {}

    /**
     * @param  list<array{url: string, source: string}>  $inventory
     * @param  callable(int $done, int $total, array $row): void|null  $onProgress
     * @return array{checkedAt: string, results: list<array<string, mixed>>, summary: array<string, int>, total: int}
     */
    public function run(array $inventory, ?callable $onProgress = null): array
    {
        $byUrl = [];
        foreach ($inventory as $hit) {
            $url = $hit['url'];
            $byUrl[$url] ??= ['url' => $url, 'sources' => []];
            $byUrl[$url]['sources'][] = $hit['source'];
        }

        $urls = array_values($byUrl);
        $total = count($urls);
        $results = [];
        $summary = ['ok' => 0, 'redirect' => 0, 'broken' => 0, 'error' => 0];

        foreach ($urls as $index => $entry) {
            $row = $this->checkUrl($entry['url']);
            $row['sources'] = array_values(array_unique($entry['sources']));
            $results[] = $row;
            $bucket = $row['bucket'] ?? 'error';
            $summary[$bucket] = ($summary[$bucket] ?? 0) + 1;
            if ($onProgress) {
                $onProgress($index + 1, $total, $row);
            }
            if ($this->delayMs > 0) {
                usleep($this->delayMs * 1000);
            }
        }

        return [
            'checkedAt' => now()->toIso8601String(),
            'results' => $results,
            'summary' => $summary,
            'total' => $total,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function checkUrl(string $url): array
    {
        $started = microtime(true);
        try {
            $response = Http::withHeaders([
                'User-Agent' => 'binom-tools-link-check/1.0 (+https://github.com/Ac1d0n3/binom-tools)',
                'Accept' => '*/*',
            ])
                ->timeout($this->timeoutSeconds)
                ->withOptions(['allow_redirects' => false])
                ->head($url);

            $status = $response->status();
            if ($status === 405 || $status === 501 || $status === 403) {
                $response = Http::withHeaders([
                    'User-Agent' => 'binom-tools-link-check/1.0 (+https://github.com/Ac1d0n3/binom-tools)',
                    'Accept' => 'text/html,application/xhtml+xml;q=0.9,*/*;q=0.8',
                ])
                    ->timeout($this->timeoutSeconds)
                    ->withOptions(['allow_redirects' => false])
                    ->get($url);
                $status = $response->status();
            }

            $bucket = $this->bucketForStatus($status);
            $location = $response->header('Location');

            return [
                'url' => $url,
                'status' => $status,
                'bucket' => $bucket,
                'redirectTo' => is_string($location) && $location !== '' ? $location : null,
                'ms' => (int) round((microtime(true) - $started) * 1000),
                'error' => null,
            ];
        } catch (\Throwable $e) {
            return [
                'url' => $url,
                'status' => null,
                'bucket' => 'error',
                'redirectTo' => null,
                'ms' => (int) round((microtime(true) - $started) * 1000),
                'error' => $e->getMessage(),
            ];
        }
    }

    private function bucketForStatus(int $status): string
    {
        if ($status >= 200 && $status < 300) {
            return 'ok';
        }
        if ($status >= 300 && $status < 400) {
            return 'redirect';
        }
        if ($status >= 400) {
            return 'broken';
        }

        return 'error';
    }
}
