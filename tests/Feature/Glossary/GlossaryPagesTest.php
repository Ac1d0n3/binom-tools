<?php

namespace Tests\Feature\Glossary;

use Tests\TestCase;

class GlossaryPagesTest extends TestCase
{
    public function test_glossary_index_and_show_pages(): void
    {
        $index = $this->get('/glossary');
        $index->assertOk();
        $index->assertSee('data-i18n="glossary.indexTitle"', false);
        $index->assertSee('glossary-hub-grid', false);
        $index->assertSee('/glossary/data-steward', false);
        $index->assertSee('data-glossary-az-panel', false);
        $index->assertSee('data-glossary-az-toggle', false);
        $index->assertSee('data-glossary-letter="all"', false);
        $index->assertSee('data-letter-en=', false);
        $index->assertSee('data-overview-result-count', false);
        $index->assertSee('tools-overview-count-badge', false);
        $index->assertSee('data-overview-layout-toggle="grid"', false);
        $index->assertSee('data-overview-layout-toggle="list"', false);
        $index->assertSee('data-overview-stories-grid', false);

        $show = $this->get('/glossary/dsdr');
        $show->assertOk();
        $show->assertSee('DSDR', false);
        $show->assertSee('glossary-detail__related', false);

        $this->get('/de/glossary')->assertOk();
        $this->get('/en/glossary/pii')->assertOk();
        $this->get('/glossary/does-not-exist')->assertNotFound();
    }

    public function test_glossary_quiz_and_bingo_pages(): void
    {
        $index = $this->get('/glossary');
        $index->assertOk();
        $index->assertSee('data-glossary-quiz-open', false);
        $index->assertSee('data-i18n-aria="glossary.quiz.cta"', false);
        $index->assertSee('data-i18n-aria="glossary.bingo.cta"', false);
        $index->assertSee('/glossary/bingo', false);
        $index->assertSee('data-glossary-quiz-modal', false);
        $index->assertSee('data-quiz-data-url=', false);
        $index->assertSee('data-quiz-category', false);
        $index->assertSee('data-i18n="glossary.quiz.categoriesLabel"', false);
        $index->assertDontSee('glossary-hub-actions', false);

        $quizRedirect = $this->get('/glossary/quiz?count=10&categories[]=ai&categories[]=bi');
        $quizRedirect->assertRedirect();
        $location = (string) $quizRedirect->headers->get('Location');
        $this->assertStringContainsString('/glossary', parse_url($location, PHP_URL_PATH) ?: '');
        $this->assertStringContainsString('quiz=1', (string) parse_url($location, PHP_URL_QUERY));
        $this->assertStringContainsString('categories', (string) parse_url($location, PHP_URL_QUERY));

        $quizData = $this->getJson('/glossary/quiz/data?count=15');
        $quizData->assertOk();
        $quizData->assertJsonPath('count', 15);
        $quizData->assertJsonStructure(['seed', 'count', 'categories', 'questions']);
        $this->assertCount(15, $quizData->json('questions'));

        $filteredQuiz = $this->getJson('/glossary/quiz/data?count=8&categories[]=ai&categories[]=nope');
        $filteredQuiz->assertOk();
        $filteredQuiz->assertJsonPath('categories', ['ai']);
        $this->assertCount(8, $filteredQuiz->json('questions'));

        $openModal = $this->get('/glossary?quiz=1&count=15&categories[]=ai');
        $openModal->assertOk();
        $openModal->assertSee('data-glossary-quiz-autopen="1"', false);
        $openModal->assertSee('value="15" selected', false);
        $openModal->assertSee('value="ai"', false);
        $openModal->assertSee('checked', false);

        $bingo = $this->get('/glossary/bingo');
        $bingo->assertOk();
        $bingo->assertSee('data-glossary-bingo', false);
        $bingo->assertSee('data-bingo-cell', false);
        $bingo->assertSee('data-i18n="glossary.bingo.free"', false);
        $bingo->assertSee('name="cards"', false);
        $bingo->assertSee('name="size"', false);
        $bingo->assertSee('data-i18n="glossary.bingo.cardsLabel"', false);
        $bingo->assertSee('data-i18n="glossary.bingo.sizeLabel"', false);
        $bingo->assertSee('data-i18n="glossary.bingo.categoriesLabel"', false);
        $bingo->assertSee('name="categories[]"', false);
        $bingo->assertSee('glossary-bingo__controls', false);
        $bingo->assertSee('glossary-bingo-grid', false);
        $bingo->assertSee('glossary-bingo-grid--5', false);

        $miniBingo = $this->get('/glossary/bingo?size=3&cards=2&seed=mini-seed');
        $miniBingo->assertOk();
        $miniBingo->assertSee('glossary-bingo-grid--3', false);
        $miniBingo->assertSee('data-bingo-size="3"', false);
        $miniBingo->assertSee('3×3', false);
        $this->assertSame(2, substr_count($miniBingo->getContent(), 'class="glossary-bingo-card glossary-bingo-card--size-3"'));

        $filteredBingo = $this->get('/glossary/bingo?cards=1&categories[]=ai&seed=fixed-ai-bingo');
        $filteredBingo->assertOk();
        $filteredBingo->assertSee('value="ai"', false);
        $filteredBingo->assertSee('checked', false);
        $filteredBingo->assertSee('categories%5B0%5D=ai', false);

        $multiBingo = $this->get('/glossary/bingo?cards=3&seed=fixed-seed-1');
        $multiBingo->assertOk();
        $multiBingo->assertSee('fixed-seed-1', false);
        $this->assertSame(3, substr_count($multiBingo->getContent(), 'class="glossary-bingo-card glossary-bingo-card--size-5"'));
        $multiBingo->assertSee('#1', false);
        $multiBingo->assertSee('#3', false);

        $seeded = $this->get('/glossary/bingo?seed=fixed-seed-1');
        $seeded->assertOk();
        $seeded->assertSee('fixed-seed-1', false);

        $this->get('/de/glossary/quiz')->assertRedirect();
        $this->get('/en/glossary/bingo')->assertOk();

        // Feature routes must not be captured by {slug}.
        $this->get('/glossary/quiz/data')->assertOk();
        $this->get('/glossary/bingo')->assertOk();
    }

