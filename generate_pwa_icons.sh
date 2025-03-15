#!/bin/bash

# Créer le répertoire des icônes s'il n'existe pas
mkdir -p assets/img/icons

# Utiliser ImageMagick pour redimensionner le logo en différentes tailles d'icônes
# Si ImageMagick n'est pas installé, vous devrez l'installer ou utiliser un autre outil

# Vérifier si convert (ImageMagick) est disponible
if command -v convert >/dev/null 2>&1; then
  echo "Génération des icônes PWA à partir du logo..."
  
  # Générer les icônes aux tailles requises
  convert assets/img/logo/logo.webp -resize 72x72 assets/img/icons/icon-72x72.png
  convert assets/img/logo/logo.webp -resize 96x96 assets/img/icons/icon-96x96.png
  convert assets/img/logo/logo.webp -resize 128x128 assets/img/icons/icon-128x128.png
  convert assets/img/logo/logo.webp -resize 144x144 assets/img/icons/icon-144x144.png
  convert assets/img/logo/logo.webp -resize 152x152 assets/img/icons/icon-152x152.png
  convert assets/img/logo/logo.webp -resize 192x192 assets/img/icons/icon-192x192.png
  convert assets/img/logo/logo.webp -resize 384x384 assets/img/icons/icon-384x384.png
  convert assets/img/logo/logo.webp -resize 512x512 assets/img/icons/icon-512x512.png
  
  echo "Icônes générées avec succès!"
else
  echo "ImageMagick n'est pas installé. Veuillez l'installer ou générer les icônes manuellement."
  echo "Vous pouvez installer ImageMagick avec: brew install imagemagick"
  echo "Ou utiliser un service en ligne comme https://app-manifest.firebaseapp.com/ pour générer vos icônes."
fi

# Vérifier si les icônes ont été créées
echo "Vérification des icônes générées:"
ls -la assets/img/icons/
