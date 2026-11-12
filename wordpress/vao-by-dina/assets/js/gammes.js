// VAO - page "Nos marques" : le bouton "+" déplie les produits suivants de la gamme.
document.querySelectorAll('.row-plus[aria-controls]').forEach(function (btn) {
  var extra = document.getElementById(btn.getAttribute('aria-controls'));
  if (!extra) return;
  btn.addEventListener('click', function () {
    var open = extra.classList.toggle('open');
    btn.setAttribute('aria-expanded', open ? 'true' : 'false');
  });
});

// Arrivée depuis la recherche (#produit-123) : le produit peut être dans une rangée repliée, où le navigateur
// ne sait pas défiler ; on la déplie, puis on centre et met en évidence le produit une fois l'ouverture finie.
(function () {
  function reveal() {
    var id = window.location.hash.slice(1);
    if (id.indexOf('produit-') !== 0) return;
    var card = document.getElementById(id);
    if (!card) return;
    var extra = card.closest('.extra-rows');
    var delay = 0;
    if (extra && !extra.classList.contains('open')) {
      var btn = document.querySelector('.row-plus[aria-controls="' + extra.id + '"]');
      if (btn) btn.click();
      delay = 400; // durée du dépliage (gammes.css : 0,35 s)
    }
    setTimeout(function () {
      card.scrollIntoView({ block: 'center' });
      card.classList.remove('is-found');
      void card.offsetWidth;
      card.classList.add('is-found');
    }, delay);
  }
  window.addEventListener('hashchange', reveal);
  // Après le chargement des images : avant, leur hauteur inconnue fausse la position.
  if (document.readyState === 'complete') reveal();
  else window.addEventListener('load', reveal);
})();