    public function test_glossary_has_expanded_vocabulary_and_new_categories(): void
    {
        /** @var list<array<string, mixed>> $terms */
        $terms = config('glossary.terms', []);
        $this->assertGreaterThanOrEqual(700, count($terms));

        /** @var array<string, array{de: string, en: string}> $categories */
        $categories = config('glossary.categories', []);
        foreach (['architecture', 'modeling', 'bi', 'security', 'ai'] as $categoryId) {
            $this->assertArrayHasKey($categoryId, $categories);
        }

        $index = $this->get('/glossary');
        $index->assertOk();
        $index->assertSee('value="architecture"', false);
        $index->assertSee('value="modeling"', false);
        $index->assertSee('value="bi"', false);
        $index->assertSee('value="security"', false);
        $index->assertSee('value="ai"', false);
        $index->assertSee('/glossary/medallion-architecture', false);
        $index->assertSee('/glossary/semantic-layer', false);
        $index->assertSee('/glossary/rag', false);
        $index->assertSee('/glossary/set-analysis', false);
    }

    public function test_new_buzzword_glossary_terms_are_reachable(): void
    {
        $this->get('/glossary/medallion-architecture')
            ->assertOk()
            ->assertSee('Medallion Architecture', false);

        $this->get('/glossary/semantic-layer')
            ->assertOk()
            ->assertSee('Semantic Layer', false);

        $this->get('/glossary/rag')
            ->assertOk()
            ->assertSee('RAG', false);

        $this->get('/glossary/data-observability')
            ->assertOk()
            ->assertSee('Data Observability', false);

        $this->get('/glossary/business-glossary')
            ->assertOk()
            ->assertSee('Business Glossary', false);

        $this->get('/glossary/set-analysis')
            ->assertOk()
            ->assertSee('Set Analysis', false);

        $this->get('/glossary/filter-context')
            ->assertOk()
            ->assertSee('Filter Context', false);

        $this->get('/glossary/technical-metadata')
            ->assertOk()
            ->assertSee('Technical Metadata', false);

        $this->get('/glossary/headless-bi')
            ->assertOk()
            ->assertSee('Headless BI', false);

        $this->get('/glossary/gdpr')
            ->assertOk()
            ->assertSee('GDPR', false);

        $this->get('/glossary/dataops')
            ->assertOk()
            ->assertSee('DataOps', false);

        $this->get('/glossary/parallel-run')
            ->assertOk()
            ->assertSee('Parallel Run', false);

        $this->get('/glossary/governed-self-service')
            ->assertOk()
            ->assertSee('Governed Self-Service', false);

        $this->get('/glossary/bounded-context')
            ->assertOk()
            ->assertSee('Bounded Context', false);

        $this->get('/glossary/backfill')
            ->assertOk()
            ->assertSee('Backfill', false);

        $this->get('/glossary/kimball')
            ->assertOk()
            ->assertSee('Kimball', false);

        $this->get('/glossary/vanity-metric')
            ->assertOk()
            ->assertSee('Vanity Metric', false);

        $this->get('/glossary/duckdb')
            ->assertOk()
            ->assertSee('DuckDB', false);

        $this->get('/glossary/golden-path')
            ->assertOk()
            ->assertSee('Golden Path', false);

        $this->get('/glossary/data-residency')
            ->assertOk()
            ->assertSee('Data Residency', false);

        $this->get('/glossary/cqrs')
            ->assertOk()
            ->assertSee('CQRS', false);

        $this->get('/glossary/grounding')
            ->assertOk()
            ->assertSee('Grounding', false);

        $this->get('/glossary/jit-access')
            ->assertOk()
            ->assertSee('Just-in-Time Access', false);

        $this->get('/de/glossary/lakehouse')->assertOk();
        $this->get('/en/glossary/scd2')->assertOk();
        $this->get('/en/glossary/dax')->assertOk();
        $this->get('/en/glossary/etl')->assertOk();
        $this->get('/en/glossary/dbt-exposure')->assertOk();
        $this->get('/en/glossary/workflow-orchestrator')->assertOk();
        $this->get('/en/glossary/log-based-cdc')->assertOk();
        $this->get('/en/glossary/liquid-clustering')->assertOk();
    }

