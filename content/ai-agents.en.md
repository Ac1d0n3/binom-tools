---
title: AI Agents and Automation — From Language Model to Controlled Action
description: How AI agents pursue goals across multiple steps, use tools under control and operate safely inside deterministic process boundaries.
category: Artificial Intelligence
tags:
  - ai
  - ai-foundations
  - ai-agents
  - agentic-ai
  - automation
  - tool-calling
  - orchestration
  - human-in-the-loop
  - workflows
  - ai-governance
order: -1
author: Thomas Lindackers
series: ai-foundations
seriesPart: 4
seriesTitle: AI Foundations
hero: images/playbooks/ai-agents-hero.png
---

## From response to action

A language model initially produces an output. It can explain, structure, classify or propose a next step. That output does not automatically become a real-world action.

For an AI system to retrieve a record, prepare an email, check a calendar, create a ticket, update a report or execute an action in a business system, it needs additional software components.

An AI agent is therefore not merely a model and it is not an independently thinking digital being.

> **An AI agent is an orchestrated software architecture that connects a goal, a language model, working state, defined tools and technical controls.**

Within that architecture, the model can assess which next step is plausible. Whether the step is permitted, how it is executed and which limits apply must be controlled by the surrounding application.

```flowchart
Goal
Understand current state
Select next step
Execute permitted tool
Observe result
Evaluate progress
```

The loop does not end only on success. It can also stop for approval, time limits, budgets, policies, errors or escalation.

## Chatbot, tool-using assistant and agent are not the same

The terms are frequently mixed together. A chatbot can be highly capable without being an agent. A single tool call also does not automatically create a multi-step agent.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-agents-img1-en.png"
        alt="Comparison of chatbot, tool-using assistant and AI agent by execution state, tool usage and multi-step goal pursuit"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        A chatbot primarily creates content. A tool-using assistant can invoke a defined function. An agent pursues a goal through multiple controlled steps and uses the results of its actions.
    </figcaption>
</figure>

### Chatbot

```text
Question
→ Model
→ Answer
```

Typical tasks include answering questions, drafting text, summarizing content and generating suggestions. The user initiates every next step. There is normally no independent execution state that tracks an ongoing task across several actions.

### Tool-using assistant

```text
Question
→ Model
→ Tool call
→ Tool result
→ Answer
```

A tool-using assistant can look up a customer number, perform a calculation or search available appointments. The tool call can still be part of one request. The user frequently confirms the actual action or initiates the next step again.

### AI agent

```text
Goal
→ analyze state
→ plan next step
→ use tool
→ observe result
→ update state
→ continue or finish
```

Typical characteristics include:

- multi-step execution
- working state
- tool selection
- observation of intermediate results
- adaptation of the execution path
- stop and escalation conditions
- optional human approvals

There is no universal mathematical boundary between an assistant and an agent. In practice, the important distinction is whether the system coordinates multiple execution steps and uses new observations to decide what happens next.

## Tool calling is a protocol, not permission

A language model does not directly operate a business system. It usually creates a structured request that an application evaluates.

```json
{
  "name": "create_support_ticket",
  "description": "Create a support ticket after validation and approval.",
  "input_schema": {
    "type": "object",
    "properties": {
      "customer_id": { "type": "string" },
      "category": {
        "type": "string",
        "enum": ["billing", "relocation", "meter", "outage", "contract"]
      },
      "priority": {
        "type": "string",
        "enum": ["low", "medium", "high"]
      },
      "summary": { "type": "string" }
    },
    "required": ["customer_id", "category", "priority", "summary"]
  }
}
```

The model can then propose a matching tool call:

```json
{
  "tool": "create_support_ticket",
  "arguments": {
    "customer_id": "C-10472",
    "category": "outage",
    "priority": "high",
    "summary": "Customer reports a complete power outage."
  }
}
```

That proposal must not be confused with authorization. The application still needs to check:

- Is the tool approved for the use case?
- May the current user use it?
- Are the parameters syntactically and operationally valid?
- Does the input contain sensitive or prohibited data?
- Is human approval required?
- Has a cost or step budget been exceeded?
- Has the action already been executed?
- Must the action be logged?

