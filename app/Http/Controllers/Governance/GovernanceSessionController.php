<?php

namespace App\Http\Controllers\Governance;

use App\Accounts\AccountAuth;
use App\Accounts\Contracts\PlanStoreInterface;
use App\Governance\GovernanceSessionStore;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GovernanceSessionController extends Controller
{
    public function __construct(
        private readonly AccountAuth $auth,
        private readonly GovernanceSessionStore $sessions,
        private readonly PlanStoreInterface $plans,
    ) {}

    public function index(Request $request): View
    {
        $user = $this->auth->user();
        abort_if($user === null, 401);

        return view('governance.sessions.index', [
            'sessions' => $this->sessions->listFor($user, $request->boolean('archived')),
            'showArchived' => $request->boolean('archived'),
        ]);
    }

    public function report(string $sessionId): View
    {
        $user = $this->auth->user();
        abort_if($user === null, 401);
        $sessionId = (string) (request()->route('sessionId') ?: $sessionId);
        $session = $this->sessions->findFor($user, $sessionId);
        abort_if($session === null, 404);

        return view('governance.sessions.report', [
            'session' => $session,
            'report' => $this->reportData($session),
        ]);
    }

    public function apiIndex(): JsonResponse
    {
        $user = $this->auth->user();
        abort_if($user === null, 401);

        return response()->json(['sessions' => $this->sessions->listFor($user)]);
    }

    public function apiShow(string $sessionId): JsonResponse
    {
        $user = $this->auth->user();
        abort_if($user === null, 401);
        $sessionId = (string) (request()->route('sessionId') ?: $sessionId);
        $session = $this->sessions->findFor($user, $sessionId);
        abort_if($session === null, 404);

        return response()->json(['session' => $session]);
    }

    public function apiStore(Request $request): JsonResponse
    {
        $user = $this->auth->user();
        abort_if($user === null, 401);

        $data = $request->validate([
            'session' => ['required', 'array'],
            'session.id' => ['sometimes', 'nullable', 'string'],
            'session.title' => ['sometimes', 'nullable', 'string', 'max:190'],
            'session.companyName' => ['sometimes', 'nullable', 'string', 'max:190'],
            'session.projectName' => ['sometimes', 'nullable', 'string', 'max:190'],
            'session.scenario' => ['sometimes', 'nullable', 'string', 'max:32'],
            'session.status' => ['sometimes', 'nullable', 'string', 'max:32'],
            'session.currentStep' => ['sometimes', 'nullable', 'string', 'max:64'],
            'session.payload' => ['sometimes', 'array'],
            'session.reportSnapshot' => ['sometimes', 'array'],
        ]);

        try {
            $session = $this->sessions->save($user, $data['session']);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['session' => $session]);
    }

    public function apiDuplicate(string $sessionId): JsonResponse
    {
        $user = $this->auth->user();
        abort_if($user === null, 401);
        $sessionId = (string) (request()->route('sessionId') ?: $sessionId);

        try {
            $session = $this->sessions->duplicate($user, $sessionId);
        } catch (\InvalidArgumentException) {
            abort(404);
        }

        return response()->json(['session' => $session]);
    }

    public function duplicate(string $sessionId): RedirectResponse
    {
        $user = $this->auth->user();
        abort_if($user === null, 401);
        $sessionId = (string) (request()->route('sessionId') ?: $sessionId);

        try {
            $session = $this->sessions->duplicate($user, $sessionId);
        } catch (\InvalidArgumentException) {
            abort(404);
        }

        return redirect(locale_route('governance.sessions.report', ['sessionId' => $session['id']]))
            ->with('status', 'Governance Session duplicated.');
    }

    public function archive(string $sessionId): RedirectResponse
    {
        $user = $this->auth->user();
        abort_if($user === null, 401);
        $sessionId = (string) (request()->route('sessionId') ?: $sessionId);

        try {
            $this->sessions->archive($user, $sessionId);
        } catch (\InvalidArgumentException) {
            abort(404);
        }

        return redirect(locale_route('governance.sessions.index'))
            ->with('status', 'Governance Session archived.');
    }

    public function apiArchive(string $sessionId): JsonResponse
    {
        $user = $this->auth->user();
        abort_if($user === null, 401);
        $sessionId = (string) (request()->route('sessionId') ?: $sessionId);

        try {
            $session = $this->sessions->archive($user, $sessionId);
        } catch (\InvalidArgumentException) {
            abort(404);
        }

        return response()->json(['session' => $session]);
    }

    public function apiCreatePlan(string $sessionId): JsonResponse
    {
        $user = $this->auth->user();
        abort_if($user === null, 401);
        $sessionId = (string) (request()->route('sessionId') ?: $sessionId);
        $session = $this->sessions->findFor($user, $sessionId);
        abort_if($session === null, 404);

        $plan = $this->plans->save($this->planFromSession($session, $user->id), $user, [
            'action' => 'governance-session',
            'summary' => 'Created from Governance Discovery Session',
        ]);

        return response()->json([
            'plan' => $plan,
            'url' => locale_route('sprint-planner.show', ['instanceId' => $plan['id']]),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function reportData(array $session): array
    {
        $payload = is_array($session['payload'] ?? null) ? $session['payload'] : [];

        return [
            'advisor' => is_array($payload['advisor'] ?? null) ? $payload['advisor'] : [],
            'recommendations' => is_array($payload['recommendations'] ?? null) ? $payload['recommendations'] : [],
            'validation' => is_array($session['validationSummary'] ?? null) ? $session['validationSummary'] : [],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function planFromSession(array $session, string $ownerUserId): array
    {
        $now = now()->toIso8601String();
        $planId = 'plan_'.date('Ymd_His').'_'.bin2hex(random_bytes(3));
        $title = (string) ($session['title'] ?? 'Governance Discovery');
        $sessionId = (string) ($session['id'] ?? '');
        $template = [
            'slug' => 'governance-discovery-session',
            'version' => 1,
            'duration' => 4,
            'unit' => 'weeks',
            'category' => 'governance',
            'author' => 'Binom Tools',
            'recommendedPeopleMin' => 1,
            'recommendedPeopleMax' => 6,
            'capacityHoursPerPersonWeek' => 8,
            'roadmapFamily' => 'governance',
            'roadmapTitle' => 'Governance Discovery',
            'roadmapTrack' => 'governance-discovery',
            'roadmapTrackTitle' => 'Discovery to Decision',
            'roadmapPhase' => 1,
            'roadmapOption' => 'Session Workflow',
            'roadmapFollows' => [],
            'tags' => ['governance', 'discovery', 'decision-brief'],
            'locales' => [
                'de' => [
                    'title' => $title,
                    'description' => 'Workflow aus einer gespeicherten Governance Discovery Session.',
                    'sprints' => $this->workflowSprints('de'),
                ],
                'en' => [
                    'title' => $title,
                    'description' => 'Workflow from a saved Governance Discovery Session.',
                    'sprints' => $this->workflowSprints('en'),
                ],
            ],
            'sprints' => $this->workflowStructure(),
            'errors' => [],
            'warnings' => [],
        ];

        return [
            'id' => $planId,
            'templateSlug' => $template['slug'],
            'templateVersion' => 1,
            'translations' => [
                'de' => ['title' => $title, 'description' => 'Governance Discovery Workflow'],
                'en' => ['title' => $title, 'description' => 'Governance Discovery Workflow'],
            ],
            'startedAt' => $now,
            'status' => 'active',
            'teamIds' => [],
            'teamId' => null,
            'participantIds' => [$ownerUserId],
            'completedTasks' => [],
            'completedDeliverables' => [],
            'fieldValues' => [
                'governanceSessionId' => $sessionId,
                'scenario' => $session['scenario'] ?? 'new',
                'status' => $session['status'] ?? 'draft',
                'validationState' => $session['validationSummary']['state'] ?? 'incomplete',
                'validationScore' => $session['validationSummary']['score'] ?? 0,
            ],
            'sprintNotes' => [
                'session-context' => $this->planNote($session),
            ],
            'customTasks' => [],
            'customDeliverables' => [],
            'customSprints' => [],
            'sprintOverrides' => [],
            'itemOverrides' => [],
            'removedItemKeys' => [],
            'templateSnapshot' => $template,
            'ownerUserId' => $ownerUserId,
            'viewerUserIds' => [],
            'viewerTeamIds' => [],
            'linkedStorySlugs' => $this->storySlugsForSession($session),
            'ephemeral' => false,
            'archived' => false,
            'createdAt' => $now,
            'updatedAt' => $now,
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function workflowStructure(): array
    {
        return [
            ['id' => 'session-context', 'number' => 1, 'tasks' => [['id' => 'review-input'], ['id' => 'fix-validation']], 'deliverables' => [['id' => 'validated-session']]],
            ['id' => 'scope-and-risk', 'number' => 2, 'tasks' => [['id' => 'source-scope'], ['id' => 'pii-dsdr']], 'deliverables' => [['id' => 'risk-backlog']]],
            ['id' => 'model-and-decision', 'number' => 3, 'tasks' => [['id' => 'kpi-mart'], ['id' => 'decision-brief']], 'deliverables' => [['id' => 'printable-report']]],
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function workflowSprints(string $locale): array
    {
        if ($locale === 'de') {
            return [
                ['id' => 'session-context', 'title' => 'Session pruefen', 'tasks' => [['id' => 'review-input', 'title' => 'Eingaben und Empfehlungen pruefen'], ['id' => 'fix-validation', 'title' => 'Validierungswarnungen klaeren']], 'deliverables' => [['id' => 'validated-session', 'title' => 'Entscheidungsreife Session']]],
                ['id' => 'scope-and-risk', 'title' => 'Scope und Risiko', 'tasks' => [['id' => 'source-scope', 'title' => 'Source Scope und Supplier-Entscheidung festhalten'], ['id' => 'pii-dsdr', 'title' => 'PII/DSDR, Access und Retention pruefen']], 'deliverables' => [['id' => 'risk-backlog', 'title' => 'Risk Backlog mit Ownern']]],
                ['id' => 'model-and-decision', 'title' => 'Modell und Entscheidung', 'tasks' => [['id' => 'kpi-mart', 'title' => 'KPI/Mart Design abstimmen'], ['id' => 'decision-brief', 'title' => 'Decision Brief finalisieren']], 'deliverables' => [['id' => 'printable-report', 'title' => 'Druckbarer Governance Report']]],
            ];
        }

        return [
            ['id' => 'session-context', 'title' => 'Review session', 'tasks' => [['id' => 'review-input', 'title' => 'Review inputs and recommendations'], ['id' => 'fix-validation', 'title' => 'Resolve validation warnings']], 'deliverables' => [['id' => 'validated-session', 'title' => 'Decision-ready session']]],
            ['id' => 'scope-and-risk', 'title' => 'Scope and risk', 'tasks' => [['id' => 'source-scope', 'title' => 'Document source scope and supplier decision'], ['id' => 'pii-dsdr', 'title' => 'Review PII/DSDR, access, and retention']], 'deliverables' => [['id' => 'risk-backlog', 'title' => 'Risk backlog with owners']]],
            ['id' => 'model-and-decision', 'title' => 'Model and decision', 'tasks' => [['id' => 'kpi-mart', 'title' => 'Align KPI/mart design'], ['id' => 'decision-brief', 'title' => 'Finalize decision brief']], 'deliverables' => [['id' => 'printable-report', 'title' => 'Printable governance report']]],
        ];
    }

    private function planNote(array $session): string
    {
        $validation = $session['validationSummary'] ?? [];

        return 'Governance Session: '.($session['id'] ?? '')
            ."\nStatus: ".($session['status'] ?? 'draft')
            ."\nScenario: ".($session['scenario'] ?? 'new')
            ."\nValidation: ".($validation['state'] ?? 'incomplete').' ('.($validation['score'] ?? 0).')';
    }

    /**
     * @return list<string>
     */
    private function storySlugsForSession(array $session): array
    {
        $payload = is_array($session['payload'] ?? null) ? $session['payload'] : [];
        $advisor = is_array($payload['advisor'] ?? null) ? $payload['advisor'] : [];
        $goal = (string) ($advisor['goal'] ?? '');
        $domain = (string) ($advisor['domain'] ?? '');
        $slugs = ['end-to-end-governance-architecture'];
        if ($goal === 'pii' || in_array($domain, ['hcm', 'collab', 'finance'], true)) {
            $slugs[] = 'pii-privacy-governance';
            $slugs[] = 'dsdr-governance';
        }
        if ($goal === 'dq') {
            $slugs[] = 'metadata-driven-governance-with-dbt-meta';
        }

        return array_values(array_unique($slugs));
    }
}
