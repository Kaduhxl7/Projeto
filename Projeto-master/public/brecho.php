<?php
require_once __DIR__ . '/../app/config/bootstrap.php';
require_once __DIR__ . '/../app/controllers/BuscaController.php';

// Verificar se usuário está logado
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$controller = new BuscaController();
$id = $_GET['id'] ?? null;

if ($id) {
    $controller->show($id);
} else {
    header('Location: buscar.php');
    exit;
}
?>