<?php
require_once __DIR__ . '/app/config/database.php';

try {
    $database = new Database();
    $conn = $database->getConnection();
    
    // Criar tabela brechos
    $sql = "CREATE TABLE IF NOT EXISTS brechos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(100) NOT NULL,
        descricao TEXT,
        endereco TEXT,
        cidade VARCHAR(100),
        estado VARCHAR(50),
        cep VARCHAR(10),
        latitude DECIMAL(10,8),
        longitude DECIMAL(11,8),
        telefone VARCHAR(20),
        email VARCHAR(255),
        usuario_id INT,
        ativo BOOLEAN DEFAULT TRUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
    )";
    
    $conn->exec($sql);
    echo "✅ Tabela 'brechos' criada!<br>";
    
    // Criar índices
    $indexes = [
        "CREATE INDEX idx_brechos_cidade ON brechos(cidade)",
        "CREATE INDEX idx_brechos_estado ON brechos(estado)",
        "CREATE INDEX idx_brechos_latitude ON brechos(latitude)",
        "CREATE INDEX idx_brechos_longitude ON brechos(longitude)"
    ];
    
    foreach ($indexes as $index) {
        try {
            $conn->exec($index);
            echo "✅ Índice criado<br>";
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'Duplicate key name') === false) {
                echo "❌ Erro no índice: " . $e->getMessage() . "<br>";
            }
        }
    }
    
    // Inserir dados de exemplo
    $brechos = [
        ['Brechó Vintage SP', 'Peças vintage e retrô', 'Rua Augusta, 1234', 'São Paulo', 'SP', '01305-100', -23.5505, -46.6333, '(11) 99999-0001', 'contato@vintagesp.com'],
        ['Moda Consciente RJ', 'Roupas sustentáveis', 'Rua das Flores, 567', 'Rio de Janeiro', 'RJ', '22071-900', -22.9068, -43.1729, '(21) 99999-0002', 'info@modaconsciente.com'],
        ['Brechó do Centro', 'Variedades no centro', 'Av. Paulista, 890', 'São Paulo', 'SP', '01310-100', -23.5618, -46.6565, '(11) 99999-0003', 'centro@brecho.com'],
        ['Estilo Único BH', 'Peças exclusivas', 'Rua da Bahia, 321', 'Belo Horizonte', 'MG', '30112-000', -19.9167, -43.9345, '(31) 99999-0004', 'contato@estilounico.com'],
        ['Vintage Curitiba', 'Moda retrô', 'Rua XV de Novembro, 654', 'Curitiba', 'PR', '80020-310', -25.4284, -49.2733, '(41) 99999-0005', 'vintage@cwb.com']
    ];
    
    $stmt = $conn->prepare("INSERT INTO brechos (nome, descricao, endereco, cidade, estado, cep, latitude, longitude, telefone, email, usuario_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)");
    
    foreach ($brechos as $brecho) {
        $stmt->execute($brecho);
    }
    
    echo "✅ " . count($brechos) . " brechós inseridos!<br>";
    echo "<br>🎉 Sistema de busca configurado!<br>";
    echo "<a href='public/buscar.php'>Testar busca</a>";
    
} catch (PDOException $e) {
    echo "❌ Erro: " . $e->getMessage();
}
?>