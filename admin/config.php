<?php
/**
 * Configuration de la base de données
 * Ce fichier contient les paramètres de connexion à la base de données
 */

class Database {
    // Configuration de la base de données
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $port;
    private $socket;

    public function __construct() {
        if ($_SERVER['HTTP_HOST'] === 'localhost:8888' || $_SERVER['HTTP_HOST'] === '127.0.0.1:8888') {
            // Configuration locale (MAMP)
            $this->host = 'localhost';
            $this->db_name = 'beja5669_gut5';
            $this->username = 'root';
            $this->password = 'root';
            $this->port = 8889;
            $this->socket = '/Applications/MAMP/tmp/mysql/mysql.sock';
        } else {
            // Configuration production (o2switch)
            $this->host = 'furry.o2switch.net';
            $this->db_name = 'beja5669_gut5';
            $this->username = 'beja5669';
            $this->password = 'uNj7-xLky-7w3@';
            $this->port = 3306;
            $this->socket = null;
        }
    }

    private $conn;

    /**
     * Établit la connexion à la base de données
     * @return PDO|null Objet PDO de connexion ou null en cas d'échec
     */
    public function getConnection() {
        $this->conn = null;

        try {
            if ($this->socket) {
                // Connexion locale via socket
                $dsn = "mysql:unix_socket={$this->socket};dbname={$this->db_name}";
            } else {
                // Connexion directe avec host et port
                $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->db_name}";
            }
            
            $this->conn = new PDO($dsn, $this->username, $this->password);
            error_log("Connexion à la base de données réussie");
            
            // Configurer la connexion
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8");
            
            // Vérifier si les tables nécessaires existent, sinon les créer
            $this->checkAndCreateMessagesTable();
            $this->checkAndCreateUserTable();
            $this->checkAndCreateProjetsTable();
            
        } catch(PDOException $e) {
            error_log("Erreur de connexion à la base de données: " . $e->getMessage());
        }
        
        return $this->conn;
    }
    
    /**
     * Vérifie si la table messages existe et la crée si nécessaire
     */
    private function checkAndCreateMessagesTable() {
        if (!$this->conn) {
            return;
        }
        
        try {
            // Vérifier si la table messages existe
            $tableExists = $this->conn->query("SHOW TABLES LIKE 'messages'")->rowCount() > 0;
            
            if (!$tableExists) {
                error_log("La table 'messages' n'existe pas, création en cours...");
                
                // Créer la table messages
                $sql = "CREATE TABLE IF NOT EXISTS messages (
                    id INT(11) NOT NULL AUTO_INCREMENT,
                    name VARCHAR(100) NOT NULL,
                    email VARCHAR(100) NOT NULL,
                    subject VARCHAR(255) NOT NULL,
                    message TEXT NOT NULL,
                    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    is_read TINYINT(1) NOT NULL DEFAULT 0,
                    PRIMARY KEY (id)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"; 
                
                $this->conn->exec($sql);
                error_log("Table 'messages' créée avec succès");
            } else {
                // Vérifier s'il y a des messages dans la table
                $count = $this->conn->query("SELECT COUNT(*) FROM messages")->fetchColumn();
                error_log("La table 'messages' existe et contient $count message(s)");
                
                // Afficher le premier message pour débogage
                if ($count > 0) {
                    $firstMessage = $this->conn->query("SELECT * FROM messages LIMIT 1")->fetch(PDO::FETCH_ASSOC);
                    error_log("Premier message: " . json_encode($firstMessage));
                }
            }
        } catch (PDOException $e) {
            error_log("Erreur lors de la vérification/création de la table messages: " . $e->getMessage());
        }
    }
    
    /**
     * Vérifie si la table user existe et la crée si nécessaire
     */
    private function checkAndCreateUserTable() {
        if (!$this->conn) {
            return;
        }
        
        try {
            // Vérifier si la table user existe
            $tableExists = $this->conn->query("SHOW TABLES LIKE 'user'")->rowCount() > 0;
            
            if (!$tableExists) {
                error_log("La table 'user' n'existe pas, création en cours...");
                
                // Créer la table user selon la structure définie dans user.sql
                $sql = "CREATE TABLE IF NOT EXISTS user (
                    id INT(11) NOT NULL,
                    nickname VARCHAR(255) NOT NULL,
                    email VARCHAR(255) NOT NULL,
                    password VARCHAR(255) NOT NULL,
                    PRIMARY KEY (id)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
                
                ALTER TABLE user
                    MODIFY id INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;"; 
                
                $this->conn->exec($sql);
                error_log("Table 'user' créée avec succès");
                
                // Créer un utilisateur par défaut si la table est vide
                $this->createDefaultUser();
            } else {
                // Vérifier s'il y a des utilisateurs dans la table
                $count = $this->conn->query("SELECT COUNT(*) FROM user")->fetchColumn();
                error_log("La table 'user' existe et contient $count utilisateur(s)");
                
                // Créer un utilisateur par défaut si la table est vide
                if ($count == 0) {
                    $this->createDefaultUser();
                }
            }
        } catch (PDOException $e) {
            error_log("Erreur lors de la vérification/création de la table user: " . $e->getMessage());
        }
    }
    
    /**
     * Crée un utilisateur par défaut dans la table user
     */
    private function createDefaultUser() {
        try {
            // Vérifier si l'utilisateur existe déjà
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM user WHERE email = 'jason.bedulho@icloud.com'");
            $stmt->execute();
            $count = $stmt->fetchColumn();
            
            if ($count == 0) {
                // Créer l'utilisateur par défaut
                $nickname = 'jason';
                $email = 'jason.bedulho@icloud.com';
                $password = password_hash('admin123', PASSWORD_DEFAULT); // Mot de passe par défaut sécurisé
                
                $stmt = $this->conn->prepare("INSERT INTO user (nickname, email, password) VALUES (?, ?, ?)");
                $stmt->execute([$nickname, $email, $password]);
                
                error_log("Utilisateur par défaut créé avec succès");
            }
        } catch (PDOException $e) {
            error_log("Erreur lors de la création de l'utilisateur par défaut: " . $e->getMessage());
        }
    }

    /**
     * Vérifie si la table projets existe et la crée si nécessaire
     */
    private function checkAndCreateProjetsTable() {
        if (!$this->conn) {
            return;
        }
        
        try {
            // Vérifier si la table projets existe
            $tableExists = $this->conn->query("SHOW TABLES LIKE 'projets'")->rowCount() > 0;
            
            if (!$tableExists) {
                error_log("La table 'projets' n'existe pas, création en cours...");
                
                // Créer la table projets
                $sql = "CREATE TABLE IF NOT EXISTS projets (
                    id int(11) NOT NULL AUTO_INCREMENT,
                    titre varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                    description text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                    images varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                    liens_site varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                    liens_github varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                    PRIMARY KEY (id)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
                
                $this->conn->exec($sql);
                error_log("Table 'projets' créée avec succès");
            }
        } catch (PDOException $e) {
            error_log("Erreur lors de la vérification/création de la table projets: " . $e->getMessage());
        }
    }
}
?>
