<?php
require_once __DIR__ . '/../app/config/bootstrap.php';

if (isset($_GET['lang'])) {
    $lang = Language::getInstance();
    if ($lang->setLanguage($_GET['lang'])) {
        // Forçar recarregamento das traduções de produtos
        if (class_exists('ProductTranslator')) {
            ProductTranslator::reload();
        }
        
        // Redirecionar de volta para a página anterior, removendo parâmetros de idioma
        $redirect = $_SERVER['HTTP_REFERER'] ?? 'index.php';
        
        // Remover parâmetros de idioma da URL de redirecionamento
        $redirect = preg_replace('/[?&]lang=[^&]*/', '', $redirect);
        $redirect = preg_replace('/[?&]$/', '', $redirect);
        
        header("Location: $redirect");
        exit;
    }
}

// Se chegou aqui, houve erro
header("Location: index.php");
exit;
?>