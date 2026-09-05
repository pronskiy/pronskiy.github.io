# CLAUDE.md

Guidance for Claude Code (claude.ai/code) when working in this repository.

## Project overview

Personal site for Roman Pronskiy (pronskiy.com), built with **Sculpin**, a PHP
static site generator.

**There is no build step and no CSS framework.** No npm, no `package.json`, no
Tailwind, no PostCSS. `source/assets/css/app.css` is hand-written CSS, edited
directly and served as-is. Tailwind was removed in April 2026 (commit `6568d1e`).

## Commands

```bash
composer install                              # only dependency install there is
composer run serve                            # dev server + watch, localhost:8000
./vendor/bin/sculpin generate --env=dev       # one-off dev build  -> output_dev/
./vendor/bin/sculpin generate --env=prod      # production build   -> output_prod/
```

CI (`.github/workflows/deploy-site.yml`) runs only the prod generate and pushes
`output_prod/` to `gh-pages`. Nothing else runs at deploy time.

Sculpin caches aggressively. If a change to a **layout or partial** does not
appear in the output, clear the caches and rebuild:

```bash
rm -rf .sculpin app/cache/* && ./vendor/bin/sculpin generate --env=dev
```

Always check the exit code — a Twig error still prints a progress bar and leaves
the previous output in place, so a broken build can look like a successful one.

## Design

The site is a **calm archive**: a compact profile header, then a hairline-ruled
index of everything Roman has made. It is deliberately **ink and paper — there
is no accent colour.** Hierarchy comes from grid position, type scale and weight.
The surface treatment borrows from luma.com: one rule weight, soft corners,
muted secondary text, a soft tint on hover.

Rules worth keeping:

- **No accent colour.** The only colour on the page comes from images: the YouTube
  thumbnails in the video strip and the project preview cards. That is why they
  read as intentional.
- **Three text sizes carry the interface.** Body is 16px with 1.5 line-height.
  Anything you act on (nav, index titles, project names) is `1rem`; muted notes
  beside it are `0.875rem`; running text (the intro, post prose) is `1.0625rem`.
  Nothing goes below `0.75rem`, and that size is only the duration pill on a
  thumbnail. Do not reintroduce 13px: it was most of the text on the page.
- **One typeface for the interface.** Mono (`--mono`) appears only inside code in
  posts (`.prose code`, `.prose pre`) and in the wrap-shifter easter egg. Every
  label is the sans in sentence case with no tracking, and there is no
  `text-transform: uppercase` anywhere in the stylesheet. Small tracked-out
  uppercase labels are the single strongest "generated" tell; do not reintroduce
  them.
- **One rule weight.** Every hairline is `1px solid var(--line)`; there are no
  solid-ink rules. Each section opens with one rule on the text column (2.5rem
  above, 2rem below) and that is the only line it draws: the index has no row
  separators. Rows keep rhythm from line-height, and year groups from air.
- **The index is two columns.** The year (first row of a year only) and the
  title, and the whole row is the link. Articles are the bulk of the index and
  carry no note; every other kind names itself in a muted note after the title,
  plus the venue for talks and podcasts. Video rows carry no running time; the
  duration pill lives on the thumbnail in the video strip only. Do not bring
  back a source or type column: the same three strings would fill it on nine
  rows in ten.
- **Corners** are `--radius` (8px: thumbnails, row hover, code blocks) or
  `--radius-lg` (12px: project cards). Inline code is pinned to 4px.
- **Hover is a soft tint** (`background: var(--ink-06)` on a rounded box) for
  index rows, project cards and post-nav links. Titles go to full ink, nothing
  inverts and nothing gains an underline.
- **Underlines only in running text** (the masthead intro and post prose). Nav,
  contacts, profile lines and footer links are muted and go to ink on hover.
- The homepage and the blog listing hang off one left edge: nav, masthead and
  index all align to the `--container` gutter. Article and page bodies are the
  exception: they read in a column capped at `--measure` and centred in the
  container, with the nav and footer still on the container grid.

## Content

- **Posts**: `source/_posts/YYYY-MM-DD-slug.md`, permalink `blog/:filename/`.
  `unlisted: true` hides a post from the index, `/blog`, the feed and the sitemap,
  and adds a `noindex` banner. Files prefixed `draft-` are ignored entirely.
- **Pages**: `source/_pages/` (talks.md, articles.md, music.md, larajp.html). `wide: true`
  in a page's front matter lets its index run the full container width; `talks.md`
  and `articles.md` use it.
- **Archive**: `app/config/archive.yml` — talks, podcasts and videos. Imported into
  `sculpin_site.yml` and exposed to Twig as `site.archive`. **Read the rules at the
  top of that file before editing it**; in particular a `date:` must be a full
  `YYYY-MM-DD` or the entry silently sinks to the bottom of the page.
- **Articles**: `app/config/articles.yml`: Roman's posts on the PhpStorm blog and The
  PHP Foundation blog, exposed as `site.articles`. It is a generated snapshot; its
  header records both sources, the selection regexes and how to regenerate it. The
  same date rule applies.
- `source/_partials/archive-list.html` merges `data.posts`, `site.archive` and
  `site.articles` into one reverse-chronological feed. The homepage, `/videos` and
  `/articles` all render through it; `blog.html` mirrors its row markup by hand
  so pagination keeps working, so change both when the row changes.
- **Projects**: the `projects:` list in the same `archive.yml`. Each has an `image`
  that is a local snapshot of the project's own `og:image`, resized to 960px wide
  and saved as JPEG in `source/assets/img/projects/`. They are snapshots on purpose
  (no runtime dependency on GitHub); re-fetch one when a project's card changes.

## Colour scheme

The CSS follows the OS preference unless `<html data-theme="light|dark">` forces
a side. An inline script in the head of `_layouts/default.html` reads the choice
from `localStorage` (key `theme`) before first paint, sets the attribute, and
flips the media of the two highlight.js `<link id="hljs-light|dark">` sheets and
the `theme-color` metas, which otherwise pick a side by media query. The footer
toggle (a Tabler sun or moon, chosen in CSS by the `--icon-sun` / `--icon-moon`
tokens) flips between light and dark through `window.theme.set()`; picking the
side the OS already prefers clears the override, so "follow the system" needs no
third state. The dark tokens are defined twice in `app.css` (OS preference and
forced) and must stay identical. The linktree layout has its own palette and no
toggle.

## Gotchas

- **`page.url` for the homepage is `/.`, not `/`.** Use the `is_home` variable set
  at the top of `_layouts/default.html`.
- **The dev server does not watch `app/config/*.yml`.** Restart after editing
  `archive.yml` or `sculpin_site.yml`.
- **Twig is 3.19**: `{% for x in y if cond %}` was removed — use `|filter(...)`.
  `|filter` also throws on `null`, so guard with `|default([])` where a value may
  be undefined on Sculpin's first pass (e.g. `page.pagination.items`).
- **`source/_pages/talks.md` and `articles.md` must stay `.md`.** `SharingImageGenerator`
  filters on the file extension, so renaming them to `.html` silently stops generating
  their OG images.
- **Two posts have hand-made OG images** in `source/assets/share/`. They are real
  artwork that intentionally overrides the generator — do not delete them.

## Custom PHP (`app/src/`)

- `Bundles/AtomFeedGeneratorBundle` — per-author feeds in `output_*/rss/`
- `Bundles/SharingImageGeneratorBundle` + `Seo/SharingImageGenerator` — 1200×630 OG
  images drawn with GD. **Its palette is duplicated from the CSS tokens; keep the
  two in sync.**
- `Blog/Tag.php` — the allowed post tags and their display labels
