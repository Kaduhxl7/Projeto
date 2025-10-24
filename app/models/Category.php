<?php
require_once __DIR__ . '/../config/database.php';

class Category {
    private $conn;
    private $table_name = "categorias";

    public $id;
    public $nome;
    public $slug;
    public $descricao;
    public $ativo;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Buscar todas as categorias ativas
    public function getAll() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE ativo = 1 ORDER BY nome";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar categoria por slug
    public function findBySlug($slug) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE slug = :slug AND ativo = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':slug', $slug);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Buscar categoria por ID
    public function findById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id AND ativo = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Contar produtos por categoria
    public function getProductCount($slug = null) {
        if ($slug) {
            $query = "SELECT COUNT(*) as total FROM produtos p 
                      JOIN categorias c ON p.categoria_id = c.id 
                      WHERE c.slug = :slug AND p.status = 'Ativo'";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':slug', $slug);
        } else {
            $query = "SELECT c.slug, c.nome, COUNT(p.id) as total 
                      FROM categorias c 
                      LEFT JOIN produtos p ON c.id = p.categoria_id AND p.status = 'Ativo'
                      WHERE c.ativo = 1 
                      GROUP BY c.id, c.slug, c.nome 
                      ORDER BY c.nome";
            $stmt = $this->conn->prepare($query);
        }
        
        $stmt->execute();
        
        if ($slug) {
            return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        } else {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
}
?>