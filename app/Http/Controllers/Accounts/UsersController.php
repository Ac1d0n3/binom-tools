<?php

namespace App\Http\Controllers\Accounts;

use App\Accounts\AccountAuth;
use App\Accounts\MembershipSync;
use App\Accounts\TeamRepository;
use App\Accounts\UserRepository;
use App\Http\Controllers\Controller;
use App\Support\AccentColors;
use App\Support\AvatarIcons;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UsersController extends Controller
{
    public function __construct(
        private readonly AccountAuth $auth,
        private readonly UserRepository $users,
        private readonly TeamRepository $teams,
        private readonly MembershipSync $membership,
    ) {}

    public function index(): View
    {
        $this->assertCanManage();

        return view('accounts.users', [
            'users' => array_map(static fn ($u) => $u->toPublicArray(), $this->users->all()),
            'teams' => array_map(static fn ($t) => $t->toArray(), $this->teams->all(true)),
            'actorId' => $this->auth->id(),
        ]);
    }

    public function create(): View
    {
        $this->assertCanManage();

        return view('accounts.users-form', [
            'user' => null,
            'teams' => array_map(static fn ($t) => $t->toArray(), $this->teams->all(true)),
            'actorId' => $this->auth->id(),
        ]);
    }

    public function edit(string $userId): View
    {
        $this->assertCanManage();
        $user = $this->users->findById($userId);
        abort_if($user === null, 404);

        return view('accounts.users-form', [
            'user' => $user->toPublicArray(),
            'teams' => array_map(static fn ($t) => $t->toArray(), $this->teams->all(true)),
            'actorId' => $this->auth->id(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->assertCanManage();

        $data = $request->validate([
            'email' => ['required', 'email'],
            'displayName' => ['required', 'string', 'max:120'],
            'password' => ['required', 'string', 'min:8'],
            'teamIds' => ['nullable', 'array'],
            'teamIds.*' => ['string'],
            'canManageUsers' => ['sometimes', 'boolean'],
            'canManageTeams' => ['sometimes', 'boolean'],
            'active' => ['sometimes', 'boolean'],
            'shortName' => ['nullable', 'string', 'max:3'],
            'colorToken' => ['nullable', 'string', Rule::in(AccentColors::TOKENS)],
            'avatarIcon' => ['nullable', 'string', Rule::in(array_merge([''], AvatarIcons::OPTIONS))],
        ]);

        $user = $this->users->upsert([
            'email' => $data['email'],
            'displayName' => $data['displayName'],
            'passwordHash' => password_hash($data['password'], PASSWORD_DEFAULT),
            'teamIds' => $data['teamIds'] ?? [],
            'canManageUsers' => $request->boolean('canManageUsers'),
            'canManageTeams' => $request->boolean('canManageTeams'),
            'active' => $request->boolean('active', true),
            'shortName' => $data['shortName'] ?? '',
            'colorToken' => $data['colorToken'] ?? 'accent-1',
            'avatarIcon' => AvatarIcons::normalize($data['avatarIcon'] ?? ''),
        ]);

        $this->membership->syncTeamsFromUser($user);

        return redirect()
            ->to(locale_route('accounts.users'))
            ->with('status', 'user-created');
    }

    public function update(Request $request, string $userId): RedirectResponse
    {
        $this->assertCanManage();

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
            'shortName' => ['nullable', 'string', 'max:3'],
            'colorToken' => ['nullable', 'string', Rule::in(AccentColors::TOKENS)],
            'avatarIcon' => ['nullable', 'string', Rule::in(array_merge([''], AvatarIcons::OPTIONS))],
        ]);

        $payload = [
            ...$existing->toArray(),
            'email' => $data['email'],
            'displayName' => $data['displayName'],
            'teamIds' => $data['teamIds'] ?? [],
            'canManageUsers' => $request->boolean('canManageUsers'),
            'canManageTeams' => $request->boolean('canManageTeams'),
            'active' => $request->boolean('active', true),
            'shortName' => $data['shortName'] ?? '',
            'colorToken' => $data['colorToken'] ?? 'accent-1',
            'avatarIcon' => AvatarIcons::normalize($data['avatarIcon'] ?? ''),
        ];

        if (! empty($data['password'])) {
            $payload['passwordHash'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        unset($payload['password']);
        $user = $this->users->upsert($payload);
        $this->membership->syncTeamsFromUser($user);

        return redirect()
            ->to(locale_route('accounts.users'))
            ->with('status', 'user-updated');
    }

    public function destroy(string $userId): RedirectResponse
    {
        $this->assertCanManage();
        abort_if($this->auth->id() === $userId, 422);
        abort_if($this->users->findById($userId) === null, 404);

        $this->users->delete($userId);
        $this->membership->removeUserFromTeams($userId);

        return redirect()
            ->to(locale_route('accounts.users'))
            ->with('status', 'user-deleted');
    }

    private function assertCanManage(): void
    {
        $actor = $this->auth->user();
        abort_if($actor === null || ! $actor->canManageUsers, 403);
    }
}
