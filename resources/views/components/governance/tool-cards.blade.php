@props([
    'tools' => [],
])

@php
    /** @var list<array<string, mixed>> $tools */
@endphp

@if (count($tools) > 0)
    <div class="governance-hub__tool-grid">
        @foreach ($tools as $tool)
            @php
                $labelEn = $tool['label']['en'] ?? ($tool['id'] ?? '');
                $labelDe = $tool['label']['de'] ?? $labelEn;
                $descEn = $tool['description']['en'] ?? '';
                $descDe = $tool['description']['de'] ?? $descEn;
                $route = is_string($tool['route'] ?? null) ? $tool['route'] : '';
            @endphp
            @if ($route !== '' && \Illuminate\Support\Facades\Route::has($route))
                <a class="governance-hub__tool" href="{{ locale_route($route) }}">
                    @if (! empty($tool['icon']))
                        <i class="fa-solid {{ $tool['icon'] }}" aria-hidden="true"></i>
                    @endif
                    <span>
                        <strong data-text-de="{{ $labelDe }}" data-text-en="{{ $labelEn }}">{{ $labelEn }}</strong>
                        @if ($descEn !== '' || $descDe !== '')
                            <small data-text-de="{{ $descDe }}" data-text-en="{{ $descEn }}">{{ $descEn }}</small>
                        @endif
                    </span>
                </a>
            @endif
        @endforeach
    </div>
@endif
