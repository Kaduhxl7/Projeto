<?php
echo "=== Configuração do Banco DressCode ===\n";

// Tentar diferentes configurações de senha
$passwords = ['', 'root', '123456', 'password'];
$host = 'localhost';
$username = 'root';

$conn = null;
$working_password = null;

// Testar conexões
foreach ($passwords as $password) {
    try {
        $conn = new PDO("mysql:host=$host", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $working_password = $password;
        echo "✅ Conectado com sucesso! Senha: " . ($password === '' ? '(vazia)' : $password) . "\n";
        break;
    } catch(PDOException $e) {
        echo "❌ Falha com senha: " . ($password === '' ? '(vazia)' : $password) . "\n";
    }
}

if (!$conn) {
    die("❌ Não foi possível conectar ao MySQL. Verifique se o Laragon está rodando.\n");
}

try {
    // Criar banco de dados
    echo "\n📦 Criando banco de dados 'dresscode'...\n";
    $conn->exec("CREATE DATABASE IF NOT EXISTS dresscode CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✅ Banco 'dresscode' criado com sucesso!\n";
    
    // Usar o banco
    $conn->exec("USE dresscode");
    
    // Criar tabela de categorias
    echo "\n📋 Criando tabela de categorias...\n";
    $sql_categorias = "
    CREATE TABLE IF NOT EXISTS categorias (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(50) NOT NULL,
        slug VARCHAR(50) NOT NULL,
        descricao TEXT,
        ativo BOOLEAN DEFAULT TRUE
    )";
    $conn->exec($sql_categorias);
    
    // Inserir categorias
    $categorias = [
        ['Feminino', 'feminino', 'Roupas femininas'],
        ['Masculino', 'masculino', 'Roupas masculinas'],
        ['Infantil', 'infantil', 'Roupas infantis'],
        ['Acessórios', 'acessorios', 'Bolsas, sapatos, joias, etc.']
    ];
    
    $stmt = $conn->prepare("INSERT IGNORE INTO categorias (nome, slug, descricao) VALUES (?, ?, ?)");
    foreach ($categorias as $cat) {
        $stmt->execute($cat);
    }
    echo "✅ Categorias inseridas!\n";
    
    // Criar tabela de produtos
    echo "\n🛍️ Criando tabela de produtos...\n";
    $sql_produtos = "
    CREATE TABLE IF NOT EXISTS produtos (
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
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (categoria_id) REFERENCES categorias(id)
    )";
    $conn->exec($sql_produtos);
    
    // Inserir produtos de exemplo
    $produtos = [
        ['Blusa Floral Vintage', 'Linda blusa com estampa floral, perfeita para o verão', 45.90, 'M', 'Floral', 'Vintage', 'Seminovo', 1, 'blusa-floral.jpg'],
        ['Calça Jeans Skinny', 'Calça jeans azul escuro, modelo skinny', 89.90, 'G', 'Azul', 'Levi\'s', 'Usado', 1, 'calca-jeans.jpg'],
        ['Vestido Midi Bege', 'Vestido midi em tom bege, muito elegante', 120.00, 'P', 'Bege', 'Zara', 'Seminovo', 1, 'vestido-midi.jpg'],
        ['Camisa Social Branca', 'Camisa social masculina branca, tamanho M', 35.00, 'M', 'Branco', 'Renner', 'Seminovo', 2, 'camisa-social.jpg'],
        ['Tênis Esportivo', 'Tênis esportivo preto, muito confortável', 75.50, 'G', 'Preto', 'Nike', 'Usado', 4, 'tenis-esportivo.jpg'],
        ['Saia Plissada Rosa', 'Saia plissada em tom rosa claro', 55.00, 'M', 'Rosa', 'C&A', 'Novo', 1, 'saia-plissada.jpg']
    ];
    
    $stmt = $conn->prepare("INSERT IGNORE INTO produtos (nome, descricao, preco, tamanho, cor, marca, condicao, categoria_id, imagem) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    foreach ($produtos as $produto) {
        $stmt->execute($produto);
    }
    echo "✅ Produtos de exemplo inseridos!\n";
    
    // Criar tabela de usuários
    echo "\n👤 Criando tabela de usuários...\n";
    $sql_usuarios = "
    CREATE TABLE IF NOT EXISTS usuarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) UNIQUE NOT NULL,
        senha VARCHAR(255) NOT NULL,
        nome VARCHAR(100),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $conn->exec($sql_usuarios);
    
    // Inserir usuário de teste
    $stmt = $conn->prepare("INSERT IGNORE INTO usuarios (email, senha, nome) VALUES (?, ?, ?)");
    $stmt->execute(['teste@dresscode.com', password_hash('123456', PASSWORD_DEFAULT), 'Usuário Teste']);
    echo "✅ Usuário de teste criado!\n";
    
    echo "\n🎉 CONFIGURAÇÃO CONCLUÍDA COM SUCESSO!\n";
    echo "📊 Resumo:\n";
    echo "   - Banco: dresscode\n";
    echo "   - Categorias: 4 inseridas\n";
    echo "   - Produtos: 6 exemplos inseridos\n";
    echo "   - Usuário teste: teste@dresscode.com (senha: 123456)\n";
    echo "   - Senha MySQL: " . ($working_password === '' ? '(vazia)' : $working_password) . "\n";
    
    // Atualizar arquivo de configuração se necessário
    if ($working_password !== '') {
        echo "\n⚠️  ATENÇÃO: Atualize o arquivo app/config/database.php\n";
        echo "   Altere a linha: private \$password = '';\n";
        echo "   Para: private \$password = '$working_password';\n";
    }
    
} catch(PDOException $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
}
?>