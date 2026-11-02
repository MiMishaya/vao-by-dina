// VAO - page d'accueil : carrousel « en éventail » des témoignages (carte centrale agrandie,
// deux cartes visibles de chaque côté, en boucle). Les positions sont gérées en CSS via data-pos.
(function () {
  var root = document.querySelector('[data-coverflow]');
  if (!root) return;
  var stage = root.querySelector('.home-temoignages-stage');
  var cards = Array.prototype.slice.call(stage.children);
  var n = cards.length;
  if (n < 2) return;

  var L = window.VAO_UI || { prev: 'Précédent', next: 'Suivant' };
  var CHEVRON = {
    prev: '<svg viewBox="0 0 20 32" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16 3 4 16l12 13"/></svg>',
    next: '<svg viewBox="0 0 20 32" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m4 3 12 13L4 29"/></svg>'
  };
  // La maquette centre la 3e carte (deux de chaque côté).
  var current = Math.min(2, n - 1);

  function render() {
    cards.forEach(function (card, i) {
      var d = (i - current + n) % n;
      if (d > n / 2) d -= n;
      // Au-delà de ±3, la carte reste cachée au bord pour éviter qu'elle traverse l'éventail.
      var pos = Math.max(-3, Math.min(3, d));
      card.setAttribute('data-pos', String(pos));
      card.setAttribute('aria-hidden', Math.abs(pos) > 2 ? 'true' : 'false');
    });
  }

  function go(step) {
    current = (current + step + n) % n;
    render();
  }

  ['prev', 'next'].forEach(function (dir) {
    var b = document.createElement('button');
    b.type = 'button';
    b.className = 'coverflow-arrow coverflow-' + dir;
    b.setAttribute('aria-label', L[dir]);
    b.innerHTML = CHEVRON[dir];
    b.addEventListener('click', function () { go(dir === 'next' ? 1 : -1); });
    root.appendChild(b);
  });

  // Clic sur une carte latérale : elle passe au centre.
  cards.forEach(function (card) {
    card.addEventListener('click', function () {
      var d = parseInt(card.getAttribute('data-pos'), 10);
      if (d) go(d);
    });
  });

  var startX = null;
  stage.addEventListener('touchstart', function (e) { startX = e.touches[0].clientX; }, { passive: true });
  stage.addEventListener('touchend', function (e) {
    if (startX === null) return;
    var dx = e.changedTouches[0].clientX - startX;
    startX = null;
    if (Math.abs(dx) > 40) go(dx < 0 ? 1 : -1);
  });

  // Défilement automatique au même rythme que la bannière (5 s), en pause au survol et au focus clavier.
  var DELAY = 5000;
  var timer = null;
  function restart() {
    clearInterval(timer);
    timer = setInterval(function () { go(1); }, DELAY);
  }
  if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    root.addEventListener('mouseenter', function () { clearInterval(timer); });
    root.addEventListener('mouseleave', restart);
    root.addEventListener('focusin', function () { clearInterval(timer); });
    root.addEventListener('focusout', restart);
    // Sur écran tactile (pas de survol) : après un glissement, la carte choisie reste affichée 5 s.
    stage.addEventListener('touchend', restart);
    restart();
  }

  render();
  root.classList.add('is-ready');
})();
