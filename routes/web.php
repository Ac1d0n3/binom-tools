<?php

require_once __DIR__.'/../app/Support/helpers.php';

use App\Http\Controllers\About\AboutController;
use App\Http\Controllers\Accounts\AuthController;
use App\Http\Controllers\Accounts\PlanApiController;
use App\Http\Controllers\Accounts\PlanAttachmentController;
use App\Http\Controllers\Accounts\UserTemplateApiController;
use App\Http\Controllers\Accounts\PromptStudioLibraryApiController;
use App\Http\Controllers\Accounts\StoryAclController;
use App\Http\Controllers\Accounts\TeamsController;
use App\Http\Controllers\Accounts\UsersController;
use App\Http\Controllers\Legal\ImpressumController;
use App\Http\Controllers\Legal\PrivacyController;
use App\Http\Controllers\Playbooks\PlaybookController;
use App\Http\Controllers\Playbooks\PlaybookStatsController;
use App\Http\Controllers\SprintPlanner\SprintPlannerController;
use App\Http\Controllers\Tools\ArchitectureFitController;
use App\Http\Controllers\Tools\BiPythonToolkitController;
use App\Http\Controllers\Tools\DbtDqHistoryGeneratorController;
use App\Http\Controllers\Tools\DbtDqMacroGeneratorController;
use App\Http\Controllers\Tools\DbtDqRulesGeneratorController;
use App\Http\Controllers\Tools\DbtGovernanceMacroGeneratorController;
use App\Http\Controllers\Tools\DatabricksDqPatternGeneratorController;
use App\Http\Controllers\Tools\DatabricksPiiGovernancePatternGeneratorController;
use App\Http\Controllers\Tools\FabricDqPatternGeneratorController;
use App\Http\Controllers\Tools\FabricPiiGovernancePatternGeneratorController;
use App\Http\Controllers\Tools\GovernanceAiSanitizerController;
use App\Http\Controllers\Tools\ImpactEffortController;
use App\Http\Controllers\Tools\KpiDefinitionController;
use App\Http\Controllers\Tools\MetaExportGeneratorController;
use App\Http\Controllers\Tools\PiiPolicyGeneratorController;
use App\Http\Controllers\Tools\PiiRecommendGeneratorController;
use App\Http\Controllers\Tools\PiiUnreviewedGateGeneratorController;
use App\Http\Controllers\Tools\PromptStudioConfigController;
use App\Http\Controllers\Tools\PromptStudioController;
use App\Http\Controllers\Tools\ReportInventoryController;
use App\Http\Controllers\Tools\SchemaYmlEditorController;
use App\Http\Controllers\Tools\StakeholderMatrixController;
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

    Route::middleware(['accounts.enabled', 'accounts.auth'])->group(static function () use ($name, $localized): void {
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

        // JSON APIs stay locale-free: {locale} would be injected as the first
        // controller argument (Laravel spreads route params by position).
        if (! $localized) {
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
            Route::get('/api/prompt-studio/library', [PromptStudioLibraryApiController::class, 'show'])->name($name('accounts.prompt-studio.library.show'));
            Route::post('/api/prompt-studio/library', [PromptStudioLibraryApiController::class, 'store'])->name($name('accounts.prompt-studio.library.store'));
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
        }
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
    Route::get('/tools/stakeholder-matrix', [StakeholderMatrixController::class, 'show'])
        ->name($name('tools.stakeholder-matrix'));
    Route::get('/tools/report-inventory', [ReportInventoryController::class, 'show'])
        ->name($name('tools.report-inventory'));
    Route::get('/tools/kpi-definition', [KpiDefinitionController::class, 'show'])
        ->name($name('tools.kpi-definition'));
    Route::get('/tools/bi-python-toolkit', [BiPythonToolkitController::class, 'show'])
        ->name($name('tools.bi-python-toolkit'));
    Route::get('/tools/bi-python-toolkit/download/{file}', [BiPythonToolkitController::class, 'download'])
        ->where('file', 'bi-kpi-export|qlik-app-inventory|readme')
        ->name($name('tools.bi-python-toolkit.download'));
    Route::view('/tools/qlik-set-analysis-generator', 'tools.qlik-set-analysis-generator.show')
        ->name($name('tools.qlik-set-analysis-generator'));
    Route::get('/tools/architecture-fit', [ArchitectureFitController::class, 'show'])
        ->name($name('tools.architecture-fit'));
    Route::get('/tools/impact-effort', [ImpactEffortController::class, 'show'])
        ->name($name('tools.impact-effort'));
    Route::get('/tools/dbt-dq-macro-generator', [DbtDqMacroGeneratorController::class, 'show'])
        ->name($name('tools.dbt-dq-macro-generator'));
    Route::get('/tools/dbt-dq-rules-generator', [DbtDqRulesGeneratorController::class, 'show'])
        ->name($name('tools.dbt-dq-rules-generator'));
    Route::get('/tools/dbt-dq-history-generator', [DbtDqHistoryGeneratorController::class, 'show'])
        ->name($name('tools.dbt-dq-history-generator'));
    Route::get('/tools/fabric-dq-pattern-generator', [FabricDqPatternGeneratorController::class, 'show'])
        ->name($name('tools.fabric-dq-pattern-generator'));
    Route::get('/tools/databricks-dq-pattern-generator', [DatabricksDqPatternGeneratorController::class, 'show'])
        ->name($name('tools.databricks-dq-pattern-generator'));
    Route::get('/tools/fabric-pii-governance-pattern-generator', [FabricPiiGovernancePatternGeneratorController::class, 'show'])
        ->name($name('tools.fabric-pii-governance-pattern-generator'));
    Route::get('/tools/databricks-pii-governance-pattern-generator', [DatabricksPiiGovernancePatternGeneratorController::class, 'show'])
        ->name($name('tools.databricks-pii-governance-pattern-generator'));

    Route::view('/tools/fabric-dq-rule-generator', 'tools.lakehouse-pattern-tool', [
        'platform' => 'fabric',
        'toolId' => 'fabric-dq-rule-generator',
        'pageTitle' => 'Fabric DQ Rule Generator',
        'titleKey' => 'lakehouseDq.fabricDqRule.pageTitle',
        'leadKey' => 'lakehouseDq.fabricDqRule.lead',
        'introKey' => 'lakehouseDq.fabricDqRule.howto.intro',
        'tipKey' => 'lakehouseDq.fabricDqRule.howto.tip',
        'table' => 'sales.orders_curated',
        'keys' => 'order_id',
        'required' => 'order_id, customer_id, order_date, amount',
        'freshness' => 'updated_at',
        'pii' => 'customer_email',
        'owner' => 'data-owner-sales',
        'selectedPattern' => 'dq',
        'patterns' => ['dq' => 'DQ Checks'],
        'sqlTitleKey' => 'lakehouseDq.fabric.output.sql',
        'notebookTitleKey' => 'lakehouseDq.fabric.output.notebook',
    ])->name($name('tools.fabric-dq-rule-generator'));
    Route::view('/tools/fabric-notebook-snippet-generator', 'tools.lakehouse-pattern-tool', [
        'platform' => 'fabric',
        'toolId' => 'fabric-notebook-snippet-generator',
        'pageTitle' => 'Fabric Notebook Snippet Generator',
        'titleKey' => 'lakehouseDq.fabricNotebook.pageTitle',
        'leadKey' => 'lakehouseDq.fabricNotebook.lead',
        'introKey' => 'lakehouseDq.fabricNotebook.howto.intro',
        'tipKey' => 'lakehouseDq.fabricNotebook.howto.tip',
        'table' => 'sales.orders_curated',
        'keys' => 'order_id',
        'required' => 'order_id, customer_id, order_date, amount',
        'freshness' => 'updated_at',
        'pii' => 'customer_email, customer_name',
        'owner' => 'data-owner-sales',
        'selectedPattern' => 'dq',
        'patterns' => ['dq' => 'DQ Notebook', 'delta' => 'Delta Notebook', 'scd2' => 'SCD2 Notebook', 'governance' => 'Governance Notebook'],
        'sqlTitleKey' => 'lakehouseDq.fabric.output.sql',
        'notebookTitleKey' => 'lakehouseDq.fabric.output.notebook',
    ])->name($name('tools.fabric-notebook-snippet-generator'));
    Route::view('/tools/fabric-pipeline-checklist-generator', 'tools.lakehouse-pattern-tool', [
        'platform' => 'fabric',
        'toolId' => 'fabric-pipeline-checklist-generator',
        'pageTitle' => 'Fabric Pipeline Checklist Generator',
        'titleKey' => 'lakehouseDq.fabricPipeline.pageTitle',
        'leadKey' => 'lakehouseDq.fabricPipeline.lead',
        'introKey' => 'lakehouseDq.fabricPipeline.howto.intro',
        'tipKey' => 'lakehouseDq.fabricPipeline.howto.tip',
        'table' => 'sales.orders_curated',
        'keys' => 'order_id',
        'required' => 'order_id, customer_id, order_date, amount',
        'freshness' => 'updated_at',
        'pii' => 'customer_email, customer_name',
        'owner' => 'data-owner-sales',
        'selectedPattern' => 'pipeline',
        'patterns' => ['pipeline' => 'Pipeline Checklist', 'delta' => 'Delta Load', 'governance' => 'Governance Gate'],
        'sqlTitleKey' => 'lakehouseDq.fabric.output.sql',
        'notebookTitleKey' => 'lakehouseDq.fabric.output.notebook',
    ])->name($name('tools.fabric-pipeline-checklist-generator'));
    Route::view('/tools/fabric-semantic-model-guardrails', 'tools.lakehouse-pattern-tool', [
        'platform' => 'fabric',
        'toolId' => 'fabric-semantic-model-guardrails',
        'pageTitle' => 'Fabric Semantic Model Guardrails',
        'titleKey' => 'lakehouseDq.fabricSemantic.pageTitle',
        'leadKey' => 'lakehouseDq.fabricSemantic.lead',
        'introKey' => 'lakehouseDq.fabricSemantic.howto.intro',
        'tipKey' => 'lakehouseDq.fabricSemantic.howto.tip',
        'table' => 'sales.orders_semantic',
        'keys' => 'order_id',
        'required' => 'order_id, customer_id, amount, order_date',
        'freshness' => 'updated_at',
        'pii' => 'customer_email, customer_name',
        'owner' => 'semantic-model-owner',
        'selectedPattern' => 'semantic',
        'patterns' => ['semantic' => 'Semantic Guardrails', 'governance' => 'Governance Gate'],
        'sqlTitleKey' => 'lakehouseDq.fabric.output.sql',
        'notebookTitleKey' => 'lakehouseDq.fabric.output.notebook',
    ])->name($name('tools.fabric-semantic-model-guardrails'));
    Route::view('/tools/databricks-dq-expectation-generator', 'tools.lakehouse-pattern-tool', [
        'platform' => 'databricks',
        'toolId' => 'databricks-dq-expectation-generator',
        'pageTitle' => 'Databricks DQ Expectation Generator',
        'titleKey' => 'lakehouseDq.databricksDqExpectation.pageTitle',
        'leadKey' => 'lakehouseDq.databricksDqExpectation.lead',
        'introKey' => 'lakehouseDq.databricksDqExpectation.howto.intro',
        'tipKey' => 'lakehouseDq.databricksDqExpectation.howto.tip',
        'table' => 'main.sales.orders_curated',
        'keys' => 'order_id',
        'required' => 'order_id, customer_id, order_date, amount',
        'freshness' => 'updated_at',
        'pii' => 'customer_email',
        'owner' => 'data-owner-sales',
        'selectedPattern' => 'dq',
        'patterns' => ['dq' => 'DLT Expectations'],
        'sqlTitleKey' => 'lakehouseDq.databricks.output.sql',
        'notebookTitleKey' => 'lakehouseDq.databricks.output.notebook',
    ])->name($name('tools.databricks-dq-expectation-generator'));
    Route::view('/tools/databricks-dbt-on-databricks-generator', 'tools.lakehouse-pattern-tool', [
        'platform' => 'databricks',
        'toolId' => 'databricks-dbt-on-databricks-generator',
        'pageTitle' => 'Databricks dbt-on-Databricks Generator',
        'titleKey' => 'lakehouseDq.databricksDbt.pageTitle',
        'leadKey' => 'lakehouseDq.databricksDbt.lead',
        'introKey' => 'lakehouseDq.databricksDbt.howto.intro',
        'tipKey' => 'lakehouseDq.databricksDbt.howto.tip',
        'table' => 'main.sales.orders_curated',
        'keys' => 'order_id',
        'required' => 'order_id, customer_id, order_date, amount',
        'freshness' => 'updated_at',
        'pii' => 'customer_email, customer_name',
        'owner' => 'analytics-engineering',
        'selectedPattern' => 'dbt',
        'patterns' => ['dbt' => 'dbt on Databricks', 'dq' => 'DLT Expectations', 'governance' => 'Unity Catalog Gate'],
        'sqlTitleKey' => 'lakehouseDq.databricks.output.sql',
        'notebookTitleKey' => 'lakehouseDq.databricks.output.notebook',
    ])->name($name('tools.databricks-dbt-on-databricks-generator'));
    Route::view('/tools/unity-catalog-governance-generator', 'tools.lakehouse-pattern-tool', [
        'platform' => 'databricks',
        'toolId' => 'unity-catalog-governance-generator',
        'pageTitle' => 'Unity Catalog Governance Generator',
        'titleKey' => 'lakehouseDq.unityCatalog.pageTitle',
        'leadKey' => 'lakehouseDq.unityCatalog.lead',
        'introKey' => 'lakehouseDq.unityCatalog.howto.intro',
        'tipKey' => 'lakehouseDq.unityCatalog.howto.tip',
        'table' => 'main.customer.customer_app_curated',
        'keys' => 'customer_id',
        'required' => 'customer_id, consent_status, updated_at',
        'freshness' => 'updated_at',
        'pii' => 'customer_email, customer_name, phone',
        'owner' => 'data-owner-customer',
        'selectedPattern' => 'governance',
        'patterns' => ['governance' => 'Unity Catalog Gate'],
        'sqlTitleKey' => 'lakehouseDq.databricks.output.sql',
        'notebookTitleKey' => 'lakehouseDq.databricks.output.notebook',
    ])->name($name('tools.unity-catalog-governance-generator'));
    Route::view('/tools/delta-load-scd-pattern-generator', 'tools.lakehouse-pattern-tool', [
        'platform' => 'databricks',
        'toolId' => 'delta-load-scd-pattern-generator',
        'pageTitle' => 'Delta Load / SCD Pattern Generator',
        'titleKey' => 'lakehouseDq.deltaScd.pageTitle',
        'leadKey' => 'lakehouseDq.deltaScd.lead',
        'introKey' => 'lakehouseDq.deltaScd.howto.intro',
        'tipKey' => 'lakehouseDq.deltaScd.howto.tip',
        'table' => 'main.sales.orders_curated',
        'keys' => 'order_id',
        'required' => 'order_id, customer_id, order_date, amount',
        'freshness' => 'updated_at',
        'pii' => 'customer_email',
        'owner' => 'data-owner-sales',
        'selectedPattern' => 'delta',
        'patterns' => ['delta' => 'Delta MERGE', 'scd2' => 'SCD2'],
        'sqlTitleKey' => 'lakehouseDq.databricks.output.sql',
        'notebookTitleKey' => 'lakehouseDq.databricks.output.notebook',
    ])->name($name('tools.delta-load-scd-pattern-generator'));
    Route::view('/tools/pureview-scan-generator', 'tools.pureview-generator', [
        'toolId' => 'pureview-scan-generator',
        'pageTitle' => 'PureView Scan Generator',
        'titleKey' => 'pureview.scan.pageTitle',
        'leadKey' => 'pureview.scan.lead',
        'introKey' => 'pureview.scan.howto.intro',
        'tipKey' => 'pureview.scan.howto.tip',
        'asset' => 'sales-prod-warehouse',
        'domain' => 'sales-domain',
        'owner' => 'data-owner-sales',
        'steward' => 'data-steward-sales',
        'columns' => 'order_id, customer_id, customer_email, amount, updated_at',
        'sensitive' => 'customer_email',
        'selectedPlatform' => 'fabric',
        'selectedFrequency' => 'daily',
        'platforms' => ['fabric' => 'Fabric Warehouse', 'databricks' => 'Databricks Unity Catalog', 'sqlserver' => 'SQL Server', 'adls' => 'ADLS Gen2'],
        'frequencies' => ['daily' => 'Daily', 'weekly' => 'Weekly', 'monthly' => 'Monthly'],
    ])->name($name('tools.pureview-scan-generator'));
    Route::view('/tools/pureview-classification-generator', 'tools.pureview-generator', [
        'toolId' => 'pureview-classification-generator',
        'pageTitle' => 'PureView Classification Generator',
        'titleKey' => 'pureview.classification.pageTitle',
        'leadKey' => 'pureview.classification.lead',
        'introKey' => 'pureview.classification.howto.intro',
        'tipKey' => 'pureview.classification.howto.tip',
        'asset' => 'sales.orders_curated',
        'domain' => 'sales-domain',
        'owner' => 'data-owner-sales',
        'steward' => 'privacy-steward',
        'columns' => 'order_id, customer_id, customer_email, customer_name, iban, amount',
        'sensitive' => 'customer_email, customer_name, iban',
        'selectedPlatform' => 'fabric',
        'selectedFrequency' => 'weekly',
        'platforms' => ['fabric' => 'Fabric Warehouse', 'databricks' => 'Databricks Unity Catalog', 'sqlserver' => 'SQL Server', 'adls' => 'ADLS Gen2'],
        'frequencies' => ['daily' => 'Daily', 'weekly' => 'Weekly', 'monthly' => 'Monthly'],
    ])->name($name('tools.pureview-classification-generator'));
    Route::view('/tools/pureview-glossary-generator', 'tools.pureview-generator', [
        'toolId' => 'pureview-glossary-generator',
        'pageTitle' => 'PureView Glossary Generator',
        'titleKey' => 'pureview.glossary.pageTitle',
        'leadKey' => 'pureview.glossary.lead',
        'introKey' => 'pureview.glossary.howto.intro',
        'tipKey' => 'pureview.glossary.howto.tip',
        'asset' => 'Net Revenue',
        'domain' => 'finance-domain',
        'owner' => 'kpi-owner-finance',
        'steward' => 'data-steward-finance',
        'columns' => 'gross_revenue, discount_amount, refund_amount, net_revenue',
        'sensitive' => '',
        'selectedPlatform' => 'fabric',
        'selectedFrequency' => 'monthly',
        'platforms' => ['fabric' => 'Fabric Warehouse', 'databricks' => 'Databricks Unity Catalog', 'sqlserver' => 'SQL Server', 'adls' => 'ADLS Gen2'],
        'frequencies' => ['daily' => 'Daily', 'weekly' => 'Weekly', 'monthly' => 'Monthly'],
    ])->name($name('tools.pureview-glossary-generator'));
    Route::view('/tools/pureview-data-product-generator', 'tools.pureview-generator', [
        'toolId' => 'pureview-data-product-generator',
        'pageTitle' => 'PureView Data Product Generator',
        'titleKey' => 'pureview.dataProduct.pageTitle',
        'leadKey' => 'pureview.dataProduct.lead',
        'introKey' => 'pureview.dataProduct.howto.intro',
        'tipKey' => 'pureview.dataProduct.howto.tip',
        'asset' => 'Sales Orders Curated',
        'domain' => 'sales-domain',
        'owner' => 'data-owner-sales',
        'steward' => 'data-steward-sales',
        'columns' => 'order_id, customer_id, order_date, amount, net_revenue, updated_at',
        'sensitive' => 'customer_id',
        'selectedPlatform' => 'fabric',
        'selectedFrequency' => 'daily',
        'platforms' => ['fabric' => 'Fabric Warehouse', 'databricks' => 'Databricks Unity Catalog', 'sqlserver' => 'SQL Server', 'adls' => 'ADLS Gen2'],
        'frequencies' => ['daily' => 'Daily', 'weekly' => 'Weekly', 'monthly' => 'Monthly'],
    ])->name($name('tools.pureview-data-product-generator'));
};

$registerRoutes(false);

Route::prefix('{locale}')
    ->where(['locale' => 'de|en'])
    ->group(static function () use ($registerRoutes): void {
        $registerRoutes(true);
    });
