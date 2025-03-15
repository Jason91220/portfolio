<?php
function getUnreadMessagesCount($db = null) {
    $unread_count = 0;
    try {
        if ($db === null) {
            if (!class_exists('Database')) {
                require_once dirname(__DIR__) . '/config.php';
            }
            $database = new Database();
            $db = $database->getConnection();
        }
        
        if (!class_exists('Message')) {
            require_once dirname(__DIR__) . '/classes/Message.php';
        }
        
        $messageManager = new Message($db);
        return $messageManager->countUnread();
    } catch (Exception $e) {
        error_log('Erreur lors du comptage des messages non lus: ' . $e->getMessage());
        return 0;
    }
}
