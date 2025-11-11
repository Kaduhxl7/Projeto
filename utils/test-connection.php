<?php
echo "=== Teste de Conexão MySQL ===\n";

// Verificar se as extensões estão carregadas
echo "PDO disponível: " . (extension_loaded('pdo') ? 'SIM' : 'NÃO') . "\n";
echo "PDO MySQL disponível: " . (extension_loaded('pdo_mysql') ? 'SIM' : 'NÃO') . "\n\n";

// Tentar conectar usando socket (método alternativo)
$configs = [
    ['host' => 'localhost', 'port' => '3306', 'user' => 'root', 'pass' => ''],
    ['host' => '127.0.0.1', 'port' => '3306', 'user' => 'root', 'pass' => ''],
    ['host' => 'localhost', 'port' => '3306', 'user' => 'root', 'pass' => 'root'],
    ['host' => '127.0.0.1', 'port' => '3306', 'user' => 'root', 'pass' => 'root'],
];

foreach ($configs as $i => $config) {
    echo "Teste " . ($i + 1) . ": {$config['host']}:{$config['port']} - user: {$config['user']} - pass: " . ($config['pass'] === '' ? '(vazia)' : $config['pass']) . "\n";
    
    try {
        $dsn = "mysql:host={$config['host']};port={$config['port']}";
        $pdo = new PDO($dsn, $config['user'], $config['pass']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        echo "✅ CONEXÃO ESTABELECIDA!\n";
        echo "Versão MySQL: ";
        $stmt = $pdo->query('SELECT VERSION()');
        echo $stmt->fetchColumn() . "\n";
        
        // Tentar criar o banco
        echo "Criando banco 'dresscode'...\n";
        $pdo->exec("CREATE DATABASE IF NOT EXISTS dresscode CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        echo "✅ Banco criado com sucesso!\n";
        
        // Atualizar configuração se necessário
        if ($config['pass'] !== '') {
            echo "\n⚠️  ATENÇÃO: Atualize app/config/database.php\n";
            echo "   private \$password = '{$config['pass']}';\n";
        }
        
        exit(0);
        
    } catch (PDOException $e) {
        echo "❌ Erro: " . $e->getMessage() . "\n\n";
    }
}

echo "❌ Nenhuma configuração funcionou. Verifique se o MySQL está rodando no Laragon.\n";
?>