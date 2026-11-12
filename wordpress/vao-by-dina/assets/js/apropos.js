// VAO - page Qui sommes-nous : chiffres clés comptés une fois à l'arrivée à l'écran ; « + 100 » et « + 60 »
// déplient la liste des distributeurs / des références produits.
(function () {
  var section = document.querySelector('.qsn-chiffres');
  if (!section || !('IntersectionObserver' in window)) return;
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

  // La valeur finale reste dans le HTML (SEO, lecteurs d'écran, sans JS) : on la relit ici.
  var groups = [
    { els: section.querySelectorAll('.qsn-num'), delay: 0 },
    { els: section.querySelectorAll('.qsn-small-figure > span'), delay: 250 }
  ];
  var counters = [];
  groups.forEach(function (g) {
    Array.prototype.forEach.call(g.els, function (el) {
      var text = el.textContent;
      var m = text.match(/^([^\d]*)([\d\s ]+)$/);
      if (!m) return;
      var target = parseInt(m[2].replace(/[\s ]/g, ''), 10);
      // Largeur figée sur la valeur finale : sans ça, « 500 » passerait de 1 à 3 chiffres et tout bougerait.
      el.style.minWidth = el.getBoundingClientRect().width + 'px';
      el.style.textAlign = 'center';
      counters.push({ el: el, prefix: m[1], target: target, final: text, delay: g.delay });
    });
  });
  if (!counters.length) return;

  function format(n) { return String(n).replace(/\B(?=(\d{3})+(?!\d))/g, ' '); }
  counters.forEach(function (c) { c.el.textContent = c.prefix + '0'; });

  var DURATION = 1200;
  function run(c) {
    var start = null;
    function frame(now) {
      if (start === null) start = now;
      var t = Math.min(1, (now - start) / DURATION);
      var eased = 1 - Math.pow(1 - t, 3);
      if (t < 1) {
        c.el.textContent = c.prefix + format(Math.round(c.target * eased));
        requestAnimationFrame(frame);
      } else {
        c.el.textContent = c.final;
        c.el.style.minWidth = '';
        c.el.style.textAlign = '';
      }
    }
    setTimeout(function () { requestAnimationFrame(frame); }, c.delay);
  }

  var io = new IntersectionObserver(function (entries) {
    if (!entries[0].isIntersecting) return;
    io.disconnect();
    counters.forEach(run);
  }, { rootMargin: '0px 0px -15% 0px' });
  io.observe(section);
})();

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
