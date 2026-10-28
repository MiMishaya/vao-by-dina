# vao-by-dina

Site vitrine VAO / SDOI (français + malagasy) : thème WordPress `wordpress/vao-by-dina`.

## Arborescence
- `wordpress/vao-by-dina/` : le thème (PHP / CSS / JS, sans build)
- `img/` : images sources du thème (copiées dans `assets/img/` par le build)
  - `maquette/` : visuels provisoires extraits de la maquette, à remplacer par les fichiers HD du client
  - `rse/`, `avis/` : photos RSE et captures d'avis fournies par le client
  - `docs/` : catalogue PDF

## Prérequis
- WordPress 5.5+ ; extension Polylang pour le malagasy (facultative)
- PHP avec GD pour le build (réduction des images)

## Build
```powershell
powershell -ExecutionPolicy Bypass -File wordpress\build.ps1
```
→ `wordpress/vao-by-dina.zip`, à installer via Apparence > Thèmes > Ajouter > Téléverser.

À l'activation, le thème crée les pages et le contenu de démonstration. Sur un site déjà installé, le contenu v2 est ajouté à la première visite de l'admin.

## Variables d'environnement
Aucune. Destinataires des formulaires : Apparence > Personnaliser > Thème VAO > Formulaires. Ne jamais mettre de clé privée dans le HTML/JS.
