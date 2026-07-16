---
title: Trash In, Trash Out — When AI Treats Assumptions as Facts
description: Why unresolved source defects, hidden estimates and incomplete data become AI risk — and how governance prevents uncertainty from being presented as truth.
category: Data Governance
tags:
  - ai-governance
  - data-quality
  - source-data
  - training-data
  - hallucinations
  - machine-learning
  - rag
  - data-lineage
  - trusted-ai
  - data-governance
order: -1
author: Thomas Lindackers
hero: images/playbooks/trash-iinout-hero.png
---

## AI does not begin with intelligence — it begins with data

Organizations are connecting AI to almost every part of the data landscape:

- data warehouses and lakehouses
- documents and knowledge bases
- CRM, ERP and ticketing systems
- operational applications and APIs
- reports, metrics and semantic models
- emails, contracts and support histories

The underlying data is rarely a direct representation of reality.

It has usually already been:

- corrected
- standardized
- merged
- filtered
- enriched
- mapped
- aggregated
- imputed
- estimated

Most of these transformations are necessary. A data platform must harmonize formats, integrate systems and prepare information for a specific purpose.

The risk begins when the result looks complete and precise, while its **uncertainty has disappeared**.

A missing value is visibly unknown. An estimated value can be useful when it remains clearly marked as an estimate. But once an estimate is stored, copied and presented like an observed fact, every downstream consumer may treat an assumption as truth.

AI can amplify that problem.

> **AI can infer what may be missing. It cannot know whether that inference is true.**

## Enterprise data reaches AI in different ways

“AI trained on company data” is often used as a broad description, but several technically different patterns exist.

### Model training

A model is trained from data to recognize patterns and generate predictions. Data defects, missing coverage and hidden assumptions can influence the learned behaviour directly.

### Fine-tuning and feature engineering

An existing model is adapted using company-specific examples, or structured features are created for a machine-learning model. The quality and representativeness of these inputs shape later predictions.

### Retrieval-Augmented Generation

A language model retrieves documents, records or knowledge fragments at request time. The base model is not retrained, but incomplete, outdated or contradictory enterprise content can still produce misleading answers.

### Agents, analytics and decision support

AI systems query databases, call APIs, interpret KPIs or recommend actions. Here the model may be technically sound while the underlying business data, definitions or access paths remain weak.

The mechanism differs. The governance principle does not:

> **The quality, meaning and provenance of the input constrain the trustworthiness of the output.**

## The dangerous conversion: unknown → estimate → fact

Data pipelines often need to close gaps so that reports, processes or models can continue to operate.

A typical chain looks like this:

1. A mandatory value is missing in the source.
2. A transformation inserts a default, mapping or estimated value.
3. The corrected record is merged into a curated table.
4. The estimation flag is dropped or never created.
5. The value is reused in features, metrics, retrieval or model input.
6. AI presents an answer without revealing that part of its evidence was inferred.

The downstream dataset may now be more complete in a technical sense. It is not necessarily more accurate.

This distinction matters:

- **Observed** means the value was captured from an event or authoritative system.
- **Corrected** means a known defect was repaired under a defined rule.
- **Derived** means the value was calculated from other information.
- **Estimated or imputed** means the value was inferred because information was missing.
- **Synthetic** means the value was generated rather than observed.
- **Unknown** means the system does not have reliable evidence.

These states should not silently collapse into one generic “clean” value.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/trash-iinout-img1-en.png"
        alt="Source data with gaps, errors, duplicates, inconsistent definitions, outdated records, estimates and bias is prepared for AI and can produce valuable outputs or amplify hallucinations, misleading metrics and poor decisions"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Preparation can improve data, but unresolved gaps, hidden estimates and source defects do not disappear simply because the dataset is used by AI.
    </figcaption>
</figure>

## Data quality problems become AI problems in different ways

| Input problem | Possible AI effect | Governance question |
| --- | --- | --- |
| **Missing values** | The model substitutes patterns, defaults or nearby information | Is the gap visible, explained and acceptable for this use case? |
| **Hidden estimates** | Assumptions are treated like observed evidence | Can observed, derived and estimated values be distinguished? |
| **Inconsistent definitions** | Different meanings are blended into one answer or feature | Which definition is authoritative and where is it documented? |
| **Outdated records** | Recommendations reflect a state that is no longer current | What freshness is required and how is it monitored? |
| **Duplicates** | Certain events or groups are unintentionally overweighted | Which entity and deduplication rules apply? |
| **Selection gaps** | The model learns mainly from cases that are available, successful or easy to capture | Which populations, scenarios or failure cases are missing? |
| **Broken lineage** | Incorrect output cannot be traced to its origin | Which source, transformation and dataset version produced the evidence? |
| **Uncontrolled corrections** | Several teams repair the same issue differently | Who owns the rule and the root-cause remediation? |

