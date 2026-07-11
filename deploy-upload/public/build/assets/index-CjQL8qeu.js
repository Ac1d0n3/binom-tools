import{n as e}from"./locale-6DFKnQyG.js";import{i as t,r as n,t as r}from"./tool-utils-Cwe7qQBx.js";import{o as i,r as a}from"./pii-meta-D_9Jsj4F.js";import{a as o,c as s,i as c,r as l}from"./schema-storage-C6SLgBnN.js";import{a as u,n as d,s as f}from"./validation-ui-DZX4dsJT.js";var p={de:{"gate.howto.summary":`So funktioniert's`,"gate.howto.overview.intro":`Schritt 3/4 — Ungeprüfte Models identifizieren und für normale Rollen verstecken, bis meta.pii-reviewed: true (Step 2).`,"gate.howto.overview.step1":`Review-Rollen festlegen — wer darf unreviewed Models sehen (z. B. dpo, security).`,"gate.howto.overview.step2":`Default access_groups setzen — geteilt mit Step 1/2/4.`,"gate.howto.overview.step3":`pii_table_gate.sql nach macros/ kopieren — referenziert pii_model_accessible() aus Step 1.`,"gate.howto.overview.step4":`Gated-View-Beispiel und Gate-YAML für neue Tabellen kopieren.`,"gate.howto.overview.step5":`dbt run-operation pii_audit_unreviewed_models — listet Models ohne pii-reviewed: true.`,"gate.howto.overview.tip":`Nach Review in Step 2 pii-reviewed: true setzen — dann greift pii_model_accessible() statt Gate.`,"gate.howto.reviewRoles.intro":`Review-Rollen sehen unreviewed Models zum Klassifizieren. Alle anderen Rollen erhalten keinen Zugriff.`,"gate.howto.reviewRoles.step1":`Kommagetrennt eintragen: dpo, security, admin …`,"gate.howto.reviewRoles.step2":`Wird in dbt_project.yml als var pii_review_roles überschreibbar.`,"gate.howto.reviewRoles.tip":`Nicht mit access_groups verwechseln — Review-Rollen sind nur für den Übergangszustand.`,"gate.howto.macro.intro":`Laufzeit-Gate — pii_model_visible_for_role() kombiniert Review-Status mit Step-1-Zugriff.`,"gate.howto.macro.step1":`Kopiere nach macros/pii_table_gate.sql.`,"gate.howto.macro.step2":`Nutze in Secure Views: {% if pii_model_visible_for_role(model, user_role) %}.`,"gate.howto.macro.tip":`Step 1 pii_governance.sql muss im Projekt liegen.`,"gate.howto.yaml.intro":`Beispiel-meta für neue Tabellen — pii-reviewed: false bis Step 2 abgeschlossen ist.`,"gate.howto.yaml.step1":`Als Vorlage für schema.yml neuer Models verwenden.`,"gate.howto.yaml.tip":`Nach Review: pii-reviewed: true und pii_details aus Step 2.`,"gate.howto.view.intro":`Beispiel-Gated-View — zeigt Daten nur wenn pii_model_visible_for_role() true zurückgibt.`,"gate.howto.view.step1":`Anpassen und nach models/marts/ kopieren.`,"gate.howto.view.tip":`Für Sources analog ref() durch source() ersetzen.`},en:{"gate.howto.summary":`How it works`,"gate.howto.overview.intro":`Step 3/4 — identify unreviewed models and hide them from normal roles until meta.pii-reviewed: true (step 2).`,"gate.howto.overview.step1":`Set review roles — who may see unreviewed models (e.g. dpo, security).`,"gate.howto.overview.step2":`Set default access_groups — shared with steps 1/2/4.`,"gate.howto.overview.step3":`Copy pii_table_gate.sql to macros/ — references pii_model_accessible() from step 1.`,"gate.howto.overview.step4":`Copy gated view example and gate YAML for new tables.`,"gate.howto.overview.step5":`dbt run-operation pii_audit_unreviewed_models — lists models missing pii-reviewed: true.`,"gate.howto.overview.tip":`After review in step 2 set pii-reviewed: true — then pii_model_accessible() applies instead of the gate.`,"gate.howto.reviewRoles.intro":`Review roles see unreviewed models for classification. All other roles get no access.`,"gate.howto.reviewRoles.step1":`Enter comma-separated: dpo, security, admin …`,"gate.howto.reviewRoles.step2":`Overridable in dbt_project.yml as var pii_review_roles.`,"gate.howto.reviewRoles.tip":`Do not confuse with access_groups — review roles are for the transition state only.`,"gate.howto.macro.intro":`Runtime gate — pii_model_visible_for_role() combines review status with step 1 access.`,"gate.howto.macro.step1":`Copy to macros/pii_table_gate.sql.`,"gate.howto.macro.step2":`Use in secure views: {% if pii_model_visible_for_role(model, user_role) %}.`,"gate.howto.macro.tip":`Step 1 pii_governance.sql must be in the project.`,"gate.howto.yaml.intro":`Example meta for new tables — pii-reviewed: false until step 2 is complete.`,"gate.howto.yaml.step1":`Use as template for schema.yml of new models.`,"gate.howto.yaml.tip":`After review: pii-reviewed: true and pii_details from step 2.`,"gate.howto.view.intro":`Example gated view — returns rows only when pii_model_visible_for_role() is true.`,"gate.howto.view.step1":`Adapt and copy to models/marts/.`,"gate.howto.view.tip":`For sources replace ref() with source() accordingly.`}},m={de:{...p.de,"gate.pageTitle":`PII Table Gate Generator`,"gate.pageLead":`Schritt 3/4: Ungeprüfte Models identifizieren und verstecken — Makros, Gate-YAML und Gated-View-Beispiel.`,"gate.reviewRoles.title":`Review-Rollen`,"gate.reviewRoles.description":`Wer darf Models ohne pii-reviewed: true sehen? (Kommagetrennt)`,"gate.access.title":`Model access_groups`,"gate.access.groups":`Default access_groups`,"gate.output.macro":`macros/pii_table_gate.sql`,"gate.output.yaml":`models/schema/example_table_gate.yml`,"gate.output.view":`models/marts/example_table_gated.sql`,"gate.copy":`Kopieren`,"shared.syncStatus":`Einstellungen zuletzt von {source} ({time})`,"shared.copied":`Kopiert!`},en:{...p.en,"gate.pageTitle":`PII Table Gate Generator`,"gate.pageLead":`Step 3/4: Identify and hide unreviewed models — macros, gate YAML, and gated view example.`,"gate.reviewRoles.title":`Review roles`,"gate.reviewRoles.description":`Who may see models without pii-reviewed: true? (comma-separated)`,"gate.access.title":`Model access_groups`,"gate.access.groups":`Default access_groups`,"gate.output.macro":`macros/pii_table_gate.sql`,"gate.output.yaml":`models/schema/example_table_gate.yml`,"gate.output.view":`models/marts/example_table_gated.sql`,"gate.copy":`Copy`,"shared.syncStatus":`Settings last saved by {source} ({time})`,"shared.copied":`Copied!`}};function h(e,t){return m[e]?.[t]??m.en[t]??t}function g(){let t=e();document.querySelectorAll(`[data-i18n]`).forEach(e=>{let n=e.getAttribute(`data-i18n`);if(!n?.startsWith(`gate.`)&&!n?.startsWith(`shared.`))return;let r=h(t,n);r&&(e.textContent=r)})}function _(e){return`[${e.map(e=>`'${e}'`).join(`, `)}]`}function v(e){let t=e.defaultReviewRoles??[`dpo`,`security`],n=e.defaultModelAccessGroups??[`analyst`,`dpo`];return`{# macros/pii_table_gate.sql — Step 3: PII Table Gate Generator #}
{# Copy into macros/ — requires pii_model_accessible() from Step 1 (pii_governance.sql) #}
{# dbt run-operation pii_audit_unreviewed_models #}

{% macro pii_model_reviewed(model_name) %}
  {% set node = graph.nodes.get('model.' ~ project_name ~ '.' ~ model_name) %}
  {% if node is none %}
    {% set node = graph.nodes.get('source.' ~ project_name ~ '.' ~ model_name) %}
  {% endif %}
  {% if node is none %}
    {{ return(true) }}
  {% endif %}
  {{ return(node.meta.get('pii-reviewed') is true) }}
{% endmacro %}

{% macro pii_model_visible_for_role(model_name, user_role) %}
  {% if not pii_model_reviewed(model_name) %}
    {{ return(user_role in var('pii_review_roles', ${_(t)})) }}
  {% endif %}
  {{ return(pii_model_accessible(model_name, user_role)) }}
{% endmacro %}

{% macro pii_audit_unreviewed_models() %}
  {% for node in graph.nodes.values() %}
    {% if node.resource_type not in ['model', 'source'] %}
      {% continue %}
    {% endif %}

    {% if node.meta.get('pii-reviewed') is not true %}
      {{ log("=== " ~ node.name ~ " — missing meta.pii-reviewed: true ===", info=true) }}
      {{ log("    meta:", info=true) }}
      {{ log("      pii-reviewed: false", info=true) }}
      {{ log("      access_groups: " ~ ${_(n)}, info=true) }}
      {{ log("    review_roles (may see unreviewed): " ~ ${_(t)}, info=true) }}
    {% endif %}
  {% endfor %}
{% endmacro %}
`}function y(e){let t=e.defaultModelAccessGroups??[`analyst`,`dpo`];return`# models/schema/example_table_gate.yml — Step 3: PII Table Gate Generator
# Default meta for new tables — hide from normal roles until reviewed (Step 2)

version: 2

models:
  - name: ${e.modelName}
    description: |
      New table — not yet PII-reviewed.
      Set meta.pii-reviewed: true after Step 2 policy is applied.
    meta:
      pii-reviewed: false
      access_groups: ${JSON.stringify(t)}
`}function b(e){return`{# models/marts/${e.modelName}_gated.sql — Step 3 example #}
{# Uses pii_model_visible_for_role() — unreviewed models hidden except for review roles #}

{{ config(materialized='view') }}

{% set user_role = var('pii_user_role', 'analyst') %}

{% if pii_model_visible_for_role('${e.modelName}', user_role) %}
  select * from {{ ref('${e.modelName}') }}
{% else %}
  select cast(null as varchar) as _access_denied where 1 = 0
{% endif %}
`}if(!document.getElementById(`pii-unreviewed-gate-generator-app`))throw Error(`Unreviewed table gate generator root element not found`);var x=i(),S=null,C={reviewRoles:document.getElementById(`gate-review-roles`),accessGroups:document.getElementById(`gate-access-groups`),validationBanner:document.getElementById(`gate-validation-banner`),macroPre:document.getElementById(`gate-macro-pre`),yamlPre:document.getElementById(`gate-yaml-pre`),viewPre:document.getElementById(`gate-view-pre`),copyMacroBtn:document.getElementById(`gate-copy-macro-btn`),copyYamlBtn:document.getElementById(`gate-copy-yaml-btn`),copyViewBtn:document.getElementById(`gate-copy-view-btn`),syncStatus:document.getElementById(`gate-sync-status`)};function w(){return e()}function T(e,t={}){return u(w(),h)(e,t)}function E(){x.defaultReviewRoles=n(C.reviewRoles.value),x.defaultModelAccessGroups=n(C.accessGroups.value)}function D(){C.reviewRoles.value=(x.defaultReviewRoles??[]).join(`, `),C.accessGroups.value=(x.defaultModelAccessGroups??[]).join(`, `)}function O(){let e=[...S?[S]:[],...f(x,`gate`)];d({bannerEl:C.validationBanner,outputPres:[C.macroPre,C.yamlPre,C.viewPre],issues:e,builds:[{el:C.macroPre,fn:()=>v(x)},{el:C.yamlPre,fn:()=>y(x)},{el:C.viewPre,fn:()=>b(x)}],t:T})}function k(){l(x,`pii-unreviewed-gate-generator`)}function A(){E(),O(),k()}function j(){[C.reviewRoles,C.accessGroups].forEach(e=>{e?.addEventListener(`input`,A)}),C.copyMacroBtn?.addEventListener(`click`,()=>r(C.copyMacroBtn,v(x),e=>h(w(),e))),C.copyYamlBtn?.addEventListener(`click`,()=>r(C.copyYamlBtn,y(x),e=>h(w(),e))),C.copyViewBtn?.addEventListener(`click`,()=>r(C.copyViewBtn,b(x),e=>h(w(),e)))}function M(){let e=o();if(c(e)){S={code:`storage_corrupt`,messageKey:`validation.storageCorrupt`,severity:`warning`};return}e&&`state`in e&&(x=a(x,e.state),t(C.syncStatus,e.meta,e=>h(w(),e)))}function N(){g(),M(),D(),O(),j(),s(({state:e,meta:n})=>{!e||n?.source===`pii-unreviewed-gate-generator`||(x=a(x,e),D(),O(),t(C.syncStatus,n,e=>h(w(),e)))}),window.addEventListener(`binom-tools:locale`,()=>{g();let e=o();t(C.syncStatus,e&&`meta`in e?e.meta:null,e=>h(w(),e)),O()})}N();