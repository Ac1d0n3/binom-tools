<?php

namespace App\Http\Controllers\SprintPlanner;

use App\Accounts\AccountAuth;
use App\Accounts\AccountsConfig;
use App\Accounts\PlanStore;
use App\Accounts\ReadStateStore;
use App\Accounts\TeamRepository;
use App\Accounts\UserRepository;
use App\Http\Controllers\Controller;
use App\SprintPlanner\SprintPlanRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SprintPlannerController extends Controller
{
    public function __construct(
        private readonly SprintPlanRepository $plans,
        private readonly AccountsConfig $accountsConfig,
        private readonly AccountAuth $accountAuth,
        private readonly UserRepository $users,
        private readonly TeamRepository $teams,
        private readonly PlanStore $planStore,
        private readonly ReadStateStore $readState,
    ) {}

    public function index(): View
    {
        return view('sprint-planner.index', $this->sharedViewData());
    }

    public function templates(): View
    {
        return view('sprint-planner.templates', $this->sharedViewData());
    }

    public function people(): View|RedirectResponse
    {
        if ($this->accountsConfig->enabled()) {
            $user = $this->accountAuth->user();
            if ($user === null) {
                return redirect()->guest(locale_route('accounts.login'));
            }
            if (! $user->canManageUsers && ! $user->canManageTeams) {
                abort(403);
            }
        }

        return view('sprint-planner.people', $this->sharedViewData());
    }

    public function show(Request $request): View
    {
        $instanceId = (string) $request->route('instanceId');

        return view('sprint-planner.show', [
            ...$this->sharedViewData(),
            'instanceId' => $instanceId,
        ]);
    }

    public function settings(Request $request): View
    {
        $instanceId = (string) $request->route('instanceId');

        return view('sprint-planner.settings', [
            ...$this->sharedViewData(),
            'instanceId' => $instanceId,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function sharedViewData(): array
    {
        $accountsOn = $this->accountsConfig->enabled();
        $user = $this->accountAuth->user();
        $people = [];
        $teams = [];
        $serverPlans = [];
        $readSlugs = [];

        if ($accountsOn && $user !== null) {
            $people = array_map(static fn ($u) => $u->toPublicArray(), $this->users->all());
            $teams = array_map(static fn ($t) => $t->toArray(), $this->teams->all());
            $serverPlans = $this->planStore->listVisibleTo($user);
            $readSlugs = array_keys($this->readState->forUser($user->id));
        }

        return [
            'templates' => $this->plans->allForIndex(),
            'templatesJson' => $this->plans->allForClient(),
            'accountsEnabled' => $accountsOn,
            'accountUser' => $user?->toPublicArray(),
            'demoMode' => $accountsOn && $user === null,
            'loginUrl' => $accountsOn ? locale_route('accounts.login') : null,
            'accountPeopleJson' => $people,
            'accountTeamsJson' => $teams,
            'serverPlansJson' => $serverPlans,
            'readSlugsJson' => $readSlugs,
            'plansApiUrl' => $accountsOn && $user !== null ? locale_route('accounts.plans.index') : null,
            'storiesApiUrl' => $accountsOn && $user !== null ? locale_route('accounts.plans.stories') : null,
        ];
    }
}
