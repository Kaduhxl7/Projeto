<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>🔍 Debug Sistema de Busca</h2>";

try {
    // Testar bootstrap
    require_once __DIR__ . '/app/config/bootstrap.php';
    echo "✅ Bootstrap carregado<br>";
    
    // Testar banco
    require_once __DIR__ . '/app/config/database.php';
    $database = new Database();
    $conn = $database->getConnection();
    echo "✅ Conexão com banco OK<br>";
    
    // Verificar tabela brechos
    $stmt = $conn->query("SHOW TABLES LIKE 'brechos'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Tabela brechos existe<br>";
        
        // Verificar colunas
        $stmt = $conn->query("DESCRIBE brechos");
        $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo "📋 Colunas: " . implode(', ', $columns) . "<br>";
        
        // Contar registros
        $stmt = $conn->query("SELECT COUNT(*) FROM brechos");
        $count = $stmt->fetchColumn();
        echo "📊 Total de brechós: $count<br>";
        
    } else {
        echo "❌ Tabela brechos não existe<br>";
    }
    
    // Testar modelo
    require_once __DIR__ . '/app/models/Brecho.php';
    $brechoModel = new Brecho();
    echo "✅ Modelo Brecho carregado<br>";
    
    // Testar busca simples
    $resultados = $brechoModel->searchByLocation('São Paulo');
    echo "🔍 Busca 'São Paulo': " . count($resultados) . " resultados<br>";
    
    // Testar controller
    require_once __DIR__ . '/app/controllers/BuscaController.php';
    echo "✅ Controller carregado<br>";
    
    echo "<br>🎉 Todos os componentes funcionando!";
    echo "<br><a href='public/buscar.php'>Testar busca completa</a>";
    
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "<br>";
    echo "📍 Arquivo: " . $e->getFile() . " linha " . $e->getLine() . "<br>";
}
?>