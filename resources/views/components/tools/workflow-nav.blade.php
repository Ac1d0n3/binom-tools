@props([
    'toolId',
])

@php
    $steps = \App\Support\ToolWorkflow::flowchartStepsForToolId($toolId);
@endphp

@if ($steps)
    <nav class="tools-workflow-flowchart tools-workflow-flowchart--chevron" aria-label="Workflow">
        <p class="tools-workflow-flowchart__label" data-i18n="workflow.setupLabel">
            dbt PII Governance setup
        </p>
        <x-tools.workflow-flowchart :steps="$steps" />
    </nav>
@endif
