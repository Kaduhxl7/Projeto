<?php
echo "=== Verificando Arquivos DressCode ===\n\n";

$files_to_check = [
    'public/index.php' => 'Página inicial',
    'public/categoria.php' => 'Página de categoria',
    'public/produto.php' => 'Página de produto',
    'public/busca.php' => 'Página de busca',
    'public/login.php' => 'Página de login',
    'public/cadastro.php' => 'Página de cadastro',
    'public/assets/css/style.css' => 'CSS principal',
    'public/assets/js/script.js' => 'JavaScript principal',
    'public/assets/images/Logo.png' => 'Logo',
    'app/controllers/ProductController.php' => 'Controller de produtos',
    'app/models/Product.php' => 'Model de produto',
    'app/models/Category.php' => 'Model de categoria',
    'app/config/database.php' => 'Configuração do banco',
    'app/views/products/category.php' => 'View de categoria',
    'app/views/products/detail.php' => 'View de detalhes',
    'includes/header.php' => 'Header',
    'includes/footer.php' => 'Footer'
];

foreach ($files_to_check as $file => $description) {
    if (file_exists($file)) {
        echo "✅ $description: $file\n";
    } else {
        echo "❌ $description: $file (NÃO ENCONTRADO)\n";
    }
}

echo "\n=== Testando Banco ===\n";
try {
    require_once 'app/config/database.php';
    $database = new Database();
    $db = $database->getConnection();
    
    $stmt = $db->query("SELECT COUNT(*) as total FROM produtos");
    $produtos = $stmt->fetch()['total'];
    
    $stmt = $db->query("SELECT COUNT(*) as total FROM categorias WHERE ativo = 1");
    $categorias = $stmt->fetch()['total'];
    
    echo "✅ Produtos: $produtos\n";
    echo "✅ Categorias: $categorias\n";
    
} catch (Exception $e) {
    echo "❌ Erro no banco: " . $e->getMessage() . "\n";
}

echo "\n🎯 Para testar: http://localhost:8000/test-links.php\n";
?>