<?php
require_once __DIR__ . '/app/config/database.php';

try {
    $database = new Database();
    $conn = $database->getConnection();
    
    $stmt = $conn->query("SELECT id, email, nome, created_at FROM usuarios ORDER BY created_at DESC");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "📋 Usuários cadastrados no sistema:\n\n";
    echo str_pad("ID", 5) . str_pad("EMAIL", 30) . str_pad("NOME", 25) . "DATA CADASTRO\n";
    echo str_repeat("-", 80) . "\n";
    
    foreach ($usuarios as $user) {
        echo str_pad($user['id'], 5) . 
             str_pad($user['email'], 30) . 
             str_pad($user['nome'] ?? 'N/A', 25) . 
             $user['created_at'] . "\n";
    }
    
    echo "\n📊 Total: " . count($usuarios) . " usuários\n";
    
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
}
?>