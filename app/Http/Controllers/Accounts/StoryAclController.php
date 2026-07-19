<?php

namespace App\Http\Controllers\Accounts;

use App\Accounts\AccountAuth;
use App\Accounts\ReadStateStore;
use App\Accounts\StoryAclRepository;
use App\Accounts\TeamRepository;
use App\Accounts\UserRepository;
use App\Http\Controllers\Controller;
use App\Playbooks\PlaybookRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StoryAclController extends Controller
{
    public function __construct(
        private readonly AccountAuth $auth,
        private readonly StoryAclRepository $acl,
        private readonly PlaybookRepository $playbooks,
        private readonly UserRepository $users,
        private readonly TeamRepository $teams,
        private readonly ReadStateStore $readState,
    ) {}

    public function index(): View
    {
        $actor = $this->auth->user();
        abort_if($actor === null || ! $actor->canManageUsers, 403);

        $stories = [];
        foreach ($this->playbooks->allForIndex() as $item) {
            $slug = (string) ($item['slug'] ?? '');
            $stories[] = [
                'slug' => $slug,
                'title' => $item['locales']['en']['title'] ?? $slug,
                'acl' => $this->acl->forSlug($slug),
            ];
        }

        return view('accounts.story-acl', [
            'stories' => $stories,
            'users' => array_map(static fn ($u) => $u->toPublicArray(), $this->users->all()),
            'teams' => array_map(static fn ($t) => $t->toArray(), $this->teams->all(true)),
        ]);
    }

    public function update(Request $request, string $slug): RedirectResponse
    {
        $actor = $this->auth->user();
        abort_if($actor === null || ! $actor->canManageUsers, 403);

        $data = $request->validate([
            'visibility' => ['required', 'in:public,restricted'],
            'userIds' => ['nullable', 'array'],
            'userIds.*' => ['string'],
            'teamIds' => ['nullable', 'array'],
            'teamIds.*' => ['string'],
        ]);

        $this->acl->set($slug, [
            'visibility' => $data['visibility'],
            'userIds' => $data['userIds'] ?? [],
            'teamIds' => $data['teamIds'] ?? [],
        ]);

        return back()->with('status', 'acl-updated');
    }

    public function markRead(Request $request, string $slug): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        $user = $this->auth->user();
        abort_if($user === null, 401);
        abort_unless($this->acl->canAccess($user, $slug), 403);

        $this->readState->markRead($user->id, $slug);

        if ($request->expectsJson()) {
            return response()->json(['ok' => true]);
        }

        return back();
    }
}
