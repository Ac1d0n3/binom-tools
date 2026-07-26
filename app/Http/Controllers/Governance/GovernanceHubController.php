<?php

namespace App\Http\Controllers\Governance;

use App\Accounts\AccountAuth;
use App\Governance\GovernanceRadarFeedDisplay;
use App\Governance\GovernanceRadarFeedItemStore;
use App\Governance\GovernanceRadarFeedSync;
use App\Governance\GovernanceRadarItemOverlayStore;
use App\Governance\GovernanceRadarSourceStore;
use App\Http\Controllers\Controller;
use App\Support\ToolsNav;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;
use InvalidArgumentException;

class GovernanceHubController extends Controller
{
    public function __construct(
        private readonly AccountAuth $auth,
        private readonly GovernanceRadarSourceStore $radarSources,
        private readonly GovernanceRadarItemOverlayStore $radarOverlays,
        private readonly GovernanceRadarFeedSync $radarFeedSync,
        private readonly GovernanceRadarFeedDisplay $radarFeedDisplay,
        private readonly GovernanceRadarFeedItemStore $radarFeedItems,
    ) {}

    public function index(): View
    {
        /** @var list<array<string, mixed>> $tools */
        $tools = ToolsNav::withRegisteredRoutes(config('tools.nav', []));
        $toolsById = [];
        foreach ($tools as $tool) {
            $id = is_string($tool['id'] ?? null) ? $tool['id'] : '';
            if ($id !== '') {
                $toolsById[$id] = $tool;
            }
        }

        /** @var array<string, array<string, mixed>> $stacks */
        $stacks = config('vendor-resources.stacks', []);
        /** @var list<array<string, mixed>> $resources */
        $resources = config('vendor-resources.products', []);
        /** @var list<array<string, mixed>> $suppliers */
        $suppliers = config('suppliers.products', []);
        /** @var list<array<string, mixed>> $compliance */
        $compliance = config('compliance.items', []);

        $featuredToolIds = [
            'kpi-requirements-intake',
            'source-scope-builder',
            'mart-design-brief-generator',
            'governance-stack-advisor',
            'pii-dsdr-readiness-checker',
            'decision-brief-generator',
            'vendor-learning-path-builder',
            'stakeholder-matrix',
            'kpi-definition',
            'report-inventory',
            'architecture-fit',
            'impact-effort',
            'pii-policy-generator',
            'pii-recommend-generator',
            'dbt-dq-rules-generator',
            'meta-export-generator',
        ];

        $featuredTools = [];
        foreach ($featuredToolIds as $id) {
            if (isset($toolsById[$id])) {
                $featuredTools[] = $toolsById[$id];
            }
        }

        return view('governance.index', [
            'counts' => [
                'tools' => count($tools),
                'resources' => count($resources),
                'suppliers' => count($suppliers),
                'stacks' => count($stacks),
                'compliance' => count($compliance),
            ],
            'featuredTools' => $featuredTools,
            'journeys' => $this->journeys(),
            'setupWorkflows' => ToolsNav::workflowsWithRegisteredRoutes(config('tools.workflows', [])),
            'toolsById' => $toolsById,
        ]);
    }

