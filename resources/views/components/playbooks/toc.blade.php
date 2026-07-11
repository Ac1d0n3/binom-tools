@props(['entries'])

@php
    $groups = [];

    foreach ($entries as $entry) {
        if ($entry->level === 2) {
            $groups[] = ['heading' => $entry, 'children' => []];
        } elseif ($groups !== []) {
            $groups[array_key_last($groups)]['children'][] = $entry;
        }
    }
@endphp

<aside class="playbook-toc" data-playbook-toc aria-label="Table of contents">
    <div class="playbook-toc__mobile">
        <button
            type="button"
            class="playbook-toc__toggle tools-btn tools-btn--ghost"
            data-playbook-toc-toggle
            aria-expanded="false"
            aria-controls="playbook-toc-panel"
            data-i18n="playbooks.tocToggle"
        >
            On this page
        </button>
    </div>

    <div class="playbook-toc__panel" id="playbook-toc-panel" data-playbook-toc-panel>
        <p class="playbook-toc__title" data-i18n="playbooks.tocTitle">On this page</p>
        <nav class="playbook-toc__nav">
            <ul class="playbook-toc__list">
                @foreach ($groups as $group)
                    @php
                        $heading = $group['heading'];
                        $children = $group['children'];
                        $isBranch = count($children) > 0;
                    @endphp
                    @if ($isBranch)
                        <li
                            class="playbook-toc__group playbook-toc__group--branch"
                            data-playbook-toc-group
                            data-playbook-toc-branch
                            data-group-id="{{ $heading->id }}"
                        >
                            <div class="playbook-toc__group-header">
                                <button
                                    type="button"
                                    class="playbook-toc__group-toggle"
                                    data-playbook-toc-group-toggle
                                    aria-expanded="false"
                                    aria-controls="toc-sub-{{ $heading->id }}"
                                    aria-label="Toggle section"
                                >
                                    <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                                </button>
                                <a
                                    href="#{{ $heading->id }}"
                                    class="playbook-toc__link"
                                    data-playbook-toc-link
                                    data-target-id="{{ $heading->id }}"
                                >
                                    {{ $heading->text }}
                                </a>
                            </div>

                            <ul
                                id="toc-sub-{{ $heading->id }}"
                                class="playbook-toc__sublist"
                                data-playbook-toc-sublist
                            >
                                @foreach ($children as $child)
                                    <li class="playbook-toc__item playbook-toc__item--level-3">
                                        <a
                                            href="#{{ $child->id }}"
                                            class="playbook-toc__link"
                                            data-playbook-toc-link
                                            data-target-id="{{ $child->id }}"
                                        >
                                            {{ $child->text }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @else
                        <li
                            class="playbook-toc__group playbook-toc__group--leaf"
                            data-playbook-toc-group
                            data-playbook-toc-leaf
                            data-group-id="{{ $heading->id }}"
                        >
                            <a
                                href="#{{ $heading->id }}"
                                class="playbook-toc__link playbook-toc__link--leaf"
                                data-playbook-toc-link
                                data-target-id="{{ $heading->id }}"
                            >
                                {{ $heading->text }}
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </nav>
    </div>
</aside>
