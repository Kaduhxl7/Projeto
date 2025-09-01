<?php
session_start();

try {
    require_once '../app/controllers/FavoritosController.php';
    
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'Usuário não logado']);
        exit;
    }
    
    $input = json_decode(file_get_contents('php://input'), true);
    $produto_id = $input['produto_id'] ?? null;
    $action = $input['action'] ?? 'toggle';
    
    if (!$produto_id) {
        echo json_encode(['status' => 'error', 'message' => 'ID do produto não informado']);
        exit;
    }
    
    $favoritosController = new FavoritosController();
    $usuario_id = $_SESSION['user_id'];
    
    if ($action === 'remove') {
        $result = $favoritosController->removerFavorito($usuario_id, $produto_id);
    } else {
        // Toggle: se já é favorito, remove; se não é, adiciona
        if ($favoritosController->isFavorito($usuario_id, $produto_id)) {
            $result = $favoritosController->removerFavorito($usuario_id, $produto_id);
        } else {
            $result = $favoritosController->adicionarFavorito($usuario_id, $produto_id);
        }
    }
    
    echo json_encode($result);
    
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erro interno: ' . $e->getMessage()]);
}
?>