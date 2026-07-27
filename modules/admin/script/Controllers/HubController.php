<?php

namespace App\Http\Controllers\Admin;

use App\Accounts\AccountAuth;
use App\Admin\Contracts\WorkspaceStoreInterface;
use Illuminate\View\View;

class HubController extends AdminController
{
    public function __construct(
        AccountAuth $auth,
        private readonly WorkspaceStoreInterface $workspaces,
    ) {
        parent::__construct($auth);
    }

    public function index(): View
    {
        $user = $this->user();
        $workspaces = $this->workspaces->listFor($user);

        return $this->adminView('admin::index', [
            'workspaces' => $workspaces,
            'activeWorkspaceId' => $this->workspaces->activeId($user),
            'canManageUsers' => $user->canManageUsers,
            'canManageTeams' => $user->canManageTeams,
        ]);
    }
}
