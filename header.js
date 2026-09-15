// VAO - shared site header. Include with:
//   <link rel="stylesheet" href="header.css">
//   ...
//   <script src="header.js"></script>
// at the spot in <body> where the header should appear.
document.write(`
<header>
  <div class="header-left">
    <div class="header-home">
      <a href="index.html">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M3 11.5 12 4l9 7.5"/>
          <path d="M5 10v10h14V10"/>
          <path d="M10 20v-6h4v6"/>
        </svg>
      </a>
    </div>
    <div class="header-nav">
      <div class="nav-dropdown">
        <button type="button" class="dropdown-toggle" id="gamme-toggle" aria-expanded="false">Notre gamme ▼</button>
        <div class="nav-dropdown-panel" id="gamme-panel">
          <a href="loading.html?to=gammes.html">Tous les produits</a>
          <a href="loading.html?to=gammes.html%23detergent">Détergent en poudre</a>
          <a href="loading.html?to=gammes.html%23detergent">Détergent en barre</a>
          <a href="loading.html?to=gammes.html%23toilette">Savon de toilette</a>
          <a href="loading.html?to=gammes.html%23savonbar">Savon en barre</a>
          <a href="loading.html?to=gammes.html%23savonbar">Savon translucide</a>
          <a href="loading.html?to=gammes.html%23menage">Savon de ménage</a>
        </div>
      </div>
      <div class="nav-dropdown">
        <button type="button" class="dropdown-toggle" id="formulaire-toggle" aria-expanded="false">Formulaires ▼</button>
        <div class="nav-dropdown-panel" id="formulaire-panel">
        <a href="formulaire01.html">Devenir revendeur</a>
        <a href="formulaire02.html#echantillon">Demande d'échantillon</a>
        <a href="formulaire02.html#candidature">Candidature spontanée</a>
        </div>
      </div>
    </div>
  </div>
  <div class="header-logo">
    <img src="img/Render element 02/Element_004-Formulaire 01.png" alt="VAO">
  </div>
  <div class="header-right">
    <div class="site-search" id="site-search">
      <input type="text" class="search-inline-input" id="search-panel-input" placeholder="Rechercher">
      <button type="button" class="header-menu-icon" id="search-toggle" aria-expanded="false" aria-label="Rechercher">
        <img src="img/Render element 02/Element-05.png" alt="">
      </button>
    </div>
    <div class="site-menu">
      <button type="button" class="header-menu-icon" id="site-menu-toggle" aria-expanded="false" aria-label="Menu">
        <img src="img/Render element 02/Element-06.png" alt="">
      </button>
      <div class="nav-dropdown-panel align-right" id="site-menu-panel">
        <a href="formulaire01.html">Devenir revendeur</a>
        <a href="formulaire02.html#echantillon">Demande d'échantillon</a>
        <a href="formulaire02.html#candidature">Candidature spontanée</a>
      </div>
    </div>
    <div class="mobile-menu">
      <button type="button" class="header-menu-icon burger-icon" id="mobile-menu-toggle" aria-expanded="false" aria-label="Menu">
        <span></span><span></span><span></span>
      </button>
      <div class="nav-dropdown-panel align-right" id="mobile-menu-panel">
        <a href="index.html">Accueil</a>
        <div class="mobile-nav-section">
          <button type="button" class="mobile-nav-toggle" id="mnav-gamme-toggle" aria-expanded="false">
            Notre gamme <span class="mobile-nav-caret">▾</span>
          </button>
          <div class="mobile-nav-submenu" id="mnav-gamme-submenu">
            <a href="loading.html?to=gammes.html">Tous les produits</a>
            <a href="loading.html?to=gammes.html%23detergent">Détergent en poudre</a>
            <a href="loading.html?to=gammes.html%23detergent">Détergent en barre</a>
            <a href="loading.html?to=gammes.html%23toilette">Savon de toilette</a>
            <a href="loading.html?to=gammes.html%23savonbar">Savon en barre</a>
            <a href="loading.html?to=gammes.html%23savonbar">Savon translucide</a>
            <a href="loading.html?to=gammes.html%23menage">Savon de ménage</a>
          </div>
        </div>
        <div class="mobile-nav-section">
          <button type="button" class="mobile-nav-toggle" id="mnav-formulaire-toggle" aria-expanded="false">
            Formulaires <span class="mobile-nav-caret">▾</span>
          </button>
          <div class="mobile-nav-submenu" id="mnav-formulaire-submenu">
            <a href="formulaire01.html">Devenir revendeur</a>
            <a href="formulaire02.html#echantillon">Demande d'échantillon</a>
            <a href="formulaire02.html#candidature">Candidature spontanée</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>
`);

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
