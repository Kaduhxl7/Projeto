<?php
require_once __DIR__ . '/../app/config/bootstrap.php';
require_once '../app/controllers/ProductController.php';

$categoria = $_GET['cat'] ?? '';

if (empty($categoria)) {
    header('Location: /index.php');
    exit;
}

$controller = new ProductController();
$controller->listByCategory($categoria);
?>