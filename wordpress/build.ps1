# Construit wordpress/vao-by-dina.zip, prêt à installer dans WordPress
# (Apparence > Thèmes > Ajouter > Téléverser un thème).
# Copie les images du site statique (../img) dans le thème avant de zipper.
#
# Utilisation :  powershell -ExecutionPolicy Bypass -File wordpress\build.ps1

$ErrorActionPreference = 'Stop'
Add-Type -AssemblyName System.IO.Compression
Add-Type -AssemblyName System.IO.Compression.FileSystem

$root  = Split-Path -Parent $PSScriptRoot
$theme = Join-Path $PSScriptRoot 'vao-by-dina'
$img   = Join-Path $theme 'assets\img'
$zip   = Join-Path $PSScriptRoot 'vao-by-dina.zip'

# 1. Images
if (Test-Path $img) { Remove-Item $img -Recurse -Force }
Copy-Item (Join-Path $root 'img') $img -Recurse

# Réduction des images (taille adaptée à l'affichage), nécessite PHP avec GD
php -d memory_limit=1024M (Join-Path $PSScriptRoot 'optimize-images.php') $img
if ($LASTEXITCODE -ne 0) { throw "Echec de la reduction des images." }

# Aperçu du thème affiché dans l'admin WordPress (1200 px conseillés)
Remove-Item (Join-Path $theme 'screenshot.*') -ErrorAction SilentlyContinue
$shot = Get-Item (Join-Path $img 'header\Web project 02 Header only-06.*') | Select-Object -First 1
Copy-Item $shot.FullName (Join-Path $theme ('screenshot' + $shot.Extension)) -Force

# 2. Zip (chemins avec "/" pour être lisible sur un serveur Linux)
if (Test-Path $zip) { Remove-Item $zip -Force }
$archive = [System.IO.Compression.ZipFile]::Open($zip, 'Create')
try {
    Get-ChildItem $theme -Recurse -File | ForEach-Object {
        $relative = $_.FullName.Substring($PSScriptRoot.Length + 1).Replace('\', '/')
        [void][System.IO.Compression.ZipFileExtensions]::CreateEntryFromFile($archive, $_.FullName, $relative)
    }
} finally {
    $archive.Dispose()
}

"Theme pret : $zip ({0:N1} Mo)" -f ((Get-Item $zip).Length / 1MB)
