// VAO - bouton "+" qui déplie les lignes de produits supplémentaires.
document.querySelectorAll('.row-plus').forEach(function (btn) {
  var row = btn.closest('.prod-row');
  var extra = row ? row.nextElementSibling : null;
  if (!extra || !extra.classList.contains('extra-rows')) return;
  btn.addEventListener('click', function () {
    var open = extra.classList.toggle('open');
    btn.classList.toggle('open', open);
    btn.setAttribute('aria-expanded', open ? 'true' : 'false');
  });
});
