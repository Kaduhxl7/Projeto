<?php
/**
 * Endpoint para processar aceite de termos
 */
require_once __DIR__ . '/../app/config/bootstrap.php';
require_once __DIR__ . '/../app/controllers/TermoController.php';

// Instanciar controller
$controller = new TermoController($pdo);

// Processar requisição
$controller->registrarAceite();
