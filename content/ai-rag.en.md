---
title: Enterprise Data, Embeddings and RAG
description: How enterprise content becomes searchable through chunking, metadata and embeddings, and how Retrieval-Augmented Generation creates controlled context for language models.
category: Artificial Intelligence
tags:
  - ai
  - ai-foundations
  - enterprise-data
  - embeddings
  - vector-search
  - rag
  - retrieval
  - hybrid-search
  - metadata
  - access-control
order: -1
author: Thomas Lindackers
series: ai-foundations
seriesPart: 3
seriesTitle: AI Foundations
hero: images/playbooks/ai-rag-hero.png
---

## The model does not automatically know your enterprise

A language model can process language, structure content and apply general patterns learned during training. It does not automatically know an organization's current policies, contracts, tickets, data models or operational events.

Even a highly capable model initially contains only:

- learned model parameters
- general language and structural patterns
- knowledge represented by the scope and time of its training
- capabilities shaped by its training and architecture

Enterprise knowledge normally lives across many separate systems:

- document platforms
- databases and data warehouses
- data catalogs
- policies and procedures
- ticketing systems
- wikis and knowledge bases
- business applications
- APIs and operational systems

These sources change independently from the model. A new policy, an updated product catalog or a support ticket resolved yesterday does not automatically become part of the model parameters.

> **Enterprise knowledge must be retrieved under control at runtime and supplied as context for the current request.**

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-rag-img1-en.png"
        alt="Comparison between the general knowledge of a language model and current enterprise knowledge supplied through retrieval and runtime context"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        The model has general learned capabilities. Enterprise-specific information is searched through retrieval and added to the prompt context.
    </figcaption>
</figure>

This distinction is the basis of **Retrieval-Augmented Generation**, commonly called **RAG**.

## What RAG actually means

RAG combines two separate capabilities:

1. **Retrieval:** Relevant information is searched from a controlled knowledge base.
2. **Generation:** A language model uses the retrieved information as additional context for its response.

```flowchart
Question
Search
Relevant evidence
Model context
Generated answer
```

The language model does not answer directly from a vector database. Search returns document passages, records or other evidence. That content is passed to the model together with the question and instructions.

A simplified internal prompt might look like this:

```text
System instruction:
Answer only from the supplied sources.
Cite the sources used. State when evidence is insufficient.

User question:
Which rules apply to remote work?

Retrieved evidence:
[Source A, section 3]
[Source B, section 7]

Task:
Produce a precise answer with source references.
```

This normally does not permanently retrain the model. The retrieved information applies to the current request and consumes space in the context window.

## Embeddings translate content into a search space

Traditional search primarily compares terms, word forms and character sequences. This works well for clear names, identifiers and specialist terminology. It is less effective when the question and document express the same concept in different words.

Example:

```text
Question:
May employees work from home?

Document:
Eligible staff may perform their duties outside the company site
under the mobile-work agreement.
```

A pure keyword search needs matching terms or synonyms. Semantic search instead tries to identify the conceptual similarity between the two passages.

This is where **embeddings** are used. An embedding model converts text or other content into a numeric vector.

```text
Text passage
→ Embedding model
→ [0.21, -0.41, 0.73, ...]
```

These numbers are not a readable summary. They represent a position in a high-dimensional space. Content with similar meaning should be located closer together than unrelated content.

A typical flow is:

```flowchart
Document passage
Embedding
Vector
Index
Similarity search
```

Important qualifications:

- An embedding is not a fact check.
- A high similarity score does not guarantee domain relevance.
- Different embedding models create different vector spaces.
- Documents and queries must be processed with compatible models and configurations.
- Changing the embedding model can require re-indexing.

Embeddings make content semantically searchable. They do not replace metadata, permissions or lexical search.

## From documents to searchable knowledge

A production RAG solution does not begin with the user's question. It begins with preparing the source material.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-rag-img2-en.png"
        alt="Pipeline from documents and data through extraction, cleaning, chunking, metadata and embeddings to a vector index"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        RAG quality starts with content preparation. Chunks, metadata, embeddings and indexing determine which evidence can later be retrieved.
    </figcaption>
</figure>

### 1. Connect source systems

Possible sources include:

- PDF, Office and text documents
- wiki pages
- spreadsheets and tables
- database queries
- tickets and cases
- data catalogs
- policy archives
- websites
- APIs
- structured exports

Not every source should be handled in the same way. A contract has a different structure from a support ticket, a data table or a catalog entry.

### 2. Extract content

Relevant information must be read from the source format.

