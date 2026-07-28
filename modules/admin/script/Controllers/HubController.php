<?php

namespace App\Http\Controllers\Admin;

use App\Accounts\AccountAuth;
use App\Accounts\AccountUser;
use App\Accounts\Contracts\TeamRepositoryInterface;
use App\Accounts\Contracts\UserRepositoryInterface;
use App\Admin\Content\CatalogJsonWriter;
use App\Admin\Content\MarkdownContentWriter;
use App\Admin\Content\StoryDraftTemplates;
use App\Catalog\LinkCheckStore;
use App\Governance\GovernanceRadarFeedDisplay;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use RuntimeException;

class HubController extends AdminController
{
    public function __construct(
        AccountAuth $auth,
        private readonly UserRepositoryInterface $users,
        private readonly TeamRepositoryInterface $teams,
    ) {
        parent::__construct($auth);
    }

    public function index(): View
    {
        $user = $this->user();
        $storiesPath = (string) config('admin.stories_path', base_path('content/stories'));
        $plansPath = (string) config('admin.sprint_plans_path', base_path('content/sprint-plans'));
        $storiesWriter = new MarkdownContentWriter($storiesPath);

        $advisorDoc = $this->readCatalogDoc(base_path('content/catalogs/advisor-recommendations'));
        $radarDoc = $this->readCatalogDoc(base_path('content/catalogs/governance-radar'));
        $vendorDoc = $this->readCatalogDoc(base_path('content/catalogs/vendor-resources'));
        $supplierProducts = $this->readCatalogList(
            base_path('content/catalogs/suppliers'),
            'products.json'
        );
        $glossaryCore = $this->readCatalogList(base_path('content/catalogs/glossary'), 'terms-core.json');
        $glossaryBuzz = $this->readCatalogList(base_path('content/catalogs/glossary'), 'terms-buzzwords.json');
        $advisorItems = array_values(array_filter($advisorDoc['items'] ?? [], 'is_array'));
        $advisorKindCounts = $this->countByKey($advisorItems, 'kind');

        $allUsers = $user->canManageUsers ? $this->users->all() : [];
        $activeUsers = array_values(array_filter(
            $allUsers,
            static fn (AccountUser $row): bool => $row->active
        ));
        $contentManagers = array_values(array_filter(
            $allUsers,
            static fn (AccountUser $row): bool => $row->canManageContent || $row->hasAnyContentAccess()
        ));
        $linkCheck = $this->linkCheckDashboard($user->canManageUsers);

        $radarSources = array_values(array_filter($radarDoc['sources'] ?? [], 'is_array'));
        $radarOwnItems = array_values(array_filter($radarDoc['items'] ?? [], 'is_array'));
        $radarRssItems = app(GovernanceRadarFeedDisplay::class)->displayItems($radarSources);

        return $this->adminView('admin::index', [
            'canManageUsers' => $user->canManageUsers,
            'canManageTeams' => $user->canManageTeams,
            'canManageContent' => $user->canManageContent,
            'contentAreas' => $user->contentAreas,
            // Match sticky counts on each admin index page.
            'storyCount' => count($storiesWriter->listSlugs()),
            'storySeriesCount' => count((new StoryDraftTemplates($storiesWriter))->listSeries()),
            'templateCount' => count((new MarkdownContentWriter($plansPath))->listSlugs()),
            'advisorCount' => count($advisorItems),
            'advisorStoryCount' => (int) ($advisorKindCounts['story'] ?? 0),
            'advisorSeriesCount' => (int) ($advisorKindCounts['series'] ?? 0),
            'advisorSupplierCount' => (int) ($advisorKindCounts['supplier'] ?? 0),
            'advisorVendorCount' => (int) ($advisorKindCounts['vendor'] ?? 0),
            'radarSourceCount' => count($radarSources),
            'radarItemCount' => count($radarOwnItems),
            'radarOwnNewsCount' => count($radarOwnItems),
            'radarRssNewsCount' => count($radarRssItems),
            'vendorCount' => is_array($vendorDoc['vendors'] ?? null) ? count($vendorDoc['vendors']) : 0,
            'vendorProductCount' => count(array_values(array_filter($vendorDoc['products'] ?? [], 'is_array'))),
            'supplierCount' => count($supplierProducts),
            'glossaryCount' => count($glossaryCore) + count($glossaryBuzz),
            'glossaryCoreCount' => count($glossaryCore),
            'glossaryBuzzCount' => count($glossaryBuzz),
            'userCount' => count($allUsers),
            'activeUserCount' => count($activeUsers),
            'contentManagerCount' => count($contentManagers),
            'teamCount' => $user->canManageTeams ? count($this->teams->all()) : 0,
            'linkCheckStatus' => $linkCheck['status'],
            'linkCheckCheckedAt' => $linkCheck['checkedAt'],
            'linkCheckTotal' => $linkCheck['total'],
            'linkCheckOk' => $linkCheck['ok'],
            'linkCheckBroken' => $linkCheck['broken'],
            'linkCheckError' => $linkCheck['error'],
            'linkCheckIssues' => $linkCheck['issues'],
        ]);
    }

