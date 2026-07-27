@extends('admin::layouts.shell')

@section('title', 'Radar admin — ' . config('app.name'))

@section('admin_content')
    <div class="tools-content tools-content--wide sp-app admin-hub">
        <h1 class="tools-page-title">Radar</h1>
        <x-admin.help id="radar-admin">
            <p data-text-de="Quellen und News in content/catalogs/governance-radar/document.json — Speichern ohne Git." data-text-en="Sources and news in content/catalogs/governance-radar/document.json — save without git.">Sources and news in content/catalogs/governance-radar/document.json — save without git.</p>
        </x-admin.help>

        <section class="sp-section">
            <x-admin.page-header title="Add news (DE/EN)" titleDe="News anlegen" titleEn="Add news" />
            <form method="post" action="{{ locale_route('admin.radar.items.store') }}" class="admin-hub__editor" style="max-width:40rem">
                @csrf
                <div class="admin-hub__field"><label>Title DE</label><input name="title_de" required value="{{ old('title_de') }}"></div>
                <div class="admin-hub__field"><label>Title EN</label><input name="title_en" required value="{{ old('title_en') }}"></div>
                <div class="admin-hub__field"><label>URL</label><input name="url" type="url" required value="{{ old('url') }}"></div>
                <div class="admin-hub__field"><label>Language</label>
                    <select name="language"><option value="en">en</option><option value="de">de</option></select>
                </div>
                <div class="admin-hub__field"><label>Summary DE</label><textarea name="summary_de" class="admin-hub__textarea" style="min-height:5rem">{{ old('summary_de') }}</textarea></div>
                <div class="admin-hub__field"><label>Summary EN</label><textarea name="summary_en" class="admin-hub__textarea" style="min-height:5rem">{{ old('summary_en') }}</textarea></div>
                <button class="tools-btn tools-btn--primary" type="submit">Add item</button>
            </form>
        </section>

        <section class="sp-section">
            <x-admin.page-header title="Add source" titleDe="Quelle hinzufügen" titleEn="Add source" />
            <form method="post" action="{{ locale_route('admin.radar.sources.store') }}" class="admin-hub__editor" style="max-width:40rem">
                @csrf
                <div class="admin-hub__field"><label>Id (optional)</label><input name="id" pattern="[a-z0-9-]+" value="{{ old('id') }}"></div>
                <div class="admin-hub__field"><label>Name</label><input name="name" required value="{{ old('name') }}"></div>
                <div class="admin-hub__field"><label>URL</label><input name="url" type="url" required value="{{ old('url') }}"></div>
                <div class="admin-hub__field"><label>Language</label>
                    <select name="language"><option value="en">en</option><option value="de">de</option></select>
                </div>
                <button class="tools-btn tools-btn--primary" type="submit">Add source</button>
            </form>
        </section>

        <section class="sp-section">
            <x-admin.page-header title="Sources" titleDe="Quellen" titleEn="Sources" />
            <div class="sp-list">
                @foreach ($sources as $source)
                    <div class="sp-list__row">
                        <div class="sp-list__identity">
                            <strong>{{ $source['name'] ?? $source['id'] ?? '-' }}</strong>
                            <span class="admin-hub__meta">{{ $source['id'] ?? '' }} · {{ $source['language'] ?? '' }}</span>
                        </div>
                        <div class="sp-list__actions">
                            <form method="post" action="{{ locale_route('admin.radar.sources.update', ['sourceId' => $source['id']]) }}" style="display:flex;flex-wrap:wrap;gap:.35rem;align-items:center">
                                @csrf
                                @method('PUT')
                                <input name="name" value="{{ $source['name'] ?? '' }}" required>
                                <input name="url" type="url" value="{{ $source['url'] ?? '' }}" required>
                                <select name="language">
                                    <option value="en" @selected(($source['language'] ?? '') === 'en')>en</option>
                                    <option value="de" @selected(($source['language'] ?? '') === 'de')>de</option>
                                </select>
                                <input type="hidden" name="type" value="{{ $source['type'] ?? 'Governance News' }}">
                                <button class="tools-btn tools-btn--small" type="submit">Save</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="sp-section">
            <x-admin.page-header title="Recent items" titleDe="Aktuelle Items" titleEn="Recent items" />
            <div class="sp-list">
                @foreach (array_slice(array_reverse($items), 0, 30) as $item)
                    <div class="sp-list__row">
                        <div class="sp-list__identity">
                            <strong>{{ is_array($item['title_i18n'] ?? null) ? ($item['title_i18n']['en'] ?? $item['title'] ?? '-') : ($item['title'] ?? '-') }}</strong>
                            <span class="admin-hub__meta">{{ $item['id'] ?? '' }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </div>
@endsection
