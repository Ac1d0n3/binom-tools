<?php

namespace App\Http\Controllers\Accounts;

use App\Accounts\AccountAuth;
use App\Accounts\AccountsConfig;
use App\Accounts\UserRepository;
use App\Http\Controllers\Controller;
use App\Support\AccentColors;
use App\Support\AvatarIcons;
use App\Support\ShortName;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(
        private readonly AccountsConfig $config,
        private readonly AccountAuth $auth,
        private readonly UserRepository $users,
    ) {}

    public function showLogin(): View|RedirectResponse
    {
        if (! $this->config->enabled()) {
            abort(404);
        }
        if ($this->auth->check()) {
            return redirect(locale_route('tools.landing'));
        }

        return view('accounts.login');
    }

    public function login(Request $request): RedirectResponse
    {
        if (! $this->config->enabled()) {
            abort(404);
        }

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! $this->auth->attempt($credentials['email'], $credentials['password'])) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => __('auth.failed')]);
        }

        $user = $this->auth->user();
        if ($user?->mustChangePassword) {
            return redirect()
                ->to(locale_route('accounts.profile'))
                ->with('status', 'must-change-password');
        }

        return redirect()->intended(locale_route('tools.landing'));
    }

    public function logout(Request $request): RedirectResponse
    {
        if (! $this->config->enabled()) {
            abort(404);
        }

        $this->auth->logout();

        return redirect(locale_route('tools.landing'));
    }

    public function profile(): View
    {
        $user = $this->auth->user();
        abort_if($user === null, 401);

        return view('accounts.profile', [
            'account' => $user->toPublicArray(),
            'profileAvatarEnabled' => $this->config->profileAvatarEnabled(),
            'mustChangePassword' => $user->mustChangePassword,
        ]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $this->auth->user();
        abort_if($user === null, 401);

        $mustChange = $user->mustChangePassword;

        $rules = [
            'displayName' => ['required', 'string', 'max:120'],
            'current_password' => [$mustChange ? 'required' : 'nullable', 'string'],
            'password' => [$mustChange ? 'required' : 'nullable', 'string', 'min:8', 'confirmed'],
        ];

        if ($this->config->profileAvatarEnabled()) {
            $rules['shortName'] = ShortName::rules();
            $rules['colorToken'] = ['nullable', 'string', Rule::in(AccentColors::TOKENS)];
            $rules['avatarIcon'] = ['nullable', 'string', Rule::in(array_merge([''], AvatarIcons::OPTIONS))];
        }

        $data = $request->validate($rules);

        $payload = [
            ...$user->toArray(),
            'displayName' => $data['displayName'],
        ];

        if ($this->config->profileAvatarEnabled()) {
            $payload['shortName'] = $data['shortName'] ?? '';
            $payload['colorToken'] = $data['colorToken'] ?? 'accent-1';
            $payload['avatarIcon'] = AvatarIcons::normalize($data['avatarIcon'] ?? '');
        }

        if (! empty($data['password'])) {
            if (empty($data['current_password']) || ! password_verify($data['current_password'], $user->passwordHash)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }
            $payload['passwordHash'] = password_hash($data['password'], PASSWORD_DEFAULT);
            $payload['mustChangePassword'] = false;
        }

        unset($payload['password'], $payload['password_confirmation'], $payload['current_password']);
        $this->users->upsert($payload);

        return back()->with('status', 'profile-updated');
    }
}
