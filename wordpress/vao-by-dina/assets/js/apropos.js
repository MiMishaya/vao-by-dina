// VAO - page Qui sommes-nous : « + 100 » et « + 60 » déplient la liste des distributeurs / des références produits.
(function () {
  var toggles = Array.prototype.slice.call(document.querySelectorAll('.qsn-panel-toggle'));
  if (!toggles.length) return;

  function panelOf(toggle) { return document.getElementById(toggle.getAttribute('aria-controls')); }

  // Un seul panneau ouvert à la fois, comme sur la maquette (onglet blanc sous le chiffre actif).
  function open(target) {
    toggles.forEach(function (toggle) {
      var panel = panelOf(toggle);
      var on = toggle === target;
      toggle.setAttribute('aria-expanded', on ? 'true' : 'false');
      if (panel) panel.hidden = !on;
    });
    // Le carrousel (ui.js) mesure sa piste au redimensionnement : masquée, elle mesurait 0 et les flèches restaient cachées.
    if (target) window.dispatchEvent(new Event('resize'));
  }

  toggles.forEach(function (toggle) {
    toggle.addEventListener('click', function () {
      open(toggle.getAttribute('aria-expanded') === 'true' ? null : toggle);
    });
  });

  // Lien direct (#distributeurs, #references) : le navigateur ne peut pas défiler vers une section masquée.
  var hash = window.location.hash.slice(1);
  toggles.forEach(function (toggle) {
    if (hash && toggle.getAttribute('aria-controls') === hash) {
      open(toggle);
      panelOf(toggle).scrollIntoView();
    }
  });
})();
