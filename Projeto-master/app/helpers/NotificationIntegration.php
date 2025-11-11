<?php
require_once __DIR__ . '/../models/NotificationSimple.php';
require_once __DIR__ . '/../config/database.php';

class NotificationIntegration {
    private static $instance = null;
    private $notificationModel;
    
    private function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        $this->notificationModel = new NotificationSimple($db);
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Hook chamado quando um novo produto é criado
     */
    public static function onProductCreated($produto_id, $produto_nome, $brecho_id = null) {
        try {
            $integration = self::getInstance();
            $result = $integration->notificationModel->notifyNewProduct($produto_id, $produto_nome, $brecho_id);
            
            error_log("NotificationIntegration: Produto $produto_id criado, $result notificações enviadas");
            return $result;
        } catch (Exception $e) {
            error_log("NotificationIntegration Error: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Hook chamado quando uma promoção é criada
     */
    public static function onPromotionCreated($titulo, $descricao, $brecho_id = null, $brecho_nome = null) {
        try {
            $integration = self::getInstance();
            $result = $integration->notificationModel->notifyPromotion($titulo, $descricao, $brecho_id, $brecho_nome);
            
            error_log("NotificationIntegration: Promoção '$titulo' criada, $result notificações enviadas");
            return $result;
        } catch (Exception $e) {
            error_log("NotificationIntegration Error: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Hook chamado quando um brechó é atualizado
     */
    public static function onStoreUpdated($brecho_id, $brecho_nome) {
        try {
            $integration = self::getInstance();
            $result = $integration->notificationModel->notifyStoreUpdate($brecho_id, $brecho_nome);
            
            error_log("NotificationIntegration: Brechó '$brecho_nome' atualizado, $result notificações enviadas");
            return $result;
        } catch (Exception $e) {
            error_log("NotificationIntegration Error: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Verificar se deve enviar notificação baseado em configurações
     */
    private function shouldSendNotification($type, $user_id = null) {
        // Aqui você pode adicionar lógica para verificar se deve enviar
        // Por exemplo, verificar configurações do usuário, horário, etc.
        return true;
    }
    

}

// Função helper para facilitar o uso
function notify_new_product($produto_id, $produto_nome, $brecho_id = null) {
    return NotificationIntegration::onProductCreated($produto_id, $produto_nome, $brecho_id);
}

function notify_promotion($titulo, $descricao, $brecho_id = null, $brecho_nome = null) {
    return NotificationIntegration::onPromotionCreated($titulo, $descricao, $brecho_id, $brecho_nome);
}

function notify_store_update($brecho_id, $brecho_nome) {
    return NotificationIntegration::onStoreUpdated($brecho_id, $brecho_nome);
}
?>