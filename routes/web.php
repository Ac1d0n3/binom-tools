<?php

use App\Http\Controllers\Tools\GovernanceAiSanitizerController;
use App\Http\Controllers\Tools\PiiPolicyGeneratorController;
use App\Http\Controllers\Tools\SchemaYmlEditorController;
use App\Http\Controllers\Tools\ToolsHomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ToolsHomeController::class, 'index'])->name('tools.home');
Route::get('/tools/governance-ai-sanitizer', [GovernanceAiSanitizerController::class, 'show'])
    ->name('tools.governance-ai-sanitizer');
Route::get('/tools/pii-policy-generator', [PiiPolicyGeneratorController::class, 'show'])
    ->name('tools.pii-policy-generator');
Route::get('/tools/schema-yml-editor', [SchemaYmlEditorController::class, 'show'])
    ->name('tools.schema-yml-editor');
