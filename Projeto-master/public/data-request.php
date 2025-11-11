<?php
session_start();
require_once '../app/controllers/SecurityController.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Usuário não logado']);
    exit;
}

try {
    $input = json_decode(file_get_contents('php://input'), true);
    $tipo = $input['tipo'] ?? '';
    
    if (!in_array($tipo, ['acesso', 'correcao', 'exclusao', 'portabilidade'])) {
        echo json_encode(['status' => 'error', 'message' => 'Tipo de solicitação inválido']);
        exit;
    }
    
    $security = new SecurityController();
    $result = $security->solicitarDados($_SESSION['user_id'], $tipo);
    
    echo json_encode($result);
    
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erro interno']);
}
?>