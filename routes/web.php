<?php

require_once __DIR__.'/../app/Support/helpers.php';

use App\Http\Controllers\About\AboutController;
use App\Http\Controllers\Accounts\AuthController;
use App\Http\Controllers\Accounts\PlanApiController;
use App\Http\Controllers\Accounts\PlanAttachmentController;
use App\Http\Controllers\Accounts\UserTemplateApiController;
use App\Http\Controllers\Accounts\StoryAclController;
use App\Http\Controllers\Accounts\TeamsController;
use App\Http\Controllers\Accounts\UsersController;
use App\Http\Controllers\Legal\ImpressumController;
use App\Http\Controllers\Legal\PrivacyController;
use App\Http\Controllers\Playbooks\PlaybookController;
use App\Http\Controllers\Playbooks\PlaybookStatsController;
use App\Http\Controllers\SprintPlanner\SprintPlannerController;
use App\Http\Controllers\Tools\DbtDqHistoryGeneratorController;
use App\Http\Controllers\Tools\DbtDqMacroGeneratorController;
use App\Http\Controllers\Tools\DbtDqRulesGeneratorController;
use App\Http\Controllers\Tools\DbtGovernanceMacroGeneratorController;
use App\Http\Controllers\Tools\GovernanceAiSanitizerController;
use App\Http\Controllers\Tools\PiiPolicyGeneratorController;
use App\Http\Controllers\Tools\PiiRecommendGeneratorController;
use App\Http\Controllers\Tools\PiiUnreviewedGateGeneratorController;
use App\Http\Controllers\Tools\PromptStudioConfigController;
use App\Http\Controllers\Tools\PromptStudioController;
use App\Http\Controllers\Tools\SchemaYmlEditorController;
use App\Http\Controllers\Tools\MetaExportGeneratorController;
use App\Http\Controllers\Tools\ToolsLandingController;
use App\Http\Controllers\Tools\ToolsOverviewController;
use Illuminate\Support\Facades\Route;

Route::get('/tools/prompt-studio/config/{file}', [PromptStudioConfigController::class, 'show'])
    ->where('file', '.+')
    ->name('prompt-studio.config');

