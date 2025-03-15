"use strict";
document.addEventListener('DOMContentLoaded', () => {
    // Gestion du menu burger pour mobile et tablette
    const burgerMenu = document.getElementById('burgerMenu');
    const navLinksMenu = document.querySelector('.nav-links');
    
    if (burgerMenu) {
        burgerMenu.addEventListener('click', () => {
            burgerMenu.classList.toggle('active');
            navLinksMenu.classList.toggle('active');
            
            // Mise à jour de l'attribut aria-expanded
            const expanded = burgerMenu.getAttribute('aria-expanded') === 'true' || false;
            burgerMenu.setAttribute('aria-expanded', !expanded);
        });
        
        // Fermer le menu burger lorsqu'un lien est cliqué
        document.querySelectorAll('.nav-links a').forEach(link => {
            link.addEventListener('click', () => {
                burgerMenu.classList.remove('active');
                navLinksMenu.classList.remove('active');
                burgerMenu.setAttribute('aria-expanded', 'false');
            });
        });
        
        // Fermer le menu burger lorsque l'utilisateur clique en dehors du menu
        document.addEventListener('click', (event) => {
            if (!event.target.closest('.nav-links') && !event.target.closest('#burgerMenu')) {
                if (navLinksMenu.classList.contains('active')) {
                    burgerMenu.classList.remove('active');
                    navLinksMenu.classList.remove('active');
                    burgerMenu.setAttribute('aria-expanded', 'false');
                }
            }
        });
    }
    // Initialize GSAP animations
    gsap.from('.hero-section h1', {
        duration: 1,
        y: 50,
        opacity: 0,
        ease: 'power3.out'
    });

    gsap.from('.hero-section .subtitle', {
        duration: 1,
        y: 30,
        opacity: 0,
        delay: 0.3,
        ease: 'power3.out'
    });

    // Animation de la barre de navigation pendant le défilement
    const navbar = document.querySelector('.main-nav');
    let lastScrollTop = 0;
    let scrollThreshold = 100; // Seuil de défilement pour déclencher l'animation

    window.addEventListener('scroll', () => {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        // Ajouter la classe 'scrolled' lorsque l'utilisateur défile en dessous du seuil
        if (scrollTop > scrollThreshold) {
            navbar.classList.add('scrolled');
            
            // Déterminer la direction du défilement
            if (scrollTop > lastScrollTop) {
                // Défilement vers le bas
                navbar.classList.remove('scroll-up');
                navbar.classList.add('scroll-down');
            } else {
                // Défilement vers le haut
                navbar.classList.remove('scroll-down');
                navbar.classList.add('scroll-up');
            }
        } else {
            // Au-dessus du seuil, retirer toutes les classes
            navbar.classList.remove('scrolled', 'scroll-up', 'scroll-down');
        }
        
        lastScrollTop = scrollTop;
    });

    // Animation des liens de navigation pendant le défilement
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.nav-links a');
    
    // Fonction pour mettre à jour les liens actifs pendant le défilement
    const updateActiveNavLink = () => {
        const scrollPosition = window.scrollY + 100; // Ajout d'un offset pour une meilleure détection
        
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.offsetHeight;
            const sectionId = section.getAttribute('id');
            
            if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                // Retirer la classe active de tous les liens
                navLinks.forEach(link => {
                    link.classList.remove('active');
                    
                    // Animation de retrait
                    gsap.to(link, {
                        scale: 1,
                        fontWeight: 600,
                        duration: 0.3,
                        ease: 'power2.out'
                    });
                });
                
                // Ajouter la classe active au lien correspondant à la section
                const activeLink = document.querySelector(`.nav-links a[href="#${sectionId}"]`);
                if (activeLink) {
                    activeLink.classList.add('active');
                    
                    // Animation d'activation
                    gsap.to(activeLink, {
                        scale: 1.05,
                        fontWeight: 700,
                        duration: 0.3,
                        ease: 'power2.out'
                    });
                }
            }
        });
    };
    
    // Mettre à jour les liens actifs lors du défilement
    window.addEventListener('scroll', updateActiveNavLink);
    
    // Mettre à jour les liens actifs au chargement de la page
    updateActiveNavLink();

    // Smooth scroll for navigation links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                window.scrollTo({
                    top: target.offsetTop - 80,
                    behavior: 'smooth'
                });
                
                // Mettre à jour les liens actifs après le clic
                navLinks.forEach(link => link.classList.remove('active'));
                this.classList.add('active');
            }
        });
    });

    // Intersection Observer for section animations
    const observerOptions = {
        threshold: 0.2
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('section').forEach(section => {
        observer.observe(section);
    });

    // Fonction pour faire défiler la page en douceur vers le haut
    const logoLink = document.querySelector('.logo-link');
    
    if (logoLink) {
        logoLink.addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    // Gestion de l'accordéon des mentions légales
    const accordionToggle = document.querySelector('.accordion-toggle');
    if (accordionToggle) {
        accordionToggle.addEventListener('click', function() {
            const expanded = this.getAttribute('aria-expanded') === 'true' || false;
            this.setAttribute('aria-expanded', !expanded);
        });
    }
});
