<?php
session_start();
require_once '../app/controllers/SecurityController.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Usuário não logado']);
    exit;
}

try {
    $security = new SecurityController();
    $result = $security->excluirDados($_SESSION['user_id']);
    
    if ($result['status'] === 'success') {
        // Destruir sessão
        session_destroy();
    }
    
    echo json_encode($result);
    
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erro interno']);
}
?>