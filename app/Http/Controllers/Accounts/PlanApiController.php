<?php

namespace App\Http\Controllers\Accounts;

use App\Accounts\AccountAuth;
use App\Accounts\PlanStore;
use App\Accounts\StoryAclRepository;
use App\Accounts\TeamRepository;
use App\Accounts\UserRepository;
use App\Http\Controllers\Controller;
use App\Playbooks\PlaybookRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlanApiController extends Controller
{
    public function __construct(
        private readonly AccountAuth $auth,
        private readonly PlanStore $plans,
        private readonly UserRepository $users,
        private readonly TeamRepository $teams,
        private readonly StoryAclRepository $storyAcl,
        private readonly PlaybookRepository $playbooks,
    ) {}

    public function index(): JsonResponse
    {
        $user = $this->auth->user();
        abort_if($user === null, 401);

        return response()->json([
            'plans' => $this->plans->listVisibleTo($user),
            'users' => array_map(static fn ($u) => $u->toPublicArray(), $this->users->all()),
            'teams' => array_map(static fn ($t) => $t->toArray(), $this->teams->all()),
        ]);
    }

    public function show(string $planId): JsonResponse
    {
        $user = $this->auth->user();
        abort_if($user === null, 401);
        $plan = $this->plans->find($planId);
        abort_if($plan === null, 404);
        abort_unless($this->plans->canAccess($user, $plan), 403);

        return response()->json(['plan' => $plan]);
    }

    public function store(Request $request): JsonResponse
    {
        $user = $this->auth->user();
        abort_if($user === null, 401);

        $data = $request->validate([
            'plan' => ['required', 'array'],
            'plan.id' => ['required', 'string'],
        ]);

        $plan = $this->plans->save($data['plan'], $user);

        return response()->json(['plan' => $plan]);
    }

    public function destroy(string $planId): JsonResponse
    {
        $user = $this->auth->user();
        abort_if($user === null, 401);
        $this->plans->delete($planId, $user);

        return response()->json(['ok' => true]);
    }

    public function storyMeta(): JsonResponse
    {
        $user = $this->auth->user();
        abort_if($user === null, 401);

        $items = [];
        foreach ($this->playbooks->allForIndex() as $item) {
            $slug = (string) ($item['slug'] ?? '');
            if ($slug === '' || ! $this->storyAcl->canAccess($user, $slug)) {
                continue;
            }
            $items[] = [
                'slug' => $slug,
                'title' => $item['locales']['en']['title'] ?? $slug,
                'titleDe' => $item['locales']['de']['title'] ?? null,
                'titleEn' => $item['locales']['en']['title'] ?? null,
            ];
        }

        return response()->json(['stories' => $items]);
    }
}
