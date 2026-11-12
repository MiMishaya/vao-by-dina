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

  // 7 s : le temps de lire l'essentiel d'un avis (~40 mots) ; pause au survol souris et au focus clavier.
  // Maintenu avec « réduire les animations » : le CSS rend alors le changement instantané, sans mouvement.
  var DELAY = 7000;
  var timer = null;
  var hovered = false;
  var focused = false;
  function restart() {
    clearInterval(timer);
    if (!hovered && !focused) timer = setInterval(function () { go(1); }, DELAY);
  }
  // pointerenter filtré sur la souris : un appui tactile émule un survol sans jamais le terminer.
  root.addEventListener('pointerenter', function (e) { if (e.pointerType === 'mouse') { hovered = true; restart(); } });
  root.addEventListener('pointerleave', function (e) { if (e.pointerType === 'mouse') { hovered = false; restart(); } });
  // Seul le focus clavier met en pause : une flèche cliquée garde le focus et figeait le carrousel.
  root.addEventListener('focusin', function (e) {
    try { focused = e.target.matches(':focus-visible'); } catch { focused = true; }
    restart();
  });
  root.addEventListener('focusout', function () { focused = false; restart(); });
  // Après une action manuelle, la carte choisie reste affichée un cycle complet.
  root.addEventListener('click', restart);
  stage.addEventListener('touchend', restart);
  restart();

  render();
  root.classList.add('is-ready');
})();

// Frise « Notre histoire » : animation jouée à l'arrivée à l'écran puis rejouée toutes les 12 s (demande client)
// tant que la frise est visible. Sans IntersectionObserver ou avec « réduire les animations », elle reste fixe.
(function () {
  var tl = document.querySelector('.timeline');
  if (!tl || !('IntersectionObserver' in window)) return;
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
  var REPLAY = 12000;
  var timer = null;
  var lastPlay = 0;
  tl.classList.add('tl-anim');

  function play() {
    lastPlay = Date.now();
    // Retour instantané à l'état masqué (sans transition), puis lecture : les lectures de offsetWidth
    // forcent le calcul des styles entre deux étapes, sinon le navigateur fusionne et rien ne s'anime.
    tl.classList.add('tl-reset');
    tl.classList.remove('is-in');
    void tl.offsetWidth;
    tl.classList.remove('tl-reset');
    void tl.offsetWidth;
    tl.classList.add('is-in');
  }

  var io = new IntersectionObserver(function (entries) {
    clearInterval(timer);
    if (!entries[0].isIntersecting) return;
    if (Date.now() - lastPlay >= REPLAY) play();
    timer = setInterval(play, REPLAY);
  }, { rootMargin: '0px 0px -15% 0px' });
  io.observe(tl);
})();
