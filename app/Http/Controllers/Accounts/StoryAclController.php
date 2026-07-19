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
        $this->assertCanManage();

        return view('accounts.story-acl', [
            'stories' => $this->storyRows(),
        ]);
    }

    public function edit(string $slug): View
    {
        $this->assertCanManage();
        $story = $this->findStory($slug);
        abort_if($story === null, 404);

        return view('accounts.story-acl-form', [
            'story' => $story,
            'users' => array_map(static fn ($u) => $u->toPublicArray(), $this->users->all()),
            'teams' => array_map(static fn ($t) => $t->toArray(), $this->teams->all(true)),
        ]);
    }

    public function update(Request $request, string $slug): RedirectResponse
    {
        $this->assertCanManage();
        abort_if($this->findStory($slug) === null, 404);

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

        return redirect()
            ->to(locale_route('accounts.story-acl'))
            ->with('status', 'acl-updated');
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

    private function assertCanManage(): void
    {
        $actor = $this->auth->user();
        abort_if($actor === null || ! $actor->canManageUsers, 403);
    }

    /**
     * @return list<array{slug: string, title: string, acl: array{visibility: string, userIds: list<string>, teamIds: list<string>}}>
     */
    private function storyRows(): array
    {
        $locale = current_locale();
        $stories = [];

        foreach ($this->playbooks->allForIndex() as $item) {
            $slug = (string) ($item['slug'] ?? '');
            if ($slug === '') {
                continue;
            }

            $stories[] = [
                'slug' => $slug,
                'title' => $this->titleFor($item, $locale),
                'acl' => $this->acl->forSlug($slug),
            ];
        }

        return $stories;
    }

    /**
     * @return array{slug: string, title: string, acl: array{visibility: string, userIds: list<string>, teamIds: list<string>}}|null
     */
    private function findStory(string $slug): ?array
    {
        $locale = current_locale();

        foreach ($this->playbooks->allForIndex() as $item) {
            if ((string) ($item['slug'] ?? '') !== $slug) {
                continue;
            }

            return [
                'slug' => $slug,
                'title' => $this->titleFor($item, $locale),
                'acl' => $this->acl->forSlug($slug),
            ];
        }

        return null;
    }

    /**
     * @param  array<string, mixed>  $item
     */
    private function titleFor(array $item, string $locale): string
    {
        $slug = (string) ($item['slug'] ?? '');
        $locales = is_array($item['locales'] ?? null) ? $item['locales'] : [];

        return (string) (
            $locales[$locale]['title']
            ?? $locales['en']['title']
            ?? $locales['de']['title']
            ?? $slug
        );
    }
}
