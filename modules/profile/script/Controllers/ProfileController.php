<?php

namespace App\Http\Controllers\Profile;

use App\Accounts\AccountAuth;
use App\Accounts\AccountUser;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;

abstract class ProfileController extends Controller
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

    /**
     * @param  array<string, mixed>  $data
     */
    protected function profileView(string $view, array $data = [])
    {
        return view($view, $data);
    }
}
