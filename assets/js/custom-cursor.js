document.addEventListener('DOMContentLoaded', function() {
    // Vérifier si l'appareil utilise un écran tactile
    const isTouchDevice = () => {
        return (('ontouchstart' in window) ||
                (navigator.maxTouchPoints > 0) ||
                (navigator.msMaxTouchPoints > 0));
    };

    // Ne pas initialiser le curseur sur les appareils tactiles
    if (isTouchDevice()) return;

    // Créer les éléments du curseur
    const cursor = document.createElement('div');
    cursor.classList.add('custom-cursor');
    
    const cursorDot = document.createElement('div');
    cursorDot.classList.add('cursor-dot');
    
    // Ajouter les éléments au DOM
    document.body.appendChild(cursor);
    document.body.appendChild(cursorDot);
    
    // Position initiale hors écran
    cursor.style.top = '-100px';
    cursor.style.left = '-100px';
    cursorDot.style.top = '-100px';
    cursorDot.style.left = '-100px';
    
    // Variables pour animation fluide
    let cursorX = -100;
    let cursorY = -100;
    let dotX = -100;
    let dotY = -100;
    
    // Mettre à jour la position du curseur avec un léger délai pour l'anneau extérieur
    document.addEventListener('mousemove', function(e) {
        dotX = e.clientX;
        dotY = e.clientY;
        
        // Mettre à jour immédiatement le point central
        cursorDot.style.left = dotX + 'px';
        cursorDot.style.top = dotY + 'px';
    });
    
    // Animation fluide pour l'anneau extérieur
    function animateCursor() {
        // Effet de traînée pour l'anneau extérieur
        cursorX += (dotX - cursorX) * 0.2;
        cursorY += (dotY - cursorY) * 0.2;
        
        cursor.style.left = cursorX + 'px';
        cursor.style.top = cursorY + 'px';
        
        requestAnimationFrame(animateCursor);
    }
    
    animateCursor();
    
    // Effet de survol sur les éléments interactifs
    const interactiveElements = document.querySelectorAll('a, button, input, textarea, select, .skill-card, .project-card');
    
    interactiveElements.forEach(element => {
        element.addEventListener('mouseenter', () => {
            cursor.classList.add('hover');
        });
        
        element.addEventListener('mouseleave', () => {
            cursor.classList.remove('hover');
        });
    });
    
    // Effet de clic
    document.addEventListener('mousedown', () => {
        cursor.classList.add('click');
        cursorDot.style.transform = 'translate(-50%, -50%) scale(0.5)';
    });
    
    document.addEventListener('mouseup', () => {
        cursor.classList.remove('click');
        cursorDot.style.transform = 'translate(-50%, -50%) scale(1)';
    });
    
    // S'assurer que le curseur reste visible lors du défilement
    document.addEventListener('scroll', () => {
        if (!dotX || !dotY) return;
        cursorDot.style.left = dotX + 'px';
        cursorDot.style.top = dotY + 'px';
    });
});