    /**
     * @return array{status: string, checkedAt: ?string, total: int, ok: int, broken: int, error: int, issues: int}
     */
    private function linkCheckDashboard(bool $canManageUsers): array
    {
        $empty = [
            'status' => 'none',
            'checkedAt' => null,
            'total' => 0,
            'ok' => 0,
            'broken' => 0,
            'error' => 0,
            'issues' => 0,
        ];
        if (! $canManageUsers) {
            return $empty;
        }

        $latest = app(LinkCheckStore::class)->latest();
        if (! is_array($latest)) {
            return $empty;
        }

        $summary = is_array($latest['summary'] ?? null) ? $latest['summary'] : [];
        $ok = (int) ($summary['ok'] ?? 0);
        $broken = (int) ($summary['broken'] ?? 0);
        $error = (int) ($summary['error'] ?? 0);
        $checkedAtRaw = is_string($latest['checkedAt'] ?? null) ? trim((string) $latest['checkedAt']) : '';
        $checkedAt = null;
        if ($checkedAtRaw !== '') {
            try {
                $checkedAt = Carbon::parse($checkedAtRaw)
                    ->timezone((string) config('app.timezone', 'UTC'))
                    ->format('Y-m-d H:i');
            } catch (\Throwable) {
                $checkedAt = $checkedAtRaw;
            }
        }

        $status = is_string($latest['status'] ?? null) ? (string) $latest['status'] : 'done';
        if ($status === '' && $checkedAt !== null) {
            $status = 'done';
        }

        return [
            'status' => $status !== '' ? $status : 'none',
            'checkedAt' => $checkedAt,
            'total' => (int) ($latest['total'] ?? 0),
            'ok' => $ok,
            'broken' => $broken,
            'error' => $error,
            'issues' => $broken + $error,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function readCatalogDoc(string $directory, string $documentFile = 'document.json'): array
    {
        try {
            return (new CatalogJsonWriter($directory, $documentFile))->read();
        } catch (RuntimeException) {
            return [];
        }
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function readCatalogList(string $directory, string $documentFile): array
    {
        $doc = $this->readCatalogDoc($directory, $documentFile);
        // products.json is a bare list; document catalogs wrap lists in keys.
        if ($doc !== [] && array_is_list($doc)) {
            return array_values(array_filter($doc, 'is_array'));
        }

        return [];
    }

    /**
     * @param  list<array<string, mixed>>  $rows
     * @return array<string, int>
     */
    private function countByKey(array $rows, string $key): array
    {
        $counts = [];
        foreach ($rows as $row) {
            $value = is_string($row[$key] ?? null) ? trim((string) $row[$key]) : '';
            if ($value === '') {
                continue;
            }
            $counts[$value] = ($counts[$value] ?? 0) + 1;
        }

        return $counts;
    }
}
