<?php
require_once __DIR__ . '/app/config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    
    echo "Adicionando coluna quero_vender...\n";
    
    // Verificar se coluna já existe
    $stmt = $db->query("SHOW COLUMNS FROM usuarios LIKE 'quero_vender'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Coluna quero_vender já existe\n";
    } else {
        // Adicionar coluna
        $db->exec("ALTER TABLE usuarios ADD COLUMN quero_vender TINYINT(1) DEFAULT 0");
        echo "✅ Coluna quero_vender adicionada\n";
    }
    
    // Definir usuário 1 como vendedor
    $db->exec("UPDATE usuarios SET quero_vender = 1 WHERE id = 1");
    echo "✅ Usuário ID 1 definido como vendedor\n";
    
    // Verificar resultado
    $stmt = $db->query("SELECT id, nome, quero_vender FROM usuarios LIMIT 3");
    echo "\n📊 Usuários:\n";
    while($row = $stmt->fetch()) {
        $vendedor = $row['quero_vender'] ? 'SIM' : 'NÃO';
        echo "ID: {$row['id']} | Nome: {$row['nome']} | Vendedor: $vendedor\n";
    }
    
    echo "\n🎉 Pronto! Agora teste: http://localhost/Projeto/Projeto-master/public/test-seller-payment.php\n";
    
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
}
?>