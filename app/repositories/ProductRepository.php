<?php
require_once __DIR__ . '/../entities/Product.php';

class ProductRepository {
    private $pdo;
    private $table = 'produtos';

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT p.*, c.nome as categoria_nome, c.slug as categoria_slug 
                                    FROM {$this->table} p 
                                    LEFT JOIN categorias c ON p.categoria_id = c.id 
                                    WHERE p.id = :id AND p.status = 'Ativo'");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;
        
        $product = new Product($row['id'], $row['nome'], $row['descricao'], $row['preco'],
                              $row['tamanho'], $row['cor'], $row['marca'], $row['condicao'],
                              $row['categoria_id'], $row['status'], $row['visualizacoes']);
        $product->created_at = $row['created_at'];
        $product->updated_at = $row['updated_at'];
        return $product;
    }

    public function findByIdAsArray($id) {
        $stmt = $this->pdo->prepare("SELECT p.*, c.nome as categoria_nome, c.slug as categoria_slug 
                                    FROM {$this->table} p 
                                    LEFT JOIN categorias c ON p.categoria_id = c.id 
                                    WHERE p.id = :id AND p.status = 'Ativo'");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function search($filters = []) {
        $query = "SELECT p.*, c.nome as categoria_nome 
                  FROM {$this->table} p 
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
        
        if (!empty($filters['cor'])) {
            $query .= " AND p.cor LIKE :cor";
            $params[':cor'] = '%' . $filters['cor'] . '%';
        }
        
        if (!empty($filters['tamanho'])) {
            $query .= " AND p.tamanho = :tamanho";
            $params[':tamanho'] = $filters['tamanho'];
        }
        
        if (!empty($filters['marca'])) {
            $query .= " AND p.marca LIKE :marca";
            $params[':marca'] = '%' . $filters['marca'] . '%';
        }
        
        if (!empty($filters['condicao'])) {
            $query .= " AND p.condicao = :condicao";
            $params[':condicao'] = $filters['condicao'];
        }
        
        if (!empty($filters['preco_min'])) {
            $query .= " AND p.preco >= :preco_min";
            $params[':preco_min'] = $filters['preco_min'];
        }
        
        if (!empty($filters['preco_max'])) {
            $query .= " AND p.preco <= :preco_max";
            $params[':preco_max'] = $filters['preco_max'];
        }
        
        $orderBy = $filters['order'] ?? 'created_at';
        $orderDir = $filters['dir'] ?? 'DESC';
        $query .= " ORDER BY p.$orderBy $orderDir";
        
        if (!empty($filters['limit'])) {
            $page = intval($filters['page'] ?? 1);
            $limit = intval($filters['limit']);
            $offset = ($page - 1) * $limit;
            $query .= " LIMIT $limit OFFSET $offset";
        }
        
        $stmt = $this->pdo->prepare($query);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function save(Product $product) {
        if ($product->id) {
            return $this->update($product);
        } else {
            return $this->create($product);
        }
    }

    private function create(Product $product) {
        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} 
                                    (nome, descricao, preco, tamanho, cor, marca, condicao, categoria_id, status, visualizacoes) 
                                    VALUES (:nome, :descricao, :preco, :tamanho, :cor, :marca, :condicao, :categoria_id, :status, :visualizacoes)");
        
        $stmt->bindParam(':nome', $product->nome);
        $stmt->bindParam(':descricao', $product->descricao);
        $stmt->bindParam(':preco', $product->preco);
        $stmt->bindParam(':tamanho', $product->tamanho);
        $stmt->bindParam(':cor', $product->cor);
        $stmt->bindParam(':marca', $product->marca);
        $stmt->bindParam(':condicao', $product->condicao);
        $stmt->bindParam(':categoria_id', $product->categoria_id);
        $stmt->bindParam(':status', $product->status);
        $stmt->bindParam(':visualizacoes', $product->visualizacoes);
        
        if ($stmt->execute()) {
            $product->id = $this->pdo->lastInsertId();
            return $product;
        }
        return false;
    }

    private function update(Product $product) {
        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET 
                                    nome = :nome, descricao = :descricao, preco = :preco, 
                                    tamanho = :tamanho, cor = :cor, marca = :marca, 
                                    condicao = :condicao, categoria_id = :categoria_id, 
                                    status = :status, visualizacoes = :visualizacoes 
                                    WHERE id = :id");
        
        $stmt->bindParam(':id', $product->id);
        $stmt->bindParam(':nome', $product->nome);
        $stmt->bindParam(':descricao', $product->descricao);
        $stmt->bindParam(':preco', $product->preco);
        $stmt->bindParam(':tamanho', $product->tamanho);
        $stmt->bindParam(':cor', $product->cor);
        $stmt->bindParam(':marca', $product->marca);
        $stmt->bindParam(':condicao', $product->condicao);
        $stmt->bindParam(':categoria_id', $product->categoria_id);
        $stmt->bindParam(':status', $product->status);
        $stmt->bindParam(':visualizacoes', $product->visualizacoes);
        
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET status = 'Inativo' WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function incrementViews($id) {
        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET visualizacoes = visualizacoes + 1 WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function getRelated($categoria_id, $produto_id, $limit = 4) {
        $stmt = $this->pdo->prepare("SELECT p.*, c.nome as categoria_nome 
                                    FROM {$this->table} p 
                                    LEFT JOIN categorias c ON p.categoria_id = c.id 
                                    WHERE p.categoria_id = :categoria_id 
                                    AND p.id != :produto_id 
                                    AND p.status = 'Ativo' 
                                    ORDER BY RAND() 
                                    LIMIT :limit");
        
        $stmt->bindParam(':categoria_id', $categoria_id);
        $stmt->bindParam(':produto_id', $produto_id);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function count($filters = []) {
        $query = "SELECT COUNT(*) as total 
                  FROM {$this->table} p 
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
        
        $stmt = $this->pdo->prepare($query);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getAvailableFilters($categoria = null) {
        $filters = [];
        
        $query = "SELECT DISTINCT cor FROM {$this->table} WHERE status = 'Ativo' AND cor IS NOT NULL";
        if ($categoria) {
            $query .= " AND categoria_id = (SELECT id FROM categorias WHERE slug = ?)";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$categoria]);
        } else {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
        }
        $filters['cores'] = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        $query = "SELECT DISTINCT tamanho FROM {$this->table} WHERE status = 'Ativo'";
        if ($categoria) {
            $query .= " AND categoria_id = (SELECT id FROM categorias WHERE slug = ?)";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$categoria]);
        } else {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
        }
        $filters['tamanhos'] = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        $query = "SELECT DISTINCT marca FROM {$this->table} WHERE status = 'Ativo' AND marca IS NOT NULL";
        if ($categoria) {
            $query .= " AND categoria_id = (SELECT id FROM categorias WHERE slug = ?)";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$categoria]);
        } else {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
        }
        $filters['marcas'] = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        return $filters;
    }

    // Métodos de favoritos movidos da FavoritosController
    public function favoritarProduto(int $idProduto, int $idUsuario): bool {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO favoritos (usuario_id, produto_id) VALUES (?, ?)");
            return $stmt->execute([$idUsuario, $idProduto]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function desfavoritarProduto(int $idProduto, int $idUsuario): bool {
        $stmt = $this->pdo->prepare("DELETE FROM favoritos WHERE usuario_id = ? AND produto_id = ?");
        return $stmt->execute([$idUsuario, $idProduto]);
    }

    public function isFavorito(int $idProduto, int $idUsuario): bool {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM favoritos WHERE usuario_id = ? AND produto_id = ?");
        $stmt->execute([$idUsuario, $idProduto]);
        return $stmt->fetchColumn() > 0;
    }

    public function listarFavoritos(int $idUsuario): array {
        $stmt = $this->pdo->prepare("SELECT p.*, c.nome as categoria_nome 
                                    FROM favoritos f 
                                    JOIN produtos p ON f.produto_id = p.id 
                                    JOIN categorias c ON p.categoria_id = c.id 
                                    WHERE f.usuario_id = ? 
                                    ORDER BY f.created_at DESC");
        $stmt->execute([$idUsuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para executar busca usando Decorator pattern
    public function executarBusca($search): array {
        $sql = $search->getSQL();
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($search->getParams());
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>