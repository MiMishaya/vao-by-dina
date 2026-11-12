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
- f9b5d06 (message « push »)

## 2026-11-03 — Sécurité formulaires et préparation mise en ligne

### Ce qui a été fait
- Revue sécurité du thème : nonces, capacités, échappement des sorties, redirections, pièces jointes → pas de faille trouvée
- Formulaires durcis : limite de 10 envois / 10 min par IP, 30 champs max, libellés 100 car., valeurs 5000 car.
- E-mail du Service Commercial : valeur par défaut invalide (« vente directe@vao.mg ») retirée → masqué en attendant le client
- project.md à jour (site LocalWP « vao » en jonction, build, points d'attention)
- Build vérifié : bannières réduites à 1920 px ; seul le catalogue PDF (≈ 30 Mo) reste lourd

### Non fait
- Recette sur vao.local : site LocalWP arrêté au moment de la session
- SMTP : attend les accès client / hébergeur

### Commits
- (à compléter au commit)

## 2026-11-08 — Historique des demandes (v1)

### Ce qui a été fait
- Client : hébergement = VPS Pro Madagascar Internet ; envoi des formulaires par e-mail confirmé ; historique des demandes souhaité
- inc/demandes.php : type privé `vao_demande` (wp_posts/postmeta, pas de table), menu « Demandes » réservé aux admins, fiche en lecture seule, filtre par formulaire, export CSV (BOM + `;`, formules neutralisées), purge quotidienne > 12 mois (corbeille comprise)
- forms.php : enregistrement après wp_mail(), même en cas d'échec d'envoi (statut « E-mail non envoyé »)

### Décisions
- Newsletter incluse ; pièces jointes non conservées (noms seulement) ; conservation 12 mois

### Non fait
- Recette sur vao.local : site LocalWP arrêté

### Commits
- (à compléter au commit)

## 2026-11-08 — Retours client : malagasy officiel, catalogue, destinataires

### Ce qui a été fait
- Malagasy du Word client (TEXTE_SITEWEB_03112026_MALAGASY) reporté dans inc/strings/accueil.php, apropos.php, gammes.php et i18n.php (navigation, noms de gammes, texte SDOI) ; coquilles évidentes corrigées
- Nouveau inc/strings/contenus.php : MG des collaborateurs (poste, ancienneté, témoignage), actions RSE (titre, texte) et textes alternatifs des bannières, clé = texte FR d'install.php ; templates branchés via vao_t() / vao_post_title()
- Polylang : VAO_MG_REVISION force une fois la réécriture des chaînes déjà en base (anciennes propositions MG)
- Catalogue remplacé par la version allégée client (30 Mo → 4,9 Mo), même nom de fichier
- Destinataires des formulaires : déjà conformes au mail client, rien à changer

### Décisions
- Offres d'emploi laissées en français (client)
- Bannières : texte MG uniquement en alternatif ; le texte incrusté reste FR tant que le graphiste ne fournit pas les images MG

### En attente
- Images de bannières en malagasy (graphiste), hébergeur (M. Heidar), précision du client sur « l'historique à conserver »
- Recette MG sur vao.local (site arrêté pendant la session)

### Commits
- (à compléter au commit)

## 2026-11-08 — Optimisation des performances

### Ce qui a été fait
- optimize-images.php : traite aussi les JPG, crée un .webp par image (transparence conservée), variantes bannière 960 px et vignettes RSE 640 px ; logos limités à 800 px
- vao_img() sert le .webp ; CSS des fonds en .webp ; images de l'accueil appelées en dur passées par vao_img()
- Bannière : srcset 960/1920 (PHP + hero-carousel.js), fetchpriority="high" ; galerie RSE : vignettes 640 px
- Polices Inter / Poppins / Montserrat auto-hébergées (assets/fonts), préchargement d'Inter, script emoji WordPress retiré ; VAO_VERSION 2.2.0
- Résultat build : 73 Mo de sources → 10 Mo servis en WebP (témoignages 523 → 56 Ko, logo 199 → 36 Ko, savons 318 → 39 Ko, bannière mobile 75 Ko)

### Non fait
- Recette navigateur : site LocalWP arrêté (vérifié : syntaxe PHP/JS, 141 chemins d'images résolus)
- Images de la médiathèque (ajoutées dans l'admin) : non concernées → extension WebP côté WordPress à prévoir

### Commits
- (à compléter au commit)

## 2026-11-08 — Bannières malagasy, e-mail commercial, audit

### Ce qui a été fait
- Bannières MG : texte FR effacé à partir des photos sources sans texte (VAO/Fichier Illustrator/Web project 02 Header only_Dossier/Links, recalage automatique), slogans du Word client (« TEXTE sur VISUELS ») composés en Inter (600 / 500, mêmes lignes de base et couleurs que le FR) → img/header/*-mg.png (3840 px)
- vao_hero_file() : en MG, une bannière à image du thème sert « nom-mg » si présent ; image de la médiathèque toujours prioritaire ; vao_hero_slides() mis en cache statique
- E-mail Service Commercial : ventedirecte@vao.mg (espace retiré, confirmé par l'utilisateur)
- Audit (agent) : lang="mg-MG" au lieu de mg_MG dans le sélecteur de langue, alt « VAO 20 ans » traduit, logo du pied de page en lazy
- Recette vao.local : FR et MG sans erreur console, bannières MG servies en WebP 1920/960

### Décisions
- Contenus manquants (distributeurs, MAX, Germicide, photos HD) : abandonnés (utilisateur)
- Découpe des slogans MG calquée sur le FR (grande / petite / grande / petite), réduction auto si la ligne déborde (06 : 88 %, 07 : 86 %)

### Commits
- (à compléter au commit)

## 2026-11-08 — Recette complète (« site impeccable »)

### Ce qui a été fait
- Parcours automatique (puppeteer) des 14 pages FR / MG + 404 + recherche, en 1440 px et 390 px : 0 erreur console, 0 débordement, 0 image cassée
- Accueil : titre SDOI passé en <h1> (aucun h1 avant) ; recherche : gammes et produits trouvés (FR / MG, sans accents) ; titres d'onglet recherche / 404 traduits ; pagination traduite
- Gammes : « + » décoratif retiré quand la gamme a ≤ 3 produits
- Revue du diff (agent) : en-tête CSV neutralisé, fputcsv compatible PHP 8.4, limite d'envois comptée après validation, visiteur informé « envoyé » dès que la demande est enregistrée
- Test réel du formulaire « information » : demande enregistrée intacte (antislash, guillemets), purge planifiée ; demandes de test supprimées

### À faire dans l'admin (hors code)
- Réglages > Général : titre du site « vao » → « VAO », slogan, langue Français ; titre de la page des gammes « Tous nos gammes » → « Nos marques »
- Après chaque mise en ligne : ouvrir wp-admin une fois (Polylang réécrit les traductions du dictionnaire)

### Commits
- (à compléter au commit)

## 2026-11-08 — Admin : traduction malagasy simplifiée + guide

### Ce qui a été fait
- inc/admin-mg.php : champ « en malagasy » sous chaque texte du Personnaliser, nom / description MG dans la fiche gamme, poste / ancienneté / témoignage MG (collaborateurs), titre / texte MG (RSE) ; pré-remplis avec la traduction affichée ; prioritaires dans vao_t() (vao_mg_overrides) et conservés si le FR change
- vao_items() : types traduits par Polylang (bannière, témoignages…) en MG élément par élément (traduction sinon original) — traduire une bannière ne masque plus les autres
- inc/admin-guide.php : encadré « Guide du site VAO » en tête du tableau de bord (liens directs + mode d'emploi malagasy)
- Rappel « Version malagasy : … » en haut des fiches bannière, témoignage, collaborateur, RSE ; VAO_VERSION 2.3.0
- Tests : écrans admin rendus via WP en CLI (php -d mysqli.default_port=10005), traductions temporaires vérifiées sur le site MG puis supprimées ; parcours 14 pages sans erreur

### Commits
- (à compléter au commit)

## 2026-11-08 — Cartes témoignages en malagasy

### Ce qui a été fait
- 8 cartes MG (img/temoignage/*-mg.png) : bandeau recalculé (dégradé linéaire ajusté), catégorie Inter 800, citation Inter 300 opsz 14 justifiée, signature light italique ; citations FR traduites par nous (À FAIRE VALIDER par le client), 2 citations déjà en malagasy conservées
- vao_mg_theme_file() : toute image du thème a sa variante « -mg » servie en MG (bannières + témoignages)
- Base locale : correspondance image / titre de 5 témoignages corrigée (alt faux) ; traduction MG vide en corbeille (n° 240, capture d'écran étrangère au site) supprimée avec son média
- Aide admin témoignages mise à jour (cartes MG déjà livrées)

### En attente
- Fiche MG n° 243 (Haja) recréée par l'utilisateur avec la même capture d'écran : à supprimer pour retrouver la carte MG livrée

### Commits
- (à compléter au commit)

## 2026-11-11 / 12 — Corrections visuelles, animations, conformité brief

### Ce qui a été fait
- Cartes « Contactez l'équipe » dégagées de la vague du pied de page (padding proportionnel à la vague)
- Avis : défilement auto 7 s, actif même avec « réduire les animations », pause souris / focus clavier seulement (bug mobile corrigé)
- Frise « Notre histoire » animée une fois à l'arrivée (tracé SVG + apparition des étapes) ; compteurs des chiffres clés (Qui sommes-nous)
- Bords déchirés (accueil orange, Qui sommes-nous violet) passés en masque CSS sur ::before — ⚠️ écart maquette (papier blanc), à valider client
- Frise conforme au Word client (TEXTE_SITEWEB_23102026) : 2008 « Blanc, Jaune, Marron, Or », 2026 « 20 ans : +10 marques… » (MG déjà conforme)
- Écran de chargement : 5 s → 2,5 s ; VAO_VERSION 2.4.3

### Décisions / infos
- Brief = D:\PRIMANET\Preprod\VAO\28102026 (Word = organigramme + textes ; PDF = mêmes maquettes que Render)
- Preprod : protection HTTP « vao » ≠ Web Disk agenceweb@vao.mg (dossier public_html/preprod.vao.mg) ; IP 102.17.231.217 bloquée après échecs WebDAV puis débloquée
- Les 8 photos collaborateurs existent (VAO\Elément\Témoignage interne) mais le client complétera lui-même (utilisateur)

### En attente client
- Logo Germicide, logo + packshots MAX (seule une affiche existe), liste distributeurs, photos collaborateurs
- Validation : bords déchirés, cartes avis MG, bannières MG

### Commits
- (à compléter au commit)

## 2026-11-12 — Retours client (accueil, Qui sommes-nous, chargement, pied de page)

### Ce qui a été fait
- Frise « Notre histoire » rejouée toutes les 12 s tant qu'elle est visible ; en MG sur ordinateur, même vague que le FR (repère agrandi, textes du haut au-dessus des titres), frise verticale seulement < 1024 px
- MG : « Découvrir plus » → « Ny vokatra rehetra » ; menu « Notre engagement » retiré ; liens « Politique de confidentialité | Conditions générales » retirés du pied de page (FR + MG)
- Chiffres clés : scaleX supprimé (chiffres déformés), taille réduite ; références produits en liste multi-colonnes pleine largeur (plus de carrousel)
- Survol : icônes gage de qualité / valeurs zoom + 10° ; cartes « Contactez l'équipe » zoom
- Photos collaborateurs : originaux client (img/collaborateurs, 8 JPG) + mise à jour unique `vao_content_v3` (admin_init) qui les affecte aux fiches sans image choisie
- Écran de chargement : affiché à chaque clic (fin du « une fois par navigateur »), produits agrandis, feuilles qui tombent en se balançant ; fond « feuilles » des pages animé (calque fixe, 90 s par motif)
- Pied de page : marge = hauteur de la vague + 2rem (la vague recouvrait images / logos en fin de page) ; VAO_VERSION 2.5.0

### À vérifier (site local arrêté pendant la session)
- Rendu frise MG à 1024 / 1440 / 1920 px, chiffres clés (position de « ans »), références, chargement, pied de page
- Recherche « pas encore fonctionnelle » (retour client) : code en place, non reproduit
- Liens « Produits sélectionnés » / « Accédez à nos formulaires » : pointent déjà vers gamme / onglet du formulaire

### Commits
- (à compléter au commit)

## 2026-11-12 (suite) — 2e retour client
- Chiffres clés : police Akzidenz-Grotesk Std Extra Bold (OTF du dossier maquette client → assets/fonts, @font-face dans fonts.css) — licence web à confirmer par le client
- MG frise 2008 : « Fampiroboroboana » → « Fandrosoana » ; « Tout voir… » centré sous les références
- Soulignement au survol retiré partout (consentement newsletter, cartes contact, liens du pied de page, « Tout voir ») ; VAO_VERSION 2.5.1
- En attente client : liste distributeurs, page produits complète ; recherche toujours à tester (site local arrêté)
- Recherche sans page de résultats (demande client) : vao_search_redirect() (template_redirect) → gamme trouvée, sinon 1er produit (#produit-ID, rangée « + » dépliée et halo par gammes.js), sinon 1re page WP ; page « Aucun résultat » seulement si rien ; VAO_VERSION 2.5.2
- Pages « Politique de confidentialité » / « Conditions générales » retirées de vao_pages() (plus créées ni signalées manquantes) ; case newsletter en texte simple sans lien ; pages existantes à mettre à la corbeille dans wp-admin (FR + MG)
