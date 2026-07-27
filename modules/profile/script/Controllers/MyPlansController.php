<?php

namespace App\Http\Controllers\Profile;

use App\Accounts\AccountAuth;
use App\Accounts\Contracts\PlanStoreInterface;
use App\Profile\Contracts\WorkspaceStoreInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use InvalidArgumentException;

class MyPlansController extends ProfileController
{
    public function __construct(
        AccountAuth $auth,
        private readonly PlanStoreInterface $plans,
        private readonly WorkspaceStoreInterface $workspaces,
    ) {
        parent::__construct($auth);
    }

    public function index(): View
    {
        $user = $this->user();
        $activeId = $this->workspaces->activeId($user);
        $plans = $this->plans->listVisibleTo($user);
        if ($activeId !== null) {
            $plans = array_values(array_filter(
                $plans,
                static fn (array $p): bool => ($p['workspaceId'] ?? null) === $activeId
                    || ($p['workspaceId'] ?? null) === null
            ));
        }

        return $this->profileView('profile::plans.index', [
            'plans' => $plans,
            'workspaces' => $this->workspaces->listFor($user),
            'activeWorkspaceId' => $activeId,
        ]);
    }

    public function assign(Request $request, string $planId): RedirectResponse
    {
        $user = $this->user();
        $plan = $this->plans->find($planId);
        abort_if($plan === null || ! $this->plans->canAccess($user, $plan), 404);

        $data = $request->validate([
            'workspaceId' => ['nullable', 'string', 'max:64'],
        ]);
        $workspaceId = $data['workspaceId'] ?? null;
        if (is_string($workspaceId) && $workspaceId !== '' && $this->workspaces->find($workspaceId, $user) === null) {
            return back()->withErrors(['workspaceId' => 'Workspace not found.']);
        }

        $plan['workspaceId'] = $workspaceId !== '' ? $workspaceId : null;
        try {
            $this->plans->save($plan, $user, ['action' => 'assign-workspace', 'summary' => 'Assigned workspace']);
        } catch (InvalidArgumentException $e) {
            return back()->withErrors(['workspaceId' => $e->getMessage()]);
        }

        return back()->with('status', 'plan-workspace-assigned');
    }

    public function duplicate(Request $request, string $planId): RedirectResponse
    {
        $user = $this->user();
        $plan = $this->plans->find($planId);
        abort_if($plan === null || ! $this->plans->canAccess($user, $plan), 404);

        $data = $request->validate([
            'workspaceId' => ['nullable', 'string', 'max:64'],
        ]);
        $workspaceId = $data['workspaceId'] ?? $plan['workspaceId'] ?? null;
        if (is_string($workspaceId) && $workspaceId !== '' && $this->workspaces->find($workspaceId, $user) === null) {
            return back()->withErrors(['workspaceId' => 'Workspace not found.']);
        }

        $copy = $plan;
        $copy['id'] = 'plan_'.bin2hex(random_bytes(8));
        $copy['workspaceId'] = $workspaceId !== '' ? $workspaceId : null;
        unset($copy['createdAt'], $copy['updatedAt'], $copy['updatedBy']);
        if (isset($copy['translations']['en']['title'])) {
            $copy['translations']['en']['title'] = ($copy['translations']['en']['title'] ?? 'Plan').' (copy)';
        }
        if (isset($copy['translations']['de']['title'])) {
            $copy['translations']['de']['title'] = ($copy['translations']['de']['title'] ?? 'Plan').' (Kopie)';
        }

        try {
            $this->plans->save($copy, $user, ['action' => 'duplicate', 'summary' => 'Duplicated into workspace']);
        } catch (InvalidArgumentException $e) {
            return back()->withErrors(['workspaceId' => $e->getMessage()]);
        }

        return back()->with('status', 'plan-duplicated');
    }
}