Not every gap has to be filled. Sometimes the most accurate value is still **unknown**.

For AI, preserving that uncertainty may be safer than manufacturing completeness.

## Hallucination is an additional risk — not another name for poor data

Poor data quality and hallucination are related, but they are not the same problem.

Weak, contradictory or incomplete context can increase the chance of an unsupported or misleading response. Yet a generative model can also produce false or invented content when the available data is correct.

Possible causes include:

- the probabilistic generation process
- ambiguous instructions
- retrieval of the wrong context
- missing or truncated context
- conflicting sources
- weak grounding
- unsuitable model behaviour for the task
- missing evaluation and output controls

This means better data is necessary, but not sufficient.

A clean table does not turn a language model into a database. A documented knowledge base does not guarantee that every generated answer is grounded in it. Retrieval-Augmented Generation can reduce some risks, but it does not eliminate unsupported generation.

The danger is often not that an answer looks obviously wrong.

It is that it sounds coherent, fluent and confident.

> **Plausibility is not evidence. Confidence is not correctness.**

A governed AI application therefore needs controls for both:

1. the quality and provenance of its data, and
2. the reliability and permitted use of its generated output.

## Estimates are not trash — invisible estimates are the risk

Business data will never be perfectly complete.

Forecasts, allocations, imputations and approximations are legitimate components of many processes. A planning model without estimates would often be unusable. Historical records may remain incomplete even after the source process has been improved.

The goal is therefore not to ban estimated values.

The goal is to make their status governable.

At minimum, an estimated or imputed value should have metadata such as:

| Metadata | Purpose |
| --- | --- |
| **Value status** | Observed, corrected, derived, estimated, synthetic or unknown |
| **Method** | Rule, model, interpolation, default or manual assessment used |
| **Reason** | Why the original value was unavailable or invalid |
| **Confidence or uncertainty** | How strongly the estimate can be relied upon, where meaningful |
| **Source evidence** | Inputs used to create the value |
| **Owner** | Role accountable for the method and its permitted use |
| **Valid use** | Processes, reports or models for which the value is approved |
| **Review date** | When the assumption or method must be reassessed |
| **Lineage** | Where the value was created and where it is consumed |

This makes a crucial distinction possible:

> **A governed estimate is an explicit business assumption. An unlabelled estimate becomes a hidden fact.**

## Governance must cover the complete AI chain

AI Governance cannot begin only at the model.

The complete path needs control.

### 1. Source and process governance

The organization must know:

- which process creates the data
- which system is authoritative
- who owns the source
- which fields are mandatory
- which defects are recurring
- which issues can be fixed at the point of creation

A downstream correction can protect operations temporarily. It should not make the original cause invisible.

The related playbook [The Missing Pieces – Part 1: Data Quality](/playbooks/missing-pieces-data-quality) explains the required feedback loop from detection and containment to source remediation, validation and controlled removal of temporary workarounds.

### 2. Data-product governance

Before data is approved for AI use, teams need to know:

- intended purpose
- critical quality dimensions
- quality rules and thresholds
- freshness requirements
- known limitations
- missing coverage
- estimate and imputation logic
- certification status
- owner, steward and technical responsibility
- lineage and version

The broader operating model is described in [Data Quality Governance](/playbooks/data-quality-governance): quality must be linked to purpose, measurable rules, accountability, monitoring and continuous improvement.

### 3. Model and retrieval governance

The AI layer needs its own controls:

- model and configuration version
- training, fine-tuning or evaluation dataset version
- retrieval sources and ranking logic
- prompt and system-instruction version
- approved and prohibited use cases
- evaluation scenarios
- known limitations
- output thresholds and fallback behaviour
- change and release history

A model update, embedding change, new search index or prompt revision can alter behaviour even when the underlying business data remains unchanged.

### 4. Application and output governance

The consuming application should define:

