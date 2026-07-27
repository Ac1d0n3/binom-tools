<?php

namespace App\Http\Controllers\Admin;

use App\Accounts\AccountAuth;
use App\Accounts\Contracts\GlossaryQuizResultStoreInterface;
use App\Accounts\Contracts\ReadStateStoreInterface;
use Illuminate\View\View;

class ProgressController extends AdminController
{
    public function __construct(
        AccountAuth $auth,
        private readonly ReadStateStoreInterface $reads,
        private readonly GlossaryQuizResultStoreInterface $quiz,
    ) {
        parent::__construct($auth);
    }

    public function reads(): View
    {
        $user = $this->user();
        $map = $this->reads->forUser($user->id);
        arsort($map);
        $items = [];
        foreach ($map as $slug => $ts) {
            $items[] = [
                'slug' => $slug,
                'readAt' => is_numeric($ts) ? (int) $ts : null,
            ];
        }

        return $this->adminView('admin::reads.index', [
            'items' => $items,
        ]);
    }

    public function quiz(): View
    {
        $user = $this->user();

        return $this->adminView('admin::quiz.index', [
            'results' => $this->quiz->loadFor($user),
        ]);
    }
}