This can include:

- PDF and Office parsers
- platform and API connectors
- table and layout recognition
- OCR for scanned documents
- extraction of headings, sections and lists
- preservation of existing source metadata

The objective is not merely to collect as much text as possible. The original structure should be retained where it improves retrieval.

### 3. Clean and structure

Extracted content can contain technical artifacts:

- repeated headers and footers
- navigation elements
- incomplete tables
- broken line wrapping
- duplicated content
- encoding errors
- obsolete or invalid versions

Cleaning directly affects the quality of the chunks created later.

### 4. Split content into chunks

Large documents are generally divided into smaller units called **chunks**.

A good chunk should:

- represent a coherent business concept
- contain sufficient context
- avoid unnecessary size
- remain traceable to its source
- avoid splitting a central statement in the middle

Possible strategies include:

| Strategy | Suitable use |
| --- | --- |
| Fixed character or token length | Simple, but unaware of document structure |
| Paragraphs | Useful for well-structured prose |
| Headings and sections | Useful for policies, manuals and documentation |
| Table rows or record groups | Suitable for structured content |
| Semantic chunking | Groups content around conceptual transitions |
| Parent-child chunking | Searches smaller units but returns wider source context |

There is no universal chunk size. Chunks that are too small lose context. Chunks that are too large consume more tokens and may introduce irrelevant material into the model context.

### 5. Add metadata

Metadata is at least as important as embeddings in enterprise RAG.

Example fields:

- document ID
- title
- source system
- department
- owner
- language
- document type
- version
- classification
- validity period
- created and modified dates
- tenant
- country or region
- access groups
- original URL or file position

Metadata enables:

- filtering
- access control
- citations
- versioning
- lifecycle management
- domain scoping
- troubleshooting

A semantically similar but expired policy must not automatically outrank the current version.

### 6. Create embeddings

A vector representation is created for every searchable chunk. Depending on the architecture, the pipeline may also generate:

- dense embeddings for semantic retrieval
- sparse representations for token-based retrieval
- document summaries
- key terms
- questions that a chunk can answer
- additional ranking features

### 7. Index and store

A search index normally contains more than a vector.

```text
Chunk ID
Original text
Embedding
Document ID
Metadata
Permission information
Source position
Version information
```

Original documents may remain in a separate document store. The search index then references the source and contains only the units required for retrieval.

## The question is also represented

When a request arrives, the complete document collection is not sent to the language model.

The question is analyzed and is often converted into an embedding as well:

```text
User question
→ Query embedding
→ Similarity search
→ matching chunks
```

A production solution can also:

- normalize the question
- decompose it into subquestions
- add missing conversation context
- expand it with synonyms or business terminology
- classify intent and entities
- route it to different knowledge sources

These steps are commonly described as **query understanding**, **query rewriting** or **query planning**.

## From question to grounded answer

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-rag-img3-en.png"
        alt="RAG process from the user question through query embedding, vector search, metadata and access filters, chunk selection and prompt construction to an answer with sources"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Retrieval supplies evidence. The language model then processes the question, evidence and instructions as one model context.
    </figcaption>
</figure>

The process can be divided into seven steps.

### 1. Capture the user question

In addition to the question text, the application may need:

- user identity
- role and group membership
- language
- current application
- tenant
- conversation history
- desired response format

### 2. Create a query embedding

The embedding places the semantic meaning of the query into the same or a compatible vector space as the document chunks.

### 3. Search the index

Pure vector search is not always sufficient. Product numbers, abbreviations, names and new internal terms are often found more reliably through keyword search.

Enterprise solutions therefore frequently use **hybrid search**:

```text
Semantic vector search
+
keyword or full-text search
+
optional structured-data lookup
=
combined candidate set
```

### 4. Apply metadata and permissions

Filters may be applied before or during retrieval:

```text
classification = internal
department = finance
valid_from <= today
valid_to >= today
country = DE
access_group contains current_user_group
```

Authorization must not be postponed until after the answer is generated. Unauthorized content should ideally never enter the candidate set or model context.

### 5. Select and rank relevant chunks

Initial retrieval produces candidates. The results can then be:

- deduplicated
- weighted by recency
- diversified by source or topic
- reranked with a dedicated model
- checked for contradictions
- limited to a token budget

A vector similarity score alone is rarely enough for this decision.

### 6. Build the enriched prompt

The model context usually contains:

```text
System and security instructions
+
user question
+
retrieved evidence
+
source metadata
+
required output format
```

The context window remains finite. The application must decide which results to include in full, shorten or summarize.

