<?php
/**
 * Endpoint para resetar aceite (APENAS PARA TESTES)
 * Remover em produção
 */
require_once __DIR__ . '/../app/config/bootstrap.php';

header('Content-Type: application/json');

try {
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('Usuário não autenticado');
    }
    
    $stmt = $pdo->prepare("DELETE FROM aceite_termos WHERE id_usuario = ?");
    $stmt->execute([$_SESSION['user_id']]);
    
    echo json_encode([
        'status' => 'success',
        'message' => 'Aceite resetado com sucesso'
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
