// VAO - shared hero carousel for the header banner images.
// Include with a <div class="hero"><img class="hero-bg">...<div class="hero-dots">...</div></div>
// block already in the page, then <script src="hero-carousel.js"></script> after it.
// Crossfades between two stacked image layers so the fade blends photo-to-photo
// (never dips through the .hero black background).
(function () {
  var SLIDES = [
    { src: "img/header/Web project 02 Header only_Header 01.png", alt: "Propre à l'œil, mais encore plus frais au nez" },
    { src: "img/header/Web project 02 Header only-04.png", alt: "Mousse généreuse pour une propreté éclatante" },
    { src: "img/header/Web project 02 Header only-05.png", alt: "Votre linge, propre comme au premier jour" },
    { src: "img/header/Web project 02 Header only-06.png", alt: "La propreté qui se voit, la fraîcheur qui se ressent" },
    { src: "img/header/Web project 02 Header only-07.png", alt: "Puissance contre les taches, fraîcheur sur votre linge" },
    { src: "img/header/Web project 02 Header only_Header 02.png", alt: "La fraîcheur qui voyage avec vous" },
    { src: "img/header/Web project 02 Header only-03.png", alt: "Douceur au toucher, fraîcheur au quotidien" }
  ];
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
    if (!hero) return;

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
        void imgs[back].offsetWidth; // force reflow so the opacity transition runs
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