### 7. Generate an answer with sources

The model generates an answer from the supplied context. Sources can be exposed as:

- document title
- direct URL
- section or page number
- chunk ID
- record reference
- timestamp
- source version

Citations make an answer more traceable. They do not automatically prove that every sentence is correctly derived from the source. Verification and evaluation remain necessary.

## RAG is more than a vector database

A demo can consist of one file, a vector index and a language model. A production enterprise solution requires additional layers.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-rag-img4-en.png"
        alt="Production RAG architecture with query understanding, identity and access control, hybrid retrieval, metadata filtering, ranking, context assembly, language model, citations and supporting operational capabilities"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Retrieval, security, metadata, ranking, context assembly and validation must operate as one controlled system.
    </figcaption>
</figure>

### Query understanding

The application must determine what type of information is required and which source is appropriate.

Example:

```text
“What was yesterday's revenue?”
```

This is unlikely to belong in static document retrieval. It requires an authorized query against an operational system or data warehouse.

By contrast:

```text
“Which definition does the company use for net revenue?”
```

is more likely to be answered from a data catalog, KPI documentation or governance policy.

### Identity and access control

A RAG application needs the user's identity and must enforce permissions at the relevant data level.

Possible controls include:

- single sign-on
- roles and groups
- tenant isolation
- document and row-level permissions
- data classification
- masking
- regional restrictions
- purpose limitation

The model must not be responsible for “interpreting” access rules. The application and retrieval layer must enforce them technically.

### Hybrid retrieval

Semantic and lexical search complement one another.

Semantic search is strong for:

- similar meaning
- natural-language questions
- multilingual phrasing
- conceptual proximity

Keyword search is strong for:

- identifiers
- product numbers
- proper names
- abbreviations
- new internal terminology
- exact wording

### Ranking and relevance

A production search pipeline may use several stages:

```flow linear vertical
Fast candidate retrieval
Merge multiple retrieval paths
Metadata and permission filtering
Reranking
Context selection
```

### Context assembly

The context layer must:

- avoid duplicates
- preserve source boundaries
- stay within token limits
- prefer valid versions
- protect system instructions
- handle conflicting sources
- retain citation metadata

### Monitoring and evaluation

The complete retrieval process must be observable, not only the model output:

- search query
- selected sources
- retrieval scores
- applied filters
- response latency
- token usage
- source coverage
- user feedback
- errors
- model, prompt and index version

### Document and index lifecycle

A RAG system needs controlled processes for:

- new content
- updates
- deletion
- archiving
- retention
- re-embedding
- re-indexing
- versioning
- rollback

When a document is deleted or a user's permission changes, that change must also become effective in the search index.

## RAG is not the correct access method for every source

Embedding-based retrieval is particularly useful for unstructured and semi-structured knowledge.

Current, transactional or precisely calculated data often requires a tool or API call instead.

| Question | Appropriate access |
| --- | --- |
| What does our travel policy say? | RAG over governed documents |
| Which KPI definition applies to Net Revenue? | RAG over catalog and governance documentation |
| What is today's revenue? | Authorized database or BI query |
| What is the status of order 4711? | API or operational system |
| Which incidents resemble this error? | Hybrid search over tickets |
| Cancel the order | Controlled tool action, not RAG alone |

A modern AI application can combine RAG and tools:

```flowchart
Understand question
Search knowledge
Retrieve live data
Validate results
Generate answer
Approve action
```

## Long context, RAG and fine-tuning are different tools

These approaches are frequently conflated.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-rag-img5-en.png"
        alt="Comparison of long context, Retrieval-Augmented Generation and fine-tuning by purpose, knowledge delivery, update path, strengths, limitations and enterprise use"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Long context, RAG and fine-tuning are complementary. They solve different problems and can be combined in one application.
    </figcaption>
</figure>

### Long context

With a long-context approach, selected content is sent directly with the request.

Suitable for:

- a few documents
- one-off analysis
- bounded tasks
- rapid prototypes
- direct document comparison

Advantages:

- simple architecture
- no retrieval index required
- complete selected content can be processed directly

Limitations:

- high token usage
- increased latency
- finite context capacity
- irrelevant content can make processing harder
- permissions and source management are still required

### RAG

RAG retrieves relevant content from a larger knowledge base for each request.

Suitable for:

- frequently updated enterprise documents
- many sources
- knowledge assistants
- support and policy knowledge
- answers with source references

Advantages:

- selective context
- knowledge can be updated independently from the model
- improved traceability through citations
- access control and metadata filters can be integrated

