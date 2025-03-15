"use strict";
// Enregistrement du service worker
if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    navigator.serviceWorker.register('/sw.js')
      .then(registration => {
        console.log('Service Worker enregistré avec succès:', registration.scope);
      })
      .catch(error => {
        console.log('Échec de l\'enregistrement du Service Worker:', error);
      });
  });
}

// Gestion de l'installation de la PWA
let deferredPrompt;

window.addEventListener('beforeinstallprompt', (e) => {
  // Empêcher Chrome 67+ d'afficher automatiquement la notification d'installation
  e.preventDefault();
  // Stocker l'événement pour pouvoir le déclencher plus tard
  deferredPrompt = e;
  
  // Afficher un bouton d'installation personnalisé si nécessaire
  const installButton = document.getElementById('installPwa');
  if (installButton) {
    installButton.style.display = 'block';
    
    installButton.addEventListener('click', () => {
      // Afficher la notification d'installation
      deferredPrompt.prompt();
      
      // Attendre que l'utilisateur réponde à la notification
      deferredPrompt.userChoice.then((choiceResult) => {
        if (choiceResult.outcome === 'accepted') {
          console.log('L\'utilisateur a accepté l\'installation de la PWA');
        } else {
          console.log('L\'utilisateur a refusé l\'installation de la PWA');
        }
        // Réinitialiser la variable deferredPrompt
        deferredPrompt = null;
        
        // Cacher le bouton d'installation
        installButton.style.display = 'none';
      });
    });
  }
});

// Détecter quand la PWA a été installée
window.addEventListener('appinstalled', (evt) => {
  console.log('Application installée avec succès!');
});
