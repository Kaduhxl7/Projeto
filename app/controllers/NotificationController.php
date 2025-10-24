<?php
require_once __DIR__ . '/../config/bootstrap.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/NotificationSimple.php';

class NotificationController {
    private $notificationModel;
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->notificationModel = new NotificationSimple($this->db);
    }
    
    /**
     * Página principal de notificações
     */
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php');
            exit;
        }
        
        $user_id = $_SESSION['user_id'];
        $notifications = $this->notificationModel->getByUser($user_id);
        $stats = $this->notificationModel->getStats($user_id);
        
        $page_title = __('notifications.title');
        $page_description = __('notifications.description');
        
        $data = [
            'notifications' => $notifications,
            'stats' => $stats
        ];
        
        include __DIR__ . '/../views/notifications-simple.php';
    }
    
    /**
     * API para buscar notificações (AJAX)
     */
    public function getNotifications() {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['error' => 'Not authenticated']);
            return;
        }
        
        $user_id = $_SESSION['user_id'];
        $notifications = $this->notificationModel->getByUser($user_id, 10);
        $unread_count = $this->notificationModel->countUnread($user_id);
        
        // Adicionar ícones e cores às notificações
        foreach ($notifications as &$notification) {
            $notification['icon'] = $this->getNotificationIcon($notification['tipo']);
            $notification['color'] = $this->getNotificationColor($notification['tipo']);
        }
        
        echo json_encode([
            'notifications' => $notifications,
            'unread_count' => $unread_count
        ]);
    }
    
    /**
     * Marcar notificação como lida
     */
    public function markAsRead() {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['error' => 'Not authenticated']);
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        $notification_id = $input['id'] ?? null;
        
        if (!$notification_id) {
            echo json_encode(['error' => 'Invalid notification ID']);
            return;
        }
        
        $success = $this->notificationModel->markAsRead($notification_id, $_SESSION['user_id']);
        
        echo json_encode(['success' => $success]);
    }
    
    /**
     * Marcar todas como lidas
     */
    public function markAllAsRead() {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['error' => 'Not authenticated']);
            return;
        }
        
        $success = $this->notificationModel->markAllAsRead($_SESSION['user_id']);
        
        echo json_encode(['success' => $success]);
    }
    
    /**
     * Excluir notificação
     */
    public function deleteNotification() {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['error' => 'Not authenticated']);
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        $notification_id = $input['id'] ?? null;
        
        if (!$notification_id) {
            echo json_encode(['error' => 'Invalid notification ID']);
            return;
        }
        
        $success = $this->notificationModel->delete($notification_id, $_SESSION['user_id']);
        
        echo json_encode(['success' => $success]);
    }
    
    /**
     * Excluir todas as notificações
     */
    public function deleteAllNotifications() {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['error' => 'Not authenticated']);
            return;
        }
        
        $success = $this->notificationModel->deleteAll($_SESSION['user_id']);
        
        echo json_encode(['success' => $success]);
    }
    
    /**
     * Enviar notificação de promoção
     */
    public function sendPromotion() {
        header('Content-Type: application/json');
        
        try {
            if (!isset($_SESSION['user_id'])) {
                throw new Exception('Not authenticated');
            }
            
            $input = json_decode(file_get_contents('php://input'), true);
            if (!$input) {
                throw new Exception('Invalid JSON data');
            }
            
            $titulo = trim($input['titulo'] ?? '');
            $descricao = trim($input['descricao'] ?? '');
            
            if (empty($titulo) || empty($descricao)) {
                throw new Exception('Título e descrição são obrigatórios');
            }
            
            $created = $this->notificationModel->notifyPromotion($titulo, $descricao, null, 'Brechó');
            
            echo json_encode([
                'success' => true,
                'notifications_sent' => $created,
                'message' => "Notificação enviada para $created usuários!"
            ]);
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Criar notificação de novo produto (chamado automaticamente)
     */
    public function notifyNewProduct($produto_id, $produto_nome, $brecho_id = null) {
        return $this->notificationModel->notifyNewProduct($produto_id, $produto_nome, $brecho_id);
    }
    
    /**
     * Criar notificação de promoção (chamado automaticamente)
     */
    public function notifyPromotion($titulo, $descricao, $brecho_id = null, $brecho_nome = null) {
        return $this->notificationModel->notifyPromotion($titulo, $descricao, $brecho_id, $brecho_nome);
    }
    
    /**
     * Criar notificação de atualização do brechó
     */
    public function notifyStoreUpdate($brecho_id, $brecho_nome) {
        return $this->notificationModel->notifyStoreUpdate($brecho_id, $brecho_nome);
    }
    
    /**
     * Obter ícone baseado no tipo de notificação
     */
    private function getNotificationIcon($tipo) {
        switch ($tipo) {
            case 'novo_produto':
                return '📦';
            case 'promocao':
                return '💸';
            case 'atualizacao_brecho':
                return '🏦';
            default:
                return '🔔';
        }
    }
    
    /**
     * Obter cor baseada no tipo de notificação
     */
    private function getNotificationColor($tipo) {
        switch ($tipo) {
            case 'novo_produto':
                return '#28a745';
            case 'promocao':
                return '#dc3545';
            case 'atualizacao_brecho':
                return '#17a2b8';
            default:
                return '#6c757d';
        }
    }
    
    /**
     * Teste de envio de notificação
     */
    public function test() {
        if (!isset($_SESSION['user_id'])) {
            echo "Faça login para testar notificações";
            return;
        }
        
        // Testar diferentes tipos de notificação
        $result1 = $this->notifyNewProduct(1, "Produto de Teste");
        $result2 = $this->notifyPromotion("Promoção Teste", "Desconto especial de 50%", null, "Brechó Teste");
        $result3 = $this->notifyStoreUpdate(1, "Brechó Teste");
        
        echo "Notificações de teste enviadas!<br>";
        echo "Novo produto: $result1 notificações<br>";
        echo "Promoção: $result2 notificações<br>";
        echo "Atualização: $result3 notificações<br>";
        echo "<a href='notifications.php'>Ver notificações</a>";
    }
}
?>