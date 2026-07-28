<?php

namespace App\Http\Controllers\Admin;

use App\Accounts\AccountAuth;
use App\Accounts\ContentAreas;
use App\Admin\Content\CatalogJsonWriter;
use App\Admin\Content\ContentOwnership;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use RuntimeException;

class GlossaryAdminController extends AdminController
{
    private const CATALOG_DIR = 'content/catalogs/glossary';

    private const CORE_FILE = 'terms-core.json';

    private const BUZZ_FILE = 'terms-buzzwords.json';

    public function __construct(AccountAuth $auth)
    {
        parent::__construct($auth);
    }

    public function index(Request $request): View
    {
        $user = $this->assertContentArea(ContentAreas::GLOSSARY);
        $allTerms = $this->safeReadList();
        if (! $user->canManageContent) {
            $allTerms = array_values(array_filter(
                $allTerms,
                static fn (array $row): bool => ContentOwnership::ownerFromRow($row) === $user->id
            ));
        }
        $terms = $allTerms;
        $q = trim((string) $request->query('q', ''));
        if ($q !== '') {
            $needle = mb_strtolower($q);
            $terms = array_values(array_filter($terms, static function (array $term) use ($needle): bool {
                $hay = mb_strtolower(json_encode($term, JSON_UNESCAPED_UNICODE) ?: '');

                return str_contains($hay, $needle);
            }));
        }

        return $this->adminView('admin::catalogs.glossary-index', [
            'terms' => $terms,
            'total' => count($allTerms),
            'q' => $q,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $this->assertContentArea(ContentAreas::GLOSSARY);
        $data = $request->validate([
            'id' => ['nullable', 'regex:/^[a-z0-9-]+$/', 'max:80'],
            'term_de' => ['required', 'string', 'max:160'],
            'term_en' => ['required', 'string', 'max:160'],
            'definition_de' => ['required', 'string', 'max:4000'],
            'definition_en' => ['required', 'string', 'max:4000'],
            'category' => ['nullable', 'string', 'max:60'],
        ]);

        try {
            $id = $data['id'] ?: Str::slug($data['term_en']);
            foreach ($this->safeReadList() as $term) {
                if (($term['id'] ?? '') === $id) {
                    return back()->withErrors(['id' => 'Term id already exists.'])->withInput();
                }
            }

            $core = $this->readFile(self::CORE_FILE);
            $core[] = ContentOwnership::stampRow([
                'id' => $id,
                'order' => count($this->safeReadList()) + 1,
                'category' => $data['category'] ?: 'data',
                'term' => ['de' => $data['term_de'], 'en' => $data['term_en']],
                'aliases' => [],
                'definition' => ['de' => $data['definition_de'], 'en' => $data['definition_en']],
                'related' => [],
            ], $user->id);
            $this->writeFile(self::CORE_FILE, $core);
        } catch (RuntimeException $e) {
            return back()->withErrors(['term_en' => $e->getMessage()])->withInput();
        }

        return back()->with('status', 'glossary-term-saved');
    }

    public function update(Request $request, string $termId): RedirectResponse
    {
        abort_unless(preg_match('/^[a-z0-9-]+$/', $termId) === 1, 404);
        $this->assertContentMutation(ContentAreas::GLOSSARY, $this->termOwner($termId));
        $data = $request->validate([
            'term_de' => ['required', 'string', 'max:160'],
            'term_en' => ['required', 'string', 'max:160'],
            'definition_de' => ['required', 'string', 'max:4000'],
            'definition_en' => ['required', 'string', 'max:4000'],
            'category' => ['nullable', 'string', 'max:60'],
        ]);

        try {
            $file = $this->fileForTerm($termId);
            abort_unless($file !== null, 404);
            $terms = $this->readFile($file);
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
            $this->writeFile($file, $terms);
        } catch (RuntimeException $e) {
            return back()->withErrors(['term_en' => $e->getMessage()])->withInput();
        }

        return back()->with('status', 'glossary-term-saved');
    }

    public function destroy(string $termId): RedirectResponse
    {
        abort_unless(preg_match('/^[a-z0-9-]+$/', $termId) === 1, 404);
        $this->assertContentMutation(ContentAreas::GLOSSARY, $this->termOwner($termId));
        try {
            $file = $this->fileForTerm($termId);
            abort_unless($file !== null, 404);
            $terms = array_values(array_filter(
                $this->readFile($file),
                static fn (array $term): bool => ($term['id'] ?? '') !== $termId
            ));
            $this->writeFile($file, $terms);
        } catch (RuntimeException $e) {
            return back()->withErrors(['id' => $e->getMessage()]);
        }

        return back()->with('status', 'glossary-term-deleted');
    }

    private function termOwner(string $termId): ?string
    {
        foreach ($this->safeReadList() as $term) {
            if (($term['id'] ?? '') !== $termId) {
                continue;
            }

            return ContentOwnership::ownerFromRow($term);
        }

        return null;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function safeReadList(): array
    {
        return array_values(array_merge(
            $this->readFile(self::CORE_FILE),
            $this->readFile(self::BUZZ_FILE),
        ));
    }

    private function fileForTerm(string $termId): ?string
    {
        foreach ([self::CORE_FILE, self::BUZZ_FILE] as $file) {
            foreach ($this->readFile($file) as $term) {
                if (($term['id'] ?? '') === $termId) {
                    return $file;
                }
            }
        }

        return null;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function readFile(string $file): array
    {
        try {
            $data = $this->writerFor($file)->read();
        } catch (RuntimeException) {
            return [];
        }

        return array_values(array_filter($data, 'is_array'));
    }

    /**
     * @param  list<array<string, mixed>>  $terms
     */
    private function writeFile(string $file, array $terms): void
    {
        $this->writerFor($file)->write(array_values($terms));
    }

    private function writerFor(string $file): CatalogJsonWriter
    {
        return new CatalogJsonWriter(base_path(self::CATALOG_DIR), $file);
    }
}
