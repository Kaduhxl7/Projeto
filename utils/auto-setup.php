<?php
echo "🚀 Configuração automática do DressCode...\n\n";

require_once '../app/config/database.php';

try {
    $database = new Database();
    $pdo = $database->getConnection();
    
    if ($pdo) {
        echo "✅ Conexão com banco estabelecida!\n";
        
        // Verificar se tabelas existem
        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (empty($tables)) {
            echo "📊 Criando tabelas...\n";
            
            // Ler e executar script SQL
            $sql = file_get_contents('../database/database.sql');
            
            // Remover comentários e dividir em comandos
            $sql = preg_replace('/--.*$/m', '', $sql);
            $commands = array_filter(array_map('trim', explode(';', $sql)));
            
            foreach ($commands as $command) {
                if (!empty($command) && !preg_match('/^(CREATE DATABASE|USE)/i', $command)) {
                    try {
                        $pdo->exec($command);
                    } catch (PDOException $e) {
                        // Ignora erros de tabelas que já existem
                        if (strpos($e->getMessage(), 'already exists') === false) {
                            echo "⚠️ Aviso: " . $e->getMessage() . "\n";
                        }
                    }
                }
            }
            
            echo "✅ Tabelas criadas com sucesso!\n";
        } else {
            echo "✅ Tabelas já existem: " . implode(', ', $tables) . "\n";
        }
        
        echo "\n🎉 Configuração concluída!\n";
        echo "🌐 Acesse: http://localhost:8000\n";
        
    } else {
        echo "❌ Falha na conexão com banco\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
}
?>