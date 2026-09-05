// The full archive remains available without JavaScript. Filters are shareable
// through ?type= and browser Back restores the previous selection.
(function () {
  const controls = document.querySelector('[data-archive-tools]');
  if (!controls) return;
  const buttons = [...controls.querySelectorAll('[data-filter]')];
  const rows = [...document.querySelectorAll('.index > li[data-type]')];
  const count = controls.querySelector('[data-archive-count]');
  const allowed = buttons.map(button => button.dataset.filter);

  function apply(type) {
    if (!allowed.includes(type)) type = 'all';
    let previousYear = null;
    let visible = 0;
    for (const row of rows) {
      const matches = type === 'all' || row.dataset.type === type ||
        (type === 'writing' && ['post', 'article'].includes(row.dataset.type));
      row.hidden = !matches;
      if (!matches) continue;
      const startsYear = row.dataset.year !== previousYear;
      row.querySelector('.index-row').classList.toggle('is-year-start', startsYear);
      row.querySelector('.index-year').textContent = startsYear ? row.dataset.year : '';
      previousYear = row.dataset.year;
      visible++;
    }
    for (const button of buttons) {
      button.setAttribute('aria-pressed', String(button.dataset.filter === type));
    }
    count.textContent = visible === 0 ? 'No entries of this type yet.' :
      visible + (visible === 1 ? ' entry' : ' entries');
  }

  function fromUrl() {
    apply(new URL(window.location.href).searchParams.get('type') || 'all');
  }
  for (const button of buttons) {
    button.addEventListener('click', function () {
      const url = new URL(window.location.href);
      const type = button.dataset.filter;
      if (type === 'all') url.searchParams.delete('type');
      else url.searchParams.set('type', type);
      if (url.href !== window.location.href) history.pushState(null, '', url);
      apply(type);
    });
  }
  window.addEventListener('popstate', fromUrl);
  fromUrl();
  controls.hidden = false;
})();
