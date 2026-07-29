@props([
    'toolId',
])

@php
    $steps = \App\Support\ToolWorkflow::flowchartStepsForToolId($toolId);
    $context = \App\Support\ToolWorkflow::contextForToolId($toolId);
    $workflowId = $context['workflowId'] ?? null;
    $useWrap = count($steps ?? []) >= 7
        || $workflowId === 'governance-decision-support';
    $layoutClass = $useWrap ? 'tools-workflow-flowchart--wrap' : 'tools-workflow-flowchart--classic';
@endphp

@if ($steps)
    <nav
        class="tools-workflow-flowchart tools-workflow-flowchart--chevron {{ $layoutClass }}"
        aria-label="Workflow"
        data-workflow-layout="{{ $useWrap ? 'wrap' : 'classic' }}"
    >
        @if ($workflowId)
            <p
                class="tools-workflow-flowchart__label"
                data-i18n="workflow.setupLabel.{{ $workflowId }}"
            >
                {{ config("tools.workflows.{$workflowId}.label.en") ?? $workflowId }}
            </p>
        @endif
        <x-tools.workflow-flowchart :steps="$steps" />
        <p
            class="tools-workflow-step-lead"
            data-i18n="card.{{ $toolId }}.description"
            role="status"
        >
            {{ $context['current']['description']['en'] ?? '' }}
        </p>
    </nav>
@endif
