<?php
require_once __DIR__ . '/app/config/database.php';

try {
    $database = new Database();
    $conn = $database->getConnection();
    
    // Criar tabela notificacoes
    $sql = "CREATE TABLE IF NOT EXISTS notificacoes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        id_usuario INT,
        titulo VARCHAR(255) NOT NULL,
        mensagem TEXT NOT NULL,
        tipo ENUM('novo_produto', 'promocao') NOT NULL,
        produto_id INT NULL,
        data_envio DATETIME DEFAULT CURRENT_TIMESTAMP,
        lida TINYINT(1) DEFAULT 0,
        FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
        FOREIGN KEY (produto_id) REFERENCES produtos(id)
    )";
    
    $conn->exec($sql);
    echo "✅ Tabela 'notificacoes' criada com sucesso!\n";
    
    // Adicionar campo receber_notificacoes na tabela usuarios se não existir
    try {
        $conn->exec("ALTER TABLE usuarios ADD COLUMN receber_notificacoes TINYINT(1) DEFAULT 1");
        echo "✅ Campo 'receber_notificacoes' adicionado à tabela usuarios!\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
            echo "⚠️ Campo 'receber_notificacoes' já existe na tabela usuarios\n";
        } else {
            echo "❌ Erro ao adicionar campo: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n🎉 Sistema de notificações configurado!\n";
    
} catch (PDOException $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
}
?>