<nav class="governance-hub__subtabs" aria-label="Tools Bereiche" data-governance-subtabs="tools" role="tablist">
    <button type="button" class="governance-hub__subtab governance-hub__subtab--active" data-governance-subtab-toggle="featured" role="tab" aria-controls="tools-featured" aria-selected="true">
        <span data-text-de="Featured" data-text-en="Featured">Featured</span>
    </button>
    <button type="button" class="governance-hub__subtab" data-governance-subtab-toggle="workflows" role="tab" aria-controls="tools-workflows" aria-selected="false" tabindex="-1">
        <span data-text-de="Workflows" data-text-en="Workflows">Workflows</span>
    </button>
</nav>

<section class="governance-hub__guides-block" id="tools-featured" data-governance-subtab-panel="featured" role="tabpanel">
    <p
        class="tools-section__lead"
        data-hub-lead
        data-text-de="Featured Tools — Generatoren einzeln nutzbar."
        data-text-en="Featured tools — generators usable on their own."
    >Featured tools — generators usable on their own.</p>

    <div class="governance-hub__tool-grid">
        @foreach ($featuredTools as $tool)
            @php
                $labelEn = $tool['label']['en'] ?? ($tool['id'] ?? '');
                $labelDe = $tool['label']['de'] ?? $labelEn;
                $descEn = $tool['description']['en'] ?? '';
                $descDe = $tool['description']['de'] ?? $descEn;
            @endphp
            <a class="governance-hub__tool" href="{{ locale_route($tool['route']) }}" data-tool-id="{{ $tool['id'] ?? '' }}">
                @if (! empty($tool['icon']))
                    <i class="fa-solid {{ $tool['icon'] }}" aria-hidden="true"></i>
                @endif
                <span>
                    <strong data-text-de="{{ $labelDe }}" data-text-en="{{ $labelEn }}">{{ $labelEn }}</strong>
                    <small data-text-de="{{ $descDe }}" data-text-en="{{ $descEn }}">{{ $descEn }}</small>
                </span>
            </a>
        @endforeach
    </div>
</section>

<section class="governance-hub__guides-block" id="tools-workflows" data-governance-subtab-panel="workflows" data-governance-setup-workflows role="tabpanel" hidden>
    <p
        class="tools-section__lead"
        data-hub-lead
        data-text-de="Klickbare Flowcharts — dieselbe Reihenfolge wie in der In-Tool-Navigation."
        data-text-en="Clickable flowcharts — same order as in-tool navigation."
    >Clickable flowcharts — same order as in-tool navigation.</p>

    <div class="governance-hub__setup-flow-list">
        @foreach ($setupWorkflows as $workflowId => $workflow)
            @php
                $steps = $workflow['steps'] ?? [];
                $labelEn = $workflow['label']['en'] ?? $workflowId;
                $labelDe = $workflow['label']['de'] ?? $labelEn;
                $descEn = $workflow['description']['en'] ?? '';
                $descDe = $workflow['description']['de'] ?? $descEn;
                $flowchartSteps = [];
                foreach (array_values($steps) as $index => $stepId) {
                    $step = $toolsById[$stepId] ?? null;
                    if ($step === null || ! is_string($step['route'] ?? null)) {
                        continue;
                    }
                    $flowchartSteps[] = [
                        'id' => $stepId,
                        'num' => $index + 1,
                        'route' => $step['route'],
                        'label' => $step['label'] ?? ['en' => $stepId, 'de' => $stepId],
                        'isActive' => false,
                        'isCompleted' => false,
                    ];
                }
            @endphp
            @if (count($flowchartSteps) > 0)
                <section
                    class="tools-workflow-flowchart tools-workflow-flowchart--chevron governance-hub__setup-workflow"
                    aria-labelledby="governance-workflow-{{ $workflowId }}-title"
                    data-governance-setup-workflow="{{ $workflowId }}"
                    data-tool-ids="{{ implode(' ', array_column($flowchartSteps, 'id')) }}"
                >
                    <h3 id="governance-workflow-{{ $workflowId }}-title" class="governance-hub__workflow-title">
                        @if (! empty($workflow['icon']))
                            <i class="fa-solid {{ $workflow['icon'] }}" aria-hidden="true"></i>
                        @endif
                        <span data-text-de="{{ $labelDe }}" data-text-en="{{ $labelEn }}">{{ $labelEn }}</span>
                    </h3>
                    @if ($descEn !== '')
                        <p class="tools-section__lead" data-hub-lead data-text-de="{{ $descDe }}" data-text-en="{{ $descEn }}">{{ $descEn }}</p>
                    @endif
                    <x-tools.workflow-flowchart :steps="$flowchartSteps" />
                </section>
            @endif
        @endforeach
    </div>
</section>
