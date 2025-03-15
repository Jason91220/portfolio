<?php
/**
 * Script de diagnostic pour tester la connexion à la base de données
 * et vérifier la structure de la table user
 */

// Afficher les erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclure les fichiers nécessaires
require_once 'config.php';
require_once 'classes/User.php';

echo "<h1>Test de connexion à la base de données</h1>";

// Créer une instance de Database
$database = new Database();
$db = $database->getConnection();

if (!$db) {
    echo "<p style='color: red;'>Erreur: Impossible de se connecter à la base de données.</p>";
    exit;
}

echo "<p style='color: green;'>Connexion à la base de données réussie!</p>";

// Vérifier si la table user existe
try {
    $tableExists = $db->query("SHOW TABLES LIKE 'user'")->rowCount() > 0;
    
    if (!$tableExists) {
        echo "<p style='color: red;'>La table 'user' n'existe pas dans la base de données.</p>";
    } else {
        echo "<p style='color: green;'>La table 'user' existe dans la base de données.</p>";
        
        // Vérifier la structure de la table user
        $columns = $db->query("SHOW COLUMNS FROM user")->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<h2>Structure de la table user:</h2>";
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        
        foreach ($columns as $column) {
            echo "<tr>";
            foreach ($column as $key => $value) {
                echo "<td>" . htmlspecialchars($value ?? 'NULL') . "</td>";
            }
            echo "</tr>";
        }
        
        echo "</table>";
        
        // Vérifier le nombre d'utilisateurs
        $count = $db->query("SELECT COUNT(*) FROM user")->fetchColumn();
        echo "<p>Nombre d'utilisateurs dans la table: $count</p>";
        
        // Afficher les utilisateurs (sans les mots de passe)
        if ($count > 0) {
            $users = $db->query("SELECT id, nickname, email FROM user")->fetchAll(PDO::FETCH_ASSOC);
            
            echo "<h2>Liste des utilisateurs:</h2>";
            echo "<table border='1' cellpadding='5'>";
            echo "<tr><th>ID</th><th>Nickname</th><th>Email</th></tr>";
            
            foreach ($users as $user) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($user['id']) . "</td>";
                echo "<td>" . htmlspecialchars($user['nickname']) . "</td>";
                echo "<td>" . htmlspecialchars($user['email']) . "</td>";
                echo "</tr>";
            }
            
            echo "</table>";
        }
        
        // Tester la connexion avec un utilisateur existant
        echo "<h2>Test d'authentification:</h2>";
        
        $user = new User($db);
        $email = 'jason.bedulho@icloud.com';
        $password = 'admin123'; // Mot de passe par défaut
        
        if ($user->login($email, $password)) {
            echo "<p style='color: green;'>Authentification réussie pour l'utilisateur: " . htmlspecialchars($user->nickname) . "</p>";
        } else {
            echo "<p style='color: red;'>Échec de l'authentification pour l'email: " . htmlspecialchars($email) . "</p>";
        }
    }
} catch (PDOException $e) {
    echo "<p style='color: red;'>Erreur lors de la vérification de la table user: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
