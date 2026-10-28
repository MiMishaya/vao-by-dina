# PostToolUse (Write / Edit) : signale a Claude les traces de debug, anti-patterns et secrets
# dans le fichier ecrit. Ne bloque pas : le message arrive a Claude comme contexte additionnel.
[Console]::InputEncoding = [System.Text.Encoding]::UTF8
try { $data = [Console]::In.ReadToEnd() | ConvertFrom-Json } catch { exit 0 }
$path = [string]$data.tool_input.file_path
if (-not $path -or -not (Test-Path -LiteralPath $path -PathType Leaf)) { exit 0 }
# Code tiers : pas de notre ressort
if ($path -match '[\\/](vendor|node_modules|lib|libs|dist|build)[\\/]') { exit 0 }

$ext = [System.IO.Path]::GetExtension($path).ToLower()
$checks = @(
    @{ p = 'AKIA[0-9A-Z]{16}|sk_live_[0-9a-zA-Z]{10,}|sk_test_[0-9a-zA-Z]{10,}|whsec_[0-9a-zA-Z]{10,}|SG\.[A-Za-z0-9_-]{20,}|-----BEGIN [A-Z ]*PRIVATE KEY'; m = 'secret en clair (cle API / cle privee)' }
)
switch -Regex ($ext) {
    '^\.py$' {
        $checks += @{ p = '^\s*print\('; m = 'print() de debug' }
        $checks += @{ p = 'pdb\.set_trace|\bbreakpoint\(\)'; m = 'point d''arret pdb / breakpoint()' }
        $checks += @{ p = 'cr\.execute\(\s*f["'']|cr\.execute\([^)]*["'']\s*%\s*[\(\w]|cr\.execute\([^)]*\.format\('; m = 'SQL non parametre (f-string / % / format) dans cr.execute' }
        $checks += @{ p = '[''"]ilike[''"]\s*,\s*[''"]%'; m = 'ilike avec joker initial (full scan)' }
    }
    '^\.(js|mjs|cjs|ts|tsx|jsx)$' {
        $checks += @{ p = 'console\.log\('; m = 'console.log' }
        $checks += @{ p = '\bas any\b|:\s*any\b'; m = 'type any' }
        $checks += @{ p = 'select\(\s*[''"]\*[''"]\s*\)'; m = 'select(''*'')' }
        $checks += @{ p = 'window\.location\.reload\('; m = 'window.location.reload()' }
    }
    '^\.php$' {
        $checks += @{ p = '\b(dump|dd|var_dump|print_r)\s*\('; m = 'dump() / dd() / var_dump() de debug' }
        $checks += @{ p = '::getInstance\('; m = 'appel statique ::getInstance() (preferer l''injection)' }
    }
    '^\.twig$' {
        $checks += @{ p = '\{\{\s*dump\('; m = 'dump() dans un template' }
    }
}

$lines = Get-Content -LiteralPath $path -Encoding UTF8
$found = New-Object System.Collections.Generic.List[string]
foreach ($c in $checks) {
    $hits = @()
    for ($i = 0; $i -lt $lines.Count; $i++) {
        if ($lines[$i] -match $c.p) { $hits += ($i + 1) }
    }
    if ($hits.Count -gt 0) {
        $found.Add("- $($c.m) : ligne(s) $((($hits | Select-Object -First 10) -join ', '))")
    }
}
if ($found.Count -eq 0) { exit 0 }

$msg = "Hook qualite Advences - $([System.IO.Path]::GetFileName($path)) :`n" + ($found -join "`n") + "`nCorriger ce qui vient de ta modification ; signaler a l'utilisateur ce qui existait deja."
@{ hookSpecificOutput = @{ hookEventName = 'PostToolUse'; additionalContext = $msg } } | ConvertTo-Json -Compress -Depth 4
exit 0
