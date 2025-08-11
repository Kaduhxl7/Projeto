<?php
// Script para criar banco de dados DressCode
echo "=== DressCode - Criação do Banco de Dados ===\n\n";

// Configurações do banco
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'dresscode';

try {
    // Conectar ao MySQL (sem especificar banco)
    echo "1. Conectando ao MySQL...\n";
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Conectado com sucesso!\n\n";

    // Criar banco de dados
    echo "2. Criando banco de dados 'dresscode'...\n";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $database CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✅ Banco de dados criado!\n\n";

    // Usar o banco criado
    $pdo->exec("USE $database");

    // Criar tabela usuarios
    echo "3. Criando tabela 'usuarios'...\n";
    $sql = "
    CREATE TABLE IF NOT EXISTS usuarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) UNIQUE NOT NULL,
        senha VARCHAR(255) NOT NULL,
        nome VARCHAR(100),
        telefone VARCHAR(20),
        data_nascimento DATE,
        genero ENUM('M', 'F', 'Outro'),
        foto_perfil VARCHAR(255),
        ativo BOOLEAN DEFAULT TRUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "✅ Tabela 'usuarios' criada!\n\n";

    // Criar tabela categorias
    echo "4. Criando tabela 'categorias'...\n";
    $sql = "
    CREATE TABLE IF NOT EXISTS categorias (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(50) NOT NULL,
        descricao TEXT,
        ativo BOOLEAN DEFAULT TRUE
    )";
    $pdo->exec($sql);
    echo "✅ Tabela 'categorias' criada!\n\n";

    // Inserir categorias padrão
    echo "5. Inserindo categorias padrão...\n";
    $sql = "
    INSERT IGNORE INTO categorias (nome, descricao) VALUES
    ('Feminino', 'Roupas femininas'),
    ('Masculino', 'Roupas masculinas'),
    ('Infantil', 'Roupas infantis'),
    ('Acessórios', 'Bolsas, sapatos, joias, etc.'),
    ('Outros', 'Outros itens de moda')
    ";
    $pdo->exec($sql);
    echo "✅ Categorias inseridas!\n\n";

    // Inserir usuário de teste
    echo "6. Criando usuário de teste...\n";
    $sql = "
    INSERT IGNORE INTO usuarios (email, senha, nome) VALUES 
    ('teste@dresscode.com', '$2y$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Usuário Teste'),
    ('admin@dresscode.com', '$2y$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador')
    ";
    $pdo->exec($sql);
    echo "✅ Usuários de teste criados!\n\n";

    // Verificar dados
    echo "7. Verificando dados criados...\n";
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM usuarios");
    $usuarios = $stmt->fetch()['total'];
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM categorias");
    $categorias = $stmt->fetch()['total'];
    
    echo "📊 Usuários: $usuarios\n";
    echo "📊 Categorias: $categorias\n\n";

    echo "🎉 BANCO DE DADOS CRIADO COM SUCESSO!\n\n";
    echo "=== INFORMAÇÕES DE LOGIN ===\n";
    echo "Email: teste@dresscode.com\n";
    echo "Senha: 123456\n\n";
    echo "Email: admin@dresscode.com\n";
    echo "Senha: 123456\n\n";
    echo "=== PRÓXIMOS PASSOS ===\n";
    echo "1. Execute: php -S localhost:8000 (na pasta public)\n";
    echo "2. Acesse: http://localhost:8000\n";
    echo "3. Faça login com as credenciais acima\n\n";

} catch (PDOException $e) {
    echo "❌ ERRO: " . $e->getMessage() . "\n\n";
    echo "=== POSSÍVEIS SOLUÇÕES ===\n";
    echo "1. Verifique se o MySQL/XAMPP está rodando\n";
    echo "2. Confirme usuário/senha do MySQL\n";
    echo "3. Edite as configurações no início deste arquivo\n\n";
    exit(1);
}
?>