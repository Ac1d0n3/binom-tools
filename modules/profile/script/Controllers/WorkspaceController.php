<?php

namespace App\Http\Controllers\Profile;

use App\Accounts\AccountAuth;
use App\Profile\Contracts\WorkspaceStoreInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use InvalidArgumentException;

class WorkspaceController extends ProfileController
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

        return $this->profileView('profile::workspaces.index', [
            'workspaces' => $this->workspaces->listFor($user, true),
            'activeWorkspaceId' => $this->workspaces->activeId($user),
            'stacks' => $this->stackOptions(),
        ]);
    }

    public function create(): View
    {
        return $this->profileView('profile::workspaces.form', [
            'workspace' => null,
            'stacks' => $this->stackOptions(),
        ]);
    }

    public function edit(string $workspaceId): View
    {
        $user = $this->user();
        $workspace = $this->workspaces->find($workspaceId, $user);
        abort_if($workspace === null, 404);

        return $this->profileView('profile::workspaces.form', [
            'workspace' => $workspace,
            'stacks' => $this->stackOptions(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $this->user();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'stack' => ['required', 'string', 'max:40'],
            'label' => ['nullable', 'string', 'max:120'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        try {
            $row = $this->workspaces->save($data, $user);
            if ($this->workspaces->activeId($user) === null) {
                $this->workspaces->setActive($user, $row['id']);
            }
        } catch (InvalidArgumentException $e) {
            return back()->withErrors(['name' => $e->getMessage()])->withInput();
        }

        return redirect()->to(locale_route('profile.workspaces.index'))->with('status', 'workspace-saved');
    }

    public function update(Request $request, string $workspaceId): RedirectResponse
    {
        $user = $this->user();
        $existing = $this->workspaces->find($workspaceId, $user);
        abort_if($existing === null, 404);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'stack' => ['required', 'string', 'max:40'],
            'label' => ['nullable', 'string', 'max:120'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);
        $data['id'] = $workspaceId;

        try {
            $this->workspaces->save($data, $user);
        } catch (InvalidArgumentException $e) {
            return back()->withErrors(['name' => $e->getMessage()])->withInput();
        }

        return redirect()->to(locale_route('profile.workspaces.index'))->with('status', 'workspace-saved');
    }

    public function activate(string $workspaceId): RedirectResponse
    {
        $user = $this->user();
        try {
            $this->workspaces->setActive($user, $workspaceId);
        } catch (InvalidArgumentException) {
            abort(404);
        }

        return back()->with('status', 'workspace-active');
    }

    public function duplicate(string $workspaceId): RedirectResponse
    {
        $user = $this->user();
        try {
            $this->workspaces->duplicate($workspaceId, $user);
        } catch (InvalidArgumentException) {
            abort(404);
        }

        return redirect()->to(locale_route('profile.workspaces.index'))->with('status', 'workspace-duplicated');
    }

    public function archive(string $workspaceId): RedirectResponse
    {
        $this->workspaces->archive($workspaceId, $this->user());

        return redirect()->to(locale_route('profile.workspaces.index'))->with('status', 'workspace-archived');
    }

    public function activePayload(): \Illuminate\Http\JsonResponse
    {
        $user = $this->user();
        $activeId = $this->workspaces->activeId($user);
        if ($activeId === null) {
            return response()->json(['workspace' => null]);
        }
        $workspace = $this->workspaces->find($activeId, $user);
        if ($workspace === null) {
            return response()->json(['workspace' => null]);
        }

        return response()->json([
            'workspace' => [
                'id' => $workspace['id'],
                'name' => $workspace['name'] ?? '',
                'stack' => $workspace['stack'] ?? 'unknown',
                'customStack' => is_array($workspace['customStack'] ?? null) ? $workspace['customStack'] : null,
                'savedStacks' => is_array($workspace['savedStacks'] ?? null) ? array_values($workspace['savedStacks']) : [],
            ],
        ]);
    }

    public function syncActiveStack(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = $this->user();
        $activeId = $this->workspaces->activeId($user);
        if ($activeId === null) {
            return response()->json(['error' => 'No active workspace.'], 422);
        }
        $workspace = $this->workspaces->find($activeId, $user);
        abort_if($workspace === null, 404);

        $data = $request->validate([
            'stack' => ['required', 'string', 'max:40'],
            'customStack' => ['nullable', 'array'],
        ]);
        $workspace['stack'] = $data['stack'];
        if (array_key_exists('customStack', $data)) {
            $workspace['customStack'] = is_array($data['customStack']) ? $data['customStack'] : null;
        }
        $row = $this->workspaces->save($workspace, $user);

        return response()->json([
            'workspace' => [
                'id' => $row['id'],
                'stack' => $row['stack'] ?? 'unknown',
                'customStack' => is_array($row['customStack'] ?? null) ? $row['customStack'] : null,
                'savedStacks' => is_array($row['savedStacks'] ?? null) ? array_values($row['savedStacks']) : [],
            ],
        ]);
    }

    public function storeSavedStack(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = $this->user();
        $activeId = $this->workspaces->activeId($user);
        if ($activeId === null) {
            return response()->json(['error' => 'No active workspace.'], 422);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'selection' => ['required', 'array'],
            'id' => ['nullable', 'string', 'max:64'],
        ]);

        try {
            $entry = $this->workspaces->upsertSavedStack(
                $activeId,
                $user,
                $data['name'],
                $data['selection'],
                $data['id'] ?? null,
            );
        } catch (InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

        $workspace = $this->workspaces->find($activeId, $user);

        return response()->json([
            'savedStack' => $entry,
            'savedStacks' => is_array($workspace['savedStacks'] ?? null) ? array_values($workspace['savedStacks']) : [],
        ]);
    }

    public function destroySavedStack(string $stackId): \Illuminate\Http\JsonResponse
    {
        $user = $this->user();
        $activeId = $this->workspaces->activeId($user);
        if ($activeId === null) {
            return response()->json(['error' => 'No active workspace.'], 422);
        }

        try {
            $this->workspaces->removeSavedStack($activeId, $user, $stackId);
        } catch (InvalidArgumentException) {
            abort(404);
        }

        $workspace = $this->workspaces->find($activeId, $user);

        return response()->json([
            'savedStacks' => is_array($workspace['savedStacks'] ?? null) ? array_values($workspace['savedStacks']) : [],
        ]);
    }

    /**
     * @return array<string, array{de: string, en: string}>
     */
    private function stackOptions(): array
    {
        $platforms = config('taxonomy.platforms', [
            'unknown' => ['de' => 'Offen', 'en' => 'Open'],
            'fabric' => ['de' => 'Fabric', 'en' => 'Fabric'],
            'databricks' => ['de' => 'Databricks', 'en' => 'Databricks'],
            'snowflake-dbt' => ['de' => 'Snowflake / dbt', 'en' => 'Snowflake / dbt'],
        ]);
        if (! isset($platforms['custom'])) {
            $platforms['custom'] = ['de' => 'Eigener Stack', 'en' => 'Custom stack'];
        }

        return $platforms;
    }
}
