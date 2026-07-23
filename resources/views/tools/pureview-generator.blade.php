@extends('layouts.tools', [
    'viteEntries' => ['resources/js/tools/pureview-generator/index.js'],
])

@section('title', $pageTitle . ' — ' . config('app.name'))

@section('content')
    <x-tools.generator-page
        :title-key="$titleKey"
        :lead-key="$leadKey"
        :tool-id="$toolId"
        app-id="pureview-generator-app"
    >
        <x-tools.collapsible-info summary-key="pureview.howto.summary" :open="true">
            <p data-i18n="{{ $introKey }}"></p>
            <ol>
                <li data-i18n="pureview.howto.step1"></li>
                <li data-i18n="pureview.howto.step2"></li>
                <li data-i18n="pureview.howto.step3"></li>
            </ol>
            <p data-i18n="{{ $tipKey }}"></p>
        </x-tools.collapsible-info>

        <x-tools.panel heading-id="pureview-input-title" title-key="pureview.input.title" description-key="pureview.input.description">
            <div class="pii-policy-panel__grid">
                <x-tools.field label-key="pureview.input.asset">
                    <input id="pureview-asset" class="pii-policy-input" type="text" value="{{ $asset }}">
                </x-tools.field>
                <x-tools.field label-key="pureview.input.domain">
                    <input id="pureview-domain" class="pii-policy-input" type="text" value="{{ $domain }}">
                </x-tools.field>
                <x-tools.field label-key="pureview.input.platform">
                    <select id="pureview-platform" class="pii-policy-input">
                        @foreach ($platforms as $value => $label)
                            <option value="{{ $value }}" @selected($value === $selectedPlatform)>{{ $label }}</option>
                        @endforeach
                    </select>
                </x-tools.field>
                <x-tools.field label-key="pureview.input.owner">
                    <input id="pureview-owner" class="pii-policy-input" type="text" value="{{ $owner }}">
                </x-tools.field>
                <x-tools.field label-key="pureview.input.steward">
                    <input id="pureview-steward" class="pii-policy-input" type="text" value="{{ $steward }}">
                </x-tools.field>
                <x-tools.field label-key="pureview.input.columns">
                    <input id="pureview-columns" class="pii-policy-input" type="text" value="{{ $columns }}">
                </x-tools.field>
                <x-tools.field label-key="pureview.input.sensitive">
                    <input id="pureview-sensitive" class="pii-policy-input" type="text" value="{{ $sensitive }}">
                </x-tools.field>
                <x-tools.field label-key="pureview.input.frequency">
                    <select id="pureview-frequency" class="pii-policy-input">
                        @foreach ($frequencies as $value => $label)
                            <option value="{{ $value }}" @selected($value === $selectedFrequency)>{{ $label }}</option>
                        @endforeach
                    </select>
                </x-tools.field>
            </div>
        </x-tools.panel>

        <x-tools.panel-code heading-id="pureview-json-title" title-key="pureview.output.json" pre-id="pureview-json-pre" copy-btn-id="pureview-copy-json" copy-key="pureview.copy" />
        <x-tools.panel-code heading-id="pureview-mapping-title" title-key="pureview.output.mapping" pre-id="pureview-mapping-pre" copy-btn-id="pureview-copy-mapping" copy-key="pureview.copy" />
        <x-tools.panel-code heading-id="pureview-runbook-title" title-key="pureview.output.runbook" pre-id="pureview-runbook-pre" copy-btn-id="pureview-copy-runbook" copy-key="pureview.copy" />
    </x-tools.generator-page>
@endsection
