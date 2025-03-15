<?php
// Vérifier si la session est démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté
if(!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

// Générer un jeton CSRF pour les formulaires
// Vérifier si la classe User est déjà incluse
if (!class_exists('User')) {
    require_once dirname(__DIR__) . '/classes/User.php';
}
$csrf_token = User::generateCsrfToken();

// Déterminer la page active
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Administration - <?php echo ucfirst(str_replace('.php', '', $current_page)); ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dark-mode.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/custom-cursor.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <style>
        :root {
            --admin-primary: #0066CC;
            --admin-secondary: #004999;
            --admin-success: #28a745;
            --admin-danger: #dc3545;
            --admin-warning: #ffc107;
            --admin-info: #17a2b8;
            --admin-sidebar-width: 250px;
        }
        
        body {
            display: flex;
            min-height: 100vh;
            background-color: var(--background-color);
            margin: 0;
            padding: 0;
        }
        
        /* Sidebar */
        .admin-sidebar {
            width: var(--admin-sidebar-width);
            background: var(--card-bg);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            transition: all var(--transition-speed);
            z-index: 1000;
        }
        
        .sidebar-header {
            padding: 1.5rem;
            text-align: center;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }
        
        .sidebar-header h1 {
            font-size: 1.5rem;
            margin: 0;
            color: var(--admin-primary);
        }
        
        .sidebar-nav {
            padding: 1rem 0;
        }
        
        .sidebar-nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .sidebar-nav li {
            margin-bottom: 0.5rem;
        }
        
        .sidebar-nav a {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: var(--text-color);
            text-decoration: none;
            transition: all var(--transition-speed);
            border-left: 3px solid transparent;
        }
        
        .sidebar-nav a:hover {
            background-color: rgba(0, 102, 204, 0.1);
            color: var(--admin-primary);
        }
        
        .sidebar-nav a.active {
            background-color: rgba(0, 102, 204, 0.15);
            border-left-color: var(--admin-primary);
            color: var(--admin-primary);
            font-weight: 600;
        }
        
        .sidebar-nav i {
            margin-right: 0.75rem;
            width: 20px;
            text-align: center;
        }
        
        .sidebar-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(0, 0, 0, 0.1);
            position: sticky;
            bottom: 0;
            background: var(--card-bg);
        }
        
        .sidebar-footer a {
            display: block;
            padding: 0.5rem 0;
            color: var(--text-color);
            text-decoration: none;
            transition: color var(--transition-speed);
        }
        
        .sidebar-footer a:hover {
            color: var(--admin-primary);
        }
        
        /* Main content */
        .admin-content {
            flex: 1;
            margin-left: var(--admin-sidebar-width);
            padding: 2rem;
            transition: margin-left var(--transition-speed);
        }
        
        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }
        
        .content-header h1 {
            font-size: 1.8rem;
            margin: 0;
            color: var(--admin-primary);
        }
        
        .content-actions {
            display: flex;
            gap: 1rem;
        }
        
        /* Cards */
        .card {
            background: var(--card-bg);
            border-radius: var(--border-radius);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: all var(--transition-speed);
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }
        
        .card-header h2 {
            font-size: 1.4rem;
            margin: 0;
        }
        
        /* Stats cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: var(--card-bg);
            border-radius: var(--border-radius);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            transition: all var(--transition-speed);
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: rgba(0, 102, 204, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
        }
        
        .stat-icon i {
            font-size: 1.5rem;
            color: var(--admin-primary);
        }
        
        .stat-content h3 {
            font-size: 1.8rem;
            margin: 0 0 0.25rem 0;
            font-weight: 700;
        }
        
        .stat-content p {
            margin: 0;
            color: var(--text-color-secondary);
            font-size: 0.9rem;
        }
        
        /* Tables */
        .table-responsive {
            overflow-x: auto;
            margin-bottom: 1.5rem;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        table th, table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }
        
        table th {
            background-color: rgba(0, 0, 0, 0.02);
            font-weight: 600;
        }
        
        table tr:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }
        
        /* Status indicators */
        .status-indicator {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 0.5rem;
        }
        
        .status-unread {
            background-color: var(--admin-danger);
        }
        
        .status-read {
            background-color: var(--admin-success);
        }
        
        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 1rem;
            border-radius: var(--border-radius);
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            transition: all var(--transition-speed);
            border: none;
        }
        
        .btn i {
            margin-right: 0.5rem;
        }
        
        .btn-primary {
            background-color: var(--admin-primary);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: var(--admin-secondary);
        }
        
        .btn-danger {
            background-color: var(--admin-danger);
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #bd2130;
        }
        
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        
        /* Alerts */
        .alert {
            padding: 1rem;
            border-radius: var(--border-radius);
            margin-bottom: 1.5rem;
        }
        
        .alert-success {
            background-color: rgba(40, 167, 69, 0.1);
            color: var(--admin-success);
            border: 1px solid rgba(40, 167, 69, 0.2);
        }
        
        .alert-danger {
            background-color: rgba(220, 53, 69, 0.1);
            color: var(--admin-danger);
            border: 1px solid rgba(220, 53, 69, 0.2);
        }
        
        /* Modal */
        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1050;
            opacity: 0;
            visibility: hidden;
            transition: all var(--transition-speed);
        }
        
        .modal-backdrop.show {
            opacity: 1;
            visibility: visible;
        }
        
        .modal-dialog {
            width: 100%;
            max-width: 500px;
            background: var(--card-bg);
            border-radius: var(--border-radius);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            transform: translateY(-20px);
            transition: transform var(--transition-speed);
        }
        
        .modal-backdrop.show .modal-dialog {
            transform: translateY(0);
        }
        
        .modal-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .modal-header h3 {
            margin: 0;
            font-size: 1.25rem;
        }
        
        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--text-color-secondary);
            transition: color var(--transition-speed);
        }
        
        .modal-close:hover {
            color: var(--admin-danger);
        }
        
        .modal-body {
            padding: 1.5rem;
        }
        
        .modal-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
        }
        
        /* Dark mode adjustments */
        .dark-mode .admin-sidebar {
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }
        
        .dark-mode .sidebar-header,
        .dark-mode .sidebar-footer {
            border-color: rgba(255, 255, 255, 0.1);
        }
        
        .dark-mode .content-header {
            border-color: rgba(255, 255, 255, 0.1);
        }
        
        .dark-mode .card-header {
            border-color: rgba(255, 255, 255, 0.1);
        }
        
        .dark-mode table th, 
        .dark-mode table td {
            border-color: rgba(255, 255, 255, 0.1);
        }
        
        .dark-mode table th {
            background-color: rgba(255, 255, 255, 0.05);
        }
        
        .dark-mode table tr:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }
        
        /* Responsive */
        .mobile-menu-toggle {
            display: none;
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1100;
            background-color: var(--admin-primary);
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            font-size: 1.2rem;
            cursor: pointer;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: all var(--transition-speed);
        }
        
        .mobile-menu-toggle:hover {
            background-color: var(--admin-secondary);
        }
        
        @media (max-width: 1024px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .content-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            
            .content-actions {
                width: 100%;
                justify-content: flex-start;
            }
        }
        
        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .admin-sidebar {
                transform: translateX(-100%);
                width: 80%;
                max-width: 300px;
                z-index: 1050;
            }
            
            .admin-sidebar.show {
                transform: translateX(0);
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
            }
            
            .admin-content {
                margin-left: 0;
                padding: 1.5rem;
                padding-top: 4rem;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .card {
                padding: 1rem;
                overflow: hidden;
            }
            
            .card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
            
            .table-responsive {
                margin-left: -1rem;
                margin-right: -1rem;
                width: calc(100% + 2rem);
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            
            table {
                min-width: 650px;
                width: 100%;
                table-layout: fixed;
            }
            
            table th, table td {
                padding: 0.75rem;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            
            table th:first-child, table td:first-child {
                padding-left: 1rem;
            }
            
            table th:last-child, table td:last-child {
                padding-right: 1rem;
            }
            
            .btn {
                padding: 0.4rem 0.8rem;
            }
            
            .btn-sm {
                padding: 0.2rem 0.4rem;
                font-size: 0.8rem;
            }
            
            /* Amélioration pour les tableaux sur mobile */
            .status-column {
                width: 70px;
            }
            
            .actions-column {
                width: 120px;
            }
        }
        
        @media (max-width: 480px) {
            .admin-content {
                padding: 0.75rem;
                padding-top: 4rem;
            }
            
            .content-header {
                margin-bottom: 1rem;
                padding-bottom: 0.75rem;
            }
            
            .content-header h1 {
                font-size: 1.5rem;
            }
            
            .content-actions {
                flex-wrap: wrap;
                gap: 0.5rem;
                justify-content: center;
                width: 100%;
            }
            
            .content-actions .btn {
                width: 100%;
                text-align: center;
                margin-bottom: 0.25rem;
            }
            
            .stat-card {
                padding: 0.75rem;
                display: flex;
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
            
            .stat-card-inner {
                display: flex;
                flex-direction: column;
                align-items: center;
            }
            
            .stat-icon {
                width: 40px;
                height: 40px;
                margin-bottom: 0.5rem;
            }
            
            .stat-content {
                text-align: center;
            }
            
            .stat-content h3 {
                font-size: 1.5rem;
                margin-bottom: 0.25rem;
            }
            
            .stat-content p {
                margin: 0;
                font-size: 0.9rem;
            }
            
            .table-responsive {
                margin-left: -0.75rem;
                margin-right: -0.75rem;
                width: calc(100% + 1.5rem);
            }
            
            .card {
                padding: 0.75rem;
                margin-bottom: 1rem;
            }
            
            .card-header {
                padding-bottom: 0.75rem;
                margin-bottom: 0.75rem;
            }
            
            .card-header h2 {
                font-size: 1.2rem;
            }
            
            .modal-dialog {
                width: 95%;
                max-width: none;
                margin: 0 auto;
            }
            
            .modal-header h3 {
                font-size: 1.2rem;
            }
            
            .modal-body {
                padding: 1rem;
            }
            
            .modal-footer {
                padding: 0.75rem 1rem;
            }
        }
    </style>
</head>
<body class="light-mode">
    <!-- Sidebar -->
    <aside class="admin-sidebar">
        <div class="sidebar-header">
            <h1>Administration</h1>
        </div>
        
        <nav class="sidebar-nav">
            <ul>
                <li>
                    <a href="index.php" class="<?php echo $current_page == 'index.php' ? 'active' : ''; ?>">
                        <i class="fas fa-tachometer-alt"></i> Tableau de bord
                    </a>
                </li>
                <li>
                    <?php
                    require_once dirname(__FILE__) . '/unread_messages.php';
                    $unread_count = getUnreadMessagesCount();
                    ?>
                    <a href="messages.php" class="<?php echo $current_page == 'messages.php' ? 'active' : ''; ?>">
                        <i class="fas fa-envelope"></i> Messages
                        <?php if ($unread_count > 0): ?>
                        <span class="unread-badge"><?php echo htmlspecialchars($unread_count); ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li>
                    <a href="projects.php" class="<?php echo $current_page == 'projects.php' ? 'active' : ''; ?>">
                        <i class="fas fa-project-diagram"></i> Projets
                    </a>
                </li>
            </ul>
        </nav>
        
        <div class="sidebar-footer">
            <a href="../index.php" target="_blank">
                <i class="fas fa-external-link-alt"></i> Voir le site
            </a>
            <a href="logout.php">
                <i class="fas fa-sign-out-alt"></i> Déconnexion
            </a>
        </div>
    </aside>
    
    <!-- Bouton menu mobile -->
    <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle menu">
        <i class="fas fa-bars"></i>
    </button>
    
    <!-- Main content -->
    <main class="admin-content">
        <!-- Le contenu spécifique de la page sera inséré ici -->

