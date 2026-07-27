<?php

namespace App\Http\Controllers\Accounts;

use App\Accounts\AccountAuth;
use App\Accounts\Contracts\GlossaryQuizResultStoreInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InvalidArgumentException;

class GlossaryQuizResultApiController extends Controller
{
    public function __construct(
        private readonly AccountAuth $auth,
        private readonly GlossaryQuizResultStoreInterface $results,
    ) {}

    public function show(): JsonResponse
    {
        $user = $this->auth->user();
        abort_if($user === null, 401);

        return response()->json($this->results->loadFor($user));
    }

    public function store(Request $request): JsonResponse
    {
        $user = $this->auth->user();
        abort_if($user === null, 401);

        $data = $request->validate([
            'score' => ['required', 'integer', 'min:0'],
            'total' => ['required', 'integer', 'min:1'],
            'mode' => ['sometimes', 'string', 'max:32'],
        ]);

        try {
            $saved = $this->results->recordAttempt(
                $user,
                (int) $data['score'],
                (int) $data['total'],
                (string) ($data['mode'] ?? 'mixed'),
            );
        } catch (InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

        return response()->json($saved);
    }
}
