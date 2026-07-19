<?php

namespace App\Http\Controllers\Accounts;

use App\Accounts\AccountAuth;
use App\Accounts\MembershipSync;
use App\Accounts\TeamRepository;
use App\Accounts\UserRepository;
use App\Http\Controllers\Controller;
use App\Support\AccentColors;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeamsController extends Controller
{
    public function __construct(
        private readonly AccountAuth $auth,
        private readonly TeamRepository $teams,
        private readonly UserRepository $users,
        private readonly MembershipSync $membership,
    ) {}

    public function index(): View
    {
        $this->assertCanManage();

        return view('accounts.teams', [
            'teams' => array_map(static fn ($t) => $t->toArray(), $this->teams->all(true)),
            'users' => array_map(static fn ($u) => $u->toPublicArray(), $this->users->all()),
        ]);
    }

    public function create(): View
    {
        $this->assertCanManage();

        return view('accounts.teams-form', [
            'team' => null,
            'users' => array_map(static fn ($u) => $u->toPublicArray(), $this->users->all()),
            'defaultColorToken' => $this->nextColorToken(),
        ]);
    }

    public function edit(string $teamId): View
    {
        $this->assertCanManage();
        $team = $this->teams->findById($teamId);
        abort_if($team === null, 404);

        return view('accounts.teams-form', [
            'team' => $team->toArray(),
            'users' => array_map(static fn ($u) => $u->toPublicArray(), $this->users->all()),
            'defaultColorToken' => $team->colorToken,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->assertCanManage();
        $data = $this->validated($request);

        $team = $this->teams->upsert([
            'name' => [
                'de' => $data['name_de'],
                'en' => $data['name_en'] ?: $data['name_de'],
            ],
            'description' => [
                'de' => $data['description_de'] ?? '',
                'en' => $data['description_en'] ?? ($data['description_de'] ?? ''),
            ],
            'memberIds' => $data['memberIds'] ?? [],
            'shortName' => $data['shortName'] ?? '',
            'colorToken' => $data['colorToken'] ?? $this->nextColorToken(),
            'archived' => false,
        ]);

        $this->membership->syncUsersFromTeam($team);

        return redirect()
            ->to(locale_route('accounts.teams'))
            ->with('status', 'team-created');
    }

    public function update(Request $request, string $teamId): RedirectResponse
    {
        $this->assertCanManage();
        abort_if($this->teams->findById($teamId) === null, 404);

        $data = $this->validated($request, true);

        $team = $this->teams->upsert([
            'id' => $teamId,
            'name' => [
                'de' => $data['name_de'],
                'en' => $data['name_en'] ?: $data['name_de'],
            ],
            'description' => [
                'de' => $data['description_de'] ?? '',
                'en' => $data['description_en'] ?? ($data['description_de'] ?? ''),
            ],
            'memberIds' => $data['memberIds'] ?? [],
            'shortName' => $data['shortName'] ?? '',
            'colorToken' => $data['colorToken'] ?? 'accent-1',
            'archived' => $request->boolean('archived'),
        ]);

        $this->membership->syncUsersFromTeam($team);

        return redirect()
            ->to(locale_route('accounts.teams'))
            ->with('status', 'team-updated');
    }

    public function destroy(string $teamId): RedirectResponse
    {
        $this->assertCanManage();
        abort_if($this->teams->findById($teamId) === null, 404);

        $this->teams->delete($teamId);
        $this->membership->removeTeamFromUsers($teamId);

        return redirect()
            ->to(locale_route('accounts.teams'))
            ->with('status', 'team-deleted');
    }

    private function assertCanManage(): void
    {
        $actor = $this->auth->user();
        abort_if($actor === null || ! $actor->canManageTeams, 403);
    }

    private function nextColorToken(): string
    {
        $teams = $this->teams->all(true);

        return AccentColors::nextUnused(
            array_map(static fn ($team) => $team->colorToken, $teams),
            count($teams),
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, bool $forUpdate = false): array
    {
        $rules = [
            'name_de' => ['required', 'string', 'max:120'],
            'name_en' => ['nullable', 'string', 'max:120'],
            'description_de' => ['nullable', 'string', 'max:2000'],
            'description_en' => ['nullable', 'string', 'max:2000'],
            'memberIds' => ['nullable', 'array'],
            'memberIds.*' => ['string'],
            'shortName' => ['nullable', 'string', 'max:3'],
            'colorToken' => ['nullable', 'string', 'in:'.implode(',', AccentColors::TEAM_TOKENS)],
        ];

        if ($forUpdate) {
            $rules['archived'] = ['sometimes', 'boolean'];
        }

        return $request->validate($rules);
    }
}
