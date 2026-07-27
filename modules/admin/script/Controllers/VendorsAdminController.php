<?php

namespace App\Http\Controllers\Admin;

use App\Accounts\AccountAuth;
use App\Admin\Content\CatalogJsonWriter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RuntimeException;

class VendorsAdminController extends AdminController
{
    private CatalogJsonWriter $writer;

    public function __construct(AccountAuth $auth)
    {
        parent::__construct($auth);
        $this->writer = new CatalogJsonWriter(base_path('content/catalogs/vendor-resources'));
    }

    public function index(): View
    {
        $this->assertCanManageUsers();
        $doc = $this->safeRead();
        $vendors = $doc['vendors'] ?? [];

        return $this->adminView('admin::catalogs.vendors-index', [
            'vendors' => $vendors,
            'productCount' => is_array($doc['products'] ?? null) ? count($doc['products']) : 0,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->assertCanManageUsers();
        $data = $request->validate([
            'id' => ['required', 'regex:/^[a-z0-9-]+$/', 'max:80'],
            'name_de' => ['required', 'string', 'max:120'],
            'name_en' => ['required', 'string', 'max:120'],
        ]);

        try {
            $doc = $this->safeRead();
            $vendors = is_array($doc['vendors'] ?? null) ? $doc['vendors'] : [];
            if (isset($vendors[$data['id']])) {
                return back()->withErrors(['id' => 'Vendor already exists.'])->withInput();
            }
            $vendors[$data['id']] = ['de' => $data['name_de'], 'en' => $data['name_en']];
            $doc['vendors'] = $vendors;
            $this->writer->write($doc);
        } catch (RuntimeException $e) {
            return back()->withErrors(['id' => $e->getMessage()])->withInput();
        }

        return back()->with('status', 'vendor-saved');
    }

    public function update(Request $request, string $vendorId): RedirectResponse
    {
        $this->assertCanManageUsers();
        abort_unless(preg_match('/^[a-z0-9-]+$/', $vendorId) === 1, 404);
        $data = $request->validate([
            'name_de' => ['required', 'string', 'max:120'],
            'name_en' => ['required', 'string', 'max:120'],
        ]);

        try {
            $doc = $this->safeRead();
            $vendors = is_array($doc['vendors'] ?? null) ? $doc['vendors'] : [];
            abort_unless(isset($vendors[$vendorId]), 404);
            $vendors[$vendorId] = ['de' => $data['name_de'], 'en' => $data['name_en']];
            $doc['vendors'] = $vendors;
            $this->writer->write($doc);
        } catch (RuntimeException $e) {
            return back()->withErrors(['name_en' => $e->getMessage()])->withInput();
        }

        return back()->with('status', 'vendor-saved');
    }

    public function destroy(string $vendorId): RedirectResponse
    {
        $this->assertCanManageUsers();
        abort_unless(preg_match('/^[a-z0-9-]+$/', $vendorId) === 1, 404);
        try {
            $doc = $this->safeRead();
            $vendors = is_array($doc['vendors'] ?? null) ? $doc['vendors'] : [];
            unset($vendors[$vendorId]);
            $doc['vendors'] = $vendors;
            $this->writer->write($doc);
        } catch (RuntimeException $e) {
            return back()->withErrors(['id' => $e->getMessage()]);
        }

        return back()->with('status', 'vendor-deleted');
    }

    /**
     * @return array<string, mixed>
     */
    private function safeRead(): array
    {
        try {
            return $this->writer->read();
        } catch (RuntimeException) {
            return ['vendors' => [], 'products' => []];
        }
    }
}
