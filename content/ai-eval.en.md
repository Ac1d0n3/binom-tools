---
title: Evaluating and Operating AI – Quality, Costs and Production Operations
description: How reproducible test cases, layered evaluation, versioned configurations and continuous production monitoring make AI systems measurable and controllable.
category: Artificial Intelligence
tags:
  - ai
  - ai-foundations
  - evaluation
  - llm-evaluation
  - rag-evaluation
  - agent-evaluation
  - observability
  - monitoring
  - ai-operations
  - mlops
  - cost-management
  - ai-governance
order: -1
author: Thomas Lindackers
series: ai-foundations
seriesPart: 6
seriesTitle: AI Foundations
hero: images/playbooks/ai-eval-hero.png
---

## From an impressive prototype to a measurable system

An AI prototype can produce impressive results within hours. A few prepared questions work, the demonstration looks fluent and the model generates responses that appear professionally plausible.

That does not prove that the system:

- covers real user requests,
- retrieves the correct enterprise sources,
- follows business rules and output formats,
- handles difficult cases safely,
- calls tools with valid parameters,
- responds within an acceptable time,
- can be operated economically,
- survives model, prompt and data changes without regression.

Subjective impressions are not enough.

> **An AI system is production-ready only when quality, risk, cost and behaviour are measurable, versioned and observable.**

Evaluation is therefore not a final check immediately before deployment. It is a technical and business discipline across the complete lifecycle:

```flow linear vertical
Define the use case and success criteria
Test components in isolation
Evaluate the system with realistic cases
Test workflows and failure paths
Deploy under controlled production conditions
Continuously observe production behaviour
Feed findings back into data, prompts, models and processes
```

The objective is not to create one global AI quality score. The relevant characteristics of the specific system must be measured separately and combined into a defensible release decision.

## Evaluate the complete system, not only the model

A production AI system rarely consists of a language model alone. Its output is produced by a chain of components:

```text
User input
→ prompt and context preparation
→ retrieval
→ language model
→ tool selection and actions
→ validation
→ business process
```

Every stage can improve or damage the final outcome.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-eval-img1-en.png"
        alt="Complete AI system chain from user input and prompt through retrieval, language model and tools to the business outcome with separate evaluation dimensions"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        The whole system must be evaluated. Strong model performance cannot compensate for poor retrieval, invalid tool calls or an unsuitable business process.
    </figcaption>
</figure>

### User input

Evaluation should verify that the test set represents actual use.

Questions include:

- Are frequent standard cases included?
- Are ambiguous, incomplete and incorrect inputs represented?
- Are different languages and phrasings covered?
- Are rare but critical situations included?
- Does the set contain abuse and attack cases?
- Does its distribution roughly resemble expected production traffic?

A test set containing only well-written ideal questions will overestimate real system quality.

### Prompt and context preparation

This stage is evaluated for task interpretation, business rules and output constraints.

Possible criteria:

- correct interpretation of role and task,
- adherence to the required schema,
- use of supplied evidence,
- correct handling of missing information,
- compliance with business rules,
- no treatment of retrieved content as higher-authority instructions.

### Retrieval

For RAG systems, retrieval quality must be measured separately from answer quality.

Important questions:

- Was the necessary source retrieved?
- Was it ranked among the first results?
- Was the valid version selected?
- Were access filters applied correctly?
- Were relevant metadata considered?
- Were too many irrelevant or duplicate chunks supplied?

Example retrieval metrics:

| Metric | Meaning |
| --- | --- |
| **Recall at k** | Share of cases in which the required source appeared in the first k results |
| **Precision at k** | Share of relevant items among the first k results |
| **Mean Reciprocal Rank** | How early the first relevant result appeared |
| **Source Coverage** | Whether all evidence required for the answer was present |
| **Version Correctness** | Whether the valid document version was used |
| **Access Correctness** | Whether only authorized content was retrieved |

A capable model can produce a convincing incorrect answer from the wrong document. Without separate retrieval evaluation, the actual cause remains hidden.

### Language model

The model output can be scored across several dimensions:

- correctness,
- completeness,
- relevance,
- groundedness,
- consistency,
- clarity and style,
- output-format adherence,
- appropriate uncertainty,
- safe refusal for prohibited tasks.

### Tools and actions

For agents, the full trace matters—not only the final response.