$registerRoutes = static function (bool $localized): void {
    $name = static fn (string $base): string => $localized ? "localized.{$base}" : $base;

    Route::get('/', [ToolsLandingController::class, 'index'])->name($name('tools.landing'));
    Route::get('/tools', [ToolsOverviewController::class, 'index'])->name($name('tools.overview'));
    Route::get('/playbooks', [PlaybookController::class, 'index'])->name($name('playbooks.index'));
    Route::get('/playbooks/{slug}/engagement', [PlaybookStatsController::class, 'show'])
        ->where('slug', '[a-z0-9-]+')
        ->middleware('throttle:60,1')
        ->name($name('playbooks.stats.show'));
    Route::post('/playbooks/{slug}/engagement/view', [PlaybookStatsController::class, 'view'])
        ->where('slug', '[a-z0-9-]+')
        ->middleware('throttle:30,1')
        ->name($name('playbooks.stats.view'));
    Route::post('/playbooks/{slug}/engagement/like', [PlaybookStatsController::class, 'like'])
        ->where('slug', '[a-z0-9-]+')
        ->middleware('throttle:30,1')
        ->name($name('playbooks.stats.like'));
    Route::get('/playbooks/{slug}', [PlaybookController::class, 'show'])
        ->where('slug', '[a-z0-9-]+')
        ->name($name('playbooks.show'));

    Route::middleware(['accounts.enabled'])->group(static function () use ($name): void {
        Route::get('/login', [AuthController::class, 'showLogin'])->name($name('accounts.login'));
        Route::post('/login', [AuthController::class, 'login'])
            ->middleware('throttle:10,1')
            ->name($name('accounts.login.submit'));
        Route::post('/logout', [AuthController::class, 'logout'])->name($name('accounts.logout'));
    });

    Route::middleware(['accounts.enabled', 'accounts.auth'])->group(static function () use ($name): void {
        Route::get('/account', [AuthController::class, 'profile'])->name($name('accounts.profile'));
        Route::put('/account', [AuthController::class, 'updateProfile'])->name($name('accounts.profile.update'));
        Route::get('/account/users', [UsersController::class, 'index'])->name($name('accounts.users'));
        Route::get('/account/users/create', [UsersController::class, 'create'])->name($name('accounts.users.create'));
        Route::post('/account/users', [UsersController::class, 'store'])->name($name('accounts.users.store'));
        Route::get('/account/users/{userId}/edit', [UsersController::class, 'edit'])
            ->where('userId', '[a-zA-Z0-9_-]+')
            ->name($name('accounts.users.edit'));
        Route::put('/account/users/{userId}', [UsersController::class, 'update'])
            ->where('userId', '[a-zA-Z0-9_-]+')
            ->name($name('accounts.users.update'));
        Route::delete('/account/users/{userId}', [UsersController::class, 'destroy'])
            ->where('userId', '[a-zA-Z0-9_-]+')
            ->name($name('accounts.users.destroy'));
        Route::get('/account/teams', [TeamsController::class, 'index'])->name($name('accounts.teams'));
        Route::get('/account/teams/create', [TeamsController::class, 'create'])->name($name('accounts.teams.create'));
        Route::post('/account/teams', [TeamsController::class, 'store'])->name($name('accounts.teams.store'));
        Route::get('/account/teams/{teamId}/edit', [TeamsController::class, 'edit'])
            ->where('teamId', '[a-zA-Z0-9_-]+')
            ->name($name('accounts.teams.edit'));
        Route::put('/account/teams/{teamId}', [TeamsController::class, 'update'])
            ->where('teamId', '[a-zA-Z0-9_-]+')
            ->name($name('accounts.teams.update'));
        Route::delete('/account/teams/{teamId}', [TeamsController::class, 'destroy'])
            ->where('teamId', '[a-zA-Z0-9_-]+')
            ->name($name('accounts.teams.destroy'));
        Route::get('/account/story-access', [StoryAclController::class, 'index'])->name($name('accounts.story-acl'));
        Route::get('/account/story-access/{slug}/edit', [StoryAclController::class, 'edit'])
            ->where('slug', '[a-z0-9-]+')
            ->name($name('accounts.story-acl.edit'));
        Route::put('/account/story-access/{slug}', [StoryAclController::class, 'update'])
            ->where('slug', '[a-z0-9-]+')
            ->name($name('accounts.story-acl.update'));
        Route::post('/playbooks/{slug}/read', [StoryAclController::class, 'markRead'])
            ->where('slug', '[a-z0-9-]+')
            ->name($name('accounts.playbooks.read'));

        Route::get('/api/sprint-planner/plans', [PlanApiController::class, 'index'])->name($name('accounts.plans.index'));
        Route::get('/api/sprint-planner/plans/{planId}', [PlanApiController::class, 'show'])
            ->where('planId', 'plan_[a-zA-Z0-9_]+')
            ->name($name('accounts.plans.show'));
        Route::post('/api/sprint-planner/plans', [PlanApiController::class, 'store'])->name($name('accounts.plans.store'));
        Route::delete('/api/sprint-planner/plans/{planId}', [PlanApiController::class, 'destroy'])
            ->where('planId', 'plan_[a-zA-Z0-9_]+')
            ->name($name('accounts.plans.destroy'));
        Route::get('/api/sprint-planner/plans/{planId}/history', [PlanApiController::class, 'historyIndex'])
            ->where('planId', 'plan_[a-zA-Z0-9_]+')
            ->name($name('accounts.plans.history.index'));
        Route::get('/api/sprint-planner/plans/{planId}/history/{revisionId}', [PlanApiController::class, 'historyShow'])
            ->where('planId', 'plan_[a-zA-Z0-9_]+')
            ->where('revisionId', 'rev_[a-zA-Z0-9_]+')
            ->name($name('accounts.plans.history.show'));
        Route::post('/api/sprint-planner/plans/{planId}/history/{revisionId}/restore', [PlanApiController::class, 'historyRestore'])
            ->where('planId', 'plan_[a-zA-Z0-9_]+')
            ->where('revisionId', 'rev_[a-zA-Z0-9_]+')
            ->name($name('accounts.plans.history.restore'));
        Route::get('/api/sprint-planner/stories', [PlanApiController::class, 'storyMeta'])->name($name('accounts.plans.stories'));
        Route::get('/api/sprint-planner/user-templates', [UserTemplateApiController::class, 'index'])->name($name('accounts.user-templates.index'));
        Route::get('/api/sprint-planner/user-templates/{templateId}', [UserTemplateApiController::class, 'show'])
            ->where('templateId', 'utpl_[a-zA-Z0-9_]+')
            ->name($name('accounts.user-templates.show'));
        Route::post('/api/sprint-planner/user-templates', [UserTemplateApiController::class, 'store'])->name($name('accounts.user-templates.store'));
        Route::delete('/api/sprint-planner/user-templates/{templateId}', [UserTemplateApiController::class, 'destroy'])
            ->where('templateId', 'utpl_[a-zA-Z0-9_]+')
            ->name($name('accounts.user-templates.destroy'));
        Route::post('/api/sprint-planner/plans/{planId}/attachments', [PlanAttachmentController::class, 'store'])
            ->where('planId', 'plan_[a-zA-Z0-9_]+')
            ->name($name('accounts.plans.attachments.store'));
        Route::get('/api/sprint-planner/plans/{planId}/attachments/{attachmentId}', [PlanAttachmentController::class, 'show'])
            ->where('planId', 'plan_[a-zA-Z0-9_]+')
            ->where('attachmentId', 'att_[a-zA-Z0-9_]+')
            ->name($name('accounts.plans.attachments.show'));
        Route::delete('/api/sprint-planner/plans/{planId}/attachments/{attachmentId}', [PlanAttachmentController::class, 'destroy'])
            ->where('planId', 'plan_[a-zA-Z0-9_]+')
            ->where('attachmentId', 'att_[a-zA-Z0-9_]+')
            ->name($name('accounts.plans.attachments.destroy'));
    });

    Route::get('/sprint-planner', [SprintPlannerController::class, 'index'])->name($name('sprint-planner.index'));
    Route::get('/sprint-planner/templates', [SprintPlannerController::class, 'templates'])->name($name('sprint-planner.templates'));
    Route::get('/sprint-planner/create', [SprintPlannerController::class, 'create'])->name($name('sprint-planner.create'));
    Route::get('/sprint-planner/people', [SprintPlannerController::class, 'people'])->name($name('sprint-planner.people'));
    Route::get('/sprint-planner/{instanceId}/settings', [SprintPlannerController::class, 'settings'])
        ->where('instanceId', 'plan_[a-zA-Z0-9_]+')
        ->name($name('sprint-planner.settings'));
    Route::get('/sprint-planner/{instanceId}', [SprintPlannerController::class, 'show'])
        ->where('instanceId', 'plan_[a-zA-Z0-9_]+')
        ->name($name('sprint-planner.show'));
    Route::get('/about', [AboutController::class, 'show'])->name($name('about.show'));
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
    Route::get('/tools/meta-export-generator', [MetaExportGeneratorController::class, 'show'])
        ->name($name('tools.meta-export-generator'));
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
