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

The site is a **dense archive**: a masthead, then a hairline-ruled index of
everything Roman has made. It is deliberately **ink and paper — there is no
accent colour.** Hierarchy comes from grid position, type scale and weight.

Rules worth keeping:

- **No accent colour.** The only colour on the page comes from images: the YouTube
  thumbnails in the contact-sheet strip and the project preview cards. That is why
  they read as intentional.
- **Mono (`--mono`) appears in exactly four places**: the year column, block
  counts, thumbnail durations, and the phonetic tooltip. Every label is the sans
  in sentence case with no tracking, and there is no `text-transform: uppercase`
  anywhere in the stylesheet. Small tracked-out mono uppercase labels are the
  single strongest "generated" tell; do not reintroduce them.
- **Hover inverts a row** (`background: var(--ink); color: var(--paper)`). That is
  the interaction vocabulary in place of colour.
- The homepage and the blog listing hang off one left edge: nav, masthead and
  index all align to the `--container` gutter. Article and page bodies are the
  exception: they read in a column capped at `--measure` and centred in the
  container, with the nav and footer still on the container grid.

## Content

- **Posts**: `source/_posts/YYYY-MM-DD-slug.md`, permalink `blog/:filename/`.
  `unlisted: true` hides a post from the index, `/blog`, the feed and the sitemap,
  and adds a `noindex` banner. Files prefixed `draft-` are ignored entirely.
- **Pages**: `source/_pages/` (talks.md, music.md, larajp.html).
- **Archive**: `app/config/archive.yml` — talks, podcasts and videos. Imported into
  `sculpin_site.yml` and exposed to Twig as `site.archive`. **Read the rules at the
  top of that file before editing it**; in particular a `date:` must be a full
  `YYYY-MM-DD` or the entry silently sinks to the bottom of the page.
- `source/_partials/archive-list.html` merges `data.posts` with `site.archive` into
  one reverse-chronological feed. Both the homepage and `/videos` render through it.
- **Projects**: the `projects:` list in the same `archive.yml`. Each has an `image`
  that is a local snapshot of the project's own `og:image`, resized to 960px wide
  and saved as JPEG in `source/assets/img/projects/`. They are snapshots on purpose
  (no runtime dependency on GitHub); re-fetch one when a project's card changes.

## Gotchas

- **`page.url` for the homepage is `/.`, not `/`.** Use the `is_home` variable set
  at the top of `_layouts/default.html`.
- **The dev server does not watch `app/config/*.yml`.** Restart after editing
  `archive.yml` or `sculpin_site.yml`.
- **Twig is 3.19**: `{% for x in y if cond %}` was removed — use `|filter(...)`.
  `|filter` also throws on `null`, so guard with `|default([])` where a value may
  be undefined on Sculpin's first pass (e.g. `page.pagination.items`).
- **`source/_pages/talks.md` must stay `.md`.** `SharingImageGenerator` filters on
  the file extension, so renaming it to `.html` silently stops generating its OG
  image.
- **Two posts have hand-made OG images** in `source/assets/share/`. They are real
  artwork that intentionally overrides the generator — do not delete them.

## Custom PHP (`app/src/`)

- `Bundles/AtomFeedGeneratorBundle` — per-author feeds in `output_*/rss/`
- `Bundles/SharingImageGeneratorBundle` + `Seo/SharingImageGenerator` — 1200×630 OG
  images drawn with GD. **Its palette is duplicated from the CSS tokens; keep the
  two in sync.**
- `Blog/Tag.php` — the allowed post tags and their display labels
