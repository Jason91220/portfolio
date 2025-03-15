<?php
// Inclure le fichier de vérification d'authentification
require_once 'auth_check.php';

// Inclure les fichiers nécessaires
require_once 'config.php';
require_once 'classes/User.php';
require_once 'classes/Message.php';

// Créer une instance de Database
$database = new Database();
$db = $database->getConnection();

// Créer une instance de Message
$message = new Message($db);

// Traitement de la suppression d'un message
if(isset($_POST['delete_message']) && isset($_POST['message_id']) && isset($_POST['csrf_token'])) {
    // Vérifier le jeton CSRF
    if(User::verifyCsrfToken($_POST['csrf_token'])) {
        $message_id = filter_var($_POST['message_id'], FILTER_VALIDATE_INT);
        
        if($message_id && $message->delete($message_id)) {
            $success_message = "Le message a été supprimé avec succès.";
        } else {
            $error_message = "Une erreur est survenue lors de la suppression du message.";
        }
    } else {
        $error_message = "Erreur de sécurité. Veuillez réessayer.";
    }
}

// Inclure l'en-tête
include 'includes/header.php';
?>

<div class="content-header">
    <h1>Messages</h1>
</div>

<?php if(isset($success_message)): ?>
    <div class="alert alert-success"><?php echo $success_message; ?></div>
<?php endif; ?>

<?php if(isset($error_message)): ?>
    <div class="alert alert-danger"><?php echo $error_message; ?></div>
<?php endif; ?>

<!-- Liste des messages -->
<div class="card">
    <div class="card-header">
        <h2>Liste des messages</h2>
    </div>
    
    <div class="table-responsive">
        <table class="messages-table">
            <thead>
                <tr>
                    <th class="status-column">État</th>
                    <th class="name-column">Nom</th>
                    <th class="email-column">Email</th>
                    <th class="subject-column">Sujet</th>
                    <th class="date-column">Date</th>
                    <th class="actions-column">Actions</th>
                </tr>
            </thead>
            <tbody id="messagesTable">
                <?php
                // Vérifier la connexion à la base de données
                if ($db === null) {
                    echo "<tr><td colspan='6' style='text-align: center; color: red;'>Erreur: Connexion à la base de données échouée</td></tr>";
                } else {
                    // Récupérer tous les messages
                    $stmt = $message->getAll();
                    
                    if ($stmt === false) {
                        echo "<tr><td colspan='6' style='text-align: center; color: red;'>Erreur lors de la récupération des messages. Vérifiez les journaux d'erreurs.</td></tr>";
                    } else {
                        $count = 0;
                        
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            // Afficher les données de débogage dans les commentaires HTML
                            echo "<!-- Message ID: " . ($row['id'] ?? 'N/A') . " -->";
                            
                            echo "<tr data-id='" . ($row['id'] ?? 0) . "'>";
                            echo "<td><span class='status-indicator " . (isset($row['is_read']) && $row['is_read'] ? 'status-read' : 'status-unread') . "'></span>" . 
                                 (isset($row['is_read']) && $row['is_read'] ? 'Lu' : 'Non lu') . "</td>";
                            echo "<td>" . htmlspecialchars($row['name'] ?? 'Non spécifié') . "</td>";
                            echo "<td><a href='mailto:" . htmlspecialchars($row['email'] ?? '') . "'>" . htmlspecialchars($row['email'] ?? 'Non spécifié') . "</a></td>";
                            echo "<td>" . htmlspecialchars($row['subject'] ?? 'Sans sujet') . "</td>";
                            echo "<td>" . (isset($row['created_at']) && $row['created_at'] ? date('d/m/Y H:i', strtotime($row['created_at'])) : 'Date inconnue') . "</td>";
                            echo "<td>";
                            echo "<a href='view_message.php?id=" . ($row['id'] ?? 0) . "' class='btn btn-sm btn-primary'><i class='fas fa-eye'></i> Voir</a> ";
                            echo "<button type='button' class='btn btn-sm btn-danger delete-message' data-id='" . ($row['id'] ?? 0) . "'><i class='fas fa-trash'></i> Supprimer</button>";
                            echo "</td>";
                            echo "</tr>";
                            
                            $count++;
                        }
                    }
                }
                
                if ($count == 0) {
                    echo "<tr><td colspan='6' style='text-align: center;'>Aucun message</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Formulaire de suppression caché -->
<form id="deleteForm" method="POST" style="display: none;">
    <input type="hidden" name="delete_message" value="1">
    <input type="hidden" name="message_id" id="delete_message_id">
    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gestion de la suppression des messages
        const deleteButtons = document.querySelectorAll('.delete-message');
        const deleteForm = document.getElementById('deleteForm');
        const deleteMessageIdInput = document.getElementById('delete_message_id');
        
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const messageId = this.getAttribute('data-id');
                
                // Utiliser la fonction de confirmation définie dans footer.php
                confirmAction('Êtes-vous sûr de vouloir supprimer ce message ?', function() {
                    deleteMessageIdInput.value = messageId;
                    deleteForm.submit();
                });
            });
        });
        
        // Actualisation en temps réel des messages
        function updateMessages() {
            fetch('ajax/get_messages.php')
                .then(response => response.json())
                .then(data => {
                    const messagesTable = document.getElementById('messagesTable');
                    
                    // Vérifier si des messages ont été modifiés
                    data.forEach(message => {
                        const row = document.querySelector(`tr[data-id="${message.id}"]`);
                        
                        if (row) {
                            // Mettre à jour l'état du message
                            const statusCell = row.querySelector('td:first-child');
                            statusCell.innerHTML = `<span class='status-indicator ${message.is_read ? 'status-read' : 'status-unread'}'></span>${message.is_read ? 'Lu' : 'Non lu'}`;
                        }
                    });
                })
                .catch(error => console.error('Erreur lors de la mise à jour des messages:', error));
        }
        
        // Mettre à jour les messages toutes les 30 secondes
        setInterval(updateMessages, 30000);
    });
