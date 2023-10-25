---
title: 'Turn Any PHP Script into a Native Single-File Binary'
layout: post
tags:
author:
  name: Roman Pronskiy
  url: https://twitter.com/pronskiy
published_at: 25 October 2023
---

For years, I've dreamed of being able to create PHP CLI scripts that can be easily distributed without requiring users to have PHP installed on their machines.

But a scripting language needs an interpreter, they said. Not anymore! PHP still can't be compiled for real, there is a trick you can use.


## TL;DR

* There is a project [crazywhalecc/static-php-cli](https://github.com/crazywhalecc/static-php-cli)  that allows you to compile PHP statically.
* Under the hood it uses [dixyes/phpmicro](https://github.com/dixyes/phpmicro) – it is a special static PHP binary which you can “glue” to any PHP script.
* What remains is to build the application in PHAR and attach a statically-compiled interpreter binary to it.

## Show me the code!

Here's a minimal PoC.

First, let’s download a pre-built _phpmicro_ binary from the _static-php-cli_ website. Since I’m on macOS, I’m using the following link:

```Bash
curl -O https://dl.static-php.dev/static-php-cli/common/php-8.2.10-micro-macos-aarch64.tar.gz

tar -xvf php-8.2.10-micro-macos-aarch64.tar.gz
```

Now let’s create a simple application in `index.php`:
```php
<?php

echo "hello world";
```

And we are ready to glue it with a PHP binary:
```Bash
cat ./micro.sfx index.php > indexbin && chmod 0755 ./indexbin
```

And voilà! Run it:

```Bash
./indexbin
```

<figure>
  <img src="/assets/img/blog/phin.gif" alt=""/>
  <figcaption></figcaption>
</figure>

## PoC is nice, but will this work for real CLI applications?

Yes, but there may be challenges.

For a real CLI app, you would most likely need to pack the app in a PHAR file. For this, you'd typically use [box-project/box](https://github.com/box-project/box).

I successfully created a basic "hello world" application with this method. You can find it in this repo:
[github.com/pronskiy/phin](https://github.com/pronskiy/phin)

However, when I attempted to integrate something more substantial like `symfony/console`, I encountered an error:

```
zend_mm_heap corrupted
Abort trap: 6
```

It looks fixable, but I haven't looked into exactly what causes it yet.

There will probably be other difficulties with more complex applications.

## What about platform support? Linux / macOS / Windows ?

In the PoC above, I just download the _phpmicro_ archive from here:
[https://dl.static-php.dev/static-php-cli/common/](https://dl.static-php.dev/static-php-cli/common/)

But for real-world applications, you would want to build it on a CI for all platforms at once.

Micro-php is compatible with the popular Linux, macOS, and Windows platforms. Yet, as of now, GitHub Actions doesn't offer free builds for the ARM architecture, like macOS on M1/M2. But alternative CI/CD providers are available, or you could opt for a paid solution.

On a side note, pooling resources in a single repository to fund micro-php builds might be a viable solution. ;-).

## Future prospects of this method?

Ideally, all of this can be organized as a ready-made GitHub Action, which you simply add to your repository and for each release of your CLI-app you will get binaries for all platforms. Users would be able to download and use your CLI binaries without additional dependencies.

[Static-php-cli](https://github.com/crazywhalecc/static-php-cli) is already used in [FrankenPHP](https://github.com/dunglas/frankenphp). Notably, Kevin Dunglas, the author of FrankenPHP, actively contributes to static-php-cli.
