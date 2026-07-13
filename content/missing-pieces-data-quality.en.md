---
title: "The Missing Pieces – Part 1: Data Quality"
description: "Why corrected downstream data does not automatically mean that the source cause has been resolved – and how governance can close the loop."
author: Thomas Lindackers
category: Data Governance
tags:
  - the-missing-pieces
  - data-quality
  - root-cause-analysis
  - data-governance
  - data-stewardship
  - dbt
  - data-pipelines
order: -1
hero: images/playbooks/mp-dq-hero.png
series: missing-pieces
seriesPart: 1
seriesTitle: The Missing Pieces
---

## Data can improve while the cause remains unchanged

Modern data platforms have become highly effective at replicating, testing, standardizing and progressively refining data for analytical use. Multi-layer architectures such as Bronze, Silver and Gold are designed for exactly this purpose: raw data is ingested, then validated and cleaned, and finally prepared for business consumption.

This is a sound architectural pattern. Separate layers can provide traceability, repeatability, isolation and clear responsibilities inside a data platform.

However, one important governance question often remains unanswered:

> **Are we improving the process that creates incorrect data – or are we only becoming better at compensating for its defects downstream?**

A transformation can correct an invalid value. It can fill missing information, remap invalid status codes or handle exceptions. The quality of the downstream dataset may improve significantly as a result.

Yet the business process, input form, interface or source system that created the defect may remain unchanged.

That distinction is the starting point of this playbook.

## Transformation is not the problem

Transformation is an essential part of modern data architecture. Many rules belong permanently in the data platform because they are not repairing defects; they are making data usable for a different purpose.

| Type of transformation | Example | Sustainable long term? |
| --- | --- | --- |
| Technical standardization | Align date formats, time zones or data types | Yes |
| Integration | Combine customer or product data from multiple systems | Yes |
| Harmonization | Map different codes to a shared business model | Yes |
| Business modeling | Calculate revenue, margin, churn or other metrics | Yes |
| Historization | Preserve time-dependent states and changes | Yes |
| Compensation for an avoidable source defect | Permanently synthesize missing mandatory values | Critical |
| Permanent workaround | Correct invalid status values again in every new layer | No, if the cause can be resolved |

The relevant question is therefore not:

> *Should we transform less?*

It is:

> **Is this transformation creating new business value – or permanently compensating for a problem that should be resolved where it originates?**

## When a temporary fix becomes a permanent dependency

A downstream fix can be the right decision. A report, billing process or regulatory workflow cannot always wait for an operational system to be changed. A transformation rule can contain the impact quickly and protect business continuity.

The problem begins when the temporary nature is lost.

A typical pattern looks like this:

Source error  
→ temporary mapping rule  
→ additional staging exception  
→ further harmonization in the conformed layer  
→ special logic in the business layer  
→ local correction in the BI report  
→ another workaround in Excel

The original defect may no longer be visible to users. At the same time, the source remains unchanged and continues to produce the same issue.

Every additional layer can create new dependencies:

- the same business assumption is implemented in several places,
- changes need to be traced and replicated multiple times,
- different teams may develop different corrections,
- the original cause becomes harder to see in the data flow,
- new data products may inherit only part of the existing repair logic.

A successful short-term safeguard can therefore become long-term technical and business debt.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/mp-dq-img1-en.png"
        alt="How permanent downstream corrections create additional complexity and dependencies"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        A defect can be corrected repeatedly downstream while its cause in the source system remains unchanged.
    </figcaption>
</figure>

## Detection is not the same as remediation

Data quality tools and tests matter because they make problems visible and measurable.

Microsoft Purview describes data quality as the assessment and oversight of data assets to support targeted improvement actions. dbt data tests validate defined assumptions about sources and models and return the records that violate an assertion. Source freshness additionally monitors whether source data has been updated within an expected time window.

These capabilities answer important questions:

- Are mandatory fields complete?
- Are keys unique?
- Are values within an accepted range?
- Are relationships between datasets valid?
- Is the data fresh enough?
- Where and since when does an issue occur?

They do not automatically answer:

- Why does the process create the defect?
- Who owns the business process causing it?
- Does an input validation, interface or application need to change?
- By when should the source cause be resolved?
- When can the temporary transformation rule be removed?

This is not a weakness of a specific product. It is the boundary between technical detection and organizational root-cause remediation.

## The missing feedback loop

Sustainable data quality requires a closed loop between the data platform and the process that creates the data.

A target operating model can consist of seven steps:

1. **Detect**  
   The issue becomes visible through profiling, rules, tests, monitoring or user feedback.

2. **Contain the impact**  
   A temporary transformation, quarantine rule or business exception protects downstream processes.

3. **Assign ownership**  
   The source system, business process and accountable owner are identified.

4. **Analyze the root cause**  
   The organization investigates why the issue occurs: missing validation, an unclear process rule, an integration defect, user error or incorrect system logic.

