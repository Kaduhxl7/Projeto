<?php
// Script para corrigir problemas no banco de dados
echo "=== Corrigindo Banco DressCode ===\n\n";

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'dresscode';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Conectado ao banco!\n\n";

    // Adicionar campo slug na tabela categorias
    echo "1. Adicionando campo slug...\n";
    try {
        $pdo->exec("ALTER TABLE categorias ADD COLUMN slug VARCHAR(50) UNIQUE AFTER nome");
        echo "✅ Campo slug adicionado!\n";
    } catch (Exception $e) {
        echo "⚠️ Campo slug já existe ou erro: " . $e->getMessage() . "\n";
    }

    // Atualizar slugs das categorias
    echo "2. Atualizando slugs...\n";
    $pdo->exec("UPDATE categorias SET slug = 'feminino' WHERE nome = 'Feminino'");
    $pdo->exec("UPDATE categorias SET slug = 'masculino' WHERE nome = 'Masculino'");
    $pdo->exec("UPDATE categorias SET slug = 'infantil' WHERE nome = 'Infantil'");
    $pdo->exec("UPDATE categorias SET slug = 'acessorios' WHERE nome = 'Acessórios'");
    $pdo->exec("UPDATE categorias SET slug = 'outros' WHERE nome = 'Outros'");
    echo "✅ Slugs atualizados!\n";

    // Verificar se produtos existem
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM produtos");
    $total_produtos = $stmt->fetch()['total'];
    
    if ($total_produtos == 0) {
        echo "3. Inserindo produtos de exemplo...\n";
        
        $produtos = [
            // Feminino (id=2)
            ['Blusa Vintage Floral', 'Blusa vintage com estampa floral delicada', 45.00, 'M', 'Floral', 'Vintage', 'Seminovo', 2, 'ergnu9itgnruedgregmei.png'],
            ['Vestido Boho Chic', 'Vestido estilo boho com detalhes únicos', 65.00, 'P', 'Bege', 'Artesanal', 'Novo', 2, 'vretmunui9sf.png'],
            ['Calça Jeans Skinny', 'Calça jeans skinny de alta qualidade', 55.00, 'M', 'Azul', 'Levi\'s', 'Seminovo', 2, 'ff1b1c3ed2706fff44bbdce0441f394b3d564df3.png'],
            ['Saia Plissada Midi', 'Saia plissada elegante midi', 40.00, 'G', 'Preto', 'Zara', 'Seminovo', 2, 'vvvv.png'],
            ['Blazer Social', 'Blazer social feminino clássico', 80.00, 'M', 'Cinza', 'Renner', 'Usado', 2, 'ttttt.png'],
            ['Top Cropped Básico', 'Top cropped básico versátil', 25.00, 'P', 'Branco', 'C&A', 'Novo', 2, 'bbbbb.png'],
            
            // Masculino (id=3)
            ['Camisa Social Slim', 'Camisa social masculina corte slim', 60.00, 'M', 'Azul', 'Aramis', 'Seminovo', 3, 'ergnu9itgnruedgregmei.png'],
            ['Jeans Masculino', 'Calça jeans masculina reta', 70.00, 'G', 'Azul Escuro', 'Wrangler', 'Seminovo', 3, 'vretmunui9sf.png'],
            ['Polo Clássica', 'Camisa polo masculina clássica', 35.00, 'M', 'Branco', 'Lacoste', 'Usado', 3, 'ff1b1c3ed2706fff44bbdce0441f394b3d564df3.png'],
            
            // Infantil (id=4)
            ['Vestido Infantil', 'Vestido infantil com estampa de unicórnio', 30.00, 'P', 'Rosa', 'Lilica Ripilica', 'Seminovo', 4, 'vvvv.png'],
            ['Conjunto Menino', 'Conjunto camiseta e bermuda', 40.00, 'M', 'Azul', 'Tigor', 'Novo', 4, 'ttttt.png'],
            
            // Acessórios (id=1)
            ['Bolsa Vintage', 'Bolsa de couro vintage', 90.00, 'Único', 'Marrom', 'Artesanal', 'Usado', 1, 'bolsas.jpg'],
            ['Sapato Social', 'Sapato social feminino', 120.00, '37', 'Preto', 'Arezzo', 'Seminovo', 1, 'bbbbb.png']
        ];

        $stmt = $pdo->prepare("INSERT INTO produtos (nome, descricao, preco, tamanho, cor, marca, condicao, categoria_id, imagem) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        foreach ($produtos as $produto) {
            $stmt->execute($produto);
        }
        echo "✅ Produtos inseridos!\n";
    } else {
        echo "3. Produtos já existem: $total_produtos\n";
    }

    // Verificar estrutura final
    echo "\n4. Verificando estrutura...\n";
    $stmt = $pdo->query("SELECT id, nome, slug FROM categorias ORDER BY id");
    $categorias = $stmt->fetchAll();
    
    foreach ($categorias as $cat) {
        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM produtos WHERE categoria_id = ?");
        $stmt->execute([$cat['id']]);
        $total = $stmt->fetch()['total'];
        echo "   - {$cat['nome']} ({$cat['slug']}): $total produtos\n";
    }

    echo "\n🎉 Banco corrigido com sucesso!\n";

} catch (PDOException $e) {
    echo "❌ ERRO: " . $e->getMessage() . "\n";
}
?>