<?php

namespace App\Http\Controllers\Admin;

use App\Accounts\AccountAuth;
use App\Accounts\ContentAreas;
use App\Admin\Content\ContentOwnership;
use App\Admin\Content\MarkdownContentWriter;
use App\Admin\Content\PlaybookImageUploader;
use App\Admin\Content\StoryDraftTemplates;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RuntimeException;

class StoriesController extends AdminController
{
    private MarkdownContentWriter $writer;

    private StoryDraftTemplates $drafts;

    public function __construct(AccountAuth $auth)
    {
        parent::__construct($auth);
        $this->writer = new MarkdownContentWriter((string) config('admin.stories_path', base_path('content/stories')));
        $this->drafts = new StoryDraftTemplates($this->writer);
    }

    public function index(): View
    {
        $user = $this->assertContentArea(ContentAreas::STORIES);
        $stories = $this->writer->listSlugs();
        if (! $user->canManageContent) {
            $stories = array_values(array_filter(
                $stories,
                fn (array $row): bool => $this->storyOwner((string) $row['slug']) === $user->id
            ));
        }

        return $this->adminView('admin::content.stories-index', [
            'stories' => $stories,
        ]);
    }

    public function create(Request $request): View
    {
        $this->assertContentArea(ContentAreas::STORIES);

        $template = (string) $request->query('template', 'single');
        if (! in_array($template, ['single', 'series'], true)) {
            $template = 'single';
        }
        $seriesId = $request->query('series');
        $seriesId = is_string($seriesId) && preg_match('/^[a-z0-9-]+$/', $seriesId) === 1
            ? $seriesId
            : null;

        $draft = $this->drafts->draft($template, $seriesId);
        $bodyDe = old('body_de', $draft['bodyDe']);
        $bodyEn = old('body_en', $draft['bodyEn']);

        return $this->adminView('admin::content.stories-form', [
            'slug' => old('slug', ''),
            'bodyDe' => $bodyDe,
            'bodyEn' => $bodyEn,
            'images' => $this->imagesForStory((string) $bodyDe, (string) $bodyEn),
            'isNew' => true,
            'draftTemplate' => $draft['template'],
            'draftSeriesId' => $draft['seriesId'],
            'draftSeriesLabel' => $draft['seriesLabel'],
            'seriesOptions' => $this->drafts->listSeries(),
        ]);
    }

