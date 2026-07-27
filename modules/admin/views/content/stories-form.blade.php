@extends('admin::layouts.shell')

@section('title', ($isNew ? 'New story' : 'Edit story') . ' — Admin — ' . config('app.name'))

@section('admin_content')
    <div class="tools-content tools-content--wide sp-app admin-hub">
        <h1 class="tools-page-title">{{ $isNew ? 'New story' : 'Edit: '.$slug }}</h1>
        @if (session('flashDetail'))
            <p class="admin-hub__meta">{{ session('flashDetail') }}</p>
        @endif
        <form method="post" action="{{ $isNew ? locale_route('admin.stories.store') : locale_route('admin.stories.update', ['slug' => $slug]) }}" class="admin-hub__editor">
            @csrf
            @unless ($isNew) @method('PUT') @endunless
            @if ($isNew)
                <div class="admin-hub__field">
                    <label for="slug">Slug</label>
                    <input id="slug" name="slug" value="{{ old('slug', $slug) }}" required pattern="[a-z0-9-]+">
                </div>
            @endif
            <div class="admin-hub__editor-grid admin-hub__editor-grid--split">
                <div class="admin-hub__field">
                    <label for="body_de">DE markdown</label>
                    <textarea id="body_de" name="body_de" class="admin-hub__textarea">{{ old('body_de', $bodyDe) }}</textarea>
                </div>
                <div class="admin-hub__field">
                    <label for="body_en">EN markdown</label>
                    <textarea id="body_en" name="body_en" class="admin-hub__textarea">{{ old('body_en', $bodyEn) }}</textarea>
                </div>
            </div>
            <div class="admin-hub__toolbar">
                <button type="submit" class="tools-btn tools-btn--primary">Save</button>
                <a class="tools-btn" href="{{ locale_route('admin.stories.index') }}">Back</a>
            </div>
        </form>
        @unless ($isNew)
            <form method="post" action="{{ locale_route('admin.stories.upload', ['slug' => $slug]) }}" enctype="multipart/form-data" class="admin-hub__toolbar" style="margin-top:1rem">
                @csrf
                <input type="hidden" name="slug" value="{{ $slug }}">
                <input type="file" name="image" accept="image/*" required>
                <button type="submit" class="tools-btn">Upload image → WebP</button>
            </form>
            <form method="post" action="{{ locale_route('admin.stories.destroy', ['slug' => $slug]) }}" onsubmit="return confirm('Delete both locales?');" style="margin-top:1rem">
                @csrf
                @method('DELETE')
                <button type="submit" class="tools-btn tools-btn--danger">Delete story</button>
            </form>
        @endunless
    </div>
@endsection
