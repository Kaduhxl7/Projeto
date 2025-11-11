<?php
/**
 * Padrão Strategy - Busca por Preço
 * 
 * Implementa busca focada em faixas de preço com algoritmos
 * otimizados para encontrar as melhores ofertas.
 */

require_once __DIR__ . '/SearchStrategy.php';

class SearchByPriceStrategy implements SearchStrategy {
    
    public function search(PDO $db, array $filters): array {
        $query = "SELECT p.*, c.nome as categoria_nome, c.slug as categoria_slug,
                  CASE 
                      WHEN p.condicao = 'promocional' THEN p.preco * 0.8
                      WHEN p.condicao = 'usado' THEN p.preco * 0.9
                      ELSE p.preco 
                  END as preco_final
                  FROM produtos p 
                  LEFT JOIN categorias c ON p.categoria_id = c.id 
                  WHERE p.status = 'Ativo'";
        
        $params = [];
        
        // Filtros de preço (principal funcionalidade desta estratégia)
        if (!empty($filters['preco_min'])) {
            $query .= " AND p.preco >= :preco_min";
            $params[':preco_min'] = $filters['preco_min'];
        }
        
        if (!empty($filters['preco_max'])) {
            $query .= " AND p.preco <= :preco_max";
            $params[':preco_max'] = $filters['preco_max'];
        }
        
        // Filtro por faixa de preço predefinida
        if (!empty($filters['faixa_preco'])) {
            $faixaPreco = $this->getPriceBounds($filters['faixa_preco']);
            if ($faixaPreco) {
                $query .= " AND p.preco BETWEEN :faixa_min AND :faixa_max";
                $params[':faixa_min'] = $faixaPreco['min'];
                $params[':faixa_max'] = $faixaPreco['max'];
            }
        }
        
        // Filtros complementares
        if (!empty($filters['search'])) {
            $query .= " AND (p.nome LIKE :search OR p.descricao LIKE :search OR p.marca LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        
        if (!empty($filters['categoria'])) {
            $query .= " AND c.slug = :categoria";
            $params[':categoria'] = $filters['categoria'];
        }
        
        if (!empty($filters['condicao'])) {
            $query .= " AND p.condicao = :condicao";
            $params[':condicao'] = $filters['condicao'];
        }
        
        // Filtro para ofertas especiais
        if (!empty($filters['apenas_ofertas'])) {
            $query .= " AND (p.condicao = 'promocional' OR p.condicao = 'usado')";
        }
        
        // Ordenação otimizada para preço
        $orderBy = $this->getPriceOrderClause($filters);
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
        
        if (!empty($filters['preco_min'])) {
            $query .= " AND p.preco >= :preco_min";
            $params[':preco_min'] = $filters['preco_min'];
        }
        
        if (!empty($filters['preco_max'])) {
            $query .= " AND p.preco <= :preco_max";
            $params[':preco_max'] = $filters['preco_max'];
        }
        
        if (!empty($filters['faixa_preco'])) {
            $faixaPreco = $this->getPriceBounds($filters['faixa_preco']);
            if ($faixaPreco) {
                $query .= " AND p.preco BETWEEN :faixa_min AND :faixa_max";
                $params[':faixa_min'] = $faixaPreco['min'];
                $params[':faixa_max'] = $faixaPreco['max'];
            }
        }
        
        if (!empty($filters['search'])) {
            $query .= " AND (p.nome LIKE :search OR p.descricao LIKE :search OR p.marca LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        
        if (!empty($filters['categoria'])) {
            $query .= " AND c.slug = :categoria";
            $params[':categoria'] = $filters['categoria'];
        }
        
        if (!empty($filters['condicao'])) {
            $query .= " AND p.condicao = :condicao";
            $params[':condicao'] = $filters['condicao'];
        }
        
        if (!empty($filters['apenas_ofertas'])) {
            $query .= " AND (p.condicao = 'promocional' OR p.condicao = 'usado')";
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
     * Define faixas de preço predefinidas
     */
    private function getPriceBounds(string $faixa): ?array {
        $faixas = [
            'ate_50' => ['min' => 0, 'max' => 50],
            '50_100' => ['min' => 50, 'max' => 100],
            '100_200' => ['min' => 100, 'max' => 200],
            '200_500' => ['min' => 200, 'max' => 500],
            'acima_500' => ['min' => 500, 'max' => 999999]
        ];
        
        return $faixas[$faixa] ?? null;
    }
    
    /**
     * Define ordenação otimizada para busca por preço
     */
    private function getPriceOrderClause(array $filters): string {
        $orderBy = $filters['order'] ?? 'melhor_preco';
        $orderDir = strtoupper($filters['dir'] ?? 'ASC');
        
        if (!in_array($orderDir, ['ASC', 'DESC'])) {
            $orderDir = 'ASC';
        }
        
        switch ($orderBy) {
            case 'preco':
                return "p.preco {$orderDir}";
            case 'melhor_preco':
                // Prioriza ofertas e produtos com melhor custo-benefício
                return "CASE WHEN p.condicao = 'promocional' THEN 0 WHEN p.condicao = 'usado' THEN 1 ELSE 2 END ASC, p.preco ASC";
            case 'maior_desconto':
                // Produtos com maior potencial de desconto primeiro
                return "CASE WHEN p.condicao = 'promocional' THEN p.preco WHEN p.condicao = 'usado' THEN p.preco * 1.1 ELSE p.preco * 1.2 END ASC";
            case 'popularidade':
                return "p.visualizacoes DESC, p.preco ASC";
            default:
                return "p.preco {$orderDir}";
        }
    }
    
    /**
     * Obtém faixas de preço disponíveis
     */
    public static function getAvailablePriceRanges(): array {
        return [
            'ate_50' => 'Até R$ 50',
            '50_100' => 'R$ 50 - R$ 100',
            '100_200' => 'R$ 100 - R$ 200',
            '200_500' => 'R$ 200 - R$ 500',
            'acima_500' => 'Acima de R$ 500'
        ];
    }
    
    public function getName(): string {
        return 'Busca por Preço';
    }
}
?>