<?php
session_start();

try {
    require_once '../app/controllers/AvaliacoesController.php';
    
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'Usuário não logado']);
        exit;
    }
    
    $input = json_decode(file_get_contents('php://input'), true);
    $produto_id = $input['produto_id'] ?? null;
    $nota = $input['nota'] ?? null;
    $comentario = $input['comentario'] ?? '';
    
    if (!$produto_id || !$nota) {
        echo json_encode(['status' => 'error', 'message' => 'Dados incompletos']);
        exit;
    }
    
    if ($nota < 1 || $nota > 5) {
        echo json_encode(['status' => 'error', 'message' => 'Nota deve ser entre 1 e 5']);
        exit;
    }
    
    $avaliacoesController = new AvaliacoesController();
    $usuario_id = $_SESSION['user_id'];
    
    $result = $avaliacoesController->adicionarAvaliacao($usuario_id, $produto_id, $nota, $comentario);
    echo json_encode($result);
    
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erro interno: ' . $e->getMessage()]);
}
?>