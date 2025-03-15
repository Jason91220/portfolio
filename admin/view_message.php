<?php
// Inclure le fichier de vérification d'authentification
require_once 'auth_check.php';

// Inclure les fichiers nécessaires
require_once 'config.php';
require_once 'classes/User.php';
require_once 'classes/Message.php';

// Vérifier si l'ID du message est fourni
if(!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: messages.php");
    exit;
}

// Récupérer l'ID du message et le valider
$message_id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
if(!$message_id) {
    header("Location: messages.php");
    exit;
}

// Créer une instance de Database et vérifier la connexion
$database = new Database();
$db = $database->getConnection();
if (!$db) {
    die("Erreur de connexion à la base de données");
}

// Vérifier si la table messages existe
try {
    $checkTable = $db->query("SHOW TABLES LIKE 'messages'");
    if ($checkTable->rowCount() == 0) {
        die("La table 'messages' n'existe pas dans la base de données");
    }
} catch (PDOException $e) {
    die("Erreur lors de la vérification de la table: " . $e->getMessage());
}

// Récupérer directement le message depuis la base de données
try {
    // Afficher l'ID du message pour débogage
    echo "<!-- Débogage: ID du message = $message_id -->";
    
    // Vérifier si des messages existent dans la table
    $countQuery = "SELECT COUNT(*) FROM messages";
    $countStmt = $db->query($countQuery);
    $messageCount = $countStmt->fetchColumn();
    echo "<!-- Débogage: Nombre total de messages dans la base de données = $messageCount -->";
    
    // Récupérer tous les messages pour débogage
    if ($messageCount > 0) {
        $allMessagesQuery = "SELECT id, name, email, subject FROM messages LIMIT 5";
        $allMessagesStmt = $db->query($allMessagesQuery);
        $allMessages = $allMessagesStmt->fetchAll(PDO::FETCH_ASSOC);
        echo "<!-- Débogage: Aperçu des messages disponibles: ";
        foreach ($allMessages as $msg) {
            echo "ID: {$msg['id']}, Nom: {$msg['name']}, Email: {$msg['email']}, Sujet: {$msg['subject']}; ";
        }
        echo " -->";
    }
    
    // Récupérer le message spécifique
    $query = "SELECT * FROM messages WHERE id = ? LIMIT 1"; 
    $stmt = $db->prepare($query);
    $stmt->execute([$message_id]);
    
    echo "<!-- Débogage: Nombre de lignes retournées pour l'ID $message_id: " . $stmt->rowCount() . " -->";
    
    if ($stmt->rowCount() == 0) {
        echo "<!-- Débogage: Aucun message trouvé avec l'ID $message_id -->";
        header("Location: messages.php");
        exit;
    }
    
    // Récupérer les données du message
    $messageData = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Afficher les données brutes pour débogage
    echo "<!-- Débogage: Données brutes du message: " . json_encode($messageData) . " -->";
    
    // Créer une instance de Message et assigner les valeurs manuellement
    $message = new Message($db);
    
    // Vérifier chaque champ avant d'assigner
    $message->id = isset($messageData['id']) ? $messageData['id'] : null;
    $message->name = isset($messageData['name']) ? $messageData['name'] : 'Non spécifié';
    $message->email = isset($messageData['email']) ? $messageData['email'] : 'Non spécifié';
    $message->subject = isset($messageData['subject']) ? $messageData['subject'] : 'Sans sujet';
    $message->message = isset($messageData['message']) ? $messageData['message'] : '';
    $message->created_at = isset($messageData['created_at']) ? $messageData['created_at'] : date('Y-m-d H:i:s');
    $message->is_read = isset($messageData['is_read']) ? (bool)$messageData['is_read'] : false;
    
    // Afficher les valeurs assignées pour débogage
    echo "<!-- Débogage: Valeurs assignées à l'objet Message: " . 
         "ID=" . ($message->id ?? 'null') . ", " .
         "Nom=" . ($message->name ?? 'vide') . ", " .
         "Email=" . ($message->email ?? 'vide') . ", " .
         "Sujet=" . ($message->subject ?? 'vide') . ", " .
         "Message=" . (empty($message->message) ? 'vide' : substr($message->message, 0, 30) . '...') . ", " .
         "Date=" . ($message->created_at ?? 'null') . ", " .
         "Lu=" . ($message->is_read ? 'oui' : 'non') . " -->";
    
    // Marquer le message comme lu
    if (!$message->is_read) {
        $updateQuery = "UPDATE messages SET is_read = 1 WHERE id = ?"; 
        $updateStmt = $db->prepare($updateQuery);
        $updateStmt->execute([$message_id]);
        $message->is_read = 1;
    }
    
} catch (PDOException $e) {
    echo "<!-- Débogage: Erreur PDO: " . $e->getMessage() . " -->";
    die("Erreur lors de la récupération du message: " . $e->getMessage());
} catch (Exception $e) {
    echo "<!-- Débogage: Exception générale: " . $e->getMessage() . " -->";
    die("Une erreur est survenue: " . $e->getMessage());
}

// Inclure l'en-tête
include 'includes/header.php';
?>

<div class="content-header">
    <h1>Détails du message</h1>
    <div class="content-actions">
        <a href="messages.php" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Retour aux messages
        </a>
    </div>
