<?php

namespace App\Http\Controllers\Governance;

use App\Accounts\AccountAuth;
use App\Admin\Content\CatalogJsonWriter;
use App\Catalog\CatalogJsonLoader;
use App\Profile\Contracts\WorkspaceStoreInterface;
use App\Governance\AdvisorContentCardResolver;
use App\Governance\GovernanceRadarFeedDisplay;
use App\Governance\GovernanceRadarFeedItemStore;
use App\Governance\GovernanceRadarFeedSync;
use App\Governance\GovernanceRadarItemOverlayStore;
use App\Governance\GovernanceRadarSourceStore;
use App\Http\Controllers\Controller;
use App\Support\ToolsNav;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\View\View;
use InvalidArgumentException;
use RuntimeException;

class GovernanceHubController extends Controller
{
    public function __construct(
        private readonly AccountAuth $auth,
        private readonly WorkspaceStoreInterface $workspaces,
        private readonly GovernanceRadarSourceStore $radarSources,
        private readonly GovernanceRadarItemOverlayStore $radarOverlays,
        private readonly GovernanceRadarFeedSync $radarFeedSync,
        private readonly GovernanceRadarFeedDisplay $radarFeedDisplay,
        private readonly GovernanceRadarFeedItemStore $radarFeedItems,
    ) {}

    public function index(Request $request): View
    {
        $catalog = $this->hubCatalog();
        $rawTab = is_string($request->query('tab')) ? $request->query('tab') : 'advisor';
        [$tab, $fragment] = $this->resolveHubTab($rawTab);

        return view('governance::index', [
            'counts' => $catalog['counts'],
            'featuredTools' => $catalog['featuredTools'],
            'journeys' => $this->journeys(),
            'setupWorkflows' => ToolsNav::workflowsWithRegisteredRoutes(config('tools.workflows', [])),
            'toolsById' => $catalog['toolsById'],
            'advisorLinks' => $this->advisorLinks(),
            'hubFaqs' => $this->hubFaqs(),
            'stackCards' => $this->stackCards($catalog['toolsById']),
            'discoverySteps' => $this->discoveryCanvasSteps($catalog['toolsById']),
            'featuredSuppliers' => $this->featuredSuppliers(),
            'kpiRelatedTools' => $this->relatedTools($catalog['toolsById'], [
                'kpi-requirements-intake',
                'kpi-definition',
                'stakeholder-matrix',
                'report-inventory',
                'mart-design-brief-generator',
                'source-scope-builder',
            ]),
            'supplierRelatedTools' => $this->relatedTools($catalog['toolsById'], [
                'source-scope-builder',
                'pii-dsdr-readiness-checker',
                'mart-design-brief-generator',
                'kpi-requirements-intake',
            ]),
            'initialTab' => $tab,
            'initialFragment' => $fragment,
        ]);
    }

    public function advisor(): RedirectResponse
    {
        return redirect()->to(locale_route('governance.index').'?tab=advisor', 301);
    }

    public function stacks(): RedirectResponse
    {
        return redirect()->to(locale_route('governance.index').'?tab=guides#stacks', 301);
    }

    public function kpiRequirements(): RedirectResponse
    {
        return redirect()->to(locale_route('governance.index').'?tab=guides#kpi', 301);
    }

    public function supplierDiscovery(): RedirectResponse
    {
        return redirect()->to(locale_route('governance.index').'?tab=guides#supplier', 301);
    }

    public function discoveryCanvas(): RedirectResponse
    {
        return redirect()->to(locale_route('governance.index').'?tab=canvas', 301);
    }

