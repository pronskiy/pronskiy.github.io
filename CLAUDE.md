# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Personal portfolio and blog website for Roman Pronskiy (pronskiy.com), built with **Sculpin** (PHP static site generator) and **Tailwind CSS**. The site features a blog, speaking engagements page, and video portfolio focused on PHP development and the PHP Foundation.

## Common Commands

```bash
# Install dependencies
composer install && npm install

# Development server (runs Sculpin + Tailwind watcher in parallel)
composer run serve

# Individual watchers
composer run sculpin-watch    # Sculpin dev server at localhost:8000
composer run npx-watch        # Tailwind CSS watcher

# Production build (used by CI)
npx tailwind -i assets/css/app.css -o source/assets/css/app.css --minify
./vendor/bin/sculpin generate --env=prod
```

## Architecture

### Content System

- **Posts** (`source/_posts/`): Blog posts named `YYYY-MM-DD-slug.md` with YAML frontmatter
- **Pages** (`source/_pages/`): Static pages like talks.md, music.md
- **Layouts** (`source/_layouts/`): Twig templates (default.html, post.html, page.html)

### Custom PHP Bundles (`app/src/`)

- **AtomFeedGeneratorBundle**: Generates per-author RSS feeds in `output_*/rss/`
- **SharingImageGeneratorBundle**: Creates 1200x630px OG images using GD library

### Configuration

- `app/config/sculpin_site.yml`: Site metadata (title, subtitle)
- `app/config/sculpin_site_prod.yml`: Production URL (pronskiy.com)
- `tailwind.config.js`: Custom colors (foundation, baseblack, fbg, hborder), fonts (JetBrainsMono)

### Build Output

- `output_dev/`: Development build
- `output_prod/`: Production build (deployed to gh-pages)

## Creating Blog Posts

Add file to `source/_posts/` with format `YYYY-MM-DD-slug.md`:

```markdown
---
title: Post Title
layout: post
tags:
    - update
author:
  name: Author Name
  url: Author URL
---
Content here
```

## Deployment

GitHub Actions workflow (`.github/workflows/deploy-site.yml`) auto-deploys to gh-pages on push to main.
