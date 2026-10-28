// VAO - composants partagés par les pages.
//
// Carrousel à flèches :
//   <div class="vao-carousel" data-carousel>
//     <div class="vao-carousel-track"> ...éléments... </div>
//   </div>
//   Les flèches sont ajoutées automatiquement ; le défilement avance d'un élément.
//   data-autoplay="5000" (facultatif) : défilement automatique, en pause au survol.
//
// Galerie plein écran :
//   <a href="grande-image.jpg" data-lightbox="nom-du-groupe"><img ...></a>
//   Les liens d'un même groupe se parcourent avec les flèches (clavier : ← → Échap).
(function () {
  var L = window.VAO_UI || { prev: 'Précédent', next: 'Suivant', close: 'Fermer' };
  var CHEVRON = {
    prev: '<svg viewBox="0 0 20 32" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16 3 4 16l12 13"/></svg>',
    next: '<svg viewBox="0 0 20 32" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m4 3 12 13L4 29"/></svg>'
  };

  function initCarousel(root) {
    var track = root.querySelector('.vao-carousel-track');
    if (!track) return;

    function arrow(dir) {
      var b = document.createElement('button');
      b.type = 'button';
      b.className = 'vao-carousel-arrow vao-carousel-' + dir;
      b.setAttribute('aria-label', L[dir]);
      b.innerHTML = CHEVRON[dir];
      b.addEventListener('click', function () { step(dir === 'next' ? 1 : -1); restart(); });
      root.appendChild(b);
      return b;
    }

    function stepSize() {
      var first = track.firstElementChild;
      if (!first) return track.clientWidth;
      var gap = parseFloat(getComputedStyle(track).columnGap) || 0;
      return first.getBoundingClientRect().width + gap;
    }

    function atEnd() { return track.scrollLeft + track.clientWidth >= track.scrollWidth - 2; }

    function step(dir) {
      if (dir > 0 && atEnd()) {
        track.scrollTo({ left: 0 });
      } else if (dir < 0 && track.scrollLeft <= 2) {
        track.scrollTo({ left: track.scrollWidth });
      } else {
        track.scrollBy({ left: dir * stepSize() });
      }
    }

    var prev = arrow('prev');
    var next = arrow('next');

    function update() {
      var scrollable = track.scrollWidth > track.clientWidth + 2;
      prev.hidden = next.hidden = !scrollable;
    }
    window.addEventListener('resize', update);
    window.addEventListener('load', update);
    update();

    var delay = parseInt(root.getAttribute('data-autoplay'), 10);
    var timer = null;
    function restart() {
      if (!delay) return;
      clearInterval(timer);
      timer = setInterval(function () { step(1); }, delay);
    }
    if (delay && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
      root.addEventListener('mouseenter', function () { clearInterval(timer); });
      root.addEventListener('mouseleave', restart);
      restart();
    } else {
      delay = 0;
    }
  }

  var box = null;
  var group = [];
  var index = 0;
  var opener = null;

  function buildLightbox() {
    box = document.createElement('div');
    box.className = 'vao-lightbox';
    box.hidden = true;
    box.setAttribute('role', 'dialog');
    box.setAttribute('aria-modal', 'true');
    box.innerHTML =
      '<img alt="">' +
      '<button type="button" class="vao-lightbox-close" aria-label="' + L.close + '">&times;</button>' +
      '<button type="button" class="vao-lightbox-prev" aria-label="' + L.prev + '">&#8249;</button>' +
      '<button type="button" class="vao-lightbox-next" aria-label="' + L.next + '">&#8250;</button>' +
      '<div class="vao-lightbox-count"></div>';
    document.body.appendChild(box);
    box.querySelector('.vao-lightbox-close').addEventListener('click', close);
    box.querySelector('.vao-lightbox-prev').addEventListener('click', function () { show(index - 1); });
    box.querySelector('.vao-lightbox-next').addEventListener('click', function () { show(index + 1); });
    box.addEventListener('click', function (e) { if (e.target === box) close(); });
    document.addEventListener('keydown', function (e) {
      if (box.hidden) return;
      if (e.key === 'Escape') close();
      if (e.key === 'ArrowLeft') show(index - 1);
      if (e.key === 'ArrowRight') show(index + 1);
    });
  }

  function show(i) {
    index = (i + group.length) % group.length;
    var link = group[index];
    var img = box.querySelector('img');
    img.src = link.getAttribute('href');
    var thumb = link.querySelector('img');
    img.alt = thumb ? thumb.alt : '';
    box.querySelector('.vao-lightbox-count').textContent = (index + 1) + ' / ' + group.length;
    var several = group.length > 1;
    box.querySelector('.vao-lightbox-prev').hidden = !several;
    box.querySelector('.vao-lightbox-next').hidden = !several;
  }

  function close() {
    box.hidden = true;
    document.body.style.overflow = '';
    if (opener) opener.focus();
  }

  document.addEventListener('click', function (e) {
    var link = e.target.closest ? e.target.closest('a[data-lightbox]') : null;
    if (!link) return;
    e.preventDefault();
    if (!box) buildLightbox();
    var name = link.getAttribute('data-lightbox');
    group = Array.prototype.slice.call(document.querySelectorAll('a[data-lightbox]')).filter(function (a) {
      return a.getAttribute('data-lightbox') === name;
    });
    opener = link;
    box.hidden = false;
    document.body.style.overflow = 'hidden';
    show(group.indexOf(link));
    box.querySelector('.vao-lightbox-close').focus();
  });

  function init() {
    document.querySelectorAll('[data-carousel]').forEach(initCarousel);
  }
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
