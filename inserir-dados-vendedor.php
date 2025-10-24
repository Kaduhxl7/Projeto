<?php
require_once __DIR__ . '/app/config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    
    echo "💰 Inserindo dados de vendas para todos os vendedores...\n\n";
    
    // Buscar todos os vendedores
    $stmt = $db->query("SELECT id, nome, email FROM usuarios WHERE quero_vender = 1");
    $vendedores = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($vendedores as $vendedor) {
        $vendedor_id = $vendedor['id'];
        echo "👤 Processando vendedor: {$vendedor['nome']} (ID: $vendedor_id)\n";
        
        // Verificar se já tem vendas
        $stmt = $db->prepare("SELECT COUNT(*) FROM vendas WHERE vendedor_id = ?");
        $stmt->execute([$vendedor_id]);
        $count = $stmt->fetchColumn();
        
        if ($count > 0) {
            echo "✅ Já possui $count vendas\n\n";
            continue;
        }
        
        // Inserir vendas de exemplo
        echo "📦 Inserindo vendas de exemplo...\n";
        
        $vendas_exemplo = [
            [$vendedor_id, 2, 45.00, 4.50, 40.50, 'Confirmada', '2024-01-15 10:30:00'],
            [$vendedor_id, 3, 80.00, 8.00, 72.00, 'Confirmada', '2024-01-20 14:15:00'],
            [$vendedor_id, 2, 25.00, 2.50, 22.50, 'Confirmada', '2024-02-05 09:45:00'],
            [$vendedor_id, 3, 120.00, 12.00, 108.00, 'Confirmada', '2024-02-10 16:20:00'],
            [$vendedor_id, 2, 35.00, 3.50, 31.50, 'Pendente', '2024-02-15 11:10:00'],
            [$vendedor_id, 3, 60.00, 6.00, 54.00, 'Confirmada', '2024-02-20 14:30:00']
        ];
        
        $stmt = $db->prepare("INSERT INTO vendas (vendedor_id, comprador_id, valor_venda, comissao, lucro_vendedor, status, data_venda) VALUES (?, ?, ?, ?, ?, ?, ?)");
        
        $inseridas = 0;
        foreach ($vendas_exemplo as $venda) {
            if ($stmt->execute($venda)) {
                $inseridas++;
            }
        }
        
        echo "✅ $inseridas vendas inseridas\n";
        
        // Atualizar produtos para ter este vendedor
        $db->prepare("UPDATE produtos SET vendedor_id = ? WHERE vendedor_id IS NULL LIMIT 5")->execute([$vendedor_id]);
        echo "✅ Produtos associados ao vendedor\n\n";
    }
    
    echo "🎉 Dados inseridos com sucesso!\n";
    echo "Agora acesse o dashboard e veja os dados!\n";
    
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
}
?>