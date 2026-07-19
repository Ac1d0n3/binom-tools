<?php

namespace App\Http\Controllers\Playbooks;

use App\Accounts\AccountAuth;
use App\Accounts\AccountsConfig;
use App\Accounts\ReadStateStore;
use App\Accounts\StoryAclRepository;
use App\Http\Controllers\Controller;
use App\Playbooks\PlaybookRepository;
use App\Playbooks\PlaybookStatsStore;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlaybookController extends Controller
{
    public function __construct(
        private readonly PlaybookRepository $playbooks,
        private readonly PlaybookStatsStore $stats,
        private readonly AccountsConfig $accountsConfig,
        private readonly AccountAuth $accountAuth,
        private readonly StoryAclRepository $storyAcl,
        private readonly ReadStateStore $readState,
    ) {}

    public function index(): View
    {
        $playbooks = $this->stats->attachToItems($this->filterVisible($this->playbooks->allForIndex()));

        $tagCounts = collect($playbooks)
            ->flatMap(fn (array $item): array => $item['tags'] ?? [])
            ->countBy()
            ->map(fn (int $count, string $name): array => ['name' => $name, 'count' => $count])
            ->sortBy([
                ['count', 'desc'],
                ['name', 'asc'],
            ])
            ->values()
            ->all();

        $categoryCounts = collect($playbooks)
            ->map(function (array $item): ?array {
                $en = $item['locales']['en']['category'] ?? null;
                $de = $item['locales']['de']['category'] ?? null;
                $key = playbook_category_key(
                    is_string($en) ? $en : null,
                    is_string($de) ? $de : null,
                );

                if ($key === null) {
                    return null;
                }

                return [
                    'key' => $key,
                    'labelEn' => is_string($en) && $en !== '' ? $en : (is_string($de) ? $de : $key),
                    'labelDe' => is_string($de) && $de !== '' ? $de : (is_string($en) ? $en : $key),
                ];
            })
            ->filter()
            ->groupBy('key')
            ->map(function ($items, string $key): array {
                $first = $items->first();

                return [
                    'key' => $key,
                    'labelEn' => $first['labelEn'],
                    'labelDe' => $first['labelDe'],
                    'count' => $items->count(),
                ];
            })
            ->sortBy([
                ['count', 'desc'],
                ['labelEn', 'asc'],
            ])
            ->values()
            ->all();

        return view('playbooks.index', [
            'playbooks' => $playbooks,
            'tagCounts' => $tagCounts,
            'categoryCounts' => $categoryCounts,
            'seriesList' => $this->playbooks->allSeries(),
            'serverReadSlugs' => $this->serverReadSlugs(),
        ]);
    }

    public function show(Request $request): View
    {
        $slug = (string) $request->route('slug');

        $playbook = $this->playbooks->find($slug);

        abort_if($playbook === null, 404);

        $user = $this->accountAuth->user();
        if ($this->accountsConfig->enabled()) {
            abort_unless($this->storyAcl->canAccess($user, $slug), 403);
            if ($user !== null) {
                $this->readState->markRead($user->id, $slug);
            }
        }

        return view('playbooks.show', [
            'playbook' => $playbook,
            'engagementStats' => $this->stats->get($playbook->slug),
            'accountsReadUrl' => $this->accountsConfig->enabled() && $user !== null
                ? locale_route('accounts.playbooks.read', ['slug' => $slug])
                : null,
        ]);
    }

    /**
     * @param  list<array<string, mixed>>  $items
     * @return list<array<string, mixed>>
     */
    private function filterVisible(array $items): array
    {
        if (! $this->accountsConfig->enabled()) {
            return $items;
        }

        $user = $this->accountAuth->user();

        return array_values(array_filter(
            $items,
            fn (array $item): bool => $this->storyAcl->canAccess($user, (string) ($item['slug'] ?? '')),
        ));
    }

    /**
     * @return list<string>
     */
    private function serverReadSlugs(): array
    {
        if (! $this->accountsConfig->enabled()) {
            return [];
        }
        $user = $this->accountAuth->user();
        if ($user === null) {
            return [];
        }

        return array_keys($this->readState->forUser($user->id));
    }
}
