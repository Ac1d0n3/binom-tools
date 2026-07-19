{{-- Large JSON payloads as script tags (HTML data-* attributes break on quotes/size). --}}
<script type="application/json" id="sp-bootstrap-templates">@json($templatesJson ?? [])</script>
<script type="application/json" id="sp-bootstrap-stories">@json($storiesJson ?? [])</script>
<script type="application/json" id="sp-bootstrap-account-people">@json($accountPeopleJson ?? [])</script>
<script type="application/json" id="sp-bootstrap-account-teams">@json($accountTeamsJson ?? [])</script>
<script type="application/json" id="sp-bootstrap-server-plans">@json($serverPlansJson ?? [])</script>
<script type="application/json" id="sp-bootstrap-user-templates">@json($userTemplatesJson ?? [])</script>
<script type="application/json" id="sp-bootstrap-account-user">@json($accountUser ?? null)</script>
<script type="application/json" id="sp-bootstrap-read-slugs">@json($readSlugsJson ?? [])</script>
@if (! empty($editTemplateJson))
<script type="application/json" id="sp-bootstrap-edit-template">@json($editTemplateJson)</script>
@endif
