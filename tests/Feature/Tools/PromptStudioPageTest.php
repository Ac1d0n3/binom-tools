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
        $response->assertSee('tools-workflow-flowchart', false);
        $response->assertSee('workflow.setupLabel.ai-prompt-workflow', false);
        $response->assertSee('ps-role-select', false);
        $response->assertSee('ps-sanitize-btn', false);
    }

    public function test_governance_sanitizer_has_workflow_and_back_button(): void
    {
        $response = $this->get('/tools/governance-ai-sanitizer');

        $response->assertOk();
        $response->assertSee('gov-back-studio-btn', false);
        $response->assertSee('workflow.setupLabel.ai-prompt-workflow', false);
    }
}
