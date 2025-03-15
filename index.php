<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Portfolio de Jason Bedulho - Développeur Web je suis actuellement en recherche d'alternance">
    <meta name="keywords" content="Alternance développement web Développeur web, Développement web front-end, Développement web back-end, Full-stack developer, Développeur front-end, Développeur back-end, Développement logiciel, Programmation web, Développeur JavaScript, Développeur PHP, Développeur React, Développeur Angular, Développeur Vue.js, Développeur Node.js, Développeur Python, Développeur HTML/CSS, Développeur WordPress, Développeur Django, Développeur Ruby on Rails, Développeur mobile (React Native, Flutter) HTML5, CSS3, JavaScript, TypeScript, React.js, Vue.js, Angular, Node.js, Express.js, Python, Django, Flask, PHP, Laravel, MySQL, MongoDB, PostgreSQL, APIs, REST, GraphQL, Git, GitHub, GitLab, Docker, CI/CD (Intégration continue, déploiement continu), UX/UI design, Responsive design, Web performance optimizatio Alternance Paris, Alternance Île-de-France, Stage développement web, Formation continue développement web, Étudiant développeur web, Recherche alternance Paris, Contrat d'alternance développeur, Apprentissage en développement web, Formation alternance développeur web, Développement web Paris, Alternance développement web Île-de-France, Stage développeur web Paris, Alternance Paris 2025, Développeur web à Paris, Développeur web Île-de-France, Alternance développeur web 75 (Paris), Développeur web Paris 2025">
    <meta name="theme-color" content="#000000">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Jason Bedulho">
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/assets/img/icons/icon-152x152.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/assets/img/icons/icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/img/icons/icon-192x192.png">
    <link rel="apple-touch-icon" sizes="167x167" href="/assets/img/icons/icon-152x152.png">
    <title>Jason Bedulho | Développeur Web & Designer</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/dark-mode.css">
    <link rel="stylesheet" href="assets/css/cursor.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r121/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.halo.min.js"></script>
</head>