```flowchart
Model proposes
Orchestrator validates
Tool executes
Result returns
```

Technical execution belongs to the controlled software layer, not the language model.

## The agent works in an execution loop

An agent does not solve a multi-step task completely in one operation. It processes the current state, selects an allowed action and evaluates the result.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-agents-img2-en.png"
        alt="Agent execution loop covering goal reception, state understanding, planning, tool selection, action, observation and progress evaluation with guardrails and possible outcomes"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        The agent works iteratively. Every observation updates working state and affects the decision about the next permitted step.
    </figcaption>
</figure>

### Goal and state

A robust goal definition contains the desired outcome, business context, allowed systems, prohibited actions, success criteria, time and cost limits, and required approvals.

Working state can contain:

- original goal
- user identity
- previous steps
- tool results
- retrieved documents
- remaining subtasks
- consumed budget
- remaining time
- existing approvals
- errors and retries

Some state can be present in model context. Long-running or interruptible processes should also persist state outside the model.

### Planning, tool selection and action

The agent assesses which action is most likely to advance the goal. It may search for information, ask a question, call a tool, request approval or complete the run.

The agent should only see or use tools approved for the current context. Availability can depend on user role, tenant, data classification, workflow stage, region, risk class or approval status.

Execution happens through controlled components such as an API gateway, workflow engine, function runtime, message queue or isolated sandbox. The execution layer validates parameters again and does not accept arbitrary generated commands without controls.

### Observation and progress evaluation

Tool results should be structured:

```json
{
  "status": "success",
  "ticket_draft_id": "DRAFT-7821",
  "missing_fields": [],
  "requires_approval": true
}
```

A failure is also an observation:

```json
{
  "status": "failed",
  "error_code": "CUSTOMER_NOT_FOUND",
  "retryable": false,
  "recommended_next_step": "request_customer_identifier"
}
```

At the end of each iteration, the system checks:

- Has the goal been reached?
- Is information missing?
- Is approval required?
- Is another path appropriate?
- Has a policy been violated?
- Has a step, time or cost limit been reached?
- Should the run abort or escalate?

```text
Completed successfully
Partial progress — continue the loop
Human approval required
Stopped by policy
Failed — retry, compensate or escalate
```

## Working state is more than conversation history

A long chat transcript is not a robust process state. A production agent needs structured state:

```json
{
  "run_id": "RUN-2026-0717-0042",
  "goal": "Prepare an approved response for the customer request.",
  "current_stage": "approval_pending",
  "completed_steps": [
    "classify_request",
    "retrieve_customer",
    "draft_response"
  ],
  "pending_steps": [
    "manager_approval",
    "send_response"
  ],
  "tool_results": {
    "customer_id": "C-10472",
    "classification": "billing"
  },
  "limits": {
    "max_steps": 12,
    "used_steps": 5,
    "max_cost_eur": 0.80
  }
}
```

Structured state supports resumption after interruption, auditability, root-cause analysis, rollback, handoff to a person and deterministic validation. The model should receive only the state required for the next decision.

## Anatomy of an enterprise AI agent

A production agent consists of several clearly separated layers.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-agents-img3-en.png"
        alt="Enterprise AI agent architecture with inputs, agent orchestrator, language model, planning, tool registry, working state, retrieval, permissions, policy engine, monitoring, external systems and oversight layer"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        An agent is an orchestrated system. The language model is central, but it is not the complete architecture.
    </figcaption>
</figure>

### Users and applications

Agents can be started through chat, web applications, business applications, APIs, events, queues, schedules or workflows. The interface should make clear what the agent can do, which systems it uses, when an action is prepared or executed and when approval is required.

### Agent orchestrator

The orchestrator manages execution. Typical responsibilities include:

- invoking the model
- processing tool calls
- loading and storing state
- evaluating policies
- managing approvals
- enforcing step limits
- handling errors and retries
- logging outcomes
- continuing or stopping the run

### Language model

The model can support goal interpretation, task decomposition, selection among approved tools, processing unstructured results and drafting outputs. It should not be the sole authority for permissions, financial limits or irreversible actions.

### Tool registry

The tool registry describes available capabilities:

- tool name and purpose
- input and output schema
- risk class
- required permissions
- approval rules
- timeouts and cost
- idempotency
- owner and version

