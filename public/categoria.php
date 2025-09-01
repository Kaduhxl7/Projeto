<?php
require_once '../app/controllers/ProductController.php';

$categoria = $_GET['cat'] ?? '';

if (empty($categoria)) {
    header('Location: /index.php');
    exit;
}

$controller = new ProductController();
$controller->listByCategory($categoria);
?>