<?php
require_once __DIR__ . '/../config/DatabaseConnection.php';

class Product {
    private $conn;
    private $table_name = "produtos";

    public $id;
    public $nome;
    public $descricao;
    public $preco;
    public $tamanho;
    public $cor;
    public $marca;
    public $condicao;
    public $categoria_id;
    public $imagem;
    public $status;
    public $visualizacoes;

    public function __construct($db = null) {
        // Usar Singleton se não for fornecida conexão específica
        $this->conn = $db ?? DatabaseConnection::getInstance();
    }

    // Buscar todos os produtos com filtros
    public function search($filters = []) {
        $query = "SELECT p.*, c.nome as categoria_nome 
                  FROM " . $this->table_name . " p 
                  LEFT JOIN categorias c ON p.categoria_id = c.id 
                  WHERE p.status = 'Ativo'";
        
        $params = [];
        
        // Filtro por categoria
        if (!empty($filters['categoria'])) {
            $query .= " AND c.slug = :categoria";
            $params[':categoria'] = $filters['categoria'];
        }
        
        // Filtro por busca
        if (!empty($filters['search'])) {
            $query .= " AND (p.nome LIKE :search OR p.descricao LIKE :search OR p.marca LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        
        // Filtro por cor
        if (!empty($filters['cor'])) {
            $query .= " AND p.cor LIKE :cor";
            $params[':cor'] = '%' . $filters['cor'] . '%';
        }
        
        // Filtro por tamanho
        if (!empty($filters['tamanho'])) {
            $query .= " AND p.tamanho = :tamanho";
            $params[':tamanho'] = $filters['tamanho'];
        }
        
        // Filtro por marca
        if (!empty($filters['marca'])) {
            $query .= " AND p.marca LIKE :marca";
            $params[':marca'] = '%' . $filters['marca'] . '%';
        }
        
        // Filtro por condição
        if (!empty($filters['condicao'])) {
            $query .= " AND p.condicao = :condicao";
            $params[':condicao'] = $filters['condicao'];
        }
        
        // Filtro por preço
        if (!empty($filters['preco_min'])) {
            $query .= " AND p.preco >= :preco_min";
            $params[':preco_min'] = $filters['preco_min'];
        }
        
        if (!empty($filters['preco_max'])) {
            $query .= " AND p.preco <= :preco_max";
            $params[':preco_max'] = $filters['preco_max'];
        }
        
        // Ordenação
        $orderBy = $filters['order'] ?? 'created_at';
        $orderDir = $filters['dir'] ?? 'DESC';
        $query .= " ORDER BY p.$orderBy $orderDir";
        
        // Paginação
        if (!empty($filters['limit'])) {
            $page = intval($filters['page'] ?? 1);
            $limit = intval($filters['limit']);
            $offset = ($page - 1) * $limit;
            $query .= " LIMIT $limit OFFSET $offset";
        }
        
        $stmt = $this->conn->prepare($query);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar produto por ID
    public function findById($id) {
        $query = "SELECT p.*, c.nome as categoria_nome, c.slug as categoria_slug 
                  FROM " . $this->table_name . " p 
                  LEFT JOIN categorias c ON p.categoria_id = c.id 
                  WHERE p.id = :id AND p.status = 'Ativo'";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Incrementar visualizações
    public function incrementViews($id) {
        $query = "UPDATE " . $this->table_name . " SET visualizacoes = visualizacoes + 1 WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Produtos relacionados
    public function getRelated($categoria_id, $produto_id, $limit = 4) {
        $query = "SELECT p.*, c.nome as categoria_nome 
                  FROM " . $this->table_name . " p 
                  LEFT JOIN categorias c ON p.categoria_id = c.id 
                  WHERE p.categoria_id = :categoria_id 
                  AND p.id != :produto_id 
                  AND p.status = 'Ativo' 
                  ORDER BY RAND() 
                  LIMIT " . intval($limit);
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':categoria_id', $categoria_id);
        $stmt->bindParam(':produto_id', $produto_id);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Contar produtos com filtros
    public function count($filters = []) {
        $query = "SELECT COUNT(*) as total 
                  FROM " . $this->table_name . " p 
                  LEFT JOIN categorias c ON p.categoria_id = c.id 
                  WHERE p.status = 'Ativo'";
        
        $params = [];
        
        if (!empty($filters['categoria'])) {
            $query .= " AND c.slug = :categoria";
            $params[':categoria'] = $filters['categoria'];
        }
        
        if (!empty($filters['search'])) {
            $query .= " AND (p.nome LIKE :search OR p.descricao LIKE :search OR p.marca LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        
        $stmt = $this->conn->prepare($query);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    // Obter filtros disponíveis
    public function getAvailableFilters($categoria = null) {
        $filters = [];
        
        // Cores disponíveis
        $query = "SELECT DISTINCT cor FROM " . $this->table_name . " WHERE status = 'Ativo' AND cor IS NOT NULL";
        if ($categoria) {
            $query .= " AND categoria_id = (SELECT id FROM categorias WHERE slug = ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$categoria]);
        } else {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
        }
        $filters['cores'] = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        // Tamanhos disponíveis
        $query = "SELECT DISTINCT tamanho FROM " . $this->table_name . " WHERE status = 'Ativo'";
        if ($categoria) {
            $query .= " AND categoria_id = (SELECT id FROM categorias WHERE slug = ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$categoria]);
        } else {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
        }
        $filters['tamanhos'] = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        // Marcas disponíveis
        $query = "SELECT DISTINCT marca FROM " . $this->table_name . " WHERE status = 'Ativo' AND marca IS NOT NULL";
        if ($categoria) {
            $query .= " AND categoria_id = (SELECT id FROM categorias WHERE slug = ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$categoria]);
        } else {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
        }
        $filters['marcas'] = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        return $filters;
    }
}
?>