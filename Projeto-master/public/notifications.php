<?php
require_once __DIR__ . '/../app/config/bootstrap.php';
require_once __DIR__ . '/../app/controllers/NotificationController.php';

$controller = new NotificationController();

// Verificar ações via GET/POST
$action = $_GET['action'] ?? $_POST['action'] ?? 'index';

switch ($action) {
    case 'get_notifications':
        $controller->getNotifications();
        break;
    case 'mark_read':
        $controller->markAsRead();
        break;
    case 'mark_all_read':
        $controller->markAllAsRead();
        break;
    case 'delete_notification':
        $controller->deleteNotification();
        break;
    case 'delete_all':
        $controller->deleteAllNotifications();
        break;
    case 'send_promotion':
        $controller->sendPromotion();
        break;
    case 'test':
        $controller->test();
        break;
    default:
        $controller->index();
        break;
}
?>