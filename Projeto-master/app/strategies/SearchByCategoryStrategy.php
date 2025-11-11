<?php
/**
 * Padrão Strategy - Busca por Categoria
 * 
 * Implementa busca focada em produtos de uma categoria específica
 * com filtros avançados por atributos do produto.
 */

require_once __DIR__ . '/SearchStrategy.php';

class SearchByCategoryStrategy implements SearchStrategy {
    
    public function search(PDO $db, array $filters): array {
        $query = "SELECT p.*, c.nome as categoria_nome, c.slug as categoria_slug
                  FROM produtos p 
                  LEFT JOIN categorias c ON p.categoria_id = c.id 
                  WHERE p.status = 'Ativo'";
        
        $params = [];
        
        // Filtro por categoria (obrigatório para esta estratégia)
        if (!empty($filters['categoria'])) {
            $query .= " AND c.slug = :categoria";
            $params[':categoria'] = $filters['categoria'];
        }
        
        // Filtros específicos de produto
        if (!empty($filters['search'])) {
            $query .= " AND (p.nome LIKE :search OR p.descricao LIKE :search OR p.marca LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        
        if (!empty($filters['cor'])) {
            $query .= " AND p.cor LIKE :cor";
            $params[':cor'] = '%' . $filters['cor'] . '%';
        }
        
        if (!empty($filters['tamanho'])) {
            $query .= " AND p.tamanho = :tamanho";
            $params[':tamanho'] = $filters['tamanho'];
        }
        
        if (!empty($filters['marca'])) {
            $query .= " AND p.marca LIKE :marca";
            $params[':marca'] = '%' . $filters['marca'] . '%';
        }
        
        if (!empty($filters['condicao'])) {
            $query .= " AND p.condicao = :condicao";
            $params[':condicao'] = $filters['condicao'];
        }
        
        // Filtros de preço
        if (!empty($filters['preco_min'])) {
            $query .= " AND p.preco >= :preco_min";
            $params[':preco_min'] = $filters['preco_min'];
        }
        
        if (!empty($filters['preco_max'])) {
            $query .= " AND p.preco <= :preco_max";
            $params[':preco_max'] = $filters['preco_max'];
        }
        
        // Ordenação inteligente por categoria
        $orderBy = $this->getOrderByClause($filters);
        $query .= " ORDER BY {$orderBy}";
        
        // Paginação
        if (!empty($filters['limit'])) {
            $page = intval($filters['page'] ?? 1);
            $limit = intval($filters['limit']);
            $offset = ($page - 1) * $limit;
            $query .= " LIMIT {$limit} OFFSET {$offset}";
        }
        
        $stmt = $db->prepare($query);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function count(PDO $db, array $filters): int {
        $query = "SELECT COUNT(*) as total 
                  FROM produtos p 
                  LEFT JOIN categorias c ON p.categoria_id = c.id 
                  WHERE p.status = 'Ativo'";
        
        $params = [];
        
        if (!empty($filters['categoria'])) {
            $query .= " AND c.slug = :categoria";
            $params[':categoria'] = $filters['categoria'];
        }
        
        if (!empty($filters['search'])) {
            $query .= " AND (p.nome LIKE :search OR p.descricao LIKE :search OR p.marca LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        
        if (!empty($filters['cor'])) {
            $query .= " AND p.cor LIKE :cor";
            $params[':cor'] = '%' . $filters['cor'] . '%';
        }
        
        if (!empty($filters['tamanho'])) {
            $query .= " AND p.tamanho = :tamanho";
            $params[':tamanho'] = $filters['tamanho'];
        }
        
        if (!empty($filters['marca'])) {
            $query .= " AND p.marca LIKE :marca";
            $params[':marca'] = '%' . $filters['marca'] . '%';
        }
        
        if (!empty($filters['condicao'])) {
            $query .= " AND p.condicao = :condicao";
            $params[':condicao'] = $filters['condicao'];
        }
        
        if (!empty($filters['preco_min'])) {
            $query .= " AND p.preco >= :preco_min";
            $params[':preco_min'] = $filters['preco_min'];
        }
        
        if (!empty($filters['preco_max'])) {
            $query .= " AND p.preco <= :preco_max";
            $params[':preco_max'] = $filters['preco_max'];
        }
        
        $stmt = $db->prepare($query);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['total'];
    }
    
    /**
     * Define a ordenação baseada nos filtros e categoria
     */
    private function getOrderByClause(array $filters): string {
        $orderBy = $filters['order'] ?? 'relevancia';
        $orderDir = strtoupper($filters['dir'] ?? 'DESC');
        
        // Validar direção
        if (!in_array($orderDir, ['ASC', 'DESC'])) {
            $orderDir = 'DESC';
        }
        
        switch ($orderBy) {
            case 'preco':
                return "p.preco {$orderDir}";
            case 'nome':
                return "p.nome {$orderDir}";
            case 'data':
                return "p.created_at {$orderDir}";
            case 'visualizacoes':
                return "p.visualizacoes {$orderDir}";
            case 'relevancia':
            default:
                // Ordenação por relevância: produtos promocionais primeiro, depois por visualizações
                return "CASE WHEN p.condicao = 'promocional' THEN 0 ELSE 1 END ASC, p.visualizacoes DESC, p.created_at DESC";
        }
    }
    
    public function getName(): string {
        return 'Busca por Categoria';
    }
}
?>