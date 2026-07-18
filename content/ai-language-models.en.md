---
title: AI Language Models — How They Work, Provider Families and Model Selection
description: How language models tokenize prompts, process context and generate responses — including provider-family profiles, access models and practical selection criteria.
category: Artificial Intelligence
tags:
  - ai
  - ai-foundations
  - language-models
  - llm
  - tokens
  - transformer
  - context-window
  - open-weight
  - self-hosted-ai
  - model-selection
order: -1
author: Thomas Lindackers
series: ai-foundations
seriesPart: 2
seriesTitle: AI Foundations
hero: images/playbooks/ai-language-models-hero.png
---

## Language models are not knowledge databases

Part 1 of this series introduced the underlying machine-learning principle: a model is trained against data and an objective, its parameters are optimized, and the resulting model is applied to new inputs during inference.

A language model is a specialized implementation of that principle. It does not process language directly as human meaning. It first transforms text into numeric representations and then calculates which continuation is likely and suitable within the current context.

> **A language model normally does not retrieve a completed answer from a database. It constructs the output step by step from the prompt, the available context and its trained parameters.**

Modern language models can do considerably more than continue text. They write, analyze, structure, translate and generate code. Multimodal variants can also process images, audio, video or computer screens. Through tools and APIs, models can retrieve data, execute calculations and prepare actions.

The model itself nevertheless remains a computational function:

```text
Input + context + model parameters
→ probability calculation
→ next output element
→ growing context
→ completed response
```

## From prompt to response

The visible starting point is the **prompt**. This is not necessarily limited to the latest words written by the user. Depending on the application, system instructions, prior messages, documents, tool results and metadata may also form part of the input.

Before a language model can process text, a **tokenizer** divides it into smaller units called tokens.

A token may be:

- a complete word
- part of a word
- punctuation
- a number
- whitespace or a formatting element
- another encoded input unit in a multimodal model

Tokens are transformed into numeric vectors. These representations pass through the model layers. Most current large language models are based on the **Transformer architecture**.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-language-models-img1-en.png"
        alt="Technical process from a prompt through tokenization, numeric representation and Transformer layers to a response generated step by step"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        The language model divides the input into tokens, processes their relationships in context and calculates the next output element.
    </figcaption>
</figure>

The illustrated process is intentionally simplified:

1. The prompt is received.
2. The text is tokenized.
3. Tokens are converted into numeric representations.
4. Model layers process relationships and context.
5. The model calculates a distribution over possible next tokens.
6. A token is selected according to the model and sampling configuration.
7. The new token becomes part of the context.
8. The process repeats until the response is complete.

The model therefore does not predict the complete answer only once. It performs many successive inference steps while generating the output.

## What Transformer layers do

Transformer models use **attention mechanisms** to weight relationships between elements of the input.

In simplified terms:

- Which previous tokens matter for the current token?
- Which terms refer to one another?
- Which instruction determines the required format?
- Which information in a document is relevant to the question?
- Which part of the generated output should influence the continuation?

This does not mean that the model consciously interprets the text as a human would. Attention is a mathematical weighting mechanism within the model computation.

Consider this prompt:

```text
Summarize the contract and list only
the termination periods in a table.
```

It contains several requirements:

- task: summarize
- restriction: only termination periods
- output format: table
- information source: contract

A capable language model must relate these elements to one another within the available context.

## Probabilities, sampling and reproducible results

After processing the context, the model calculates possible next tokens with different scores or probabilities.

A highly simplified example:

```text
Thank        0.42
Certainly    0.18
The          0.12
Below        0.08
...
```

The application can additionally influence token selection. Typical parameters include:

| Parameter | Simplified effect |
| --- | --- |
| **Temperature** | Controls how strongly likely or more varied continuations are preferred |
| **Top-p** | Restricts selection to a cumulative probability set |
| **Maximum output tokens** | Limits output length |
| **Stop conditions** | End generation at defined sequences |
| **Seed**, where supported | May improve repeatability for a particular configuration |
| **Structured output / schema** | Restricts the format, for example to valid JSON |

A low temperature does not automatically make an answer factually correct. It primarily reduces variation in token selection. Similarly, a high model probability does not mean that a statement is objectively true.

