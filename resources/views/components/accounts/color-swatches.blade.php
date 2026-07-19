@props([
    'name' => 'colorToken',
    'selected' => 'accent-1',
    'tokens' => null,
])

@php
    use App\Support\AccentColors;

    $palette = is_array($tokens) && $tokens !== [] ? $tokens : AccentColors::TOKENS;
    $selected = in_array($selected, $palette, true) ? $selected : ($palette[0] ?? 'accent-1');
@endphp

<fieldset class="sp-field">
    <legend data-i18n="accounts.color">Color</legend>
    <div class="sp-color-swatches" role="radiogroup">
        @foreach ($palette as $token)
            @php
                $token = AccentColors::normalize($token);
                $isBordered = AccentColors::isBordered($token);
            @endphp
            <label
                class="sp-color-swatch sp-avatar--{{ $token }}{{ $isBordered ? ' sp-color-swatch--bordered' : '' }}"
                style="{{ AccentColors::chipStyle($token) }}"
                title="{{ $token }}"
            >
                <input type="radio" name="{{ $name }}" value="{{ $token }}" @checked($selected === $token)>
                <span class="visually-hidden">{{ $token }}</span>
            </label>
        @endforeach
    </div>
    <p class="sp-field-hint" data-i18n="accounts.colorHint">
        Solid fills or white chips with solid, dotted, or dashed colored borders.
    </p>
</fieldset>
