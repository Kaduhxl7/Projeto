<?php
/**
 * Padrão Strategy - Busca por Localização
 * 
 * Implementa busca baseada em proximidade geográfica usando coordenadas.
 * Utiliza a fórmula Haversine para calcular distâncias.
 */

require_once __DIR__ . '/SearchStrategy.php';

class SearchByLocationStrategy implements SearchStrategy {
    
    public function search(PDO $db, array $filters): array {
        if (empty($filters['latitude']) || empty($filters['longitude'])) {
            throw new InvalidArgumentException('Latitude e longitude são obrigatórias para busca por localização');
        }
        
        $latitude = $filters['latitude'];
        $longitude = $filters['longitude'];
        $radius = $filters['radius'] ?? 50; // Raio padrão de 50km
        
        $query = "SELECT b.*, 
                  (6371 * ACOS(
                      COS(RADIANS(:lat)) * COS(RADIANS(b.latitude)) * 
                      COS(RADIANS(b.longitude) - RADIANS(:lng)) + 
                      SIN(RADIANS(:lat)) * SIN(RADIANS(b.latitude))
                  )) AS distancia
                  FROM brechos b 
                  WHERE b.ativo = 1 
                  AND b.latitude IS NOT NULL 
                  AND b.longitude IS NOT NULL";
        
        $params = [
            ':lat' => $latitude,
            ':lng' => $longitude
        ];
        
        // Filtros adicionais
        if (!empty($filters['search'])) {
            $query .= " AND (b.nome LIKE :search OR b.cidade LIKE :search OR b.endereco LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        
        if (!empty($filters['estado'])) {
            $query .= " AND b.estado = :estado";
            $params[':estado'] = $filters['estado'];
        }
        
        // Filtro por distância
        $query .= " HAVING distancia < :radius";
        $params[':radius'] = $radius;
        
        // Ordenação por distância
        $query .= " ORDER BY distancia ASC";
        
        // Paginação
        if (!empty($filters['limit'])) {
            $offset = (!empty($filters['page']) ? ($filters['page'] - 1) * $filters['limit'] : 0);
            $query .= " LIMIT :limit OFFSET :offset";
            $params[':limit'] = $filters['limit'];
            $params[':offset'] = $offset;
        }
        
        $stmt = $db->prepare($query);
        
        // Bind dos parâmetros
        foreach ($params as $key => $value) {
            if ($key === ':limit' || $key === ':offset') {
                $stmt->bindValue($key, (int)$value, PDO::PARAM_INT);
            } else {
                $stmt->bindValue($key, $value);
            }
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function count(PDO $db, array $filters): int {
        if (empty($filters['latitude']) || empty($filters['longitude'])) {
            return 0;
        }
        
        $latitude = $filters['latitude'];
        $longitude = $filters['longitude'];
        $radius = $filters['radius'] ?? 50;
        
        $query = "SELECT COUNT(*) as total
                  FROM (
                      SELECT b.id,
                      (6371 * ACOS(
                          COS(RADIANS(:lat)) * COS(RADIANS(b.latitude)) * 
                          COS(RADIANS(b.longitude) - RADIANS(:lng)) + 
                          SIN(RADIANS(:lat)) * SIN(RADIANS(b.latitude))
                      )) AS distancia
                      FROM brechos b 
                      WHERE b.ativo = 1 
                      AND b.latitude IS NOT NULL 
                      AND b.longitude IS NOT NULL";
        
        $params = [
            ':lat' => $latitude,
            ':lng' => $longitude
        ];
        
        if (!empty($filters['search'])) {
            $query .= " AND (b.nome LIKE :search OR b.cidade LIKE :search OR b.endereco LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        
        if (!empty($filters['estado'])) {
            $query .= " AND b.estado = :estado";
            $params[':estado'] = $filters['estado'];
        }
        
        $query .= " HAVING distancia < :radius
                  ) as results";
        $params[':radius'] = $radius;
        
        $stmt = $db->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['total'];
    }
    
    public function getName(): string {
        return 'Busca por Localização';
    }
}
?>