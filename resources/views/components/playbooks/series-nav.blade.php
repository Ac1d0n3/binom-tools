@props(['playbook'])

@if ($playbook->series)
    @php
        $series = $playbook->series;
    @endphp

    <section class="playbook-series" aria-labelledby="playbook-series-title">
        <h2
            id="playbook-series-title"
            class="playbook-series__title"
            data-playbook-series-title
            data-text-de="{{ $series->titleDe }}"
            data-text-en="{{ $series->titleEn }}"
        >{{ $series->titleEn }}</h2>

        <p
            class="playbook-series__part-of"
            data-playbook-series-part-of
            data-text-de="Teil {{ $series->currentPart }} von {{ $series->totalParts() }}"
            data-text-en="Part {{ $series->currentPart }} of {{ $series->totalParts() }}"
        >Part {{ $series->currentPart }} of {{ $series->totalParts() }}</p>

        <div class="playbook-series__list" role="list">
            @foreach ($series->parts as $part)
                @if ($part->isCurrent)
                    <span
                        class="playbook-series__option playbook-series__option--active"
                        role="listitem"
                        aria-current="true"
                    >
                        <span class="playbook-series__option-label">
                            <span data-i18n="playbooks.seriesPartLabel">Part</span>
                            {{ $part->part }}:
                            <span
                                data-playbook-series-part-title
                                data-text-de="{{ $part->titleDe }}"
                                data-text-en="{{ $part->titleEn }}"
                            >{{ $part->titleEn }}</span>
                        </span>
                    </span>
                @else
                    <a
                        href="{{ locale_route('playbooks.show', ['slug' => $part->slug]) }}"
                        class="playbook-series__option"
                        role="listitem"
                    >
                        <span class="playbook-series__option-label">
                            <span data-i18n="playbooks.seriesPartLabel">Part</span>
                            {{ $part->part }}:
                            <span
                                data-playbook-series-part-title
                                data-text-de="{{ $part->titleDe }}"
                                data-text-en="{{ $part->titleEn }}"
                            >{{ $part->titleEn }}</span>
                        </span>
                    </a>
                @endif
            @endforeach
        </div>
    </section>
@endif