</div>

<!-- Détails du message -->
<div class="card">
    <div class="card-header">
        <h2><?php echo html_entity_decode($messageData['subject'] ?? 'Sans sujet', ENT_QUOTES, 'UTF-8'); ?></h2>
        <span class="message-date"><?php echo isset($messageData['created_at']) ? date('d/m/Y H:i', strtotime($messageData['created_at'])) : ''; ?></span>
    </div>
    
    <div class="message-details">
        <div class="message-info">
            <p><strong>De :</strong> <?php echo html_entity_decode($messageData['name'] ?? 'Non spécifié', ENT_QUOTES, 'UTF-8'); ?> 
               (<a href="mailto:<?php echo htmlspecialchars($messageData['email'] ?? ''); ?>"><?php echo htmlspecialchars($messageData['email'] ?? 'Non spécifié'); ?></a>)</p>
            <p><strong>Date :</strong> <?php echo isset($messageData['created_at']) ? date('d/m/Y H:i', strtotime($messageData['created_at'])) : 'Non spécifiée'; ?></p>
            <p><strong>État :</strong> <span class="status-indicator <?php echo isset($messageData['is_read']) && $messageData['is_read'] ? 'status-read' : 'status-unread'; ?>"></span> 
               <?php echo isset($messageData['is_read']) && $messageData['is_read'] ? 'Lu' : 'Non lu'; ?></p>
        </div>
        
        <div class="message-content">
            <h3>Message :</h3>
            <div class="message-text">
                <?php 
                if (isset($messageData['message']) && !empty($messageData['message'])) {
                    // Utiliser html_entity_decode pour convertir les entités HTML en caractères normaux
                    $decodedMessage = html_entity_decode($messageData['message'], ENT_QUOTES, 'UTF-8');
                    // Puis utiliser nl2br pour conserver les sauts de ligne
                    echo nl2br($decodedMessage);
                } else {
                    echo '<em>Aucun contenu de message disponible</em>';
                }
                ?>
            </div>
        </div>
        
        <div class="message-actions">
            <button type="button" class="btn btn-danger delete-message" data-id="<?php echo $message->id; ?>">
                <i class="fas fa-trash"></i> Supprimer ce message
            </button>
        </div>
    </div>
</div>

<!-- Formulaire de suppression caché -->
<form id="deleteForm" method="POST" action="messages.php" style="display: none;">
    <input type="hidden" name="delete_message" value="1">
    <input type="hidden" name="message_id" id="delete_message_id">
    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gestion de la suppression du message
        const deleteButton = document.querySelector('.delete-message');
        const deleteForm = document.getElementById('deleteForm');
        const deleteMessageIdInput = document.getElementById('delete_message_id');
        
        deleteButton.addEventListener('click', function() {
            const messageId = this.getAttribute('data-id');
            
            // Utiliser la fonction de confirmation définie dans footer.php
            confirmAction('Êtes-vous sûr de vouloir supprimer ce message ?', function() {
                deleteMessageIdInput.value = messageId;
                deleteForm.submit();
            });
        });
    });
</script>

<style>
    .message-details {
        padding: 1.5rem;
    }
    
    .message-info {
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }
    
    .dark-mode .message-info {
        border-color: rgba(255, 255, 255, 0.1);
    }
    
    .message-info p {
        margin: 0.5rem 0;
    }
    
    .message-content {
        margin-bottom: 1.5rem;
    }
    
    .message-content h3 {
        font-size: 1.2rem;
        margin-bottom: 1rem;
    }
    
    .message-text {
        background: rgba(0, 0, 0, 0.02);
        padding: 1.5rem;
        border-radius: var(--border-radius);
        white-space: pre-line;
        overflow-wrap: break-word;
        word-wrap: break-word;
        word-break: break-word;
    }
    
    .dark-mode .message-text {
        background: rgba(255, 255, 255, 0.05);
    }
    
    .message-actions {
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid rgba(0, 0, 0, 0.1);
        display: flex;
        justify-content: flex-end;
    }
    
    .dark-mode .message-actions {
        border-color: rgba(255, 255, 255, 0.1);
    }
    
    .message-date {
        font-size: 0.9rem;
        color: var(--text-color-secondary);
    }
    
    /* Styles responsive pour les appareils mobiles */
    @media (max-width: 768px) {
        .message-details {
            padding: 1rem;
        }
        
        .message-text {
            padding: 1rem;
        }
        
        .card-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .card-header h2 {
            margin-bottom: 0.5rem;
        }
        
        .message-date {
            margin-bottom: 0.5rem;
        }
    }
    
    @media (max-width: 480px) {
        .message-details {
            padding: 0.75rem;
        }
        
        .message-info {
            margin-bottom: 1rem;
            padding-bottom: 1rem;
        }
        
        .message-text {
            padding: 0.75rem;
        }
        
        .message-actions {
            margin-top: 1rem;
            padding-top: 1rem;
            justify-content: center;
        }
        
        .message-content h3 {
            font-size: 1.1rem;
            margin-bottom: 0.75rem;
        }
    }
</style>

<?php
// Inclure le pied de page
include 'includes/footer.php';
?>
