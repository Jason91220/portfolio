<?php
// Inclure le fichier de vérification d'authentification
require_once 'auth_check.php';

// Inclure les fichiers nécessaires
require_once 'config.php';
require_once 'classes/User.php';
require_once 'classes/Message.php';

// Créer une instance de Database
$database = new Database();
$db = $database->getConnection();

// Créer une instance de Message
$message = new Message($db);

// Obtenir les statistiques
$total_messages = $message->countAll();
$unread_messages = $message->countUnread();

// Inclure l'en-tête
include 'includes/header.php';
?>

<div class="content-header">
    <h1>Tableau de bord</h1>
    <div class="content-actions">
        <a href="messages.php" class="btn btn-primary">
            <i class="fas fa-envelope"></i> Voir les messages
        </a>
    </div>
</div>

<!-- Statistiques -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-card-inner">
            <div class="stat-icon">
                <i class="fas fa-envelope"></i>
            </div>
            <div class="stat-content">
                <h3 data-stat="total_messages"><?php echo $total_messages; ?></h3>
                <p>Messages totaux</p>
            </div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-card-inner">
            <div class="stat-icon">
                <i class="fas fa-envelope-open"></i>
            </div>
            <div class="stat-content">
                <h3 data-stat="unread_messages"><?php echo $unread_messages; ?></h3>
                <p>Messages non lus</p>
            </div>
        </div>
    </div>
</div>

<!-- Messages récents -->
<div class="card">
    <div class="card-header">
        <h2>Messages récents</h2>
        <a href="messages.php" class="btn btn-sm btn-primary">Voir tous</a>
    </div>
    
    <div class="table-responsive">
        <table class="messages-table">
            <thead>
                <tr>
                    <th class="status-column">État</th>
                    <th class="name-column">Nom</th>
                    <th class="email-column">Email</th>
                    <th class="subject-column">Sujet</th>
                    <th class="date-column">Date</th>
                    <th class="actions-column">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Récupérer les messages récents (limité à 5)
                $stmt = $message->getAll();
                $count = 0;
                
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    if ($count >= 5) break; // Limiter à 5 messages
                    
                    echo "<tr>";
                    echo "<td><span class='status-indicator " . ($row['is_read'] ? 'status-read' : 'status-unread') . "'></span>" . 
                         ($row['is_read'] ? 'Lu' : 'Non lu') . "</td>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td><a href='mailto:" . htmlspecialchars($row['email']) . "'>" . htmlspecialchars($row['email']) . "</a></td>";
                    echo "<td>" . htmlspecialchars($row['subject']) . "</td>";
                    echo "<td>" . date('d/m/Y H:i', strtotime($row['created_at'])) . "</td>";
                    echo "<td>";
                    echo "<a href='view_message.php?id=" . $row['id'] . "' class='btn btn-sm btn-primary'><i class='fas fa-eye'></i> Voir</a> ";
                    echo "</td>";
                    echo "</tr>";
                    
                    $count++;
                }
                
                if ($count == 0) {
                    echo "<tr><td colspan='6' style='text-align: center;'>Aucun message</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    /* Styles responsive pour le tableau de bord */
    .messages-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .status-indicator {
        display: inline-block;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        margin-right: 5px;
    }
    
    .status-read {
        background-color: #28a745;
    }
    
    .status-unread {
        background-color: #ffc107;
    }
    
    /* Responsive styles */
    @media (max-width: 1024px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
        
        .email-column {
            max-width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .subject-column {
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .content-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .content-actions {
            width: 100%;
        }
        
        .stat-card-inner {
            display: flex;
            align-items: center;
            width: 100%;
        }
        
        .stat-icon {
            margin-right: 1rem;
        }
    }
    
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }
        
        .messages-table {
            min-width: 650px;
        }
        
        .status-column {
            width: 80px;
        }
        
        .name-column, .email-column {
            max-width: 120px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .subject-column {
            max-width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .date-column {
            width: 100px;
        }
        
        .actions-column {
            width: 100px;
        }
        
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin: 0 -1rem;
            padding: 0 1rem;
            width: calc(100% + 2rem);
        }
        
        .stat-card {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
        
        .stat-card-inner {
            display: flex;
            align-items: center;
            width: 100%;
        }
        
        .stat-icon {
            margin-right: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    }
    
    @media (max-width: 480px) {
        .stat-card {
            padding: 0.75rem;
        }
        
        .stat-card-inner {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            text-align: center;
        }
        
        .messages-table td, .messages-table th {
            font-size: 0.9rem;
        }
        
        .stat-icon {
            width: 40px;
            height: 40px;
            font-size: 1.2rem;
        }
        
        .stat-content h3 {
            font-size: 1.5rem;
        }
        
        .card {
            padding: 0.75rem;
        }
        
        .card-header {
            padding: 0.75rem;
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
        
        .card-header h2 {
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
        }
        
        .btn-sm {
            padding: 0.2rem 0.4rem;
            font-size: 0.75rem;
        }
    }
</style>

<?php
// Inclure le pied de page
include 'includes/footer.php';
?>
