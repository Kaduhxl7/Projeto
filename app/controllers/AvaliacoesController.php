<?php
require_once '../app/config/database.php';

class AvaliacoesController {
    private $conn;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        if (!$this->conn) {
            throw new Exception('Erro na conexão com o banco de dados');
        }
    }
    
    public function adicionarAvaliacao($usuario_id, $produto_id, $nota, $comentario) {
        try {
            $sql = "INSERT INTO avaliacoes (usuario_id, produto_id, nota, comentario) VALUES (?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$usuario_id, $produto_id, $nota, $comentario]);
            return ['status' => 'success', 'message' => 'Avaliação adicionada com sucesso!'];
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                return ['status' => 'error', 'message' => 'Você já avaliou este produto!'];
            }
            return ['status' => 'error', 'message' => 'Erro ao adicionar avaliação'];
        }
    }
    
    public function listarAvaliacoes($produto_id) {
        $sql = "SELECT a.*, u.nome as usuario_nome 
                FROM avaliacoes a 
                JOIN usuarios u ON a.usuario_id = u.id 
                WHERE a.produto_id = ? 
                ORDER BY a.created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$produto_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getEstatisticas($produto_id) {
        $sql = "SELECT 
                    COUNT(*) as total_avaliacoes,
                    AVG(nota) as media_nota,
                    SUM(CASE WHEN nota = 5 THEN 1 ELSE 0 END) as nota_5,
                    SUM(CASE WHEN nota = 4 THEN 1 ELSE 0 END) as nota_4,
                    SUM(CASE WHEN nota = 3 THEN 1 ELSE 0 END) as nota_3,
                    SUM(CASE WHEN nota = 2 THEN 1 ELSE 0 END) as nota_2,
                    SUM(CASE WHEN nota = 1 THEN 1 ELSE 0 END) as nota_1
                FROM avaliacoes 
                WHERE produto_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$produto_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function jaAvaliou($usuario_id, $produto_id) {
        $sql = "SELECT COUNT(*) FROM avaliacoes WHERE usuario_id = ? AND produto_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$usuario_id, $produto_id]);
        return $stmt->fetchColumn() > 0;
    }
}
?>