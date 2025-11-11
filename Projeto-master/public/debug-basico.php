<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Debug Básico</h2>";

// Testar se sessão funciona
session_start();
echo "✅ Sessão iniciada<br>";

// Testar se arquivo Language existe
$langFile = __DIR__ . '/../app/config/Language.php';
if (file_exists($langFile)) {
    echo "✅ Language.php existe<br>";
    require_once $langFile;
    echo "✅ Language.php carregado<br>";
} else {
    echo "❌ Language.php não encontrado em: $langFile<br>";
    exit;
}

// Testar se classe existe
if (class_exists('Language')) {
    echo "✅ Classe Language existe<br>";
    $lang = Language::getInstance();
    echo "✅ Instância criada<br>";
    echo "Idioma atual: " . $lang->getCurrentLanguage() . "<br>";
} else {
    echo "❌ Classe Language não existe<br>";
}

// Testar mudança manual
if (isset($_GET['test'])) {
    $lang->setLanguage($_GET['test']);
    echo "Tentou mudar para: " . $_GET['test'] . "<br>";
    echo "Idioma agora: " . $lang->getCurrentLanguage() . "<br>";
}

echo "<br><a href='?test=en'>Testar EN</a> | <a href='?test=es'>Testar ES</a>";
?>