</script>

<style>
    /* Styles responsive pour la page des messages */
    .messages-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .status-indicator {
        display: inline-block;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        margin-right: 5px;
    }
    
    .status-read {
        background-color: #28a745;
    }
    
    .status-unread {
        background-color: #ffc107;
    }
    
    /* Responsive styles */
    @media (max-width: 1024px) {
        .email-column {
            max-width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .subject-column {
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .content-header {
            margin-bottom: 1.5rem;
        }
        
        .card {
            margin-bottom: 1.5rem;
        }
    }
    
    @media (max-width: 768px) {
        .messages-table {
            min-width: 650px;
        }
        
        .status-column {
            width: 80px;
        }
        
        .name-column, .email-column {
            max-width: 120px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .subject-column {
            max-width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .date-column {
            width: 100px;
        }
        
        .actions-column {
            width: 150px;
        }
        
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin: 0 -1rem;
            padding: 0 1rem;
            width: calc(100% + 2rem);
        }
        
        .btn-sm {
            padding: 0.3rem 0.5rem;
            font-size: 0.8rem;
        }
        
        .delete-message {
            margin-top: 0.25rem;
        }
    }
    
    @media (max-width: 480px) {
        .card {
            padding: 0.75rem;
        }
        
        .card-header {
            padding: 0.75rem;
            flex-direction: column;
            align-items: flex-start;
        }
        
        .card-header h2 {
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
        }
        
        .btn-sm {
            padding: 0.2rem 0.4rem;
            font-size: 0.75rem;
        }
        
        .messages-table td, .messages-table th {
            font-size: 0.85rem;
            padding: 0.5rem 0.4rem;
        }
        
        .status-indicator {
            width: 8px;
            height: 8px;
        }
        
        .actions-column {
            width: 120px;
        }
        
        .delete-message, .btn-sm {
            display: inline-block;
            width: 100%;
            text-align: center;
            margin-bottom: 0.25rem;
        }
        
        .table-responsive {
            margin: 0 -0.75rem;
            padding: 0 0.75rem;
            width: calc(100% + 1.5rem);
        }
    }
</style>

<?php
// Inclure le pied de page
include 'includes/footer.php';
?>
