# PreToolUse (Bash / PowerShell) : bloque les commandes destructives.
# Exit 2 = commande refusee, le message stderr est renvoye a Claude.
[Console]::InputEncoding = [System.Text.Encoding]::UTF8
try { $data = [Console]::In.ReadToEnd() | ConvertFrom-Json } catch { exit 0 }
$cmd = [string]$data.tool_input.command
if (-not $cmd) { exit 0 }

$rules = @(
    @{ p = '\brm\s+(-[a-z]*r[a-z]*f|-[a-z]*f[a-z]*r|-r\s+-f|-f\s+-r)'; m = 'rm -rf' },
    @{ p = 'Remove-Item\b(?=.*-Recurse)(?=.*-Force)'; m = 'Remove-Item -Recurse -Force' },
    @{ p = '\bgit\s+push\b.*(\s--force\b|\s--force-with-lease\b|\s-f\b)'; m = 'git push --force' },
    @{ p = '\bgit\s+reset\s+--hard\b'; m = 'git reset --hard' },
    @{ p = '\bgit\s+clean\s+-[a-z]*f'; m = 'git clean -f' },
    @{ p = '\bDROP\s+(TABLE|DATABASE|SCHEMA)\b'; m = 'DROP TABLE / DATABASE' },
    @{ p = '\bdropdb\b'; m = 'dropdb' },
    @{ p = '\bTRUNCATE\s+(TABLE\s+)?\w'; m = 'TRUNCATE' },
    # DELETE FROM sans WHERE dans la meme instruction (jusqu'au ; ou a la fin)
    @{ p = '\bDELETE\s+FROM\b(?![^;]*\bWHERE\b)'; m = 'DELETE FROM sans WHERE' }
)

foreach ($r in $rules) {
    if ($cmd -match $r.p) {
        [Console]::Error.WriteLine("Commande bloquee par le hook Advences ($($r.m)). Ne pas la contourner : expliquer a l'utilisateur pourquoi elle est necessaire et lui demander de la lancer lui-meme.")
        exit 2
    }
}
exit 0
