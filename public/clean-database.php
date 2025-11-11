<?php
// Script para limpar produtos de teste do banco de dados
require_once __DIR__ . '/../app/config/Database.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // Deletar os produtos de teste
    $produtos_teste = [
        'Vestido Elegante', 'Camisa Social', 'Calça Jeans', 'Blazer Executivo', 
        'Camiseta Casual', 'Saia Midi', 'Shorts Jeans', 'Blusa Estampada', 
        'Polo Masculina', 'Vestido Casual'
    ];
    
    $placeholders = str_repeat('?,', count($produtos_teste) - 1) . '?';
    $sql = "DELETE FROM produtos WHERE nome IN ($placeholders)";
    
    $stmt = $db->prepare($sql);
    $stmt->execute($produtos_teste);
    
    $deletados = $stmt->rowCount();
    
    echo "<h2>✅ Limpeza concluída!</h2>";
    echo "<p>$deletados produtos de teste foram removidos do banco de dados.</p>";
    echo "<p><a href='index.php'>Voltar para a loja</a></p>";
    
} catch (PDOException $e) {
    echo "<h2>❌ Erro ao limpar produtos:</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>