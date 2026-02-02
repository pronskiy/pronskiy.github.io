# pronskiy.com website

This is the source code for the website of [pronskiy.com](https://pronskiy.com).
It is built using the PHP static-site generator [Sculpin](https://sculpin.io), and uses [Tailwind CSS](https://tailwindcss.com) for design and layout.

## Creating new blog posts

To create a blog post, add a new file under `source/_posts/` in the format `{4-digit Year}-{2-digit Month}-{2-digit Day}-{dash-separated title}.md`.
All posts are written using Markdown with frontmatter YAML, and should have the following general format:

```markdown
---
title: Title for the post
layout: post
tags:
    - update
author:
  name: Your name
  url: A URL with information on you
---
Markdown content starts here
```

## Developing/maintaining the site

```bash
$ composer install
$ npm install
```

```bash
$ composer run npx-watch & composer run sculpin-watch
```

This will launch the server at https://localhost:8000


### CSS/Design changes

The primary CSS file is kept in `assets/css/app.css`, and contains a number of overrides for common HTML tags; this is done so that rendered Markdown can remain styled.
All other styles are derived from CSS classes; see the [Tailwind CSS documentation](https://tailwindcss.com/docs/installation) for details on what classes you can compose to achieve different design goals.

### Content Types

This site has two Sculpin content types:

- pages (under `source/_pages/`)
- posts (under `source/_posts/`)

Pages are one-off pages with a static permalink.

Posts are blog posts, and will show up on the `/blog` page as well as in the site feed.

### Top-level pages

The site defines two top-level pages:

- `index.html`: The site landing page.
- `blog.html`: The blog landing page.

## Deployment

The [deployment workflow](.github/workflows/deploy-site.yml) auto-deploys to gh-pages on a push to the main branch.
