# Règles techniques — site statique HTML / JS (+ WordPress)

- Jamais de `console.log` committé dans les fichiers JS custom
- Jamais de clé API privée dans le HTML/JS (tout ce qui est servi au navigateur est public) ; clé publique restreinte par domaine si nécessaire
- Formulaires : validation côté serveur (WordPress / backend) en plus du navigateur ; jamais d'envoi de mail ou d'écriture en base déclenché par du JS seul
- Liens externes `target="_blank"` → `rel="noopener noreferrer"`
- Images : dimensions `width`/`height` renseignées, formats compressés (webp/jpg)
- WordPress : pas de modification du core ni des thèmes/plugins tiers — thème enfant ou plugin custom

## Vérifications avant push
- Ouvrir chaque page modifiée dans le navigateur, console sans erreur
- Grep `console.log` dans les fichiers modifiés → 0