Possible metrics include:

- correct tool selection,
- valid arguments,
- tool success rate,
- number of required steps,
- unnecessary or duplicate calls,
- retry rate,
- abort rate,
- recovery success,
- adherence to step and cost budgets.

### Validation

A guardrail or validator also requires its own evaluation.

Measure:

- How many real errors were detected?
- How many errors were missed?
- How many correct outputs were blocked?
- How stable is the validator across different wording?
- Can manipulated content influence the validator?

### Business outcome

The final measure is not only the generated response but its effect on the process.

Examples:

- process completed successfully,
- rework required,
- case escalated,
- handling time reduced,
- incorrect decision prevented,
- recommendation accepted or corrected,
- business value per successful outcome.

> **A smaller model in a strong architecture can outperform a larger model in a weak system.**

## Evaluation requires several layers

One large end-to-end test phase is expensive and often provides weak root-cause information. A layered approach is more effective.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-eval-img2-en.png"
        alt="Evaluation pyramid with component tests, offline evaluation, scenario and workflow tests, controlled production and continuous production monitoring"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Evaluation is built progressively from fast component tests to continuous observation of real business processes.
    </figcaption>
</figure>

## Level 1: Component tests

Component tests verify small units with clearly defined expected behaviour.

Examples:

- a parser reads tables correctly,
- chunking retains headings and sections,
- metadata filters are applied,
- a tool schema accepts only valid arguments,
- a policy blocks prohibited actions,
- structured output matches a JSON schema,
- a calculation tool returns the expected value.

Advantages:

- fast,
- inexpensive,
- easy to automate,
- clear error localization,
- suitable for every build and pull request.

Component tests do not prove that the full use case works.

## Level 2: Offline evaluation

Offline evaluation runs the complete system against a versioned test set without affecting real users or production systems.

Typical elements include:

- golden dataset,
- expected results,
- expected sources,
- safety cases,
- boundary cases,
- latency and cost budgets,
- comparison of multiple configurations.

Offline evaluation supports:

- model comparison,
- prompt regression testing,
- RAG optimization,
- tool routing,
- policy changes,
- go/no-go gates before release.

## Level 3: Scenario and workflow tests

Individual question-answer pairs are insufficient for agentic systems. Scenario tests verify complete tasks.

Example:

```flow linear vertical
User reports a billing error
Agent resolves customer and contract context
Agent retrieves relevant invoice data
Agent checks business rules
Agent prepares a correction proposal
Human approves the change
System records the decision and execution
```

Tests must cover failure paths as well as success paths:

- source unavailable,
- tool returns incomplete data,
- user lacks authorization,
- information conflicts,
- model proposes a risky action,
- approval is rejected,
- action fails after a partial step,
- rollback or compensation is required.

## Level 4: Controlled production

A system can perform well offline and still fail under real conditions. Real language, data distributions, system load, user behaviour and dependencies cannot be fully simulated.

Controlled production can include:

- limited user group,
- internal pilot,
- shadow mode,
- A/B testing,
- feature flags,
- manual review,
- bounded autonomy,
- gradual rollout.

The scope and potential impact must remain limited.

## Level 5: Continuous production monitoring

The environment changes after deployment:

- users ask new questions,
- data and policies change,
- models and APIs receive new versions,
- document collections grow,
- retrieval distributions shift,
- tools and dependencies change behaviour.

Evaluation therefore does not end with release. Production observations become new test cases and regression tests.

```flowchart
Capture production case
Classify anomaly
Create test case
Correct the cause
Run regression suite
Release under control
```

## What makes a good golden dataset?

A golden dataset is not a random export of historical prompts. It is a curated, versioned and owned evaluation asset.

A complete test case can include:

| Element | Purpose |
| --- | --- |
| **Input** | Question, task or conversation |
| **Context** | Relevant user, tenant or process information |
| **Expected evidence** | Sources or records that must be retrieved |
| **Expected behaviour** | Permitted answer or required process step |
| **Expected facts** | Verifiable claims |
| **Allowed tools** | Functions that may be used |
| **Prohibited actions** | Explicitly disallowed behaviour |
| **Success criteria** | Business, technical and safety conditions |
| **Tags** | Language, risk, category, region and difficulty |
| **Owner** | Responsible business role or person |

### Sources of evaluation cases

