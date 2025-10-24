<?php
require_once __DIR__ . '/../config/database.php';

class Payment {
    private $conn;
    private $table = 'pagamentos';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Criar novo pagamento
    public function create($data) {
        $query = "INSERT INTO {$this->table} 
                  (id_usuario, id_produto, valor, metodo_pagamento, codigo_transacao, gateway_id) 
                  VALUES (:id_usuario, :id_produto, :valor, :metodo_pagamento, :codigo_transacao, :gateway_id)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_usuario', $data['id_usuario']);
        $stmt->bindParam(':id_produto', $data['id_produto']);
        $stmt->bindParam(':valor', $data['valor']);
        $stmt->bindParam(':metodo_pagamento', $data['metodo_pagamento']);
        $stmt->bindParam(':codigo_transacao', $data['codigo_transacao']);
        $stmt->bindParam(':gateway_id', $data['gateway_id']);
        
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    // Buscar pagamento por ID
    public function findById($id) {
        $query = "SELECT p.*, u.nome as usuario_nome, pr.nome as produto_nome 
                  FROM {$this->table} p 
                  LEFT JOIN usuarios u ON p.id_usuario = u.id 
                  LEFT JOIN produtos pr ON p.id_produto = pr.id 
                  WHERE p.id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Buscar por código de transação
    public function findByTransaction($codigo_transacao) {
        $query = "SELECT * FROM {$this->table} WHERE codigo_transacao = :codigo_transacao";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':codigo_transacao', $codigo_transacao);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Atualizar status do pagamento
    public function updateStatus($id, $status, $gateway_response = null) {
        $query = "UPDATE {$this->table} 
                  SET status_pagamento = :status, 
                      gateway_response = :gateway_response,
                      data_pagamento = " . ($status === 'pago' ? 'NOW()' : 'data_pagamento') . "
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':gateway_response', $gateway_response);
        
        return $stmt->execute();
    }

    // Listar pagamentos do usuário
    public function getByUser($id_usuario, $limit = 10) {
        $query = "SELECT p.*, pr.nome as produto_nome 
                  FROM {$this->table} p 
                  LEFT JOIN produtos pr ON p.id_produto = pr.id 
                  WHERE p.id_usuario = :id_usuario 
                  ORDER BY p.data_criacao DESC 
                  LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Verificar se produto tem pagamento pendente
    public function hasPendingPayment($id_produto, $id_usuario) {
        $query = "SELECT id FROM {$this->table} 
                  WHERE id_produto = :id_produto 
                  AND id_usuario = :id_usuario 
                  AND status_pagamento IN ('pendente', 'pago')";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_produto', $id_produto);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

    // Obter configuração
    public function getConfig($chave) {
        $query = "SELECT valor FROM configuracoes_pagamento WHERE chave = :chave AND ativo = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':chave', $chave);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['valor'] : null;
    }
}