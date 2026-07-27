<?php

namespace App\Http\Controllers\Profile;

use App\Accounts\AccountAuth;
use App\Accounts\Contracts\GlossaryQuizResultStoreInterface;
use App\Accounts\Contracts\PlanStoreInterface;
use App\Accounts\Contracts\ReadStateStoreInterface;
use App\Profile\Contracts\WorkspaceStoreInterface;
use Illuminate\View\View;

class HubController extends ProfileController
{
    public function __construct(
        AccountAuth $auth,
        private readonly WorkspaceStoreInterface $workspaces,
        private readonly PlanStoreInterface $plans,
        private readonly ReadStateStoreInterface $reads,
        private readonly GlossaryQuizResultStoreInterface $quiz,
    ) {
        parent::__construct($auth);
    }

    public function index(): View
    {
        $user = $this->user();
        $activeId = $this->workspaces->activeId($user);
        $active = $activeId ? $this->workspaces->find($activeId, $user) : null;
        $workspaceCount = count($this->workspaces->listFor($user));
        $planCount = count($this->plans->listVisibleTo($user));
        $readCount = count($this->reads->forUser($user->id));
        $quizResults = $this->quiz->loadFor($user);
        $quizAttempts = (int) ($quizResults['attemptCount'] ?? 0);

        return $this->profileView('profile::index', [
            'activeWorkspace' => $active,
            'workspaceCount' => $workspaceCount,
            'planCount' => $planCount,
            'readCount' => $readCount,
            'quizAttempts' => $quizAttempts,
            'quizBestScore' => (int) ($quizResults['bestScore'] ?? 0),
            'quizBestTotal' => (int) ($quizResults['bestTotal'] ?? 0),
        ]);
    }
}
