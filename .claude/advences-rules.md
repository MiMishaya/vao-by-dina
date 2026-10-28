# Règles Dev Advences — tronc commun (tous projets)

> Règles spécifiques à la stack : `.claude/memory/rules.md`. Contexte projet : `.claude/memory/MEMORY.md`.

## Comportement Claude
- Réponses courtes et directes ; procéder sans demander confirmation
- Exception : changement de schéma DB (CREATE/ALTER TABLE, nouveau champ stocké, script de migration) → soumettre d'abord dans le chat
- Ne jamais refactorer un fichier qui fonctionne sans demande explicite
- Ne jamais inventer une réponse technique incertaine — dire "je ne sais pas"
- Commenter le code uniquement sur le POURQUOI, jamais le QUOI

## Workflow sessions
- `/plan` avant toute tâche touchant > 2 fichiers — valider le plan AVANT d'écrire du code
- `/clear` entre tâches non reliées ; `/compact` après 30+ min de session
- Après chaque implémentation non triviale, se demander : "What breaks? What's not handled? Quels edge cases / failles ?" — avant le push
- Tâche non triviale : découverte → plan validé → implémentation → vérification (tests, edge cases, sécurité)

## Protocole avant toute correction d'audit
1. Lire `.claude/memory/` : MEMORY.md, history.md, rules.md, feedback.md
2. Évaluer si le problème est réel : le code tourne en prod ? pattern documenté ? compatible avec la stack ? (un auditeur sans contexte produit des faux positifs)
3. Grep les appelants de la fonction / route / champ à modifier
4. Corriger puis tester (voir "Vérifications avant push" dans rules.md) — jamais déclarer terminé sans avoir testé

## Sécurité — règles absolues
- Zéro secret dans le code versionné (mots de passe, tokens, clés API, URLs avec credentials) : fichiers d'env hors git uniquement
- `.env` / `.env.local` dans `.gitignore` ; `.env.example` commité avec les noms de variables et des valeurs VIDES, une ligne commentée par variable
- Un secret commité reste dans l'historique git même après suppression → le révoquer / le faire tourner, pas seulement le retirer
- Jamais de valeur secrète dans `.claude/memory/` (commité) : écrire le NOM de la variable, jamais sa valeur
- Jamais de trace de debug committée (`print`, `console.log`, `dump()`, `dd()`, `var_dump`) — debug temporaire marqué `TODO: rm`

## Git
- Commits atomiques : 1 sujet = 1 commit (`git add -p` pour ne stager que le sujet)
- Format : `feat(scope): …` / `fix(scope): …` / `chore: …` — jamais "wip", "fix", "misc"
- Relire le `git diff` complet avant de commit ; `/code-review` avant chaque push significatif
- Pas de push direct sur la branche de prod sans relecture ; jamais `push --force` / `reset --hard` sans accord explicite
- Après une suppression / un renommage de champ ou colonne : grep de l'ancien nom sur tout le dépôt (Python, XML, JS, Twig, PHP, SQL) → 0 résultat

## Mémoire projet (.claude/memory/)
- `history.md` : ajouter une entrée en fin de chaque session (date, ce qui a été fait, décisions, commits)
- `feedback.md` : toute erreur de Claude corrigée → "Ne pas faire" ; tout pattern validé → "Patterns validés"
- `rules.md` : nouvelle règle technique validée → l'y ajouter, puis committer
- `CLAUDE.md` + fichiers importés < 200 lignes : déplacer les détails dans `.claude/memory/`

## Hooks actifs (.claude/settings.json)
- PreToolUse : bloque `rm -rf`, `git push --force/-f`, `git reset --hard`, `DROP TABLE`, `DELETE FROM` sans WHERE → demander à l'utilisateur de lancer la commande lui-même
- PostToolUse : signale les traces de debug et anti-patterns après chaque écriture → les corriger
- Stop : rappel history.md + vérifications avant push
