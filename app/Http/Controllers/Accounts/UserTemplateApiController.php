<?php

namespace App\Http\Controllers\Accounts;

use App\Accounts\AccountAuth;
use App\Accounts\UserTemplateStore;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InvalidArgumentException;

class UserTemplateApiController extends Controller
{
    public function __construct(
        private readonly AccountAuth $auth,
        private readonly UserTemplateStore $templates,
    ) {}

    public function index(): JsonResponse
    {
        $user = $this->auth->user();
        abort_if($user === null, 401);

        return response()->json([
            'templates' => $this->templates->listFor($user),
        ]);
    }

    public function show(string $templateId): JsonResponse
    {
        $user = $this->auth->user();
        abort_if($user === null, 401);
        $template = $this->templates->find($templateId);
        abort_if($template === null, 404);
        abort_unless(
            ($template['ownerUserId'] ?? null) === $user->id || $user->canManageUsers,
            403
        );

        return response()->json(['template' => $template]);
    }

    public function store(Request $request): JsonResponse
    {
        $user = $this->auth->user();
        abort_if($user === null, 401);

        $data = $request->validate([
            'template' => ['required', 'array'],
            'template.id' => ['required', 'string'],
        ]);

        try {
            $template = $this->templates->save($data['template'], $user);
        } catch (InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

        return response()->json(['template' => $template]);
    }

    public function destroy(string $templateId): JsonResponse
    {
        $user = $this->auth->user();
        abort_if($user === null, 401);

        try {
            $this->templates->delete($templateId, $user);
        } catch (InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }

        return response()->json(['ok' => true]);
    }
}
