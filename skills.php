<section id="skills" class="skills-section">
    <div class="container">
        <h2 data-lang="skills-title">Compétences</h2>
        
        <div class="skills-grid">
            <div class="skills-category">
                <h3 data-lang="dev-skills">Développement Web</h3>
                <div class="skills-cards">
                    <div class="skill-card">
                        <div class="skill-icon">
                            <i class="fab fa-html5"></i>
                        </div>
                        <h4>HTML</h4>
                        <p data-lang="html-desc">Structuration sémantique et accessible du contenu web</p>
                    </div>
                    <div class="skill-card">
                        <div class="skill-icon">
                            <i class="fab fa-css3-alt"></i>
                        </div>
                        <h4>CSS</h4>
                        <p data-lang="css-desc">Stylisation avancée et animations fluides</p>
                    </div>
                    <div class="skill-card">
                        <div class="skill-icon">
                            <i class="fab fa-js"></i>
                        </div>
                        <h4>JavaScript</h4>
                        <p data-lang="js-desc">Interactions dynamiques et expérience utilisateur</p>
                    </div>
                    <div class="skill-card">
                        <div class="skill-icon">
                            <i class="fab fa-php"></i>
                        </div>
                        <h4>PHP</h4>
                        <p data-lang="php-desc">Développement backend orienté objet</p>
                    </div>
                    <div class="skill-card">
                        <div class="skill-icon">
                            <i class="fas fa-database"></i>
                        </div>
                        <h4>SQL</h4>
                        <p data-lang="sql-desc">Gestion et optimisation des bases de données</p>
                    </div>
                    <div class="skill-card">
                        <div class="skill-icon">
                            <i class="fab fa-react"></i>
                        </div>
                        <h4>React.js</h4>
                        <p data-lang="react-desc">Création d'interfaces utilisateur modernes</p>
                    </div>
                    <div class="skill-card">
                        <div class="skill-icon">
                            <i class="fab fa-vuejs"></i>
                        </div>
                        <h4>Vue.js</h4>
                        <p data-lang="vue-desc">Applications web réactives et performantes</p>
                    </div>
                    <div class="skill-card">
                        <div class="skill-icon">
                            <i class="fab fa-symfony"></i>
                        </div>
                        <h4>Symfony</h4>
                        <p data-lang="symfony-desc">Framework PHP robuste pour applications complexes</p>
                    </div>
                    <div class="skill-card">
                        <div class="skill-icon">
                            <i class="fab fa-python"></i>
                        </div>
                        <h4>Python</h4>
                        <p data-lang="python-desc">Développement polyvalent et analyse de données</p>
                    </div>
                    <div class="skill-card">
                        <div class="skill-icon">
                            <i class="fab fa-wordpress"></i>
                        </div>
                        <h4>WordPress</h4>
                        <p data-lang="wordpress-desc">Création de sites et personnalisation avancée</p>
                    </div>
                    <div class="skill-card">
                        <div class="skill-icon">
                            <i class="fab fa-swift"></i>
                        </div>
                        <h4>Swift</h4>
                        <p data-lang="swift-desc">Développement d'applications iOS</p>
                    </div>

                </div>
            </div>

            <div class="skills-category">
                <h3 data-lang="dev-tools">Outils de développement</h3>
                <div class="skills-cards">
                    <div class="skill-card">
                        <div class="skill-icon">
                            <i class="fab fa-github"></i>
                        </div>
                        <h4>GitHub</h4>
                        <p data-lang="github-desc">Gestion de projets et collaboration</p>
                    </div>
                    <div class="skill-card">
                        <div class="skill-icon">
                            <i class="fab fa-git-alt"></i>
                        </div>
                        <h4>Git</h4>
                        <p data-lang="git-desc">Versionnement et gestion de code source</p>
                    </div>
                    <div class="skill-card">
                        <div class="skill-icon">
                            <i class="fas fa-terminal"></i>
                        </div>
                        <h4>Ligne de commande</h4>
                        <p data-lang="cmd-desc">Automatisation et gestion système</p>
                    </div>
                </div>
            </div>

            <div class="skills-category">
                <h3 data-lang="design-skills">Design</h3>
                <div class="skills-cards">
                    <div class="skill-card">
                        <div class="skill-icon">
                            <i class="fas fa-bezier-curve"></i>
                        </div>
                        <h4>Illustrator</h4>
                        <p data-lang="illustrator-desc">Création de visuels vectoriels</p>
                    </div>
                    <div class="skill-card">
                        <div class="skill-icon">
                            <i class="fas fa-image"></i>
                        </div>
                        <h4>Photoshop</h4>
                        <p data-lang="photoshop-desc">Retouche d'image et webdesign</p>
                    </div>
                    <div class="skill-card">
                        <div class="skill-icon">
                            <i class="fab fa-figma"></i>
                        </div>
                        <h4>Figma</h4>
                        <p data-lang="figma-desc">Conception d'interfaces et prototypage</p>
                    </div>
                    <div class="skill-card">
                        <div class="skill-icon">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <h4>InDesign</h4>
                        <p data-lang="indesign-desc">Mise en page professionnelle</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.skills-section {
    padding: 6rem 0;
    background: var(--secondary-bg);
}

.skills-section h2 {
    font-size: 2.5rem;
    margin-bottom: 3rem;
    text-align: center;
    background: linear-gradient(135deg, var(--primary-color), #00c6ff);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

.skills-category {
    margin-bottom: 4rem;
}

.skills-category h3 {
    font-size: 1.8rem;
    margin-bottom: 2rem;
    color: var(--text-color);
}

.skills-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
}

.skill-card {
    background: var(--card-bg);
    border-radius: var(--border-radius);
    padding: 2rem;
    text-align: center;
    transition: transform var(--transition-speed);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.skill-card:hover {
    transform: translateY(-5px);
}

.skill-icon {
    height: 60px;
    width: 60px;
    margin: 0 auto 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    padding: 12px;
    background: var(--background-color);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.tech-logo {
    width: 100%;
    height: 100%;
    object-fit: contain;
    transition: all var(--transition-speed);
}

.skill-card {
    position: relative;
    overflow: hidden;
}

.skill-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--primary-color), #00c6ff);
    transform: translateX(-100%);
    transition: transform var(--transition-speed);
}

.skill-card:hover::before {
    transform: translateX(0);
}

.skill-card:hover .tech-logo {
    transform: scale(1.1) rotate(5deg);
}

.skill-card h4 {
    font-size: 1.2rem;
    margin-bottom: 1rem;
    color: var(--text-color);
}

.skill-card p {
    font-size: 0.9rem;
    color: var(--text-color);
    opacity: 0.8;
}

@media (max-width: 768px) {
    .skills-section h2 {
        font-size: 2rem;
    }
    
    .skills-category h3 {
        font-size: 1.5rem;
    }
    
    .skills-cards {
        grid-template-columns: 1fr;
    }
}
</style>