A robust test set combines:

- business-defined normal cases,
- anonymized production requests,
- known failure modes,
- support and incident cases,
- boundary conditions,
- deliberately created attack cases,
- requirements derived from policies and regulation.

Production data is valuable but must not be copied blindly. It may contain personal data, incorrect historic decisions or biased usage patterns.

### The dataset must grow

A new failure should become a reproducible test case whenever practical.

```flow linear vertical
Failure occurs in production
Root cause is investigated
Representative test case is added
Correction is implemented
All prior tests are run again
```

Over time, the regression suite captures real operational knowledge.

## Reproducibility requires versioning

An evaluation result is comparable only when the complete system configuration that generated it is known.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-eval-img3-en.png"
        alt="Reproducible test case with versioned system configuration, measurable result and comparison of multiple system versions"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        The question, model, prompt, retrieval index, tools, policies and runtime configuration must all be versioned.
    </figcaption>
</figure>

At minimum, an evaluation run should reference:

```text
Evaluation dataset version
Model and model version
System prompt version
Prompt template version
Embedding model
Retrieval index or snapshot
Chunking configuration
Reranker version
Tool versions
Policy and guardrail versions
Sampling parameters
Runtime configuration
Code commit
Execution time and environment
```

Without this information, a changed result cannot be explained reliably.

### Example

Version B scores better than Version A. At the same time:

- the model changed,
- a new system prompt was introduced,
- the index was rebuilt,
- chunking was modified,
- an additional validator was enabled.

The improvement cannot be attributed to one change.

A controlled experiment changes as few factors as possible or documents every difference.

## Evaluation methods

Different quality dimensions require different methods.

### Deterministic checks

Useful for conditions that can be verified precisely:

- valid JSON,
- required field present,
- number inside a permitted range,
- tool call authorized,
- source belongs to the allowed tenant,
- response contains no prohibited data class,
- action executed exactly once.

Deterministic checks are fast and reproducible. They should be preferred where possible.

### Reference-based comparison

Useful for:

- classification,
- extraction,
- known calculations,
- defined entities,
- expected sources,
- business questions with one authoritative answer.

Not every generative task has one exact reference wording.

### Rule- or rubric-based evaluation

A rubric describes several criteria with clear performance levels.

| Criterion | 0 points | 1 point | 2 points |
| --- | --- | --- | --- |
| Correctness | central claim is wrong | partially correct | fully correct |
| Evidence | unsupported | partly supported | fully supported |
| Completeness | important aspects missing | minor gaps | complete |
| Actionability | unusable | useful with rework | directly usable |

A rubric can be applied by humans or by an evaluation model.

### LLM-as-a-Judge

One model evaluates another system's output.

Advantages:

- scalable,
- flexible,
- useful for open-ended text,
- supports detailed rubrics.

Risks:

- judge hallucination,
- preference for particular wording,
- position or length bias,
- unstable scoring,
- dependency on judge-model behaviour,
- unobserved changes to the judge.

LLM judges should therefore be:

- calibrated against human labels,
- versioned,
- driven by explicit rubrics,
- revalidated regularly,
- avoided as the only control for high-risk decisions.

### Human evaluation

Human review remains important when:

- several answers are professionally valid,
- business context is difficult to formalize,
- impact is high,
- new failure modes are investigated,
- user experience and clarity matter.

Human evaluation is not automatically objective. It needs:

- clear rubrics,
- reviewer training,
- multiple reviewers for critical cases,
- agreement measurement,
- documented resolution of disagreements.

## Metrics must match the use case

There is no universal AI metric.

### RAG question answering

Possible dimensions:

- retrieval recall,
- source coverage,
- groundedness,
- factual correctness,
- citation accuracy,
- completeness,
- abstention when evidence is missing.

### Document extraction

- field accuracy,
- precision and recall,
- table structure,
- format validity,
- manual correction rate.

### Classification

- precision,
- recall,
- F1,
- confusion matrix,
- performance per class,
- rare-case performance.

### Agents

- task completion,
- correct tool selection,
- step count,
- duplicate actions,
- recovery success,
- cost per successful run,
- human intervention rate,
- policy violations.

### Code generation

- tests passed,
- security scans,
- static analysis,
- maintainability,
- rework required,
- execution performance.

### Summarization

