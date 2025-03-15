<?php
class Project {
    private $db;
    private $id;
    private $titre;
    private $description;
    private $images;
    private $liens_site;
    private $liens_github;
    
    public function __construct($db) {
        $this->db = $db;
        $this->initializeUploadDirectory();
    }

    /**
     * Initialise le dossier d'upload des projets
     */
    private function initializeUploadDirectory() {
        $targetDir = $this->getUploadDirectory();
        
        if (!file_exists($targetDir)) {
            if (!mkdir($targetDir, 0755, true)) {
                throw new Exception("Impossible de créer le dossier d'upload des projets");
            }
        }
        
        // Vérifier les permissions
        if (!is_writable($targetDir)) {
            chmod($targetDir, 0755);
            if (!is_writable($targetDir)) {
                throw new Exception("Le dossier d'upload des projets n'est pas accessible en écriture");
            }
        }
    }

    /**
     * Retourne le chemin du dossier d'upload
     */
    private function getUploadDirectory() {
        return dirname(dirname(dirname(__FILE__))) . '/uploads/projects/';
    }
    
    // Getters
    public function getId() { return $this->id; }
    public function getTitre() { return $this->titre; }
    public function getDescription() { return $this->description; }
    public function getImages() { return $this->images; }
    public function getLiensSite() { return $this->liens_site; }
    public function getLiensGithub() { return $this->liens_github; }
    
    // Setters avec validation
    public function setTitre($titre) {
        if (empty($titre)) throw new Exception("Le titre ne peut pas être vide");
        $this->titre = htmlspecialchars($titre);
    }
    
    public function setDescription($description) {
        if (empty($description)) throw new Exception("La description ne peut pas être vide");
        $this->description = htmlspecialchars($description);
    }
    
    public function setImages($images) {
        $this->images = $images;
    }
    
    public function setLiensSite($liens_site) {
        if (!empty($liens_site) && !filter_var($liens_site, FILTER_VALIDATE_URL)) {
            throw new Exception("Le lien du site n'est pas valide");
        }
        $this->liens_site = htmlspecialchars($liens_site);
    }
    
    public function setLiensGithub($liens_github) {
        if (!empty($liens_github) && !filter_var($liens_github, FILTER_VALIDATE_URL)) {
            throw new Exception("Le lien GitHub n'est pas valide");
        }
        $this->liens_github = htmlspecialchars($liens_github);
    }
    
    // Méthodes CRUD
    public function create() {
        try {
            // Vérifier si les champs requis sont présents
            if (empty($this->titre) || empty($this->description)) {
                throw new Exception("Le titre et la description sont obligatoires");
            }

            // Démarrer la transaction
            $this->db->beginTransaction();
            
            $query = "INSERT INTO projets (titre, description, images, liens_site, liens_github) 
                     VALUES (:titre, :description, :images, :liens_site, :liens_github)";
            
            $stmt = $this->db->prepare($query);
            
            // Bind des valeurs avec vérification
            $params = [
                ':titre' => $this->titre,
                ':description' => $this->description,
                ':images' => $this->images ?: null,
                ':liens_site' => $this->liens_site ?: null,
                ':liens_github' => $this->liens_github ?: null
            ];
            
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            
            // Exécuter la requête
            if (!$stmt->execute()) {
                $error = $stmt->errorInfo();
                error_log('Erreur SQL lors de la création du projet: ' . print_r($error, true));
                throw new Exception('Erreur lors de la création du projet: ' . $error[2]);
            }
            
            // Récupérer l'ID du projet créé
            $this->id = $this->db->lastInsertId();
            
            // Valider la transaction
            $this->db->commit();
            error_log('Projet créé avec succès. ID: ' . $this->id . ', Titre: ' . $this->titre);
            return true;
            
        } catch (Exception $e) {
            // En cas d'erreur, annuler la transaction
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log('Exception lors de la création du projet: ' . $e->getMessage());
            throw $e;
        }
    }
    
    public function read($id = null) {
        if ($id) {
            $query = "SELECT * FROM projets WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $query = "SELECT * FROM projets ORDER BY id DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
    
    public function update($id) {
        try {
            // Vérifier si les champs requis sont présents
            if (empty($this->titre) || empty($this->description)) {
                throw new Exception("Le titre et la description sont obligatoires");
            }

            $query = "UPDATE projets 
                     SET titre = :titre, 
                         description = :description, 
                         images = :images, 
                         liens_site = :liens_site, 
                         liens_github = :liens_github 
                     WHERE id = :id";
            
            $stmt = $this->db->prepare($query);
            
            // Bind des valeurs avec vérification
            $params = [
                ':id' => $id,
                ':titre' => $this->titre,
                ':description' => $this->description,
                ':images' => $this->images ?: null,
                ':liens_site' => $this->liens_site ?: null,
                ':liens_github' => $this->liens_github ?: null
            ];
            
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            
            if (!$stmt->execute()) {
                $error = $stmt->errorInfo();
                error_log('Erreur SQL lors de la mise à jour du projet: ' . print_r($error, true));
                throw new Exception('Erreur lors de la mise à jour du projet: ' . $error[2]);
            }
            
            return true;
            
        } catch (Exception $e) {
            error_log('Exception lors de la mise à jour du projet: ' . $e->getMessage());
            throw $e;
        }
    }
    
    public function delete($id) {
        // Récupérer l'image avant la suppression
        $query = "SELECT images FROM projets WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $project = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Supprimer le fichier image si il existe
        if ($project && $project['images']) {
            $imagePath = $_SERVER['DOCUMENT_ROOT'] . '/v2/uploads/projects/' . $project['images'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        
        // Supprimer l'enregistrement
        $query = "DELETE FROM projets WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }
    
    // Méthode pour gérer l'upload d'image
    public function uploadImage($file) {
        $targetDir = $this->getUploadDirectory();
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        
        $fileName = uniqid() . '_' . basename($file['name']);
        $targetFile = $targetDir . $fileName;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        
        // Vérifier si c'est une vraie image
        if (!getimagesize($file['tmp_name'])) {
            throw new Exception("Le fichier n'est pas une image valide.");
        }
        
        // Vérifier la taille (max 5MB)
        if ($file['size'] > 5000000) {
            throw new Exception("L'image est trop volumineuse (max 5MB).");
        }
        
        // Autoriser certains formats
        if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            throw new Exception("Seuls les fichiers JPG, JPEG, PNG & GIF sont autorisés.");
        }
        
        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            return $fileName;
        }
        
        throw new Exception("Une erreur est survenue lors de l'upload de l'image.");
    }
}
?>
