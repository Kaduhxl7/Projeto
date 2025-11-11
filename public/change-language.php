<?php
require_once __DIR__ . '/../app/config/bootstrap.php';

if (isset($_GET['lang'])) {
    $lang = Language::getInstance();
    if ($lang->setLanguage($_GET['lang'])) {
        // Forçar recarregamento das traduções de produtos
        ProductTranslator::reload();
        
        // Redirecionar de volta para a página anterior
        $redirect = $_SERVER['HTTP_REFERER'] ?? 'index.php';
        header("Location: $redirect");
        exit;
    }
}

// Se chegou aqui, houve erro
header("Location: index.php");
exit;
?>