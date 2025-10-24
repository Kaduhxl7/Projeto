<?php
require_once __DIR__ . '/app/config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    
    echo "🔔 Configuração Rápida - Sistema de Notificações\n\n";
    
    // 1. Criar tabela de notificações (estrutura mínima)
    echo "1. Criando tabela de notificações...\n";
    $sql = "CREATE TABLE IF NOT EXISTS notificacoes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        id_usuario INT NOT NULL,
        titulo VARCHAR(255) NOT NULL,
        mensagem TEXT NOT NULL,
        tipo ENUM('novo_produto', 'promocao', 'atualizacao_brecho') NOT NULL DEFAULT 'novo_produto',
        produto_id INT NULL,
        brecho_id INT NULL,
        data_envio DATETIME DEFAULT CURRENT_TIMESTAMP,
        lida TINYINT(1) DEFAULT 0
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $db->exec($sql);
    echo "✅ Tabela notificacoes criada/verificada\n";
    
    // 2. Adicionar campo receber_notificacoes se não existir
    echo "\n2. Verificando campo receber_notificacoes...\n";
    try {
        $db->exec("ALTER TABLE usuarios ADD COLUMN receber_notificacoes TINYINT(1) DEFAULT 1");
        echo "✅ Campo receber_notificacoes adicionado\n";
    } catch (Exception $e) {
        if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
            echo "✅ Campo receber_notificacoes já existe\n";
        } else {
            echo "⚠️ Erro: " . $e->getMessage() . "\n";
        }
    }
    
    // 3. Inserir notificações de exemplo
    echo "\n3. Inserindo notificações de exemplo...\n";
    $stmt = $db->query("SELECT COUNT(*) FROM notificacoes");
    $count = $stmt->fetchColumn();
    
    if ($count == 0) {
        // Buscar primeiro usuário
        $stmt = $db->query("SELECT id FROM usuarios LIMIT 1");
        $user_id = $stmt->fetchColumn();
        
        if ($user_id) {
            $notificacoes = [
                [$user_id, 'Novo produto disponível!', 'Confira este novo produto: Camiseta Vintage', 'novo_produto'],
                [$user_id, 'Promoção especial!', 'Desconto de 30% em toda a loja!', 'promocao'],
                [$user_id, 'Brechó atualizado!', 'Novos produtos adicionados ao brechó', 'atualizacao_brecho']
            ];
            
            $stmt = $db->prepare("INSERT INTO notificacoes (id_usuario, titulo, mensagem, tipo) VALUES (?, ?, ?, ?)");
            
            foreach ($notificacoes as $notif) {
                $stmt->execute($notif);
            }
            
            echo "✅ 3 notificações de exemplo inseridas\n";
        } else {
            echo "⚠️ Nenhum usuário encontrado para criar notificações\n";
        }
    } else {
        echo "✅ Notificações já existem ($count)\n";
    }
    
    // 4. Verificar estrutura
    echo "\n4. Verificando estrutura...\n";
    $stmt = $db->query("DESCRIBE notificacoes");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    $required_columns = ['id', 'id_usuario', 'titulo', 'mensagem', 'tipo', 'data_envio', 'lida'];
    $missing = array_diff($required_columns, $columns);
    
    if (empty($missing)) {
        echo "✅ Estrutura da tabela está correta\n";
    } else {
        echo "⚠️ Colunas faltando: " . implode(', ', $missing) . "\n";
    }
    
    echo "\n🎉 Configuração concluída!\n";
    echo "🌐 Acesse: http://localhost/Projeto/Projeto-master/public/notifications.php\n";
    echo "🧪 Teste: http://localhost/Projeto/Projeto-master/test-notifications-simple.php\n";
    
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
}
?>