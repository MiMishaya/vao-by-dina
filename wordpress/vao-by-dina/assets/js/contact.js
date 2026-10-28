// VAO - page Nous contacter (template-contact.php) :
// onglets synchronisés avec l'ancre de l'URL (#distributeur, #information, #offres, #candidature),
// flèches précédent / suivant, bouton « Postuler » des offres et contrôle des pièces jointes.
// Sans JavaScript, les quatre panneaux restent affichés l'un sous l'autre.
(function () {
  var root = document.querySelector('.contact');
  if (!root) return;

  var tablist = root.querySelector('.contact-tabs');
  var tabs = Array.prototype.slice.call(root.querySelectorAll('.contact-tab'));
  var panels = tabs.map(function (tab) { return document.getElementById(tab.getAttribute('href').slice(1)); });
  if (!tablist || !tabs.length || panels.indexOf(null) !== -1) return;

  var ids = panels.map(function (panel) { return panel.id; });
  var arrows = Array.prototype.slice.call(root.querySelectorAll('.contact-arrow'));
  var current = 0;

  root.classList.add('contact-js');
  tablist.setAttribute('role', 'tablist');
  tabs.forEach(function (tab, i) {
    tab.setAttribute('role', 'tab');
    tab.setAttribute('aria-controls', ids[i]);
    panels[i].setAttribute('role', 'tabpanel');
    panels[i].setAttribute('tabindex', '0');
  });
  arrows.forEach(function (arrow) { arrow.setAttribute('data-label', arrow.getAttribute('aria-label')); });

  function indexOfHash(hash) {
    return ids.indexOf((hash || '').replace(/^#/, ''));
  }

  function activate(i) {
    current = i;
    tabs.forEach(function (tab, j) {
      var on = j === i;
      tab.classList.toggle('active', on);
      tab.setAttribute('aria-selected', String(on));
      tab.tabIndex = on ? 0 : -1;
      panels[j].hidden = !on;
    });
    // Le fond teinté de la page dépend de l'onglet ouvert (voir contact.css).
    document.body.setAttribute('data-contact', ids[i]);

    arrows.forEach(function (arrow) {
      var target = (i + (arrow.getAttribute('data-dir') === 'prev' ? -1 : 1) + ids.length) % ids.length;
      arrow.setAttribute('href', '#' + ids[target]);
      arrow.setAttribute('aria-label', arrow.getAttribute('data-label') + ' : ' + tabs[target].textContent.trim());
    });
  }

  // replaceState : l'URL reste partageable sans empiler l'historique ni faire défiler la page.
  function open(i) {
    activate(i);
    if (window.history && history.replaceState) {
      history.replaceState(null, '', '#' + ids[i]);
    }
  }

  // Après un changement d'onglet depuis le bas de page, on remonte au début du panneau.
  function revealIfNeeded(i) {
    if (tablist.getBoundingClientRect().top < 0) {
      panels[i].scrollIntoView();
    }
  }

  tabs.forEach(function (tab, i) {
    tab.addEventListener('click', function (e) {
      e.preventDefault();
      open(i);
    });
  });

  tablist.addEventListener('keydown', function (e) {
    var i = null;
    if (e.key === 'ArrowRight' || e.key === 'ArrowDown') i = (current + 1) % tabs.length;
    if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') i = (current - 1 + tabs.length) % tabs.length;
    if (e.key === 'Home') i = 0;
    if (e.key === 'End') i = tabs.length - 1;
    if (i === null) return;
    e.preventDefault();
    open(i);
    tabs[i].focus();
  });

  arrows.forEach(function (arrow) {
    arrow.addEventListener('click', function (e) {
      var i = indexOfHash(arrow.getAttribute('href'));
      if (i === -1) return;
      e.preventDefault();
      open(i);
      revealIfNeeded(i);
    });
  });

  // Liens internes vers un onglet (ex. état vide des offres → candidature).
  root.querySelectorAll('.contact-panel a[href^="#"]').forEach(function (link) {
    link.addEventListener('click', function (e) {
      var i = indexOfHash(link.getAttribute('href'));
      if (i === -1) return;
      e.preventDefault();
      open(i);
      panels[i].scrollIntoView();
    });
  });

  // Liens du menu vers cette même page (…/nous-contacter/#offres).
  window.addEventListener('hashchange', function () {
    var i = indexOfHash(window.location.hash);
    if (i === -1) return;
    activate(i);
    panels[i].scrollIntoView();
  });

  var start = indexOfHash(window.location.hash);
  activate(start === -1 ? 0 : start);

  /* ---------- Bouton « Postuler » : ouvre la candidature avec le poste prérempli ---------- */
  var poste = document.getElementById('c-poste');
  var candidature = ids.indexOf('candidature');
  root.querySelectorAll('.offre-postuler').forEach(function (button) {
    button.addEventListener('click', function (e) {
      if (!poste || candidature === -1) return;
      e.preventDefault();
      poste.value = button.getAttribute('data-poste') || '';
      open(candidature);
      poste.focus({ preventScroll: true });
      panels[candidature].scrollIntoView();
    });
  });

  /* ---------- Pièces jointes : noms des fichiers, 5 Mo et 5 fichiers maximum (comme inc/forms.php) ---------- */
  var MAX_SIZE = 5 * 1024 * 1024;
  var MAX_FILES = 5;
  var msgSize = root.getAttribute('data-msg-size') || '%s';
  var msgCount = root.getAttribute('data-msg-count') || '';

  function formatSize(bytes) {
    return bytes >= 1024 * 1024
      ? (bytes / 1024 / 1024).toFixed(1).replace('.', ',') + ' Mo'
      : Math.max(1, Math.round(bytes / 1024)) + ' Ko';
  }

  root.querySelectorAll('.contact-file').forEach(function (input) {
    var field = input.parentNode;
    var list = field.querySelector('.contact-file-list');
    var error = field.querySelector('.contact-file-error');
    if (!list || !error) return;

    input.addEventListener('change', function () {
      var files = Array.prototype.slice.call(input.files || []);
      var kept = [];
      var messages = [];

      files.forEach(function (file) {
        if (file.size > MAX_SIZE) {
          messages.push(msgSize.replace('%s', file.name));
        } else {
          kept.push(file);
        }
      });
      if (kept.length > MAX_FILES) {
        kept = kept.slice(0, MAX_FILES);
        messages.push(msgCount);
      }

      if (kept.length !== files.length) {
        try {
          var transfer = new DataTransfer();
          kept.forEach(function (file) { transfer.items.add(file); });
          input.files = transfer.files;
        } catch (err) {
          // Navigateur sans DataTransfer : on vide la sélection plutôt que d'envoyer un fichier refusé.
          input.value = '';
          kept = [];
        }
      }

      error.textContent = messages.join(' ');
      error.hidden = !messages.length;
      list.innerHTML = '';
      kept.forEach(function (file) {
        var item = document.createElement('li');
        item.textContent = file.name + ' (' + formatSize(file.size) + ')';
        list.appendChild(item);
      });
    });
  });
})();
