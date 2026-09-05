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

All styles live in `source/assets/css/app.css`. It defines the design tokens (paper/ink palette, dark-mode override), a small reset, and semantic component classes used by the templates (`.masthead`, `.projects`, `.index`, `.prose`, etc.). Edit and refresh — no compilation involved.

### Content types

- **Pages** — `source/_pages/*.md`, one-off pages with a static permalink.
- **Posts** — `source/_posts/*.md`, blog posts; also appear on `/blog` and in the Atom feed.

### Top-level pages

- `source/index.html` — introduction, three selected projects, three essays, six recent videos, and eight recent activity entries.
- `source/blog.html` — Writing: personal posts plus recent articles published elsewhere.
- `source/_pages/projects.md` — all projects; `featured: true` in `app/config/archive.yml` selects homepage projects.
- `source/_pages/archive.md` — complete archive with type filters. Filters use `?type=writing|talk|podcast|video`; all entries remain available without JavaScript.
- `source/_pages/talks.md` — talks, podcasts, and videos, with the same filters.
- `source/_pages/articles.md` — the complete list of external articles.
- `source/_pages/music.md` — photo/video gallery, direct listening links, and expandable players.

Project previews are local SVG illustrations, original website previews (PhpStorm Light and DAWhub), and a product screenshot in `source/assets/img/projects/`, rendered by `source/_partials/project-list.html`. The redpen screenshot comes from its repository's `docs/screenshot.png`. Music video posters are frames extracted from the corresponding local videos.

## Deployment

The [deployment workflow](.github/workflows/deploy-site.yml) auto-deploys to gh-pages on push to `main`.
