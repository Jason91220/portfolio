"use strict";
const translations = {
    fr: {
        // Navigation
        'nav-about': 'À propos',
        'nav-projects': 'Projets',
        'nav-skills': 'Compétences',
        'nav-contact': 'Contact',
        'nav-cv': 'CV',
        
        // Hero section
        'hero-subtitle': 'Développeur Web Full Stack & Designer',
        
        // About section
        'about-title': 'À propos',
        'about-intro': 'Bonjour, je suis Jason Bedulho, 25 ans, étudiant en Bachelor 2ème année Développeur Web & Concepteur d\'Application à Digital Campus Paris. Passionné par le développement web et le design, je combine créativité et expertise technique pour créer des expériences numériques exceptionnelles.',
        'about-goal': 'Actuellement à la recherche d\'une alternance en développement web, je souhaite mettre mes compétences au service d\'une entreprise innovante tout en continuant à développer mon expertise.',
        'skills-title': 'Technologies maîtrisées',
        
        // Projects section
        'projects-title': 'Projets',
        'project1-desc': 'Un dashboard collaboratif pour développeurs avec gestion de projet et To-Do List intégrée.',
        'project2-desc': 'Site de recettes interactif avec système de filtres et recherche avancée.',
        'project3-desc': 'Application mobile de retouche photo avec filtres et effets personnalisables.',
        'project4-desc': 'Plateforme e-commerce pour artistes indépendants avec système de paiement sécurisé.',
        'view-live': 'Voir le site',
        
        // Skills section
        'dev-skills': 'Développement Web',
        'html-desc': 'Structuration sémantique et accessible du contenu web',
        'css-desc': 'Stylisation avancée et animations fluides',
        'js-desc': 'Interactions dynamiques et expérience utilisateur',
        'php-desc': 'Développement backend orienté objet',
        'sql-desc': 'Gestion et optimisation des bases de données',
        'react-desc': 'Création d\'interfaces utilisateur modernes',
        'vue-desc': 'Applications réactives et composants réutilisables',
        'symfony-desc': 'Architecture MVC et développement rapide',
        'design-skills': 'Design & UI/UX',
        'figma-desc': 'Prototypage et design d\'interfaces',
        'ps-desc': 'Retouche photo et création graphique',
        'ai-desc': 'Création de logos et illustrations vectorielles',
        'id-desc': 'Mise en page et design éditorial',
        'other-skills': 'Autres compétences',
        'git-desc': 'Gestion de versions et collaboration',
        'docker-desc': 'Conteneurisation et déploiement',
        'agile-desc': 'Méthodologies agiles et gestion de projet',
        'seo-desc': 'Optimisation pour les moteurs de recherche',
        
        // Contact section
        'contact-title': 'Contact',
        'contact-name': 'Nom',
        'contact-email': 'Email',
        'contact-subject': 'Sujet',
        'contact-message': 'Message',
        'contact-submit': 'Envoyer',
        'contact-success': 'Message envoyé avec succès ! Je vous répondrai dans les plus brefs délais.',
        
        // Footer
        'footer-rights': 'Tous droits réservés',
        'legal-mentions': 'Mentions légales',
        'legal-title': 'Mentions légales',
        'legal-owner': 'Propriétaire du site :',
        'legal-contact': 'Contact :',
        'legal-host': 'Hébergeur :',
        'legal-siret': 'SIRET o2switch :',
        'legal-ip-title': 'Propriété intellectuelle',
        'legal-ip-content': 'L\'ensemble du contenu de ce site (textes, images, design) est la propriété exclusive de Jason Bedulho ou fait l\'objet d\'une autorisation d\'utilisation. Toute reproduction ou représentation, intégrale ou partielle, est interdite sans autorisation.',
        'legal-data-title': 'Données personnelles',
        'legal-data-content': 'Conformément au Règlement Général sur la Protection des Données (RGPD), vous disposez d\'un droit d\'accès, de rectification et de suppression de vos données. Pour exercer ce droit, veuillez me contacter par email.',
        'legal-cookies-title': 'Cookies',
        'legal-cookies-content': 'Ce site utilise des cookies pour améliorer l\'expérience utilisateur. En naviguant sur ce site, vous acceptez l\'utilisation de ces cookies.'
    },
    en: {
        // Navigation
        'nav-about': 'About',
        'nav-projects': 'Projects',
        'nav-skills': 'Skills',
        'nav-contact': 'Contact',
        'nav-cv': 'CV',
        
        // Hero section
        'hero-subtitle': 'Full Stack Web Developer & Designer',
        
        // About section
        'about-title': 'About',
        'about-intro': 'Hello, I\'m Jason Bedulho, 25 years old, currently in my second year of a Bachelor\'s degree in Web Development & Application Design at Digital Campus Paris. Passionate about web development and design, I combine creativity and technical expertise to create exceptional digital experiences.',
        'about-goal': 'Currently looking for an apprenticeship in web development, I wish to put my skills at the service of an innovative company while continuing to develop my expertise.',
        'skills-title': 'Technologies',
        
        // Projects section
        'projects-title': 'Projects',
        'project1-desc': 'A collaborative dashboard for developers with integrated project management and To-Do List.',
        'project2-desc': 'Interactive recipe website with advanced filtering and search system.',
        'project3-desc': 'Mobile photo editing application with customizable filters and effects.',
        'project4-desc': 'E-commerce platform for independent artists with secure payment system.',
        'view-live': 'View site',
        
        // Skills section
        'dev-skills': 'Web Development',
        'html-desc': 'Semantic and accessible web content structuring',
        'css-desc': 'Advanced styling and fluid animations',
        'js-desc': 'Dynamic interactions and user experience',
        'php-desc': 'Object-oriented backend development',
        'sql-desc': 'Database management and optimization',
        'react-desc': 'Modern user interface creation',
        'vue-desc': 'Reactive applications and reusable components',
        'symfony-desc': 'MVC architecture and rapid development',
        'design-skills': 'Design & UI/UX',
        'figma-desc': 'Interface prototyping and design',
        'ps-desc': 'Photo editing and graphic creation',
        'ai-desc': 'Logo creation and vector illustrations',
        'id-desc': 'Layout and editorial design',
        'other-skills': 'Other Skills',
        'git-desc': 'Version control and collaboration',
        'docker-desc': 'Containerization and deployment',
        'agile-desc': 'Agile methodologies and project management',
        'seo-desc': 'Search engine optimization',
        
        // Contact section
        'contact-title': 'Contact',
        'contact-name': 'Name',
        'contact-email': 'Email',
        'contact-subject': 'Subject',
        'contact-message': 'Message',
        'contact-submit': 'Send',
        'contact-success': 'Message sent successfully! I will reply as soon as possible.',
        
        // Footer
        'footer-rights': 'All rights reserved',
        'legal-mentions': 'Legal Notice',
        'legal-title': 'Legal Notice',
        'legal-owner': 'Website Owner:',
        'legal-contact': 'Contact:',
        'legal-host': 'Hosting Provider:',
        'legal-siret': 'SIRET o2switch:',
        'legal-ip-title': 'Intellectual Property',
        'legal-ip-content': 'All content on this site (texts, images, design) is the exclusive property of Jason Bedulho or is used with authorization. Any reproduction or representation, in whole or in part, is prohibited without permission.',
        'legal-data-title': 'Personal Data',
        'legal-data-content': 'In accordance with the General Data Protection Regulation (GDPR), you have the right to access, rectify, and delete your data. To exercise this right, please contact me by email.',
        'legal-cookies-title': 'Cookies',
        'legal-cookies-content': 'This site uses cookies to enhance user experience. By browsing this site, you accept the use of these cookies.'
    }
};

document.addEventListener('DOMContentLoaded', () => {
    const langToggle = document.getElementById('langToggle');
    const langText = langToggle.querySelector('.lang-text');
    
    // Check for saved language preference
    let currentLang = localStorage.getItem('language') || 'fr';
    updateLanguage(currentLang);
    
    langToggle.addEventListener('click', () => {
        currentLang = currentLang === 'fr' ? 'en' : 'fr';
        localStorage.setItem('language', currentLang);
        updateLanguage(currentLang);
    });
    
    function updateLanguage(lang) {
        document.querySelectorAll('[data-lang]').forEach(element => {
            const key = element.getAttribute('data-lang');
            if (translations[lang][key]) {
                element.textContent = translations[lang][key];
            }
        });
        langText.textContent = lang.toUpperCase();
    }
});