    public function radar(): View
    {
        $user = $this->auth->user();
        /** @var list<array<string, mixed>> $sources */
        $sources = config('governance-radar.sources', []);
        $customSources = $user !== null ? $this->radarSources->listFor($user) : [];

        // Defer feed refresh until after the HTML is sent so page open stays fast.
        // Scheduler + manual sync cover bulk freshness; ensureFresh is budgeted + backoffed.
        $feedSyncResult = ['synced' => 0, 'failed' => 0, 'skipped' => 0, 'errors' => [], 'statuses' => []];
        if (! app()->runningUnitTests() && (bool) config('governance-radar.ingest.on_request', true)) {
            $feedSync = $this->radarFeedSync;
            app()->terminating(static function () use ($feedSync, $user): void {
                $feedSync->ensureFresh($user);
            });
        }

        /** @var list<array<string, mixed>> $items */
        $items = config('governance-radar.items', []);
        $overlaysById = $this->radarOverlays->allByItemId();
        $preferDe = app()->getLocale() === 'de';
        $canEnrich = $user !== null && $user->canManageUsers;

        $sourceNames = [];
        $mergedSources = [...$sources, ...$customSources];
        foreach ($mergedSources as $source) {
            $id = is_string($source['id'] ?? null) ? $source['id'] : '';
            if ($id !== '') {
                $sourceNames[$id] = (string) ($source['short_name'] ?? $source['name'] ?? $id);
            }
        }

        $typeMeta = config('governance-radar.type_meta', []);

        $typesPresent = [];
        $stacks = [];
        $regions = [];
        /** @var array<string, array<string, true>> $topicsByType */
        $topicsByType = [];
        $allTopics = [];
        $displayItems = [];
        $seenUrls = [];

        foreach ($items as $item) {
            $displayItem = $this->mergeRadarItemForDisplay($item, $overlaysById[(string) ($item['id'] ?? '')] ?? null, $preferDe);
            $displayItems[] = $displayItem;
            $url = strtolower(trim((string) ($displayItem['url'] ?? '')));
            if ($url !== '') {
                $seenUrls[$url] = true;
            }
            $this->collectRadarFilterFacets($displayItem, $typesPresent, $stacks, $regions, $topicsByType, $allTopics);
        }

        $customAsSources = array_map(static function (array $custom): array {
            return [
                'id' => $custom['id'] ?? '',
                'name' => $custom['name'] ?? '',
                'short_name' => $custom['name'] ?? '',
                'type' => $custom['type'] ?? 'Custom',
                'region' => $custom['region'] ?? 'Global',
                'language' => $custom['language'] ?? 'en',
                'topics' => $custom['topics'] ?? [],
                'feed_url' => $custom['feedUrl'] ?? '',
                'source_url' => $custom['sourceUrl'] ?? '',
                'stack' => [],
            ];
        }, $customSources);

        foreach ($this->radarFeedDisplay->displayItems([...$sources, ...$customAsSources]) as $feedItem) {
            $url = strtolower(trim((string) ($feedItem['url'] ?? '')));
            if ($url !== '' && isset($seenUrls[$url])) {
                continue;
            }
            if ($url !== '') {
                $seenUrls[$url] = true;
            }
            $displayItem = $this->mergeRadarItemForDisplay($feedItem, null, $preferDe);
            $displayItems[] = $displayItem;
            $this->collectRadarFilterFacets($displayItem, $typesPresent, $stacks, $regions, $topicsByType, $allTopics);
        }

        usort($displayItems, static function (array $a, array $b): int {
            return strcmp((string) ($b['published_at'] ?? ''), (string) ($a['published_at'] ?? ''));
        });

        $typeOptions = [];
        foreach ($typeMeta as $type => $meta) {
            if (! isset($typesPresent[$type])) {
                continue;
            }
            $typeOptions[] = [
                'value' => $type,
                'icon' => (string) ($meta['icon'] ?? 'fa-circle'),
                'label' => is_array($meta['label'] ?? null) ? $meta['label'] : ['de' => $type, 'en' => $type],
                'order' => (int) ($meta['order'] ?? 100),
            ];
        }
        usort($typeOptions, static fn (array $a, array $b): int => $a['order'] <=> $b['order']);

        $topicOptions = array_values(array_unique(array_filter($allTopics)));
        sort($topicOptions, SORT_NATURAL | SORT_FLAG_CASE);

        $topicTypeMap = [];
        foreach ($topicOptions as $topic) {
            $topicTypeMap[$topic] = array_keys($topicsByType[$topic] ?? []);
            sort($topicTypeMap[$topic], SORT_NATURAL | SORT_FLAG_CASE);
        }

        $stackOptions = array_values(array_unique(array_filter($stacks)));
        sort($stackOptions, SORT_NATURAL | SORT_FLAG_CASE);

        $regionOptions = array_values(array_unique(array_filter($regions)));
        sort($regionOptions, SORT_NATURAL | SORT_FLAG_CASE);

        $feedErrorStatuses = array_values(array_filter(
            $this->radarFeedItems->syncStatusesBySourceId(),
            static function (array $sync): bool {
                $status = (string) ($sync['last_status'] ?? '');
                if ($status !== 'error') {
                    return false;
                }
                $error = (string) ($sync['last_error'] ?? '');

                // Auth/missing feeds are not actionable sync problems.
                foreach ([401, 403, 404, 410] as $status) {
                    if (str_contains($error, 'HTTP '.$status)) {
                        return false;
                    }
                }

                // Legacy "too large" errors retry via salvage on next sync — hide noise.
                if (str_contains($error, 'maximum allowed size')) {
                    return false;
                }

                return true;
            },
        ));
        $feedSyncErrors = array_map(static function (array $sync): string {
            $sourceId = (string) ($sync['source_id'] ?? 'source');
            $raw = (string) ($sync['last_error'] ?? 'error');
            $short = match (true) {
                str_contains($raw, 'timeout') || str_contains($raw, 'Timeout') => 'timeout',
                default => mb_substr($raw, 0, 80),
            };

            return $sourceId.': '.$short;
        }, $feedErrorStatuses);

        return view('governance.radar', [
            'sources' => $sources,
            'customSources' => $customSources,
            'radarSourcesApiUrl' => $user !== null ? url('/api/governance/radar/sources') : null,
            'radarFeedSyncApiUrl' => $user !== null ? url('/api/governance/radar/feeds/sync') : null,
            'radarOverlaysApiUrl' => $canEnrich ? url('/api/governance/radar/items') : null,
            'canEnrichRadarItems' => $canEnrich,
            'items' => $displayItems,
            'sourceNames' => $sourceNames,
            'typeMeta' => $typeMeta,
            'topicTypeMap' => $topicTypeMap,
            'feedSyncedAt' => $this->radarFeedItems->latestSyncedAt(),
            'feedSyncErrors' => array_slice($feedSyncErrors, 0, 8),
            'feedSyncErrorCount' => count($feedErrorStatuses),
            'feedSyncResult' => $feedSyncResult,
            'filters' => [
                'types' => $typeOptions,
                'topics' => $topicOptions,
                'stacks' => $stackOptions,
                'regions' => $regionOptions,
            ],
        ]);
    }

