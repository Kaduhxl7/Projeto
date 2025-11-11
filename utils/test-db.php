<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=dresscode;charset=utf8mb4", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexão OK!<br>";
    
    $stmt = $pdo->query("SHOW TABLES");
    echo "Tabelas encontradas:<br>";
    while ($row = $stmt->fetch()) {
        echo "- " . $row[0] . "<br>";
    }
} catch(PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>