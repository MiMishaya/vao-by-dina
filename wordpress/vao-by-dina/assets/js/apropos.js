// VAO - page Qui sommes-nous : liste des distributeurs dépliée par le chevron sous « Distributeurs nationaux ».
(function () {
  var toggle = document.querySelector('.qsn-distrib-toggle');
  var list = document.getElementById('distributeurs');
  if (!toggle || !list) return;

  function setOpen(open) {
    toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    list.hidden = !open;
    // Le carrousel (ui.js) mesure sa piste au redimensionnement : masquée, elle mesurait 0 et les flèches restaient cachées.
    if (open) window.dispatchEvent(new Event('resize'));
  }

  toggle.addEventListener('click', function () {
    setOpen(toggle.getAttribute('aria-expanded') !== 'true');
  });

  // Lien direct vers #distributeurs : le navigateur ne peut pas défiler vers une section masquée.
  if (window.location.hash === '#distributeurs') {
    setOpen(true);
    list.scrollIntoView();
  }
})();