| Tool | Risk | Approval | Repeatability |
| --- | --- | --- | --- |
| Read customer data | Medium | Role check | Yes |
| Create ticket draft | Low | No additional approval | Yes |
| Send customer email | Medium | User approval | Conditional |
| Trigger payment | High | Dual approval | No |
| Delete record | High | Business and technical approval | No |

### Retrieval and knowledge

The agent can use RAG to retrieve policies, product information, process descriptions, data catalogs or previous tickets. Retrieval supplies context. Tools supply live data or perform actions. These are distinct capabilities.

### Identity and permissions

An agent needs a technical identity. The identity of the initiating user must also remain available.

Questions to resolve include:

- Is the agent acting on behalf of a user?
- Does it have its own service identity?
- Are user permissions delegated?
- Which rights apply to scheduled or event-driven runs?
- How are tenant and region determined?
- How are temporary permissions revoked?

A broadly privileged agent identity creates substantial risk.

### Policy engine

Policies should be technically enforceable outside the language model.

```text
If tool = delete_customer_record
Then human approval is required.

If classification >= confidential
Then use only an internal model or approved endpoint.

If amount > EUR 5,000
Then dual approval is required.

If step_count >= 12
Then stop and escalate.
```

System prompts can explain rules. They do not replace enforced policy.

### Logging and monitoring

An agent run should record at least the run ID, user and agent identity, goal, model version, prompt and policy versions, tool calls, tool results, approvals, step count, duration, cost and final status.

Sensitive content must still follow data-classification rules. Complete logging must not create an uncontrolled secondary data store.

## Deterministic or agentic?

Not every process benefits from an agent. Stable, clearly defined workflows are often simpler, less expensive and easier to audit when implemented as classical automation.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-agents-img4-en.png"
        alt="Comparison between deterministic workflow, agentic workflow and a hybrid architecture with fixed process boundaries and bounded agentic decisions"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Deterministic and agentic logic solve different problems. A hybrid design is often the most robust enterprise architecture.
    </figcaption>
</figure>

### Deterministic workflow

```text
Fixed trigger
→ fixed validation
→ fixed transformation
→ fixed action
→ fixed result
```

Suitable for clear business rules, structured inputs, high repetition, regulated processes and exact calculations.

Advantages:

- predictable behaviour
- simple testing
- clear error paths
- low model cost
- strong auditability

### Agentic workflow

```text
Goal
→ interpret situation
→ select next step
→ use tool
→ evaluate result
→ adapt path
```

Suitable for variable and unstructured inputs, several valid execution paths, discovery across systems and interpretation-heavy tasks.

Advantages:

- flexible processing
- adaptation to new observations
- less need to program every path in advance
- natural handling of unstructured content

Disadvantages:

- lower predictability
- more demanding evaluation
- additional latency and cost
- harder root-cause analysis
- wider attack and misuse surface

### The hybrid middle way

```flowchart
Deterministic workflow
Bounded agentic decision
Deterministic execution
Deterministic validation
```

Example:

1. A workflow validates identity, data classification and required fields.
2. An agent analyzes the unstructured request and proposes a category and route.
3. A fixed rule validates the category.
4. A deterministic service creates a draft.
5. A person approves transmission.
6. A fixed process logs and archives the result.

The agent handles interpretation where flexibility is useful. Control and irreversible actions remain deterministic.

## Autonomy is not a binary decision

| Level | Behaviour | Example |
| --- | --- | --- |
| **1 — Analysis only** | Produces a recommendation and executes nothing | Assess contract risk |
| **2 — Prepare action** | Creates a draft | Prepare an email or ticket |
| **3 — Approval required** | Pauses before execution | Send response or modify record |
| **4 — Execute within boundaries** | Uses approved tools within limits | Process standard tickets |
| **5 — Multi-step autonomous** | Plans and coordinates several actions | Complex research and case preparation |

The highest level is not automatically the best. Appropriate autonomy depends on potential harm, reversibility, data classification, financial impact, regulatory relevance and rollback availability.

> **Autonomy should be no higher than the process requires.**

## Human in the loop must be part of the technical process