    public function apiSyncRadarFeeds(): JsonResponse
    {
        $user = $this->auth->user();
        abort_if($user === null, 401);

        $result = $this->radarFeedSync->sync($user);

        return response()->json([
            'synced' => $result['synced'],
            'failed' => $result['failed'],
            'skipped' => $result['skipped'],
            'errors' => $result['errors'],
            'syncedAt' => $this->radarFeedItems->latestSyncedAt(),
            'statuses' => $result['statuses'],
        ]);
    }

    public function apiRadarSources(): JsonResponse
    {
        $user = $this->auth->user();
        abort_if($user === null, 401);

        return response()->json([
            'sources' => $this->radarSources->listFor($user),
        ]);
    }

    public function apiStoreRadarSource(Request $request): JsonResponse
    {
        $user = $this->auth->user();
        abort_if($user === null, 401);

        $data = $request->validate([
            'id' => ['nullable', 'string', 'max:64'],
            'name' => ['required', 'string', 'max:190'],
            'feedUrl' => ['required', 'url', 'max:500'],
            'sourceUrl' => ['nullable', 'url', 'max:500'],
            'type' => ['nullable', 'string', 'max:64'],
            'region' => ['nullable', 'string', 'max:64'],
            'language' => ['nullable', 'string', 'max:16'],
            'cadence' => ['nullable', 'string', 'max:64'],
            'topics' => ['nullable'],
            'note' => ['nullable', 'string', 'max:1000'],
            'active' => ['nullable', 'boolean'],
        ]);

        try {
            $source = $this->radarSources->save($user, $data);
        } catch (InvalidArgumentException $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }

        return response()->json([
            'source' => $source,
            'sources' => $this->radarSources->listFor($user),
        ]);
    }

    public function apiDeleteRadarSource(string $sourceId): JsonResponse
    {
        $user = $this->auth->user();
        abort_if($user === null, 401);

        try {
            $this->radarSources->delete($user, $sourceId);
        } catch (InvalidArgumentException $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }

        return response()->json([
            'sources' => $this->radarSources->listFor($user),
        ]);
    }

    public function apiRadarItemOverlay(string $itemId): JsonResponse
    {
        $user = $this->requireRadarEnrichAdmin();

        try {
            $overlay = $this->radarOverlays->find($itemId);
        } catch (InvalidArgumentException $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }

        return response()->json([
            'overlay' => $overlay,
        ]);
    }

    public function apiStoreRadarItemOverlay(Request $request, string $itemId): JsonResponse
    {
        $user = $this->requireRadarEnrichAdmin();

        $data = $request->validate([
            'titleDe' => ['nullable', 'string', 'max:500'],
            'summaryDe' => ['nullable', 'string', 'max:4000'],
            'recommendedActionDe' => ['nullable', 'string', 'max:2000'],
            'editorialNote' => ['nullable', 'string', 'max:2000'],
            'impact' => ['nullable', 'string', 'max:64'],
        ]);

        try {
            $overlay = $this->radarOverlays->save($user, $itemId, $data);
        } catch (InvalidArgumentException $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }

        return response()->json([
            'overlay' => $overlay,
        ]);
    }

