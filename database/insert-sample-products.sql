-- Inserir produtos de exemplo para testar traduções
-- Execute apenas em ambiente de desenvolvimento

USE dresscode;

-- Inserir produtos de exemplo
INSERT INTO produtos (nome, descricao, preco, tamanho, cor, marca, condicao, categoria_id, visualizacoes) VALUES
('Blusa Vintage Floral', 'Peça única com estilo vintage autêntico', 45.90, 'M', 'Floral', 'Vintage Co.', 'Seminovo', 1, 15),
('Vestido Boho Chic', 'Design moderno e elegante para todas as ocasiões', 89.50, 'P', 'Bege', 'Boho Style', 'Usado', 1, 23),
('Calça Jeans Destroyed', 'Estilo casual e despojado', 65.00, 'G', 'Azul', 'Denim Brand', 'Seminovo', 1, 8),
('Tênis All Star Vintage', 'Item raro para colecionadores', 120.00, '38', 'Preto', 'Converse', 'Usado', 4, 31),
('Bolsa de Couro Artesanal', 'Qualidade premium e durável', 150.00, 'Único', 'Marrom', 'Artesã Local', 'Novo', 4, 12),
('Camiseta de Band Rock', 'Tecido macio e confortável para uso diário', 35.00, 'M', 'Preto', 'Band Merch', 'Usado', 2, 19),
('Blazer Social Feminino', 'Ideal para ambiente de trabalho', 95.00, 'M', 'Preto', 'Executive', 'Seminovo', 1, 7),
('Vestido de Festa Anos 80', 'Estado de conservação excelente', 180.00, 'P', 'Roxo', 'Retro Fashion', 'Vintage', 1, 25);

-- Inserir imagens de exemplo (opcional)
INSERT INTO produto_imagens (produto_id, caminho, principal) VALUES
(1, 'blusa_vintage_1.jpg', TRUE),
(2, 'vestido_boho_1.jpg', TRUE),
(3, 'calca_jeans_1.jpg', TRUE),
(4, 'tenis_allstar_1.jpg', TRUE),
(5, 'bolsa_couro_1.jpg', TRUE),
(6, 'camiseta_rock_1.jpg', TRUE),
(7, 'blazer_social_1.jpg', TRUE),
(8, 'vestido_festa_1.jpg', TRUE);