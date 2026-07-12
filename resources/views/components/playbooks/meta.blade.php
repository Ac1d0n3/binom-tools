@props([
    'variant',
    'modifiedAt',
])

<dl class="playbook-meta">
    @if ($variant->category)
        <div class="playbook-meta__item">
            <dt class="playbook-meta__label" data-i18n="playbooks.category">Category</dt>
            <dd class="playbook-meta__value">{{ $variant->category }}</dd>
        </div>
    @endif

    @if ($variant->author)
        <div class="playbook-meta__item">
            <dt class="playbook-meta__label" data-i18n="playbooks.author">Author</dt>
            <dd class="playbook-meta__value">
                <span itemprop="author" itemscope itemtype="https://schema.org/Person">
                    <a
                        href="{{ config('playbooks.author_url', 'https://binom.net') }}"
                        itemprop="url"
                        target="_blank"
                        rel="noopener noreferrer author"
                    >
                        <span itemprop="name">{{ $variant->author }}</span>
                    </a>
                </span>
            </dd>
        </div>
    @endif

    <div class="playbook-meta__item">
        <dt class="playbook-meta__label" data-i18n="playbooks.readingTime">Reading time</dt>
        <dd class="playbook-meta__value">{{ $variant->readingTimeMinutes }} min</dd>
    </div>

    <div class="playbook-meta__item">
        <dt class="playbook-meta__label" data-i18n="playbooks.updated">Updated</dt>
        <dd class="playbook-meta__value">
            <time datetime="{{ $modifiedAt->toIso8601String() }}">
                {{ $modifiedAt->format('M j, Y') }}
            </time>
        </dd>
    </div>

    @if (count($variant->tags) > 0)
        <div class="playbook-meta__item playbook-meta__item--tags">
            <dt class="playbook-meta__label" data-i18n="playbooks.tags">Tags</dt>
            <dd class="playbook-meta__tags">
                @foreach ($variant->tags as $tag)
                    <span class="playbook-meta__tag">{{ $tag }}</span>
                @endforeach
            </dd>
        </div>
    @endif
</dl>
