<?php
// Teste rápido das funcionalidades
echo "=== Teste Rápido DressCode ===\n\n";

try {
    // Testar conexão
    require_once 'app/config/database.php';
    $database = new Database();
    $db = $database->getConnection();
    echo "✅ Banco conectado\n";

    // Testar produtos
    $stmt = $db->query("SELECT COUNT(*) as total FROM produtos");
    $total = $stmt->fetch()['total'];
    echo "✅ Produtos: $total\n";

    // Testar categorias
    $stmt = $db->query("SELECT nome, slug FROM categorias WHERE ativo = 1");
    $categorias = $stmt->fetchAll();
    echo "✅ Categorias:\n";
    foreach ($categorias as $cat) {
        echo "   - {$cat['nome']} ({$cat['slug']})\n";
    }

    echo "\n🎉 Tudo funcionando!\n";
    echo "\n=== URLs para testar ===\n";
    echo "http://localhost:8000/categoria.php?cat=feminino\n";
    echo "http://localhost:8000/categoria.php?cat=masculino\n";
    echo "http://localhost:8000/produto.php?id=1\n";
    echo "http://localhost:8000/busca.php?search=blusa\n";

} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
}
?>