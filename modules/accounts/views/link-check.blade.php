@extends('admin::layouts.shell')

@section('title', 'Link check — ' . config('app.name'))

@section('admin_content')
    <div class="tools-content tools-content--wide admin-hub" data-overview-filter-root>
        <x-admin.sticky-header :count="count($results)">
            <x-slot:search>
                <input type="search" class="tools-input" data-overview-search placeholder="Search URLs / sources…" aria-label="Search">
            </x-slot:search>
            <x-slot:actions>
                <form method="post" action="{{ locale_route('admin.link-check.run') }}">
                    @csrf
                    <button
                        type="submit"
                        class="tools-btn tools-btn--primary"
                        @disabled(! empty($isRunning))
                        data-text-de="{{ ! empty($isRunning) ? 'Scan läuft…' : 'Scan starten' }}"
                        data-text-en="{{ ! empty($isRunning) ? 'Scan running…' : 'Run scan' }}"
                    >{{ ! empty($isRunning) ? 'Scan running…' : 'Run scan' }}</button>
                    <input type="hidden" name="limit" value="0">
                </form>
            </x-slot:actions>
        </x-admin.sticky-header>

        @if (session('status') === 'link-check-done')
            <p class="tools-flash tools-flash--success" data-text-de="Scan gespeichert." data-text-en="Scan saved.">Scan saved.</p>
        @elseif (session('status') === 'link-check-started')
            <p class="tools-flash tools-flash--success" data-text-de="Scan gestartet — läuft im Hintergrund. Seite gleich neu laden." data-text-en="Scan started — running in the background. Reload shortly.">Scan started — running in the background. Reload shortly.</p>
        @elseif (session('status') === 'link-check-running')
            <p class="tools-flash" data-text-de="Ein Scan läuft bereits." data-text-en="A scan is already running.">A scan is already running.</p>
        @endif

        <p class="admin-hub__meta">
            <span data-text-de="Inventar (Vorkommen)" data-text-en="Inventory (occurrences)">Inventory (occurrences)</span>:
            <strong>{{ $inventoryCount }}</strong>
            @if (! empty($isRunning))
                · <strong data-text-de="Scan läuft…" data-text-en="Scan running…">Scan running…</strong>
            @endif
            @if (! empty($latest['checkedAt']))
                · <span data-text-de="Zuletzt" data-text-en="Last">Last</span>: {{ $latest['checkedAt'] }}
            @endif
            @if (! empty($latest['summary']))
                · ok {{ $latest['summary']['ok'] ?? 0 }}
                · redirect {{ $latest['summary']['redirect'] ?? 0 }}
                · broken {{ $latest['summary']['broken'] ?? 0 }}
                · error {{ $latest['summary']['error'] ?? 0 }}
            @endif
        </p>

        <nav class="tools-filter-sidebar__tabs" role="tablist" aria-label="Filter" style="margin-bottom: 1rem;">
            @foreach (['all' => 'All', 'broken' => 'Broken', 'error' => 'Error', 'redirect' => 'Redirect', 'ok' => 'OK'] as $key => $label)
                <a
                    class="tools-filter-sidebar__tab {{ $filter === $key ? 'tools-filter-sidebar__tab--active' : '' }}"
                    href="{{ locale_route('admin.link-check.index') }}{{ $key === 'all' ? '' : '?filter='.$key }}"
                >{{ $label }}</a>
            @endforeach
        </nav>

        <p class="admin-hub__meta" data-overview-empty hidden data-text-de="Keine Treffer." data-text-en="No matches.">No matches.</p>

        @if ($results === [])
            <p data-text-de="Noch kein Scan — oder keine Treffer für diesen Filter." data-text-en="No scan yet — or no rows for this filter.">
                No scan yet — or no rows for this filter.
            </p>
        @else
            <div class="supplier-table-wrap">
                <table class="supplier-table">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>URL</th>
                            <th>Sources</th>
                            <th>ms</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($results as $row)
                            @php
                                $searchText = strtolower(implode(' ', array_filter([
                                    $row['url'] ?? '',
                                    $row['bucket'] ?? '',
                                    $row['error'] ?? '',
                                    $row['redirectTo'] ?? '',
                                    implode(' ', $row['sources'] ?? []),
                                ])));
                            @endphp
                            <tr data-overview-item data-search-text="{{ $searchText }}">
                                <td>
                                    <strong>{{ $row['bucket'] ?? '?' }}</strong>
                                    @if (! empty($row['status']))
                                        ({{ $row['status'] }})
                                    @endif
                                    @if (! empty($row['error']))
                                        <br><small>{{ $row['error'] }}</small>
                                    @endif
                                    @if (! empty($row['redirectTo']))
                                        <br><small>→ {{ $row['redirectTo'] }}</small>
                                    @endif
                                </td>
                                <td><a href="{{ $row['url'] ?? '#' }}" rel="noopener noreferrer" target="_blank">{{ $row['url'] ?? '-' }}</a></td>
                                <td><small>{{ implode(', ', array_slice($row['sources'] ?? [], 0, 4)) }}</small></td>
                                <td>{{ $row['ms'] ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