Limitations:

- additional retrieval infrastructure
- quality depends on sources, chunking and ranking
- indexes and permissions must be kept current
- more complex evaluation

### Fine-tuning

Fine-tuning modifies model parameters through additional training examples.

Suitable for:

- consistent formats
- repeated style
- specialized classification patterns
- domain-specific response behaviour
- frequently repeated task structures

Fine-tuning is not the preferred method for injecting facts that change every day. Current knowledge is easier to replace and control through RAG or tools.

## The approaches can be combined

One application can use all three methods:

```flow linear vertical
Fine-tuned model for consistent behaviour
RAG for current governed evidence
Long context for selected source documents
Tool calls for live data and actions
```

Example:

A contract assistant uses a model optimized for a required legal output format. RAG searches relevant policies and clauses. The full current contract is additionally supplied in the context. A tool retrieves authorized master data from the contract system.

The architecture is therefore not driven by the question “Which technology wins?” but by:

> **Which information does the task require, how current must it be, how is it authorized, and how should the model use it?**

## A target architecture for enterprise RAG

```flow linear vertical
Governed enterprise sources
Extraction and normalization
Chunking and metadata enrichment
Embeddings and search indexes
Identity-aware hybrid retrieval
Ranking and context assembly
Language model generation
Citations, validation and feedback
Monitoring, lifecycle and versioning
```

The model request itself is only a small part of this flow.

## What should be documented for a RAG use case

| Area | Required documentation |
| --- | --- |
| **Use case** | Purpose, user groups and permitted questions |
| **Sources** | Systems, owners, classification and update frequency |
| **Ingestion** | Extraction, cleaning and error handling |
| **Chunking** | Strategy, sizes, overlap and parent-child relationships |
| **Metadata** | Required fields, filters and source references |
| **Embeddings** | Provider, model, version and vector dimension |
| **Index** | Technology, partitioning and update procedure |
| **Retrieval** | Vector, keyword and structured search |
| **Ranking** | Scores, reranker, thresholds and top-k |
| **Access** | Identity, groups, document and data permissions |
| **Prompt** | Instructions, context format and citation rules |
| **Model** | Provider, model version and configuration |
| **Evaluation** | Retrieval relevance, groundedness and answer quality |
| **Lifecycle** | Re-embedding, deletion, retention and rollback |
| **Monitoring** | Latency, cost, errors, sources and user feedback |

## The central conclusion

> **RAG connects a language model to controlled enterprise knowledge. Embeddings and vector search help find semantically relevant content. Only chunking, metadata, access control, ranking, context assembly and lifecycle management turn it into a robust enterprise architecture.**

RAG does not repair poor source data. It does not remove the need for ownership and it must not bypass access control. It can, however, provide a controlled bridge between language models and current enterprise knowledge.

## The series at a glance

```flow linear vertical
AI Foundations — How AI Works [done]
Language Models and Providers [done]
Enterprise Data, Embeddings and RAG [active]
AI Agents and Automation
Known Problems and Failure Modes
Evaluation, Costs and Operations
AI Governance
```

Part 4 will examine **AI Agents and Automation**: how models select tools, plan workflows, perform actions and why an agent requires more controls than a normal chat application.

## Sources and further reading

- [OpenAI API — Vector Embeddings](https://developers.openai.com/api/docs/guides/embeddings)
- [OpenAI API — Retrieval](https://developers.openai.com/api/docs/guides/retrieval)
- [OpenAI API — Model Optimization and Fine-Tuning](https://developers.openai.com/api/docs/guides/model-optimization)
- [Microsoft Learn — Retrieval-Augmented Generation in Azure AI Search](https://learn.microsoft.com/en-us/azure/search/retrieval-augmented-generation-overview)
- [Microsoft Learn — RAG Evaluators](https://learn.microsoft.com/en-us/azure/foundry/concepts/evaluation-evaluators/rag-evaluators)
- [Google Cloud — Embeddings APIs for Search and RAG](https://docs.cloud.google.com/generative-ai-app-builder/docs/builder-apis)
- [Google Cloud — Hybrid Search](https://docs.cloud.google.com/gemini-enterprise-agent-platform/build/vector-search/about-hybrid-search)
- [Google Cloud — Vector Database Choices in RAG Engine](https://docs.cloud.google.com/gemini-enterprise-agent-platform/build/rag-engine/vector-db-choices)
- [Anthropic — Glossary: Context Window, Fine-Tuning and RAG](https://platform.claude.com/docs/en/about-claude/glossary)
