# Stop : rappel affiche a l'utilisateur en fin de reponse. Exit 0 = ne relance jamais Claude.
$null = [Console]::In.ReadToEnd()
$msg = "Fin de session ? -> mettre a jour .claude/memory/history.md (+ feedback.md / rules.md si correction ou pattern valide) -> verifications avant push (.claude/memory/rules.md) -> relire git diff -> commit atomique."
@{ systemMessage = $msg } | ConvertTo-Json -Compress
exit 0
