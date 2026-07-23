<?php

namespace App\Http\Controllers\Accounts;

use App\Accounts\AccountAuth;
use App\Accounts\PromptStudioLibraryStore;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InvalidArgumentException;

class PromptStudioLibraryApiController extends Controller
{
    public function __construct(
        private readonly AccountAuth $auth,
        private readonly PromptStudioLibraryStore $library,
    ) {}

    public function show(): JsonResponse
    {
        $user = $this->auth->user();
        abort_if($user === null, 401);

        $bundle = $this->library->loadFor($user);

        return response()->json($bundle);
    }

    public function store(Request $request): JsonResponse
    {
        $user = $this->auth->user();
        abort_if($user === null, 401);

        $data = $request->validate([
            'library' => ['required', 'array'],
            'library.templates' => ['sometimes', 'array'],
            'library.chains' => ['sometimes', 'array'],
            'library.customRoles' => ['sometimes', 'array'],
        ]);

        try {
            $saved = $this->library->saveFor($user, $data['library']);
        } catch (InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

        return response()->json($saved);
    }
}
