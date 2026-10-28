// VAO - comportements de l'en-tête (le HTML est généré par header.php).
(function () {
  var burger = document.getElementById('burger');
  var nav = document.getElementById('site-nav');
  if (burger && nav) {
    burger.addEventListener('click', function () {
      var open = nav.classList.toggle('open');
      burger.setAttribute('aria-expanded', open ? 'true' : 'false');
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
})();