    public function test_buzzword_wave2_quiz_friendly_terms_are_reachable(): void
    {
        $samples = [
            'chief-data-officer' => 'Chief Data Officer',
            'zero-etl' => 'Zero-ETL',
            'kappa-architecture' => 'Kappa Architecture',
            'lambda-architecture' => 'Lambda Architecture',
            'slowly-changing-dimension' => 'Slowly Changing Dimension',
            'import-mode' => 'Import Mode',
            'live-connection' => 'Live Connection',
            'quarantine-zone' => 'Quarantine Zone',
            'data-clean-room' => 'Data Clean Room',
            'data-dictionary' => 'Data Dictionary',
            'canary-release' => 'Canary Release',
            'model-context-protocol' => 'Model Context Protocol',
            'agentic-workflow' => 'Agentic Workflow',
            'jailbreak' => 'Jailbreak',
        ];

        foreach ($samples as $slug => $label) {
            $this->get('/glossary/'.$slug)
                ->assertOk()
                ->assertSee($label, false);
        }

        $this->get('/de/glossary/data-sla')->assertOk();
        $this->get('/en/glossary/reranker')->assertOk();

        $bundle = (new \App\Glossary\BuzzwordQuizGenerator())->generate('de', 10, 'wave2-quiz');
        $this->assertCount(10, $bundle['questions']);
        foreach ($bundle['questions'] as $question) {
            $this->assertStringContainsString('?', (string) ($question['stem'] ?? $question['prompt'] ?? ''));
        }
    }

