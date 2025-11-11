<?php
require_once __DIR__ . '/../entities/Category.php';

class CategoryRepository {
    private $pdo;
    private $table = 'categorias';

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function findAll() {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE ativo = 1 ORDER BY nome");
        $stmt->execute();
        
        $categories = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $categories[] = new Category($row['id'], $row['nome'], $row['slug'], $row['descricao'], $row['ativo']);
        }
        return $categories;
    }

    public function findBySlug($slug) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE slug = :slug AND ativo = 1");
        $stmt->bindParam(':slug', $slug);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;
        
        return new Category($row['id'], $row['nome'], $row['slug'], $row['descricao'], $row['ativo']);
    }

    public function findBySlugAsArray($slug) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE slug = :slug AND ativo = 1");
        $stmt->bindParam(':slug', $slug);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = :id AND ativo = 1");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;
        
        return new Category($row['id'], $row['nome'], $row['slug'], $row['descricao'], $row['ativo']);
    }

    public function save(Category $category) {
        if ($category->id) {
            return $this->update($category);
        } else {
            return $this->create($category);
        }
    }

    private function create(Category $category) {
        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (nome, slug, descricao, ativo) VALUES (:nome, :slug, :descricao, :ativo)");
        $stmt->bindParam(':nome', $category->nome);
        $stmt->bindParam(':slug', $category->slug);
        $stmt->bindParam(':descricao', $category->descricao);
        $stmt->bindParam(':ativo', $category->ativo);
        
        if ($stmt->execute()) {
            $category->id = $this->pdo->lastInsertId();
            return $category;
        }
        return false;
    }

    private function update(Category $category) {
        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET nome = :nome, slug = :slug, descricao = :descricao, ativo = :ativo WHERE id = :id");
        $stmt->bindParam(':id', $category->id);
        $stmt->bindParam(':nome', $category->nome);
        $stmt->bindParam(':slug', $category->slug);
        $stmt->bindParam(':descricao', $category->descricao);
        $stmt->bindParam(':ativo', $category->ativo);
        
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET ativo = 0 WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function getProductCount($slug = null) {
        if ($slug) {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM produtos p 
                                        JOIN categorias c ON p.categoria_id = c.id 
                                        WHERE c.slug = :slug AND p.status = 'Ativo'");
            $stmt->bindParam(':slug', $slug);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        } else {
            $stmt = $this->pdo->prepare("SELECT c.slug, c.nome, COUNT(p.id) as total 
                                        FROM categorias c 
                                        LEFT JOIN produtos p ON c.id = p.categoria_id AND p.status = 'Ativo'
                                        WHERE c.ativo = 1 
                                        GROUP BY c.id, c.slug, c.nome 
                                        ORDER BY c.nome");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
}
?>