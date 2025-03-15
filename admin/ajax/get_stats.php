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

// Obtenir les statistiques
$stats = [
    'total_messages' => $message->countAll(),
    'unread_messages' => $message->countUnread()
];

// Renvoyer les statistiques au format JSON
header('Content-Type: application/json');
echo json_encode($stats);
?>
