<?php

namespace App\Http\Controllers\Playbooks;

use App\Http\Controllers\Controller;
use App\Playbooks\PlaybookRepository;
use App\Playbooks\PlaybookStatsStore;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Cookie;

class PlaybookStatsController extends Controller
{
    public const ENGAGEMENT_COOKIE = 'bn_playbook_engagement';

    public function __construct(
        private readonly PlaybookRepository $playbooks,
        private readonly PlaybookStatsStore $stats,
    ) {}

    public function show(Request $request): JsonResponse
    {
        $slug = $this->resolveSlug($request);
        $stats = $this->stats->get($slug);
        $state = $this->readEngagement($request);

        return response()->json([
            ...$stats,
            'liked' => ! empty($state['l'][$slug]),
        ]);
    }

    public function view(Request $request): JsonResponse
    {
        $slug = $this->resolveSlug($request);
        $state = $this->readEngagement($request);
        $today = gmdate('Y-m-d');

        if (($state['v'][$slug] ?? null) === $today) {
            $stats = $this->stats->get($slug);

            return response()->json([
                ...$stats,
                'liked' => ! empty($state['l'][$slug]),
                'counted' => false,
            ]);
        }

        $stats = $this->stats->incrementView($slug);
        $state['v'][$slug] = $today;

        return response()
            ->json([
                ...$stats,
                'liked' => ! empty($state['l'][$slug]),
                'counted' => true,
            ])
            ->cookie($this->engagementCookie($state));
    }

    public function like(Request $request): JsonResponse
    {
        $slug = $this->resolveSlug($request);
        $state = $this->readEngagement($request);
        $alreadyLiked = ! empty($state['l'][$slug]);

        if ($alreadyLiked) {
            $stats = $this->stats->unlike($slug);
            unset($state['l'][$slug]);

            return response()
                ->json($stats)
                ->cookie($this->engagementCookie($state));
        }

        $stats = $this->stats->like($slug);
        $state['l'][$slug] = 1;

        return response()
            ->json($stats)
            ->cookie($this->engagementCookie($state));
    }

    private function resolveSlug(Request $request): string
    {
        $slug = (string) $request->route('slug');
        abort_if($this->playbooks->find($slug) === null, 404);

        return $slug;
    }

    /**
     * @return array{v: array<string, string>, l: array<string, int>}
     */
    private function readEngagement(Request $request): array
    {
        $raw = (string) $request->cookies->get(self::ENGAGEMENT_COOKIE, '');
        if ($raw === '') {
            return ['v' => [], 'l' => []];
        }

        try {
            $decoded = json_decode(base64_decode($raw, true) ?: '', true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            return ['v' => [], 'l' => []];
        }

        if (! is_array($decoded)) {
            return ['v' => [], 'l' => []];
        }

        $views = is_array($decoded['v'] ?? null) ? $decoded['v'] : [];
        $likes = is_array($decoded['l'] ?? null) ? $decoded['l'] : [];

        return [
            'v' => array_filter($views, static fn ($value) => is_string($value)),
            'l' => array_filter($likes, static fn ($value) => is_int($value) || is_string($value)),
        ];
    }

    /**
     * @param  array{v: array<string, string>, l: array<string, int|string>}  $state
     */
    private function engagementCookie(array $state): Cookie
    {
        $payload = base64_encode(json_encode([
            'v' => $state['v'],
            'l' => $state['l'],
        ], JSON_THROW_ON_ERROR));

        return cookie(self::ENGAGEMENT_COOKIE, $payload, 60 * 24 * 365, null, null, false, false, false, 'Lax');
    }
}
