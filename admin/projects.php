<?php
require_once __DIR__ . '/debug.php';

$message = '';
$error = '';
$projets = [];
$project = null;

try {
    error_log('Démarrage de la page projects.php');
    require_once __DIR__ . '/auth_check.php';
    error_log('Auth check OK');
    require_once __DIR__ . '/config.php';
    error_log('Config chargée');
    
    // Créer une instance de Database
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        throw new Exception('La connexion à la base de données a échoué');
    }
    
    require_once __DIR__ . '/classes/Project.php';

    error_log('Tentative de création de l\'instance Project');
    $project = new Project($db);
    error_log('Instance Project créée avec succès');
    
    // Récupération de tous les projets
    $projets = $project->read();
    
} catch (Exception $e) {
    error_log('Erreur dans projects.php: ' . $e->getMessage());
    $error = 'Une erreur est survenue lors de l\'initialisation de la page: ' . $e->getMessage();
}

// Traitement de l'ajout/modification de projet
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $project !== null) {
    try {
        error_log('Début du traitement du formulaire POST');
        error_log('Données reçues : ' . print_r($_POST, true));
        
        $project->setTitre($_POST['titre']);
        error_log('Titre défini : ' . $_POST['titre']);
        
        $project->setDescription($_POST['description']);
        error_log('Description définie : ' . $_POST['description']);
        
        $project->setLiensSite($_POST['liens_site']);
        error_log('Lien site défini : ' . $_POST['liens_site']);
        
        $project->setLiensGithub($_POST['liens_github']);
        error_log('Lien GitHub défini : ' . $_POST['liens_github']);

        // Gestion de l'upload d'image
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            error_log('Upload d\'image détecté');
            $imageName = $project->uploadImage($_FILES['image']);
            error_log('Image uploadée avec succès : ' . $imageName);
            $project->setImages($imageName);
        } else if (isset($_FILES['image'])) {
            error_log('Erreur upload image : ' . $_FILES['image']['error']);
        }

        if (isset($_POST['id']) && !empty($_POST['id'])) {
            error_log('Tentative de modification du projet ID: ' . $_POST['id']);
            // Modification
            if ($project->update($_POST['id'])) {
                error_log('Projet modifié avec succès');
                $message = "Projet modifié avec succès!";
            } else {
                error_log('Erreur lors de la modification du projet');
                throw new Exception("Erreur lors de la modification du projet");
            }
        } else {
            error_log('Tentative de création d\'un nouveau projet');
            // Ajout
            if ($project->create()) {
                error_log('Projet créé avec succès');
                $message = "Projet ajouté avec succès!";
            } else {
                error_log('Erreur lors de la création du projet');
                throw new Exception("Erreur lors de la création du projet");
            }
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Suppression d'un projet
if (isset($_GET['delete']) && !empty($_GET['delete']) && $project !== null) {
    try {
        if ($project->delete($_GET['delete'])) {
            $message = "Projet supprimé avec succès!";
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

include 'includes/header.php';
?>

<style>
    .project-form-header {
        background: linear-gradient(135deg, #2193b0, #6dd5ed);
        color: white;
        padding: 1.5rem;
        border-radius: 8px 8px 0 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .project-form-header i {
        font-size: 1.5rem;
    }

    .project-card {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border: none;
        border-radius: 8px;
        margin-bottom: 2rem;
    }

    .project-card .card-body {
        padding: 2rem;
    }

    .form-label {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }

    .form-control {
        border: 2px solid #e9ecef;
        border-radius: 6px;
        padding: 0.75rem;
        transition: all 0.3s ease;
        font-size: 0.95rem;
    }

    .input-group-text {
        background: #f8f9fa;
        border: 2px solid #e9ecef;
        border-left: none;
        color: #6c757d;
    }

    .form-control:focus + .input-group-text {
        border-color: #2193b0;
    }

    textarea.form-control {
        min-height: 120px;
    }

    .text-muted {
        font-size: 0.85rem;
        margin-top: 0.25rem;
    }

    .form-control:focus {
        border-color: #2193b0;
        box-shadow: 0 0 0 0.2rem rgba(33, 147, 176, 0.25);
    }

    .btn-primary {
        background: linear-gradient(135deg, #2193b0, #6dd5ed);
        border: none;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(33, 147, 176, 0.3);
    }

    .btn-secondary {
        background: #e9ecef;
        color: #2c3e50;
        border: none;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-secondary:hover {
        background: #dee2e6;
        transform: translateY(-2px);
    }

    #imagePreview {
        max-width: 200px;
        border-radius: 4px;
        overflow: hidden;
    }

    #imagePreview img {
        width: 100%;
        height: auto;
        display: block;
    }

    .required-field::after {
        content: ' *';
        color: #e74c3c;
    }

    .form-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    .projects-table {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .projects-table th {
        background: linear-gradient(135deg, #2193b0, #6dd5ed);
        color: white;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.9rem;
        padding: 1rem;
    }

    .projects-table td {
        padding: 1rem;
        vertical-align: middle;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .btn-edit, .btn-delete {
        padding: 0.5rem 1rem;
        border-radius: 4px;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
    }

    .btn-edit {
        background: #3498db;
        color: white;
    }

    .btn-delete {
        background: #e74c3c;
        color: white;
    }
</style>

<div class="container-fluid px-4">
    <h1 class="mt-4">Gestion des Projets</h1>
    
    <?php if ($message): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Succès !',
                    text: '<?php echo addslashes($message); ?>',
                    icon: 'success',
                    timer: 3000,
                    showConfirmButton: false
                });
            });
        </script>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Erreur !',
                    text: '<?php echo addslashes($error); ?>',
                    icon: 'error'
                });
            });
        </script>
    <?php endif; ?>

    <!-- Formulaire d'ajout/modification -->
    <div class="card project-card mb-4">
        <div class="project-form-header">
            <i class="fas fa-plus me-1"></i>
            <h2 class="mb-0 h5">Ajouter un nouveau projet</h2>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data" id="projectForm">
                <input type="hidden" name="id" id="projectId">
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="titre" class="form-label required-field">Titre du projet</label>
                        <input type="text" class="form-control" id="titre" name="titre" required 
                               placeholder="Entrez le titre du projet">
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="image" class="form-label">Image du projet</label>
                        <div class="input-group">
                            <input type="file" class="form-control" id="image" name="image" 
                                   accept="image/*">
                            <label class="input-group-text" for="image">
                                <i class="fas fa-image"></i>
                            </label>
                        </div>
                        <div id="imagePreview" class="mt-2"></div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label required-field">Description</label>
                    <textarea class="form-control" id="description" name="description" 
                              rows="4" required placeholder="Décrivez votre projet"></textarea>
                    <small class="text-muted">Décrivez votre projet en détail</small>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="liens_site" class="form-label">Lien du site</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-link"></i>
                            </span>
                            <input type="url" class="form-control" id="liens_site" name="liens_site"
                                   placeholder="https://votre-site.com">
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="liens_github" class="form-label">Lien GitHub</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fab fa-github"></i>
                            </span>
                            <input type="url" class="form-control" id="liens_github" name="liens_github"
                                   placeholder="https://github.com/votre-projet">
                        </div>
                    </div>
                </div>
                
                <div class="form-buttons">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Enregistrer
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="resetForm()">
                        <i class="fas fa-plus me-2"></i>Nouveau projet
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des projets -->
    <div class="card project-card mb-4">
        <div class="project-form-header">
            <i class="fas fa-table me-1"></i>
            <h2 class="mb-0 h5">Liste des projets</h2>
        </div>
        <div class="card-body">
            <table id="projectsTable" class="table table-striped table-bordered projects-table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Titre</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($projets as $proj): ?>
                        <tr>
                            <td>
                                <?php if ($proj['images']): ?>
                                    <img src="/v2/uploads/projects/<?php echo htmlspecialchars($proj['images']); ?>" 
                                         alt="<?php echo htmlspecialchars($proj['titre']); ?>" 
                                         style="max-width: 100px;">
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($proj['titre']); ?></td>
                            <td><?php echo nl2br(htmlspecialchars($proj['description'])); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-edit" onclick="editProject(<?php echo htmlspecialchars(json_encode($proj)); ?>)">
                                        <i class="fas fa-edit"></i> Modifier
                                    </button>
                                    <button class="btn-delete" onclick="deleteProject(<?php echo $proj['id']; ?>)">
                                        <i class="fas fa-trash"></i> Supprimer
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialisation de DataTables
    $('#projectsTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/fr-FR.json'
        },
        order: [[1, 'asc']],
        columnDefs: [
            { orderable: false, targets: [0, 3] }
        ],
        pageLength: 10,
        responsive: true
    });

    // Prévisualisation de l'image
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');

    imageInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.innerHTML = `
                    <div class="position-relative mt-3">
                        <img src="${e.target.result}" alt="Prévisualisation" class="img-thumbnail">
                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1" 
                                onclick="clearImagePreview()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>`;
            };
            reader.readAsDataURL(file);
        }
    });

    // Animation des champs de formulaire
    const formInputs = document.querySelectorAll('.form-control');
    formInputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.closest('.mb-3').classList.add('focused');
        });
        input.addEventListener('blur', function() {
            if (!this.value) {
                this.closest('.mb-3').classList.remove('focused');
            }
        });
        // Initialiser l'état focused si le champ a une valeur
        if (input.value) {
            input.closest('.mb-3').classList.add('focused');
        }
    });

    // Validation du formulaire
    document.getElementById('projectForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const titre = document.getElementById('titre').value.trim();
        const description = document.getElementById('description').value.trim();

        if (!titre || !description) {
            Swal.fire({
                title: 'Attention !',
                text: 'Veuillez remplir tous les champs obligatoires.',
                icon: 'warning'
            });
            return;
        }

        this.submit();
    });
});

