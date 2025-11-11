<?php
// Script para expandir banco de dados com produtos e funcionalidades completas
echo "=== Expandindo Banco DressCode ===\n\n";

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'dresscode';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Conectado ao banco!\n\n";

    // Criar tabela de categorias
    echo "1. Criando tabela categorias...\n";
    $sql = "
    CREATE TABLE IF NOT EXISTS categorias (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(50) NOT NULL,
        slug VARCHAR(50) NOT NULL UNIQUE,
        descricao TEXT,
        ativo BOOLEAN DEFAULT TRUE
    )";
    $pdo->exec($sql);

    // Inserir categorias
    $sql = "INSERT IGNORE INTO categorias (nome, slug, descricao) VALUES 
            ('Feminino', 'feminino', 'Roupas e acessórios femininos'),
            ('Masculino', 'masculino', 'Roupas e acessórios masculinos'),
            ('Infantil', 'infantil', 'Roupas infantis'),
            ('Acessórios', 'acessorios', 'Bolsas, sapatos, joias'),
            ('Outros', 'outros', 'Outros itens de moda')";
    $pdo->exec($sql);
    echo "✅ Categorias criadas!\n";

    // Criar tabela de produtos
    echo "2. Criando tabela produtos...\n";
    $sql = "
    CREATE TABLE IF NOT EXISTS produtos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(100) NOT NULL,
        descricao TEXT,
        preco DECIMAL(10,2) NOT NULL,
        tamanho ENUM('PP', 'P', 'M', 'G', 'GG', 'XG', 'Único') DEFAULT 'M',
        cor VARCHAR(50),
        marca VARCHAR(50),
        condicao ENUM('Novo', 'Seminovo', 'Usado') DEFAULT 'Seminovo',
        categoria_id INT,
        imagem VARCHAR(255),
        status ENUM('Ativo', 'Vendido', 'Inativo') DEFAULT 'Ativo',
        visualizacoes INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (categoria_id) REFERENCES categorias(id)
    )";
    $pdo->exec($sql);
    echo "✅ Tabela produtos criada!\n";

    // Inserir produtos de exemplo
    echo "3. Inserindo produtos de exemplo...\n";
    $produtos = [
        // Feminino
        ['Blusa Vintage Floral', 'Blusa vintage com estampa floral delicada', 45.00, 'M', 'Floral', 'Vintage', 'Seminovo', 1, 'ergnu9itgnruedgregmei.png'],
        ['Vestido Boho Chic', 'Vestido estilo boho com detalhes únicos', 65.00, 'P', 'Bege', 'Artesanal', 'Novo', 1, 'vretmunui9sf.png'],
        ['Calça Jeans Skinny', 'Calça jeans skinny de alta qualidade', 55.00, 'M', 'Azul', 'Levi\'s', 'Seminovo', 1, 'ff1b1c3ed2706fff44bbdce0441f394b3d564df3.png'],
        ['Saia Plissada Midi', 'Saia plissada elegante midi', 40.00, 'G', 'Preto', 'Zara', 'Seminovo', 1, 'vvvv.png'],
        ['Blazer Social', 'Blazer social feminino clássico', 80.00, 'M', 'Cinza', 'Renner', 'Usado', 1, 'ttttt.png'],
        ['Top Cropped Básico', 'Top cropped básico versátil', 25.00, 'P', 'Branco', 'C&A', 'Novo', 1, 'bbbbb.png'],
        
        // Masculino
        ['Camisa Social Slim', 'Camisa social masculina corte slim', 60.00, 'M', 'Azul', 'Aramis', 'Seminovo', 2, 'ergnu9itgnruedgregmei.png'],
        ['Jeans Masculino', 'Calça jeans masculina reta', 70.00, 'G', 'Azul Escuro', 'Wrangler', 'Seminovo', 2, 'vretmunui9sf.png'],
        ['Polo Clássica', 'Camisa polo masculina clássica', 35.00, 'M', 'Branco', 'Lacoste', 'Usado', 2, 'ff1b1c3ed2706fff44bbdce0441f394b3d564df3.png'],
        
        // Infantil
        ['Vestido Infantil', 'Vestido infantil com estampa de unicórnio', 30.00, 'P', 'Rosa', 'Lilica Ripilica', 'Seminovo', 3, 'vvvv.png'],
        ['Conjunto Menino', 'Conjunto camiseta e bermuda', 40.00, 'M', 'Azul', 'Tigor', 'Novo', 3, 'ttttt.png'],
        
        // Acessórios
        ['Bolsa Vintage', 'Bolsa de couro vintage', 90.00, 'Único', 'Marrom', 'Artesanal', 'Usado', 4, 'bolsas.jpg'],
        ['Sapato Social', 'Sapato social feminino', 120.00, '37', 'Preto', 'Arezzo', 'Seminovo', 4, 'bbbbb.png']
    ];

    $stmt = $pdo->prepare("INSERT IGNORE INTO produtos (nome, descricao, preco, tamanho, cor, marca, condicao, categoria_id, imagem) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    foreach ($produtos as $produto) {
        $stmt->execute($produto);
    }
    echo "✅ Produtos inseridos!\n";

    // Verificar dados
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM produtos");
    $total_produtos = $stmt->fetch()['total'];
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM categorias");
    $total_categorias = $stmt->fetch()['total'];
    
    echo "\n📊 Resumo:\n";
    echo "- Produtos: $total_produtos\n";
    echo "- Categorias: $total_categorias\n";
    echo "\n🎉 Banco expandido com sucesso!\n";

} catch (PDOException $e) {
    echo "❌ ERRO: " . $e->getMessage() . "\n";
}
?>