Hallucinations, bias, prompt injection and other failure modes are addressed in a later part of this series.

## The context window is the working space of a request

A language model cannot process an unlimited amount of content at once. Every model variant has a **context window**.

Depending on the API and application, the context window can contain:

- system instructions
- developer or application instructions
- the current user prompt
- relevant conversation history
- retrieved documents
- tool definitions
- tool results
- metadata and structured inputs
- already generated parts of the response

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-language-models-img2-en.png"
        alt="Limited context window containing system instructions, user prompt, conversation history, retrieved documents, tool results and the generated response"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        All components of a request share a common token budget. The quantities shown are illustrative examples, not the specification of a particular model.
    </figcaption>
</figure>

A larger context window allows an application to provide more material in one request. It does not automatically guarantee a better result.

Very large contexts can:

- increase cost
- increase latency
- contain irrelevant information
- carry conflicting instructions
- make relevant passages harder to identify
- reduce the remaining output capacity

**Context management** is therefore more important than simply sending as much content as possible.

A well-designed application decides deliberately:

- Which parts of the conversation history are required?
- Which document passages are actually relevant?
- Which tool results must be transferred in full?
- Which content can be summarized?
- Which instructions must remain persistent?
- Which information must not be sent to the model for privacy reasons?

## Context is not the same as model knowledge

Two different forms of knowledge must be distinguished.

### Knowledge represented in model parameters

During training, the model learned statistical structures from training data. This knowledge cannot be queried like a relational database and has no automatic guarantee of being current or true.

### Knowledge supplied in the current context

Additional information can be provided at runtime:

- uploaded files
- search results
- enterprise-system data
- data-catalog content
- API results
- structured datasets
- vector-search results

This normally does not retrain the model permanently. It only provides additional context for the current request.

Connecting language models to enterprise knowledge through embeddings, retrieval and RAG is the subject of **Part 3**.

## A provider is not one single model

OpenAI, Anthropic, Google, Meta and Mistral are providers or organizations with multiple model families, variants and platform offerings.

Models within the same family may differ substantially:

- general model versus reasoning model
- fast variant versus high-capability variant
- text model versus multimodal model
- small local model versus large cloud model
- coding model versus real-time audio model
- current version versus previous version
- API model versus open-weight model

The statement “Provider A is better than Provider B” is therefore usually too imprecise.

A technically meaningful selection should identify at least:

```text
Provider
+ model family
+ exact model version
+ access path
+ operating model
+ configuration
+ test dataset
+ quality metrics
```

## Three different layers: provider, weights and operation

A common mistake is to treat **Closed API**, **Open Weight** and **Self-Hosted** as three entirely separate model types. In fact, they describe different aspects.

### Closed API

The model is used through an API or managed service. The provider operates the model infrastructure and controls the weights and model updates.

Typical advantages:

- fast start
- limited infrastructure responsibility
- managed scaling
- rapid access to new capabilities
- integrated security and enterprise functions

Typical constraints:

- provider dependency
- limited insight into weights and training
- data may leave the organization's operating environment
- prices, limits and model versions can change
- local execution is generally unavailable

### Open-weight model

The trained model weights are available and can be operated under the applicable license by the organization or a cloud provider.

Open Weight does not automatically mean:

- fully open source
- unrestricted use for every purpose
- published training data
- published training code
- unrestricted commercial use

The license remains decisive.

### Self-hosted

Self-hosted describes the operating model. The organization runs the model in an owned or controlled environment.

Possible environments include:

- on-premises data center
- private cloud
- dedicated cloud account
- edge device
- workstation
- isolated research environment

Self-hosting is commonly possible with open-weight models. A provider may also offer dedicated or privately hosted enterprise deployments.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-language-models-img3-en.png"
        alt="Comparison of closed API, open-weight model and self-hosted model across access, control, infrastructure, customization, privacy, time to value and cost model"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Model access and operating model jointly determine how quickly a solution can start and how much control, infrastructure responsibility and customization remain with the organization.
    </figcaption>
</figure>

Real architectures often sit between the extremes:

```text
Application in the internal network
→ internal AI gateway
→ pseudonymization and policy checks
→ external model provider
→ controlled response processing
```

