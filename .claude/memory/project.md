# Projet — vao-by-dina

## Stack
- Thème WordPress classique `wordpress/vao-by-dina` (PHP / CSS / JS, sans build), FR + MG via Polylang
- Images sources dans `img/`, copiées et réduites dans le thème par `wordpress/build.ps1` (PHP GD)
- Test local : site LocalWP « vao » (http://vao.local), thème = jonction vers `wordpress/vao-by-dina` (pas de copie) ; démarrer le site dans Local avant toute recette
- Build : `powershell -ExecutionPolicy Bypass -File wordpress\build.ps1` → `wordpress/vao-by-dina.zip` : optimize-images.php réduit PNG/JPG et crée un .webp par image (+ variantes header -960, rse -640), servi par vao_img() et les CSS ; à relancer après tout ajout dans img/ (PHP du PATH = D:\Logiciel\php7, GD + WebP ; le PHP de LocalWP n'a pas GD)
- Polices auto-hébergées dans `assets/fonts` (fonts.css, latin + latin-ext) : plus aucun appel à Google Fonts

- Version Odoo / PHP exacte : à compléter

## Dépôt
- Remote : https://github.com/MiMishaya/vao-by-dina.git
- Branche de travail preprod : `main`
- Branche de prod : à compléter (push = déploiement ?)

## Environnements
- Hébergement annoncé par le client (2026-11-08) : VPS Pro chez Madagascar Internet — à rapprocher des relevés DNS ci-dessous (migration prévue ? mails restent chez ServerMada ?)
- Prod : https://vao.mg (page « En construction » au 2026-11-03) — relevé DNS : hébergeur ServerMada (serveur priv25.servermada.com, Apache, DNS ns1/ns2.servermada.com) ; e-mails @vao.mg sur le même serveur (MX = vao.mg, SPF limité à cette IP) ; accès cPanel à obtenir du client
- Preprod : à compléter (URL)
- Variables / paramètres nécessaires : voir `.env.example` ou paramètres système — NOMS uniquement, jamais les valeurs

## Client / contexte métier
- À compléter : client, périmètre fonctionnel, interlocuteurs

## Points d'attention connus
- Formulaires : envoi via `wp_mail()` → SMTP à configurer sur chaque environnement (plugin type WP Mail SMTP, identifiants hors dépôt)
- Bannières MG : img/header/*-mg.png (texte malagasy incrusté), servies par vao_hero_file() ; à refaire si le client change un slogan
- Formulaires : chaque envoi valide est aussi enregistré (type `vao_demande`, menu admin « Demandes », admins seuls) ; noms des pièces jointes seulement ; purge auto à 12 mois (cron `vao_demandes_purge`, filtre `vao_demandes_retention_months`)
- Formulaires : limite de 10 envois / 10 min par IP (transient) ; derrière un proxy / CDN, `REMOTE_ADDR` est l'IP du proxy → relever la limite ou lire l'IP réelle
- Catalogue PDF (`img/docs/catalogue-2026.pdf`) : version allégée client (oct. 2026, 4,9 Mo) depuis le 2026-11-08
- Malagasy : texte officiel client = D:\PRIMANET\Preprod\VAO\TEXTE_SITEWEB_03112026_MALAGASY (1).docx ; offres d'emploi laissées en français (choix client)
- Destinataires formulaires confirmés par le client (2026-11-08) : distributeur → grandcompte@, contact@, direction@ ; information → contact@ ; candidature → rh@, contact@, direction@ (@vao.mg)
