<?php
/**
 * Classe User
 * Gère l'authentification et les opérations liées aux utilisateurs
 */
class User {
    private $conn;
    private $table_name = "user";
    
    public $id;
    public $nickname;
    public $email;
    public $password;
    
    /**
     * Constructeur avec connexion à la base de données
     * @param PDO $db Connexion à la base de données
     */
    public function __construct($db) {
        $this->conn = $db;
    }
    
    /**
     * Vérifie les identifiants de connexion
     * @param string $email Email de l'utilisateur
     * @param string $password Mot de passe en clair
     * @return bool Succès de la connexion
     */
    public function login($email, $password) {
        try {
            // Vérifier si la table existe
            $tableCheck = $this->conn->query("SHOW TABLES LIKE '{$this->table_name}'")->rowCount();
            if ($tableCheck == 0) {
                error_log("La table {$this->table_name} n'existe pas dans la base de données");
                return false;
            }
            
            // Requête pour vérifier si l'email existe
            $query = "SELECT id, nickname, email, password FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            // Si l'email existe
            if($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Vérifier le mot de passe
                if(password_verify($password, $row['password'])) {
                    // Stocker les données utilisateur
                    $this->id = $row['id'];
                    $this->nickname = $row['nickname'];
                    $this->email = $row['email'];
                    
                    // Journaliser la connexion réussie
                    error_log("Connexion réussie pour l'utilisateur: {$this->email} (ID: {$this->id})");
                    return true;
                } else {
                    error_log("Mot de passe incorrect pour l'email: {$email}");
                }
            } else {
                error_log("Aucun utilisateur trouvé avec l'email: {$email}");
            }
        } catch (PDOException $e) {
            error_log("Erreur lors de la connexion: " . $e->getMessage());
        }
        
        return false;
    }
    
    /**
     * Vérifie si l'utilisateur est connecté
     * @return bool État de connexion
     */
    public static function isLoggedIn() {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }
    
    /**
     * Déconnecte l'utilisateur
     */
    public static function logout() {
        // Détruire toutes les variables de session
        $_SESSION = array();
        
        // Détruire la session
        session_destroy();
    }
    
    /**
     * Génère un jeton CSRF pour sécuriser les formulaires
     * @return string Jeton CSRF
     */
    public static function generateCsrfToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    /**
     * Vérifie la validité d'un jeton CSRF
     * @param string $token Jeton CSRF à vérifier
     * @return bool Validité du jeton
     */
    public static function verifyCsrfToken($token) {
        if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
            return false;
        }
        return true;
    }
    
    /**
     * Vérifie si la table user existe et la crée si nécessaire
     * @return bool Succès de l'opération
     */
    public function checkAndCreateUserTable() {
        try {
            // Vérifier si la table existe
            $tableCheck = $this->conn->query("SHOW TABLES LIKE '{$this->table_name}'")->rowCount();
            
            if ($tableCheck == 0) {
                // La table n'existe pas, la créer
                $query = "CREATE TABLE `{$this->table_name}` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `nickname` varchar(255) NOT NULL,
                    `email` varchar(255) NOT NULL,
                    `password` varchar(255) NOT NULL,
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
                
                $this->conn->exec($query);
                error_log("Table {$this->table_name} créée avec succès");
                return true;
            }
            
            return true; // La table existe déjà
        } catch (PDOException $e) {
            error_log("Erreur lors de la vérification/création de la table {$this->table_name}: " . $e->getMessage());
            return false;
        }
    }
}
?>
