<?php
require_once __DIR__ . '/app/config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    
    echo "🔧 Corrigindo dados do dashboard...\n\n";
    
    // Verificar qual usuário você está usando
    echo "Qual é o ID do usuário que você está logado?\n";
    echo "Usuários vendedores disponíveis:\n";
    
    $stmt = $db->query("SELECT id, nome, email FROM usuarios WHERE quero_vender = 1");
    $vendedores = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($vendedores as $vendedor) {
        echo "- ID: {$vendedor['id']} | Nome: {$vendedor['nome']} | Email: {$vendedor['email']}\n";
    }
    
    // Inserir dados para o vendedor ID 4 (que não tinha dados)
    $vendedor_id = 4;
    echo "\n📦 Inserindo dados para vendedor ID $vendedor_id...\n";
    
    // Buscar produtos existentes
    $stmt = $db->query("SELECT id FROM produtos LIMIT 6");
    $produtos = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($produtos)) {
        echo "❌ Nenhum produto encontrado\n";
        exit;
    }
    
    // Inserir vendas com produto_id
    $vendas_exemplo = [
        [$produtos[0] ?? 1, $vendedor_id, 2, 45.00, 4.50, 40.50, 'Confirmada', '2024-01-15 10:30:00'],
        [$produtos[1] ?? 2, $vendedor_id, 3, 80.00, 8.00, 72.00, 'Confirmada', '2024-01-20 14:15:00'],
        [$produtos[2] ?? 3, $vendedor_id, 2, 25.00, 2.50, 22.50, 'Confirmada', '2024-02-05 09:45:00'],
        [$produtos[3] ?? 4, $vendedor_id, 3, 120.00, 12.00, 108.00, 'Confirmada', '2024-02-10 16:20:00'],
        [$produtos[4] ?? 5, $vendedor_id, 2, 35.00, 3.50, 31.50, 'Pendente', '2024-02-15 11:10:00'],
        [$produtos[5] ?? 6, $vendedor_id, 3, 60.00, 6.00, 54.00, 'Confirmada', '2024-02-20 14:30:00']
    ];
    
    $stmt = $db->prepare("INSERT INTO vendas (produto_id, vendedor_id, comprador_id, valor_venda, comissao, lucro_vendedor, status, data_venda) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    
    $inseridas = 0;
    foreach ($vendas_exemplo as $venda) {
        try {
            if ($stmt->execute($venda)) {
                $inseridas++;
            }
        } catch (Exception $e) {
            // Ignorar duplicatas
        }
    }
    
    echo "✅ $inseridas vendas inseridas para vendedor ID $vendedor_id\n";
    
    // Atualizar produtos para ter vendedor
    $db->prepare("UPDATE produtos SET vendedor_id = ? WHERE id IN (" . implode(',', $produtos) . ")")->execute([$vendedor_id]);
    echo "✅ Produtos associados ao vendedor\n";
    
    // Mostrar resumo final
    echo "\n📊 Resumo final:\n";
    foreach ($vendedores as $vendedor) {
        $stmt = $db->prepare("SELECT COUNT(*) as vendas, SUM(valor_venda) as total FROM vendas WHERE vendedor_id = ? AND status = 'Confirmada'");
        $stmt->execute([$vendedor['id']]);
        $resumo = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo "- {$vendedor['nome']}: {$resumo['vendas']} vendas, R$ " . number_format($resumo['total'] ?? 0, 2, ',', '.') . "\n";
    }
    
    echo "\n🎉 Agora teste o dashboard novamente!\n";
    
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
}
?>