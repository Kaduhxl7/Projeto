<?php
echo "=== Setup Rápido DressCode ===\n";

// Senhas mais comuns do Laragon
$passwords = ['', 'root', '123456', 'password', 'laragon', 'mysql'];
$host = 'localhost';
$username = 'root';

foreach ($passwords as $password) {
    try {
        echo "Testando senha: " . ($password === '' ? '(vazia)' : $password) . "\n";
        $conn = new PDO("mysql:host=$host", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        echo "✅ CONECTADO! Criando banco...\n";
        
        // Criar banco
        $conn->exec("CREATE DATABASE IF NOT EXISTS dresscode CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $conn->exec("USE dresscode");
        
        // Criar categorias
        $conn->exec("CREATE TABLE IF NOT EXISTS categorias (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(50) NOT NULL,
            slug VARCHAR(50) NOT NULL,
            descricao TEXT,
            ativo BOOLEAN DEFAULT TRUE
        )");
        
        $conn->exec("INSERT IGNORE INTO categorias (nome, slug, descricao) VALUES 
            ('Feminino', 'feminino', 'Roupas femininas'),
            ('Masculino', 'masculino', 'Roupas masculinas'),
            ('Infantil', 'infantil', 'Roupas infantis'),
            ('Acessórios', 'acessorios', 'Bolsas, sapatos, joias, etc.')");
        
        // Criar produtos
        $conn->exec("CREATE TABLE IF NOT EXISTS produtos (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(100) NOT NULL,
            descricao TEXT,
            preco DECIMAL(10,2) NOT NULL,
            tamanho ENUM('PP', 'P', 'M', 'G', 'GG', 'XG', 'Único'),
            cor VARCHAR(50),
            marca VARCHAR(50),
            condicao ENUM('Novo', 'Seminovo', 'Usado') DEFAULT 'Seminovo',
            categoria_id INT,
            imagem VARCHAR(255),
            status ENUM('Ativo', 'Vendido', 'Inativo') DEFAULT 'Ativo',
            visualizacoes INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (categoria_id) REFERENCES categorias(id)
        )");
        
        $conn->exec("INSERT IGNORE INTO produtos (nome, descricao, preco, tamanho, cor, marca, condicao, categoria_id, imagem) VALUES 
            ('Blusa Floral Vintage', 'Linda blusa com estampa floral', 45.90, 'M', 'Floral', 'Vintage', 'Seminovo', 1, 'blusa-floral.jpg'),
            ('Calça Jeans Skinny', 'Calça jeans azul escuro', 89.90, 'G', 'Azul', 'Levis', 'Usado', 1, 'calca-jeans.jpg'),
            ('Vestido Midi Bege', 'Vestido midi elegante', 120.00, 'P', 'Bege', 'Zara', 'Seminovo', 1, 'vestido-midi.jpg'),
            ('Camisa Social Branca', 'Camisa social masculina', 35.00, 'M', 'Branco', 'Renner', 'Seminovo', 2, 'camisa-social.jpg'),
            ('Tênis Esportivo', 'Tênis esportivo confortável', 75.50, 'G', 'Preto', 'Nike', 'Usado', 4, 'tenis-esportivo.jpg'),
            ('Saia Plissada Rosa', 'Saia plissada rosa claro', 55.00, 'M', 'Rosa', 'C&A', 'Novo', 1, 'saia-plissada.jpg')");
        
        echo "✅ BANCO CONFIGURADO COM SUCESSO!\n";
        echo "🌐 Acesse: http://localhost:8000\n";
        
        // Atualizar senha no arquivo de config se necessário
        if ($password !== '') {
            echo "⚠️  Atualize app/config/database.php:\n";
            echo "   private \$password = '$password';\n";
        }
        
        exit(0);
        
    } catch (PDOException $e) {
        echo "❌ Falhou\n";
    }
}

echo "❌ Nenhuma senha funcionou. Abra o Laragon e verifique as configurações do MySQL.\n";
?>