5. **Fix at the source**  
   The process, application, interface or validation is changed so that the defect is no longer created.

6. **Validate and monitor**  
   The improvement is verified and observed for a defined period.

7. **Remove the temporary fix**  
   Repair logic that is no longer required is deliberately removed from the pipeline.

The final step is particularly important. If an old correction remains after the source has been fixed, the correction itself can create new defects or misinterpret future data.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/mp-dq-img2-en.png"
        alt="Closed governance cycle from detection to root-cause remediation and removal of the temporary fix"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Data quality becomes more sustainable when detection, containment, ownership, source remediation and controlled removal are managed as one lifecycle.
    </figcaption>
</figure>

## What is the role of the Data Steward?

A Data Steward should not automatically be expected to resolve every technical or business cause personally. The steward is not necessarily the owner of the source system either.

A core responsibility can be to make the quality process manageable across system boundaries:

- document the quality issue and its business impact,
- identify affected data products and consumers,
- involve the Source Owner and Process Owner,
- make the temporary correction transparent,
- track status, priority and target date,
- coordinate validation of the source correction,
- make the decision to retire the workaround traceable.

This turns stewardship into more than maintaining a catalog entry. It becomes the link between technical observation and business accountability.

The actual change remains with the appropriate role:

- The **Process Owner** owns the business process.
- The **System Owner** owns the application or interface.
- The **Data Team** protects data products and implements necessary temporary controls.
- The **Data Steward** coordinates definition, transparency, escalation and follow-up.

## Temporary corrections need a lifecycle

A fix should not consist only of SQL, a mapping table or an additional business rule. It also needs governance metadata.

At minimum, the following information should be traceable:

| Information | Guiding question |
| --- | --- |
| Cause | Which process or system creates the defect? |
| Business impact | Which reports, metrics, processes or decisions are affected? |
| Accountability | Who owns the source and the root-cause remediation? |
| Temporary measure | Where and how is the impact currently contained? |
| Validity | From when does the fix apply and when will it be reviewed? |
| Target state | Which source change should prevent the issue permanently? |
| Validation | How will we know that the cause has actually been resolved? |
| Retirement | Which rule, mapping or exception can then be removed? |

An expiry date does not have to mean that a rule is deleted automatically. It may simply trigger a mandatory review.

The key point is: **A temporary fix should not silently become a permanent part of the architecture.**

## Not every issue can be fixed at the source immediately

A closed governance model still needs pragmatism.

There can be valid reasons why source remediation takes time:

- an external SaaS platform offers limited customization,
- a supplier interface is outside the organization's direct control,
- a legacy application will be replaced later,
- historical records remain incorrect even after the process is improved,
- regulatory or operational deadlines require immediate containment.

In such cases, a long-term or even permanent transformation may be the most economical solution.

What matters is that the decision is explicit:

> **Not every downstream fix is bad architecture. It becomes problematic when nobody knows why it exists, who owns it or whether it is still needed.**

## Practical questions for teams

Five questions can help whenever a new data-quality rule or repair transformation is introduced:

1. **Is this a business transformation or the repair of an avoidable defect?**
2. **Can the cause be influenced in the source system or business process?**
3. **Who owns the root-cause remediation – not only the downstream fix?**
4. **Does the temporary correction have a review date and a defined target state?**
5. **How will the team prove that the fix can later be removed or simplified?**

These questions do not replace a data-quality platform. They add accountability and lifecycle management to it.

## Conclusion

Modern data platforms can substantially improve data quality within a pipeline. Tests, profiling, monitoring and transformations are essential to that work.

But a high-quality Gold table does not automatically prove that the business process creating the data produces high-quality data.

The missing piece is often not another test or another transformation layer. It is the closed feedback loop:

> **Detect the issue. Contain the impact. Assign ownership. Fix the source. Validate the result. Remove the workaround.**

The decisive question is therefore:

> **Are we improving the systems that create data – or are we only becoming more efficient at compensating for their recurring problems?**

## Further resources

- [Databricks: What is the medallion lakehouse architecture?](https://docs.databricks.com/aws/en/lakehouse/medallion)
- [dbt Developer Hub: Add data tests to your DAG](https://docs.getdbt.com/docs/build/data-tests)
- [dbt Developer Hub: Source freshness](https://docs.getdbt.com/docs/deploy/source-freshness)
- [Microsoft Learn: Overview of data quality in Microsoft Purview Unified Catalog](https://learn.microsoft.com/en-us/purview/unified-catalog-data-quality)
- [Microsoft Learn: Data quality actions in Unified Catalog](https://learn.microsoft.com/en-us/purview/unified-catalog-data-quality-actions)
- [IBM: What is data quality?](https://www.ibm.com/think/topics/data-quality)
- [IBM: What is root cause analysis?](https://www.ibm.com/think/topics/root-cause-analysis)
