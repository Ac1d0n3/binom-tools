@php
    $isCreate = (bool) ($isCreate ?? false);
@endphp
@if ($isCreate)
    <label class="admin-hub__field">
        <span>Id (optional)</span>
        <input class="tools-input" type="text" name="id" pattern="[a-z0-9-]+" maxlength="80" placeholder="story-my-slug">
    </label>
@endif
<label class="admin-hub__field">
    <span>Kind</span>
    <select class="tools-input" name="kind" required data-admin-advisor-kind>
        @if ($canCreateStory)
            <option value="story">Story</option>
            <option value="series">Series</option>
        @endif
        @if ($canCreateVendorSource)
            <option value="supplier">Source (supplier)</option>
            <option value="vendor">Vendor</option>
        @endif
    </select>
</label>
<label class="admin-hub__field" data-admin-advisor-ref-wrap="story">
    <span>Story</span>
    <select class="tools-input" name="ref" data-admin-advisor-ref="story">
        @foreach ($storyOptions as $slug => $label)
            <option value="{{ $slug }}">{{ $label }}</option>
        @endforeach
    </select>
</label>
<label class="admin-hub__field" data-admin-advisor-ref-wrap="series" hidden>
    <span>Series</span>
    <select class="tools-input" data-admin-advisor-ref="series" disabled>
        @foreach ($seriesOptions as $id => $label)
            <option value="{{ $id }}">{{ $label }}</option>
        @endforeach
    </select>
</label>
<label class="admin-hub__field" data-admin-advisor-ref-wrap="supplier" hidden>
    <span>Source</span>
    <select class="tools-input" data-admin-advisor-ref="supplier" disabled>
        @foreach ($supplierOptions as $id => $label)
            <option value="{{ $id }}">{{ $label }}</option>
        @endforeach
    </select>
</label>
<label class="admin-hub__field" data-admin-advisor-ref-wrap="vendor" hidden>
    <span>Vendor</span>
    <select class="tools-input" data-admin-advisor-ref="vendor" disabled>
        @foreach ($vendorOptions as $id => $label)
            <option value="{{ $id }}">{{ $label }}</option>
        @endforeach
    </select>
</label>
<label class="admin-hub__field admin-hub__field--check">
    <input type="checkbox" name="enabled" value="1" checked>
    <span data-text-de="Aktiv" data-text-en="Enabled">Enabled</span>
</label>
<label class="admin-hub__field">
    <span>Group</span>
    <select class="tools-input" name="group">
        <option value="resources">resources</option>
        <option value="suppliers">suppliers</option>
        <option value="certs">certs</option>
        <option value="gaps">gaps</option>
    </select>
</label>
<label class="admin-hub__field">
    <span>Icon (Font Awesome)</span>
    <input class="tools-input" type="text" name="icon" maxlength="80" placeholder="fa-book">
</label>
<label class="admin-hub__field">
    <span>Score (0–100)</span>
    <input class="tools-input" type="number" name="score" min="0" max="100" value="70">
</label>
<label class="admin-hub__field">
    <span>Tags (comma-separated)</span>
    <input class="tools-input" type="text" name="tags" maxlength="500" placeholder="help, learning, stack">
</label>
<label class="admin-hub__field">
    <span>Title DE</span>
    <input class="tools-input" type="text" name="title_de" required maxlength="240">
</label>
<label class="admin-hub__field">
    <span>Title EN</span>
    <input class="tools-input" type="text" name="title_en" required maxlength="240">
</label>
<label class="admin-hub__field">
    <span>Reason DE</span>
    <textarea class="tools-input" name="reason_de" required rows="3" maxlength="2000"></textarea>
</label>
<label class="admin-hub__field">
    <span>Reason EN</span>
    <textarea class="tools-input" name="reason_en" required rows="3" maxlength="2000"></textarea>
</label>
<p class="admin-hub__meta" data-text-de="When-Filter (leer = keine Einschränkung)" data-text-en="When filters (empty = no restriction)">When filters (empty = no restriction)</p>
<label class="admin-hub__field">
    <span>Goals</span>
    <input class="tools-input" type="text" name="when_goals" maxlength="500" placeholder="stack, supplier">
</label>
<label class="admin-hub__field">
    <span>Scenarios</span>
    <input class="tools-input" type="text" name="when_scenarios" maxlength="500" placeholder="extend, help">
</label>
<label class="admin-hub__field">
    <span>Domains</span>
    <input class="tools-input" type="text" name="when_domains" maxlength="500" placeholder="crm, erp">
</label>
<label class="admin-hub__field">
    <span>Platforms</span>
    <input class="tools-input" type="text" name="when_platforms" maxlength="500" placeholder="databricks, fabric">
</label>
<label class="admin-hub__field">
    <span>Roles</span>
    <input class="tools-input" type="text" name="when_roles" maxlength="500" placeholder="architect, steward">
</label>
