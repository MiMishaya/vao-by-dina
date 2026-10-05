// VAO - comportements de l'en-tête (le HTML est généré par header.php).
(function () {
  function wireDropdown(toggleId, panelId) {
    var toggle = document.getElementById(toggleId);
    var panel = document.getElementById(panelId);
    if (!toggle || !panel) return;

    toggle.addEventListener('click', function (e) {
      e.stopPropagation();
      var open = panel.classList.toggle('open');
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      document.querySelectorAll('.nav-dropdown-panel.open').forEach(function (other) {
        if (other !== panel) other.classList.remove('open');
      });
    });

    document.addEventListener('click', function (e) {
      if (!panel.contains(e.target) && e.target !== toggle) {
        panel.classList.remove('open');
        toggle.setAttribute('aria-expanded', 'false');
      }
    });
  }

  function wireMobileSubmenu(toggleId, submenuId) {
    var toggle = document.getElementById(toggleId);
    var submenu = document.getElementById(submenuId);
    if (!toggle || !submenu) return;

    toggle.addEventListener('click', function (e) {
      e.stopPropagation();
      var open = submenu.classList.toggle('open');
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  }

  function wireInlineSearch(wrapId, toggleId, inputId) {
    var wrap = document.getElementById(wrapId);
    var toggle = document.getElementById(toggleId);
    var input = document.getElementById(inputId);
    if (!wrap || !toggle || !input) return;

    toggle.addEventListener('click', function (e) {
      e.stopPropagation();
      // Champ déjà ouvert et rempli : le bouton lance la recherche.
      if (wrap.classList.contains('open') && input.value.trim() !== '') {
        wrap.submit();
        return;
      }
      var open = wrap.classList.toggle('open');
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      if (open) {
        setTimeout(function () { input.focus(); }, 0);
      } else {
        input.blur();
      }
    });

    document.addEventListener('click', function (e) {
      if (!wrap.contains(e.target)) {
        wrap.classList.remove('open');
        toggle.setAttribute('aria-expanded', 'false');
      }
    });
  }

  wireDropdown('gamme-toggle', 'gamme-panel');
  wireDropdown('formulaire-toggle', 'formulaire-panel');
  wireDropdown('site-menu-toggle', 'site-menu-panel');
  wireDropdown('mobile-menu-toggle', 'mobile-menu-panel');
  wireInlineSearch('site-search', 'search-toggle', 'search-panel-input');
  wireMobileSubmenu('mnav-gamme-toggle', 'mnav-gamme-submenu');
  wireMobileSubmenu('mnav-formulaire-toggle', 'mnav-formulaire-submenu');
})();
