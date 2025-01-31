"use strict";
// Active le mode strict en JavaScript. Cela impose une syntaxe plus stricte et empêche certaines pratiques risquées ou dépréciées.

document.querySelectorAll('.nav-link').forEach(link => {
    // Sélectionne tous les éléments de la page ayant la classe "nav-link" et applique une fonction à chaque élément.
    // La méthode "forEach" permet de parcourir tous les éléments trouvés.

    link.addEventListener('click', (e) => {
        // Ajoute un écouteur d'événements sur chaque lien ("link") pour l'événement "click".
        // Dès qu'un utilisateur clique sur un lien, cette fonction est exécutée.

        e.preventDefault();
        // Empêche le comportement par défaut du lien (navigation classique vers une autre page).
        // Cela permet d'effectuer un traitement personnalisé au lieu de rediriger vers une nouvelle page.

        const targetId = e.target.getAttribute('data-target');
        // Récupère la valeur de l'attribut "data-target" de l'élément cliqué (le lien).
        // Cet attribut contient l'ID de la section vers laquelle on souhaite défiler.

        const targetElement = document.getElementById(targetId);
        // Utilise l'ID récupéré pour sélectionner l'élément HTML correspondant (la section cible).
        // "getElementById" renvoie l'élément dont l'ID est égal à "targetId".

        if (targetElement) {
            // Vérifie si l'élément cible existe sur la page (pour éviter les erreurs si l'ID est invalide).

            targetElement.scrollIntoView({ behavior: 'smooth' });
            // Fait défiler la page jusqu'à l'élément cible en utilisant un défilement fluide.
            // "behavior: 'smooth'" permet d'effectuer le défilement de manière animée et fluide.
        }
    });
});

// Sélection des éléments nécessaires
const filterButtons = document.querySelectorAll('.filter-btn');
const projets = document.querySelectorAll('.projet');

// Fonction pour filtrer les projets
function filterProjects(category) {
    projets.forEach(projet => {
        // Si "Tous" est sélectionné ou si le projet correspond à la catégorie, on l'affiche
        if (category === 'all' || projet.classList.contains(category)) {
            projet.style.display = 'block';
        } else {
            projet.style.display = 'none';
        }
    });

    // Mise à jour de l'état actif des boutons
    filterButtons.forEach(button => button.classList.remove('active'));
    document.getElementById(`filter-${category}`).classList.add('active');
}

// Ajout des événements de clic aux boutons
filterButtons.forEach(button => {
    button.addEventListener('click', () => {
        const category = button.id.split('-')[1]; // Récupère la catégorie à partir de l'ID du bouton
        filterProjects(category);
    });
});

// Affichage initial : Tous les projets
filterProjects('dev');


const burgerMenu = document.getElementById('burger-menu');
const overlay = document.getElementById('menu');

// Ouvrir/fermer le menu
burgerMenu.addEventListener('click', function () {
    this.classList.toggle("close");
    overlay.classList.toggle("overlay");
});

// Fermer le menu lorsqu'un lien est cliqué
const menuLinks = document.querySelectorAll('#menu a');

menuLinks.forEach(link => {
    link.addEventListener('click', () => {
        burgerMenu.classList.remove("close");
        overlay.classList.remove("overlay");
    });
});

// Fermer le menu lorsqu'on clique en dehors du menu
overlay.addEventListener('click', (event) => {
    if (event.target === overlay) {
        burgerMenu.classList.remove("close");
        overlay.classList.remove("overlay");
    }
});


// Attend que le document soit complètement chargé avant d'exécuter le script
document.addEventListener("DOMContentLoaded", function () {
    // Sélectionne le bouton de bascule du mode sombre/clair par son ID
    const toggleButton = document.getElementById("l");
    
    // Récupère l'élément <body> pour lui appliquer la classe de thème
    const body = document.body;

    // Vérifie si un thème "dark" est déjà stocké dans le localStorage
    if (localStorage.getItem("theme") === "dark") {
        // Ajoute la classe "dark-mode" au <body> si le thème stocké est sombre
        body.classList.add("dark-mode");

        // Met à jour le texte du bouton pour refléter l'état actuel (mode clair)
        toggleButton.textContent = "";
    }

    // Ajoute un écouteur d'événements au bouton pour gérer le changement de thème
    toggleButton.addEventListener("click", function () {
        // Ajoute ou supprime la classe "dark-mode" sur le <body>
        body.classList.toggle("dark-mode");

        // Vérifie si le mode sombre est activé après le basculement
        if (body.classList.contains("dark-mode")) {
            // Stocke la préférence utilisateur en mode sombre dans le localStorage
            localStorage.setItem("theme", "dark");

            // Met à jour le texte du bouton pour indiquer le passage en mode clair
            toggleButton.textContent = "";
        } else {
            // Stocke la préférence utilisateur en mode clair dans le localStorage
            localStorage.setItem("theme", "light");

            // Met à jour le texte du bouton pour indiquer le passage en mode sombre
            toggleButton.textContent = "";
        }
    });
});


