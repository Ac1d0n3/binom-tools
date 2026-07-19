<?php

namespace App\Http\Controllers\Accounts;

use App\Accounts\AccountAuth;
use App\Accounts\AccountsConfig;
use App\Accounts\JsonFileStore;
use App\Accounts\PlanStore;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PlanAttachmentController extends Controller
{
    private const MAX_BYTES = 10 * 1024 * 1024;

    /** @var list<string> */
    private const ALLOWED_MIME = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
        'image/svg+xml',
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'text/csv',
    ];

    public function __construct(
        private readonly AccountAuth $auth,
        private readonly PlanStore $plans,
        private readonly AccountsConfig $config,
        private readonly JsonFileStore $store,
    ) {}

    public function store(Request $request, string $planId): JsonResponse
    {
        $user = $this->auth->user();
        abort_if($user === null, 401);

        $plan = $this->plans->find($planId);
        abort_if($plan === null, 404);
        abort_unless($this->plans->canAccess($user, $plan), 403);

        $request->validate([
            'file' => ['required', 'file', 'max:'.(self::MAX_BYTES / 1024)],
            'attachmentId' => ['nullable', 'string', 'max:64'],
        ]);

        $file = $request->file('file');
        abort_if($file === null, 422);

        $mime = (string) ($file->getMimeType() ?: $file->getClientMimeType() ?: 'application/octet-stream');
        $originalName = (string) $file->getClientOriginalName();
        if (! $this->isAllowed($mime, $originalName)) {
            return response()->json(['error' => 'attachment-type'], 422);
        }

        $attachmentId = (string) ($request->input('attachmentId') ?: ('att_'.bin2hex(random_bytes(6))));
        if (! preg_match('/^att_[a-zA-Z0-9_]+$/', $attachmentId)) {
            return response()->json(['error' => 'attachment-invalid-id'], 422);
        }

        $dir = $this->config->planAttachmentsDirectory($planId);
        $this->store->ensureDirectory($dir);
        $safeName = $this->safeFileName($originalName);
        $storedName = $attachmentId.'__'.$safeName;
        $path = $dir.DIRECTORY_SEPARATOR.$storedName;
        $file->move($dir, $storedName);

        $meta = [
            'id' => $attachmentId,
            'name' => $originalName !== '' ? $originalName : $safeName,
            'mime' => $mime,
            'size' => is_file($path) ? (int) filesize($path) : (int) $file->getSize(),
            'kind' => 'file',
            'href' => url('/api/sprint-planner/plans/'.$planId.'/attachments/'.$attachmentId),
            'previewable' => str_starts_with($mime, 'image/'),
            'uploadedAt' => now()->toIso8601String(),
            'uploadedBy' => $user->id,
            'storedName' => $storedName,
        ];

        $index = $this->readIndex($planId);
        $index[$attachmentId] = $meta;
        $this->writeIndex($planId, $index);

        return response()->json(['attachment' => $meta]);
    }

    public function show(string $planId, string $attachmentId): BinaryFileResponse|JsonResponse|Response
    {
        $user = $this->auth->user();
        abort_if($user === null, 401);

        $plan = $this->plans->find($planId);
        abort_if($plan === null, 404);
        abort_unless($this->plans->canAccess($user, $plan), 403);

        $meta = $this->readIndex($planId)[$attachmentId] ?? null;
        abort_if($meta === null, 404);

        $path = $this->config->planAttachmentsDirectory($planId).DIRECTORY_SEPARATOR.(string) ($meta['storedName'] ?? '');
        abort_unless(is_file($path), 404);

        return response()->file($path, [
            'Content-Type' => (string) ($meta['mime'] ?? 'application/octet-stream'),
            'Content-Disposition' => 'inline; filename="'.addslashes((string) ($meta['name'] ?? 'file')).'"',
        ]);
    }

    public function destroy(string $planId, string $attachmentId): JsonResponse
    {
        $user = $this->auth->user();
        abort_if($user === null, 401);

        $plan = $this->plans->find($planId);
        abort_if($plan === null, 404);
        abort_unless($this->plans->canAccess($user, $plan), 403);

        $index = $this->readIndex($planId);
        $meta = $index[$attachmentId] ?? null;
        if ($meta !== null) {
            $path = $this->config->planAttachmentsDirectory($planId).DIRECTORY_SEPARATOR.(string) ($meta['storedName'] ?? '');
            if (is_file($path)) {
                @unlink($path);
            }
            unset($index[$attachmentId]);
            $this->writeIndex($planId, $index);
        }

        return response()->json(['ok' => true]);
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private function readIndex(string $planId): array
    {
        $path = $this->indexPath($planId);
        if (! is_file($path)) {
            return [];
        }
        $data = $this->store->read($path, []);

        return is_array($data) ? $data : [];
    }

    /**
     * @param  array<string, array<string, mixed>>  $index
     */
    private function writeIndex(string $planId, array $index): void
    {
        $dir = $this->config->planAttachmentsDirectory($planId);
        $this->store->ensureDirectory($dir);
        $this->store->write($this->indexPath($planId), $index);
    }

    private function indexPath(string $planId): string
    {
        return $this->config->planAttachmentsDirectory($planId).DIRECTORY_SEPARATOR.'index.json';
    }

    private function isAllowed(string $mime, string $fileName): bool
    {
        if (in_array($mime, self::ALLOWED_MIME, true)) {
            return true;
        }

        return (bool) preg_match('/\.(jpe?g|png|gif|webp|svg|pdf|docx?|pptx?|xlsx?|csv)$/i', $fileName);
    }

    private function safeFileName(string $name): string
    {
        $base = basename(str_replace(["\0", '/', '\\'], '', $name));
        $base = preg_replace('/[^a-zA-Z0-9._-]+/', '_', $base) ?: 'file';

        return substr($base, 0, 120);
    }
}
