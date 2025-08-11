<?php
// Script simplificado para criar banco de dados DressCode
echo "=== DressCode - Criação do Banco (Versão Simplificada) ===\n\n";

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'dresscode';

try {
    echo "1. Conectando ao MySQL...\n";
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Conectado!\n\n";

    echo "2. Criando banco 'dresscode'...\n";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $database");
    $pdo->exec("USE $database");
    echo "✅ Banco criado!\n\n";

    echo "3. Criando tabela 'usuarios'...\n";
    $sql = "
    CREATE TABLE IF NOT EXISTS usuarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(100) UNIQUE NOT NULL,
        senha VARCHAR(255) NOT NULL,
        nome VARCHAR(100),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "✅ Tabela criada!\n\n";

    echo "4. Inserindo usuário de teste...\n";
    $sql = "INSERT IGNORE INTO usuarios (email, senha, nome) VALUES 
            ('teste@dresscode.com', ?, 'Usuário Teste')";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([password_hash('123456', PASSWORD_DEFAULT)]);
    echo "✅ Usuário criado!\n\n";

    echo "🎉 SUCESSO! Banco configurado!\n\n";
    echo "=== LOGIN ===\n";
    echo "Email: teste@dresscode.com\n";
    echo "Senha: 123456\n\n";

} catch (PDOException $e) {
    echo "❌ ERRO: " . $e->getMessage() . "\n";
}
?>