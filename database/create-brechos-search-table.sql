-- Atualizar tabela de brechós para sistema de busca por localização
-- Executar este script para adicionar campos de localização

USE dresscode;

-- Atualizar tabela brechos com campos de localização
ALTER TABLE brechos 
ADD COLUMN cidade VARCHAR(100) AFTER endereco,
ADD COLUMN estado VARCHAR(50) AFTER cidade,
ADD COLUMN cep VARCHAR(10) AFTER estado,
ADD COLUMN latitude DECIMAL(10,8) AFTER cep,
ADD COLUMN longitude DECIMAL(11,8) AFTER latitude;

-- Criar índices para performance de busca
CREATE INDEX idx_brechos_cidade ON brechos(cidade);
CREATE INDEX idx_brechos_estado ON brechos(estado);
CREATE INDEX idx_brechos_latitude ON brechos(latitude);
CREATE INDEX idx_brechos_longitude ON brechos(longitude);
CREATE INDEX idx_brechos_location ON brechos(latitude, longitude);

-- Inserir dados de exemplo para teste
INSERT INTO brechos (nome, descricao, endereco, cidade, estado, cep, latitude, longitude, usuario_id) VALUES
('Brechó Vintage SP', 'Peças vintage e retrô', 'Rua Augusta, 1234', 'São Paulo', 'SP', '01305-100', -23.5505, -46.6333, 1),
('Moda Consciente RJ', 'Roupas sustentáveis', 'Rua das Flores, 567', 'Rio de Janeiro', 'RJ', '22071-900', -22.9068, -43.1729, 1),
('Brechó do Centro', 'Variedades no centro', 'Av. Paulista, 890', 'São Paulo', 'SP', '01310-100', -23.5618, -46.6565, 1),
('Estilo Único BH', 'Peças exclusivas', 'Rua da Bahia, 321', 'Belo Horizonte', 'MG', '30112-000', -19.9167, -43.9345, 1),
('Vintage Curitiba', 'Moda retrô', 'Rua XV de Novembro, 654', 'Curitiba', 'PR', '80020-310', -25.4284, -49.2733, 1),
('Brechó Ipanema', 'Estilo praia', 'Rua Visconde de Pirajá, 111', 'Rio de Janeiro', 'RJ', '22410-000', -22.9839, -43.2096, 1),
('Moda Alternativa', 'Roupas diferenciadas', 'Rua Oscar Freire, 222', 'São Paulo', 'SP', '01426-001', -23.5629, -46.6719, 1),
('Brechó da Vila', 'Peças acessíveis', 'Rua Teodoro Sampaio, 333', 'São Paulo', 'SP', '05405-000', -23.5629, -46.6719, 1);