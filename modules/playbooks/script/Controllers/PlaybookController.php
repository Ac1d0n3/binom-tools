<?php

namespace App\Http\Controllers\Playbooks;

use App\Accounts\AccountAuth;
use App\Accounts\AccountsConfig;
use App\Accounts\Contracts\ReadStateStoreInterface;
use App\Accounts\Contracts\StoryAclRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Playbooks\PlaybookProducts;
use App\Playbooks\PlaybookRepository;
use App\Playbooks\Contracts\PlaybookStatsStoreInterface;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlaybookController extends Controller
{
    public function __construct(
        private readonly PlaybookRepository $playbooks,
        private readonly PlaybookStatsStoreInterface $stats,
        private readonly AccountsConfig $accountsConfig,
        private readonly AccountAuth $accountAuth,
        private readonly StoryAclRepositoryInterface $storyAcl,
        private readonly ReadStateStoreInterface $readState,
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

        $seriesList = $this->playbooks->allSeries();

        $productCounts = collect($playbooks)
            ->flatMap(fn (array $item): array => $item['products'] ?? [])
            ->merge(
                collect($seriesList)->flatMap(fn ($series): array => $series->products ?? [])
            )
            ->filter(fn (mixed $id): bool => is_string($id) && $id !== '')
            ->countBy()
            ->map(fn (int $count, string $id): array => [
                'id' => $id,
                'label' => PlaybookProducts::label($id),
                'count' => $count,
            ])
            ->sortBy(fn (array $product): int => array_search($product['id'], PlaybookProducts::ORDERED_IDS, true) !== false
                ? (int) array_search($product['id'], PlaybookProducts::ORDERED_IDS, true)
                : PHP_INT_MAX)
            ->values()
            ->all();

        $availableProducts = array_values(array_map(
            static fn (array $product): string => $product['id'],
            $productCounts,
        ));

        return view('playbooks::index', [
            'playbooks' => $playbooks,
            'tagCounts' => $tagCounts,
            'categoryCounts' => $categoryCounts,
            'productCounts' => $productCounts,
            'availableProducts' => $availableProducts,
            'seriesList' => $seriesList,
            'serverReadSlugs' => $this->serverReadSlugs(),
        ]);
    }

    public function series(Request $request): View
    {
        // Resolve by route name — localized URLs also bind {locale}, which would
        // otherwise fill a positional string $seriesId with the locale code.
        $seriesId = (string) $request->route('seriesId');
        $series = $this->playbooks->findSeries($seriesId);
        abort_if($series === null, 404);

        if ($this->accountsConfig->enabled()) {
            $user = $this->accountAuth->user();
            $visibleParts = array_values(array_filter(
                $series->parts,
                fn ($part): bool => $this->storyAcl->canAccess($user, $part->slug),
            ));
            abort_if($visibleParts === [], 403);
        }

        return view('playbooks::series', [
            'series' => $series,
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

        return view('playbooks::show', [
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
