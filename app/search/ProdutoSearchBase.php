<?php
require_once __DIR__ . '/ProdutoSearchInterface.php';

/**
 * Busca base para produtos - implementação concreta inicial
 */
class ProdutoSearchBase implements ProdutoSearchInterface {
    public function getSQL(): string {
        return "SELECT p.*, c.nome as categoria_nome FROM produtos p LEFT JOIN categorias c ON p.categoria_id = c.id WHERE p.status = 'Ativo'";
    }
    
    public function getParams(): array {
        return [];
    }
}
?>