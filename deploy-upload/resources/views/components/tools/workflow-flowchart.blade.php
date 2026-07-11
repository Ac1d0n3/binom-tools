@props([
    'steps' => [],
])

@if (count($steps) > 0)
    <ol class="tools-workflow-flowchart__list">
        @foreach ($steps as $step)
            <li class="tools-workflow-flowchart__item">
                <a
                    href="{{ route($step['route']) }}"
                    class="tools-workflow-flowchart__chevron{{ $step['isActive'] ? ' tools-workflow-flowchart__chevron--active' : '' }}{{ $step['isCompleted'] ? ' tools-workflow-flowchart__chevron--completed' : '' }}"
                    @if ($step['isActive']) aria-current="step" @endif
                >
                    <span class="tools-workflow-flowchart__num">{{ $step['num'] }}</span>
                    <span data-i18n="workflow.step-{{ $step['id'] }}">{{ $step['label']['en'] ?? $step['id'] }}</span>
                </a>
            </li>
        @endforeach
    </ol>
@endif
