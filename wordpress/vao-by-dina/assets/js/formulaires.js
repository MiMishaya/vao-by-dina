// VAO - onglets "Demande d'échantillon" / "Candidature spontanée".
// Les URL des pages sont fournies par functions.php (VAO_FORMS.revendeurUrl).
(function () {
  var tabEchantillon = document.getElementById('tab-echantillon');
  var tabCandidature = document.getElementById('tab-candidature');
  var panelEchantillon = document.getElementById('panel-echantillon');
  var panelCandidature = document.getElementById('panel-candidature');
  var arrowPrev = document.getElementById('arrow-prev');
  var arrowNext = document.getElementById('arrow-next');
  if (!tabEchantillon || !tabCandidature || !panelEchantillon || !panelCandidature) return;

  var revendeurUrl = (window.VAO_FORMS && window.VAO_FORMS.revendeurUrl) || arrowPrev.href;

  function activate(name) {
    var isEchantillon = name === 'echantillon';
    document.body.classList.toggle('theme-echantillon', isEchantillon);
    document.body.classList.toggle('theme-candidature', !isEchantillon);
    tabEchantillon.classList.toggle('active', isEchantillon);
    tabCandidature.classList.toggle('active', !isEchantillon);
    tabEchantillon.setAttribute('aria-pressed', String(isEchantillon));
    tabCandidature.setAttribute('aria-pressed', String(!isEchantillon));
    panelEchantillon.hidden = !isEchantillon;
    panelCandidature.hidden = isEchantillon;

    if (isEchantillon) {
      arrowPrev.href = revendeurUrl;
      arrowPrev.setAttribute('aria-label', 'Précédent : Devenir revendeur');
      arrowNext.href = '#candidature';
      arrowNext.setAttribute('aria-label', 'Suivant : Candidature spontanée');
    } else {
      arrowPrev.href = '#echantillon';
      arrowPrev.setAttribute('aria-label', 'Précédent : Demande d\'échantillon');
      arrowNext.href = revendeurUrl;
      arrowNext.setAttribute('aria-label', 'Suivant : Devenir revendeur');
    }
  }

  tabEchantillon.addEventListener('click', function () { activate('echantillon'); });
  tabCandidature.addEventListener('click', function () { activate('candidature'); });

  arrowPrev.addEventListener('click', function (e) {
    if (arrowPrev.getAttribute('href') === '#echantillon') { e.preventDefault(); activate('echantillon'); }
  });
  arrowNext.addEventListener('click', function (e) {
    if (arrowNext.getAttribute('href') === '#candidature') { e.preventDefault(); activate('candidature'); }
  });

  // Les liens du menu pointent vers #echantillon / #candidature sur cette même page.
  window.addEventListener('hashchange', function () {
    activate(window.location.hash === '#candidature' ? 'candidature' : 'echantillon');
  });

  activate(window.location.hash === '#candidature' ? 'candidature' : 'echantillon');
})();
