<?php

namespace App\Http\Controllers\Admin;

use App\Accounts\AccountAuth;
use Illuminate\View\View;

class HubController extends AdminController
{
    public function __construct(AccountAuth $auth)
    {
        parent::__construct($auth);
    }

    public function index(): View
    {
        $user = $this->user();
        $storiesPath = (string) config('admin.stories_path', base_path('content/stories'));
        $plansPath = (string) config('admin.sprint_plans_path', base_path('content/sprint-plans'));
        $storyCount = $this->countDirectories($storiesPath);
        $templateCount = $this->countMarkdownFiles($plansPath);

        return $this->adminView('admin::index', [
            'canManageUsers' => $user->canManageUsers,
            'canManageTeams' => $user->canManageTeams,
            'storyCount' => $storyCount,
            'templateCount' => $templateCount,
        ]);
    }

    private function countDirectories(string $path): int
    {
        if (! is_dir($path)) {
            return 0;
        }
        $count = 0;
        foreach (scandir($path) ?: [] as $item) {
            if ($item === '.' || $item === '..' || str_starts_with($item, '.')) {
                continue;
            }
            if (is_dir($path.DIRECTORY_SEPARATOR.$item)) {
                $count++;
            }
        }

        return $count;
    }

    private function countMarkdownFiles(string $path): int
    {
        if (! is_dir($path)) {
            return 0;
        }
        $files = glob($path.DIRECTORY_SEPARATOR.'*.md') ?: [];

        return count($files);
    }
}
