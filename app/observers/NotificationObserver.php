<?php
/**
 * Padrão Observer - Observador de Notificações
 * 
 * Observador responsável por criar notificações no banco de dados
 * quando eventos de produtos ocorrem.
 */

require_once __DIR__ . '/Observer.php';
require_once __DIR__ . '/../config/DatabaseConnection.php';

class NotificationObserver implements Observer {
    private PDO $db;
    
    public function __construct() {
        $this->db = DatabaseConnection::getInstance();
    }
    
    /**
     * Processa eventos e cria notificações apropriadas
     */
    public function update(string $eventType, array $data): void {
        switch ($eventType) {
            case 'new_product':
                $this->handleNewProduct($data);
                break;
            case 'promotion':
                $this->handlePromotion($data);
                break;
            case 'store_update':
                $this->handleStoreUpdate($data);
                break;
            case 'product_promotion':
                $this->handleProductPromotion($data);
                break;
        }
    }
    
    /**
     * Cria notificações para novo produto
     */
    private function handleNewProduct(array $data): void {
        $titulo = "Novo produto disponível!";
        $mensagem = "O produto '{$data['product_name']}' foi adicionado ao catálogo.";
        
        $this->createNotificationForAllUsers(
            $titulo,
            $mensagem,
            'novo_produto',
            $data['product_id'],
            $data['brecho_id']
        );
    }
    
    /**
     * Cria notificações para promoção
     */
    private function handlePromotion(array $data): void {
        $brechoInfo = $data['brecho_name'] ? " do {$data['brecho_name']}" : "";
        $titulo = "Nova promoção{$brechoInfo}!";
        $mensagem = "{$data['title']}: {$data['description']}";
        
        $this->createNotificationForAllUsers(
            $titulo,
            $mensagem,
            'promocao',
            null,
            $data['brecho_id']
        );
    }
    
    /**
     * Cria notificações para atualização de brechó
     */
    private function handleStoreUpdate(array $data): void {
        $titulo = "Atualização no {$data['brecho_name']}";
        $mensagem = "O brechó {$data['brecho_name']} foi atualizado com novidades!";
        
        $this->createNotificationForAllUsers(
            $titulo,
            $mensagem,
            'atualizacao_brecho',
            null,
            $data['brecho_id']
        );
    }
    
    /**
     * Cria notificações para produto em promoção
     */
    private function handleProductPromotion(array $data): void {
        $titulo = "Produto em promoção!";
        $mensagem = "{$data['product_name']} com {$data['discount_percentage']}% de desconto! De R$ {$data['old_price']} por R$ {$data['new_price']}.";
        
        $this->createNotificationForAllUsers(
            $titulo,
            $mensagem,
            'promocao',
            $data['product_id'],
            null
        );
    }
    
    /**
     * Cria notificação para todos os usuários que aceitam notificações
     */
    private function createNotificationForAllUsers(string $titulo, string $mensagem, string $tipo, ?int $produtoId = null, ?int $brechoId = null): int {
        try {
            // Buscar usuários que aceitam notificações
            $sql = "SELECT id FROM usuarios WHERE receber_notificacoes = 1 OR receber_notificacoes IS NULL";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $usuarios = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            if (empty($usuarios)) {
                return 0;
            }
            
            // Preparar inserção em lote
            $placeholders = str_repeat('(?, ?, ?, ?, ?, ?),', count($usuarios));
            $placeholders = rtrim($placeholders, ',');
            
            $sql = "INSERT INTO notificacoes (id_usuario, titulo, mensagem, tipo, produto_id, brecho_id) VALUES {$placeholders}";
            $stmt = $this->db->prepare($sql);
            
            // Preparar dados para inserção
            $values = [];
            foreach ($usuarios as $userId) {
                $values[] = $userId;
                $values[] = $titulo;
                $values[] = $mensagem;
                $values[] = $tipo;
                $values[] = $produtoId;
                $values[] = $brechoId;
            }
            
            $stmt->execute($values);
            return count($usuarios);
            
        } catch (Exception $e) {
            error_log("Erro ao criar notificações: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Cria notificação para usuário específico
     */
    public function createNotificationForUser(int $userId, string $titulo, string $mensagem, string $tipo, ?int $produtoId = null, ?int $brechoId = null): bool {
        try {
            $sql = "INSERT INTO notificacoes (id_usuario, titulo, mensagem, tipo, produto_id, brecho_id) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$userId, $titulo, $mensagem, $tipo, $produtoId, $brechoId]);
        } catch (Exception $e) {
            error_log("Erro ao criar notificação para usuário {$userId}: " . $e->getMessage());
            return false;
        }
    }
}
?>