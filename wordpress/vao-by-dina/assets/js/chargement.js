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

  var bar = document.getElementById('progress-bar-fill');
  if (bar) bar.addEventListener('animationend', go);
  setTimeout(go, 5000);
})();
