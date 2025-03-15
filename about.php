<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<section id="about" class="about-section">
    <div class="container">
        <div class="about-content">
            <div class="about-text">
                <h2 data-lang="about-title">À propos</h2>
                <p data-lang="about-intro">
                    Bonjour, je suis Jason Bedulho, 25 ans, étudiant en Bachelor 2ème année Développeur Web & Concepteur d'Application à Digital Campus Paris. Passionné par le développement web et le design, je combine créativité et expertise technique pour créer des expériences numériques exceptionnelles.
                </p>
                <p data-lang="about-goal">
                    Actuellement à la recherche d'une alternance en développement web, je souhaite mettre mes compétences au service d'une entreprise innovante tout en continuant à développer mon expertise.
                </p>
            </div>
            <div class="about-image">
                
                <img src="assets/img/avatar/avatar.webp" alt="Jason Bedulho" class="profile-image">
           
        </div>
        </div>
        
        <div class="quick-skills">
            <h3 data-lang="skills-title">Technologies maîtrisées</h3>
            <div class="skills-container">
                <div class="skills-scroll logos">
                    <div class="scroll-content">
                        <div class="tech-item"><i class="fab fa-python"></i></div>
                        <div class="tech-item"><i class="fab fa-vuejs"></i></div>
                        <div class="tech-item"><i class="fab fa-wordpress"></i></div>
                        <div class="tech-item"><i class="fas fa-database"></i></div>
                        <div class="tech-item"><i class="fab fa-react"></i></div>
                        <div class="tech-item"><i class="fab fa-html5"></i></div>
                        <div class="tech-item"><i class="fab fa-css3-alt"></i></div>
                        <div class="tech-item"><i class="fab fa-js"></i></div>
                        <!-- Duplicate for infinite scroll -->
                        <div class="tech-item"><i class="fab fa-python"></i></div>
                        <div class="tech-item"><i class="fab fa-vuejs"></i></div>
                        <div class="tech-item"><i class="fab fa-wordpress"></i></div>
                        <div class="tech-item"><i class="fas fa-database"></i></div>
                        <div class="tech-item"><i class="fab fa-react"></i></div>
                        <div class="tech-item"><i class="fab fa-html5"></i></div>
                        <div class="tech-item"><i class="fab fa-css3-alt"></i></div>
                        <div class="tech-item"><i class="fab fa-js"></i></div>
                    </div>
                </div>
                <div class="skills-scroll names">
                    <div class="scroll-content">
                        <span>Python</span>
                        <span>Vue.js</span>
                        <span>WordPress</span>
                        <span>MySQL</span>
                        <span>React</span>
                        <span>HTML5</span>
                        <span>CSS3</span>
                        <span>JavaScript</span>
                        <!-- Duplicate for infinite scroll -->
                        <span>Python</span>
                        <span>Vue.js</span>
                        <span>WordPress</span>
                        <span>MySQL</span>
                        <span>React</span>
                        <span>HTML5</span>
                        <span>CSS3</span>
                        <span>JavaScript</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.about-section {
    padding: 6rem 0;
    background: var(--secondary-bg);
}

.about-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    margin-bottom: 3rem;
}

.about-text h2 {
    font-size: 2.5rem;
    margin-bottom: 2rem;
    background: linear-gradient(135deg, var(--primary-color), #00c6ff);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

.about-text p {
    margin-bottom: 1.5rem;
    font-size: 1.1rem;
    line-height: 1.8;
}

.profile-image-container {
    width: 100%;
    height: 400px;
    border-radius: var(--border-radius);
    overflow: hidden;
    background: var(--card-bg);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
}

.profile-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(45deg, #f3f3f3, #e1e1e1);
}

.quick-skills {
    margin-top: 4rem;
}

.quick-skills h3 {
    font-size: 1.8rem;
    margin-bottom: 2rem;
    text-align: center;
}

.skills-container {
    display: flex;
    flex-direction: column;
    gap: 2rem;
    overflow: hidden;
    padding: 2rem 0;
}

.skills-scroll {
    position: relative;
    width: 100%;
    height: 80px;
    overflow: hidden;
}

.scroll-content {
    display: flex;
    position: absolute;
    animation: scroll 20s linear infinite;
    gap: 4rem;
    align-items: center;
}

.skills-scroll.logos .scroll-content {
    animation-direction: normal;
}

.skills-scroll.names .scroll-content {
    animation-direction: reverse;
}

.tech-item {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 60px;
    height: 60px;
    background: var(--card-bg);
    border-radius: 50%;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.tech-item i {
    font-size: 30px;
    color: var(--primary-color);
    transition: transform 0.3s ease;
}

.tech-item:hover i {
    transform: scale(1.2);
}

.skills-scroll.names span {
    font-size: 1.2rem;
    color: var(--text-color);
    white-space: nowrap;
    padding: 0.5rem 1rem;
    background: var(--card-bg);
    border-radius: 20px;
    backdrop-filter: blur(5px);
    -webkit-backdrop-filter: blur(5px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

@keyframes scroll {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(calc(-50% - 2rem));
    }
}

.skills-scroll:hover .scroll-content {
    animation-play-state: paused;
}

@media (max-width: 768px) {
    .about-content {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .about-text h2 {
        font-size: 2rem;
    }
    
    .profile-image-container {
        height: 300px;
    }
}
</style>
