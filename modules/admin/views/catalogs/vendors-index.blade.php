@extends('admin::layouts.shell')

@section('title', 'Vendors — Admin — ' . config('app.name'))

@section('admin_content')
    <div class="tools-content tools-content--wide sp-app admin-hub">
        <h1 class="tools-page-title">Vendors</h1>
        <x-admin.help id="vendors-admin">
            <p data-text-de="Vendor-Labels in content/catalogs/vendor-resources/document.json ({{ $productCount }} Produkte)." data-text-en="Vendor labels in content/catalogs/vendor-resources/document.json ({{ $productCount }} products).">Vendor labels in content/catalogs/vendor-resources/document.json ({{ $productCount }} products).</p>
        </x-admin.help>
        <form method="post" action="{{ locale_route('admin.vendors.store') }}" class="admin-hub__editor" style="max-width:36rem;margin-bottom:1.5rem">
            @csrf
            <div class="admin-hub__field"><label>Id</label><input name="id" required pattern="[a-z0-9-]+"></div>
            <div class="admin-hub__field"><label>Name DE</label><input name="name_de" required></div>
            <div class="admin-hub__field"><label>Name EN</label><input name="name_en" required></div>
            <button class="tools-btn tools-btn--primary" type="submit">Add vendor</button>
        </form>
        <div class="sp-list">
            @foreach ($vendors as $id => $labels)
                <div class="sp-list__row">
                    <div class="sp-list__identity"><strong>{{ $id }}</strong></div>
                    <div class="sp-list__actions">
                        <form method="post" action="{{ locale_route('admin.vendors.update', ['vendorId' => $id]) }}" style="display:inline-flex;gap:.35rem">
                            @csrf
                            @method('PUT')
                            <input name="name_de" value="{{ $labels['de'] ?? '' }}" required>
                            <input name="name_en" value="{{ $labels['en'] ?? '' }}" required>
                            <button class="tools-btn tools-btn--small" type="submit">Save</button>
                        </form>
                        <form method="post" action="{{ locale_route('admin.vendors.destroy', ['vendorId' => $id]) }}" style="display:inline" onsubmit="return confirm('Delete vendor label?');">
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
