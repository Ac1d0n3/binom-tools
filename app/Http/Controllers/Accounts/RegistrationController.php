<?php

namespace App\Http\Controllers\Accounts;

use App\Accounts\AccountAuth;
use App\Accounts\AccountsConfig;
use App\Accounts\Contracts\UserRepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use InvalidArgumentException;

class RegistrationController extends Controller
{
    public function __construct(
        private readonly AccountsConfig $config,
        private readonly AccountAuth $auth,
        private readonly UserRepositoryInterface $users,
    ) {}

    public function show(): View|RedirectResponse
    {
        if (! $this->config->registrationEnabled()) {
            abort(404);
        }
        if ($this->auth->check()) {
            return redirect(locale_route('tools.landing'));
        }

        return view('accounts.register');
    }

    public function store(Request $request): RedirectResponse
    {
        if (! $this->config->registrationEnabled()) {
            abort(404);
        }
        if ($this->auth->check()) {
            return redirect(locale_route('tools.landing'));
        }

        $data = $request->validate([
            'email' => ['required', 'email', 'max:190'],
            'displayName' => ['required', 'string', 'max:120'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($this->users->findByEmail($data['email']) !== null) {
            return back()
                ->withInput($request->only('email', 'displayName'))
                ->withErrors(['email' => __('validation.unique', ['attribute' => 'email'])]);
        }

        try {
            $this->users->upsert([
                'email' => $data['email'],
                'displayName' => $data['displayName'],
                'passwordHash' => password_hash($data['password'], PASSWORD_DEFAULT),
                'teamIds' => [],
                'canManageUsers' => false,
                'canManageTeams' => false,
                'active' => false,
                'pendingApproval' => true,
                'mustChangePassword' => false,
            ]);
        } catch (InvalidArgumentException $e) {
            return back()
                ->withInput($request->only('email', 'displayName'))
                ->withErrors(['email' => $e->getMessage()]);
        }

        return redirect()
            ->to(locale_route('accounts.login'))
            ->with('status', 'registration-pending');
    }
}