    /**
     * @return array{0: string, 1: string}
     */
    private function resolveHubTab(string $rawTab): array
    {
        $allowed = ['advisor', 'guides', 'canvas', 'tools'];
        $aliases = [
            'hub' => ['advisor', ''],
            'workflows' => ['guides', 'journeys'],
            'decisions' => ['guides', 'decisions'],
            'stacks' => ['guides', 'stacks'],
            'kpi' => ['guides', 'kpi'],
            'supplier' => ['guides', 'supplier'],
        ];

        if (isset($aliases[$rawTab])) {
            return $aliases[$rawTab];
        }

        if (in_array($rawTab, $allowed, true)) {
            return [$rawTab, ''];
        }

        return ['advisor', ''];
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

        return view('governance::radar', [
            'sources' => $sources,
            'customSources' => $customSources,
            'radarSourcesApiUrl' => $user !== null ? url('/api/governance/radar/sources') : null,
            'radarFeedSyncApiUrl' => $user !== null ? url('/api/governance/radar/feeds/sync') : null,
            'radarOverlaysApiUrl' => $canEnrich ? url('/api/governance/radar/items') : null,
            'radarNewsApiUrl' => $canEnrich ? url('/api/governance/radar/news') : null,
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

    public function apiStoreRadarNews(Request $request): JsonResponse
    {
        $this->requireRadarEnrichAdmin();

        $data = $request->validate([
            'title_de' => ['required', 'string', 'max:240'],
            'title_en' => ['required', 'string', 'max:240'],
            'summary_de' => ['nullable', 'string', 'max:2000'],
            'summary_en' => ['nullable', 'string', 'max:2000'],
            'url' => ['required', 'url', 'max:500'],
            'language' => ['required', 'in:de,en'],
            'type' => ['nullable', 'string', 'max:80'],
            'impact' => ['nullable', 'string', 'max:64'],
        ]);

        try {
            $writer = new CatalogJsonWriter(base_path('content/catalogs/governance-radar'));
            $doc = $writer->read();
            $items = array_values(array_filter($doc['items'] ?? [], 'is_array'));
            $id = 'manual-'.Str::slug(substr($data['title_en'], 0, 40)).'-'.bin2hex(random_bytes(3));
            $item = [
                'id' => $id,
                'source_id' => 'binom-editorial',
                'title' => $data['language'] === 'de' ? $data['title_de'] : $data['title_en'],
                'title_i18n' => ['de' => $data['title_de'], 'en' => $data['title_en']],
                'summary' => $data['language'] === 'de' ? ($data['summary_de'] ?? '') : ($data['summary_en'] ?? ''),
                'summary_i18n' => [
                    'de' => $data['summary_de'] ?? '',
                    'en' => $data['summary_en'] ?? '',
                ],
                'url' => $data['url'],
                'language' => $data['language'],
                'type' => $data['type'] ?? 'Binom News',
                'impact' => $data['impact'] ?? 'Prüfen',
                'published_at' => now()->toDateString(),
                'region' => 'DE',
                'topics' => ['Binom News'],
                'stack' => ['Alle Stacks'],
                'recommended_action' => '',
                'origin' => 'manual',
                'ingest' => false,
            ];
            $items[] = $item;
            $doc['items'] = $items;
            $writer->write($doc);
            CatalogJsonLoader::clearCache();
        } catch (RuntimeException $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }

        return response()->json([
            'ok' => true,
            'item' => $item,
        ]);
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
        $guides = static fn (string $hash = ''): string => locale_route('governance.index').'?tab=guides'.($hash !== '' ? '#'.$hash : '');
        $hubTab = static fn (string $tab): string => locale_route('governance.index').'?tab='.$tab;

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
                    ['href' => $guides('kpi'), 'label' => ['de' => 'KPI-Einstieg', 'en' => 'KPI entry']],
                    ['href' => $route('tools.stakeholder-matrix'), 'label' => ['de' => 'Stakeholder & RACI', 'en' => 'Stakeholder & RACI']],
                    ['href' => $route('tools.kpi-requirements-intake'), 'label' => ['de' => 'KPI-Anforderungen', 'en' => 'KPI Requirements Intake']],
                    ['href' => $route('tools.kpi-definition'), 'label' => ['de' => 'KPI Definition Card', 'en' => 'KPI Definition Card']],
                    ['href' => $route('tools.mart-design-brief-generator'), 'label' => ['de' => 'Mart Design Brief', 'en' => 'Mart Design Brief']],
                    ['href' => $route('playbooks.show', ['slug' => 'define-kpi']), 'label' => ['de' => 'KPI Playbook', 'en' => 'KPI playbook']],
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
                    ['href' => $guides('supplier'), 'label' => ['de' => 'Supplier Discovery', 'en' => 'Supplier discovery']],
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
                    ['href' => $guides('stacks'), 'label' => ['de' => 'Stack-Vergleich', 'en' => 'Stack comparison']],
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
                    ['href' => $route('playbooks.show', ['slug' => 'pii-privacy-governance']), 'label' => ['de' => 'PII Playbook', 'en' => 'PII playbook']],
                ], static fn (array $link): bool => is_string($link['href'] ?? null))),
            ],
            [
                'id' => 'bi',
                'icon' => 'fa-chart-column',
                'label' => ['de' => 'Trusted Metrics / BI', 'en' => 'Trusted metrics / BI'],
                'lead' => [
                    'de' => 'Report Inventory → KPI Definition → Grain/Owner → Mart → Formel-Tool → Evidence.',
                    'en' => 'Report inventory → KPI definition → grain/owner → mart → formula tool → evidence.',
                ],
                'links' => array_values(array_filter([
                    ['href' => $route('tools.report-inventory'), 'label' => ['de' => 'Report Inventory', 'en' => 'Report inventory']],
                    ['href' => $route('tools.kpi-definition'), 'label' => ['de' => 'KPI Definition', 'en' => 'KPI definition']],
                    ['href' => $route('playbooks.show', ['slug' => 'define-kpi']), 'label' => ['de' => 'Grain & Owner', 'en' => 'Grain & owner']],
                    ['href' => $route('tools.mart-design-brief-generator'), 'label' => ['de' => 'Mart Design', 'en' => 'Mart design']],
                    ['href' => $route('tools.powerbi-dax-generator'), 'label' => ['de' => 'Power BI DAX', 'en' => 'Power BI DAX']],
                    ['href' => $route('learning-paths.show', ['slug' => 'trusted-metrics']), 'label' => ['de' => 'Trusted Metrics Path', 'en' => 'Trusted metrics path']],
                    ['href' => $route('playbooks.show', ['slug' => 'missing-pieces-trusted-metrics']), 'label' => ['de' => 'Evidence / Missing Piece', 'en' => 'Evidence / missing piece']],
                ], static fn (array $link): bool => is_string($link['href'] ?? null))),
            ],
            [
                'id' => 'collect',
                'icon' => 'fa-table-columns',
                'label' => ['de' => 'Infos sammeln (Workshop)', 'en' => 'Collect infos (Workshop)'],
                'lead' => [
                    'de' => 'Acht Schritte von Stakeholdern bis Decision Brief — mit Markdown-Export für Workshops.',
                    'en' => 'Eight steps from stakeholders to decision brief — with Markdown export for workshops.',
                ],
                'links' => array_values(array_filter([
                    ['href' => $hubTab('canvas'), 'label' => ['de' => 'Workshop', 'en' => 'Workshop']],
                    ['href' => $hubTab('advisor'), 'label' => ['de' => 'Online-Berater', 'en' => 'Online advisor']],
                    ['href' => $guides('kpi'), 'label' => ['de' => 'KPI-Anforderungen', 'en' => 'KPI requirements']],
                    ['href' => $guides('supplier'), 'label' => ['de' => 'Supplier Discovery', 'en' => 'Supplier discovery']],
                ], static fn (array $link): bool => is_string($link['href'] ?? null))),
            ],
        ];
    }

    /**
     * @return array{
     *   counts: array<string, int>,
     *   featuredTools: list<array<string, mixed>>,
     *   toolsById: array<string, array<string, mixed>>
     * }
     */
    private function hubCatalog(): array
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
            'custom-stack-builder',
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

        return [
            'counts' => [
                'tools' => count($tools),
                'resources' => count($resources),
                'suppliers' => count($suppliers),
                'stacks' => count($stacks),
                'compliance' => count($compliance),
            ],
            'featuredTools' => $featuredTools,
            'toolsById' => $toolsById,
        ];
    }

    /**
     * @return array{
     *   tools: array<string, string>,
     *   hubs: array<string, string>,
     *   session: array<string, mixed>
     * }
     */
    private function advisorLinks(): array
    {
        $resolver = new AdvisorContentCardResolver;
        $contentCards = $resolver->resolveContentCards(
            is_array(config('advisor-recommendations')) ? config('advisor-recommendations') : []
        );
        $guidanceStories = $resolver->guidanceStoryUrls($contentCards);

        return [
            'tools' => [
                'governance-stack-advisor' => locale_route('tools.governance-stack-advisor'),
                'custom-stack-builder' => locale_route('tools.custom-stack-builder'),
                'source-scope-builder' => locale_route('tools.source-scope-builder'),
                'kpi-requirements-intake' => locale_route('tools.kpi-requirements-intake'),
                'mart-design-brief-generator' => locale_route('tools.mart-design-brief-generator'),
                'pii-dsdr-readiness-checker' => locale_route('tools.pii-dsdr-readiness-checker'),
                'decision-brief-generator' => locale_route('tools.decision-brief-generator'),
                'vendor-learning-path-builder' => locale_route('tools.vendor-learning-path-builder'),
                'architecture-fit' => locale_route('tools.architecture-fit'),
                'impact-effort' => locale_route('tools.impact-effort'),
                'meta-export-generator' => locale_route('tools.meta-export-generator'),
                'report-inventory' => locale_route('tools.report-inventory'),
                'kpi-definition' => locale_route('tools.kpi-definition'),
                'pii-policy-generator' => locale_route('tools.pii-policy-generator'),
                'pii-recommend-generator' => locale_route('tools.pii-recommend-generator'),
                'schema-yml-editor' => locale_route('tools.schema-yml-editor'),
                'dbt-dq-macro-generator' => locale_route('tools.dbt-dq-macro-generator'),
                'dbt-dq-rules-generator' => locale_route('tools.dbt-dq-rules-generator'),
                'dbt-dq-history-generator' => locale_route('tools.dbt-dq-history-generator'),
                'fabric-pii-governance-pattern-generator' => locale_route('tools.fabric-pii-governance-pattern-generator'),
                'databricks-pii-governance-pattern-generator' => locale_route('tools.databricks-pii-governance-pattern-generator'),
                'unity-catalog-governance-generator' => locale_route('tools.unity-catalog-governance-generator'),
            ],
            'hubs' => [
                'resources' => locale_route('resources.index'),
                'suppliers' => locale_route('suppliers.index'),
                'compliance' => locale_route('compliance.index'),
                'playbooks' => locale_route('playbooks.index'),
                'learningPaths' => locale_route('learning-paths.index'),
                'roles' => locale_route('roles.index'),
                'sprintPlanner' => locale_route('sprint-planner.templates'),
            ],
            'guidance' => array_merge([
                'roadmap' => locale_route('compliance.roadmap'),
                'cdmp' => locale_route('compliance.show', ['slug' => 'cdmp']),
                'cippE' => locale_route('compliance.show', ['slug' => 'cipp-e']),
                'iso27001' => locale_route('compliance.show', ['slug' => 'iso27001-li']),
                'dsbDe' => locale_route('compliance.show', ['slug' => 'dsb-de']),
                'dora' => locale_route('compliance.show', ['slug' => 'dora']),
                'nis2' => locale_route('compliance.show', ['slug' => 'nis2']),
                'bsiC5' => locale_route('compliance.show', ['slug' => 'bsi-c5']),
                'promptStudio' => locale_route('tools.prompt-studio'),
                'aiSanitizer' => locale_route('tools.governance-ai-sanitizer'),
                'toolsOverview' => locale_route('tools.overview'),
                'qlikSetAnalysis' => locale_route('tools.qlik-set-analysis-generator'),
                'unityCatalogTool' => locale_route('tools.unity-catalog-governance-generator'),
                'metaExportTool' => locale_route('tools.meta-export-generator'),
                'guidesStacks' => locale_route('governance.index').'#guides-stacks',
            ], $guidanceStories),
            'contentCards' => $contentCards,
            'session' => [
                'accountsEnabled' => (bool) config('accounts.enabled', false),
                'loggedIn' => $this->auth->user() !== null,
                'apiUrl' => $this->auth->user() !== null ? url('/api/governance/sessions') : null,
                'sessionsUrl' => $this->auth->user() !== null ? locale_route('governance.sessions.index') : null,
                'loginUrl' => (bool) config('accounts.enabled', false) && $this->auth->user() === null
                    ? locale_route('accounts.login')
                    : null,
            ],
            'workspace' => $this->workspaceBootstrap(),
        ];
    }

    /**
     * @return array{
     *   enabled: bool,
     *   activeUrl: ?string,
     *   syncStackUrl: ?string,
     *   savedStacksUrl: ?string,
     *   active: ?array{id: string, name: string, stack: string, customStack: ?array, savedStacks: list<array>}
     * }
     */
    private function workspaceBootstrap(): array
    {
        $user = $this->auth->user();
        if ($user === null || ! config('accounts.enabled', false)) {
            return [
                'enabled' => false,
                'activeUrl' => null,
                'syncStackUrl' => null,
                'savedStacksUrl' => null,
                'active' => null,
            ];
        }

        $activeId = $this->workspaces->activeId($user);
        $workspace = $activeId ? $this->workspaces->find($activeId, $user) : null;

        return [
            'enabled' => true,
            'activeUrl' => locale_route('profile.api.workspace.active'),
            'syncStackUrl' => locale_route('profile.api.workspace.stack'),
            'savedStacksUrl' => locale_route('profile.api.workspace.saved-stacks.store'),
            'active' => $workspace === null ? null : [
                'id' => (string) $workspace['id'],
                'name' => (string) ($workspace['name'] ?? ''),
                'stack' => (string) ($workspace['stack'] ?? 'unknown'),
                'customStack' => is_array($workspace['customStack'] ?? null) ? $workspace['customStack'] : null,
                'savedStacks' => is_array($workspace['savedStacks'] ?? null) ? array_values($workspace['savedStacks']) : [],
            ],
        ];
    }

    /**
     * @return list<array{qDe: string, qEn: string, aDe: string, aEn: string}>
     */
    private function hubFaqs(): array
    {
        return [
            [
                'qDe' => 'Was ist der Governance Hub?',
                'qEn' => 'What is the Governance Hub?',
                'aDe' => 'Ein geführter Einstieg für Data-Governance-Entscheidungen: erst die Frage klären, dann Tools, Supplier, Resources und Playbooks öffnen.',
                'aEn' => 'A guided entry point for data governance decisions: clarify the question first, then open tools, suppliers, resources, and playbooks.',
            ],
            [
                'qDe' => 'Brauche ich Login für den Berater?',
                'qEn' => 'Do I need to sign in for the advisor?',
                'aDe' => 'Nein. Empfehlungen funktionieren ohne Login. Speichern dauerhaft geht mit Account; Demo-Sessions bleiben im Browser.',
                'aEn' => 'No. Recommendations work without sign-in. Permanent save needs an account; demo sessions stay in the browser.',
            ],
            [
                'qDe' => 'Wie starte ich KPI-Anforderungen?',
                'qEn' => 'How do I start KPI requirements?',
                'aDe' => 'Öffne den KPI-Einstieg oder den Workshop, erfasse Stakeholder und KPI-Karten, danach Source Scope und Mart Design.',
                'aEn' => 'Open the KPI entry or Workshop, capture stakeholders and KPI cards, then source scope and mart design.',
            ],
            [
                'qDe' => 'Ersetzt das Rechtsberatung?',
                'qEn' => 'Does this replace legal advice?',
                'aDe' => 'Nein. Die Seite liefert kuratierte Wegweiser und Vorlagen, keinen Ersatz für Rechts- oder Vertragsprüfung.',
                'aEn' => 'No. The site provides curated guides and templates, not a substitute for legal or contract review.',
            ],
        ];
    }

    /**
     * @param  array<string, array<string, mixed>>  $toolsById
     * @return list<array<string, mixed>>
     */
    private function stackCards(array $toolsById): array
    {
        /** @var array<string, array<string, mixed>> $stacks */
        $stacks = config('vendor-resources.stacks', []);
        $startToolsByStack = [
            'modern-data-stack' => ['governance-stack-advisor', 'source-scope-builder', 'dbt-dq-rules-generator'],
            'microsoft-fabric' => ['governance-stack-advisor', 'fabric-pii-governance-pattern-generator', 'pii-dsdr-readiness-checker'],
            'databricks-lakehouse' => ['governance-stack-advisor', 'unity-catalog-governance-generator', 'databricks-pii-governance-pattern-generator'],
            'gcp-analytics' => ['governance-stack-advisor', 'source-scope-builder', 'kpi-requirements-intake'],
            'open-source-stack' => ['governance-stack-advisor', 'schema-yml-editor', 'dbt-dq-rules-generator'],
            'eu-sovereign' => ['governance-stack-advisor', 'pii-dsdr-readiness-checker', 'decision-brief-generator'],
        ];

        $stackCards = [];
        foreach ($stacks as $stackId => $stack) {
            $toolIds = $startToolsByStack[$stackId] ?? ['governance-stack-advisor', 'source-scope-builder', 'decision-brief-generator'];
            $startTools = [];
            foreach ($toolIds as $toolId) {
                if (isset($toolsById[$toolId])) {
                    $startTools[] = $toolsById[$toolId];
                }
            }

            $stackCards[] = [
                'id' => $stackId,
                'label' => $stack['label'] ?? ['de' => $stackId, 'en' => $stackId],
                'description' => $stack['description'] ?? ['de' => '', 'en' => ''],
                'products' => is_array($stack['products'] ?? null) ? $stack['products'] : [],
                'startTools' => $startTools,
            ];
        }

        return $stackCards;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function featuredSuppliers(): array
    {
        /** @var list<array<string, mixed>> $suppliers */
        $suppliers = config('suppliers.products', []);
        $featuredIds = [
            'salesforce', 'hubspot', 'sap-s4hana', 'workday', 'servicenow', 'jira', 'sharepoint', 'personio', 'stripe', 'shopify',
        ];
        $byId = [];
        foreach ($suppliers as $supplier) {
            $id = is_string($supplier['id'] ?? null) ? $supplier['id'] : '';
            if ($id !== '') {
                $byId[$id] = $supplier;
            }
        }

        $featured = [];
        foreach ($featuredIds as $id) {
            if (isset($byId[$id])) {
                $featured[] = $byId[$id];
            }
        }

        return $featured !== [] ? $featured : array_slice($suppliers, 0, 10);
    }

    /**
     * @param  array<string, array<string, mixed>>  $toolsById
     * @param  list<string>  $ids
     * @return list<array<string, mixed>>
     */
    private function relatedTools(array $toolsById, array $ids): array
    {
        $related = [];
        foreach ($ids as $id) {
            if (isset($toolsById[$id])) {
                $related[] = $toolsById[$id];
            }
        }

        return $related;
    }

    /**
     * @param  array<string, array<string, mixed>>  $toolsById
     * @return list<array<string, mixed>>
     */
    private function discoveryCanvasSteps(array $toolsById): array
    {
        $toolHref = static function (string $id) use ($toolsById): ?string {
            $route = is_string($toolsById[$id]['route'] ?? null) ? $toolsById[$id]['route'] : null;
            if ($route === null || ! Route::has($route)) {
                return null;
            }

            return locale_route($route);
        };

        $steps = [
            [
                'id' => 'stakeholders',
                'title' => ['de' => 'Stakeholder erfassen', 'en' => 'Capture stakeholders'],
                'lead' => [
                    'de' => 'Sponsor, Data Owner, Steward, Consumer, Security, Privacy, Platform, BI Owner.',
                    'en' => 'Sponsor, data owner, steward, consumer, security, privacy, platform, BI owner.',
                ],
                'output' => ['de' => 'RACI und Interviewliste', 'en' => 'RACI and interview list'],
                'toolId' => 'stakeholder-matrix',
                'playbooks' => [
                    [
                        'slug' => 'raci-for-data-governance',
                        'label' => ['de' => 'RACI für Data Governance', 'en' => 'RACI for data governance'],
                    ],
                ],
            ],
            [
                'id' => 'business-questions',
                'title' => ['de' => 'Business-Fragen sammeln', 'en' => 'Collect business questions'],
                'lead' => [
                    'de' => 'Welche Entscheidungen sollen besser werden? Welche Reports und kritischen KPIs gibt es?',
                    'en' => 'Which decisions should improve? Which reports and critical KPIs exist?',
                ],
                'output' => ['de' => 'Priorisierte Frageliste', 'en' => 'Prioritized question list'],
                'toolId' => 'report-inventory',
                'playbooks' => [
                    [
                        'slug' => 'define-kpi',
                        'label' => ['de' => 'KPI Definition', 'en' => 'KPI definition'],
                    ],
                    [
                        'slug' => 'before-building-the-first-table',
                        'label' => ['de' => 'Bevor die erste Tabelle entsteht', 'en' => 'Before building the first table'],
                    ],
                ],
            ],
            [
                'id' => 'kpi',
                'title' => ['de' => 'KPI-Anforderungen strukturieren', 'en' => 'Structure KPI requirements'],
                'lead' => [
                    'de' => 'Name, Definition, Formel, Grain, Zeitlogik, Filter, Dimensionen, Owner, Akzeptanzbeispiel.',
                    'en' => 'Name, definition, formula, grain, time logic, filters, dimensions, owner, acceptance example.',
                ],
                'output' => ['de' => 'KPI Cards', 'en' => 'KPI cards'],
                'toolId' => 'kpi-requirements-intake',
                'playbooks' => [
                    [
                        'slug' => 'define-kpi',
                        'label' => ['de' => 'KPI Definition', 'en' => 'KPI definition'],
                    ],
                    [
                        'slug' => 'kpi-metric-governance',
                        'label' => ['de' => 'KPI & Metric Governance', 'en' => 'KPI & metric governance'],
                    ],
                ],
            ],
            [
                'id' => 'sources',
                'title' => ['de' => 'Quellen zuordnen', 'en' => 'Map sources'],
                'lead' => [
                    'de' => 'Supplier, Entitäten, System Owner, Zugriff, Datenfrequenz.',
                    'en' => 'Supplier, entities, system owner, access, data frequency.',
                ],
                'output' => ['de' => 'Source Scope', 'en' => 'Source scope'],
                'toolId' => 'source-scope-builder',
                'playbooks' => [
                    [
                        'slug' => 'before-building-the-first-table',
                        'label' => ['de' => 'Bevor die erste Tabelle entsteht', 'en' => 'Before building the first table'],
                    ],
                ],
            ],
            [
                'id' => 'risk',
                'title' => ['de' => 'Risiko erfassen', 'en' => 'Capture risk'],
                'lead' => [
                    'de' => 'PII, besondere Kategorien, Freitext, Anhänge, Workforce Data, DSDR-Suchkeys, Retention.',
                    'en' => 'PII, special categories, free text, attachments, workforce data, DSDR search keys, retention.',
                ],
                'output' => ['de' => 'PII/DSDR Review Sheet', 'en' => 'PII/DSDR review sheet'],
                'toolId' => 'pii-dsdr-readiness-checker',
                'playbooks' => [
                    [
                        'slug' => 'pii-privacy-governance',
                        'label' => ['de' => 'PII & Privacy Governance', 'en' => 'PII & privacy governance'],
                    ],
                    [
                        'slug' => 'dsdr-governance',
                        'label' => ['de' => 'DSDR Governance', 'en' => 'DSDR governance'],
                    ],
                ],
            ],
            [
                'id' => 'dq',
                'title' => ['de' => 'Datenqualität definieren', 'en' => 'Define data quality'],
                'lead' => [
                    'de' => 'Pflichtfelder, Business Keys, Freshness, Referenzen, erlaubte Werte, Duplikate.',
                    'en' => 'Required fields, business keys, freshness, references, allowed values, duplicates.',
                ],
                'output' => ['de' => 'DQ Rule Backlog', 'en' => 'DQ rule backlog'],
                'toolId' => 'dbt-dq-rules-generator',
                'playbooks' => [
                    [
                        'slug' => 'data-quality-governance',
                        'label' => ['de' => 'Data Quality Governance', 'en' => 'Data quality governance'],
                    ],
                ],
            ],
            [
                'id' => 'mart',
                'title' => ['de' => 'Tabellen- und Mart-Design vorbereiten', 'en' => 'Prepare table and mart design'],
                'lead' => [
                    'de' => 'Grain, Facts, Dimensions, SCD, History-Bedarf, Semantik.',
                    'en' => 'Grain, facts, dimensions, SCD, history needs, semantics.',
                ],
                'output' => ['de' => 'Mart Design Brief', 'en' => 'Mart design brief'],
                'toolId' => 'mart-design-brief-generator',
                'playbooks' => [
                    [
                        'slug' => 'data-architect-role',
                        'label' => ['de' => 'Rolle Data Architect', 'en' => 'Data architect role'],
                    ],
                    [
                        'slug' => 'before-building-the-first-table',
                        'label' => ['de' => 'Bevor die erste Tabelle entsteht', 'en' => 'Before building the first table'],
                    ],
                ],
            ],
            [
                'id' => 'decision',
                'title' => ['de' => 'Entscheidung vorbereiten', 'en' => 'Prepare the decision'],
                'lead' => [
                    'de' => 'Impact, Effort, Risiken, offene Fragen, Pilot-Kandidat.',
                    'en' => 'Impact, effort, risks, open questions, pilot candidate.',
                ],
                'output' => ['de' => 'Decision Brief', 'en' => 'Decision brief'],
                'toolId' => 'decision-brief-generator',
                'playbooks' => [
                    [
                        'slug' => 'choosing-the-simplest-viable-architecture',
                        'label' => ['de' => 'Einfachste tragfähige Architektur', 'en' => 'Simplest viable architecture'],
                    ],
                ],
            ],
        ];

        foreach ($steps as &$step) {
            $toolId = (string) ($step['toolId'] ?? '');
            $step['href'] = $toolHref($toolId);
            $step['tool'] = $toolsById[$toolId] ?? null;
            $playbooks = [];
            foreach ($step['playbooks'] ?? [] as $playbook) {
                $slug = is_string($playbook['slug'] ?? null) ? $playbook['slug'] : '';
                if ($slug === '' || ! Route::has('playbooks.show')) {
                    continue;
                }
                $label = is_array($playbook['label'] ?? null) ? $playbook['label'] : [];
                $playbooks[] = [
                    'slug' => $slug,
                    'href' => locale_route('playbooks.show', ['slug' => $slug]),
                    'label' => [
                        'de' => (string) ($label['de'] ?? $slug),
                        'en' => (string) ($label['en'] ?? $slug),
                    ],
                ];
            }
            $step['playbooks'] = $playbooks;
        }
        unset($step);

        return $steps;
    }
}
