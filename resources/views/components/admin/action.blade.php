@props([
    'href' => null,
    'method' => null,
    'label' => null,
    'danger' => false,
])

@if ($method && $href)
    <form method="post" action="{{ $href }}" class="sp-list__action-form" style="display:inline">
        @csrf
        @if (strtoupper($method) !== 'POST')
            @method($method)
        @endif
        <button type="submit" class="tools-btn tools-btn--small {{ $danger ? 'tools-btn--danger' : '' }}">
            {{ $slot->isEmpty() ? $label : $slot }}
        </button>
    </form>
@elseif ($href)
    <a href="{{ $href }}" class="tools-btn tools-btn--small {{ $danger ? 'tools-btn--danger' : '' }}">
        {{ $slot->isEmpty() ? $label : $slot }}
    </a>
@else
    <span class="sp-list__actions">{{ $slot }}</span>
@endif
