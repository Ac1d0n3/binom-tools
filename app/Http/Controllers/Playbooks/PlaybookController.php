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

        return view('playbooks.index', [
            'playbooks' => $playbooks,
            'tagCounts' => $tagCounts,
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
            'sidebarPlaybooks' => $this->playbooks->allForIndex(),
        ]);
    }
}
