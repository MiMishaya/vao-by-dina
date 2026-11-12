// VAO - écran de chargement : redirige vers ?to=... (même site uniquement) ou vers la page des gammes.
(function () {
  var redirected = false;
  var target = (window.VAO_LOADING && window.VAO_LOADING.fallback) || '/';
  var to = new URLSearchParams(window.location.search).get('to');
  if (to) {
    try {
      var url = new URL(to, window.location.href);
      if (url.origin === window.location.origin) target = url.href;
    } catch (e) { /* URL invalide : on garde la page des gammes */ }
  }

  function go() {
    if (redirected) return;
    redirected = true;
    window.location.href = target;
  }

  // Affiché à chaque passage (demande client) : l'ancien « une fois par navigateur » le faisait disparaître.
  var bar = document.getElementById('progress-bar-fill');
  if (bar) bar.addEventListener('animationend', go);
  // Filet de sécurité si l'animation de la barre (2,5 s) ne se termine pas.
  setTimeout(go, 3000);
})();
