<?php

class NotificationSimple {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    /**
     * Criar nova notificação
     */
    public function create($id_usuario, $titulo, $mensagem, $tipo, $produto_id = null, $brecho_id = null) {
        $sql = "INSERT INTO notificacoes (id_usuario, titulo, mensagem, tipo, produto_id, brecho_id) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id_usuario, $titulo, $mensagem, $tipo, $produto_id, $brecho_id]);
    }
    
    /**
     * Criar notificação para todos os usuários que aceitam notificações
     */
    public function createForAllUsers($titulo, $mensagem, $tipo, $produto_id = null, $brecho_id = null) {
        $sql = "SELECT id FROM usuarios WHERE receber_notificacoes = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $usuarios = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        $created = 0;
        foreach ($usuarios as $id_usuario) {
            if ($this->create($id_usuario, $titulo, $mensagem, $tipo, $produto_id, $brecho_id)) {
                $created++;
            }
        }
        
        return $created;
    }
    
    /**
     * Buscar notificações do usuário (versão simples)
     */
    public function getByUser($id_usuario, $limit = 20) {
        $limit = (int) $limit;
        
        $sql = "SELECT * FROM notificacoes 
                WHERE id_usuario = ?
                ORDER BY data_envio DESC
                LIMIT $limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_usuario]);
        $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Adicionar informações extras se disponíveis
        foreach ($notifications as &$notification) {
            if ($notification['produto_id']) {
                $notification['produto_nome'] = $this->getProductName($notification['produto_id']);
            }
            if ($notification['brecho_id']) {
                $notification['brecho_nome'] = $this->getStoreInfo($notification['brecho_id']);
            }
        }
        
        return $notifications;
    }
    
    /**
     * Buscar nome do produto (se tabela existir)
     */
    private function getProductName($produto_id) {
        try {
            $stmt = $this->db->prepare("SELECT nome FROM produtos WHERE id = ?");
            $stmt->execute([$produto_id]);
            return $stmt->fetchColumn() ?: null;
        } catch (Exception $e) {
            return null;
        }
    }
    
    /**
     * Buscar informações do brechó (se tabela existir)
     */
    private function getStoreInfo($brecho_id) {
        try {
            $stmt = $this->db->prepare("SELECT nome FROM brechos WHERE id = ?");
            $stmt->execute([$brecho_id]);
            return $stmt->fetchColumn() ?: null;
        } catch (Exception $e) {
            return null;
        }
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
     * Excluir notificação
     */
    public function delete($id, $id_usuario) {
        $sql = "DELETE FROM notificacoes WHERE id = ? AND id_usuario = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id, $id_usuario]);
    }
    
    /**
     * Excluir todas as notificações do usuário
     */
    public function deleteAll($id_usuario) {
        $sql = "DELETE FROM notificacoes WHERE id_usuario = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id_usuario]);
    }
    
    /**
     * Notificar sobre novo produto
     */
    public function notifyNewProduct($produto_id, $produto_nome, $brecho_id = null) {
        $titulo = 'Novo produto disponível!';
        $mensagem = "Confira este novo produto que acabou de chegar: $produto_nome";
        
        return $this->createForAllUsers($titulo, $mensagem, 'novo_produto', $produto_id, $brecho_id);
    }
    
    /**
     * Notificar sobre promoção
     */
    public function notifyPromotion($titulo_promocao, $descricao, $brecho_id = null, $brecho_nome = null) {
        $titulo = $brecho_nome ? "Nova promoção no brechó $brecho_nome!" : "Nova promoção especial!";
        $mensagem = "$titulo_promocao: $descricao";
        
        return $this->createForAllUsers($titulo, $mensagem, 'promocao', null, $brecho_id);
    }
    
    /**
     * Notificar sobre atualização do brechó
     */
    public function notifyStoreUpdate($brecho_id, $brecho_nome) {
        $titulo = "O brechó $brecho_nome atualizou seus itens";
        $mensagem = "Novos produtos e atualizações disponíveis no brechó $brecho_nome";
        
        return $this->createForAllUsers($titulo, $mensagem, 'atualizacao_brecho', null, $brecho_id);
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