function clearImagePreview() {
    document.getElementById('image').value = '';
    document.getElementById('imagePreview').innerHTML = '';
}

function resetForm() {
    const form = document.getElementById('projectForm');
    form.reset();
    document.getElementById('projectId').value = '';
    document.getElementById('imagePreview').innerHTML = '';
    
    // Animation de reset
    form.classList.add('form-reset');
    setTimeout(() => form.classList.remove('form-reset'), 500);

    // Réinitialiser les classes focused
    document.querySelectorAll('.mb-3.focused').forEach(el => {
        el.classList.remove('focused');
    });

    // Scroll en douceur vers le formulaire
    document.querySelector('.project-card').scrollIntoView({ behavior: 'smooth' });
}

function editProject(project) {
    // Animation de transition
    const form = document.getElementById('projectForm');
    form.classList.add('form-loading');

    // Scroll en douceur vers le formulaire
    document.querySelector('.project-card').scrollIntoView({ behavior: 'smooth' });

    setTimeout(() => {
        document.getElementById('projectId').value = project.id;
        document.getElementById('titre').value = project.titre;
        document.getElementById('description').value = project.description;
        document.getElementById('liens_site').value = project.liens_site || '';
        document.getElementById('liens_github').value = project.liens_github || '';
        
        if (project.images) {
            document.getElementById('imagePreview').innerHTML = `
                <div class="position-relative mt-3">
                    <img src="/v2/uploads/projects/${project.images}" 
                         alt="${project.titre}" 
                         class="img-thumbnail">
                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1" 
                            onclick="clearImagePreview()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>`;
        }

        // Ajouter la classe focused aux champs remplis
        document.querySelectorAll('.form-control').forEach(input => {
            if (input.value) {
                input.closest('.mb-3').classList.add('focused');
            } else {
                input.closest('.mb-3').classList.remove('focused');
            }
        });

        form.classList.remove('form-loading');
    }, 300);
}

function deleteProject(id) {
    Swal.fire({
        title: 'Êtes-vous sûr ?',
        text: 'Cette action est irréversible !',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e74c3c',
        cancelButtonColor: '#7f8c8d',
        confirmButtonText: 'Oui, supprimer',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `?delete=${id}`;
        }
    });
}
</script>

<style>
    .form-reset {
        animation: formReset 0.5s ease;
    }

    .form-loading {
        opacity: 0.6;
        pointer-events: none;
        transition: opacity 0.3s ease;
    }

    .mb-3 {
        position: relative;
        transition: transform 0.3s ease;
    }

    .mb-3.focused {
        transform: translateX(5px);
    }

    @keyframes formReset {
        0% { transform: scale(1); }
        50% { transform: scale(0.98); }
        100% { transform: scale(1); }
    }

    .img-thumbnail {
        max-width: 200px;
        height: auto;
        transition: transform 0.3s ease;
    }

    .img-thumbnail:hover {
        transform: scale(1.05);
    }
</style>

<?php include 'includes/footer.php'; ?>
