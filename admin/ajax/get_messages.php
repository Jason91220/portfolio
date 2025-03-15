<?php
// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté
if(!isset($_SESSION["user_id"])) {
    header('HTTP/1.1 403 Forbidden');
    echo json_encode(['error' => 'Non autorisé']);
    exit;
}

// Inclure les fichiers nécessaires
require_once '../config.php';
require_once '../classes/Message.php';

// Créer une instance de Database
$database = new Database();
$db = $database->getConnection();

// Créer une instance de Message
$message = new Message($db);

// Récupérer tous les messages
$stmt = $message->getAll();
$messages = [];

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $messages[] = [
        'id' => $row['id'],
        'name' => $row['name'],
        'email' => $row['email'],
        'subject' => $row['subject'],
        'created_at' => $row['created_at'],
        'is_read' => (bool)$row['is_read']
    ];
}

// Renvoyer les messages au format JSON
header('Content-Type: application/json');
echo json_encode($messages);
?>
