<?php

namespace App\Http\Controllers\Accounts;

use App\Accounts\AccountAuth;
use App\Accounts\TeamRepository;
use App\Accounts\UserRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeamsController extends Controller
{
    public function __construct(
        private readonly AccountAuth $auth,
        private readonly TeamRepository $teams,
        private readonly UserRepository $users,
    ) {}

    public function index(): View
    {
        $actor = $this->auth->user();
        abort_if($actor === null || ! $actor->canManageTeams, 403);

        return view('accounts.teams', [
            'teams' => array_map(static fn ($t) => $t->toArray(), $this->teams->all(true)),
            'users' => array_map(static fn ($u) => $u->toPublicArray(), $this->users->all()),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $actor = $this->auth->user();
        abort_if($actor === null || ! $actor->canManageTeams, 403);

        $data = $request->validate([
            'name_de' => ['required', 'string', 'max:120'],
            'name_en' => ['nullable', 'string', 'max:120'],
            'description_de' => ['nullable', 'string', 'max:2000'],
            'description_en' => ['nullable', 'string', 'max:2000'],
            'memberIds' => ['nullable', 'array'],
            'memberIds.*' => ['string'],
        ]);

        $this->teams->upsert([
            'name' => [
                'de' => $data['name_de'],
                'en' => $data['name_en'] ?: $data['name_de'],
            ],
            'description' => [
                'de' => $data['description_de'] ?? '',
                'en' => $data['description_en'] ?? ($data['description_de'] ?? ''),
            ],
            'memberIds' => $data['memberIds'] ?? [],
        ]);

        return back()->with('status', 'team-created');
    }

    public function update(Request $request, string $teamId): RedirectResponse
    {
        $actor = $this->auth->user();
        abort_if($actor === null || ! $actor->canManageTeams, 403);
        abort_if($this->teams->findById($teamId) === null, 404);

        $data = $request->validate([
            'name_de' => ['required', 'string', 'max:120'],
            'name_en' => ['nullable', 'string', 'max:120'],
            'description_de' => ['nullable', 'string', 'max:2000'],
            'description_en' => ['nullable', 'string', 'max:2000'],
            'memberIds' => ['nullable', 'array'],
            'memberIds.*' => ['string'],
            'archived' => ['sometimes', 'boolean'],
        ]);

        $this->teams->upsert([
            'id' => $teamId,
            'name' => [
                'de' => $data['name_de'],
                'en' => $data['name_en'] ?: $data['name_de'],
            ],
            'description' => [
                'de' => $data['description_de'] ?? '',
                'en' => $data['description_en'] ?? ($data['description_de'] ?? ''),
            ],
            'memberIds' => $data['memberIds'] ?? [],
            'archived' => $request->boolean('archived'),
        ]);

        return back()->with('status', 'team-updated');
    }

    public function destroy(string $teamId): RedirectResponse
    {
        $actor = $this->auth->user();
        abort_if($actor === null || ! $actor->canManageTeams, 403);
        $this->teams->delete($teamId);

        return back()->with('status', 'team-deleted');
    }
}
