<?php
echo "🚀 DressCode - Configuração Automática\n";
echo "=====================================\n\n";

// Detectar sistema operacional
$isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
echo "Sistema: " . ($isWindows ? "Windows" : "Linux/Mac") . "\n";

// Verificar PHP
echo "PHP: " . PHP_VERSION . "\n";

// Verificar extensões
$extensions = ['pdo', 'pdo_mysql', 'mysqli'];
foreach ($extensions as $ext) {
    echo "Extensão $ext: " . (extension_loaded($ext) ? "✅ OK" : "❌ FALTANDO") . "\n";
}

echo "\n📊 Testando conexões MySQL...\n";

// Senhas comuns para testar
$passwords = ['', 'root', '1234', 'password', 'mysql'];
$host = 'localhost';
$username = 'root';
$working_config = null;

foreach ($passwords as $password) {
    try {
        echo "Testando senha: " . ($password === '' ? '(vazia)' : $password) . " ... ";
        $pdo = new PDO("mysql:host=$host", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        echo "✅ FUNCIONOU!\n";
        $working_config = ['password' => $password];
        break;
        
    } catch (PDOException $e) {
        echo "❌ Falhou\n";
    }
}

if (!$working_config) {
    echo "\n❌ Nenhuma senha funcionou. Verifique se o MySQL está rodando.\n";
    echo "💡 Dicas:\n";
    echo "   - Inicie o Laragon/XAMPP\n";
    echo "   - Verifique se o MySQL está ativo\n";
    echo "   - Tente redefinir a senha do MySQL\n";
    exit(1);
}

echo "\n🗄️ Configurando banco de dados...\n";

try {
    $pdo = new PDO("mysql:host=$host", $username, $working_config['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Criar banco
    echo "Criando banco 'dresscode'... ";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS dresscode CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✅\n";
    
    $pdo->exec("USE dresscode");
    
    // Ler e executar SQL
    $sqlFile = __DIR__ . '/import-database.sql';
    if (file_exists($sqlFile)) {
        echo "Executando script SQL... ";
        $sql = file_get_contents($sqlFile);
        $pdo->exec($sql);
        echo "✅\n";
    }
    
    // Verificar dados
    $stmt = $pdo->query("SELECT COUNT(*) FROM produtos");
    $produtos = $stmt->fetchColumn();
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM categorias");
    $categorias = $stmt->fetchColumn();
    
    echo "Produtos: $produtos ✅\n";
    echo "Categorias: $categorias ✅\n";
    
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n⚙️ Atualizando configuração...\n";

// Atualizar arquivo de configuração
$configFile = __DIR__ . '/app/config/database.php';
$configContent = file_get_contents($configFile);

// Substituir senha
$newPassword = $working_config['password'];
$configContent = preg_replace(
    "/private \\\$password = '[^']*';/",
    "private \$password = '$newPassword';",
    $configContent
);

file_put_contents($configFile, $configContent);
echo "Arquivo de configuração atualizado ✅\n";

echo "\n🎉 CONFIGURAÇÃO CONCLUÍDA!\n";
echo "========================\n\n";

echo "📋 Resumo:\n";
echo "   - Banco: dresscode ✅\n";
echo "   - Produtos: $produtos ✅\n";
echo "   - Categorias: $categorias ✅\n";
echo "   - Senha MySQL: " . ($newPassword === '' ? '(vazia)' : $newPassword) . " ✅\n\n";

echo "🚀 Para iniciar o servidor:\n";
if ($isWindows) {
    echo "   start-server-windows.bat\n";
    echo "   OU\n";
    echo "   cd public && php -S localhost:8000\n\n";
} else {
    echo "   cd public && php -S localhost:8000\n\n";
}

echo "🌐 Acesse: http://localhost:8000\n";
echo "🔐 Login: teste@dresscode.com / 123456\n\n";

echo "✨ Pronto para usar!\n";
?>