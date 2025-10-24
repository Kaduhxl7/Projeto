<?php
session_start();
require_once '../app/controllers/SecurityController.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    exit;
}

try {
    $security = new SecurityController();
    $dados = $security->exportarDados($_SESSION['user_id']);
    
    // Adicionar metadados
    $export = [
        'exportado_em' => date('Y-m-d H:i:s'),
        'usuario_id' => $_SESSION['user_id'],
        'dados' => $dados
    ];
    
    header('Content-Type: application/json');
    header('Content-Disposition: attachment; filename="meus-dados-dresscode.json"');
    
    echo json_encode($export, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao exportar dados']);
}
?>