<?php
try {
    require_once '../app/controllers/AvaliacoesController.php';
    
    header('Content-Type: application/json');
    
    $produto_id = $_GET['produto_id'] ?? null;
    
    if (!$produto_id) {
        echo json_encode(['error' => 'ID do produto não informado']);
        exit;
    }
    
    $avaliacoesController = new AvaliacoesController();
    
    $avaliacoes = $avaliacoesController->listarAvaliacoes($produto_id);
    $estatisticas = $avaliacoesController->getEstatisticas($produto_id);
    
    echo json_encode([
        'avaliacoes' => $avaliacoes,
        'estatisticas' => $estatisticas
    ]);
    
} catch (Exception $e) {
    echo json_encode(['error' => 'Erro interno: ' . $e->getMessage()]);
}
?>