@extends('profile::layouts.shell')

@section('title', (($workspace['name'] ?? null) ? 'Edit workspace' : 'Create workspace') . ' — ' . config('app.name'))

@section('profile_content')
    @php $isEdit = is_array($workspace ?? null); @endphp
    <div class="tools-content tools-content--wide sp-app admin-hub">
        <h1 class="tools-page-title">{{ $isEdit ? 'Edit workspace' : 'Create workspace' }}</h1>
        <form method="post" action="{{ $isEdit ? locale_route('profile.workspaces.update', ['workspaceId' => $workspace['id']]) : locale_route('profile.workspaces.store') }}" class="admin-hub__editor" style="max-width:36rem">
            @csrf
            @if ($isEdit) @method('PUT') @endif
            <div class="admin-hub__field">
                <label for="ws-name">Name</label>
                <input id="ws-name" name="name" value="{{ old('name', $workspace['name'] ?? '') }}" required>
            </div>
            <div class="admin-hub__field">
                <label for="ws-stack">Stack</label>
                <select id="ws-stack" name="stack" required>
                    @foreach ($stacks as $id => $labels)
                        <option value="{{ $id }}" @selected(old('stack', $workspace['stack'] ?? 'unknown') === $id)>{{ $labels['en'] ?? $id }}</option>
                    @endforeach
                </select>
                @if ($isEdit && ($workspace['stack'] ?? '') === 'custom' && is_array($workspace['customStack'] ?? null))
                    <p class="admin-hub__meta">Custom stack selection is stored on this workspace and syncs from the Governance Advisor / Stack Builder.</p>
                @endif
                @if ($isEdit && ! empty($workspace['savedStacks']) && is_array($workspace['savedStacks']))
                    <p class="admin-hub__meta">{{ count($workspace['savedStacks']) }} saved named stack(s) available in the Stack Builder.</p>
                @endif
            </div>
            <div class="admin-hub__field">
                <label for="ws-label">Label</label>
                <input id="ws-label" name="label" value="{{ old('label', $workspace['label'] ?? '') }}">
            </div>
            <div class="admin-hub__field">
                <label for="ws-notes">Notes</label>
                <textarea id="ws-notes" name="notes" class="admin-hub__textarea" style="min-height:6rem">{{ old('notes', $workspace['notes'] ?? '') }}</textarea>
            </div>
            @if ($errors->any())
                <p class="admin-hub__meta">{{ $errors->first() }}</p>
            @endif
            <div class="admin-hub__toolbar">
                <button type="submit" class="tools-btn tools-btn--primary">Save</button>
                <a class="tools-btn" href="{{ locale_route('profile.workspaces.index') }}">Cancel</a>
            </div>
        </form>
    </div>
@endsection
