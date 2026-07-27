@extends('admin::layouts.shell')

@section('title', 'Glossary — Admin — ' . config('app.name'))

@section('admin_content')
    <div class="tools-content tools-content--wide sp-app admin-hub">
        <h1 class="tools-page-title">Glossary</h1>
        <x-admin.help id="glossary-admin">
            <p data-text-de="Terms in content/catalogs/glossary/terms-core.json ({{ $total }} total, showing up to 200)." data-text-en="Terms in content/catalogs/glossary/terms-core.json ({{ $total }} total, showing up to 200).">Terms in content/catalogs/glossary/terms-core.json ({{ $total }} total, showing up to 200).</p>
        </x-admin.help>
        <form method="get" class="admin-hub__toolbar">
            <input type="search" name="q" value="{{ $q }}" placeholder="Search…">
            <button class="tools-btn" type="submit">Filter</button>
        </form>
        <form method="post" action="{{ locale_route('admin.glossary.store') }}" class="admin-hub__editor" style="max-width:40rem;margin-bottom:1.5rem">
            @csrf
            <div class="admin-hub__field"><label>Id (optional)</label><input name="id" pattern="[a-z0-9-]+"></div>
            <div class="admin-hub__field"><label>Term DE</label><input name="term_de" required></div>
            <div class="admin-hub__field"><label>Term EN</label><input name="term_en" required></div>
            <div class="admin-hub__field"><label>Definition DE</label><textarea name="definition_de" class="admin-hub__textarea" style="min-height:5rem" required></textarea></div>
            <div class="admin-hub__field"><label>Definition EN</label><textarea name="definition_en" class="admin-hub__textarea" style="min-height:5rem" required></textarea></div>
            <div class="admin-hub__field"><label>Category</label><input name="category" value="data"></div>
            <button class="tools-btn tools-btn--primary" type="submit">Add term</button>
        </form>
        <div class="sp-list">
            @foreach ($terms as $term)
                <div class="sp-list__row">
                    <div class="sp-list__identity">
                        <strong>{{ $term['term']['en'] ?? $term['id'] ?? '-' }}</strong>
                        <span class="admin-hub__meta">{{ $term['id'] ?? '' }} · {{ $term['category'] ?? '' }}</span>
                    </div>
                    <div class="sp-list__actions">
                        <form method="post" action="{{ locale_route('admin.glossary.update', ['termId' => $term['id']]) }}" style="display:grid;gap:.35rem;min-width:18rem">
                            @csrf
                            @method('PUT')
                            <input name="term_de" value="{{ $term['term']['de'] ?? '' }}" required>
                            <input name="term_en" value="{{ $term['term']['en'] ?? '' }}" required>
                            <textarea name="definition_de" class="admin-hub__textarea" style="min-height:4rem" required>{{ $term['definition']['de'] ?? '' }}</textarea>
                            <textarea name="definition_en" class="admin-hub__textarea" style="min-height:4rem" required>{{ $term['definition']['en'] ?? '' }}</textarea>
                            <input name="category" value="{{ $term['category'] ?? 'data' }}">
                            <button class="tools-btn tools-btn--small" type="submit">Save</button>
                        </form>
                        <form method="post" action="{{ locale_route('admin.glossary.destroy', ['termId' => $term['id']]) }}" onsubmit="return confirm('Delete term?');">
                            @csrf
                            @method('DELETE')
                            <button class="tools-btn tools-btn--small tools-btn--danger" type="submit">Delete</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
