<?php
/**
 * Fichier de vérification d'authentification pour les pages d'administration
 * À inclure au début de chaque fichier PHP dans le dossier admin (sauf login.php)
 */

// Démarrer la session si elle n'est pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Journaliser la tentative d'accès non autorisée
    error_log("Tentative d'accès non autorisée à la page admin: " . $_SERVER['REQUEST_URI']);
    
    // Rediriger vers la page de connexion avec un paramètre pour indiquer une redirection
    header("Location: login.php?redirect=" . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

// Si nous arrivons ici, l'utilisateur est connecté et peut accéder à la page
// Rafraîchir la session pour éviter l'expiration
$_SESSION['last_activity'] = time();
?>
