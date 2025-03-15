<?php
// Connexion à la base de données
function getDbConnection() {
    // Configuration pour l'hébergeur o2switch (mêmes paramètres que dans admin/config.php)
    $host = 'furry.o2switch.net';
    $db_name = 'beja5669_gut5';
    $username = 'beja5669';
    $password = 'uNj7-xLky-7w3@';
    $port = 8889; // Port MySQL de MAMP
    $socket = '/Applications/MAMP/tmp/mysql/mysql.sock'; // Socket MySQL de MAMP
    $conn = null;
    
    try {
        // Essayer d'abord la connexion directe à l'hébergeur o2switch
        $dsn = "mysql:host={$host};dbname={$db_name}";
        $conn = new PDO($dsn, $username, $password);
        error_log("Connexion à la base de données o2switch réussie");
        
        // Configurer la connexion
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->exec("set names utf8");
    } catch(PDOException $e) {
        error_log("Erreur de connexion directe à o2switch: " . $e->getMessage());
        
        try {
            // Essayer avec le socket local (pour développement)
            $dsn = "mysql:unix_socket={$socket};dbname={$db_name}";
            $conn = new PDO($dsn, $username, $password);
            error_log("Connexion via socket réussie");
            
            // Configurer la connexion
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->exec("set names utf8");
        } catch (PDOException $socketEx) {
            error_log("Échec de connexion via socket: " . $socketEx->getMessage());
            
            try {
                // Essayer avec le port local
                $dsn = "mysql:host=localhost;port={$port};dbname={$db_name}";
                $conn = new PDO($dsn, $username, $password);
                error_log("Connexion via port local réussie");
                
                // Configurer la connexion
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $conn->exec("set names utf8");
            } catch (PDOException $portEx) {
                error_log("Échec de connexion via port local: " . $portEx->getMessage());
                // Toutes les tentatives ont échoué
                $conn = null;
            }
        }
    }
    
    return $conn;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire sans conversion en entités HTML
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $subject = isset($_POST['subject']) ? trim($_POST['subject']) : 'Message de contact';
    $messageContent = isset($_POST['message']) ? trim($_POST['message']) : '';
    
    $errors = [];
    
    if (empty($name)) {
        $errors[] = "Le nom est requis";
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email invalide";
    }
    
    if (empty($messageContent) || strlen($messageContent) < 10) {
        $errors[] = "Le message doit contenir au moins 10 caractères";
    }
    
    if (empty($errors)) {
        // Enregistrer le message dans la base de données
        $conn = getDbConnection();
        
        if ($conn) {
            try {
                // Vérifier si la table messages existe
                $tableCheck = $conn->query("SHOW TABLES LIKE 'messages'");
                if ($tableCheck->rowCount() == 0) {
                    // Créer la table si elle n'existe pas
                    $createTable = "CREATE TABLE IF NOT EXISTS `messages` (
                        `id` int NOT NULL AUTO_INCREMENT,
                        `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                        `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                        `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                        `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
                        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                        `is_read` tinyint(1) NOT NULL DEFAULT '0',
                        PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
                    $conn->exec($createTable);
                    error_log("Table messages créée avec succès");
                }
                
                // Préparer la requête d'insertion
                $query = "INSERT INTO messages (name, email, subject, message, created_at, is_read) 
                          VALUES (:name, :email, :subject, :message, NOW(), 0)";
                
                $stmt = $conn->prepare($query);
                
                // S'assurer que les variables ne sont pas null
                $name = $name ?? '';
                $email = $email ?? '';
                $subject = $subject ?? 'Message de contact';
                $messageContent = $messageContent ?? '';
                
                // Journaliser les données avant insertion pour débogage
                error_log("Données à insérer: Nom=$name, Email=$email, Sujet=$subject, Message=$messageContent");
                
                // Lier les paramètres
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':subject', $subject);
                $stmt->bindParam(':message', $messageContent);
                
                // Exécuter la requête
                if ($stmt->execute()) {
                    $success = true;
                    $lastId = $conn->lastInsertId();
                    error_log("Message inséré avec succès, ID: $lastId");
                    
                    // Vérifier que le message a bien été inséré
                    $verifyQuery = "SELECT * FROM messages WHERE id = :id";
                    $verifyStmt = $conn->prepare($verifyQuery);
                    $verifyStmt->bindParam(':id', $lastId);
                    $verifyStmt->execute();
                    
                    if ($verifyStmt->rowCount() > 0) {
                        $messageData = $verifyStmt->fetch(PDO::FETCH_ASSOC);
                        error_log("Vérification du message inséré: " . json_encode($messageData));
                        
                        // Envoi d'un email de notification
                        $to = "jason.bedulho99@gmail.com";
                        $email_subject = "Nouveau message de contact: " . $subject;
                        
                        // Préparer le contenu de l'email
                        $email_body = "Vous avez reçu un nouveau message depuis le formulaire de contact de votre site web.\n\n";
                        $email_body .= "Détails du message:\n";
                        $email_body .= "Nom: " . $name . "\n";
                        $email_body .= "Email: " . $email . "\n";
                        $email_body .= "Sujet: " . $subject . "\n";
                        $email_body .= "Message:\n" . $messageContent . "\n";
                        $email_body .= "Date: " . date('d/m/Y H:i:s') . "\n";
                        
                        // En-têtes de l'email
                        $headers = "From: noreply@" . $_SERVER['HTTP_HOST'] . "\r\n";
                        $headers .= "Reply-To: " . $email . "\r\n";
                        $headers .= "X-Mailer: PHP/" . phpversion();
                        
                        // Envoi de l'email
                        if (mail($to, $email_subject, $email_body, $headers)) {
                            error_log("Email de notification envoyé à $to");
                        } else {
                            error_log("Échec de l'envoi de l'email de notification");
                        }
                    } else {
                        error_log("Avertissement: Le message semble avoir été inséré mais n'est pas récupérable");
                    }
                } else {
                    $errorInfo = $stmt->errorInfo();
                    error_log("Erreur SQL lors de l'insertion: " . implode(', ', $errorInfo));
                    $errors[] = "Une erreur est survenue lors de l'envoi du message.";
                }
            } catch(PDOException $e) {
                error_log("Exception PDO lors de l'enregistrement du message: " . $e->getMessage());
                $errors[] = "Une erreur est survenue lors de l'envoi du message.";
            }
        } else {
            error_log("Impossible de se connecter à la base de données");
            $errors[] = "Impossible de se connecter à la base de données.";
        }
    }
}
?>

