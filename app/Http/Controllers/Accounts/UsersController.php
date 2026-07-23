<?php

namespace App\Http\Controllers\Accounts;

use App\Accounts\AccountAuth;
use App\Accounts\MembershipSync;
use App\Accounts\TeamRepository;
use App\Accounts\UserRepository;
use App\Http\Controllers\Controller;
use App\Mail\AccountWelcomeMail;
use App\Support\AccentColors;
use App\Support\AvatarIcons;
use App\Support\ShortName;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

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

        $generatePassword = $request->boolean('generatePassword');
        $sendInvite = $request->boolean('sendInvite');

        $data = $request->validate([
            'email' => ['required', 'email'],
            'displayName' => ['required', 'string', 'max:120'],
            'password' => [$generatePassword ? 'nullable' : 'required', 'string', 'min:8'],
            'teamIds' => ['nullable', 'array'],
            'teamIds.*' => ['string'],
            'canManageUsers' => ['sometimes', 'boolean'],
            'canManageTeams' => ['sometimes', 'boolean'],
            'active' => ['sometimes', 'boolean'],
            'shortName' => ShortName::rules(),
            'colorToken' => ['nullable', 'string', Rule::in(AccentColors::TOKENS)],
            'avatarIcon' => ['nullable', 'string', Rule::in(array_merge([''], AvatarIcons::OPTIONS))],
            'generatePassword' => ['sometimes', 'boolean'],
            'sendInvite' => ['sometimes', 'boolean'],
            'mustChangePassword' => ['sometimes', 'boolean'],
        ]);

        $plainPassword = $generatePassword
            ? Str::password(12, symbols: false)
            : (string) $data['password'];

        $mustChange = $request->boolean('mustChangePassword');

        $user = $this->users->upsert([
            'email' => $data['email'],
            'displayName' => $data['displayName'],
            'passwordHash' => password_hash($plainPassword, PASSWORD_DEFAULT),
            'teamIds' => $data['teamIds'] ?? [],
            'canManageUsers' => $request->boolean('canManageUsers'),
            'canManageTeams' => $request->boolean('canManageTeams'),
            'active' => $request->boolean('active', true),
            'shortName' => $data['shortName'] ?? '',
            'colorToken' => $data['colorToken'] ?? 'accent-1',
            'avatarIcon' => AvatarIcons::normalize($data['avatarIcon'] ?? ''),
            'mustChangePassword' => $mustChange,
        ]);

        $this->membership->syncTeamsFromUser($user);

        $redirect = redirect()->to(locale_route('accounts.users'));

        if ($sendInvite) {
            $mailResult = $this->sendWelcomeMail($user->email, $user->displayName, $plainPassword, $mustChange);
            if ($mailResult === true) {
                return $redirect->with('status', 'user-created-invited');
            }

            return $redirect
                ->with('status', 'user-created-invite-failed')
                ->with('invite_plain_password', $plainPassword)
                ->with('invite_error', $mailResult);
        }

        if ($generatePassword) {
            return $redirect
                ->with('status', 'user-created-with-password')
                ->with('invite_plain_password', $plainPassword);
        }

        return $redirect->with('status', 'user-created');
    }

    public function update(Request $request, string $userId): RedirectResponse
    {
        $this->assertCanManage();

        $existing = $this->users->findById($userId);
        abort_if($existing === null, 404);

        $generatePassword = $request->boolean('generatePassword');
        $sendInvite = $request->boolean('sendInvite');

        $data = $request->validate([
            'email' => ['required', 'email'],
            'displayName' => ['required', 'string', 'max:120'],
            'password' => [$generatePassword ? 'nullable' : 'nullable', 'string', 'min:8'],
            'teamIds' => ['nullable', 'array'],
            'teamIds.*' => ['string'],
            'canManageUsers' => ['sometimes', 'boolean'],
            'canManageTeams' => ['sometimes', 'boolean'],
            'active' => ['sometimes', 'boolean'],
            'shortName' => ShortName::rules(),
            'colorToken' => ['nullable', 'string', Rule::in(AccentColors::TOKENS)],
            'avatarIcon' => ['nullable', 'string', Rule::in(array_merge([''], AvatarIcons::OPTIONS))],
            'generatePassword' => ['sometimes', 'boolean'],
            'sendInvite' => ['sometimes', 'boolean'],
            'mustChangePassword' => ['sometimes', 'boolean'],
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

        $plainPassword = null;
        if ($generatePassword) {
            $plainPassword = Str::password(12, symbols: false);
            $payload['passwordHash'] = password_hash($plainPassword, PASSWORD_DEFAULT);
            $payload['mustChangePassword'] = true;
        } elseif (! empty($data['password'])) {
            $plainPassword = (string) $data['password'];
            $payload['passwordHash'] = password_hash($plainPassword, PASSWORD_DEFAULT);
            $payload['mustChangePassword'] = $request->boolean('mustChangePassword', $sendInvite);
        } elseif ($request->has('mustChangePassword')) {
            $payload['mustChangePassword'] = $request->boolean('mustChangePassword');
        }

        unset($payload['password']);
        $user = $this->users->upsert($payload);
        $this->membership->syncTeamsFromUser($user);

        $redirect = redirect()->to(locale_route('accounts.users'));

        if ($sendInvite && $plainPassword !== null) {
            $mailResult = $this->sendWelcomeMail($user->email, $user->displayName, $plainPassword, true);
            if ($mailResult === true) {
                return $redirect->with('status', 'user-updated-invited');
            }

            return $redirect
                ->with('status', 'user-updated-invite-failed')
                ->with('invite_plain_password', $plainPassword)
                ->with('invite_error', $mailResult);
        }

        if ($generatePassword && $plainPassword !== null) {
            return $redirect
                ->with('status', 'user-updated-with-password')
                ->with('invite_plain_password', $plainPassword);
        }

        return $redirect->with('status', 'user-updated');
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

    /**
     * @return true|string true on success, error message on failure
     */
    private function sendWelcomeMail(string $email, string $displayName, string $plainPassword, bool $mustChange): bool|string
    {
        try {
            Mail::to($email)->send(new AccountWelcomeMail(
                displayName: $displayName,
                emailAddress: $email,
                temporaryPassword: $plainPassword,
                loginUrl: locale_route('accounts.login'),
                mustChangePassword: $mustChange,
            ));

            return true;
        } catch (Throwable $e) {
            report($e);

            return $e->getMessage();
        }
    }

    private function assertCanManage(): void
    {
        $actor = $this->auth->user();
        abort_if($actor === null || ! $actor->canManageUsers, 403);
    }
}
