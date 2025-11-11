<?php
class Database {
    private $db_path = __DIR__ . '/../../dresscode.sqlite';
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            // Criar banco SQLite se não existir
            $this->conn = new PDO("sqlite:" . $this->db_path);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Criar tabelas se não existirem
            $this->createTables();
            
        } catch(PDOException $e) {
            echo "Erro na conexão: " . $e->getMessage();
        }
        return $this->conn;
    }
    
    private function createTables() {
        // Criar tabela de categorias
        $sql_categorias = "
        CREATE TABLE IF NOT EXISTS categorias (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nome VARCHAR(50) NOT NULL,
            slug VARCHAR(50) NOT NULL,
            descricao TEXT,
            ativo BOOLEAN DEFAULT 1
        )";
        $this->conn->exec($sql_categorias);
        
        // Inserir categorias se não existirem
        $count = $this->conn->query("SELECT COUNT(*) FROM categorias")->fetchColumn();
        if ($count == 0) {
            $categorias = [
                ['Feminino', 'feminino', 'Roupas femininas'],
                ['Masculino', 'masculino', 'Roupas masculinas'],
                ['Infantil', 'infantil', 'Roupas infantis'],
                ['Acessórios', 'acessorios', 'Bolsas, sapatos, joias, etc.']
            ];
            
            $stmt = $this->conn->prepare("INSERT INTO categorias (nome, slug, descricao) VALUES (?, ?, ?)");
            foreach ($categorias as $cat) {
                $stmt->execute($cat);
            }
        }
        
        // Criar tabela de produtos
        $sql_produtos = "
        CREATE TABLE IF NOT EXISTS produtos (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nome VARCHAR(100) NOT NULL,
            descricao TEXT,
            preco DECIMAL(10,2) NOT NULL,
            tamanho VARCHAR(10),
            cor VARCHAR(50),
            marca VARCHAR(50),
            condicao VARCHAR(20) DEFAULT 'Seminovo',
            categoria_id INTEGER,
            imagem VARCHAR(255),
            status VARCHAR(20) DEFAULT 'Ativo',
            visualizacoes INTEGER DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (categoria_id) REFERENCES categorias(id)
        )";
        $this->conn->exec($sql_produtos);
        
        // Inserir produtos se não existirem
        $count = $this->conn->query("SELECT COUNT(*) FROM produtos")->fetchColumn();
        if ($count == 0) {
            $produtos = [
                ['Blusa Floral Vintage', 'Linda blusa com estampa floral, perfeita para o verão', 45.90, 'M', 'Floral', 'Vintage', 'Seminovo', 1, 'blusa-floral.jpg'],
                ['Calça Jeans Skinny', 'Calça jeans azul escuro, modelo skinny', 89.90, 'G', 'Azul', 'Levis', 'Usado', 1, 'calca-jeans.jpg'],
                ['Vestido Midi Bege', 'Vestido midi em tom bege, muito elegante', 120.00, 'P', 'Bege', 'Zara', 'Seminovo', 1, 'vestido-midi.jpg'],
                ['Camisa Social Branca', 'Camisa social masculina branca, tamanho M', 35.00, 'M', 'Branco', 'Renner', 'Seminovo', 2, 'camisa-social.jpg'],
                ['Tênis Esportivo', 'Tênis esportivo preto, muito confortável', 75.50, 'G', 'Preto', 'Nike', 'Usado', 4, 'tenis-esportivo.jpg'],
                ['Saia Plissada Rosa', 'Saia plissada em tom rosa claro', 55.00, 'M', 'Rosa', 'C&A', 'Novo', 1, 'saia-plissada.jpg'],
                ['Jaqueta Jeans', 'Jaqueta jeans clássica, muito versátil', 95.00, 'M', 'Azul', 'Wrangler', 'Seminovo', 1, 'jaqueta-jeans.jpg'],
                ['Bolsa de Couro', 'Bolsa de couro marrom, muito elegante', 80.00, 'Único', 'Marrom', 'Arezzo', 'Usado', 4, 'bolsa-couro.jpg']
            ];
            
            $stmt = $this->conn->prepare("INSERT INTO produtos (nome, descricao, preco, tamanho, cor, marca, condicao, categoria_id, imagem) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            foreach ($produtos as $produto) {
                $stmt->execute($produto);
            }
        }
        
        // Criar tabela de usuários
        $sql_usuarios = "
        CREATE TABLE IF NOT EXISTS usuarios (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            email VARCHAR(255) UNIQUE NOT NULL,
            senha VARCHAR(255) NOT NULL,
            nome VARCHAR(100),
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )";
        $this->conn->exec($sql_usuarios);
        
        // Inserir usuário de teste se não existir
        $count = $this->conn->query("SELECT COUNT(*) FROM usuarios WHERE email = 'teste@dresscode.com'")->fetchColumn();
        if ($count == 0) {
            $stmt = $this->conn->prepare("INSERT INTO usuarios (email, senha, nome) VALUES (?, ?, ?)");
            $stmt->execute(['teste@dresscode.com', password_hash('123456', PASSWORD_DEFAULT), 'Usuário Teste']);
        }
    }
}
?>