@props(['playbook'])

@if ($playbook->prev || $playbook->next)
    <nav class="playbook-pager" aria-label="Playbook navigation">
        @if ($playbook->prev)
            <a href="{{ route('playbooks.show', $playbook->prev->slug) }}" class="playbook-pager__link playbook-pager__link--prev">
                <span class="playbook-pager__label" data-i18n="playbooks.previous">Previous</span>
                <span class="playbook-pager__title">{{ $playbook->prev->title }}</span>
            </a>
        @else
            <span class="playbook-pager__placeholder" aria-hidden="true"></span>
        @endif

        @if ($playbook->next)
            <a href="{{ route('playbooks.show', $playbook->next->slug) }}" class="playbook-pager__link playbook-pager__link--next">
                <span class="playbook-pager__label" data-i18n="playbooks.next">Next</span>
                <span class="playbook-pager__title">{{ $playbook->next->title }}</span>
            </a>
        @else
            <span class="playbook-pager__placeholder" aria-hidden="true"></span>
        @endif
    </nav>
@endif
