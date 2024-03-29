---
title: 'Generics via Attributes in PHP &mdash; Can We Have Them?'
layout: post
tags:
author:
  name: Roman Pronskiy
  url: https://twitter.com/pronskiy
published_at: 31 October 2022
---

**tl;dr:** How about using generics in PHP attributes?
```php
#[<T>]
class Stack
{
    public function push(#[<T>] mixed $item): void
    {
    }

    public function pop(): #[<T>] mixed
    {
    }
}
```

Native generics. Will they be in PHP or not? Does PHP need them at all? We'll leave this speculation for the next time, but today let's discuss what generics might look like in PHP attributes.

<figure>
  <img src="https://pbs.twimg.com/media/Ffk5-9LWAAcZhwI?format=jpg" alt="Meme: Why can't we have generics in PHP?" width="300"/>
  <figcaption><a href="https://twitter.com/brendt_gd/status/1583360505766285314">https://twitter.com/brendt_gd/status/1583360505766285314</a></figcaption>
</figure>

## Status-PHPDoc-quo

Nikita Popov did a comprehensive research on generics in PHP and shared detailed results [here](https://github.com/PHPGenerics/php-generics-rfc/issues/45). Nikita also wrote a summary on Reddit during an [AMA with the PhpStorm team](https://www.reddit.com/r/PHP/comments/j65968/ama_with_the_phpstorm_team_from_jetbrains_on/):

<iframe id="reddit-embed" src="https://www.redditmedia.com/r/PHP/comments/j65968/ama_with_the_phpstorm_team_from_jetbrains_on/g83skiz/?depth=1&amp;showmore=false&amp;embed=true&amp;showmedia=false" sandbox="allow-scripts allow-same-origin allow-popups" style="border: none;" height="200" width="640" scrolling="no"></iframe>

The conclusion that Nikita came to is that there are only three ways to implement generics, and none of them will work in PHP. Or rather, it is possible to implement them, but each of them has significant drawbacks.

Nevertheless, the implementation of erased quasi-generics already exists today. I'm talking about PHPDoc annotations.

Although there is no official standard, the popular static analyzers [PHPStan](https://phpstan.org/) and [Psalm](https://psalm.dev/), as well as [PhpStorm](https://blog.jetbrains.com/phpstorm/2021/12/phpstorm-2021-3-release/#more_for_generics), support a syntax that can generally be called well-established.

<figure>
  <img src="https://blog.jetbrains.com/wp-content/uploads/2021/12/generics_contructor.gif" alt=""/>
  <figcaption></figcaption>
</figure>

What prevents people from using generics via annotations? I just posed this question on Twitter. They are, after all, essentially erased generics, pretty much like in Python, for instance.

There were some constructive and substantive concerns raised in the replies:

<blockquote class="twitter-tweet"><p lang="en" dir="ltr">Nothing stops me from using phpdoc generics, &amp; I use them, but without native language support, I can?t enforce them on downstream users of my libraries, so I still have to write a lot of validation code to check types.<br><br>I think this would also be a problem with erased generics.</p>&mdash; Ben Ramsey @ramsey@phpc.social (@ramsey) <a href="https://twitter.com/ramsey/status/1582461944401133568?ref_src=twsrc%5Etfw">October 18, 2022</a></blockquote>

There were also comments about the usability of such generics. But what annoys me, and what the Twitter mob didn't note, is that in modern PHP code you have to use both attributes and PHPDoc annotations simultaneously.

## Generics, why no attributes?

The PHPDoc annotations are unstructured strings. They were [meant to be](https://wiki.php.net/rfc/attributes_v2) replaced by attributes, which are part of the language, and set a strict format for metadata in PHP.

However, in the case of generics, the attributes look terrible:
```php
/** @template T of object */
class Queue
{
    /** @var array<int,T> */
    private array $queue = [];

    /** @param T $item */
    public function add($item): void {}

    /** @return T */
    public function next() {}
}

// The same with current attributes => 

#[Template("T", "object")]
class Queue
{
    #[Type("array<int,T>")] 
    private array $queue = [];

    public function add(#[Type("T")] $item): void {}

    #[Type("T")]
    public function next() {}
}
```

In addition, the attributes only work on declarations but not on call-site. Consequently, you cannot do this:

```php
/** @var Queue<Person> $personQueue */
$personQueue = new Queue();

// The same with current attributes =>

#[Type("Queue<Person>")]
$personQueue = new Queue();
```


## Generics in attributes syntax RFC

What if generics looked prettier but remained in attributes?

```php
#[<T>]
class Stack
{
    public function push(#[<T>] mixed $item): void
    {
    }

    public function pop(): #[<T>] mixed
    {
    }
}
```

### Pros:
- PHP code remains untouched and BC breaks are not added
- The code becomes cleaner and prettier (subjectively)
- Static analyzers work as with PHPDoc
- Information about generics is available in the language itself (!)

### Cons:
- Type information still in two places
- Hacky syntax (?)
- What else?

## Why not all the way native generics with `<T>`? 
Adding native erased generics we get to the inconsistency that some types are not checked at runtime. The attributes never gave a promise to change runtime behavior.

## But what about runtime checks?

Since generics information is contained in attributes, it is available at runtime! This means that type checks _can_ be implemented in userland in PHP. Such checks will probably be slower than the native ones, but the main advantage is that they can be entirely optional!

That means you can have early runtime checks in your local and test environments. For production you can disable such runtime checks and get performance boost there.


## 🚧 Static Analysis PoC

<strike>Here is a fork of Nikita's PHP parser that demonstrates this concept. And here is the PHPStan fork with the ability to use this syntax.</strike>

## What do you think?
- How do you like this syntax? 
- What problems do you see with this? 
- What are other benefits and drawbacks?

---
<br>

Many thanks to Dave Liddament whose talk at the [International PHP Conference](https://twitter.com/phpconference) in Munich inspired this idea. It literally came up during our discussion after Dave's talk:

<blockquote class="twitter-tweet"><p lang="en" dir="ltr">It seems PHP generics is a hot topic at the moment. <a href="https://twitter.com/pronskiy?ref_src=twsrc%5Etfw">@pronskiy</a> following on from our conversation at IPC, has the syntax #&lt;&gt; been suggested? <br><br>Would this work for adding type information for static analysis?<br><br>See more in gist: <a href="https://t.co/IOzSGgt1Xo">https://t.co/IOzSGgt1Xo</a><br><br>1/n <a href="https://t.co/BHkqP3cr07">https://t.co/BHkqP3cr07</a> <a href="https://t.co/g2eIzm1ndT">pic.twitter.com/g2eIzm1ndT</a></p>&mdash; Dave Liddament (@DaveLiddament) <a href="https://twitter.com/DaveLiddament/status/1586726336961339392?ref_src=twsrc%5Etfw">October 30, 2022</a></blockquote> 

<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
