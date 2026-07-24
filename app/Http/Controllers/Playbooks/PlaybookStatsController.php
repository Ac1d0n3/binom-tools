<?php

namespace App\Http\Controllers\Playbooks;

use App\Http\Controllers\Controller;
use App\Playbooks\PlaybookEngagementCookie;
use App\Playbooks\PlaybookRepository;
use App\Playbooks\PlaybookStatsStore;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Cookie;

class PlaybookStatsController extends Controller
{
    /** @deprecated Use PlaybookEngagementCookie::NAME */
    public const ENGAGEMENT_COOKIE = PlaybookEngagementCookie::NAME;

    public function __construct(
        private readonly PlaybookRepository $playbooks,
        private readonly PlaybookStatsStore $stats,
    ) {}

    public function show(Request $request): JsonResponse
    {
        $slug = $this->resolveSlug($request);
        $stats = $this->stats->get($slug);
        $state = PlaybookEngagementCookie::read($request);

        return response()->json([
            ...$stats,
            'liked' => ! empty($state['l'][$slug]),
        ]);
    }

    public function view(Request $request): JsonResponse
    {
        $slug = $this->resolveSlug($request);
        $state = PlaybookEngagementCookie::read($request);
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
        $state = PlaybookEngagementCookie::read($request);
        $alreadyLiked = ! empty($state['l'][$slug]);

        if ($alreadyLiked) {
            $stats = $this->stats->unlike($slug);
            unset($state['l'][$slug]);

            return response()
                ->json([
                    ...$stats,
                    'liked' => false,
                ])
                ->cookie($this->engagementCookie($state));
        }

        $stats = $this->stats->like($slug);
        $state['l'][$slug] = 1;

        return response()
            ->json([
                ...$stats,
                'liked' => true,
            ])
            ->cookie($this->engagementCookie($state));
    }

    private function resolveSlug(Request $request): string
    {
        $slug = (string) $request->route('slug');
        abort_if($this->playbooks->find($slug) === null, 404);

        return $slug;
    }

    /**
     * @param  array{v: array<string, string>, l: array<string, int|string>}  $state
     */
    private function engagementCookie(array $state): Cookie
    {
        return cookie(
            PlaybookEngagementCookie::NAME,
            PlaybookEngagementCookie::encode($state),
            60 * 24 * 365,
            null,
            null,
            false,
            false,
            false,
            'Lax',
        );
    }
}
