---
title: AI Foundations — How Artificial Intelligence Actually Works
description: An accessible technical introduction to the difference between classical software and machine learning, the training process, inference, model parameters and the step-by-step construction of generative outputs.
category: Artificial Intelligence
tags:
  - ai
  - ai-foundations
  - artificial-intelligence
  - machine-learning
  - deep-learning
  - neural-networks
  - model-training
  - inference
  - generative-ai
  - model-parameters
order: -1
author: Thomas Lindackers
series: ai-foundations
seriesPart: 1
seriesTitle: AI Foundations
hero: images/playbooks/ai-basics-hero.png
---

## AI is not one single technology

When people talk about “AI” today, they often mean a chatbot or a large language model. Artificial intelligence is a much broader umbrella term.

It includes, among other things:

- rule-based systems
- statistical methods
- machine learning
- neural networks and deep learning
- computer vision
- language and audio models
- generative models
- systems that produce predictions, classifications or recommendations

Not every AI system generates text. Not every machine-learning model is a neural network. And not every neural network is a language model.

The shared idea behind modern machine-learning methods is:

> **The desired logic is not programmed entirely as fixed rules. A model learns relevant patterns from examples and a defined objective.**

This first playbook in the series focuses on that foundation. Language models, RAG, agents, known failure modes, evaluation and governance will follow in dedicated parts.

## From programmed rules to learned patterns

Classical software processes an input according to explicitly implemented rules.

A highly simplified example:

```text
If invoice amount > 10,000
and delivery country differs from customer country,
then flag the transaction for review.
```

A developer or subject-matter expert must define:

- which features matter
- how they are combined
- which thresholds apply
- which result is produced

Machine learning does not require every decision rule to be specified individually. Instead, a learning process receives examples and an objective. It derives mathematical relationships that can later be applied to new inputs.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-basics-img1-en.png"
        alt="Comparison between classical software with explicit rules and machine learning that creates a model from examples and expected results"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Classical software follows programmed logic. Machine learning creates a model by deriving patterns from examples and a defined objective.
    </figcaption>
</figure>

This does not mean that machine learning operates without explicit decisions. An ML system still requires people to decide:

- Which data should be used?
- What is the target?
- Which model architecture is appropriate?
- How is success measured?
- Which inputs and outputs are allowed?
- When is a result good enough?

The difference is **where the operational logic is represented**. In classical software it primarily lives in written program code. In machine learning, a substantial part of it is encoded in learned model parameters.

## What is a model?

A model can be viewed as a parameterized mathematical function.

It receives an input and calculates an output:

```text
Input → Model → Result
```

The input may take many forms:

- table rows
- measurements
- text
- images
- audio
- event sequences
- combinations of several data types

The output also depends on the intended task:

- category
- numeric value
- probability or score
- detected structure
- text
- image
- code
- audio

The structure of the model is determined by its architecture. Its learned behaviour is represented by its **parameters**, also called **weights**.

| Term | Simplified meaning |
| --- | --- |
| **Architecture** | The structure of the model and the calculations it can perform |
| **Parameters / weights** | Numeric values adjusted during training |
| **Input representation** | Numeric form into which text, images or tables are transformed |
| **Objective** | What the model should improve during training |
| **Loss** | Measure of the difference between generated and desired behaviour |
| **Optimizer** | Procedure that changes parameters based on the loss |
| **Trained model** | Architecture plus learned parameters |

A model is therefore neither just a collection of rules nor simply a database of stored answers. It is a computational structure whose behaviour is shaped by many numeric parameters.

## How a model is created

A typical training process begins with examples. The model processes an input and initially produces a prediction. That prediction is compared with a target. The difference is converted into a loss value. The parameters are then adjusted so that the loss becomes smaller for similar examples.

This process is repeated many times.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-basics-img2-en.png"
        alt="Continuous training loop consisting of training data, prediction, comparison with the expected result, error calculation, parameter adjustment and a trained model"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        The diagram shows the core idea of supervised learning: predict, compare, calculate the error, adjust parameters and repeat the process.
    </figcaption>
</figure>

The illustrated process represents **supervised learning**, where examples have known or deliberately defined outcomes.

Examples include:

- email → category
- transaction → fraudulent or not fraudulent
- machine state → expected remaining lifetime
- image → object class

Many modern generative models are trained additionally or primarily through **self-supervised learning**. Learning targets are derived from the data itself. A language model can, for example, learn to predict missing or subsequent text elements without every sentence being labelled manually.

Other learning paradigms include:

- **Unsupervised learning:** identifying structures, clusters or representations without an explicit target class
- **Reinforcement learning:** optimizing behaviour through rewards and feedback
- **Fine-tuning:** continuing the training of an existing model for a narrower purpose

The methods differ, but the core principle remains: an optimization process changes parameters so that the model performs its defined objective more effectively.

## What “learning” means technically

A model does not learn through conscious insight in the human sense. In technical terms, learning means:

1. An input is converted into a numeric representation.
2. The model calculates an output.
3. An objective function evaluates the output.
4. An optimization procedure determines how parameters should change.
5. The updated parameters influence the next iteration.

For neural networks, the influence of parameters is commonly calculated using methods such as **backpropagation** and a form of **gradient-based optimization**.

The term “neural network” is biologically inspired, but the technical system is not a digital human brain. An artificial neuron is essentially a mathematical computation unit. Many of these units are arranged into layers and larger architectures.

Deep learning means that a model contains many processing layers and can learn increasingly complex representations.

In an image model, early layers may capture local contrast and edges, while later layers represent more complex shapes or object features. In language models, numeric representations emerge for tokens, relationships and context patterns.

