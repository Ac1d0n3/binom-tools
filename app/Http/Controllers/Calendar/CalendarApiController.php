<?php

namespace App\Http\Controllers\Calendar;

use App\Accounts\AccountAuth;
use App\Calendar\CalendarEventAggregator;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CalendarApiController extends Controller
{
    public function __construct(
        private readonly CalendarEventAggregator $aggregator,
        private readonly AccountAuth $accountAuth,
    ) {}

    public function events(Request $request): JsonResponse
    {
        $from = Carbon::parse((string) $request->query('from', now()->startOfMonth()->toDateString()))->startOfDay();
        $to = Carbon::parse((string) $request->query('to', now()->endOfMonth()->toDateString()))->endOfDay();
        $user = accounts_enabled() ? $this->accountAuth->user() : null;

        $entries = $this->aggregator->storyEvents($user, $from, $to);
        if ($user !== null && accounts_enabled()) {
            $entries = array_merge($entries, $this->aggregator->planEvents($user, $from, $to));
        }

        return response()->json([
            'data' => $entries,
            'meta' => [
                'from' => $from->toDateString(),
                'to' => $to->toDateString(),
                'view' => $request->query('view'),
            ],
        ]);
    }

    public function holidays(Request $request): JsonResponse
    {
        $from = Carbon::parse((string) $request->query('from', now()->startOfMonth()->toDateString()))->startOfDay();
        $to = Carbon::parse((string) $request->query('to', now()->endOfMonth()->toDateString()))->endOfDay();

        return response()->json([
            'data' => $this->aggregator->holidaysForRange($from, $to),
            'meta' => [
                'from' => $from->toDateString(),
                'to' => $to->toDateString(),
            ],
        ]);
    }
}
