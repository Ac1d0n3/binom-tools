<?php

use App\Http\Controllers\Legal\ImpressumController;
use App\Http\Controllers\Playbooks\PlaybookController;
use App\Http\Controllers\Tools\GovernanceAiSanitizerController;
use App\Http\Controllers\Tools\PiiPolicyGeneratorController;
use App\Http\Controllers\Tools\SchemaYmlEditorController;
use App\Http\Controllers\Tools\ToolsLandingController;
use App\Http\Controllers\Tools\ToolsOverviewController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ToolsLandingController::class, 'index'])->name('tools.landing');
Route::get('/tools', [ToolsOverviewController::class, 'index'])->name('tools.overview');
Route::get('/playbooks', [PlaybookController::class, 'index'])->name('playbooks.index');
Route::get('/playbooks/{slug}', [PlaybookController::class, 'show'])
    ->where('slug', '[a-z0-9-]+')
    ->name('playbooks.show');
Route::get('/impressum', [ImpressumController::class, 'show'])->name('legal.impressum');
Route::get('/tools/governance-ai-sanitizer', [GovernanceAiSanitizerController::class, 'show'])
    ->name('tools.governance-ai-sanitizer');
Route::get('/tools/pii-policy-generator', [PiiPolicyGeneratorController::class, 'show'])
    ->name('tools.pii-policy-generator');
Route::get('/tools/schema-yml-editor', [SchemaYmlEditorController::class, 'show'])
    ->name('tools.schema-yml-editor');
