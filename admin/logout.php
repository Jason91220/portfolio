<?php
// Démarrer la session
session_start();

// Inclure les fichiers nécessaires
require_once 'classes/User.php';

// Déconnecter l'utilisateur
User::logout();

// Rediriger vers la page de connexion
header("Location: login.php");
exit;
?>
