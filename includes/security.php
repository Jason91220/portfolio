<?php
/**
 * Fichier de sécurité pour protéger contre l'accès direct aux fichiers PHP
 * À inclure au début de chaque fichier PHP
 */

// Fonction pour vérifier si l'accès est direct ou via inclusion
function isDirectAccess() {
    // Obtenir le script appelé directement
    $calledScript = $_SERVER['SCRIPT_FILENAME'];
    
    // Obtenir le script qui a inclus ce fichier (si applicable)
    $includingScript = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
    
    // Vérifier si nous sommes dans le dossier admin et si ce n'est pas login.php
    $isAdminArea = (strpos($calledScript, '/admin/') !== false);
    $isLoginPage = (strpos($calledScript, '/admin/login.php') !== false);
    
    // Si c'est la page de login, autoriser l'accès direct
    if ($isLoginPage) {
        return false;
    }
    
    // Si c'est dans l'admin et ce n'est pas login.php, vérifier si l'utilisateur est connecté
    if ($isAdminArea) {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            return true; // Accès non autorisé
        } else {
            return false; // Accès autorisé car utilisateur connecté
        }
    }
    
    // Pour les autres fichiers PHP, vérifier si l'accès est direct
    $isFrontendPage = in_array(basename($calledScript), ['index.php', 'contact.php', 'portfolio.php', 'about.php', 'services.php']);
    
    // Autoriser l'accès direct aux pages principales du frontend
    if ($isFrontendPage) {
        return false;
    }
    
    // Pour tous les autres fichiers PHP, considérer comme accès direct non autorisé
    return true;
}

// Si l'accès est direct et non autorisé, rediriger vers la page 403
if (isDirectAccess()) {
    header("Location: /403.php");
    exit;
}
?>
