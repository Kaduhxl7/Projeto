<?php
require_once __DIR__ . '/../app/controllers/DashboardController.php';

$controller = new DashboardController();

// Verificar se é uma ação de exportação
if (isset($_GET['action']) && $_GET['action'] === 'export') {
    $controller->exportarDados();
} else {
    $controller->index();
}
?>