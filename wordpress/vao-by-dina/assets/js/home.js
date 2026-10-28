// VAO - page d'accueil.
// Les carrousels sont gérés par assets/js/ui.js ; ici, on met seulement en avant
// la carte de témoignage la plus proche du centre, comme sur la maquette.
(function () {
  var track = document.querySelector('.home-temoignages .vao-carousel-track');
  if (!track) return;
  var cards = Array.prototype.slice.call(track.children);
  if (cards.length < 3) return;

  var pending = false;

  function highlight() {
    pending = false;
    var box = track.getBoundingClientRect();
    var middle = box.left + box.width / 2;
    var best = null;
    var bestDist = Infinity;
    cards.forEach(function (card) {
      var r = card.getBoundingClientRect();
      var dist = Math.abs(r.left + r.width / 2 - middle);
      if (dist < bestDist) {
        bestDist = dist;
        best = card;
      }
    });
    cards.forEach(function (card) {
      card.classList.toggle('is-center', card === best);
    });
  }

  function schedule() {
    if (pending) return;
    pending = true;
    window.requestAnimationFrame(highlight);
  }

  track.addEventListener('scroll', schedule, { passive: true });
  window.addEventListener('resize', schedule);
  highlight();
})();