    public function apiDeleteRadarItemOverlay(string $itemId): JsonResponse
    {
        $this->requireRadarEnrichAdmin();

        try {
            $this->radarOverlays->delete($itemId);
        } catch (InvalidArgumentException $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }

        return response()->json([
            'overlay' => null,
        ]);
    }

    /**
     * @param  array<string, mixed>  $displayItem
     * @param  array<string, true>  $typesPresent
     * @param  list<string>  $stacks
     * @param  list<string>  $regions
     * @param  array<string, array<string, true>>  $topicsByType
     * @param  list<string>  $allTopics
     */
    private function collectRadarFilterFacets(
        array $displayItem,
        array &$typesPresent,
        array &$stacks,
        array &$regions,
        array &$topicsByType,
        array &$allTopics,
    ): void {
        $type = (string) ($displayItem['type'] ?? '');
        if ($type !== '') {
            $typesPresent[$type] = true;
        }
        $region = (string) ($displayItem['region'] ?? '');
        if ($region !== '') {
            $regions[] = $region;
        }
        foreach ((array) ($displayItem['stack'] ?? []) as $stack) {
            $stack = (string) $stack;
            if ($stack !== '' && $stack !== 'Alle Stacks') {
                $stacks[] = $stack;
            }
        }
        foreach ((array) ($displayItem['topics'] ?? []) as $topic) {
            if (! is_string($topic) || $topic === '') {
                continue;
            }
            $allTopics[] = $topic;
            if ($type !== '') {
                $topicsByType[$topic][$type] = true;
            }
        }
    }

    /**
     * @param  array<string, mixed>  $item
     * @param  array<string, mixed>|null  $overlay
     * @return array<string, mixed>
     */
    private function mergeRadarItemForDisplay(array $item, ?array $overlay, bool $preferDe): array
    {
        $origin = (string) ($item['origin'] ?? 'example');
        $language = (string) ($item['language'] ?? 'de');
        $hasOverlay = is_array($overlay) && (
            ($overlay['titleDe'] ?? null)
            || ($overlay['summaryDe'] ?? null)
            || ($overlay['recommendedActionDe'] ?? null)
            || ($overlay['editorialNote'] ?? null)
            || ($overlay['impact'] ?? null)
        );

        $useDe = $preferDe && $hasOverlay;
        $title = $useDe && ($overlay['titleDe'] ?? null)
            ? (string) $overlay['titleDe']
            : (string) ($item['title'] ?? '');
        $summary = $useDe && ($overlay['summaryDe'] ?? null)
            ? (string) $overlay['summaryDe']
            : (string) ($item['summary'] ?? '');
        $recommendedAction = $useDe && ($overlay['recommendedActionDe'] ?? null)
            ? (string) $overlay['recommendedActionDe']
            : (string) ($item['recommended_action'] ?? '');
        $impact = ($overlay['impact'] ?? null)
            ? (string) $overlay['impact']
            : (string) ($item['impact'] ?? '');

        return [
            ...$item,
            'title' => $title,
            'summary' => $summary,
            'recommended_action' => $recommendedAction,
            'impact' => $impact,
            'origin' => $origin,
            'language' => $language,
            'original_title' => (string) ($item['title'] ?? ''),
            'original_summary' => (string) ($item['summary'] ?? ''),
            'original_recommended_action' => (string) ($item['recommended_action'] ?? ''),
            'display_language' => $useDe && (($overlay['titleDe'] ?? null) || ($overlay['summaryDe'] ?? null)) ? 'de' : $language,
            'has_overlay' => $hasOverlay,
            'editorial_note' => $overlay['editorialNote'] ?? null,
            'overlay' => $hasOverlay ? [
                'titleDe' => $overlay['titleDe'] ?? null,
                'summaryDe' => $overlay['summaryDe'] ?? null,
                'recommendedActionDe' => $overlay['recommendedActionDe'] ?? null,
                'editorialNote' => $overlay['editorialNote'] ?? null,
                'impact' => $overlay['impact'] ?? null,
            ] : null,
            'enrichable' => $origin !== 'vendor' && $origin !== 'feed',
        ];
    }

