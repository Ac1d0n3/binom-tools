<?php

namespace Tests\Feature\Tools;

use Tests\TestCase;

class PromptStudioPageTest extends TestCase
{
    public function test_prompt_studio_page_renders(): void
    {
        $response = $this->get('/tools/prompt-studio');

        $response->assertOk();
        $response->assertSee('prompt-studio-app', false);
        $response->assertSee('data-config-base="'.prompt_studio_config_path().'"', false);
        $response->assertSee('tools-workflow-flowchart', false);
        $response->assertSee('workflow.setupLabel.ai-prompt-workflow', false);
        $response->assertSee('ps-role-select', false);
        $response->assertSee('ps-sanitize-btn', false);
        $response->assertDontSee('ps-song-cta', false);
        $response->assertDontSee('ps-lyrics-cta', false);
        $response->assertDontSee('data-ps-kind=', false);
        $response->assertSee('ps-output-kind-badge', false);
        $response->assertSee('data-ps-tab="library"', false);
        $response->assertSee('ps-preview-toggle', false);
        $response->assertSee('ps-model-select', false);
        $response->assertSee('ps-model-plan', false);
        $response->assertSee('data-ps-plan="free"', false);
        $response->assertSee('ps-help-drawer', false);
        $response->assertSee('ps-library-drawer', false);
        $response->assertSee('ps-category-filter', false);
        $response->assertSee('promptStudio.category.all', false);
        $response->assertSee('data-ps-area="prompt"', false);
        $response->assertSee('data-ps-area="rule"', false);
        $response->assertSee('data-ps-area="agent"', false);
        $response->assertSee('ps-help-accordion', false);
        $response->assertSee('data-ps-help-item="rule"', false);
        $response->assertSee('data-ps-help-item="builder"', false);
    }

    public function test_prompt_studio_config_manifest_is_public(): void
    {
        $response = $this->get('/tools/prompt-studio/config/manifest.json');

        $response->assertOk();
        $response->assertHeader('content-type', 'application/json; charset=UTF-8');

        $manifestPath = public_path('prompt-studio/config/manifest.json');
        $this->assertFileExists($manifestPath);

        $data = json_decode((string) file_get_contents($manifestPath), true);
        $this->assertIsArray($data);
        $this->assertArrayHasKey('version', $data);
        $this->assertArrayHasKey('files', $data);
    }

    public function test_prompt_studio_config_chain_file_is_public(): void
    {
        $response = $this->get('/tools/prompt-studio/config/chains/business-visual.json');

        $response->assertOk();
        $response->assertHeader('content-type', 'application/json; charset=UTF-8');
    }

    public function test_governance_sanitizer_has_workflow_and_back_button(): void
    {
        $response = $this->get('/tools/governance-ai-sanitizer');

        $response->assertOk();
        $response->assertSee('gov-back-studio-btn', false);
        $response->assertSee('workflow.setupLabel.ai-prompt-workflow', false);
    }
}
