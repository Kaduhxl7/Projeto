<?php
require_once '../app/controllers/ProductController.php';

$productController = new ProductController();
$productController->listarFavoritos();
?>