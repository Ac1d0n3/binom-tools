---
title: "When BI Formula Generators Help Governance"
description: "Use BI formula generators as controlled compilers of approved metric contracts, not as authorities for business meaning or certification."
author: Thomas Lindackers
tags:
  - BI Formula Generators
  - Metric Contract
  - DAX
  - Tableau
  - Qlik
publishedAt: 2026-07-29
category: Data Governance
order: -1
hero: images/playbooks/when-to-use-bi-formula-generators-hero.png
series: bi-governance-decisions
seriesTitle: BI Governance Decisions
seriesPart: 8
---

Formula generators can strengthen governance when they compile an approved metric contract into consistent implementations, documentation and tests. They create governance debt when they are asked to invent meaning.

## Problem

A vague request such as “Create Revenue KPI” does not specify population, grain, base measures, date behavior, filters, exclusions, currency, owner or expected results. A generator can still produce syntactically valid DAX, Tableau or Qlik code. That code may run and still be wrong.

Cross-tool translation adds another risk. DAX filter context, Tableau LOD and table-calculation behavior, and Qlik selection and Set Analysis semantics are not interchangeable syntax. Equal values in one sample do not prove semantic equivalence.

Bulk generation can also create dozens of unused measures, hidden dependencies and unversioned variants. When generated code changes without updating metric version and evidence, the implementation is no longer traceable.

## Decision

Use a formula generator only after the metric definition and placement are approved.

The minimum input contract contains:

- metric ID and approved version;
- business question and definition;
- formula components and approved base measures;
- base grain and allowed dimensions;
- source fields and model references;
- date, filter and selection semantics;
- null, currency, unit and sign behavior;
- permitted local variation;
- owner, reviewer and permitted use;
- expected scenarios, reference results and tolerance.

The generator may then create platform-specific formula candidates, inline explanations, dependency lists, metadata, test scenarios, migration snippets and review checklists.

Keep the decision boundary human. People approve business meaning, source authority, grain, placement, filters, exclusions, time rules, ownership, exceptions, certification and retirement. The generator applies approved patterns, flags missing inputs and stops when required evidence is absent.

Appropriate uses include boilerplate, controlled child metrics, syntax implementation, documentation, test generation and comparison of already-approved implementations. Inappropriate uses include ambiguous KPI requests, source selection, grain repair, authority conflicts, certification and uncontrolled bulk creation.

## Checklist

Before generation:

- Is the metric contract approved and versioned?
- Is the target platform and object type known?
- Is the target model, data source or app identified?
- Are base measures, fields and dependencies approved?
- Are grain, aggregation and dimensional scope explicit?
- Are time, filter, selection and calculation context explicit?
- Are unit, currency, sign and null rules defined?
- Are local variations and performance constraints stated?
- Are owner, technical reviewer and reference results available?

After generation:

- Was the output reviewed by a platform specialist?
- Does it execute in the target model?
- Do scenario tests cover totals, filters, time changes, nulls and boundary cases?
- Does reconciliation meet the approved tolerance?
- Were security and access implications reviewed?
- Was performance tested?
- Are warnings and unresolved assumptions closed?
- Are production object reference and release version recorded?
- Was certification impact assessed?
- Is the generated-from link preserved?

Syntactic validity is not governance evidence. A generated formula is an implementation candidate until validation and approval create a production release.

## Artifact

Create a combined **Formula Generation Request and Validation Record**.

The request records metric ID, version, target platform, object type, model or app, formula components, base measures, grain, aggregation, dimensions, time behavior, filter behavior, unit rules, local variation, performance constraints, owner, reviewer and expected reference results.

Generated outputs include formula, dependencies, documentation, metadata, tags, test cases, migration notes, warnings and unresolved assumptions.

Validation records syntax and execution result, scenario-test result, reconciliation and tolerance, security impact, performance result, reviewer approval, production object reference, release version, certification impact and review trigger.

The record must preserve a deterministic relationship between input contract, generator version, generated output and released production object. Regeneration with a changed contract requires a new metric or implementation version.

## Tools

Relevant tools include the Power BI DAX Generator, Tableau Calculation Generator, Qlik Set Analysis Generator, KPI Definition and Report Inventory.

A good generator should:

- require structured inputs instead of relying only on free text;
- reject incomplete contracts;
- expose platform-specific assumptions;
- generate code and evidence from the same input;
- preserve metric ID and version;
- support review rather than self-approval;
- avoid uncontrolled mass creation.

## Resources

Use platform documentation for current calculation semantics and object capabilities. Maintain approved internal patterns for DAX measures, Tableau calculations and Qlik expressions. Version generator templates, prompts, rules and test libraries.

Do not place source integration, durable history or grain repair inside BI expressions merely because a generator can produce the syntax.

## Playbooks

- [Define KPI](/playbooks/define-kpi)
- [Semantic Layer vs Measure in the Report](/stories/semantic-layer-vs-report-measure)
- [The Missing Pieces of Trusted Metrics](/playbooks/missing-pieces-trusted-metrics)

These playbooks create the approved input. The generator begins after those decisions.

## Next step

Take one approved metric contract and generate implementations for two BI engines. Record all assumptions, run the same scenario dataset, reconcile results and document why the implementations differ. Use that controlled example as the first generator template.