Approval is more than telling the model to be careful. A robust approval flow needs:

- unique run ID
- exact proposed action
- visible parameters
- supporting evidence
- risk and policy notes
- authorized approver
- expiry
- timestamped decision
- unchanged continuation after approval

```flowchart
Agent prepares action
Execution pauses
Person reviews parameters and evidence
Approve or reject
Run resumes under control
```

After approval, the approved parameters should not be silently regenerated by the model.

## Safe tool interfaces

An agent should not receive a general-purpose tool when a narrower function is sufficient.

Risky:

```text
execute_sql(sql)
run_shell(command)
call_any_api(url, method, body)
```

Safer:

```text
get_customer_summary(customer_id)
create_ticket_draft(category, priority, summary)
list_available_appointments(region, date_range)
request_record_deletion(record_id, reason)
```

Narrow tools reduce invalid parameters, data exfiltration, consequences of prompt injection and unintended side effects.

Additional controls include schema validation, allow lists, parameter limits, output validation, timeouts, rate limits, sandboxing, network restrictions, separated read and write tools, and transactional execution.

## Retries and duplicate actions

Agents may repeat calls when a response is lost or a step remains ambiguous. Writing tools can then create duplicate side effects.

Write tools should be idempotent where possible:

```json
{
  "idempotency_key": "RUN-2026-0717-0042:create-ticket",
  "customer_id": "C-10472",
  "operation": "create_ticket"
}
```

When the same key is received again, the system returns the existing result instead of performing the action again.

## Failure, retry and compensation

| Failure type | Possible handling |
| --- | --- |
| Temporary network issue | bounded retry with backoff |
| Rate limit | wait or use an approved alternative endpoint |
| Invalid parameters | do not retry unchanged; correct input |
| Missing permission | stop and escalate |
| Business conflict | request human decision |
| Partially completed action | compensate or start manual recovery |
| Policy violation | stop immediately and log |

An agent needs a maximum retry count. Otherwise, a loop can create unnecessary cost and system load.

## Multi-agent systems are not a default objective

Multiple agents can divide work by role, such as research, planning, validation or execution. This can be useful where responsibilities are genuinely distinct, but it also creates more model calls, harder state handoffs, more failure modes and more complex monitoring.

A single agent with well-defined tools and deterministic orchestration is often the better starting point. Use multiple agents only where the separation creates measurable value.

## Governance and control across the full lifecycle

AI agents connect generative models to real systems and actions. Governance must therefore cover strategy, development, operation, evaluation and assurance.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-agents-img5-en.png"
        alt="Governance and control framework for enterprise AI agents covering strategy, design, operation, review, compliance, governance pillars and a controlled agent loop"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Guardrails must operate throughout the lifecycle: from purpose and design through operation and evaluation to audit and compliance.
    </figcaption>
</figure>

### 1. Strategy and policy

Before implementation, define the business objective, permitted and excluded tasks, responsibilities, risk tolerance, allowed data classifications and success criteria.

### 2. Design and build

Governance by design includes:

- minimal tool permissions
- secure defaults
- separated read and write operations
- data minimization
- explicit state transitions
- approval checkpoints
- tests against unexpected input
- prompt-injection controls
- fallback and rollback
- versioning

### 3. Operate and monitor

Production monitoring includes:

- completion rate
- tool failures
- latency and cost
- step count
- policy stops
- approval and abort rates
- unusual tool sequences
- model and prompt drift
- user feedback

### 4. Review and improve

Agents should be evaluated with normal requests, boundary cases, incomplete information, conflicting data, tool outages, invalid permissions and unreachable goals.

Improvements may target tool descriptions, schemas, system instructions, policies, retrieval, models, thresholds or approval rules.

### 5. Assure and comply

Organizations should be able to demonstrate:

- which agent version ran
- which tools were available
- which policies applied
- which approvals were granted
- which data was used
- which actions were executed
- which risks were assessed
- which tests passed
- who was responsible for operation and outcome

Governance is not an isolated documentation activity at the end. It is a continuous technical and organizational control layer.

## Example: agent for incoming service requests

A realistic agent could prepare new customer requests.

### Goal

> Analyze new requests, identify the customer, determine category and priority, retrieve relevant policies and create ticket and response drafts. Do not send a message without approval.