## Training and inference are different phases

A common misconception is to treat model training and model usage as the same process.

During **training**, the model changes.

During **inference**, a trained model is applied to a new input. Under normal production usage, its parameters remain unchanged.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-basics-img3-en.png"
        alt="Comparison between training with historical examples and parameter adjustment and inference with a new input and calculated result"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Training builds or modifies the model. Inference uses the trained model to calculate a result for a new case.
    </figcaption>
</figure>

| Training | Inference |
| --- | --- |
| processes many training examples | processes new inputs |
| calculates loss or learning signals | calculates a result |
| changes model parameters | uses existing parameters |
| often requires substantial compute | is usually optimized for response time |
| may happen once, periodically or continuously | happens during every production use |
| produces a new model version | produces a prediction or generated output |

This distinction matters architecturally.

An organization may train a model from scratch, fine-tune an existing model or use an externally hosted model only for inference. The visible application may look similar in all three cases, while data flows, infrastructure, cost and responsibilities are very different.

```flowchart
Training data
Learning process
Trained model
New input
Inference
Result
```

## A model can support different types of AI tasks

AI is not limited to generative systems. Models are developed or adapted for different task classes.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-basics-img4-en.png"
        alt="A model processes different input forms and supports classification, prediction, detection and generation with practical examples"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        The task is determined by the architecture, training process, input representation and intended use of the output. In practice, general models may support several tasks while other models are deliberately specialized.
    </figcaption>
</figure>

### Classification

The model assigns an input to a predefined category.

Examples:

- support request → invoice, relocation or outage
- document → contract type
- product image → product class
- customer case → priority level

### Prediction or regression

The model calculates a numeric value or expected future state.

Examples:

- expected sales volume
- delivery time
- failure probability
- customer churn score
- expected energy consumption

### Detection

The model identifies structures, events or unusual patterns.

Examples:

- unusual transaction
- defect in a product image
- anomalous sensor measurements
- named entities in a document

### Generation

The model creates new content.

Examples:

- text
- images
- program code
- audio
- summaries
- drafts and variations

The diagram is intentionally simplified. Not every individual trained model automatically performs all four task types. Often, the same underlying architecture is trained for different objectives, a foundation model is adapted to multiple tasks, or a general multimodal model supports several input and output forms.

## Probabilities rather than hard-coded certainty

Many models do not simply produce one final answer internally. They calculate scores or probability distributions.

A classification model might produce:

```text
Invoice       0.74
Contract      0.17
Other         0.09
```

The application can then:

- select the highest category
- require a minimum confidence threshold
- display several suggestions
- route the case for human review
- use the scores in another calculation

In generative models, the probability distribution influences which output element is selected next. Depending on the configuration, the result can focus more strongly on the most likely continuation or allow greater variation.

This is one of the core differences from a fixed decision rule. The result depends on learned relationships, the current input, the context and the output configuration.

## How generative AI builds an output

Generative AI normally does not retrieve a completed answer as one stored block.

For a language model, the process starts with an input: the prompt. The model calculates which next token is suitable in the current context. The selected token becomes part of that context. The model then calculates another token. This continues until the output is completed.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-basics-img5-en.png"
        alt="Step-by-step construction of a generative output from the prompt through initial output elements and growing context to the completed result"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Generative outputs are constructed iteratively. Text and code are commonly extended token by token; other model families use different technical units and generation procedures.
    </figcaption>
</figure>

A **token** is not necessarily a complete word. It may be a word, word fragment, punctuation mark or another text element.

A simplified sequence:

```text
Prompt:
“Write a short reply to a customer.”

Context 1:
“Thank”

Context 2:
“Thank you”

Context 3:
“Thank you for your”

Context 4:
“Thank you for your message.”
```

Each new element changes the context used for the next calculation.

Other media follow the same broad idea of iterative generation, but not necessarily the same technical mechanism:

- **Text and code:** commonly token by token
- **Images:** for example iterative denoising in diffusion models or generation of visual tokens
- **Audio:** audio tokens, frames, segments or samples depending on the model
- **Video:** temporal and visual representations across multiple generation steps

Generative AI is therefore not a simple search function. It constructs a new result from learned model parameters and the current context.

## What a trained model contains — and what it does not

After training, a model primarily consists of:

- a defined architecture
- numeric parameters
- input and output configuration
- often a preprocessing component such as a tokenizer or feature transformation
- a specific model version

The training data is not automatically stored inside the model as a directly queryable dataset. Instead, the model compresses statistical structures into its parameters.

That statement should not be misread as “a model can never retain specific content.” Large models can partially memorize individual training fragments or rare patterns. This later matters for privacy and governance, but it is not the central topic of this foundations article.

The important mental model is:

> **During normal inference, the model does not simply look up the matching training row. It calculates a new output using the parameters adjusted during training.**

## The same foundation behind very different systems

A recommendation engine, an image classifier, a forecast and a language model look very different from a user perspective.

Technically, they share a common foundation:

1. A real-world task is translated into inputs and outputs.
2. Data is transformed into numeric representations.
3. A model architecture processes those representations.
4. A training objective defines which behaviour should improve.
5. An optimization process adjusts parameters.
6. The trained model is applied to new inputs.
7. An application interprets or uses the result.

The quality of an AI system therefore depends on more than model size. It also depends on:

- whether the task is suitable for machine learning
- how inputs are represented
- the training objective
- data coverage
- model architecture
- technical integration
- interpretation and use of the output

The remaining parts of the series will examine these layers in more detail.

