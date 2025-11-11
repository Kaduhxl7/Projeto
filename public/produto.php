<?php
require_once __DIR__ . '/../app/config/bootstrap.php';
require_once '../app/controllers/ProductController.php';

$id = $_GET['id'] ?? '';

if (empty($id) || !is_numeric($id)) {
    header('HTTP/1.0 404 Not Found');
    include '../app/views/errors/404.php';
    exit;
}

$controller = new ProductController();
$controller->show($id);
?>