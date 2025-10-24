<?php
session_start();

try {
    require_once '../app/controllers/ConfiguracoesController.php';
    
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'Usuário não logado']);
        exit;
    }
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Validar dados
    $configuracoes = [
        'tema' => in_array($input['tema'], ['claro', 'escuro']) ? $input['tema'] : 'claro',
        'cor_primaria' => preg_match('/^#[0-9A-Fa-f]{6}$/', $input['cor_primaria']) ? $input['cor_primaria'] : '#5e2b2b',
        'tamanho_fonte' => in_array($input['tamanho_fonte'], ['pequeno', 'medio', 'grande']) ? $input['tamanho_fonte'] : 'medio',
        'layout' => in_array($input['layout'], ['grid', 'lista']) ? $input['layout'] : 'grid',
        'produtos_por_pagina' => in_array($input['produtos_por_pagina'], [6, 12, 24]) ? $input['produtos_por_pagina'] : 12,
        'mostrar_precos' => (bool)$input['mostrar_precos'],
        'notificacoes' => (bool)$input['notificacoes']
    ];
    
    $configController = new ConfiguracoesController();
    $result = $configController->atualizarConfiguracoes($_SESSION['user_id'], $configuracoes);
    
    echo json_encode($result);
    
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erro interno: ' . $e->getMessage()]);
}
?>