-- Tabela de vendas/pedidos
CREATE TABLE IF NOT EXISTS vendas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    produto_id INT NOT NULL,
    vendedor_id INT NOT NULL,
    comprador_id INT NULL,
    valor_venda DECIMAL(10,2) NOT NULL,
    comissao DECIMAL(10,2) DEFAULT 0.00,
    lucro_vendedor DECIMAL(10,2) NOT NULL,
    status ENUM('Pendente', 'Confirmada', 'Cancelada') DEFAULT 'Pendente',
    data_venda DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE CASCADE,
    FOREIGN KEY (vendedor_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (comprador_id) REFERENCES usuarios(id) ON DELETE SET NULL
);

-- Adicionar campo vendedor_id na tabela produtos se não existir
ALTER TABLE produtos ADD COLUMN IF NOT EXISTS vendedor_id INT NULL;
ALTER TABLE produtos ADD FOREIGN KEY IF NOT EXISTS (vendedor_id) REFERENCES usuarios(id) ON DELETE SET NULL;

-- Inserir algumas vendas de exemplo para demonstração
INSERT INTO vendas (produto_id, vendedor_id, comprador_id, valor_venda, comissao, lucro_vendedor, status, data_venda) VALUES
(1, 1, 2, 45.00, 4.50, 40.50, 'Confirmada', '2024-01-15 10:30:00'),
(2, 1, 3, 80.00, 8.00, 72.00, 'Confirmada', '2024-01-20 14:15:00'),
(3, 1, 2, 25.00, 2.50, 22.50, 'Confirmada', '2024-02-05 09:45:00'),
(4, 1, 4, 120.00, 12.00, 108.00, 'Confirmada', '2024-02-10 16:20:00'),
(5, 1, 3, 35.00, 3.50, 31.50, 'Pendente', '2024-02-15 11:10:00');

-- Atualizar produtos para ter vendedor_id
UPDATE produtos SET vendedor_id = 1 WHERE id <= 10;