- factual coverage,
- no unsupported additions,
- retention of important constraints,
- appropriate brevity,
- readability.

> **The metric must represent the intended behaviour—not only what is easy to count.**

## Aggregates can conceal failure

A global average may look acceptable while important segments fail.

| Segment | Success rate |
| --- | ---: |
| Frequent standard requests | 94% |
| German requests | 91% |
| English requests | 95% |
| Rare contract cases | 62% |
| High-risk actions | 71% |

The overall rate can remain high even though the most consequential cases are inadequate.

Evaluation should therefore be segmented by:

- use case,
- language,
- region,
- customer group,
- data source,
- difficulty,
- risk class,
- model route,
- tool,
- document version,
- input length.

Critical segments can have their own minimum thresholds.

## Release gates instead of intuition

Production release should depend on explicit criteria.

Example:

```text
Retrieval Recall at 5 ≥ agreed threshold
No critical access-control violations
Correctness in high-risk cases ≥ threshold
No unauthorized write actions
p95 latency within budget
Cost per successful outcome within target range
All critical regression tests passed
Business owner approval recorded
```

The exact thresholds depend on the use case. An internal search assistant needs different controls from an agent that can change orders, payments or contracts.

### Average improvement must not hide a critical regression

A new version can be better overall and worse in one critical category.

Release gates should therefore include:

- global minimums,
- segment-specific minimums,
- no new critical failures,
- maximum permitted regression,
- safety and policy gates,
- latency and cost budgets.

## Quality, latency and cost are trade-offs

Using the most capable model for every request is rarely the best architecture.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-eval-img4-en.png"
        alt="Comparison of a high-capability model for all tasks, a small model for all tasks and a routed hybrid architecture across quality, latency and cost"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        A routed architecture can send simple, complex, knowledge-dependent, deterministic and high-risk tasks through different controlled paths.
    </figcaption>
</figure>

### High-capability model for everything

Potential benefits:

- strong general capability,
- less routing logic,
- simple prototype architecture.

Disadvantages:

- higher cost,
- often higher latency,
- unnecessary capability for simple work,
- greater dependency on one model.

### Small model for everything

Potential benefits:

- low cost,
- fast response,
- high throughput.

Disadvantages:

- lower quality on complex tasks,
- weaker reasoning,
- higher correction or escalation rate.

### Routed or hybrid architecture

Example:

```flow linear vertical
Classify the request
Determine complexity, freshness and risk
Select model or tool route
Validate the result
Escalate when uncertain
```

Possible routes:

| Task | Suitable path |
| --- | --- |
| simple classification | small fast model |
| complex reasoning | more capable model |
| current enterprise knowledge | RAG |
| exact calculation | deterministic tool |
| structured data query | controlled query or API layer |
| high-risk action | human approval |

### Cost per token is not the primary business metric

A cheap run is not economical when it frequently:

- produces wrong results,
- must be repeated,
- creates manual rework,
- triggers unnecessary tools,
- forces user escalation.

More meaningful measures include:

```text
Cost per successful business process
Cost per correct answer
Cost including retries and rework
Cost per avoided manual task
Cost per accepted recommendation
```

## Measuring latency correctly

One average response time is insufficient.

Relevant metrics include:

- time to first token,
- time to complete,
- retrieval latency,
- model latency,
- tool latency,
- end-to-end latency,
- p50, p95 and p99,
- timeout rate.

User expectations depend on the use case. A complex analytical report can take longer than an interactive search suggestion.

Latency can be reduced through:

- smaller models for simple requests,
- shorter prompts,
- removal of irrelevant context,
- parallel independent tool calls,
- streaming,
- caching,
- precomputation,
- faster retrieval and reranking,
- asynchronous execution for non-interactive tasks.

Every optimization must be evaluated again against quality and cost.

## Production operations is a control loop

AI Operations combines conventional software observability with model, data, quality and safety signals.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-eval-img5-en.png"
        alt="AI production operations as a closed loop with deploy, observe, evaluate, detect change, investigate, improve, validate and release"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Production is not a one-time deployment. Observation, evaluation, investigation, improvement, validation and controlled release form a continuous loop.
    </figcaption>
</figure>

The loop consists of:

```flowchart
Deploy
Observe
Evaluate
Detect Change
Investigate
Improve
Validate
Release
```

### Deploy

