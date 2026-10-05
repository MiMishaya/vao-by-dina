// VAO - carrousel 3D des témoignages (page d'accueil).
(function () {
  var track = document.getElementById('testi-track');
  var prev = document.getElementById('testi-prev');
  var next = document.getElementById('testi-next');
  if (!track || !prev || !next) return;
  var cards = Array.prototype.slice.call(track.children);
  var total = cards.length;
  var active = Math.floor(total / 2);

  function layout() {
    cards.forEach(function (card, i) {
      var diff = i - active;
      if (diff > total / 2) diff -= total;
      if (diff < -total / 2) diff += total;
      var abs = Math.abs(diff);
      if (abs > 2) {
        card.style.opacity = '0';
        card.style.pointerEvents = 'none';
        card.style.zIndex = '0';
        return;
      }
      var scale = 1 - abs * 0.18;
      var spacing = window.innerWidth <= 480 ? 110 : (window.innerWidth <= 900 ? 160 : 220);
      var translateX = diff * spacing;
      var rotate = diff * -18;
      var opacity = 1 - abs * 0.35;
      card.style.zIndex = String(100 - abs);
      card.style.opacity = String(Math.max(opacity, 0));
      card.style.pointerEvents = 'auto';
      card.style.transform = 'translate(-50%,-50%) translateX(' + translateX + 'px) scale(' + scale + ') rotateY(' + rotate + 'deg)';
    });
  }

  cards.forEach(function (card, i) {
    card.addEventListener('click', function () {
      active = i;
      layout();
    });
  });

  prev.addEventListener('click', function () {
    active = (active - 1 + total) % total;
    layout();
  });
  next.addEventListener('click', function () {
    active = (active + 1) % total;
    layout();
  });

  window.addEventListener('resize', layout);

  layout();
})();
