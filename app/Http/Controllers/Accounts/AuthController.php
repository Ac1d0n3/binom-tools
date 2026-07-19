<?php

namespace App\Http\Controllers\Accounts;

use App\Accounts\AccountAuth;
use App\Accounts\AccountsConfig;
use App\Accounts\UserRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        ]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $this->auth->user();
        abort_if($user === null, 401);

        $data = $request->validate([
            'displayName' => ['required', 'string', 'max:120'],
            'current_password' => ['nullable', 'string'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $payload = [
            ...$user->toArray(),
            'displayName' => $data['displayName'],
        ];

        if (! empty($data['password'])) {
            if (empty($data['current_password']) || ! password_verify($data['current_password'], $user->passwordHash)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }
            $payload['passwordHash'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        unset($payload['password'], $payload['password_confirmation'], $payload['current_password']);
        $this->users->upsert($payload);

        return back()->with('status', 'profile-updated');
    }
}
