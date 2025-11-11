<?php
require_once __DIR__ . '/../app/config/bootstrap.php';
require_once __DIR__ . '/../app/controllers/PaymentController.php';

$controller = new PaymentController();
$controller->history();
?>