    public function test_buzzword_wave3_quiz_friendly_terms_are_reachable(): void
    {
        $samples = [
            'platform-engineer' => 'Platform Engineer',
            'master-data-management' => 'Master Data Management',
            'outbox-pattern' => 'Outbox Pattern',
            'saga-pattern' => 'Saga Pattern',
            'snapshot-fact' => 'Snapshot Fact',
            'scd1' => 'SCD Type 1',
            'direct-lake' => 'Direct Lake',
            'object-level-security' => 'Object-Level Security',
            'freshness-sla' => 'Freshness SLA',
            'expectation-suite' => 'Expectation Suite',
            'privacy-by-design' => 'Privacy by Design',
            'dpia' => 'DPIA',
            'break-glass-access' => 'Break-Glass Access',
            'blue-green-deployment' => 'Blue-Green Deployment',
            'vector-database' => 'Vector Database',
            'ai-act' => 'AI Act',
            'red-teaming' => 'Red Teaming',
        ];

        foreach ($samples as $slug => $label) {
            $this->get('/glossary/'.$slug)
                ->assertOk()
                ->assertSee($label, false);
        }

        $this->get('/de/glossary/metric-layer')->assertOk();
        $this->get('/en/glossary/eval-harness')->assertOk();

        $bundle = (new \App\Glossary\BuzzwordQuizGenerator())->generate('en', 10, 'wave3-quiz');
        $this->assertCount(10, $bundle['questions']);
        foreach ($bundle['questions'] as $question) {
            $this->assertStringContainsString('?', (string) ($question['stem'] ?? $question['prompt'] ?? ''));
        }
    }

    public function test_buzzword_wave4_quiz_friendly_terms_are_reachable(): void
    {
        $samples = [
            'head-of-data' => 'Head of Data',
            'ai-engineer' => 'AI Engineer',
            'domain-owner' => 'Domain Owner',
            'data-product-canvas' => 'Data Product Canvas',
            'write-audit-publish' => 'Write-Audit-Publish',
            'scd3' => 'SCD Type 3',
            'galaxy-schema' => 'Galaxy Schema',
            'visual-level-filter' => 'Visual-Level Filter',
            'paginated-report' => 'Paginated Report',
            'completeness-check' => 'Completeness Check',
            'schrems-ii' => 'Schrems II',
            'workload-identity' => 'Workload Identity',
            'column-profiling' => 'Column Profiling',
            'incident-commander' => 'Incident Commander',
            'zero-shot-prompting' => 'Zero-Shot Prompting',
            'groundedness' => 'Groundedness',
            'model-routing' => 'Model Routing',
        ];

        foreach ($samples as $slug => $label) {
            $this->get('/glossary/'.$slug)
                ->assertOk()
                ->assertSee($label, false);
        }

        $this->get('/de/glossary/rapidly-changing-dimension')->assertOk();
        $this->get('/en/glossary/ai-observability')->assertOk();

        $bundle = (new \App\Glossary\BuzzwordQuizGenerator())->generate('de', 10, 'wave4-quiz');
        $this->assertCount(10, $bundle['questions']);
        foreach ($bundle['questions'] as $question) {
            $this->assertStringContainsString('?', (string) ($question['stem'] ?? $question['prompt'] ?? ''));
        }
    }

    public function test_buzzword_wave5_quiz_friendly_terms_are_reachable(): void
    {
        $samples = [
            'data-governance-manager' => 'Data Governance Manager',
            'data-mesh-principle' => 'Data Mesh Principle',
            'data-contract-testing' => 'Data Contract Testing',
            'event-carried-state-transfer' => 'Event-Carried State Transfer',
            'strangler-fig' => 'Strangler Fig Pattern',
            'service-mesh' => 'Service Mesh',
            'additive-measure' => 'Additive Measure',
            'visual-cross-filter' => 'Visual Cross-Filter',
            'shift-left-quality' => 'Shift-Left Quality',
            'data-breach' => 'Data Breach',
            'standard-contractual-clauses' => 'Standard Contractual Clauses',
            'mutual-tls' => 'Mutual TLS',
            'metadata-lineage' => 'Metadata Lineage',
            'continuous-delivery' => 'Continuous Delivery',
            'rto' => 'RTO',
            'hybrid-search' => 'Hybrid Search',
            'llm-gateway' => 'LLM Gateway',
        ];

        foreach ($samples as $slug => $label) {
            $this->get('/glossary/'.$slug)
                ->assertOk()
                ->assertSee($label, false);
        }

        $this->get('/de/glossary/chunking-strategy')->assertOk();
        $this->get('/en/glossary/speculative-decoding')->assertOk();

        $bundle = (new \App\Glossary\BuzzwordQuizGenerator())->generate('en', 10, 'wave5-quiz');
        $this->assertCount(10, $bundle['questions']);
        foreach ($bundle['questions'] as $question) {
            $this->assertStringContainsString('?', (string) ($question['stem'] ?? $question['prompt'] ?? ''));
        }
    }

