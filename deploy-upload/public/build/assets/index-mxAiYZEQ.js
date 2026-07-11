import{n as e}from"./locale-B3wqqiVv.js";import{i as t,t as n}from"./tool-utils-CV-1LRXR.js";import{n as r}from"./validation-ui-DZX4dsJT.js";import{a as i,i as a,n as o,o as s,r as c,s as l,t as u}from"./dq-validation-BjYkN-78.js";var d={de:{"dqHistory.howto.summary":`So funktioniert's`,"dqHistory.howto.overview.intro":`Schritt 3/3 — History-Mart, Report-Views und dq_collect_results für Trends und offene Failures.`,"dqHistory.howto.overview.step1":`Nutzt Model-Name aus Step 2 (localStorage).`,"dqHistory.howto.overview.step2":`dq_run_history.sql und Report-Views nach models/marts/ kopieren.`,"dqHistory.howto.overview.step3":`dq_collect_results.sql nach macros/ — Runbook in SETUP_DQ_HISTORY.md.`,"dqHistory.howto.overview.tip":`Steps 1–2 müssen deployed sein, bevor History sinnvoll befüllt wird.`,"dqHistory.howto.history.intro":`Inkrementelles Fact für jeden DQ-Testlauf — Basis für alle Reports.`,"dqHistory.howto.history.step1":`Nach models/marts/dq_run_history.sql kopieren.`,"dqHistory.howto.history.step2":`Wird von dq_collect_results befüllt (post-hook oder Runbook).`,"dqHistory.howto.history.tip":`unique_key verhindert Duplikate bei Re-Runs.`,"dqHistory.howto.score.intro":`Täglicher DQ-Score pro Model — Aggregation aus dq_run_history.`,"dqHistory.howto.score.step1":`View dq_score_daily für Dashboards.`,"dqHistory.howto.score.tip":`Mit BI-Tool oder dbt docs verbinden.`,"dqHistory.howto.trend.intro":`Wöchentlicher Trend — Fehlerrate über Zeit.`,"dqHistory.howto.trend.step1":`View dq_trend_weekly aus History-Mart.`,"dqHistory.howto.trend.tip":`Zeitraum in der View anpassen, falls nötig.`,"dqHistory.howto.failures.intro":`Offene Failures — letzter Status pro Regel noch failed.`,"dqHistory.howto.failures.step1":`View dq_open_failures für Ops-Alerts.`,"dqHistory.howto.failures.tip":`Severity aus meta.dq_rules (Step 2) wird mitgeführt.`,"dqHistory.howto.collect.intro":`Makro sammelt Test-Ergebnisse nach jedem dbt test/run.`,"dqHistory.howto.collect.step1":`Nach macros/dq_collect_results.sql kopieren.`,"dqHistory.howto.collect.step2":`Im Runbook: wann und wie aufrufen (on-run-end).`,"dqHistory.howto.collect.tip":`Erfordert dq_governance.sql aus Step 1.`,"dqHistory.howto.setup.intro":`SETUP_DQ_HISTORY.md — Runbook für Step 3.`,"dqHistory.howto.setup.step1":`Reihenfolge: Models deployen, Macro einbinden, testen.`,"dqHistory.howto.setup.step2":`Verify: dbt run --select dq_run_history+`,"dqHistory.howto.setup.tip":`Zusammen mit SETUP_DQ.md aus Step 1 im Repo ablegen.`},en:{"dqHistory.howto.summary":`How it works`,"dqHistory.howto.overview.intro":`Step 3/3 — history mart, report views, and dq_collect_results for trends and open failures.`,"dqHistory.howto.overview.step1":`Uses model name from step 2 (localStorage).`,"dqHistory.howto.overview.step2":`Copy dq_run_history.sql and report views to models/marts/.`,"dqHistory.howto.overview.step3":`Copy dq_collect_results.sql to macros/ — runbook in SETUP_DQ_HISTORY.md.`,"dqHistory.howto.overview.tip":`Steps 1–2 must be deployed before history can be populated.`,"dqHistory.howto.history.intro":`Incremental fact for each DQ test run — base for all reports.`,"dqHistory.howto.history.step1":`Copy to models/marts/dq_run_history.sql.`,"dqHistory.howto.history.step2":`Populated by dq_collect_results (post-hook or runbook).`,"dqHistory.howto.history.tip":`unique_key prevents duplicates on re-runs.`,"dqHistory.howto.score.intro":`Daily DQ score per model — aggregated from dq_run_history.`,"dqHistory.howto.score.step1":`View dq_score_daily for dashboards.`,"dqHistory.howto.score.tip":`Connect to BI or dbt docs.`,"dqHistory.howto.trend.intro":`Weekly trend — failure rate over time.`,"dqHistory.howto.trend.step1":`View dq_trend_weekly from the history mart.`,"dqHistory.howto.trend.tip":`Adjust time window in the view if needed.`,"dqHistory.howto.failures.intro":`Open failures — latest status per rule still failed.`,"dqHistory.howto.failures.step1":`View dq_open_failures for ops alerts.`,"dqHistory.howto.failures.tip":`Severity from meta.dq_rules (step 2) is carried through.`,"dqHistory.howto.collect.intro":`Macro collects test results after each dbt test/run.`,"dqHistory.howto.collect.step1":`Copy to macros/dq_collect_results.sql.`,"dqHistory.howto.collect.step2":`Runbook explains when and how to call it (on-run-end).`,"dqHistory.howto.collect.tip":`Requires dq_governance.sql from step 1.`,"dqHistory.howto.setup.intro":`SETUP_DQ_HISTORY.md — runbook for step 3.`,"dqHistory.howto.setup.step1":`Order: deploy models, wire macro, test.`,"dqHistory.howto.setup.step2":`Verify: dbt run --select dq_run_history+`,"dqHistory.howto.setup.tip":`Keep alongside SETUP_DQ.md from step 1 in the repo.`}},f={de:{...d.de,"dqHistory.pageTitle":`DQ History Generator`,"dqHistory.output.history":`models/marts/dq_run_history.sql`,"dqHistory.output.score":`models/marts/dq_score_daily.sql`,"dqHistory.output.trend":`models/marts/dq_trend_weekly.sql`,"dqHistory.output.failures":`models/marts/dq_open_failures.sql`,"dqHistory.output.collect":`macros/dq_collect_results.sql`,"dqHistory.output.setup":`SETUP_DQ_HISTORY.md`,"dqHistory.copy":`Kopieren`,"shared.syncStatus":`Einstellungen zuletzt von {source} ({time})`,"shared.copied":`Kopiert!`,"dq.validation.modelName":`Model-Name fehlt.`},en:{...d.en,"dqHistory.pageTitle":`DQ History Generator`,"dqHistory.output.history":`models/marts/dq_run_history.sql`,"dqHistory.output.score":`models/marts/dq_score_daily.sql`,"dqHistory.output.trend":`models/marts/dq_trend_weekly.sql`,"dqHistory.output.failures":`models/marts/dq_open_failures.sql`,"dqHistory.output.collect":`macros/dq_collect_results.sql`,"dqHistory.output.setup":`SETUP_DQ_HISTORY.md`,"dqHistory.copy":`Copy`,"shared.syncStatus":`Settings last saved by {source} ({time})`,"shared.copied":`Copied!`,"dq.validation.modelName":`Model name is required.`}};function p(e,t,n={}){let r=f[e]?.[t]??f.en[t]??t;for(let[e,t]of Object.entries(n))r=r.replace(`{${e}}`,String(t));return r}function m(){let t=e();document.querySelectorAll(`[data-i18n]`).forEach(e=>{let n=e.getAttribute(`data-i18n`);if(!n?.startsWith(`dqHistory.`)&&!n?.startsWith(`shared.`))return;let r=p(t,n);r&&(e.textContent=r)})}function h(e){return`{# models/marts/dq_run_history.sql — Step 3: DQ History Generator #}

{{
  config(
    materialized='incremental',
    unique_key='run_id || model_name || coalesce(column_name, \\'\\') || rule_id',
    tags=['dq', 'dq_history']
  )
}}

select
  cast(null as varchar) as run_id,
  cast(null as timestamp_ntz) as executed_at,
  cast(null as varchar) as model_name,
  cast(null as varchar) as column_name,
  cast(null as varchar) as rule_id,
  cast(null as varchar) as rule_type,
  cast(null as varchar) as severity,
  cast(null as varchar) as status,
  cast(null as bigint) as failed_rows,
  cast(null as bigint) as total_rows,
  cast(null as float) as metric_value,
  cast(null as varchar) as failure_sample
where 1 = 0

{# After dq_collect_results populates rows, incremental merge keeps audit history #}
`}function g(){return`{# models/marts/dq_score_daily.sql — Step 3: DQ History Generator #}

{{
  config(
    materialized='view',
    tags=['dq', 'dq_report']
  )
}}

select
  date_trunc('day', executed_at) as report_date,
  model_name,
  count(*) as total_checks,
  sum(case when status = 'pass' then 1 else 0 end) as passed_checks,
  round(100.0 * sum(case when status = 'pass' then 1 else 0 end) / nullif(count(*), 0), 2) as pass_rate_pct
from {{ ref('dq_run_history') }}
group by 1, 2
order by 1 desc, 2
`}function _(){return`{# models/marts/dq_trend_weekly.sql — Step 3: DQ History Generator #}

{{
  config(
    materialized='view',
    tags=['dq', 'dq_report']
  )
}}

with daily as (
  select * from {{ ref('dq_score_daily') }}
),
this_week as (
  select model_name, avg(pass_rate_pct) as pass_rate
  from daily
  where report_date >= dateadd('week', -1, current_date())
  group by 1
),
last_week as (
  select model_name, avg(pass_rate_pct) as pass_rate
  from daily
  where report_date >= dateadd('week', -2, current_date())
    and report_date < dateadd('week', -1, current_date())
  group by 1
)
select
  coalesce(t.model_name, l.model_name) as model_name,
  t.pass_rate as pass_rate_this_week,
  l.pass_rate as pass_rate_last_week,
  round(t.pass_rate - l.pass_rate, 2) as wow_delta_pct
from this_week t
full outer join last_week l on t.model_name = l.model_name
order by wow_delta_pct asc nulls last
`}function v(){return`{# models/marts/dq_open_failures.sql — Step 3: DQ History Generator #}

{{
  config(
    materialized='view',
    tags=['dq', 'dq_report']
  )
}}

select *
from {{ ref('dq_run_history') }}
where status = 'fail'
  and severity = 'error'
  and executed_at >= (
    select max(executed_at) from {{ ref('dq_run_history') }}
  )
order by model_name, column_name, rule_id
`}function y(){return`{# macros/dq_collect_results.sql — Step 3: DQ History Generator #}
{# Run after dbt test: dbt run-operation dq_collect_results #}

{% macro dq_collect_results() %}
  {% if execute %}
    {{ log('dq_collect_results: persist test outcomes to dq_run_history (extend with store_failures or result parsing)', info=true) }}
  {% endif %}
{% endmacro %}
`}function b(e){return`# dbt Data Quality — History & Reporting

Model: **${e.modelName}**

## Runbook

\`\`\`bash
dbt test --select tag:dq
dbt run-operation dq_collect_results
dbt run --select dq_run_history dq_score_daily dq_trend_weekly dq_open_failures
\`\`\`

## BI reporting

- **dq_score_daily** — pass rate per model/day
- **dq_trend_weekly** — week-over-week delta
- **dq_open_failures** — current error-severity fails from latest run

Connect Tableau, Looker, or Metabase to these views for management dashboards.
`}if(!document.getElementById(`dbt-dq-history-generator-app`))throw Error(`DQ history generator root element not found`);var x=l(),S=null,C={validationBanner:document.getElementById(`dq-history-validation-banner`),historyPre:document.getElementById(`dq-history-pre`),scorePre:document.getElementById(`dq-score-pre`),trendPre:document.getElementById(`dq-trend-pre`),failuresPre:document.getElementById(`dq-failures-pre`),collectPre:document.getElementById(`dq-collect-pre`),setupPre:document.getElementById(`dq-history-setup-pre`),syncStatus:document.getElementById(`dq-history-sync-status`)};function w(){return e()}function T(e,t={}){return p(w(),e,t)}function E(){let e=u(x).map(e=>({severity:e.severity===`info`?`warning`:e.severity,code:e.code,messageKey:e.messageKey,params:e.params}));r({bannerEl:C.validationBanner,outputPres:[C.historyPre,C.scorePre,C.trendPre,C.failuresPre,C.collectPre,C.setupPre],issues:[...S?[S]:[],...e],builds:[{el:C.historyPre,fn:()=>h(x)},{el:C.scorePre,fn:()=>g()},{el:C.trendPre,fn:()=>_()},{el:C.failuresPre,fn:()=>v()},{el:C.collectPre,fn:()=>y()},{el:C.setupPre,fn:()=>b(x)}],t:T})}function D(){let e=a();if(c(e)){S={severity:`warning`,code:`storage_corrupt`,messageKey:`validation.storageCorrupt`};return}e&&`state`in e&&(x=s(x,e.state),t(C.syncStatus,e.meta,T))}m(),D(),E(),o(x,`dbt-dq-history-generator`),i(({state:e,meta:n})=>{x=s(x,e),E(),t(C.syncStatus,n,T)}),document.querySelectorAll(`[data-dq-copy]`).forEach(e=>{let t=e.getAttribute(`data-dq-copy`),r=t?document.getElementById(t):null;e.addEventListener(`click`,()=>n(e,r?.textContent??``,T))}),window.addEventListener(`binom-tools:locale`,()=>{m(),E()});