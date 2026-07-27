<?php

namespace App\Http\Controllers\Admin;

use App\Accounts\AccountAuth;
use App\Admin\Content\CatalogJsonWriter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use RuntimeException;

class GlossaryAdminController extends AdminController
{
    private CatalogJsonWriter $writer;

    public function __construct(AccountAuth $auth)
    {
        parent::__construct($auth);
        $this->writer = new CatalogJsonWriter(base_path('content/catalogs/glossary'), 'terms-core.json');
    }

    public function index(Request $request): View
    {
        $this->assertCanManageUsers();
        $terms = $this->safeReadList();
        $q = trim((string) $request->query('q', ''));
        if ($q !== '') {
            $needle = mb_strtolower($q);
            $terms = array_values(array_filter($terms, static function (array $term) use ($needle): bool {
                $hay = mb_strtolower(json_encode($term, JSON_UNESCAPED_UNICODE) ?: '');

                return str_contains($hay, $needle);
            }));
        }

        return $this->adminView('admin::catalogs.glossary-index', [
            'terms' => array_slice($terms, 0, 200),
            'total' => count($this->safeReadList()),
            'q' => $q,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->assertCanManageUsers();
        $data = $request->validate([
            'id' => ['nullable', 'regex:/^[a-z0-9-]+$/', 'max:80'],
            'term_de' => ['required', 'string', 'max:160'],
            'term_en' => ['required', 'string', 'max:160'],
            'definition_de' => ['required', 'string', 'max:4000'],
            'definition_en' => ['required', 'string', 'max:4000'],
            'category' => ['nullable', 'string', 'max:60'],
        ]);

        try {
            $terms = $this->safeReadList();
            $id = $data['id'] ?: Str::slug($data['term_en']);
            foreach ($terms as $term) {
                if (($term['id'] ?? '') === $id) {
                    return back()->withErrors(['id' => 'Term id already exists.'])->withInput();
                }
            }
            $terms[] = [
                'id' => $id,
                'order' => count($terms) + 1,
                'category' => $data['category'] ?: 'data',
                'term' => ['de' => $data['term_de'], 'en' => $data['term_en']],
                'aliases' => [],
                'definition' => ['de' => $data['definition_de'], 'en' => $data['definition_en']],
                'related' => [],
            ];
            $this->writer->write($terms);
        } catch (RuntimeException $e) {
            return back()->withErrors(['term_en' => $e->getMessage()])->withInput();
        }

        return back()->with('status', 'glossary-term-saved');
    }

    public function update(Request $request, string $termId): RedirectResponse
    {
        $this->assertCanManageUsers();
        abort_unless(preg_match('/^[a-z0-9-]+$/', $termId) === 1, 404);
        $data = $request->validate([
            'term_de' => ['required', 'string', 'max:160'],
            'term_en' => ['required', 'string', 'max:160'],
            'definition_de' => ['required', 'string', 'max:4000'],
            'definition_en' => ['required', 'string', 'max:4000'],
            'category' => ['nullable', 'string', 'max:60'],
        ]);

        try {
            $terms = $this->safeReadList();
            $found = false;
            foreach ($terms as $i => $term) {
                if (($term['id'] ?? '') !== $termId) {
                    continue;
                }
                $terms[$i] = array_merge($term, [
                    'category' => $data['category'] ?: ($term['category'] ?? 'data'),
                    'term' => ['de' => $data['term_de'], 'en' => $data['term_en']],
                    'definition' => ['de' => $data['definition_de'], 'en' => $data['definition_en']],
                ]);
                $found = true;
                break;
            }
            abort_unless($found, 404);
            $this->writer->write($terms);
        } catch (RuntimeException $e) {
            return back()->withErrors(['term_en' => $e->getMessage()])->withInput();
        }

        return back()->with('status', 'glossary-term-saved');
    }

    public function destroy(string $termId): RedirectResponse
    {
        $this->assertCanManageUsers();
        abort_unless(preg_match('/^[a-z0-9-]+$/', $termId) === 1, 404);
        try {
            $terms = array_values(array_filter(
                $this->safeReadList(),
                static fn (array $term): bool => ($term['id'] ?? '') !== $termId
            ));
            $this->writer->write($terms);
        } catch (RuntimeException $e) {
            return back()->withErrors(['id' => $e->getMessage()]);
        }

        return back()->with('status', 'glossary-term-deleted');
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function safeReadList(): array
    {
        try {
            $data = $this->writer->read();
        } catch (RuntimeException) {
            return [];
        }

        return array_values(array_filter($data, 'is_array'));
    }
}
