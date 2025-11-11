<?php
require_once __DIR__ . '/../app/config/bootstrap.php';
require_once __DIR__ . '/../app/helpers/TranslationAuditor.php';

// Script de auditoria - só executar em desenvolvimento
if ($_SERVER['SERVER_NAME'] !== 'localhost') {
    die('Script apenas para desenvolvimento');
}

echo "<h1>Auditoria de Traduções</h1>";
echo "<p>Idioma atual: " . getCurrentLang() . "</p>";

// Lista de textos suspeitos para verificar
$suspiciousTexts = [
    'Blusa Vintage Floral',
    'Vestido Boho Chic', 
    'Feminino',
    'Masculino',
    'Artesanal',
    'Bege',
    'Floral',
    'Publicado em',
    'Avaliações',
    'Usuário Teste',
    'visualizações'
];

echo "<h2>Textos Suspeitos Encontrados:</h2>";
echo "<ul>";

foreach ($suspiciousTexts as $text) {
    $isPortuguese = TranslationAuditor::auditText($text);
    $translated = TranslationAuditor::autoTranslate($text);
    
    if ($isPortuguese) {
        echo "<li style='color: red;'>";
        echo "<strong>$text</strong> → ";
        echo "<em>$translated</em>";
        echo " (Precisa tradução)";
        echo "</li>";
    }
}

echo "</ul>";

echo "<h2>Teste de Traduções:</h2>";
echo "<ul>";
echo "<li>product.published_on: " . __('product.published_on') . "</li>";
echo "<li>product.reviews: " . __('product.reviews') . "</li>";
echo "<li>user.test_user: " . __('user.test_user') . "</li>";
echo "<li>categories.feminino: " . __('categories.feminino') . "</li>";
echo "</ul>";

echo "<h2>Cores e Tamanhos:</h2>";
$colors = ['preto', 'branco', 'azul', 'bege', 'floral'];
$sizes = ['p', 'm', 'g'];

echo "<h3>Cores:</h3><ul>";
foreach ($colors as $color) {
    echo "<li>$color → " . TranslationHelper::translateColor($color) . "</li>";
}
echo "</ul>";

echo "<h3>Tamanhos:</h3><ul>";
foreach ($sizes as $size) {
    echo "<li>$size → " . TranslationHelper::translateSize($size) . "</li>";
}
echo "</ul>";
?>