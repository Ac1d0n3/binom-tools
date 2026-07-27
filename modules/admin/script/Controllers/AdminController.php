<?php

namespace App\Http\Controllers\Admin;

use App\Accounts\AccountAuth;
use App\Accounts\AccountUser;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;

abstract class AdminController extends Controller
{
    public function __construct(
        protected readonly AccountAuth $auth,
    ) {}

    protected function user(): AccountUser
    {
        $user = $this->auth->user();
        if ($user === null) {
            throw new HttpException(401);

        }

        return $user;
    }

    protected function assertCanManageUsers(): AccountUser
    {
        $user = $this->user();
        if (! $user->canManageUsers) {
            abort(403);
        }

        return $user;
    }

    protected function assertCanManageTeams(): AccountUser
    {
        $user = $this->user();
        if (! $user->canManageTeams) {
            abort(403);
        }

        return $user;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function adminView(string $view, array $data = [])
    {
        return view($view, $data);
    }
}
