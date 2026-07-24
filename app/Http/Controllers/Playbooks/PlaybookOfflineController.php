<?php

namespace App\Http\Controllers\Playbooks;

use App\Accounts\AccountAuth;
use App\Accounts\AccountsConfig;
use App\Accounts\StoryAclRepository;
use App\Http\Controllers\Controller;
use App\Playbooks\Playbook;
use App\Playbooks\PlaybookOfflineManifestBuilder;
use App\Playbooks\PlaybookRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlaybookOfflineController extends Controller
{
    public function __construct(
        private readonly PlaybookRepository $playbooks,
        private readonly PlaybookOfflineManifestBuilder $manifests,
        private readonly AccountsConfig $accountsConfig,
        private readonly AccountAuth $accountAuth,
        private readonly StoryAclRepository $storyAcl,
    ) {}

    public function show(Request $request): JsonResponse
    {
        $slug = (string) $request->route('slug');
        $playbook = $this->playbooks->find($slug);

        abort_if($playbook === null, 404);
        $this->assertCanAccess($slug);

        return response()->json($this->manifests->forPlaybook($playbook));
    }

    public function index(): JsonResponse
    {
        $visible = array_values(array_filter(
            $this->playbooks->all(),
            fn (Playbook $playbook): bool => $this->canAccess($playbook->slug),
        ));

        return response()->json($this->manifests->forPlaybooks($visible));
    }

    public function series(Request $request): JsonResponse
    {
        $seriesId = (string) $request->route('seriesId');
        $series = $this->playbooks->findSeries($seriesId);

        abort_if($series === null, 404);

        $playbooks = [];
        foreach ($series->parts as $part) {
            if (! $this->canAccess($part->slug)) {
                continue;
            }

            $playbook = $this->playbooks->find($part->slug);
            if ($playbook !== null) {
                $playbooks[] = $playbook;
            }
        }

        abort_if($playbooks === [], 404);

        $payload = $this->manifests->forPlaybooks($playbooks);

        return response()->json([
            'seriesId' => $series->id,
            'title' => $series->titleEn,
            'titleDe' => $series->titleDe,
            'titleEn' => $series->titleEn,
            ...$payload,
        ]);
    }

    private function assertCanAccess(string $slug): void
    {
        abort_unless($this->canAccess($slug), 403);
    }

    private function canAccess(string $slug): bool
    {
        if (! $this->accountsConfig->enabled()) {
            return true;
        }

        return $this->storyAcl->canAccess($this->accountAuth->user(), $slug);
    }
}
