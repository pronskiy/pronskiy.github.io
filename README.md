# pronskiy.com website

Source for [pronskiy.com](https://pronskiy.com), built with the PHP static-site generator [Sculpin](https://sculpin.io). Styling is hand-written CSS (no build step, no framework).

## Creating new blog posts

Add a file under `source/_posts/` named `{YYYY}-{MM}-{DD}-{dash-separated-title}.md`. Markdown with YAML frontmatter:

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

## Developing

```bash
composer install
composer serve
```

This launches the Sculpin dev server at <http://localhost:8000>.

### CSS / design

All styles live in `source/assets/css/app.css`. It defines the design tokens (cream/ink palette, pink accent, dark-mode override), a small reset, and semantic component classes used by the templates (`.hero`, `.past-list`, `.video`, `.prose`, etc.). Edit and refresh — no compilation involved.

### Content types

- **Pages** — `source/_pages/*.md`, one-off pages with a static permalink.
- **Posts** — `source/_posts/*.md`, blog posts; also appear on `/blog` and in the Atom feed.

### Top-level pages

- `source/index.html` — landing page (hero, Projects, Writing, Videos, Talks).
- `source/blog.html` — blog index.

## Deployment

The [deployment workflow](.github/workflows/deploy-site.yml) auto-deploys to gh-pages on push to `main`.
