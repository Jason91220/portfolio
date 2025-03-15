<?php
/**
 * Classe Message
 * Gère les opérations liées aux messages de contact
 */
class Message {
    private $conn;
    private $table_name = "messages";
    
    public $id;
    public $name;
    public $email;
    public $subject;
    public $message;
    public $created_at;
    public $is_read;
    
    /**
     * Constructeur avec connexion à la base de données
     * @param PDO $db Connexion à la base de données
     */
    public function __construct($db) {
        $this->conn = $db;
    }
    
    /**
     * Récupère tous les messages
     * @return PDOStatement Résultat de la requête
     */
    public function getAll() {
        try {
            // Vérifier si la connexion est valide
            if (!$this->conn) {
                error_log("Erreur: Connexion à la base de données non disponible dans getAll()");
                return false;
            }
            
            // Débogage - Vérifier si la table existe
            try {
                $checkTable = $this->conn->query("SHOW TABLES LIKE '{$this->table_name}'");
                if ($checkTable->rowCount() == 0) {
                    error_log("Erreur: La table {$this->table_name} n'existe pas dans la base de données");
                    return false;
                }
            } catch (PDOException $e) {
                error_log("Erreur lors de la vérification de la table: " . $e->getMessage());
            }
            
            // Débogage - Vérifier le nombre de messages dans la table
            try {
                $countQuery = "SELECT COUNT(*) as total FROM {$this->table_name}";
                $countStmt = $this->conn->query($countQuery);
                $countRow = $countStmt->fetch(PDO::FETCH_ASSOC);
                error_log("Nombre de messages dans la table: " . $countRow['total']);
            } catch (PDOException $e) {
                error_log("Erreur lors du comptage des messages: " . $e->getMessage());
            }
            
            // Spécifier explicitement les colonnes pour éviter tout problème
            $query = "SELECT id, name, email, subject, message, created_at, is_read FROM " . $this->table_name . " ORDER BY created_at DESC";
            $stmt = $this->conn->prepare($query);
            
            // Exécuter la requête
            if (!$stmt->execute()) {
                error_log("Erreur lors de l'exécution de la requête getAll(): " . implode(', ', $stmt->errorInfo()));
                return false;
            }
            
            // Débogage - Vérifier le nombre de lignes retournées
            error_log("Nombre de lignes retournées par getAll(): " . $stmt->rowCount());
            
            return $stmt;
        } catch (PDOException $e) {
            error_log("Exception PDO dans getAll(): " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Récupère un message par son ID
     * @param int $id ID du message
     * @return bool Succès de l'opération
     */
    public function getOne($id) {
        // Méthode simplifiée et robuste pour récupérer un message
        try {
            // Vérifier si la connexion est valide
            if (!$this->conn) {
                return false;
            }
            
            // Requête directe et simple
            $query = "SELECT * FROM messages WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$id]);
            
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Assigner les valeurs aux propriétés
                $this->id = $row['id'];
                $this->name = $row['name'];
                $this->email = $row['email'];
                $this->subject = $row['subject'];
                $this->message = $row['message'];
                $this->created_at = $row['created_at'];
                $this->is_read = $row['is_read'];
                
                // Journaliser les données récupérées pour débogage
                error_log("Message récupéré avec succès - ID: {$this->id}, Nom: {$this->name}, Sujet: {$this->subject}");
                
                return true;
            }
            
            return false;
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération du message: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Marque un message comme lu
     * @param int $id ID du message
     * @return bool Succès de l'opération
     */
    public function markAsRead($id) {
        $query = "UPDATE " . $this->table_name . " SET is_read = 1 WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Supprime un message
     * @param int $id ID du message
     * @return bool Succès de l'opération
     */
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Compte le nombre total de messages
     * @return int Nombre de messages
     */
    public function countAll() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row['total'];
    }
    
    /**
     * Compte le nombre de messages non lus
     * @return int Nombre de messages non lus
     */
    public function countUnread() {
        $query = "SELECT COUNT(*) as unread FROM " . $this->table_name . " WHERE is_read = 0";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row['unread'];
    }
}
?>
