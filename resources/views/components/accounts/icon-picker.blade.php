@props([
    'name' => 'avatarIcon',
    'selected' => '',
])

@php
    use App\Support\AvatarIcons;

    $selected = AvatarIcons::normalize($selected);
@endphp

<fieldset class="sp-field" data-accounts-icon-picker>
    <legend data-i18n="accounts.avatarIcon">Avatar icon</legend>
    <p class="sp-field-hint" data-i18n="accounts.avatarIconHint">
        Optional. When set, the icon replaces the trigram on chips.
    </p>
    <div class="sp-icon-picker" role="radiogroup">
        <label class="sp-icon-picker__option{{ $selected === '' ? ' sp-icon-picker__option--active' : '' }}">
            <input type="radio" name="{{ $name }}" value="" @checked($selected === '')>
            <span class="sp-icon-picker__preview sp-icon-picker__preview--trigram" aria-hidden="true">ABC</span>
            <span class="visually-hidden" data-i18n="accounts.avatarTrigram">Trigram</span>
        </label>
        @foreach (AvatarIcons::OPTIONS as $icon)
            @php $css = AvatarIcons::cssClass($icon); @endphp
            <label class="sp-icon-picker__option{{ $selected === $icon ? ' sp-icon-picker__option--active' : '' }}" title="{{ $icon }}">
                <input type="radio" name="{{ $name }}" value="{{ $icon }}" @checked($selected === $icon)>
                <span class="sp-icon-picker__preview" aria-hidden="true"><i class="{{ $css }}"></i></span>
                <span class="visually-hidden">{{ $icon }}</span>
            </label>
        @endforeach
    </div>
</fieldset>

<script>
(() => {
    document.querySelectorAll('[data-accounts-icon-picker]').forEach((root) => {
        if (root.dataset.bound === '1') return;
        root.dataset.bound = '1';
        root.addEventListener('change', (event) => {
            const input = event.target;
            if (!(input instanceof HTMLInputElement) || input.type !== 'radio') return;
            root.querySelectorAll('.sp-icon-picker__option').forEach((el) => {
                el.classList.toggle('sp-icon-picker__option--active', el.contains(input) && input.checked);
            });
        });
    });
})();
</script>
