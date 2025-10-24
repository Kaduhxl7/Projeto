<?php
require_once __DIR__ . '/../app/config/bootstrap.php';
require_once '../app/controllers/ProductController.php';

$controller = new ProductController();
$controller->search();
?>