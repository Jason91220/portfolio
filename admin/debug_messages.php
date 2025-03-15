<?php
// Script de diagnostic pour les messages
// Démarrer la session
session_start();

// Inclure les fichiers nécessaires
require_once 'config.php';
require_once 'classes/User.php';
require_once 'classes/Message.php';

// Vérifier si l'utilisateur est connecté
if(!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

// Créer une instance de Database
$database = new Database();
$db = $database->getConnection();

// Vérifier la connexion à la base de données
if (!$db) {
    die("Erreur: Impossible de se connecter à la base de données");
}

// Créer une instance de Message
$message = new Message($db);

// Vérifier si la table messages existe
try {
    $checkTable = $db->query("SHOW TABLES LIKE 'messages'");
    if ($checkTable->rowCount() == 0) {
        die("Erreur: La table 'messages' n'existe pas dans la base de données");
    }
} catch (PDOException $e) {
    die("Erreur lors de la vérification de la table: " . $e->getMessage());
}

// Vérifier le nombre de messages dans la table
try {
    $countQuery = "SELECT COUNT(*) as total FROM messages";
    $countStmt = $db->query($countQuery);
    $countRow = $countStmt->fetch(PDO::FETCH_ASSOC);
    $totalMessages = $countRow['total'];
} catch (PDOException $e) {
    die("Erreur lors du comptage des messages: " . $e->getMessage());
}

// Récupérer tous les messages directement
try {
    $query = "SELECT id, name, email, subject, message, created_at, is_read FROM messages ORDER BY created_at DESC";
    $stmt = $db->query($query);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des messages: " . $e->getMessage());
}

// Afficher les résultats
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnostic des Messages</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1, h2 { color: #333; }
        .diagnostic-info { background: #f5f5f5; padding: 15px; margin-bottom: 20px; border-radius: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
        .message-content { max-height: 100px; overflow-y: auto; }
        .error { color: red; font-weight: bold; }
        .success { color: green; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Diagnostic des Messages</h1>
    
    <div class="diagnostic-info">
        <h2>Informations de Diagnostic</h2>
        <p><strong>Connexion à la base de données:</strong> <span class="success">Réussie</span></p>
        <p><strong>Table 'messages':</strong> <span class="success">Existe</span></p>
        <p><strong>Nombre total de messages:</strong> <?php echo $totalMessages; ?></p>
    </div>
    
    <?php if ($totalMessages > 0): ?>
        <h2>Liste des Messages (<?php echo $totalMessages; ?>)</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Sujet</th>
                    <th>Message</th>
                    <th>Date</th>
                    <th>Lu</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($messages as $msg): ?>
                <tr>
                    <td><?php echo htmlspecialchars($msg['id']); ?></td>
                    <td><?php echo htmlspecialchars($msg['name'] ?? 'Non spécifié'); ?></td>
                    <td><?php echo htmlspecialchars($msg['email'] ?? 'Non spécifié'); ?></td>
                    <td><?php echo htmlspecialchars($msg['subject'] ?? 'Sans sujet'); ?></td>
                    <td class="message-content"><?php 
                        if (!empty($msg['message'])) {
                            echo nl2br(htmlspecialchars($msg['message']));
                        } else {
                            echo '<em>Aucun contenu</em>';
                        }
                    ?></td>
                    <td><?php echo isset($msg['created_at']) ? date('d/m/Y H:i', strtotime($msg['created_at'])) : 'Date inconnue'; ?></td>
                    <td><?php echo isset($msg['is_read']) && $msg['is_read'] ? 'Oui' : 'Non'; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="error">Aucun message trouvé dans la base de données.</p>
    <?php endif; ?>
    
    <h2>Test de la méthode getOne()</h2>
    <?php
    // Tester getOne() avec le premier message si disponible
    if ($totalMessages > 0 && isset($messages[0]['id'])) {
        $testId = $messages[0]['id'];
        $testResult = $message->getOne($testId);
        echo "<p><strong>Test de getOne() avec ID $testId:</strong> ";
        if ($testResult) {
            echo "<span class='success'>Réussi</span></p>";
            echo "<ul>";
            echo "<li><strong>ID:</strong> " . htmlspecialchars($message->id ?? 'Non défini') . "</li>";
            echo "<li><strong>Nom:</strong> " . htmlspecialchars($message->name ?? 'Non défini') . "</li>";
            echo "<li><strong>Email:</strong> " . htmlspecialchars($message->email ?? 'Non défini') . "</li>";
            echo "<li><strong>Sujet:</strong> " . htmlspecialchars($message->subject ?? 'Non défini') . "</li>";
            echo "<li><strong>Message:</strong> " . (empty($message->message) ? '<em>Vide</em>' : nl2br(htmlspecialchars($message->message))) . "</li>";
            echo "<li><strong>Date:</strong> " . (isset($message->created_at) ? date('d/m/Y H:i', strtotime($message->created_at)) : 'Non définie') . "</li>";
            echo "<li><strong>Lu:</strong> " . ($message->is_read ? 'Oui' : 'Non') . "</li>";
            echo "</ul>";
        } else {
            echo "<span class='error'>Échec</span></p>";
        }
    } else {
        echo "<p class='error'>Impossible de tester getOne() car aucun message n'est disponible.</p>";
    }
    ?>
    
    <p><a href="messages.php">Retour à la liste des messages</a></p>
</body>
</html>
