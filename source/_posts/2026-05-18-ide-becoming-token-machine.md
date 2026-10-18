---
title: 'The IDE Is Becoming a Token Machine'
layout: post
unlisted: false
tags:
author:
  name: Roman Pronskiy
  url: https://twitter.com/pronskiy
published_at: 18 May 2026
---

Or: what software development looks like after coding stops being the bottleneck.

---

For most of software history, programming looked something like this:

Tiny human.  
Big keyboard.  
Millions of tiny painful decisions.  

You sat in front of a glowing rectangle for eight hours and slowly converted caffeine into syntax.

![Tiny human, big keyboard](/assets/img/blog/2026-token-machine/tiny-human-big-keyboard.png)

And because of this, the IDE evolved around one core assumption:

> Humans manually produce code.

Everything made sense around that assumption.

Autocomplete helped humans type faster.  
Refactorings helped humans avoid mistakes.  
Navigation helped humans understand giant codebases.  
Syntax highlighting prevented humans from accidentally destroying production with one missing bracket.  

The editor was the center of gravity because the editor was where the work happened. And it worked pretty well.

```
Human brain → Keyboard → Code
```

## Then AI happened

And suddenly the entire equation broke, because tokens became cheap.

Historically, tokens were expensive. Every line of code required attention, memory, effort, frustration, context switching, debugging, emotional damage, 14 Stack Overflow tabs.

Generating 1000 lines of code used to cost actual human life force. Now it costs approximately 42 cents and one prompt.

So the way I see it:

> **Software development is becoming a token allocation problem.**

## New world

```
Human → infinite tokens → slop
```

The bottleneck moved. For decades, the bottleneck was token generation. Now it is:

* intent
* taste
* knowing what *not* to build

Code is no longer scarce.

## The economics changed

Every generation of developer tooling optimized around the scarcest resource. Early programming optimized around hardware. Later IDEs optimized around human productivity. AI-era tooling optimizes around tokens: the winning systems are the ones that help humans spend and direct more of them.

Most of the debate is about whether AI replaces developers. I find a different question more interesting: what happens to software development itself once intelligence becomes cheap?

## Maybe IDEs are not about correctness either

The comfortable answer is that the future IDE exists to keep AI under control. Like a giant safety harness that verifies things, checks the AI and guarantees correctness.

Maybe temporarily. But long term? Probably not.

Humans currently verify AI because humans are still more reliable in certain domains. That advantage may not survive. Eventually AI systems will likely write, test, review and debug code better than humans, and reason across giant systems better than we can.

That leaves an uncomfortable question: if IDEs are no longer about writing, navigating and debugging code, then what are they?

## The IDE becomes a token machine

The IDE becomes a machine for converting human intention into deployed software by burning through tokens.

The future IDE probably looks more like **TikTok**.

## Wait what?

Consumer products keep optimizing for immediacy, low friction, instant feedback and infinite interaction loops. AI development tools are drifting the same way, toward throughput and continuous generation.

Old IDEs were books. You sat quietly, concentrated deeply and traversed systems by hand. The loop was slow.

Modern AI tools feel closer to TikTok: fast, addictive, an infinite feed of generated possibilities. Once generating software is nearly instant, the interaction model changes with it.

Eat.  
Sleep.  
Claude.  
Repeat.  

The feedback loop compresses so much that building software starts to look like a continuous stream rather than careful drafting.

## Maybe we misunderstood programming

Maybe programming was never about writing code. Maybe code was a temporary compression format for human intent, a weird bridge language between `imagination ↔ logic ↔ machines`.

If so, we are now building a much better compiler for intent itself, and the IDE's job becomes turning human intention into machine execution, as fast and as often as possible.

Once intelligence becomes infrastructure, the most important developer tool is whichever one helps humans direct the most of it.

And economically, the incentives are obvious:

Cloud companies wanted more compute.  
Social networks wanted more engagement.  
Streaming companies wanted more watch time.  

## And LLM providers?

They want more token flow.

Not necessarily maliciously.

Economically.

IDE will follow.
