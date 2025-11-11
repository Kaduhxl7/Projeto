<?php
require_once __DIR__ . '/../app/config/bootstrap.php';

// Script para gerar traduções de produtos - só executar em desenvolvimento
if ($_SERVER['SERVER_NAME'] !== 'localhost') {
    die('Script apenas para desenvolvimento');
}

echo "<h1>Gerador de Traduções de Produtos</h1>";

// Produtos de exemplo para traduzir
$produtos = [
    'Blusa Vintage Floral' => [
        'en' => 'Vintage Floral Blouse',
        'es' => 'Blusa Vintage Floral', 
        'fr' => 'Blouse Vintage Florale'
    ],
    'Vestido Boho Chic' => [
        'en' => 'Boho Chic Dress',
        'es' => 'Vestido Boho Chic',
        'fr' => 'Robe Boho Chic'
    ],
    'Calça Jeans Destroyed' => [
        'en' => 'Destroyed Jeans',
        'es' => 'Pantalón Jeans Destroyed',
        'fr' => 'Jean Destroyed'
    ]
];

$descricoes = [
    'Peça única com estilo vintage autêntico' => [
        'en' => 'Unique piece with authentic vintage style',
        'es' => 'Pieza única con estilo vintage auténtico',
        'fr' => 'Pièce unique au style vintage authentique'
    ],
    'Tecido macio e confortável para uso diário' => [
        'en' => 'Soft and comfortable fabric for daily wear',
        'es' => 'Tejido suave y cómodo para uso diario',
        'fr' => 'Tissu doux et confortable pour un usage quotidien'
    ]
];

echo "<h2>Produtos Traduzidos:</h2>";
echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>Português</th><th>English</th><th>Español</th><th>Français</th></tr>";

foreach ($produtos as $pt => $translations) {
    echo "<tr>";
    echo "<td>$pt</td>";
    echo "<td>{$translations['en']}</td>";
    echo "<td>{$translations['es']}</td>";
    echo "<td>{$translations['fr']}</td>";
    echo "</tr>";
}

echo "</table>";

echo "<h2>Como Usar:</h2>";
echo "<ol>";
echo "<li>Adicione novos produtos nos arquivos <code>products_*.php</code></li>";
echo "<li>Use a chave gerada automaticamente (slug do nome)</li>";
echo "<li>Teste com <code>ProductTranslator::translateName('Nome do Produto')</code></li>";
echo "</ol>";

echo "<h2>Teste de Tradução:</h2>";
echo "<p>Idioma atual: " . getCurrentLang() . "</p>";
echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>Original</th><th>Traduzido</th><th>Descrição</th></tr>";

$produtos_teste = [
    'Blusa Vintage Floral' => 'Peça única com estilo vintage autêntico',
    'Vestido Boho Chic' => 'Design moderno e elegante para todas as ocasiões',
    'Tênis All Star Vintage' => 'Item raro para colecionadores',
    'Bolsa de Couro Artesanal' => 'Qualidade premium e durável'
];

foreach ($produtos_teste as $nome => $desc) {
    echo "<tr>";
    echo "<td>$nome</td>";
    echo "<td>" . ProductTranslator::translateName($nome) . "</td>";
    echo "<td>" . ProductTranslator::translateDescription($desc) . "</td>";
    echo "</tr>";
}
echo "</table>";

function createKey($text) {
    $key = strtolower($text);
    $key = preg_replace('/[^a-z0-9\s]/', '', $key);
    $key = preg_replace('/\s+/', '_', $key);
    return trim($key, '_');
}

echo "<h2>Chaves Geradas:</h2>";
echo "<ul>";
foreach ($produtos as $nome => $translations) {
    echo "<li>'$nome' → '" . createKey($nome) . "'</li>";
}
echo "</ul>";
?>