    public function test_buzzword_wave6_quiz_friendly_terms_are_reachable(): void
    {
        $samples = [
            'finops-analyst' => 'FinOps Analyst',
            'data-democratization' => 'Data Democratization',
            'federated-computational-governance' => 'Federated Computational Governance',
            'anti-corruption-layer' => 'Anti-Corruption Layer',
            'hexagonal-architecture' => 'Hexagonal Architecture',
            'hot-path' => 'Hot Path',
            'bronze-layer' => 'Bronze Layer',
            'gold-layer' => 'Gold Layer',
            'domain-driven-design' => 'Domain-Driven Design',
            'button-slicer' => 'Button Slicer',
            'data-quality-scorecard' => 'Data Quality Scorecard',
            'transfer-impact-assessment' => 'Transfer Impact Assessment',
            'threat-modeling' => 'Threat Modeling',
            'phishing-resistant-mfa' => 'Phishing-Resistant MFA',
            'semantic-metadata' => 'Semantic Metadata',
            'trunk-based-development' => 'Trunk-Based Development',
            'cross-encoder' => 'Cross-Encoder',
            'agent-planner' => 'Agent Planner',
        ];

        foreach ($samples as $slug => $label) {
            $this->get('/glossary/'.$slug)
                ->assertOk()
                ->assertSee($label, false);
        }

        $this->get('/de/glossary/backpressure')->assertOk();
        $this->get('/en/glossary/human-feedback-loop')->assertOk();

        $bundle = (new \App\Glossary\BuzzwordQuizGenerator())->generate('de', 10, 'wave6-quiz');
        $this->assertCount(10, $bundle['questions']);
        foreach ($bundle['questions'] as $question) {
            $this->assertStringContainsString('?', (string) ($question['stem'] ?? $question['prompt'] ?? ''));
        }
    }

    public function test_buzzword_wave7_quiz_friendly_terms_are_reachable(): void
    {
        $samples = [
            'chief-analytics-officer' => 'Chief Analytics Officer',
            'data-product-roadmap' => 'Data Product Roadmap',
            'data-provenance-chain' => 'Data Provenance Chain',
            'message-broker' => 'Message Broker',
            'event-choreography' => 'Event Choreography',
            'warm-standby' => 'Warm Standby',
            'persistent-staging' => 'Persistent Staging',
            'ragged-hierarchy' => 'Ragged Hierarchy',
            'report-book' => 'Report Book',
            'mobile-bi' => 'Mobile BI',
            'schema-validation' => 'Schema Validation',
            'data-subject-request' => 'Data Subject Request',
            'legitimate-interest-assessment' => 'Legitimate Interest Assessment',
            'siem-alert' => 'SIEM Alert',
            'business-term-link' => 'Business Term Link',
            'mob-programming' => 'Mob Programming',
            'retrieval-pipeline' => 'Retrieval Pipeline',
            'function-calling-api' => 'Function Calling API',
        ];

        foreach ($samples as $slug => $label) {
            $this->get('/glossary/'.$slug)
                ->assertOk()
                ->assertSee($label, false);
        }

        $this->get('/de/glossary/geo-replication')->assertOk();
        $this->get('/en/glossary/toil-reduction')->assertOk();

        $bundle = (new \App\Glossary\BuzzwordQuizGenerator())->generate('de', 10, 'wave7-quiz');
        $this->assertCount(10, $bundle['questions']);
        foreach ($bundle['questions'] as $question) {
            $this->assertStringContainsString('?', (string) ($question['stem'] ?? $question['prompt'] ?? ''));
        }
    }
}
