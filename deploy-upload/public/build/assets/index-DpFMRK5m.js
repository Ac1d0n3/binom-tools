import{n as e}from"./locale-B3wqqiVv.js";import{a as t,i as n,n as r,r as i,t as a}from"./tool-utils-CV-1LRXR.js";import{c as o,h as s,o as c,p as l,r as u}from"./pii-meta-D_9Jsj4F.js";import{a as d,c as f,i as p,r as m}from"./schema-storage-C6SLgBnN.js";import{a as h,n as g,s as _}from"./validation-ui-DZX4dsJT.js";var v={de:{"govMacro.howto.summary":`So funktioniert's`,"govMacro.howto.overview.intro":`Schritt 1/4 der dbt PII Governance Einrichtung — hier kopierst du Laufzeit-Makros, Generic Tests und SETUP.md in dein dbt-Projekt.`,"govMacro.howto.overview.step1":`Warehouse wählen — steuert Mask- und Null-SQL in pii_governance.sql (Snowflake, BigQuery, …).`,"govMacro.howto.overview.step2":`Access-Rollen und access_groups setzen — Einstellungen werden mit Steps 2–4 geteilt (localStorage).`,"govMacro.howto.overview.step3":`pii_governance.sql nach macros/ kopieren — Basis für pii_column_for_role() in Step 2.`,"govMacro.howto.overview.step4":`pii_reviewed.sql nach tests/generic/ kopieren — CI-Gate für meta.pii-reviewed: true.`,"govMacro.howto.overview.step5":`SETUP.md ins Projekt-Root kopieren — Dateibaum aller 4 Steps und dbt-Befehle.`,"govMacro.howto.overview.step6":`Weiter zu Step 2 (Policy Generator) für pii_details schema.yml und Secure Model.`,"govMacro.howto.overview.step7":`Step 3 (Table Gate) für unreviewed Models; Step 4 (PII Recommend) für Spalten-Audits.`,"govMacro.howto.overview.tip":`Tipp: Warehouse und Rollen einmal hier setzen — in Steps 2–4 sind sie bereits vorausgefüllt.`,"govMacro.howto.warehouse.intro":`Das Warehouse bestimmt die SQL-Syntax in den generierten Makros — Maskierung und Null-Werte sind dialect-spezifisch.`,"govMacro.howto.warehouse.step1":`Snowflake, BigQuery, Redshift, Databricks oder Postgres wählen.`,"govMacro.howto.warehouse.step2":`Nach Änderung wird pii_governance.sql automatisch neu generiert.`,"govMacro.howto.warehouse.tip":`Muss zum Warehouse deines dbt-Profils passen.`,"govMacro.howto.access.intro":`Zugriffsmodell und Model access_groups — werden in Steps 2–4 für YAML-Beispiele mitgenutzt.`,"govMacro.howto.access.step1":`Use Access Roles: Whitelist pro Spalte (wer sieht unmaskiert).`,"govMacro.howto.access.step2":`Use Access Roles aus: masked- und unmasked-Listen.`,"govMacro.howto.access.step3":`Default model access_groups: meta.access_groups für Gate (Step 3) und neue Models.`,"govMacro.howto.access.tip":`Rollen kommagetrennt: analyst, support, admin, dpo`,"govMacro.howto.governance.intro":`Laufzeit-Makros — lesen meta.pii_details aus schema.yml (Step 2), nicht pii_recommend.`,"govMacro.howto.governance.step1":`pii_mask: maskierte Ausgabe je nach Kategorie.`,"govMacro.howto.governance.step2":`pii_column_for_role: Rohdaten, Maske oder null pro Rolle.`,"govMacro.howto.governance.step3":`pii_effective_meta / pii_model_accessible: liest Graph-Metadaten zur Laufzeit.`,"govMacro.howto.governance.tip":`Nach Warehouse- oder Rollen-Änderung neu kopieren und in macros/pii_governance.sql ersetzen.`,"govMacro.howto.test.intro":`Generic Test als CI-Gate — schlägt fehl, wenn ein Model meta.pii-reviewed: true fehlt.`,"govMacro.howto.test.step1":`Kopiere nach tests/generic/pii_reviewed.sql.`,"govMacro.howto.test.step2":`Ausführen: dbt test --select test_type:generic`,"govMacro.howto.test.tip":`Erst nach manuellem Review pii-reviewed auf true setzen (Step 2 Beispiel).`,"govMacro.howto.setup.intro":`SETUP.md dokumentiert den kompletten Copy-Paste-Ablauf aller 4 Steps.`,"govMacro.howto.setup.step1":`Dateibaum: welche Datei aus welchem Step wohin gehört.`,"govMacro.howto.setup.step2":`dbt_project.yml vars (pii_user_role) und Verify-Befehle.`,"govMacro.howto.setup.tip":`Im Projekt-Root ablegen — Referenz für das Team.`},en:{"govMacro.howto.summary":`How it works`,"govMacro.howto.overview.intro":`Step 1/4 of the dbt PII governance setup — copy runtime macros, generic tests, and SETUP.md into your dbt project.`,"govMacro.howto.overview.step1":`Choose warehouse — controls mask and null SQL in pii_governance.sql (Snowflake, BigQuery, …).`,"govMacro.howto.overview.step2":`Set access roles and access_groups — settings shared with steps 2–4 (localStorage).`,"govMacro.howto.overview.step3":`Copy pii_governance.sql to macros/ — basis for pii_column_for_role() in step 2.`,"govMacro.howto.overview.step4":`Copy pii_reviewed.sql to tests/generic/ — CI gate for meta.pii-reviewed: true.`,"govMacro.howto.overview.step5":`Copy SETUP.md to project root — file tree for all 4 steps and dbt commands.`,"govMacro.howto.overview.step6":`Continue to step 2 (Policy Generator) for pii_details schema.yml and secure model.`,"govMacro.howto.overview.step7":`Step 3 (Table Gate) for unreviewed models; step 4 (PII Recommend) for column audits.`,"govMacro.howto.overview.tip":`Tip: Set warehouse and roles once here — steps 2–4 are pre-filled.`,"govMacro.howto.warehouse.intro":`Warehouse determines SQL syntax in generated macros — masking and null values are dialect-specific.`,"govMacro.howto.warehouse.step1":`Choose Snowflake, BigQuery, Redshift, Databricks, or Postgres.`,"govMacro.howto.warehouse.step2":`pii_governance.sql regenerates automatically after changes.`,"govMacro.howto.warehouse.tip":`Must match the warehouse of your dbt profile.`,"govMacro.howto.access.intro":`Access model and model access_groups — used in steps 2–4 YAML examples too.`,"govMacro.howto.access.step1":`Use Access Roles: per-column whitelist (who sees unmasked).`,"govMacro.howto.access.step2":`Use Access Roles off: masked and unmasked lists.`,"govMacro.howto.access.step3":`Default model access_groups: meta.access_groups for gate (step 3) and new models.`,"govMacro.howto.access.tip":`Enter roles comma-separated: analyst, support, admin, dpo`,"govMacro.howto.governance.intro":`Runtime macros — read meta.pii_details from schema.yml (step 2), not pii_recommend.`,"govMacro.howto.governance.step1":`pii_mask: masked output per category.`,"govMacro.howto.governance.step2":`pii_column_for_role: raw, masked, or null per role.`,"govMacro.howto.governance.step3":`pii_effective_meta / pii_model_accessible: reads graph metadata at runtime.`,"govMacro.howto.governance.tip":`Re-copy and replace macros/pii_governance.sql after warehouse or role changes.`,"govMacro.howto.test.intro":`Generic test as CI gate — fails when a model is missing meta.pii-reviewed: true.`,"govMacro.howto.test.step1":`Copy to tests/generic/pii_reviewed.sql.`,"govMacro.howto.test.step2":`Run: dbt test --select test_type:generic`,"govMacro.howto.test.tip":`Set pii-reviewed to true only after manual review (step 2 example).`,"govMacro.howto.setup.intro":`SETUP.md documents the full copy-paste flow for all 4 steps.`,"govMacro.howto.setup.step1":`File tree: which file from which step goes where.`,"govMacro.howto.setup.step2":`dbt_project.yml vars (pii_user_role) and verify commands.`,"govMacro.howto.setup.tip":`Place in project root — team reference.`}},y={de:{...v.de,"govMacro.pageTitle":`PII Macro Generator`,"govMacro.pageLead":`Schritt 1/4: Laufzeit-Makros und Tests für dein dbt-Projekt kopieren — Einstellungen werden mit den anderen Setup-Tools geteilt.`,"govMacro.warehouse.title":`Warehouse`,"govMacro.warehouse.description":`Steuert SQL-Syntax in den generierten Makros.`,"govMacro.access.title":`Access-Konfiguration`,"govMacro.access.useRoles":`Access Roles verwenden`,"govMacro.access.defaultRoles":`Default access_roles`,"govMacro.access.maskedRoles":`access_rules.masked`,"govMacro.access.unmaskedRoles":`access_rules.unmasked`,"govMacro.access.groups":`Default model access_groups`,"govMacro.output.governance":`macros/pii_governance.sql`,"govMacro.output.test":`tests/generic/pii_reviewed.sql`,"govMacro.output.setup":`SETUP.md`,"govMacro.copy":`Kopieren`,"shared.syncStatus":`Einstellungen zuletzt von {source} ({time})`,"shared.copied":`Kopiert!`},en:{...v.en,"govMacro.pageTitle":`PII Macro Generator`,"govMacro.pageLead":`Step 1/4: Copy runtime macros and tests into your dbt project — settings are shared with other setup tools.`,"govMacro.warehouse.title":`Warehouse`,"govMacro.warehouse.description":`Controls SQL syntax in generated macros.`,"govMacro.access.title":`Access configuration`,"govMacro.access.useRoles":`Use access roles`,"govMacro.access.defaultRoles":`Default access_roles`,"govMacro.access.maskedRoles":`access_rules.masked`,"govMacro.access.unmaskedRoles":`access_rules.unmasked`,"govMacro.access.groups":`Default model access_groups`,"govMacro.output.governance":`macros/pii_governance.sql`,"govMacro.output.test":`tests/generic/pii_reviewed.sql`,"govMacro.output.setup":`SETUP.md`,"govMacro.copy":`Copy`,"shared.syncStatus":`Settings last saved by {source} ({time})`,"shared.copied":`Copied!`}};function b(e,t){return y[e]?.[t]??y.en[t]??t}function x(){let t=e();document.querySelectorAll(`[data-i18n]`).forEach(e=>{let n=e.getAttribute(`data-i18n`);if(!n?.startsWith(`govMacro.`)&&!n?.startsWith(`shared.`))return;let r=b(t,n);r&&(e.textContent=r)})}function S(e){let t=l(e.selectedWarehouse),n=t.nullCast();return`{# macros/pii_governance.sql — Step 1: PII Macro Generator #}
{# Warehouse: ${t.label} | Copy into macros/ in your dbt project #}
{# Requires: schema meta.pii_details (Step 2) — pii_recommend is NOT applied at runtime #}

{% macro pii_mask(column_name, category) %}
  ${t.maskExpr(`column_name`)}
{% endmacro %}

{% macro pii_effective_meta(column_meta) %}
  {% if column_meta is mapping and column_meta.get('pii_details') is not none %}
    {{ return(column_meta.get('pii_details')) }}
  {% else %}
    {{ return(none) }}
  {% endif %}
{% endmacro %}

{% macro pii_column_for_role(column_name, user_role, model_name) %}
  {% set node = graph.nodes.get('model.' ~ project_name ~ '.' ~ model_name) %}
  {% if node is none %}
    {{ return(column_name) }}
  {% endif %}

  {% set col_meta = none %}
  {% for col in node.columns.values() %}
    {% if col.name == column_name %}
      {% set col_meta = col.meta %}
    {% endif %}
  {% endfor %}

  {% set meta = pii_effective_meta(col_meta) %}
  {% if meta is none %}
    {{ column_name }}
  {% elif meta.get('access_roles') is not none %}
    {% if user_role in meta.get('access_roles', []) %}
      {{ column_name }}
    {% else %}
      {{ pii_mask(column_name, meta.get('category')) }}
    {% endif %}
  {% elif meta.get('access_rules') is mapping %}
    {% if user_role in meta.get('access_rules', {}).get('unmasked', []) %}
      {{ column_name }}
    {% elif user_role in meta.get('access_rules', {}).get('masked', []) %}
      {{ pii_mask(column_name, meta.get('category')) }}
    {% else %}
      ${n}
    {% endif %}
  {% else %}
    {{ column_name }}
  {% endif %}
{% endmacro %}

{% macro pii_model_accessible(model_name, user_role) %}
  {% set node = graph.nodes.get('model.' ~ project_name ~ '.' ~ model_name) %}
  {% if node is none %}
    {{ return(true) }}
  {% endif %}
  {% set groups = node.meta.get('access_groups', []) %}
  {% if groups | length == 0 %}
    {{ return(true) }}
  {% endif %}
  {{ return(user_role in groups) }}
{% endmacro %}
`}function C(){return`{# tests/generic/pii_reviewed.sql — Step 1: PII Macro Generator #}

{% test pii_reviewed(model) %}
  {% if model.meta.get('pii-reviewed') is not true %}
    select 'Model {{ model.name }} missing meta.pii-reviewed: true' as failure_reason
  {% else %}
    select 1 as ok where 1 = 0
  {% endif %}
{% endtest %}
`}function w(e){return`# dbt PII Governance — SETUP

Generated for **${l(e.selectedWarehouse).label}**. Settings are configured in binom-tools and shared via localStorage across all setup steps.

## Copy these files into your dbt project

| File | From step | Target path |
|------|-----------|-------------|
| \`pii_governance.sql\` | 1 — PII Macro Generator | \`macros/pii_governance.sql\` |
| \`pii_reviewed.sql\` | 1 — PII Macro Generator | \`tests/generic/pii_reviewed.sql\` |
| \`example_table.yml\` (pii_details) | 2 — PII Policy Generator | \`models/schema/example_table.yml\` |
| \`example_table_secure.sql\` | 2 — PII Policy Generator | \`models/marts/example_table_secure.sql\` |
| \`pii_table_gate.sql\` | 3 — PII Table Gate Generator | \`macros/pii_table_gate.sql\` |
| \`example_table_gate.yml\` | 3 — PII Table Gate Generator | \`models/schema/example_table_gate.yml\` |
| \`pii_audit_by_name.sql\` | 4 — PII Recommend Generator | \`macros/pii_audit_by_name.sql\` |
| \`pii_content_scan.sql\` | 4 — PII Recommend Generator | \`macros/pii_content_scan.sql\` |
| \`example_table.yml\` (pii_recommend) | 4 — PII Recommend Generator | compare / use for new tables |

## dbt_project.yml vars (example)

\`\`\`yaml
vars:
  pii_user_role: analyst
  pii_review_roles: [dpo, security]
\`\`\`

## Verify

\`\`\`bash
dbt run-operation pii_audit_unreviewed_models
dbt run-operation pii_audit_by_name
dbt run-operation pii_audit_by_content --args '{"sample_limit": 1000}'
dbt test --select test_type:generic
dbt run --select example_table_secure --vars '{"pii_user_role": "analyst"}'
\`\`\`

## Meta conventions

- \`meta.pii_recommend\` — suggestion only (Step 4 example). Not used by runtime macros.
- \`meta.pii_details\` — production classification (Step 2 example). Used by \`pii_column_for_role\`.
- \`meta.pii-reviewed: true\` — required after manual review (\`pii_reviewed\` test).
- Unreviewed models — hidden via Step 3 gate until \`pii-reviewed: true\`.

Model: **${e.modelName}** | PII version: **${e.piiVersion}**
`}if(!document.getElementById(`dbt-governance-macro-generator-app`))throw Error(`Governance macro generator root element not found`);var T=c(),E=null,D={warehouse:document.getElementById(`gov-warehouse`),useAccessRoles:document.getElementById(`gov-use-access-roles`),accessRolesPanel:document.getElementById(`gov-access-roles-panel`),accessRulesPanel:document.getElementById(`gov-access-rules-panel`),defaultAccessRoles:document.getElementById(`gov-default-access-roles`),maskedRoles:document.getElementById(`gov-masked-roles`),unmaskedRoles:document.getElementById(`gov-unmasked-roles`),accessGroups:document.getElementById(`gov-access-groups`),validationBanner:document.getElementById(`gov-validation-banner`),governancePre:document.getElementById(`gov-governance-pre`),testPre:document.getElementById(`gov-test-pre`),setupPre:document.getElementById(`gov-setup-pre`),copyGovernanceBtn:document.getElementById(`gov-copy-governance-btn`),copyTestBtn:document.getElementById(`gov-copy-test-btn`),copySetupBtn:document.getElementById(`gov-copy-setup-btn`),syncStatus:document.getElementById(`gov-sync-status`)};function O(){return e()}function k(e,t={}){return h(O(),b)(e,t)}function A(){let e=r(D.warehouse);e&&(T.selectedWarehouse=e),T.useAccessRoles=D.useAccessRoles.checked,T.defaultAccessRoles=i(D.defaultAccessRoles.value),T.accessRules={masked:i(D.maskedRoles.value),unmasked:i(D.unmaskedRoles.value)},T.defaultModelAccessGroups=i(D.accessGroups.value),o(T)}function j(){t(T,D.warehouse),D.useAccessRoles.checked=T.useAccessRoles,D.defaultAccessRoles.value=T.defaultAccessRoles.join(`, `),D.maskedRoles.value=T.accessRules.masked.join(`, `),D.unmaskedRoles.value=T.accessRules.unmasked.join(`, `),D.accessGroups.value=(T.defaultModelAccessGroups??[]).join(`, `),M()}function M(){let e=D.useAccessRoles.checked;D.accessRolesPanel&&(D.accessRolesPanel.hidden=!e),D.accessRulesPanel&&(D.accessRulesPanel.hidden=e)}function N(){let e=[...E?[E]:[],..._(T,`govMacro`)];g({bannerEl:D.validationBanner,outputPres:[D.governancePre,D.testPre,D.setupPre],issues:e,builds:[{el:D.governancePre,fn:()=>S(T)},{el:D.testPre,fn:()=>C()},{el:D.setupPre,fn:()=>w(T)}],t:k})}function P(){m(T,`dbt-governance-macro-generator`)}function F(){[D.warehouse,D.useAccessRoles,D.defaultAccessRoles,D.maskedRoles,D.unmaskedRoles,D.accessGroups].forEach(e=>{e?.addEventListener(`input`,()=>{A(),N(),P()}),e?.addEventListener(`change`,()=>{A(),M(),N(),P()})}),D.copyGovernanceBtn?.addEventListener(`click`,()=>a(D.copyGovernanceBtn,S(T),e=>b(O(),e))),D.copyTestBtn?.addEventListener(`click`,()=>a(D.copyTestBtn,C(),e=>b(O(),e))),D.copySetupBtn?.addEventListener(`click`,()=>a(D.copySetupBtn,w(T),e=>b(O(),e)))}function I(){let e=d();if(p(e)){E={code:`storage_corrupt`,messageKey:`validation.storageCorrupt`,severity:`warning`};return}e&&`state`in e&&(T=u(T,e.state),n(D.syncStatus,e.meta,e=>b(O(),e)))}function L(){D.warehouse&&(D.warehouse.innerHTML=s.map(e=>`<option value="${e}">${e}</option>`).join(``))}function R(){x(),L(),I(),j(),N(),F(),f(({state:e,meta:t})=>{!e||t?.source===`dbt-governance-macro-generator`||(T=u(T,e),j(),N(),n(D.syncStatus,t,e=>b(O(),e)))}),window.addEventListener(`binom-tools:locale`,()=>{x();let e=d();n(D.syncStatus,e&&`meta`in e?e.meta:null,e=>b(O(),e)),N()})}R();