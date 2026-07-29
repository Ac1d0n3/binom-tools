<?php

namespace App\Http\Controllers\Calendar;

use App\Accounts\AccountAuth;
use App\Calendar\CalendarEventAggregator;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CalendarController extends Controller
{
    public function __construct(
        private readonly CalendarEventAggregator $aggregator,
        private readonly AccountAuth $accountAuth,
    ) {}

    public function index(Request $request): View
    {
        $allowedViews = ['month', 'week', 'day', 'list'];
        $view = (string) $request->query('view', 'month');
        if (! in_array($view, $allowedViews, true)) {
            $view = 'month';
        }

        $anchor = $this->resolveAnchor($request);
        [$from, $to] = $this->rangeForView($view, $anchor);
        $user = accounts_enabled() ? $this->accountAuth->user() : null;
        $weekStart = 'monday';

        $bootstrap = $this->aggregator->buildBootstrap($user, $from, $to, $view, $anchor, [
            'locale' => current_locale(),
            'week_start' => $weekStart,
            'allowed_views' => $allowedViews,
        ]);

        $sidebar = [
            'enabled' => true,
            'sections' => [
                [
                    'type' => 'mini_month',
                    'anchor' => $anchor,
                    'week_start' => $weekStart,
                    'weekdays' => $bootstrap['weekdays'] ?? [],
                ],
                [
                    'type' => 'filters',
                    'plans_login_hint' => ($user === null && accounts_enabled())
                        ? (current_locale() === 'de'
                            ? 'Melde dich an, um Plan-Aufgaben im Kalender zu sehen.'
                            : 'Sign in to see plan tasks in the calendar.')
                        : null,
                ],
                [
                    'type' => 'layers',
                    'holiday_sources' => $bootstrap['holiday_sources'] ?? [],
                    'calendars' => $bootstrap['calendars'] ?? [],
                ],
            ],
        ];

        return view('calendar::index', [
            'view' => $view,
            'allowed_views' => $allowedViews,
            'anchor' => $anchor,
            'bootstrap' => $bootstrap,
            'sidebar' => $sidebar,
        ]);
    }

    private function resolveAnchor(Request $request): Carbon
    {
        if ($request->filled('month') && $request->filled('year')) {
            $year = $request->integer('year');
            $month = max(1, min(12, $request->integer('month')));

            return Carbon::create($year, $month, 1)->startOfDay();
        }

        if ($request->filled('date')) {
            return Carbon::parse((string) $request->query('date'))->startOfDay();
        }

        return now()->startOfDay();
    }

    /**
     * @return array{0: Carbon, 1: Carbon}
     */
    private function rangeForView(string $view, Carbon $anchor): array
    {
        return match ($view) {
            'week' => [
                $anchor->copy()->startOfWeek(Carbon::MONDAY),
                $anchor->copy()->endOfWeek(Carbon::MONDAY),
            ],
            'day' => [$anchor->copy()->startOfDay(), $anchor->copy()->endOfDay()],
            'list' => [$anchor->copy()->startOfMonth(), $anchor->copy()->addMonths(2)->endOfMonth()],
            // Include leading/trailing grid days so holiday bars span muted cells too.
            default => [
                $anchor->copy()->startOfMonth()->startOfWeek(Carbon::MONDAY),
                $anchor->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY),
            ],
        };
    }
}
