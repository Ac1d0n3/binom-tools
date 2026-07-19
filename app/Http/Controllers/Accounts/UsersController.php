<?php

namespace App\Http\Controllers\Accounts;

use App\Accounts\AccountAuth;
use App\Accounts\TeamRepository;
use App\Accounts\UserRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UsersController extends Controller
{
    public function __construct(
        private readonly AccountAuth $auth,
        private readonly UserRepository $users,
        private readonly TeamRepository $teams,
    ) {}

    public function index(): View
    {
        $actor = $this->auth->user();
        abort_if($actor === null || ! $actor->canManageUsers, 403);

        return view('accounts.users', [
            'users' => array_map(static fn ($u) => $u->toPublicArray(), $this->users->all()),
            'teams' => array_map(static fn ($t) => $t->toArray(), $this->teams->all(true)),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $actor = $this->auth->user();
        abort_if($actor === null || ! $actor->canManageUsers, 403);

        $data = $request->validate([
            'email' => ['required', 'email'],
            'displayName' => ['required', 'string', 'max:120'],
            'password' => ['required', 'string', 'min:8'],
            'teamIds' => ['nullable', 'array'],
            'teamIds.*' => ['string'],
            'canManageUsers' => ['sometimes', 'boolean'],
            'canManageTeams' => ['sometimes', 'boolean'],
            'active' => ['sometimes', 'boolean'],
        ]);

        $this->users->upsert([
            'email' => $data['email'],
            'displayName' => $data['displayName'],
            'passwordHash' => password_hash($data['password'], PASSWORD_DEFAULT),
            'teamIds' => $data['teamIds'] ?? [],
            'canManageUsers' => $request->boolean('canManageUsers'),
            'canManageTeams' => $request->boolean('canManageTeams'),
            'active' => $request->boolean('active', true),
        ]);

        return back()->with('status', 'user-created');
    }

    public function update(Request $request, string $userId): RedirectResponse
    {
        $actor = $this->auth->user();
        abort_if($actor === null || ! $actor->canManageUsers, 403);

        $existing = $this->users->findById($userId);
        abort_if($existing === null, 404);

        $data = $request->validate([
            'email' => ['required', 'email'],
            'displayName' => ['required', 'string', 'max:120'],
            'password' => ['nullable', 'string', 'min:8'],
            'teamIds' => ['nullable', 'array'],
            'teamIds.*' => ['string'],
            'canManageUsers' => ['sometimes', 'boolean'],
            'canManageTeams' => ['sometimes', 'boolean'],
            'active' => ['sometimes', 'boolean'],
        ]);

        $payload = [
            ...$existing->toArray(),
            'email' => $data['email'],
            'displayName' => $data['displayName'],
            'teamIds' => $data['teamIds'] ?? [],
            'canManageUsers' => $request->boolean('canManageUsers'),
            'canManageTeams' => $request->boolean('canManageTeams'),
            'active' => $request->boolean('active', true),
        ];

        if (! empty($data['password'])) {
            $payload['passwordHash'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        unset($payload['password']);
        $this->users->upsert($payload);

        return back()->with('status', 'user-updated');
    }

    public function destroy(string $userId): RedirectResponse
    {
        $actor = $this->auth->user();
        abort_if($actor === null || ! $actor->canManageUsers, 403);
        abort_if($actor->id === $userId, 422);

        $this->users->delete($userId);

        return back()->with('status', 'user-deleted');
    }
}