<section id="contact" class="contact-section">
    <div class="container">
        <h2 data-lang="contact-title">Contact</h2>
        
        <?php if (isset($success)): ?>
        <div class="success-message" data-lang="contact-success">
            Message envoyé avec succès ! Je vous répondrai dans les plus brefs délais.
        </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
        <div class="error-message">
            <?php foreach ($errors as $error): ?>
                <p><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <form method="POST" class="contact-form" id="contactForm" novalidate autocomplete="off">
            <div class="form-group">
                <label for="name" class="required-field" data-lang="contact-name">Nom</label>
                <input type="text" id="name" name="name"
                       aria-required="true"
                       aria-describedby="name-error"
                       autocomplete="name"
                       placeholder="Votre nom"
                       value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>">

            </div>

            <div class="form-group">
                <label for="email" class="required-field" data-lang="contact-email">Email</label>
                <input type="email" id="email" name="email"
                       aria-required="true"
                       aria-describedby="email-error"
                       autocomplete="email"
                       placeholder="Votre email"
                       value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">

            </div>
            
            <div class="form-group">
                <label for="subject" data-lang="contact-subject">Sujet</label>
                <input type="text" id="subject" name="subject"
                       aria-describedby="subject-error"
                       placeholder="Sujet de votre message"
                       value="<?php echo isset($subject) ? htmlspecialchars($subject) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="message" class="required-field" data-lang="contact-message">Message</label>
                <textarea id="message" name="message"
                          aria-required="true"
                          aria-describedby="message-error"
                          placeholder="Votre message"
                          rows="5"><?php echo isset($messageContent) ? htmlspecialchars($messageContent) : ''; ?></textarea>

            </div>

            <button type="submit" class="submit-btn" data-lang="contact-submit">
                <span class="button-text">Envoyer</span>
                <span class="visually-hidden">Envoyer le formulaire de contact</span>
            </button>
        </form>
    </div>
</section>

<style>
.contact-section {
    padding: 6rem 0;
}

.contact-section h2 {
    font-size: 2.5rem;
    margin-bottom: 3rem;
    text-align: center;
    background: linear-gradient(135deg, var(--primary-color), #00c6ff);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

.contact-form {
    max-width: 600px;
    margin: 0 auto;
    background: var(--card-bg);
    padding: 2rem;
    border-radius: var(--border-radius);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    color: var(--text-color);
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 0.8rem;
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius);
    background: rgba(255, 255, 255, 0.05);
    color: var(--text-color);
    font-family: var(--font-primary);
    transition: border-color var(--transition-speed);
}

.form-group input:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--primary-color);
}

.submit-btn {
    width: 100%;
    padding: 1rem;
    background: var(--primary-color);
    color: white;
    border: none;
    border-radius: var(--border-radius);
    font-size: 1rem;
    cursor: pointer;
    transition: transform var(--transition-speed), opacity var(--transition-speed);
}

.submit-btn:hover {
    transform: translateY(-2px);
    opacity: 0.9;
}

.success-message {
    background: #4CAF50;
    color: white;
    padding: 1rem;
    border-radius: var(--border-radius);
    margin-bottom: 2rem;
    text-align: center;
}

.error-message {
    background: #f44336;
    color: white;
    padding: 1rem;
    border-radius: var(--border-radius);
    margin-bottom: 2rem;
}

.error-message p {
    margin: 0;
}

@media (max-width: 768px) {
    .contact-section h2 {
        font-size: 2rem;
    }
    
    .contact-form {
        padding: 1.5rem;
    }
}
</style>
