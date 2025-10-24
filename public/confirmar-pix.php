<?php
require_once __DIR__ . '/../app/config/bootstrap.php';
require_once __DIR__ . '/../app/controllers/PaymentController.php';

header('Content-Type: application/json');

$controller = new PaymentController();
$controller->confirmPix();
?>