An approved combination of model, prompt, data, tools and policies is released under control.

### Observe

Traces, logs, cost, latency, retrieval results, tool calls and user feedback are collected.

### Evaluate

Production cases are scored through deterministic checks, samples, human review or automated scorers.

### Detect change

Signals can include:

- quality degradation,
- drift,
- new failure patterns,
- higher cost,
- increased latency,
- unusual tool use,
- rising policy violations,
- more escalations.

### Investigate

Traces and version metadata help locate the cause:

- source,
- index,
- prompt,
- model,
- tool,
- policy,
- user interface,
- business process.

### Improve

Possible actions:

- correct source data,
- extend the evaluation set,
- modify the prompt,
- improve retrieval,
- route to another model,
- narrow a tool interface,
- change the approval process.

### Validate

The change is retested offline and against relevant scenarios. Critical regressions must be excluded.

### Release

The new version is deployed gradually. A controlled rollback path must remain available.

## What should be monitored?

Monitoring is not limited to CPU, memory and uptime. An AI system needs several observability dimensions.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-eval-img6-en.png"
        alt="Monitoring matrix for accuracy, latency, cost, quality drift, user experience, safety, model routing and system availability"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        AI monitoring must combine quality, retrieval, model, agent, tool, safety, cost, user and system signals.
    </figcaption>
</figure>

| Area | Example metrics |
| --- | --- |
| **Quality** | correctness, completeness, relevance, groundedness |
| **Retrieval** | recall, precision, version correctness, access errors |
| **Model** | format errors, abstention rate, token usage, route |
| **Agent** | steps, tool calls, loops, aborts, recovery |
| **Tools** | success rate, latency, retries, duplicates |
| **Safety** | prompt injection, policy stops, leakage, unsafe destinations |
| **Cost** | cost per request and successful outcome |
| **User** | ratings, corrections, acceptance, escalations |
| **Business** | automation rate, rework, cycle time, success |
| **System** | error rate, timeouts, throughput, availability |

### Thresholds and trends

One static threshold is often insufficient.

```text
Warning:
p95 latency above target for 15 minutes

Critical:
p95 latency above maximum or timeout rate over limit

Trend:
latency increasing continuously for seven days
```

Trends can reveal a problem earlier than a hard threshold.

### Correlation rather than isolated metrics

A cost increase may be caused by:

- longer prompts,
- more retrieval chunks,
- more routing to a large model,
- tool retries,
- agent loops,
- higher traffic,
- lower quality and repeated requests.

Traces should preserve the relationship between request, route, tokens, tools, latency, result and score.

## Tracing and privacy

Traces are valuable for diagnosis and evaluation but can contain sensitive information:

- user prompts,
- enterprise documents,
- tool arguments,
- personal data,
- model responses,
- internal instructions.

Required controls include:

- data minimization,
- masking,
- access control,
- environment separation,
- retention rules,
- auditable use,
- documented evaluation purposes,
- controlled export to external services.

Not every complete prompt must be stored permanently. Depending on the use case, hashes, references, structured metadata or redacted samples may be sufficient.

## Turning production feedback into evaluation data

User feedback is important but is not automatically a ground-truth quality label.

A negative rating can mean:

- the answer was wrong,
- the answer was correct but unclear,
- the expected feature is missing,
- the user disagrees with the policy,
- latency was too high,
- the result did not match personal expectations.

Feedback should therefore be classified.

| Feedback type | Follow-up |
| --- | --- |
| incorrect fact | source and answer review |
| missing source | add retrieval test |
| unclear answer | style or UX evaluation |
| wrong tool | inspect agent trace |
| too slow | latency analysis |
| legitimate case blocked | policy review |
| unsafe behaviour | incident process |

## Responses to anomalies

Not every anomaly requires the same action.

```flowchart
Continue
Rollback
Disable Tool
Switch Model
Reduce Autonomy
Escalate Incident
```

### Continue

The signal is within the expected range or was assessed as non-critical.

### Rollback

A new version produces regression. The previous approved configuration is restored.

### Disable tool

An integration returns incorrect results or behaves unsafely. The remaining system continues with limited capability.

### Switch model

An approved alternative model takes over selected routes.

### Reduce autonomy

Write actions are temporarily blocked or require additional review.

### Escalate incident

Security, privacy, compliance or material business risk triggers the incident process.

