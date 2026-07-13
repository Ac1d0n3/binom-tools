<?php

namespace App\Http\Controllers\Playbooks;

use App\Http\Controllers\Controller;
use App\Playbooks\PlaybookRepository;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlaybookController extends Controller
{
    public function __construct(
        private readonly PlaybookRepository $playbooks,
    ) {}

    public function index(): View
    {
        $playbooks = $this->playbooks->allForIndex();

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
        ]);
    }

    public function show(Request $request): View
    {
        $slug = (string) $request->route('slug');

        $playbook = $this->playbooks->find($slug);

        abort_if($playbook === null, 404);

        return view('playbooks.show', [
            'playbook' => $playbook,
        ]);
    }
}
