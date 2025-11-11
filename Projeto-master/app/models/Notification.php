<?php

class Notification {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    /**
     * Criar nova notificação
     */
    public function create($id_usuario, $titulo, $mensagem, $tipo, $produto_id = null) {
        $sql = "INSERT INTO notificacoes (id_usuario, titulo, mensagem, tipo, produto_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id_usuario, $titulo, $mensagem, $tipo, $produto_id]);
    }
    
    /**
     * Criar notificação para todos os usuários que aceitam notificações
     */
    public function createForAllUsers($titulo, $mensagem, $tipo, $produto_id = null) {
        $sql = "SELECT id FROM usuarios WHERE receber_notificacoes = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $usuarios = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        $created = 0;
        foreach ($usuarios as $id_usuario) {
            if ($this->create($id_usuario, $titulo, $mensagem, $tipo, $produto_id)) {
                $created++;
            }
        }
        
        return $created;
    }
    
    /**
     * Buscar notificações do usuário com informações completas
     */
    public function getByUser($id_usuario, $limit = 20) {
        $limit = (int) $limit;
        
        $sql = "SELECT n.*, 
                       p.nome as produto_nome, 
                       p.imagem as produto_imagem,
                       p.preco as produto_preco,
                       b.nome as brecho_nome,
                       b.cidade as brecho_cidade
                FROM notificacoes n
                LEFT JOIN produtos p ON n.produto_id = p.id
                LEFT JOIN brechos b ON p.brecho_id = b.id
                WHERE n.id_usuario = ?
                ORDER BY n.data_envio DESC
                LIMIT $limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_usuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Contar notificações não lidas
     */
    public function countUnread($id_usuario) {
        $sql = "SELECT COUNT(*) FROM notificacoes WHERE id_usuario = ? AND lida = 0";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_usuario]);
        return $stmt->fetchColumn();
    }
    
    /**
     * Marcar como lida
     */
    public function markAsRead($id, $id_usuario) {
        $sql = "UPDATE notificacoes SET lida = 1 WHERE id = ? AND id_usuario = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id, $id_usuario]);
    }
    
    /**
     * Marcar todas como lidas
     */
    public function markAllAsRead($id_usuario) {
        $sql = "UPDATE notificacoes SET lida = 1 WHERE id_usuario = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id_usuario]);
    }
    
    /**
     * Notificar sobre novo produto
     */
    public function notifyNewProduct($produto_id, $produto_nome) {
        $titulo = 'Novo produto disponível!';
        $mensagem = 'Confira o novo produto: ' . $produto_nome;
        
        return $this->createForAllUsers($titulo, $mensagem, 'novo_produto', $produto_id);
    }
    
    /**
     * Notificar sobre promoção
     */
    public function notifyPromotion($titulo_promocao, $descricao) {
        $titulo = 'Nova promoção disponível!';
        $mensagem = $titulo_promocao . ': ' . $descricao;
        
        return $this->createForAllUsers($titulo, $mensagem, 'promocao');
    }
    
    /**
     * Notificar sobre atualização do sistema
     */
    public function notifySystemUpdate($titulo_update, $mensagem_update) {
        return $this->createForAllUsers($titulo_update, $mensagem_update, 'sistema');
    }
    
    /**
     * Obter ícone baseado no tipo de notificação
     */
    public function getNotificationIcon($tipo) {
        switch ($tipo) {
            case 'novo_produto':
                return '📦';
            case 'promocao':
                return '💸';
            case 'atualizacao_brecho':
                return '🏪';
            default:
                return '🔔';
        }
    }
    
    /**
     * Obter cor baseada no tipo de notificação
     */
    public function getNotificationColor($tipo) {
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
     * Limpar notificações antigas (mais de 30 dias)
     */
    public function cleanOldNotifications() {
        $sql = "DELETE FROM notificacoes WHERE data_envio < DATE_SUB(NOW(), INTERVAL 30 DAY)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute();
    }
    
    /**
     * Obter estatísticas de notificações
     */
    public function getStats($id_usuario) {
        $sql = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN lida = 0 THEN 1 ELSE 0 END) as nao_lidas,
                    SUM(CASE WHEN tipo = 'novo_produto' THEN 1 ELSE 0 END) as produtos,
                    SUM(CASE WHEN tipo = 'promocao' THEN 1 ELSE 0 END) as promocoes,
                    SUM(CASE WHEN tipo = 'atualizacao_brecho' THEN 1 ELSE 0 END) as atualizacoes
                FROM notificacoes 
                WHERE id_usuario = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_usuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>