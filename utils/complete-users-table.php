<?php
echo "🔧 Completando tabela de usuários...\n\n";

require_once '../app/config/database.php';

try {
    $database = new Database();
    $pdo = $database->getConnection();
    
    if ($pdo) {
        echo "✅ Conectado ao banco!\n";
        
        // Adicionar colunas uma por vez na ordem correta
        $colunas = [
            "ALTER TABLE usuarios ADD COLUMN telefone VARCHAR(20) AFTER celular",
            "ALTER TABLE usuarios ADD COLUMN data_nascimento DATE AFTER telefone", 
            "ALTER TABLE usuarios ADD COLUMN genero ENUM('M', 'F', 'Outro') AFTER data_nascimento",
            "ALTER TABLE usuarios ADD COLUMN foto_perfil VARCHAR(255) AFTER genero",
            "ALTER TABLE usuarios ADD COLUMN ativo BOOLEAN DEFAULT TRUE AFTER foto_perfil",
            "ALTER TABLE usuarios ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER created_at",
            "ALTER TABLE usuarios ADD COLUMN quero_vender BOOLEAN DEFAULT FALSE AFTER ativo",
            "ALTER TABLE usuarios ADD COLUMN quero_comprar BOOLEAN DEFAULT TRUE AFTER quero_vender",
            "ALTER TABLE usuarios ADD COLUMN nome_brecho VARCHAR(100) AFTER quero_comprar",
            "ALTER TABLE usuarios ADD COLUMN localizacao_brecho VARCHAR(255) AFTER nome_brecho",
            "ALTER TABLE usuarios ADD COLUMN cep VARCHAR(10) AFTER localizacao_brecho",
            "ALTER TABLE usuarios ADD COLUMN rua VARCHAR(255) AFTER cep",
            "ALTER TABLE usuarios ADD COLUMN numero VARCHAR(10) AFTER rua",
            "ALTER TABLE usuarios ADD COLUMN complemento VARCHAR(100) AFTER numero",
            "ALTER TABLE usuarios ADD COLUMN bairro VARCHAR(100) AFTER complemento",
            "ALTER TABLE usuarios ADD COLUMN cidade VARCHAR(100) AFTER bairro",
            "ALTER TABLE usuarios ADD COLUMN estado VARCHAR(2) AFTER cidade"
        ];
        
        foreach ($colunas as $sql) {
            try {
                $pdo->exec($sql);
                preg_match('/ADD COLUMN (\w+)/', $sql, $matches);
                echo "✅ Coluna adicionada: " . ($matches[1] ?? 'desconhecida') . "\n";
            } catch (PDOException $e) {
                if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
                    preg_match('/ADD COLUMN (\w+)/', $sql, $matches);
                    echo "ℹ️ Coluna já existe: " . ($matches[1] ?? 'desconhecida') . "\n";
                } else {
                    echo "⚠️ Erro: " . $e->getMessage() . "\n";
                }
            }
        }
        
        echo "\n🎉 Tabela completa!\n";
        
    } else {
        echo "❌ Falha na conexão\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
}
?>