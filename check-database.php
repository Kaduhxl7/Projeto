<?php
require_once __DIR__ . '/app/config/Database.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // Verificar estrutura da tabela produtos
    $stmt = $db->query("DESCRIBE produtos");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h2>Estrutura da tabela 'produtos':</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>{$column['Field']}</td>";
        echo "<td>{$column['Type']}</td>";
        echo "<td>{$column['Null']}</td>";
        echo "<td>{$column['Key']}</td>";
        echo "<td>{$column['Default']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>