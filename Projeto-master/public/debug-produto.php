<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Debug Produto</h2>";

try {
    require_once __DIR__ . '/../app/config/bootstrap.php';
    echo "✅ Bootstrap carregado<br>";
    
    require_once '../app/controllers/ProductController.php';
    echo "✅ ProductController carregado<br>";
    
    $id = $_GET['id'] ?? '1';
    echo "ID do produto: $id<br>";
    
    if (empty($id) || !is_numeric($id)) {
        echo "❌ ID inválido<br>";
        exit;
    }
    
    $controller = new ProductController();
    echo "✅ Controller criado<br>";
    
    echo "Chamando show($id)...<br>";
    ob_start();
    $controller->show($id);
    $output = ob_get_contents();
    ob_end_clean();
    
    if (empty($output)) {
        echo "❌ Output vazio do controller->show()<br>";
    } else {
        echo "✅ Output gerado (". strlen($output) ." chars)<br>";
        echo $output;
    }
    
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "<br>";
    echo "Arquivo: " . $e->getFile() . "<br>";
    echo "Linha: " . $e->getLine() . "<br>";
    echo "Stack trace:<br><pre>" . $e->getTraceAsString() . "</pre>";
}
?>