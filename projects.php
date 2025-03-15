<?php
require_once __DIR__ . '/admin/config.php';
require_once __DIR__ . '/admin/classes/Project.php';

// Initialiser la connexion à la base de données
try {
    $database = new Database();
    $db = $database->getConnection();
    $project = new Project($db);
    $projets = $project->read();
} catch (Exception $e) {
    error_log('Erreur lors de la récupération des projets: ' . $e->getMessage());
    $projets = [];
}
?>

<section id="projects" class="projects-section">
    <div class="container">
        <h2 data-lang="projects-title">Projets</h2>
        
        <div class="projects-carousel">
            <?php if (!empty($projets)): ?>
                <?php foreach ($projets as $projet): ?>
                    <div class="project-card">
                        <div class="project-image">
                            <?php if (!empty($projet['images'])): ?>
                                <img src="/v2/uploads/projects/<?php echo htmlspecialchars($projet['images']); ?>" 
                                     alt="<?php echo htmlspecialchars($projet['titre']); ?>">
                            <?php else: ?>
                                <div class="project-placeholder"></div>
                            <?php endif; ?>
                        </div>
                        <div class="project-info">
                            <h3><?php echo htmlspecialchars($projet['titre']); ?></h3>
                            <p><?php echo nl2br(htmlspecialchars($projet['description'])); ?></p>
                            <div class="project-links">
                                <?php if (!empty($projet['liens_site'])): ?>
                                    <a href="<?php echo htmlspecialchars($projet['liens_site']); ?>" class="btn-link" target="_blank">
                                        <span class="icon">🔗</span>
                                        <span data-lang="view-live">Voir le site</span>
                                    </a>
                                <?php endif; ?>
                                <?php if (!empty($projet['liens_github'])): ?>
                                    <a href="<?php echo htmlspecialchars($projet['liens_github']); ?>" class="btn-link" target="_blank">
                                        <span class="icon">💾</span>
                                        <span>GitHub</span>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-projects">Aucun projet n'est disponible pour le moment.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<style>
.projects-section {
    padding: 6rem 0;
}

.projects-section h2 {
    font-size: 2.5rem;
    margin-bottom: 3rem;
    text-align: center;
    background: linear-gradient(135deg, var(--primary-color), #00c6ff);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

.projects-carousel {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    padding: 1rem;
}

.project-card {
    background: var(--card-bg);
    border-radius: var(--border-radius);
    overflow: hidden;
    transition: transform var(--transition-speed);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.project-card:hover {
    transform: translateY(-10px);
}

.project-image {
    width: 100%;
    height: 200px;
    overflow: hidden;
    position: relative;
    background: linear-gradient(45deg, var(--secondary-bg), var(--background-color));
}

.project-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.project-card:hover .project-image img {
    transform: scale(1.05);
}

.project-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(45deg, var(--secondary-bg), var(--background-color));
    position: absolute;
    top: 0;
    left: 0;
}

.no-projects {
    text-align: center;
    padding: 2rem;
    color: var(--text-color);
    font-style: italic;
    grid-column: 1 / -1;
}

.project-info {
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    height: calc(100% - 200px); /* Hauteur totale moins la hauteur de l'image */
}

.project-info h3 {
    font-size: 1.5rem;
    margin-bottom: 1rem;
}

.project-info p {
    margin-bottom: 1.5rem;
    line-height: 1.6;
    flex-grow: 1; /* Permet à la description de prendre l'espace disponible */
}

.project-links {
    display: flex;
    gap: 1rem;
    margin-top: auto; /* Pousse les liens vers le bas */
}

.btn-link {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    text-decoration: none;
    color: var(--text-color);
    border: 1px solid var(--text-color);
    border-radius: var(--border-radius);
    transition: all var(--transition-speed);
}

.btn-link:hover {
    background: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
}

.icon {
    font-size: 1.2rem;
}

@media (max-width: 768px) {
    .projects-section h2 {
        font-size: 2rem;
    }
    
    .projects-carousel {
        grid-template-columns: 1fr;
    }
}
</style>
