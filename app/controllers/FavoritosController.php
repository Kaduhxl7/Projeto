<?php
require_once '../app/config/database.php';

class FavoritosController {
    private $conn;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        if (!$this->conn) {
            throw new Exception('Erro na conexão com o banco de dados');
        }
    }
    
    public function adicionarFavorito($usuario_id, $produto_id) {
        try {
            $sql = "INSERT INTO favoritos (usuario_id, produto_id) VALUES (?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$usuario_id, $produto_id]);
            return ['status' => 'success', 'message' => 'Produto adicionado aos favoritos!'];
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                return ['status' => 'error', 'message' => 'Produto já está nos favoritos!'];
            }
            return ['status' => 'error', 'message' => 'Erro ao adicionar favorito'];
        }
    }
    
    public function removerFavorito($usuario_id, $produto_id) {
        try {
            $sql = "DELETE FROM favoritos WHERE usuario_id = ? AND produto_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$usuario_id, $produto_id]);
            return ['status' => 'success', 'message' => 'Produto removido dos favoritos!'];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Erro ao remover favorito'];
        }
    }
    
    public function isFavorito($usuario_id, $produto_id) {
        $sql = "SELECT COUNT(*) FROM favoritos WHERE usuario_id = ? AND produto_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$usuario_id, $produto_id]);
        return $stmt->fetchColumn() > 0;
    }
    
    public function listarFavoritos($usuario_id) {
        $sql = "SELECT p.*, c.nome as categoria_nome 
                FROM favoritos f 
                JOIN produtos p ON f.produto_id = p.id 
                JOIN categorias c ON p.categoria_id = c.id 
                WHERE f.usuario_id = ? 
                ORDER BY f.created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$usuario_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>