- when source evidence must be shown
- when the system must decline to answer
- when human review is mandatory
- which decisions may never be automated
- how user feedback is captured
- how harmful or incorrect outputs are escalated
- which prompts, sources and results are logged
- how personal and confidential data is protected

The question is not merely whether the model can produce an answer.

It is whether the application can demonstrate **why this answer may be used for this purpose**.

## A practical minimum before connecting AI to enterprise data

### Define the decision before selecting the model

Clarify:

- Which decision or action will the output support?
- Who remains accountable?
- What is the impact of a false positive, false negative or invented answer?
- Is the task informational, advisory or autonomous?

### Inventory the evidence path

Document:

- source systems
- transformations
- business definitions
- estimates and corrections
- retrieval indexes
- model and prompt versions
- consuming applications

### Preserve uncertainty

Do not remove null indicators, estimate flags, exception states or quality warnings merely to make the input easier to process.

### Separate fact from assumption

Where possible, provide distinct fields or metadata for:

- actual value
- estimated value
- estimation method
- source timestamp
- quality status

### Test real failure scenarios

Evaluation should include more than ideal examples:

- incomplete records
- contradictory sources
- outdated documents
- unusual categories
- edge cases
- unavailable evidence
- adversarial or ambiguous prompts
- cases where the correct behaviour is not to answer

### Require traceable output

For fact-based enterprise answers, the application should be able to expose the relevant source, record, document or metric definition.

### Define abstention and escalation

A trustworthy system needs a controlled way to say:

- insufficient evidence
- source conflict
- data not current
- quality threshold missed
- human review required

### Monitor after release

Observe:

- grounded-answer rate
- unsupported claims
- retrieval failures
- quality-rule violations
- user corrections
- recurring error patterns
- changes after model, prompt or data updates

## What to avoid

### Treating a curated table as verified truth

A Gold or Business Layer can be technically clean while still containing estimates, inherited bias or unresolved source defects.

### Removing uncertainty during preparation

Dropping estimation flags, quality status or source references makes the data easier to consume but harder to trust.

### Assuming RAG eliminates hallucinations

Retrieval provides context. The model can still select the wrong context, ignore it, combine it incorrectly or generate beyond the available evidence.

### Letting AI repair source data without auditability

AI-assisted correction may be useful, but every change needs traceability, review rules and clear separation from observed values.

### Measuring only average performance

A high average can hide severe failures in rare, critical or poorly represented scenarios.

### Using the same controls for every use case

A drafting assistant and an autonomous payment decision do not require the same level of evidence, review and governance.

### Interpreting fluent language as proof

The quality of the wording says little about the quality of the underlying evidence.

## The real meaning of Trash In, Trash Out

“Trash In, Trash Out” should not be interpreted as a claim that every imperfect dataset is unusable.

It is a warning about scale.

AI can process more data, connect more signals and produce more outputs than a human team could handle manually. That creates value. It also means that unresolved defects, hidden assumptions and missing context can be replicated faster and presented more convincingly.

Governance changes the outcome by keeping the chain visible:

- source quality is measured
- gaps remain identifiable
- estimates remain estimates
- transformations remain traceable
- ownership is explicit
- models are evaluated for their intended purpose
- outputs expose evidence and limitations
- failures return to the source, data product, model or application owner

The objective is not perfect data or risk-free AI.

It is a system that understands the difference between **fact, derivation, estimate and uncertainty**.

> **Better models do not replace better sources.  
> Better preparation does not replace transparency.  
> Better answers do not replace evidence.**

## Related playbooks

- [The Missing Pieces – Part 1: Data Quality](/playbooks/missing-pieces-data-quality) — why downstream corrections need a feedback loop to the source
- [Data Quality Governance](/playbooks/data-quality-governance) — the operating model for purpose-specific quality, rules, ownership, monitoring and improvement

## Further resources

- [NIST AI Risk Management Framework](https://www.nist.gov/itl/ai-risk-management-framework)
- [NIST AI 600-1: Generative Artificial Intelligence Profile](https://www.nist.gov/publications/artificial-intelligence-risk-management-framework-generative-artificial-intelligence)
- [NIST AI RMF Playbook](https://airc.nist.gov/airmf-resources/playbook/)
- [Datasheets for Datasets](https://arxiv.org/abs/1803.09010)
- [Model Cards for Model Reporting](https://arxiv.org/abs/1810.03993)