or:

```text
Application
→ self-hosted open-weight model
→ external API fallback for complex tasks
```

A hybrid approach can be more useful than the binary decision to externalize everything or host everything internally.

## How major model families typically differ

The model landscape changes quickly. New versions can shift capabilities, context sizes, prices and availability within months.

The following diagram is therefore not a permanent benchmark ranking. It shows typical provider-family profiles at the time of publication.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-language-models-img4-en.png"
        alt="Illustrative comparison of typical strengths of the OpenAI, Anthropic, Google, Meta and Mistral model families across writing, reasoning, coding, long documents, multimodality, tool integration, enterprise controls and local deployment"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        The profiles summarize typical characteristics of current model families. They do not replace an evaluation of an exact model version in the target use case.
    </figcaption>
</figure>

### OpenAI GPT family

Typical profile:

- broad general-purpose models for text, reasoning, coding and multimodal workloads
- strong API and tool integration
- managed platform and enterprise capabilities
- several capability and cost tiers
- proprietary operation without publicly available weights for the central GPT family

### Anthropic Claude family

Typical profile:

- strong text work and document analysis
- extensive use of long context
- reasoning, coding and tool capabilities
- API-based and managed usage
- proprietary model weights

### Google Gemini family

Typical profile:

- multimodal model families
- text, image, audio and other modalities depending on the model
- large context windows in selected variants
- function calling plus integrated Google tools and agent offerings
- delivery through Google services and APIs

### Meta Llama family

Typical profile:

- openly available model weights under the applicable Llama license
- broad ecosystem of cloud, hosting and inference providers
- local or controlled deployment
- several sizes and specialized variants
- infrastructure, security controls and product integration remain with the operator or platform partner

### Mistral model families

Typical profile:

- combination of commercial and open-weight models
- models for general tasks, coding and specialist workloads
- API, cloud and self-deployment options
- local deployment for selected models
- flexible integration with additional operational responsibility when self-hosted

These profiles are intentionally qualitative. Individual versions may differ from their family tendency.

## Why public benchmarks are not enough

Benchmarks are useful, but they do not replace real testing.

A benchmark usually measures:

- a defined task class
- a fixed dataset
- a particular evaluation method
- an exact model and prompt configuration
- often a limited set of languages or domains

Production systems add further factors:

- enterprise-specific terminology
- long and unstructured documents
- varied user phrasing
- tables, images and attachments
- internal security rules
- RAG and tool calls
- structured outputs
- latency
- cost
- error handling
- reproducibility
- data residency
- availability and rate limits

A model with the higher general benchmark can therefore perform worse in a particular business process.

## Choose the model for the task, not the brand

Model selection should start with the use case.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-language-models-img5-en.png"
        alt="Decision framework for selecting a language model based on task, requirements, trade-offs, operating model and evaluation with real data"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        The right choice follows from requirements and measurable tests. Brand awareness alone is not a selection criterion.
    </figcaption>
</figure>

### 1. Define the task precisely

Not:

> We need the best AI model.

Instead:

> The system must classify German customer-service emails into one of twelve categories, draft a response and route ambiguous cases for human review.

### 2. Define inputs and outputs

- text only or multimodal input?
- short prompts or long documents?
- free response or fixed JSON schema?
- single request or long conversation?
- tool calls or text output only?
- English, German or multilingual?

### 3. Specify non-functional requirements

- maximum latency
- expected request volume
- cost budget
- data residency
- permitted providers
- availability
- auditability
- local-deployment requirement
- model and prompt versioning
- required security controls

### 4. Select candidates

There is no need to evaluate every available version. A useful shortlist may include:

- a high-capability proprietary model
- a faster and less expensive proprietary model
- an appropriate open-weight model
- a specialized coding, vision or audio model where required

### 5. Evaluate with real cases

The test set should include realistic difficulty:

- common cases
- boundary cases
- incomplete information
- unusual phrasing
- long inputs
- domain ambiguity
- prohibited requests
- tool failures
- structured outputs
- sensitive data

### 6. Evaluate the complete system

The model answer is only one component.

```text
Overall quality
=
model
+ prompt
+ context
+ retrieval
+ tools
+ validation
+ user interface
+ process controls
```

