// VAO - page "Nos marques" : le bouton "+" déplie les produits suivants de la gamme.
document.querySelectorAll('.row-plus[aria-controls]').forEach(function (btn) {
  var extra = document.getElementById(btn.getAttribute('aria-controls'));
  if (!extra) return;
  btn.addEventListener('click', function () {
    var open = extra.classList.toggle('open');
    btn.setAttribute('aria-expanded', open ? 'true' : 'false');
  });
});