### Allowed tools

```text
read_incoming_request
find_customer
retrieve_service_policy
check_known_outage
create_ticket_draft
create_response_draft
request_human_approval
```

### Prohibited tools

```text
send_email
change_contract
issue_refund
delete_customer
```

### Flow

```flow linear vertical
Read new request
Minimize personal data
Resolve customer identity
Determine category and priority
Retrieve relevant policy
Check known outage
Create ticket draft
Create response draft
Request human approval
Deterministic sending process
```

### Stop conditions

- customer cannot be identified
- sensitive request outside the use case
- conflicting policies
- missing permission
- more than two failed tool calls
- insufficient evidence
- maximum processing time exceeded

The agent does not replace the ticketing system. It coordinates controlled steps around existing systems.

## What should be documented for a production agent

| Area | Required documentation |
| --- | --- |
| **Use case** | Goal, user groups, value and exclusions |
| **Autonomy level** | Analysis, preparation, approval or autonomous execution |
| **Owner** | Business, technical and governance owner |
| **Model** | Provider, model version and configuration |
| **Orchestration** | Framework, state model and execution logic |
| **Tools** | Purpose, schema, owner, version and risk class |
| **Permissions** | User, agent and system identities |
| **Policies** | Allowed actions, limits and stop conditions |
| **Approvals** | Which actions require approval and by whom |
| **State** | Persistence, retention and resumption |
| **Data** | Inputs, classification, storage and transfer paths |
| **Evaluation** | Task quality, tool use, security and cost |
| **Monitoring** | Runs, steps, latency, cost, errors and anomalies |
| **Recovery** | Retry, idempotency, rollback and compensation |
| **Versioning** | Model, prompts, tools, policies and workflow |
| **Retirement** | Shutdown, permission revocation and data cleanup |

## The central conclusion

> **An AI agent is not a single model feature. It is a controlled execution architecture composed of a model, state, tools, orchestration, identity, policies, approvals and monitoring.**

Tool calling enables actions. An agent loop coordinates multiple steps. Deterministic workflows establish the safe boundaries.

The best architecture therefore does not maximize autonomy. It uses exactly as much flexible decision-making as the use case requires.

## The series at a glance

```flow linear vertical
AI Foundations — How AI Works [done]
Language Models and Providers [done]
Enterprise Data, Embeddings and RAG [done]
AI Agents and Automation [active]
Known Problems and Failure Modes
Evaluation, Costs and Operations
AI Governance
```

Part 5 will examine **Known Problems and Failure Modes**: hallucinations, prompt injection, data leakage, tool misuse, loops, automation errors and other risks that have greater consequences in agentic systems.

## Sources and further reading

- [OpenAI Agents SDK — Overview](https://openai.github.io/openai-agents-python/)
- [OpenAI Agents SDK — Tools](https://openai.github.io/openai-agents-python/tools/)
- [OpenAI Agents SDK — Running Agents](https://openai.github.io/openai-agents-python/running_agents/)
- [OpenAI Agents SDK — Human in the Loop](https://openai.github.io/openai-agents-python/human_in_the_loop/)
- [OpenAI Agents SDK — Tracing](https://openai.github.io/openai-agents-python/tracing/)
- [Anthropic — Tool Use](https://docs.anthropic.com/en/docs/agents-and-tools/tool-use/overview)
- [Anthropic — Implement Tool Use](https://docs.anthropic.com/en/docs/agents-and-tools/tool-use/implement-tool-use)
- [Anthropic — Computer Use and the Agent Loop](https://docs.anthropic.com/en/docs/agents-and-tools/computer-use)
- [Google Agent Development Kit](https://google.github.io/adk-docs/)
- [Google ADK — Safety and Security for AI Agents](https://google.github.io/adk-docs/safety/)
- [Google ADK — Evaluating Agents](https://google.github.io/adk-docs/evaluate/)
- [NIST AI Risk Management Framework](https://www.nist.gov/itl/ai-risk-management-framework)
- [NIST AI RMF — Generative AI Profile](https://www.nist.gov/publications/artificial-intelligence-risk-management-framework-generative-artificial-intelligence)
