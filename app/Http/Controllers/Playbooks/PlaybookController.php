<?php

namespace App\Http\Controllers\Playbooks;

use App\Http\Controllers\Controller;
use App\Playbooks\PlaybookRepository;
use Illuminate\View\View;

class PlaybookController extends Controller
{
    public function __construct(
        private readonly PlaybookRepository $playbooks,
    ) {}

    public function index(): View
    {
        $playbooks = $this->playbooks->allForIndex();

        $tags = collect($playbooks)
            ->flatMap(fn (array $item): array => $item['tags'] ?? [])
            ->unique()
            ->sort()
            ->values()
            ->all();

        return view('playbooks.index', [
            'playbooks' => $playbooks,
            'tags' => $tags,
        ]);
    }

    public function show(string $slug): View
    {
        $playbook = $this->playbooks->find($slug);

        abort_if($playbook === null, 404);

        return view('playbooks.show', [
            'playbook' => $playbook,
            'sidebarPlaybooks' => $this->playbooks->allForIndex(),
        ]);
    }
}