    private function requireRadarEnrichAdmin(): \App\Accounts\AccountUser
    {
        $user = $this->auth->user();
        abort_if($user === null, 401);
        abort_if(! $user->canManageUsers, 403);

        return $user;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function journeys(): array
    {
        $route = static fn (string $name, array $parameters = []): ?string => Route::has($name)
            ? locale_route($name, $parameters)
            : null;

        return [
            [
                'id' => 'kpi',
                'icon' => 'fa-gauge-high',
                'label' => ['de' => 'KPI-Anforderungen sammeln', 'en' => 'Collect KPI requirements'],
                'lead' => [
                    'de' => 'Von Geschäftsfrage und Stakeholdern zu KPI Card, Grain, Owner und ersten Mart-Kandidaten.',
                    'en' => 'Move from business question and stakeholders to KPI card, grain, owner, and first mart candidates.',
                ],
                'links' => array_values(array_filter([
                    ['href' => $route('tools.stakeholder-matrix'), 'label' => ['de' => 'Stakeholder & RACI', 'en' => 'Stakeholder & RACI']],
                    ['href' => $route('tools.kpi-requirements-intake'), 'label' => ['de' => 'KPI-Anforderungen', 'en' => 'KPI Requirements Intake']],
                    ['href' => $route('tools.kpi-definition'), 'label' => ['de' => 'KPI Definition Card', 'en' => 'KPI Definition Card']],
                    ['href' => $route('tools.mart-design-brief-generator'), 'label' => ['de' => 'Mart Design Brief', 'en' => 'Mart Design Brief']],
                ], static fn (array $link): bool => is_string($link['href'] ?? null))),
            ],
            [
                'id' => 'supplier',
                'icon' => 'fa-database',
                'label' => ['de' => 'Quelle anbinden', 'en' => 'Scope a source'],
                'lead' => [
                    'de' => 'Supplier auswählen, Kernobjekte verstehen, PII/DSDR prüfen und Skip-Tabellen vor dem Load markieren.',
                    'en' => 'Pick a supplier, understand core entities, review PII/DSDR, and mark skip tables before loading.',
                ],
                'links' => array_values(array_filter([
                    ['href' => $route('tools.source-scope-builder'), 'label' => ['de' => 'Quellen-Scope', 'en' => 'Source Scope Builder']],
                    ['href' => $route('suppliers.index'), 'label' => ['de' => 'Supplier Library', 'en' => 'Supplier library']],
                    ['href' => $route('tools.pii-recommend-generator'), 'label' => ['de' => 'PII Recommend', 'en' => 'PII Recommend']],
                ], static fn (array $link): bool => is_string($link['href'] ?? null))),
            ],
            [
                'id' => 'stack',
                'icon' => 'fa-layer-group',
                'label' => ['de' => 'Stack entscheiden', 'en' => 'Choose a stack'],
                'lead' => [
                    'de' => 'Fabric, Databricks, Snowflake, dbt, BI und Catalog nicht isoliert betrachten, sondern als Governance-Stack.',
                    'en' => 'Treat Fabric, Databricks, Snowflake, dbt, BI, and catalog tools as one governance stack.',
                ],
                'links' => array_values(array_filter([
                    ['href' => $route('tools.governance-stack-advisor'), 'label' => ['de' => 'Stack-Berater', 'en' => 'Governance Stack Advisor']],
                    ['href' => $route('resources.index'), 'label' => ['de' => 'Stack Filter', 'en' => 'Stack filter']],
                    ['href' => $route('tools.architecture-fit'), 'label' => ['de' => 'Architecture Fit', 'en' => 'Architecture fit']],
                    ['href' => $route('tools.vendor-learning-path-builder'), 'label' => ['de' => 'Vendor-Lernpfad', 'en' => 'Learning path']],
                ], static fn (array $link): bool => is_string($link['href'] ?? null))),
            ],
            [
                'id' => 'pii',
                'icon' => 'fa-shield-halved',
                'label' => ['de' => 'PII und DSDR absichern', 'en' => 'Secure PII and DSDR'],
                'lead' => [
                    'de' => 'Personenbezug, Freitext, Kopien, Maskierung und Nachweisbarkeit als frühen Projektpfad behandeln.',
                    'en' => 'Handle personal data, free text, copies, masking, and evidence as an early project path.',
                ],
                'links' => array_values(array_filter([
                    ['href' => $route('tools.pii-dsdr-readiness-checker'), 'label' => ['de' => 'PII/DSDR Readiness', 'en' => 'PII/DSDR Readiness']],
                    ['href' => $route('tools.pii-policy-generator'), 'label' => ['de' => 'PII Policy', 'en' => 'PII Policy']],
                    ['href' => $route('tools.pii-unreviewed-gate-generator'), 'label' => ['de' => 'PII Table Gate', 'en' => 'PII Table Gate']],
                    ['href' => $route('tools.decision-brief-generator'), 'label' => ['de' => 'Entscheidungsbrief', 'en' => 'Decision Brief']],
                ], static fn (array $link): bool => is_string($link['href'] ?? null))),
            ],
        ];
    }
}
