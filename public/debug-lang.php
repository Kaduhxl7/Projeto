<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Debug Sistema de Idiomas</h2>";

try {
    session_start();
    echo "✅ Sessão iniciada<br>";
    
    require_once __DIR__ . '/../app/config/Language.php';
    echo "✅ Language.php carregado<br>";
    
    $lang = Language::getInstance();
    echo "✅ Language instance criada<br>";
    
    echo "Idioma atual: " . getCurrentLang() . "<br>";
    echo "Título: " . __('site.title') . "<br>";
    echo "Home: " . __('nav.home') . "<br>";
    
    echo "<br><strong>Teste de mudança:</strong><br>";
    echo "<a href='?lang=en'>English</a> | ";
    echo "<a href='?lang=es'>Español</a> | ";
    echo "<a href='?lang=fr'>Français</a> | ";
    echo "<a href='?lang=pt'>Português</a>";
    
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "<br>";
    echo "Arquivo: " . $e->getFile() . "<br>";
    echo "Linha: " . $e->getLine() . "<br>";
}
?>