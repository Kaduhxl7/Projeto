-- Banco de dados para o projeto DressCode
-- Criado em: 2024

-- Criar banco de dados
CREATE DATABASE IF NOT EXISTS dresscode CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE dresscode;

-- Tabela de usuários
CREATE TABLE usuarios (
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
);

-- Tabela de brechós (para futuro)
CREATE TABLE brechos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    endereco TEXT,
    telefone VARCHAR(20),
    email VARCHAR(255),
    usuario_id INT,
    ativo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Tabela de categorias
CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    descricao TEXT,
    ativo BOOLEAN DEFAULT TRUE
);

-- Inserir categorias padrão
INSERT INTO categorias (nome, descricao) VALUES
('Feminino', 'Roupas femininas'),
('Masculino', 'Roupas masculinas'),
('Infantil', 'Roupas infantis'),
('Acessórios', 'Bolsas, sapatos, joias, etc.'),
('Outros', 'Outros itens de moda');

-- Tabela de produtos (para futuro)
CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10,2) NOT NULL,
    tamanho ENUM('PP', 'P', 'M', 'G', 'GG', 'XG', 'Único'),
    cor VARCHAR(50),
    marca VARCHAR(50),
    condicao ENUM('Novo', 'Seminovo', 'Usado') DEFAULT 'Seminovo',
    categoria_id INT,
    brecho_id INT,
    status ENUM('Ativo', 'Vendido', 'Inativo') DEFAULT 'Ativo',
    visualizacoes INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id),
    FOREIGN KEY (brecho_id) REFERENCES brechos(id) ON DELETE CASCADE
);

-- Tabela de imagens dos produtos (para futuro)
CREATE TABLE produto_imagens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    produto_id INT,
    caminho VARCHAR(255) NOT NULL,
    principal BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE CASCADE
);

-- Tabela de favoritos/lista de desejos (para futuro)
CREATE TABLE favoritos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    produto_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE CASCADE,
    UNIQUE KEY unique_favorito (usuario_id, produto_id)
);

-- Tabela de avaliações (para futuro)
CREATE TABLE avaliacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    produto_id INT,
    usuario_id INT,
    nota INT CHECK (nota >= 1 AND nota <= 5),
    comentario TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Índices para melhor performance
CREATE INDEX idx_produtos_categoria ON produtos(categoria_id);
CREATE INDEX idx_produtos_brecho ON produtos(brecho_id);
CREATE INDEX idx_produtos_status ON produtos(status);
CREATE INDEX idx_usuarios_email ON usuarios(email);

-- Inserir usuário de teste (senha: 123456)
INSERT INTO usuarios (email, senha, nome) VALUES 
('teste@dresscode.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Usuário Teste');

-- Comentários sobre as tabelas
-- usuarios: Armazena informações dos usuários do sistema
-- brechos: Armazena informações dos brechós cadastrados
-- categorias: Categorias de produtos (Feminino, Masculino, etc.)
-- produtos: Produtos disponíveis para venda
-- produto_imagens: Imagens dos produtos
-- favoritos: Lista de desejos dos usuários
-- avaliacoes: Avaliações e comentários dos produtos