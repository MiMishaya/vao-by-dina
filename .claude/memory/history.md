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

### En attente client (2026-10-28)
- Traduction MG, packshots (MAX…), photos collaborateurs, liste distributeurs complète, fichiers HD, textes légaux, e-mail « vente directe@vao.mg », SMTP

### Commits
- (à compléter au commit)

## 2026-11-02 — Alignement sur les maquettes finales (D:\PRIMANET\Preprod\VAO\Render)

### Ce qui a été fait
- Maquettes Render (PNG 6000 px = page 1440 px × 4,1667) comparées aux captures du site LocalWP « vao » (http://vao.local, thème copié)
- En-tête : dégradé relevé sur la maquette, positions outils/menu, « Nos Marques » ; bannières remplacées par les versions Render
- Accueil, Qui sommes-nous (panneaux +100 / +60), Nous contacter (4 onglets colorés, offres), Gammes, écran de chargement : refaits selon la maquette + responsive
- Malagasy rempli dans inc/strings/*.php et i18n.php ; vao_t() se replie sur le dictionnaire du thème si Polylang n'a pas la traduction ; pré-remplissage Polylang relancé quand le dictionnaire change (option vao_pll_prefill_hash)
- install.php : correspondance images / titres des bannières et témoignages corrigée
- Fonds de la maquette (D:\PRIMANET\Preprod\VAO\Elément\BG) réduits dans img/bg : rayures (fond de page), feuilles (calque commun body::after, masqué < 768 px), bords déchirés + dégradé orange / aplat violet, vague du pied de page
- Bouton « retour en haut », en-tête masqué en descendant / réaffiché en remontant, écran de chargement affiché une seule fois par navigateur (localStorage vao_loading_seen)
- Le thème LocalWP est une jonction vers wordpress/vao-by-dina (plus de copie à faire) ; VAO_VERSION 2.1.0

### Décisions
- Textes en base / Word priment sur la maquette en cas d'écart ; fautes de la maquette non reprises (Envoye, Postulé, QUELQUE)
- Chrome headless ne descend pas sous ~500 px : tester le mobile dans une iframe de 390 px

### En attente client
- MG des textes incrustés (bannières, cartes avis, images des offres), validation des traductions MG
- Liste distributeurs complète (Majunga, Toamasina, 2e colonne Antananarivo), écarts poids/conditionnements, produit MAX, logo Germicide
- Photos HD sans texte (offres, collage engagement), feuilles décoratives

### Commits
- (à compléter au commit)
