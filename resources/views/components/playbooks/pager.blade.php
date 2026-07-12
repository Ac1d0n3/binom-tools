@props(['playbook'])

@if ($playbook->prev || $playbook->next)
    <nav class="playbook-pager" aria-label="Playbook navigation">
        @if ($playbook->prev)
            <a href="{{ locale_route('playbooks.show', ['slug' => $playbook->prev->slug]) }}" class="playbook-pager__link playbook-pager__link--prev">
                <span class="playbook-pager__label" data-i18n="playbooks.previous">Previous</span>
                <span
                    class="playbook-pager__title"
                    data-playbook-pager-title
                    data-text-de="{{ $playbook->prev->titleDe }}"
                    data-text-en="{{ $playbook->prev->titleEn }}"
                >{{ $playbook->prev->titleEn }}</span>
            </a>
        @else
            <span class="playbook-pager__placeholder" aria-hidden="true"></span>
        @endif

        @if ($playbook->next)
            <a href="{{ locale_route('playbooks.show', ['slug' => $playbook->next->slug]) }}" class="playbook-pager__link playbook-pager__link--next">
                <span class="playbook-pager__label" data-i18n="playbooks.next">Next</span>
                <span
                    class="playbook-pager__title"
                    data-playbook-pager-title
                    data-text-de="{{ $playbook->next->titleDe }}"
                    data-text-en="{{ $playbook->next->titleEn }}"
                >{{ $playbook->next->titleEn }}</span>
            </a>
        @else
            <span class="playbook-pager__placeholder" aria-hidden="true"></span>
        @endif
    </nav>
@endif
