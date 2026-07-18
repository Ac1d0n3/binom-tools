@props([
    'slug',
    'shareEnabled' => true,
])

@php
    $statsShowUrl = locale_route('playbooks.stats.show', ['slug' => $slug]);
    $statsViewUrl = locale_route('playbooks.stats.view', ['slug' => $slug]);
    $statsLikeUrl = locale_route('playbooks.stats.like', ['slug' => $slug]);
@endphp

<div
    class="playbook-engagement"
    data-playbook-engagement
    data-stats-show-url="{{ $statsShowUrl }}"
    data-stats-view-url="{{ $statsViewUrl }}"
    data-stats-like-url="{{ $statsLikeUrl }}"
    data-share-enabled="{{ $shareEnabled ? '1' : '0' }}"
>
    <div class="playbook-engagement__stat" title="Views">
        <i class="fa-solid fa-eye" aria-hidden="true"></i>
        <span data-playbook-views>0</span>
        <span class="playbook-engagement__sr" data-i18n="playbooks.views">Views</span>
    </div>

    <button
        type="button"
        class="playbook-engagement__like"
        data-playbook-like
        aria-pressed="false"
        data-i18n-aria="playbooks.like"
        aria-label="Like"
    >
        <i class="fa-regular fa-heart" aria-hidden="true" data-like-icon></i>
        <span data-playbook-likes>0</span>
    </button>

    @if ($shareEnabled)
        <div class="playbook-engagement__share-wrap" data-playbook-share-wrap>
            <button
                type="button"
                class="playbook-engagement__share"
                data-playbook-share
                data-i18n-aria="playbooks.share"
                aria-label="Share"
                aria-expanded="false"
                aria-haspopup="true"
            >
                <i class="fa-solid fa-share-nodes" aria-hidden="true"></i>
                <span data-i18n="playbooks.share">Share</span>
            </button>

            <div
                class="playbook-engagement__share-menu"
                data-playbook-share-menu
                hidden
                role="menu"
            >
                <a
                    class="playbook-engagement__share-item"
                    data-share-network="facebook"
                    role="menuitem"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    <i class="fa-brands fa-facebook-f" aria-hidden="true"></i>
                    <span data-i18n="playbooks.shareFacebook">Facebook</span>
                </a>
                <a
                    class="playbook-engagement__share-item"
                    data-share-network="linkedin"
                    role="menuitem"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    <i class="fa-brands fa-linkedin-in" aria-hidden="true"></i>
                    <span data-i18n="playbooks.shareLinkedIn">LinkedIn</span>
                </a>
                <a
                    class="playbook-engagement__share-item"
                    data-share-network="xing"
                    role="menuitem"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    <i class="fa-brands fa-xing" aria-hidden="true"></i>
                    <span data-i18n="playbooks.shareXing">Xing</span>
                </a>
                <a
                    class="playbook-engagement__share-item"
                    data-share-network="whatsapp"
                    role="menuitem"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
                    <span data-i18n="playbooks.shareWhatsApp">WhatsApp</span>
                </a>
                <a
                    class="playbook-engagement__share-item"
                    data-share-network="x"
                    role="menuitem"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    <i class="fa-brands fa-x-twitter" aria-hidden="true"></i>
                    <span data-i18n="playbooks.shareX">X</span>
                </a>
                <a
                    class="playbook-engagement__share-item"
                    data-share-network="email"
                    role="menuitem"
                >
                    <i class="fa-solid fa-envelope" aria-hidden="true"></i>
                    <span data-i18n="playbooks.shareEmail">E-Mail</span>
                </a>
                <button
                    type="button"
                    class="playbook-engagement__share-item"
                    data-share-copy
                    role="menuitem"
                >
                    <i class="fa-solid fa-link" aria-hidden="true"></i>
                    <span data-i18n="playbooks.shareCopy">Link kopieren</span>
                </button>
            </div>
        </div>
    @endif
</div>