<body class="light-mode">
    <nav class="main-nav">
        <div class="nav-container">
            <div class="nav-left">
                <div class="logo">
                    <a href="#" class="logo-link" title="Retour en haut de page">
                        <img src="assets/img/logo/logo.webp" alt="" class="logo-img">
                    </a>
                </div>
                <div class="nav-controls">
                    <button id="darkModeToggle" class="control-btn" aria-label="Toggle dark mode">
                        <i class="fas fa-moon dark-icon"></i>
                        <i class="fas fa-sun light-icon"></i>
                    </button>
                    <button id="langToggle" class="control-btn" aria-label="Toggle language">
                        <i class="fas fa-globe"></i>
                        <span class="lang-text">FR</span>
                    </button>
                    <button id="installPwa" class="control-btn" aria-label="Installer l'application" style="display: none;">
                        <i class="fas fa-download"></i>
                    </button>
                </div>
                <!-- Bouton menu burger pour mobile et tablette -->
                <button id="burgerMenu" class="burger-menu" aria-label="Toggle navigation menu" aria-expanded="false">
                    <span class="burger-line"></span>
                    <span class="burger-line"></span>
                    <span class="burger-line"></span>
                </button>
            </div>
            <ul class="nav-links">
                <li><a href="#about" data-lang="nav-about">À propos</a></li>
                <li><a href="#projects" data-lang="nav-projects">Projets</a></li>
                <li><a href="#skills" data-lang="nav-skills">Compétences</a></li>
                <li><a href="#contact" data-lang="nav-contact">Contact</a></li>
                <li><a href="CV.pdf" target="_blank" class="cv-btn" data-lang="nav-cv"><i class="fas fa-file-pdf"></i> CV</a></li>
            </ul>
        </div>
    </nav>

    <main>
        <section id="hero" class="hero-section">
            <h1 class="glassy-text">Jason Bedulho</h1>
            <p class="subtitle" data-lang="hero-subtitle">Développeur Web Recherche alternance</p>
            <div class="scroll-indicator">
                <span class="mouse"></span>
            </div>
        </section>

        <?php include 'about.php'; ?>
        <?php include 'projects.php'; ?>
        <?php include 'skills.php'; ?>
        <?php include 'contact.php'; ?>
    </main>

    <footer>
        <div class="container">
            <div class="footer-content">
                <p>&copy; <?php echo date('Y'); ?> Jason Bedulho. <span data-lang="footer-rights">Tous droits réservés</span>.</p>

                <div class="legal-accordion">
                    <button class="accordion-toggle" aria-expanded="false">
                        <span data-lang="legal-mentions">Mentions légales</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="accordion-content">
                        <div class="legal-content">
                            <h3 data-lang="legal-title">Mentions légales</h3>
                            <p><strong data-lang="legal-owner">Propriétaire du site :</strong> Jason Bedulho</p>
                            <p><strong data-lang="legal-contact">Contact :</strong> <a href="mailto:jason.bedulho99@gmail.com">jason.bedulho99@gmail.com</a></p>
                            <p><strong data-lang="legal-host">Hébergeur :</strong> o2switch - 222 Boulevard Gustave Flaubert, 63000 Clermont-Ferrand, France</p>
                            <p><strong data-lang="legal-siret">SIRET o2switch :</strong> 510 909 80700024</p>

                            <h4 data-lang="legal-ip-title">Propriété intellectuelle</h4>
                            <p data-lang="legal-ip-content">L'ensemble du contenu de ce site (textes, images, design) est la propriété exclusive de Jason Bedulho ou fait l'objet d'une autorisation d'utilisation. Toute reproduction ou représentation, intégrale ou partielle, est interdite sans autorisation.</p>

                            <h4 data-lang="legal-data-title">Données personnelles</h4>
                            <p data-lang="legal-data-content">Conformément au Règlement Général sur la Protection des Données (RGPD), vous disposez d'un droit d'accès, de rectification et de suppression de vos données. Pour exercer ce droit, veuillez me contacter par email.</p>

                            <h4 data-lang="legal-cookies-title">Cookies</h4>
                            <p data-lang="legal-cookies-content">Ce site utilise des cookies pour améliorer l'expérience utilisateur. En naviguant sur ce site, vous acceptez l'utilisation de ces cookies.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let vantaEffect = null;

            const initVanta = () => {
                // Détruire l'effet précédent s'il existe
                if (vantaEffect) {
                    vantaEffect.destroy();
                }

                // Déterminer la couleur de fond en fonction du mode
                const isDarkMode = document.body.classList.contains('dark-mode');
                const backgroundColor = isDarkMode ? 0x000000 : 0xdedede;

                // Initialiser l'effet Vanta
                if (window.VANTA) {
                    vantaEffect = VANTA.HALO({
                        el: ".hero-section",
                        mouseControls: true,
                        touchControls: true,
                        gyroControls: false,
                        minHeight: 200.00,
                        minWidth: 200.00,
                        backgroundColor: backgroundColor,
                        amplitudeFactor: 0.00,
                        size: 2.00
                    });
                }
            };

            // Vérifier si le mode sombre est déjà activé avant d'initialiser Vanta
            const checkDarkModeAndInit = () => {
                // Toujours s'assurer que le mode clair est activé
                document.body.classList.remove('dark-mode');

                // Initialiser Vanta avec la bonne couleur de fond
                initVanta();
            };

            // S'assurer que le DOM est complètement chargé avant d'initialiser Vanta
            if (document.readyState === 'complete' || document.readyState === 'interactive') {
                checkDarkModeAndInit();
            } else {
                window.addEventListener('DOMContentLoaded', checkDarkModeAndInit);
            }

            // Mettre à jour l'animation lors du changement de mode
            document.addEventListener('darkModeChanged', () => {
                // Appliquer immédiatement le changement de couleur de fond
                initVanta();
            });

            // Garder l'écouteur d'événement sur le bouton pour la compatibilité
            const darkModeToggle = document.getElementById('darkModeToggle');
            darkModeToggle.addEventListener('click', () => {
                // L'événement darkModeChanged sera déclenché par dark-mode.js
            });
        });
    </script>

<script src="assets/js/script.js"></script>
<script src="assets/js/dark-mode.js"></script>
<script src="assets/js/multi-lang.js"></script>
<script src="assets/js/pwa.js"></script>
<script src="assets/js/custom-cursor.js"></script>
</body>

</html>