    public function edit(string $slug): View
    {
        abort_unless(preg_match('/^[a-z0-9-]+$/', $slug) === 1, 404);
        $this->assertContentMutation(ContentAreas::STORIES, $this->storyOwner($slug));

        $bodyDe = $this->writer->read($slug, 'de') ?? '';
        $bodyEn = $this->writer->read($slug, 'en') ?? '';

        return $this->adminView('admin::content.stories-form', [
            'slug' => $slug,
            'bodyDe' => $bodyDe,
            'bodyEn' => $bodyEn,
            'images' => $this->imagesForStory($bodyDe, $bodyEn),
            'isNew' => false,
            'draftTemplate' => null,
            'draftSeriesId' => null,
            'draftSeriesLabel' => null,
            'seriesOptions' => [],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $this->assertContentArea(ContentAreas::STORIES);
        $data = $request->validate([
            'slug' => ['required', 'regex:/^[a-z0-9-]+$/', 'max:120'],
            'body_de' => ['nullable', 'string'],
            'body_en' => ['nullable', 'string'],
        ]);

        try {
            if (trim((string) ($data['body_de'] ?? '')) !== '') {
                $this->writer->write(
                    $data['slug'],
                    'de',
                    ContentOwnership::ensureMarkdownOwner((string) $data['body_de'], $user->id)
                );
            }
            if (trim((string) ($data['body_en'] ?? '')) !== '') {
                $this->writer->write(
                    $data['slug'],
                    'en',
                    ContentOwnership::ensureMarkdownOwner((string) $data['body_en'], $user->id)
                );
            }
        } catch (RuntimeException $e) {
            return back()->withErrors(['slug' => $e->getMessage()])->withInput();
        }

        return redirect()->to(locale_route('admin.stories.edit', ['slug' => $data['slug']]))->with('status', 'story-saved');
    }

    public function update(Request $request, string $slug): RedirectResponse
    {
        abort_unless(preg_match('/^[a-z0-9-]+$/', $slug) === 1, 404);
        $user = $this->assertContentMutation(ContentAreas::STORIES, $this->storyOwner($slug));
        $data = $request->validate([
            'body_de' => ['nullable', 'string'],
            'body_en' => ['nullable', 'string'],
        ]);

        try {
            $this->writer->write(
                $slug,
                'de',
                ContentOwnership::ensureMarkdownOwner((string) ($data['body_de'] ?? ''), $user->id)
            );
            $this->writer->write(
                $slug,
                'en',
                ContentOwnership::ensureMarkdownOwner((string) ($data['body_en'] ?? ''), $user->id)
            );
        } catch (RuntimeException $e) {
            return back()->withErrors(['body_de' => $e->getMessage()])->withInput();
        }

        return back()->with('status', 'story-saved');
    }

    public function destroy(string $slug): RedirectResponse
    {
        abort_unless(preg_match('/^[a-z0-9-]+$/', $slug) === 1, 404);
        $this->assertContentMutation(ContentAreas::STORIES, $this->storyOwner($slug));
        $this->writer->delete($slug);

        return redirect()->to(locale_route('admin.stories.index'))->with('status', 'story-deleted');
    }

    public function uploadImage(Request $request): RedirectResponse
    {
        $slug = (string) $request->input('slug', '');
        if ($slug !== '' && preg_match('/^[a-z0-9-]+$/', $slug) === 1) {
            $this->assertContentMutation(ContentAreas::STORIES, $this->storyOwner($slug));
        } else {
            $this->assertContentArea(ContentAreas::STORIES);
        }
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

    private function storyOwner(string $slug): ?string
    {
        foreach (['en', 'de'] as $locale) {
            $raw = $this->writer->read($slug, $locale);
            if ($raw === null || trim($raw) === '') {
                continue;
            }
            $owner = ContentOwnership::ownerFromMarkdown($raw);
            if ($owner !== null) {
                return $owner;
            }
        }

        return null;
    }

    /**
     * @return list<array{name: string, url: string, previewUrl: string, markdownPath: string, webpUrl: ?string}>
     */
    private function imagesForStory(string $bodyDe, string $bodyEn): array
    {
        $text = $bodyDe."\n".$bodyEn;
        if (! preg_match_all('#(?:/?|\./)?images/playbooks/([a-zA-Z0-9._-]+\.(?:png|jpe?g|gif|webp))#i', $text, $matches)) {
            return [];
        }

        $dir = (string) config('admin.playbook_images_path', public_path('images/playbooks'));
        $seen = [];
        $out = [];
        foreach ($matches[1] as $filename) {
            $filename = (string) $filename;
            $key = strtolower($filename);
            if (isset($seen[$key])) {
                continue;
            }
            $seen[$key] = true;

            $absolute = $dir.DIRECTORY_SEPARATOR.$filename;
            if (! is_file($absolute)) {
                $lower = $dir.DIRECTORY_SEPARATOR.strtolower($filename);
                if (! is_file($lower)) {
                    continue;
                }
                $absolute = $lower;
                $filename = basename($lower);
            }

            $webpName = (string) preg_replace('/\.[^.]+$/', '.webp', $filename);
            $hasWebp = $webpName !== $filename && is_file($dir.DIRECTORY_SEPARATOR.$webpName);
            $url = asset('images/playbooks/'.$filename);
            $out[] = [
                'name' => $filename,
                'url' => $url,
                'previewUrl' => $hasWebp ? asset('images/playbooks/'.$webpName) : $url,
                'markdownPath' => 'images/playbooks/'.$filename,
                'webpUrl' => $hasWebp ? asset('images/playbooks/'.$webpName) : null,
            ];
        }

        return $out;
    }
}
