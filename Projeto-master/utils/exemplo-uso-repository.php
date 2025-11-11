<?php
// EXEMPLO DE USO DOS REPOSITÓRIOS

require_once 'app/config/database.php';
require_once 'app/repositories/CategoryRepository.php';
require_once 'app/repositories/ProductRepository.php';

// Conexão com banco
$database = new Database();
$pdo = $database->getConnection();

// Instanciar repositórios
$categoryRepo = new CategoryRepository($pdo);
$productRepo = new ProductRepository($pdo);

// ===== EXEMPLOS DE USO =====

// 1. BUSCAR CATEGORIA POR SLUG
$categoria = $categoryRepo->findBySlug('feminino');
if ($categoria) {
    echo "Categoria encontrada: {$categoria->nome}<br>";
}

// 2. LISTAR TODAS AS CATEGORIAS
$categorias = $categoryRepo->findAll();
echo "Total de categorias: " . count($categorias) . "<br>";

// 3. CRIAR NOVA CATEGORIA
$novaCategoria = new Category(null, 'Esportivo', 'esportivo', 'Roupas esportivas');
$categoriaSalva = $categoryRepo->save($novaCategoria);
if ($categoriaSalva) {
    echo "Nova categoria criada com ID: {$categoriaSalva->id}<br>";
}

// 4. BUSCAR PRODUTOS COM FILTROS
$filtros = [
    'categoria' => 'feminino',
    'preco_min' => 50,
    'preco_max' => 200,
    'limit' => 10,
    'page' => 1
];
$produtos = $productRepo->search($filtros);
echo "Produtos encontrados: " . count($produtos) . "<br>";

// 5. CRIAR NOVO PRODUTO
$novoProduto = new Product(
    null,                    // id
    'Vestido Floral',       // nome
    'Lindo vestido floral', // descricao
    89.90,                  // preco
    'M',                    // tamanho
    'Azul',                 // cor
    'Zara',                 // marca
    'Seminovo',             // condicao
    1                       // categoria_id
);

$produtoSalvo = $productRepo->save($novoProduto);
if ($produtoSalvo) {
    echo "Novo produto criado com ID: {$produtoSalvo->id}<br>";
}

// 6. BUSCAR PRODUTO POR ID
$produto = $productRepo->findById(1);
if ($produto) {
    echo "Produto: {$produto->nome} - R$ {$produto->preco}<br>";
}

// 7. CONTAR PRODUTOS POR CATEGORIA
$total = $categoryRepo->getProductCount('feminino');
echo "Total de produtos femininos: {$total}<br>";

echo "<br><strong>✅ Repositórios funcionando corretamente!</strong>";
?>