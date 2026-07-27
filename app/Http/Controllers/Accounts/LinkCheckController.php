<?php

namespace App\Http\Controllers\Accounts;

use App\Accounts\AccountAuth;
use App\Catalog\LinkCheckRunner;
use App\Catalog\LinkCheckStore;
use App\Catalog\LinkInventoryScanner;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LinkCheckController extends Controller
{
    public function __construct(
        private readonly AccountAuth $auth,
        private readonly LinkInventoryScanner $scanner,
        private readonly LinkCheckRunner $runner,
        private readonly LinkCheckStore $store,
    ) {}

    public function index(Request $request): View
    {
        $this->assertCanManage();

        $latest = $this->store->latest();
        $filter = (string) $request->query('filter', 'all');
        $results = is_array($latest['results'] ?? null) ? $latest['results'] : [];

        if (in_array($filter, ['ok', 'redirect', 'broken', 'error'], true)) {
            $results = array_values(array_filter(
                $results,
                static fn (array $row): bool => ($row['bucket'] ?? '') === $filter
            ));
        }

        return view('accounts.link-check', [
            'latest' => $latest,
            'results' => $results,
            'filter' => $filter,
            'inventoryCount' => count($this->scanner->scan()),
        ]);
    }

    public function run(Request $request): RedirectResponse
    {
        $this->assertCanManage();

        $limit = (int) $request->input('limit', 0);
        $inventory = $this->scanner->scan();
        if ($limit > 0) {
            $inventory = array_slice($inventory, 0, $limit);
        }

        $payload = $this->runner->run($inventory);
        $this->store->save($payload);

        return redirect()
            ->to(locale_route('accounts.link-check'))
            ->with('status', 'link-check-done');
    }

    private function assertCanManage(): void
    {
        $actor = $this->auth->user();
        abort_if($actor === null || ! $actor->canManageUsers, 403);
    }
}
