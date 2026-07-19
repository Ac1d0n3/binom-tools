<?php

namespace App\Accounts;

use Illuminate\Support\Facades\Session;

final class AccountAuth
{
    public const SESSION_KEY = 'bn_tools_account_user_id';

    public function __construct(
        private readonly UserRepository $users,
        private readonly AccountsConfig $config,
    ) {}

    public function attempt(string $email, string $password): bool
    {
        if (! $this->config->enabled()) {
            return false;
        }

        $user = $this->users->findByEmail($email);
        if ($user === null || ! $user->active) {
            return false;
        }

        if (! password_verify($password, $user->passwordHash)) {
            return false;
        }

        Session::regenerate(true);
        Session::put(self::SESSION_KEY, $user->id);

        return true;
    }

    public function logout(): void
    {
        Session::forget(self::SESSION_KEY);
        Session::regenerate(true);
    }

    public function id(): ?string
    {
        $id = Session::get(self::SESSION_KEY);

        return is_string($id) && $id !== '' ? $id : null;
    }

    public function user(): ?AccountUser
    {
        $id = $this->id();
        if ($id === null) {
            return null;
        }

        $user = $this->users->findById($id);
        if ($user === null || ! $user->active) {
            $this->logout();

            return null;
        }

        return $user;
    }

    public function check(): bool
    {
        return $this->user() !== null;
    }
}
