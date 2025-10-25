<?php
/**
 * Exemplo de uso da nova arquitetura Strategy + Decorator
 * 
 * Este arquivo demonstra como usar a nova arquitetura de busca
 * implementada com os padrões Strategy e Decorator.
 */

require_once __DIR__ . '/../app/config/DatabaseConnection.php';
require_once __DIR__ . '/../app/repositories/ProductRepository.php';
require_once __DIR__ . '/../app/search/BuscarProdutosStrategy.php';

// 1. Obter conexão (Singleton)
$db = DatabaseConnection::getInstance();

// 2. Criar repository com injeção de dependência
$productRepository = new ProductRepository($db);

// 3. Definir filtros de busca
$filters = [
    'categoria' => 'feminino',
    'preco_max' => 100.00,
    'marca' => 'Nike',
    'cor' => 'azul'
];

// 4. Criar estratégia de busca
$searchStrategy = new BuscarProdutosStrategy($productRepository, $filters);

// 5. Executar busca (Strategy aplicará Decorators automaticamente)
$produtos = $searchStrategy->search();

// 6. Exibir resultados
echo "Encontrados " . count($produtos) . " produtos:\n";
foreach ($produtos as $produto) {
    echo "- {$produto['nome']} - R$ {$produto['preco']}\n";
}

// Exemplo de favoritar produto
if (!empty($produtos)) {
    $produto_id = $produtos[0]['id'];
    $usuario_id = 1; // Exemplo
    
    // Favoritar
    $sucesso = $productRepository->favoritarProduto($produto_id, $usuario_id);
    echo $sucesso ? "Produto favoritado!\n" : "Erro ao favoritar\n";
    
    // Verificar se é favorito
    $isFavorito = $productRepository->isFavorito($produto_id, $usuario_id);
    echo $isFavorito ? "É favorito\n" : "Não é favorito\n";
    
    // Listar favoritos do usuário
    $favoritos = $productRepository->listarFavoritos($usuario_id);
    echo "Usuário tem " . count($favoritos) . " favoritos\n";
}

/**
 * Vantagens da nova arquitetura:
 * 
 * 1. FLEXIBILIDADE: Filtros aplicados dinamicamente
 * 2. PERFORMANCE: Filtros no SQL, não em memória
 * 3. MANUTENIBILIDADE: Fácil adicionar novos filtros
 * 4. SEPARAÇÃO: SQL apenas nos repositories
 * 5. INJEÇÃO: PDO injetado via construtor
 * 6. RESPONSABILIDADE: Favoritos no ProductController
 */
?>