## Ownership for evaluation and operations

Evaluation is not owned by one role.

| Role | Typical responsibility |
| --- | --- |
| **Business Owner** | value, risk acceptance and success criteria |
| **Domain Expert** | golden dataset, rubrics and professional review |
| **AI / ML Engineer** | model, prompt and evaluation implementation |
| **Data Engineer** | sources, pipelines, index and data quality |
| **Software Engineer** | tools, APIs, validation and reliability |
| **Security / Privacy** | attacks, data flows and safety controls |
| **Governance** | policies, documentation, approval and traceability |
| **Operations** | monitoring, alerting, incidents and rollback |
| **End User** | usability and real-world feedback |

Every metric requires an owner, a target and a defined response.

## Required documentation

| Artifact | Content |
| --- | --- |
| **Evaluation Plan** | objectives, scope, metrics and owners |
| **Evaluation Dataset** | cases, expectations, tags and provenance |
| **Scoring Specification** | rules, rubrics, judges and thresholds |
| **System Version** | model, prompt, data, tools and policies |
| **Evaluation Run** | results, failures, cost and latency |
| **Release Decision** | go/no-go, exceptions and accepted risk |
| **Monitoring Plan** | signals, thresholds, trends and alerts |
| **Runbook** | response to regression, failure and security incidents |
| **Incident History** | cause, impact and corrective action |
| **Change History** | versions and approvals |

## A pragmatic starting point

An organization does not need a complete evaluation platform on day one.

A useful first implementation:

```flow linear vertical
Define 20–50 representative cases
Specify critical failures and mandatory conditions
Automate deterministic checks
Add a simple domain rubric
Record model, prompt and data versions
Integrate evaluation into release workflow
Turn production feedback into new tests
```

The system can then expand toward:

- additional segments,
- agent traces,
- automated judges,
- continuous sampling,
- latency and cost budgets,
- dashboards,
- release gates,
- controlled experiments.

## The central conclusion

> **AI quality is not a fixed property of a model. It is the measurable outcome of a complete, versioned and operated system.**

A more reliable production environment emerges when:

- real and critical cases are represented,
- components and end-to-end workflows are evaluated separately,
- every relevant configuration is versioned,
- quality, latency, cost and risk are considered together,
- releases depend on explicit gates,
- production signals produce new tests and improvements,
- rollback, escalation and reduced autonomy are prepared.

## The series at a glance

```flow linear vertical
AI Foundations — How AI Works [done]
Language Models and Providers [done]
Enterprise Data, Embeddings and RAG [done]
AI Agents and Automation [done]
Known Problems and Failure Modes [done]
Evaluation, Costs and Operations [active]
AI Governance
```

Part 7 will combine the technical and operational lessons from the series into a complete **AI Governance model** covering roles, policies, risk classes, approvals, lifecycle, traceability and continuous control.

## Sources and further reading

- [OpenAI — Evals API Reference](https://platform.openai.com/docs/api-reference/evals)
- [OpenAI — Evals Framework](https://github.com/openai/evals)
- [OpenAI — Optimizing Latency with API Models](https://help.openai.com/en/articles/6901266)
- [Anthropic — Using the Evaluation Tool](https://docs.anthropic.com/en/docs/test-and-evaluate/eval-tool)
- [Anthropic — Reducing Latency](https://docs.anthropic.com/en/docs/test-and-evaluate/strengthen-guardrails/reduce-latency)
- [Google Cloud — Gen AI Evaluation Service Quickstart](https://docs.cloud.google.com/vertex-ai/generative-ai/docs/models/evaluation-quickstart)
- [MLflow — LLM and Agent Evaluation](https://mlflow.org/docs/latest/genai/eval-monitor/index.html)
- [MLflow — Evaluation Quickstart](https://mlflow.org/docs/latest/genai/eval-monitor/quickstart/)
- [OpenTelemetry — Semantic Conventions](https://opentelemetry.io/docs/specs/semconv/)
- [NIST — AI Risk Management Framework](https://www.nist.gov/itl/ai-risk-management-framework)
- [NIST — Generative AI Profile](https://www.nist.gov/publications/artificial-intelligence-risk-management-framework-generative-artificial-intelligence)
- [NIST — AI RMF Playbook](https://airc.nist.gov/airmf-resources/playbook/)
