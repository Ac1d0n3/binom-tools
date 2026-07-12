<?php

require_once __DIR__.'/../app/Support/helpers.php';

use App\Http\Controllers\Legal\ImpressumController;
use App\Http\Controllers\Legal\PrivacyController;
use App\Http\Controllers\Playbooks\PlaybookController;
use App\Http\Controllers\Tools\DbtDqHistoryGeneratorController;
use App\Http\Controllers\Tools\DbtDqMacroGeneratorController;
use App\Http\Controllers\Tools\DbtDqRulesGeneratorController;
use App\Http\Controllers\Tools\DbtGovernanceMacroGeneratorController;
use App\Http\Controllers\Tools\GovernanceAiSanitizerController;
use App\Http\Controllers\Tools\PromptStudioConfigController;
use App\Http\Controllers\Tools\PromptStudioController;
use App\Http\Controllers\Tools\PiiPolicyGeneratorController;
use App\Http\Controllers\Tools\PiiRecommendGeneratorController;
use App\Http\Controllers\Tools\PiiUnreviewedGateGeneratorController;
use App\Http\Controllers\Tools\SchemaYmlEditorController;
use App\Http\Controllers\Tools\ToolsLandingController;
use App\Http\Controllers\Tools\ToolsOverviewController;
use Illuminate\Support\Facades\Route;

$registerRoutes = static function (bool $localized): void {
    $name = static fn (string $base): string => $localized ? "localized.{$base}" : $base;

    if (! $localized) {
        Route::get('/prompt-studio/config/{file}', [PromptStudioConfigController::class, 'show'])
            ->where('file', '.+')
            ->name('prompt-studio.config');
    }

    Route::get('/', [ToolsLandingController::class, 'index'])->name($name('tools.landing'));
    Route::get('/tools', [ToolsOverviewController::class, 'index'])->name($name('tools.overview'));
    Route::get('/playbooks', [PlaybookController::class, 'index'])->name($name('playbooks.index'));
    Route::get('/playbooks/{slug}', [PlaybookController::class, 'show'])
        ->where('slug', '[a-z0-9-]+')
        ->name($name('playbooks.show'));
    Route::get('/impressum', [ImpressumController::class, 'show'])->name($name('legal.impressum'));
    Route::get('/datenschutz', [PrivacyController::class, 'show'])->name($name('legal.privacy'));
    Route::get('/tools/dbt-governance-macro-generator', [DbtGovernanceMacroGeneratorController::class, 'show'])
        ->name($name('tools.dbt-governance-macro-generator'));
    Route::get('/tools/pii-recommend-generator', [PiiRecommendGeneratorController::class, 'show'])
        ->name($name('tools.pii-recommend-generator'));
    Route::get('/tools/prompt-studio', [PromptStudioController::class, 'show'])
        ->name($name('tools.prompt-studio'));
    Route::get('/tools/governance-ai-sanitizer', [GovernanceAiSanitizerController::class, 'show'])
        ->name($name('tools.governance-ai-sanitizer'));
    Route::get('/tools/pii-policy-generator', [PiiPolicyGeneratorController::class, 'show'])
        ->name($name('tools.pii-policy-generator'));
    Route::get('/tools/pii-unreviewed-gate-generator', [PiiUnreviewedGateGeneratorController::class, 'show'])
        ->name($name('tools.pii-unreviewed-gate-generator'));
    Route::get('/tools/schema-yml-editor', [SchemaYmlEditorController::class, 'show'])
        ->name($name('tools.schema-yml-editor'));
    Route::get('/tools/dbt-dq-macro-generator', [DbtDqMacroGeneratorController::class, 'show'])
        ->name($name('tools.dbt-dq-macro-generator'));
    Route::get('/tools/dbt-dq-rules-generator', [DbtDqRulesGeneratorController::class, 'show'])
        ->name($name('tools.dbt-dq-rules-generator'));
    Route::get('/tools/dbt-dq-history-generator', [DbtDqHistoryGeneratorController::class, 'show'])
        ->name($name('tools.dbt-dq-history-generator'));
};

$registerRoutes(false);

Route::prefix('{locale}')
    ->where(['locale' => 'de|en'])
    ->group(static function () use ($registerRoutes): void {
        $registerRoutes(true);
    });
