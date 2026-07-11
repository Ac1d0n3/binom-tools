import{n as e}from"./locale-6DFKnQyG.js";import{i as t,t as n}from"./tool-utils-CGGvItsS.js";import{n as r}from"./validation-ui-DZX4dsJT.js";import{a as i,i as a,n as o,o as s,r as c,s as l,t as u}from"./dq-validation-BjYkN-78.js";var d={de:{"dqHistory.pageTitle":`DQ History Generator`,"dqHistory.output.history":`models/marts/dq_run_history.sql`,"dqHistory.output.score":`models/marts/dq_score_daily.sql`,"dqHistory.output.trend":`models/marts/dq_trend_weekly.sql`,"dqHistory.output.failures":`models/marts/dq_open_failures.sql`,"dqHistory.output.collect":`macros/dq_collect_results.sql`,"dqHistory.output.setup":`SETUP_DQ_HISTORY.md`,"dqHistory.copy":`Kopieren`,"shared.syncStatus":`Einstellungen zuletzt von {source} ({time})`,"shared.copied":`Kopiert!`,"dq.validation.modelName":`Model-Name fehlt.`},en:{"dqHistory.pageTitle":`DQ History Generator`,"dqHistory.output.history":`models/marts/dq_run_history.sql`,"dqHistory.output.score":`models/marts/dq_score_daily.sql`,"dqHistory.output.trend":`models/marts/dq_trend_weekly.sql`,"dqHistory.output.failures":`models/marts/dq_open_failures.sql`,"dqHistory.output.collect":`macros/dq_collect_results.sql`,"dqHistory.output.setup":`SETUP_DQ_HISTORY.md`,"dqHistory.copy":`Copy`,"shared.syncStatus":`Settings last saved by {source} ({time})`,"shared.copied":`Copied!`,"dq.validation.modelName":`Model name is required.`}};function f(e,t,n={}){let r=d[e]?.[t]??d.en[t]??t;for(let[e,t]of Object.entries(n))r=r.replace(`{${e}}`,String(t));return r}function p(){let t=e();document.querySelectorAll(`[data-i18n]`).forEach(e=>{let n=e.getAttribute(`data-i18n`);if(!n?.startsWith(`dqHistory.`)&&!n?.startsWith(`shared.`))return;let r=f(t,n);r&&(e.textContent=r)})}function m(e){return`{# models/marts/dq_run_history.sql — Step 3: DQ History Generator #}

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
`}function h(){return`{# models/marts/dq_score_daily.sql — Step 3: DQ History Generator #}

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
`}function g(){return`{# models/marts/dq_trend_weekly.sql — Step 3: DQ History Generator #}

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
`}function _(){return`{# models/marts/dq_open_failures.sql — Step 3: DQ History Generator #}

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
`}function v(){return`{# macros/dq_collect_results.sql — Step 3: DQ History Generator #}
{# Run after dbt test: dbt run-operation dq_collect_results #}

{% macro dq_collect_results() %}
  {% if execute %}
    {{ log('dq_collect_results: persist test outcomes to dq_run_history (extend with store_failures or result parsing)', info=true) }}
  {% endif %}
{% endmacro %}
`}function y(e){return`# dbt Data Quality — History & Reporting

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
`}if(!document.getElementById(`dbt-dq-history-generator-app`))throw Error(`DQ history generator root element not found`);var b=l(),x=null,S={validationBanner:document.getElementById(`dq-history-validation-banner`),historyPre:document.getElementById(`dq-history-pre`),scorePre:document.getElementById(`dq-score-pre`),trendPre:document.getElementById(`dq-trend-pre`),failuresPre:document.getElementById(`dq-failures-pre`),collectPre:document.getElementById(`dq-collect-pre`),setupPre:document.getElementById(`dq-history-setup-pre`),syncStatus:document.getElementById(`dq-history-sync-status`)};function C(){return e()}function w(e,t={}){return f(C(),e,t)}function T(){let e=u(b).map(e=>({severity:e.severity===`info`?`warning`:e.severity,code:e.code,messageKey:e.messageKey,params:e.params}));r({bannerEl:S.validationBanner,outputPres:[S.historyPre,S.scorePre,S.trendPre,S.failuresPre,S.collectPre,S.setupPre],issues:[...x?[x]:[],...e],builds:[{el:S.historyPre,fn:()=>m(b)},{el:S.scorePre,fn:()=>h()},{el:S.trendPre,fn:()=>g()},{el:S.failuresPre,fn:()=>_()},{el:S.collectPre,fn:()=>v()},{el:S.setupPre,fn:()=>y(b)}],t:w})}function E(){let e=a();if(c(e)){x={severity:`warning`,code:`storage_corrupt`,messageKey:`validation.storageCorrupt`};return}e&&`state`in e&&(b=s(b,e.state),t(S.syncStatus,e.meta,w))}p(),E(),T(),o(b,`dbt-dq-history-generator`),i(({state:e,meta:n})=>{b=s(b,e),T(),t(S.syncStatus,n,w)}),document.querySelectorAll(`[data-dq-copy]`).forEach(e=>{let t=e.getAttribute(`data-dq-copy`),r=t?document.getElementById(t):null;e.addEventListener(`click`,()=>n(e,r?.textContent??``,w))}),window.addEventListener(`binom-tools:locale`,()=>{p(),T()});