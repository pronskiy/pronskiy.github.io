---
layout: page
permalink: archive
title: Archive
subtitle: "Writing, talks, podcasts, and videos, from 2017 to today."
nav_section: archive
wide: true
archive_filters: true
use:
    - posts
---

{{ include('archive-filters.html', { with_writing: true }) }}

{{ include('archive-list.html') }}
