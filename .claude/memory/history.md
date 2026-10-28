# Historique des sessions

## 2026-10-08 — Mise en place du kit Advences Claude Code

### Ce qui a été fait
- CLAUDE.md allégé (< 200 lignes) + tronc commun `.claude/advences-rules.md`
- `.claude/memory/` : MEMORY.md, project.md, rules.md (static), feedback.md, history.md
- Hooks `.claude/settings.json` : blocage commandes destructives (PreToolUse), contrôle qualité (PostToolUse), rappel fin de session (Stop)
- `.gitignore` : fichiers d'env et fichiers Claude locaux

### Commits
- (à compléter au commit)

## 2026-10-28 — Refonte v2 du thème selon la maquette client (SITEWEB_28102026)

### Ce qui a été fait
- Brief client (D:\Téléchargement\28102026) : maquette PDF 9 pages, textes Word, photos RSE, captures d'avis, catalogue
- Nouvelle arborescence : Accueil / Qui sommes-nous / Nos marques (5 gammes) / Nous contacter (4 onglets) + pages légales
- Nouveaux types : avis, collaborateurs, actions RSE (galerie), offres d'emploi, distributeurs ; migration v2 auto (option `vao_content_v2`)
- Formulaires : destinataires par formulaire (Personnaliser), champs obligatoires vérifiés serveur, pièces jointes multiples, newsletter
- Visuels provisoires recadrés depuis la maquette dans `img/maquette/` (à remplacer par les HD client)
- Supprimés : prototype statique à la racine, anciens modèles revendeur/formulaires, 14 images inutilisées, chaînes i18n obsolètes

### Décisions
- Textes : `texte.txt` (Word client) prime sur la maquette
- Menu codé en dur (`vao_nav()`), plus d'emplacements Apparence > Menus
- Textes des pages traduits via `inc/strings/*.php` (MG vide = à traduire)

### En attente client
- Traduction MG, packshots (MAX…), photos collaborateurs, liste distributeurs complète, fichiers HD, textes légaux, e-mail « vente directe@vao.mg », SMTP

### Commits
- (à compléter au commit)
