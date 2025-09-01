<?php
session_start();
header('Content-Type: application/json');

try {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['tema' => 'claro', 'tamanho_fonte' => 'medio']);
        exit;
    }
    
    require_once '../app/controllers/ConfiguracoesController.php';
    $configController = new ConfiguracoesController();
    $config = $configController->getConfiguracoes($_SESSION['user_id']);
    
    echo json_encode($config);
    
} catch (Exception $e) {
    echo json_encode(['tema' => 'claro', 'tamanho_fonte' => 'medio']);
}
?>