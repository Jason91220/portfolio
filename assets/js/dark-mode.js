"use strict"
document.addEventListener('DOMContentLoaded', () => {
    const darkModeToggle = document.getElementById('darkModeToggle');
    const body = document.body;
    
    // Toujours démarrer en mode clair
    body.classList.remove('dark-mode');
    localStorage.setItem('darkMode', 'disabled');
    
    darkModeToggle.addEventListener('click', () => {
        body.classList.toggle('dark-mode');
        const isDarkMode = body.classList.contains('dark-mode');
        localStorage.setItem('darkMode', isDarkMode ? 'enabled' : 'disabled');
        
        // Déclencher un événement pour informer du changement de mode
        document.dispatchEvent(new CustomEvent('darkModeChanged', { detail: { isDarkMode } }));
    });
});