A smaller model inside a well-designed application can outperform a larger model inside a poorly controlled architecture.

## A practical evaluation grid

| Dimension | Example measurement |
| --- | --- |
| **Domain accuracy** | Share of correct results against a golden dataset |
| **Task completion** | Are all requested steps and constraints followed? |
| **Groundedness** | Is the response supported by the supplied sources? |
| **Format quality** | Share of valid structured outputs |
| **Completeness** | Are all required facts considered? |
| **Latency** | Median plus 95th and 99th percentile |
| **Cost** | Cost per successful business transaction rather than only per token |
| **Tool reliability** | Success rate for function calls and follow-up actions |
| **Security** | Behaviour under prompt injection, data-exfiltration and prohibited tasks |
| **Operability** | Monitoring, versioning, fallback and rollback |

The later part **Evaluation, Costs and Operations** will examine these metrics and production operations in detail.

## Why multi-model architectures can be useful

The selection does not have to remain fixed to exactly one model.

An application can route workloads:

```text
Simple classification
→ small fast model

Complex document analysis
→ capable long-context model

Sensitive internal content
→ self-hosted model

Code review
→ specialist coding model

Outage or rate limit
→ approved fallback model
```

Advantages:

- better cost control
- reduced latency
- separated handling of sensitive workloads
- lower dependency on one provider
- targeted use of specialist models

Disadvantages:

- greater technical complexity
- more evaluations
- differing output formats
- additional versioning and governance requirements
- harder root-cause analysis
- possible inconsistency between models

A model router is therefore not an automatic optimization. It requires policies, telemetry, testing and explicit fallback strategies.

## What organizations should document

At least the following information should be traceable for every production model use:

| Area | Required documentation |
| --- | --- |
| **Use case** | Purpose, user groups and permitted usage |
| **Model** | Provider, family, exact version and endpoint |
| **Operation** | API, cloud, self-hosted or hybrid |
| **Data** | Inputs, classification, storage locations and transfer paths |
| **Context** | System prompt, RAG sources, tools and maximum sizes |
| **Configuration** | Sampling, output limits and structured formats |
| **Evaluation** | Test dataset, metrics, thresholds and latest approval |
| **Fallback** | Behaviour during failure, uncertainty or unavailability |
| **Responsibility** | Owner, technical operation and domain approval |
| **Versioning** | Model, prompt, tool and data-source versions |

These requirements lead directly toward AI governance. Governance does not begin only with a policy document. It starts with a technically unambiguous description of the system.

## The series at a glance

```flow linear vertical
AI Foundations — How AI Works [done]
Language Models and Providers [active]
Enterprise Data, Embeddings and RAG
AI Agents and Automation
Known Problems and Failure Modes
Evaluation, Costs and Operations
AI Governance
```

The central conclusion of Part 2 is:

> **A language model generates a response token by token inside a limited context window. Provider families differ in capability, access, control and operating options. The best model is not the best-known model, but the one that performs a clearly defined use case reliably under real conditions.**

Part 3 will examine how embeddings, vector search and Retrieval-Augmented Generation connect language models to controlled enterprise knowledge.

## Sources and further reading

The model landscape changes quickly. Recheck the following official documentation before making a specific selection:

- [OpenAI API — Models](https://developers.openai.com/api/docs/models)
- [Anthropic — Context windows](https://docs.anthropic.com/en/docs/build-with-claude/context-windows)
- [Anthropic — Tool use](https://docs.anthropic.com/en/docs/build-with-claude/tool-use/overview)
- [Google Gemini API — Models](https://ai.google.dev/gemini-api/docs/models)
- [Google Gemini API — Long context](https://ai.google.dev/gemini-api/docs/long-context)
- [Google Gemini API — Function calling](https://ai.google.dev/gemini-api/docs/function-calling)
- [Meta — Models and libraries](https://ai.meta.com/resources/models-and-libraries/)
- [Meta — Llama 4](https://ai.meta.com/blog/llama-4-multimodal-intelligence/)
- [Mistral AI — Models](https://docs.mistral.ai/models)
- [Mistral AI — Self-Deployment](https://docs.mistral.ai/models/deployment/local-deployment)
