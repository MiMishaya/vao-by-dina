// VAO - comportements de l'en-tête (le HTML est généré par header.php).
(function () {
  var burger = document.getElementById('burger');
  var nav = document.getElementById('site-nav');
  if (burger && nav) {
    burger.addEventListener('click', function () {
      var open = nav.classList.toggle('open');
      burger.setAttribute('aria-expanded', open ? 'true' : 'false');
      document.body.classList.toggle('nav-open', open);
    });
  }

  // Sous-menus sur mobile (sur grand écran ils s'ouvrent au survol, en CSS).
  document.querySelectorAll('.nav-sub-toggle').forEach(function (toggle) {
    toggle.addEventListener('click', function () {
      var sub = toggle.nextElementSibling;
      var open = sub.classList.toggle('open');
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  });

  var search = document.getElementById('site-search');
  var searchToggle = document.getElementById('search-toggle');
  var searchInput = document.getElementById('search-input');
  if (search && searchToggle && searchInput) {
    searchToggle.addEventListener('click', function (e) {
      e.stopPropagation();
      // Champ déjà ouvert et rempli : le bouton lance la recherche.
      if (search.classList.contains('open') && searchInput.value.trim() !== '') {
        search.submit();
        return;
      }
      var open = search.classList.toggle('open');
      searchToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      if (open) {
        searchInput.focus();
      }
    });
    document.addEventListener('click', function (e) {
      if (!search.contains(e.target)) {
        search.classList.remove('open');
        searchToggle.setAttribute('aria-expanded', 'false');
      }
    });
  }

  // En-tête masqué quand on descend, réaffiché dès qu'on remonte ; bouton « retour en haut ».
  var header = document.querySelector('.site-header');
  var toTop = document.getElementById('to-top');
  var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var lastY = window.scrollY;
  var ticking = false;

  function onScroll() {
    var y = window.scrollY;
    if (header) {
      var atTop = y <= header.offsetHeight;
      header.classList.toggle('at-top', atTop);
      var keep = atTop || header.contains(document.activeElement) || (nav && nav.classList.contains('open'));
      if (keep || y < lastY - 4) header.classList.remove('is-hidden');
      else if (y > lastY + 4) header.classList.add('is-hidden');
    }
    if (toTop) toTop.classList.toggle('visible', y > 600);
    lastY = y;
    ticking = false;
  }
  window.addEventListener('scroll', function () {
    if (!ticking) {
      ticking = true;
      window.requestAnimationFrame(onScroll);
    }
  }, { passive: true });
  onScroll();

  if (toTop) {
    toTop.addEventListener('click', function () {
      window.scrollTo({ top: 0, behavior: reduced ? 'auto' : 'smooth' });
    });
  }

  // Écran de chargement « Nos produits » déjà vu : les liens vont directement à leur destination.
  var loading = window.VAO_HEADER && window.VAO_HEADER.loading;
  var seen = false;
  try { seen = window.localStorage.getItem('vao_loading_seen') === '1'; } catch (e) { /* stockage indisponible */ }
  if (loading && seen) {
    var loadingPath = new URL(loading, window.location.href).pathname;
    document.querySelectorAll('a[href*="to="]').forEach(function (link) {
      var url = new URL(link.href, window.location.href);
      var to = url.searchParams.get('to');
      if (url.pathname !== loadingPath || !to) return;
      var dest = new URL(to, window.location.href);
      if (dest.origin === window.location.origin) link.href = dest.href;
    });
  }
})();
