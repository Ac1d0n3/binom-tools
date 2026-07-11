@props([
    'toolId',
])

@php
    $steps = \App\Support\ToolWorkflow::flowchartStepsForToolId($toolId);
    $context = \App\Support\ToolWorkflow::contextForToolId($toolId);
    $workflowId = $context['workflowId'] ?? null;
@endphp

@if ($steps)
    <nav class="tools-workflow-flowchart tools-workflow-flowchart--chevron" aria-label="Workflow">
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
