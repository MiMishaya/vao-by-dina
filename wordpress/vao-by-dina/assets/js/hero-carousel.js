// VAO - carrousel de la bannière. Les images sont fournies par functions.php (VAO_HERO.slides).
// Fondu entre deux calques d'image superposés pour passer de photo à photo
// (sans passer par le fond noir du .hero).
(function () {
  var SLIDES = (window.VAO_HERO && window.VAO_HERO.slides) || [];
  var INTERVAL = 5000;
  var FADE_MS = 600;

  function preload(src, cb) {
    var im = new Image();
    im.onload = cb;
    im.onerror = cb;
    im.src = src;
  }

  function init() {
    var hero = document.querySelector(".hero");
    if (!hero || !SLIDES.length) return;

    var imgA = hero.querySelector(".hero-bg");
    var dotsWrap = hero.querySelector(".hero-dots");
    if (!imgA || !dotsWrap) return;

    var imgB = imgA.cloneNode(true);
    imgA.parentNode.insertBefore(imgB, imgA.nextSibling);
    var imgs = [imgA, imgB];
    imgs.forEach(function (im) { im.style.transition = "opacity " + FADE_MS + "ms ease"; });

    dotsWrap.innerHTML = "";
    SLIDES.forEach(function (slide, i) {
      var dot = document.createElement("span");
      if (i === 0) dot.className = "active";
      dot.addEventListener("click", function () { goTo(i); });
      dotsWrap.appendChild(dot);
    });
    var dots = dotsWrap.children;

    var current = 0;
    var front = 0;
    var timer = null;

    function updateDots() {
      for (var i = 0; i < dots.length; i++) {
        dots[i].classList.toggle("active", i === current);
      }
    }

    function showSlide(animate) {
      var slide = SLIDES[current];
      updateDots();

      if (!animate) {
        imgs[front].src = slide.src;
        imgs[front].alt = slide.alt;
        imgs[front].style.opacity = 1;
        imgs[1 - front].style.opacity = 0;
        return;
      }

      var back = 1 - front;
      preload(slide.src, function () {
        imgs[back].src = slide.src;
        imgs[back].alt = slide.alt;
        void imgs[back].offsetWidth; // force le reflow pour que la transition d'opacité s'exécute
        imgs[back].style.opacity = 1;
        imgs[front].style.opacity = 0;
        front = back;
      });
    }

    function goTo(index) {
      current = (index + SLIDES.length) % SLIDES.length;
      showSlide(true);
      restart();
    }

    function next() { goTo(current + 1); }

    function restart() {
      clearInterval(timer);
      timer = setInterval(next, INTERVAL);
    }

    showSlide(false);
    restart();
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }
})();
