<?php

namespace App\Http\Controllers\Admin;

use App\Accounts\AccountAuth;
use App\Admin\Content\MarkdownContentWriter;
use App\Admin\Content\PlaybookImageUploader;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RuntimeException;

class StoriesController extends AdminController
{
    private MarkdownContentWriter $writer;

    public function __construct(AccountAuth $auth)
    {
        parent::__construct($auth);
        $this->writer = new MarkdownContentWriter((string) config('admin.stories_path', base_path('content/stories')));
    }

    public function index(): View
    {
        $this->assertCanManageUsers();

        return $this->adminView('admin::content.stories-index', [
            'stories' => $this->writer->listSlugs(),
        ]);
    }

    public function create(): View
    {
        $this->assertCanManageUsers();

        return $this->adminView('admin::content.stories-form', [
            'slug' => '',
            'bodyDe' => "---\ntitle: \"\"\n---\n\n",
            'bodyEn' => "---\ntitle: \"\"\n---\n\n",
            'isNew' => true,
        ]);
    }

    public function edit(string $slug): View
    {
        $this->assertCanManageUsers();
        abort_unless(preg_match('/^[a-z0-9-]+$/', $slug) === 1, 404);

        return $this->adminView('admin::content.stories-form', [
            'slug' => $slug,
            'bodyDe' => $this->writer->read($slug, 'de') ?? '',
            'bodyEn' => $this->writer->read($slug, 'en') ?? '',
            'isNew' => false,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->assertCanManageUsers();
        $data = $request->validate([
            'slug' => ['required', 'regex:/^[a-z0-9-]+$/', 'max:120'],
            'body_de' => ['nullable', 'string'],
            'body_en' => ['nullable', 'string'],
        ]);

        try {
            if (trim((string) ($data['body_de'] ?? '')) !== '') {
                $this->writer->write($data['slug'], 'de', (string) $data['body_de']);
            }
            if (trim((string) ($data['body_en'] ?? '')) !== '') {
                $this->writer->write($data['slug'], 'en', (string) $data['body_en']);
            }
        } catch (RuntimeException $e) {
            return back()->withErrors(['slug' => $e->getMessage()])->withInput();
        }

        return redirect()->to(locale_route('admin.stories.edit', ['slug' => $data['slug']]))->with('status', 'story-saved');
    }

    public function update(Request $request, string $slug): RedirectResponse
    {
        $this->assertCanManageUsers();
        abort_unless(preg_match('/^[a-z0-9-]+$/', $slug) === 1, 404);
        $data = $request->validate([
            'body_de' => ['nullable', 'string'],
            'body_en' => ['nullable', 'string'],
        ]);

        try {
            $this->writer->write($slug, 'de', (string) ($data['body_de'] ?? ''));
            $this->writer->write($slug, 'en', (string) ($data['body_en'] ?? ''));
        } catch (RuntimeException $e) {
            return back()->withErrors(['body_de' => $e->getMessage()])->withInput();
        }

        return back()->with('status', 'story-saved');
    }

    public function destroy(string $slug): RedirectResponse
    {
        $this->assertCanManageUsers();
        abort_unless(preg_match('/^[a-z0-9-]+$/', $slug) === 1, 404);
        $this->writer->delete($slug);

        return redirect()->to(locale_route('admin.stories.index'))->with('status', 'story-deleted');
    }

    public function uploadImage(Request $request): RedirectResponse
    {
        $this->assertCanManageUsers();
        $request->validate([
            'image' => ['required', 'file', 'max:10240', 'mimes:png,jpg,jpeg,gif,webp'],
            'slug' => ['nullable', 'regex:/^[a-z0-9-]+$/'],
        ]);

        $uploader = new PlaybookImageUploader((string) config('admin.playbook_images_path', public_path('images/playbooks')));
        try {
            $file = $request->file('image');
            $result = $uploader->store($file->getClientOriginalName(), $file->getRealPath() ?: $file->getPathname());
        } catch (RuntimeException $e) {
            return back()->withErrors(['image' => $e->getMessage()]);
        }

        $message = 'Uploaded: '.$result['url'].($result['webp'] ? ' (+webp)' : '');

        return back()->with('status', 'image-uploaded')->with('imageUrl', $result['url'])->with('flashDetail', $message);
    }
}
