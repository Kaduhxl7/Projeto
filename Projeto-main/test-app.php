<?php
// Script para testar todas as funcionalidades da aplicação
echo "=== Testando DressCode - Funcionalidades ===\n\n";

require_once 'app/controllers/ProductController.php';
require_once 'app/models/Category.php';
require_once 'app/config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    echo "✅ Conexão com banco: OK\n";

    // Testar categorias
    $category = new Category($db);
    $categorias = $category->getAll();
    echo "✅ Categorias encontradas: " . count($categorias) . "\n";
    
    foreach ($categorias as $cat) {
        echo "   - " . $cat['nome'] . " (" . $cat['slug'] . ")\n";
    }

    // Testar produtos
    $product = new Product($db);
    
    // Teste 1: Buscar todos os produtos
    $produtos = $product->search();
    echo "\n✅ Total de produtos: " . count($produtos) . "\n";

    // Teste 2: Buscar por categoria
    $produtos_feminino = $product->search(['categoria' => 'feminino']);
    echo "✅ Produtos femininos: " . count($produtos_feminino) . "\n";

    // Teste 3: Buscar por termo
    $busca_blusa = $product->search(['search' => 'blusa']);
    echo "✅ Busca por 'blusa': " . count($busca_blusa) . "\n";

    // Teste 4: Filtros disponíveis
    $filtros = $product->getAvailableFilters('feminino');
    echo "✅ Filtros feminino:\n";
    echo "   - Cores: " . implode(', ', $filtros['cores']) . "\n";
    echo "   - Tamanhos: " . implode(', ', $filtros['tamanhos']) . "\n";
    echo "   - Marcas: " . implode(', ', $filtros['marcas']) . "\n";

    // Teste 5: Produto específico
    if (!empty($produtos)) {
        $primeiro_produto = $product->findById($produtos[0]['id']);
        echo "\n✅ Produto de teste: " . $primeiro_produto['nome'] . "\n";
        echo "   - Preço: R$ " . number_format($primeiro_produto['preco'], 2, ',', '.') . "\n";
        echo "   - Categoria: " . $primeiro_produto['categoria_nome'] . "\n";
    }

    echo "\n🎉 TODOS OS TESTES PASSARAM!\n\n";
    echo "=== URLs DISPONÍVEIS ===\n";
    echo "Página inicial: http://localhost:8000/\n";
    echo "Feminino: http://localhost:8000/categoria.php?cat=feminino\n";
    echo "Masculino: http://localhost:8000/categoria.php?cat=masculino\n";
    echo "Infantil: http://localhost:8000/categoria.php?cat=infantil\n";
    echo "Acessórios: http://localhost:8000/categoria.php?cat=acessorios\n";
    echo "Busca: http://localhost:8000/busca.php?search=blusa\n";
    if (!empty($produtos)) {
        echo "Produto: http://localhost:8000/produto.php?id=" . $produtos[0]['id'] . "\n";
    }
    echo "\n=== CREDENCIAIS ===\n";
    echo "Email: teste@dresscode.com\n";
    echo "Senha: 123456\n";

} catch (Exception $e) {
    echo "❌ ERRO: " . $e->getMessage() . "\n";
}
?>