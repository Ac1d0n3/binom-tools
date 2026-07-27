@extends('admin::layouts.shell')

@section('title', 'Link check — ' . config('app.name'))

@section('admin_content')
    <div class="tools-content tools-content--wide">
        <h1 class="tools-page-title" data-text-de="Link-Checker" data-text-en="Link checker">Link checker</h1>
        <p class="tools-page-lead" data-text-de="Externe URLs aus Katalogen und Stories — nur für Admins." data-text-en="External URLs from catalogs and stories — admins only.">
            External URLs from catalogs and stories — admins only.
        </p>

        @if (session('status') === 'link-check-done')
            <p class="tools-flash tools-flash--success" data-text-de="Scan gespeichert." data-text-en="Scan saved.">Scan saved.</p>
        @endif

        <div class="tools-overview-toolbar" style="gap: 0.75rem; flex-wrap: wrap;">
            <form method="post" action="{{ locale_route('accounts.link-check.run') }}">
                @csrf
                <button type="submit" class="tools-btn tools-btn--primary" data-text-de="Scan starten" data-text-en="Run scan">Run scan</button>
                <input type="hidden" name="limit" value="0">
            </form>
            <p class="tools-page-lead" style="margin: 0;">
                <span data-text-de="Inventar (Vorkommen)" data-text-en="Inventory (occurrences)">Inventory (occurrences)</span>:
                <strong>{{ $inventoryCount }}</strong>
                @if (! empty($latest['checkedAt']))
                    · <span data-text-de="Zuletzt" data-text-en="Last">Last</span>: {{ $latest['checkedAt'] }}
                @endif
            </p>
        </div>

        @if (! empty($latest['summary']))
            <p class="tools-page-lead">
                ok {{ $latest['summary']['ok'] ?? 0 }}
                · redirect {{ $latest['summary']['redirect'] ?? 0 }}
                · broken {{ $latest['summary']['broken'] ?? 0 }}
                · error {{ $latest['summary']['error'] ?? 0 }}
            </p>
        @endif

        <nav class="tools-filter-sidebar__tabs" role="tablist" aria-label="Filter" style="margin-bottom: 1rem;">
            @foreach (['all' => 'All', 'broken' => 'Broken', 'error' => 'Error', 'redirect' => 'Redirect', 'ok' => 'OK'] as $key => $label)
                <a
                    class="tools-filter-sidebar__tab {{ $filter === $key ? 'tools-filter-sidebar__tab--active' : '' }}"
                    href="{{ locale_route('admin.link-check.index') }}{{ $key === 'all' ? '' : '?filter='.$key }}"
                >{{ $label }}</a>
            @endforeach
        </nav>

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
                            <tr>
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
