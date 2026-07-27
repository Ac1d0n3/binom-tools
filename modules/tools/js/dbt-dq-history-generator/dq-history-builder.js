/**
 * @param {import('../dq-shared/dq-demo-model.js').DqModelState} state
 * @returns {string}
 */
export function buildDqRunHistoryModel(state) {
    return `{# models/marts/dq_run_history.sql — Step 3: DQ History Generator #}

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
`;
}

/**
 * @returns {string}
 */
export function buildDqScoreDailyView() {
    return `{# models/marts/dq_score_daily.sql — Step 3: DQ History Generator #}

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
`;
}

/**
 * @returns {string}
 */
export function buildDqTrendWeeklyView() {
    return `{# models/marts/dq_trend_weekly.sql — Step 3: DQ History Generator #}

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
`;
}

/**
 * @returns {string}
 */
export function buildDqOpenFailuresView() {
    return `{# models/marts/dq_open_failures.sql — Step 3: DQ History Generator #}

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
`;
}

/**
 * @returns {string}
 */
export function buildDqCollectResultsMacro() {
    return `{# macros/dq_collect_results.sql — Step 3: DQ History Generator #}
{# Run after dbt test: dbt run-operation dq_collect_results #}

{% macro dq_collect_results() %}
  {% if execute %}
    {{ log('dq_collect_results: persist test outcomes to dq_run_history (extend with store_failures or result parsing)', info=true) }}
  {% endif %}
{% endmacro %}
`;
}

/**
 * @param {import('../dq-shared/dq-demo-model.js').DqModelState} state
 * @returns {string}
 */
export function buildDqHistorySetupMarkdown(state) {
    return `# dbt Data Quality — History & Reporting

Model: **${state.modelName}**

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
`;
}
