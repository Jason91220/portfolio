<?php
// Démarrer la session
session_start();

// Si l'utilisateur est déjà connecté, rediriger vers le dashboard
if(isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Inclure les fichiers nécessaires
require_once 'config.php';
require_once 'classes/User.php';

// Initialiser les variables
$email = $password = "";
$email_err = $password_err = $login_err = "";
$csrf_token = User::generateCsrfToken();

// Traitement du formulaire lors de la soumission
if($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Vérifier le jeton CSRF
    if(!isset($_POST['csrf_token']) || !User::verifyCsrfToken($_POST['csrf_token'])) {
        $login_err = "Erreur de sécurité. Veuillez réessayer.";
    } else {
        // Valider l'email
        if(empty(trim($_POST["email"]))) {
            $email_err = "Veuillez entrer votre email.";
        } else {
            $email = trim($_POST["email"]);
            // Vérifier si l'email est valide
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $email_err = "Format d'email invalide.";
            }
        }
        
        // Valider le mot de passe
        if(empty(trim($_POST["password"]))) {
            $password_err = "Veuillez entrer votre mot de passe.";
        } else {
            $password = trim($_POST["password"]);
        }
        
        // Vérifier les erreurs avant de tenter la connexion
        if(empty($email_err) && empty($password_err)) {
            // Créer une instance de Database
            $database = new Database();
            $db = $database->getConnection();
            
            if (!$db) {
                $login_err = "Erreur de connexion à la base de données. Veuillez réessayer plus tard.";
                error_log("Erreur de connexion à la base de données lors de la tentative de connexion");
            } else {
                // Créer une instance de User
                $user = new User($db);
                
                // Vérifier que la table user existe
                if ($user->checkAndCreateUserTable()) {
                    // Tenter de connecter l'utilisateur
                    if($user->login($email, $password)) {
                        // Stocker les données en session
                        $_SESSION["user_id"] = $user->id;
                        $_SESSION["user_nickname"] = $user->nickname;
                        $_SESSION["user_email"] = $user->email;
                        $_SESSION["last_activity"] = time();
                        
                        // Journaliser la connexion réussie
                        error_log("Connexion réussie pour l'utilisateur: {$user->nickname} (ID: {$user->id})");
                        
                        // Vérifier s'il y a une page de redirection
                        if (isset($_GET['redirect']) && !empty($_GET['redirect'])) {
                            $redirect = $_GET['redirect'];
                            // S'assurer que la redirection reste dans le dossier admin
                            if (strpos($redirect, '/admin/') === 0) {
                                header("Location: " . $redirect);
                                exit;
                            }
                        }
                        
                        // Redirection par défaut vers le dashboard
                        header("Location: index.php");
                        exit;
                    } else {
                        $login_err = "Email ou mot de passe incorrect.";
                        error_log("Tentative de connexion échouée pour l'email: $email");
                    }
                } else {
                    $login_err = "Erreur lors de la vérification de la table utilisateur.";
                    error_log("Erreur lors de la vérification de la table user");
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<!-- Page de connexion compatible avec la nouvelle structure de base de données -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Connexion - Administration</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dark-mode.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --admin-primary: #0066CC;
            --admin-secondary: #004999;
            --admin-success: #28a745;
            --admin-danger: #dc3545;
            --admin-warning: #ffc107;
            --admin-info: #17a2b8;
        }
        
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: var(--background-color);
            padding: 20px;
        }
        
        .login-container {
            width: 100%;
            max-width: 400px;
            background: var(--card-bg);
            border-radius: var(--border-radius);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            transition: all var(--transition-speed);
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-header h1 {
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
            color: var(--admin-primary);
        }
        
        .login-form .form-group {
            margin-bottom: 1.5rem;
        }
        
        .login-form label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }
        
        .login-form input {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: var(--border-radius);
            background: rgba(255, 255, 255, 0.8);
            transition: border-color var(--transition-speed);
        }
        
        .login-form input:focus {
            outline: none;
            border-color: var(--admin-primary);
            box-shadow: 0 0 0 2px rgba(0, 102, 204, 0.2);
        }
        
        .login-form .invalid-feedback {
            color: var(--admin-danger);
            font-size: 0.85rem;
            margin-top: 0.25rem;
        }
        
        .login-form .btn-login {
            width: 100%;
            padding: 0.8rem;
            background: var(--admin-primary);
            color: white;
            border: none;
            border-radius: var(--border-radius);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background var(--transition-speed);
        }
        
        .login-form .btn-login:hover {
            background: var(--admin-secondary);
        }
        
        .alert {
            padding: 1rem;
            border-radius: var(--border-radius);
            margin-bottom: 1.5rem;
            font-weight: 500;
        }
        
        .alert-danger {
            background-color: rgba(220, 53, 69, 0.1);
            color: var(--admin-danger);
            border: 1px solid rgba(220, 53, 69, 0.2);
        }
        
        .back-to-site {
            text-align: center;
            margin-top: 1.5rem;
        }
        
        .back-to-site a {
            color: var(--admin-primary);
            text-decoration: none;
            font-weight: 500;
            transition: color var(--transition-speed);
        }
        
        .back-to-site a:hover {
            color: var(--admin-secondary);
            text-decoration: underline;
        }
        
        /* Dark mode adjustments */
        .dark-mode .login-container {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        }
        
        .dark-mode .login-form input {
            background: rgba(30, 30, 30, 0.8);
            border-color: rgba(255, 255, 255, 0.1);
            color: var(--text-color);
        }
    </style>
</head>
<body class="light-mode">
    <div class="login-container">
        <div class="login-header">
            <h1>Administration</h1>
            <p>Connectez-vous pour accéder au tableau de bord</p>
        </div>
        
        <?php if(!empty($login_err)): ?>
            <div class="alert alert-danger"><?php echo $login_err; ?></div>
        <?php endif; ?>
        
        <form class="login-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" novalidate>
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required autofocus>
                <?php if(!empty($email_err)): ?>
                    <div class="invalid-feedback"><?php echo $email_err; ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
                <?php if(!empty($password_err)): ?>
                    <div class="invalid-feedback"><?php echo $password_err; ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn-login">Se connecter</button>
            </div>
        </form>
        
        <div class="back-to-site">
            <a href="../index.php"><i class="fas fa-arrow-left"></i> Retour au site</a>
        </div>
    </div>
    
    <script>
        // Vérifier si le mode sombre est activé dans le localStorage
        document.addEventListener('DOMContentLoaded', function() {
            const darkModeEnabled = localStorage.getItem('darkMode') === 'enabled';
            if (darkModeEnabled) {
                document.body.classList.remove('light-mode');
                document.body.classList.add('dark-mode');
            }
        });
    </script>
</body>
</html>
