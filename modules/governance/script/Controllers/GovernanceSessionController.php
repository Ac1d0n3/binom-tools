<?php

namespace App\Http\Controllers\Governance;

use App\Accounts\AccountAuth;
use App\Accounts\Contracts\PlanStoreInterface;
use App\Governance\GovernanceDemoWorkspace;
use App\Governance\GovernanceSessionStore;
use App\Http\Controllers\Controller;
use App\Profile\Contracts\WorkspaceStoreInterface;
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
        private readonly GovernanceDemoWorkspace $demo,
        private readonly WorkspaceStoreInterface $workspaces,
    ) {}

    public function index(Request $request): View
    {
        $user = $this->auth->user();
        abort_if($user === null, 401);

        $activeId = $this->workspaces->activeId($user);
        $activeWorkspace = $activeId !== null ? $this->workspaces->find($activeId, $user) : null;

        return view('governance::sessions.index', [
            'sessions' => $this->sessions->listFor($user, $request->boolean('archived')),
            'showArchived' => $request->boolean('archived'),
            'activeWorkspace' => $activeWorkspace,
        ]);
    }

    public function demoReport(): View
    {
        $session = $this->demo->session();

        return view('governance::sessions.report', [
            'session' => $session,
            'report' => $this->reportData($session),
            'isDemo' => true,
        ]);
    }

    public function demoWorkspace(): View
    {
        $session = $this->demo->session();

        return view('governance::sessions.demo-workspace', [
            'session' => $session,
            'workspace' => $this->demo->workspace(),
            'report' => $this->reportData($session),
        ]);
    }

    public function report(string $sessionId): View
    {
        $user = $this->auth->user();
        abort_if($user === null, 401);
        $sessionId = (string) (request()->route('sessionId') ?: $sessionId);
        $session = $this->sessions->findFor($user, $sessionId);
        abort_if($session === null, 404);

        return view('governance::sessions.report', [
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

        return $this->sessionResponse($session);
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

        return $this->sessionResponse($session);
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

        return $this->sessionResponse($session);
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

        return $this->sessionResponse($session);
    }

    private function sessionResponse(array $session): JsonResponse
    {
        return response()->json([
            'session' => $session,
            'reportUrl' => locale_route('governance.sessions.report', ['sessionId' => $session['id']]),
            'sessionsUrl' => locale_route('governance.sessions.index'),
        ]);
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
            'guidance' => is_array($payload['guidance'] ?? null) ? $payload['guidance'] : [],
            'dataQuality' => is_array($payload['dataQuality'] ?? null) ? $payload['dataQuality'] : [],
            'kpis' => is_array($payload['kpis'] ?? null) ? $payload['kpis'] : [],
            'sourceScope' => is_array($payload['sourceScope'] ?? null) ? $payload['sourceScope'] : [],
            'pii' => is_array($payload['pii'] ?? null) ? $payload['pii'] : [],
            'decisionBrief' => is_array($payload['decisionBrief'] ?? null) ? $payload['decisionBrief'] : [],
            'recommendations' => is_array($payload['recommendations'] ?? null) ? $payload['recommendations'] : [],
            'validation' => is_array($session['validationSummary'] ?? null) ? $session['validationSummary'] : [],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function demoSession(): array
    {
        return $this->demo->session();

        $payload = [
            'advisor' => [
                'scenario' => 'extend',
                'goal' => 'dq',
                'domain' => 'erp',
                'platform' => 'fabric',
                'dqMode' => 'report_stabilization',
                'dqLayer' => 'bi',
                'dqIssues' => ['freshness', 'business_rule', 'completeness'],
            ],
            'kpis' => [
                [
                    'name' => 'Net Revenue',
                    'formula' => 'Rechnungsbetrag minus Gutschriften, Stornos und interne Umbuchungen.',
                    'grain' => 'Firma, Kunde, Rechnungsmonat',
                    'owner' => 'Finance Owner',
                    'source' => 'SAP S/4HANA Faktura',
                    'status' => 'agreed',
                ],
                [
                    'name' => 'Offene Forderungen',
                    'formula' => 'Offene Posten gruppiert nach Fälligkeitsklasse und Buchungskreis.',
                    'grain' => 'Firma, Kunde, Beleg, Tag',
                    'owner' => 'Debitoren Lead',
                    'source' => 'SAP FI-AR',
                    'status' => 'review',
                ],
            ],
            'sourceScope' => [
                'supplier' => 'SAP S/4HANA',
                'mustHave' => ['Fakturabelege', 'Kunden', 'Buchungskreis', 'Offene Posten'],
                'optional' => ['Kundenaufträge', 'Kostenstellen', 'Zahlungsbedingungen'],
                'skip' => ['Anhänge', 'lange Freitextnotizen', 'historische Testmandanten'],
                'owners' => ['Finance Owner', 'Platform Owner', 'Datenschutz Review'],
            ],
            'pii' => [
                'fields' => ['Name Rechnungskontakt', 'E-Mail Rechnungskontakt', 'Telefonnummer Debitor'],
                'dsdrKeys' => ['customer_id', 'contact_email'],
                'controls' => ['Maskierung in BI-Extraktionen', 'Retention-Review vor Raw-Load', 'Rollenprüfung für Finance Viewer'],
            ],
            'dataQuality' => [
                'mode' => 'report_stabilization',
                'layer' => 'bi',
                'issueTypes' => ['freshness', 'business_rule', 'completeness'],
                'affectedSources' => ['SAP S/4HANA Faktura', 'SAP FI-AR Offene Posten'],
                'affectedKpis' => ['Net Revenue', 'Offene Forderungen'],
                'affectedReports' => ['Executive Finance Dashboard', 'Monatsabschluss Cockpit'],
                'proposedRules' => [
                    'billing_date darf nicht leer sein',
                    'invoice_amount muss nach Storno-Mapping >= 0 sein',
                    'Dashboard-Refresh darf maximal 24h alt sein',
                    'Jeder offene Posten braucht Buchungskreis und Kunde',
                ],
                'validationFindings' => ['zwei Reports nutzen unterschiedliche Umsatzfilter', 'Refresh-Zeitpunkt wird aktuell nicht dokumentiert'],
                'decisionStatus' => 'decision_ready',
            ],
            'decisionBrief' => [
                'recommendation' => 'Bestehenden Fabric Finance Mart stabilisieren, bevor eine weitere ERP-Quelle angebunden wird.',
                'openQuestions' => ['finale Storno-Logik', 'Owner-Freigabe für PII-Maskierung', 'Cutover-Regel für Monatsabschluss'],
                'nextSprint' => ['Source-Scope-Review', 'DQ-Regeln implementieren', 'Decision-Brief-Freigabe'],
            ],
            'recommendations' => [
                [
                    'title' => 'KPI Requirements Intake',
                    'group' => 'tool',
                    'reason' => 'macht aus Finance-Wünschen belastbare KPI-Karten mit Formel, Grain und Owner',
                    'url' => locale_route('tools.kpi-requirements-intake'),
                ],
                [
                    'title' => 'Source Scope Builder',
                    'group' => 'tool',
                    'reason' => 'klärt Must-have, Skip, PII, Owner und offene Review-Fragen für SAP',
                    'url' => locale_route('tools.source-scope-builder'),
                ],
                [
                    'title' => 'Fabric DQ Rule Generator',
                    'group' => 'tool',
                    'reason' => 'übersetzt die Fehlerklassen in konkrete DQ-Regeln und Checks',
                    'url' => locale_route('tools.fabric-dq-rule-generator'),
                ],
                [
                    'title' => 'Supplier Library: SAP S/4HANA',
                    'group' => 'supplier',
                    'reason' => 'liefert Kernobjekte, PII-Hinweise und typische Finance-Loads',
                    'url' => locale_route('suppliers.show', ['slug' => 'sap-s4hana']),
                ],
                [
                    'title' => 'Vendor Resources & Zertifikate',
                    'group' => 'resources',
                    'reason' => 'sammelt offizielle Doku, Lernpfade und Nachweise für Fabric und Governance',
                    'url' => locale_route('resources.index'),
                ],
            ],
        ];

        return [
            'id' => 'demo_finance_governance',
            'ownerUserId' => 'demo',
            'title' => 'Demo: Finance Governance Discovery',
            'companyName' => 'Acme GmbH',
            'projectName' => 'Management Reporting 2026',
            'scenario' => 'extend',
            'status' => 'decision_ready',
            'currentStep' => 'report',
            'payload' => $payload,
            'validationSummary' => [
                'score' => 96,
                'state' => 'decision_ready',
                'warnings' => [
                    'Cutover-Regel für Monatsabschluss noch fachlich bestätigen.',
                ],
            ],
            'reportSnapshot' => [
                'generatedAt' => '2026-07-26T12:00:00+02:00',
                'advisor' => $payload['advisor'],
                'dataQuality' => $payload['dataQuality'],
                'recommendations' => $payload['recommendations'],
                'validation' => [
                    'score' => 96,
                    'state' => 'decision_ready',
                    'warnings' => [
                        'Cutover-Regel für Monatsabschluss noch fachlich bestätigen.',
                    ],
                ],
            ],
            'archivedAt' => null,
            'createdAt' => '2026-07-26T12:00:00+02:00',
            'updatedAt' => '2026-07-26T12:30:00+02:00',
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
                'dataQualityMode' => $session['payload']['dataQuality']['mode'] ?? '',
                'dataQualityLayer' => $session['payload']['dataQuality']['layer'] ?? '',
                'changeApprovalRequired' => (bool) config('governance.change_approval_required', true),
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
            ['id' => 'quality-and-model', 'number' => 3, 'tasks' => [['id' => 'dq-rules'], ['id' => 'kpi-mart']], 'deliverables' => [['id' => 'quality-gate']]],
            ['id' => 'decision-and-change', 'number' => 4, 'tasks' => [['id' => 'decision-brief'], ['id' => 'change-request']], 'deliverables' => [['id' => 'printable-report']]],
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function workflowSprints(string $locale): array
    {
        if ($locale === 'de') {
            return [
                ['id' => 'session-context', 'title' => 'Session prüfen', 'tasks' => [['id' => 'review-input', 'title' => 'Eingaben und Empfehlungen prüfen'], ['id' => 'fix-validation', 'title' => 'Validierungswarnungen klären']], 'deliverables' => [['id' => 'validated-session', 'title' => 'Entscheidungsreife Session']]],
                ['id' => 'scope-and-risk', 'title' => 'Scope und Risiko', 'tasks' => [['id' => 'source-scope', 'title' => 'Source Scope und Supplier-Entscheidung festhalten'], ['id' => 'pii-dsdr', 'title' => 'PII/DSDR, Access und Retention prüfen']], 'deliverables' => [['id' => 'risk-backlog', 'title' => 'Risk Backlog mit Ownern']]],
                ['id' => 'quality-and-model', 'title' => 'Datenqualität und Modell', 'tasks' => [['id' => 'dq-rules', 'title' => 'DQ Regeln, Schicht und Monitoring festlegen'], ['id' => 'kpi-mart', 'title' => 'KPI/Mart Design abstimmen']], 'deliverables' => [['id' => 'quality-gate', 'title' => 'Quality Gate mit Regeln']]],
                ['id' => 'decision-and-change', 'title' => 'Entscheidung und Change', 'tasks' => [['id' => 'decision-brief', 'title' => 'Decision Brief finalisieren'], ['id' => 'change-request', 'title' => 'Change Request Bedarf prüfen']], 'deliverables' => [['id' => 'printable-report', 'title' => 'Druckbarer Governance Report']]],
            ];
        }

        return [
            ['id' => 'session-context', 'title' => 'Review session', 'tasks' => [['id' => 'review-input', 'title' => 'Review inputs and recommendations'], ['id' => 'fix-validation', 'title' => 'Resolve validation warnings']], 'deliverables' => [['id' => 'validated-session', 'title' => 'Decision-ready session']]],
            ['id' => 'scope-and-risk', 'title' => 'Scope and risk', 'tasks' => [['id' => 'source-scope', 'title' => 'Document source scope and supplier decision'], ['id' => 'pii-dsdr', 'title' => 'Review PII/DSDR, access, and retention']], 'deliverables' => [['id' => 'risk-backlog', 'title' => 'Risk backlog with owners']]],
            ['id' => 'quality-and-model', 'title' => 'Data quality and model', 'tasks' => [['id' => 'dq-rules', 'title' => 'Define DQ rules, layer, and monitoring'], ['id' => 'kpi-mart', 'title' => 'Align KPI/mart design']], 'deliverables' => [['id' => 'quality-gate', 'title' => 'Quality gate with rules']]],
            ['id' => 'decision-and-change', 'title' => 'Decision and change', 'tasks' => [['id' => 'decision-brief', 'title' => 'Finalize decision brief'], ['id' => 'change-request', 'title' => 'Check change request need']], 'deliverables' => [['id' => 'printable-report', 'title' => 'Printable governance report']]],
        ];
    }

    private function planNote(array $session): string
    {
        $validation = $session['validationSummary'] ?? [];
        $dataQuality = is_array($session['payload']['dataQuality'] ?? null) ? $session['payload']['dataQuality'] : [];
        $dqLine = $dataQuality === []
            ? ''
            : "\nData Quality: ".($dataQuality['mode'] ?? '-').' / '.($dataQuality['layer'] ?? '-');

        return 'Governance Session: '.($session['id'] ?? '')
            ."\nStatus: ".($session['status'] ?? 'draft')
            ."\nScenario: ".($session['scenario'] ?? 'new')
            ."\nValidation: ".($validation['state'] ?? 'incomplete').' ('.($validation['score'] ?? 0).')'
            .$dqLine
            ."\nChange approval required: ".(config('governance.change_approval_required', true) ? 'yes' : 'no');
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
            $slugs[] = 'end-to-end-governance-architecture';
        }

        return array_values(array_unique($slugs));
    }
}
