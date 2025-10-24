<?php
require_once __DIR__ . '/../config/database.php';

class Brecho {
    private $conn;
    private $table_name = "brechos";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Buscar brechós por cidade/bairro
    public function searchByLocation($search_term) {
        $query = "SELECT id, nome, endereco, cidade, estado, latitude, longitude, descricao 
                  FROM " . $this->table_name . " 
                  WHERE ativo = 1 
                  AND (cidade LIKE :search OR endereco LIKE :search OR estado LIKE :search)
                  ORDER BY nome ASC";
        
        $stmt = $this->conn->prepare($query);
        $search_param = "%" . $search_term . "%";
        $stmt->bindParam(':search', $search_param);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar brechós por proximidade (fórmula Haversine)
    public function searchByProximity($latitude, $longitude, $radius = 50) {
        $query = "SELECT id, nome, endereco, cidade, estado, latitude, longitude, descricao,
                  (6371 * ACOS(
                      COS(RADIANS(:lat)) * COS(RADIANS(latitude)) * 
                      COS(RADIANS(longitude) - RADIANS(:lng)) + 
                      SIN(RADIANS(:lat)) * SIN(RADIANS(latitude))
                  )) AS distancia
                  FROM " . $this->table_name . " 
                  WHERE ativo = 1 
                  AND latitude IS NOT NULL 
                  AND longitude IS NOT NULL
                  HAVING distancia < :radius
                  ORDER BY distancia ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':lat', $latitude);
        $stmt->bindParam(':lng', $longitude);
        $stmt->bindParam(':radius', $radius);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar brechós com filtros
    public function searchWithFilters($filters) {
        $conditions = ["ativo = 1"];
        $params = [];

        // Filtro por texto
        if (!empty($filters['search'])) {
            $conditions[] = "(cidade LIKE :search OR endereco LIKE :search OR nome LIKE :search)";
            $params[':search'] = "%" . $filters['search'] . "%";
        }

        // Filtro por estado
        if (!empty($filters['estado'])) {
            $conditions[] = "estado = :estado";
            $params[':estado'] = $filters['estado'];
        }

        // Construir query base
        $query = "SELECT id, nome, endereco, cidade, estado, latitude, longitude, descricao";
        
        // Se tem coordenadas, calcular distância
        if (!empty($filters['latitude']) && !empty($filters['longitude'])) {
            $query .= ", (6371 * ACOS(
                          COS(RADIANS(:lat)) * COS(RADIANS(latitude)) * 
                          COS(RADIANS(longitude) - RADIANS(:lng)) + 
                          SIN(RADIANS(:lat)) * SIN(RADIANS(latitude))
                      )) AS distancia";
            $params[':lat'] = $filters['latitude'];
            $params[':lng'] = $filters['longitude'];
        }

        $query .= " FROM " . $this->table_name . " WHERE " . implode(' AND ', $conditions);

        // Filtro por distância máxima
        if (!empty($filters['max_distance']) && !empty($filters['latitude'])) {
            $query .= " HAVING distancia < :max_distance";
            $params[':max_distance'] = $filters['max_distance'];
        }

        // Ordenação
        if (!empty($filters['latitude']) && !empty($filters['longitude'])) {
            $query .= " ORDER BY distancia ASC";
        } else {
            $order = $filters['order'] ?? 'nome';
            $query .= " ORDER BY " . $order . " ASC";
        }

        // Paginação
        if (!empty($filters['limit'])) {
            $offset = (!empty($filters['page']) ? ($filters['page'] - 1) * $filters['limit'] : 0);
            $query .= " LIMIT :limit OFFSET :offset";
            $params[':limit'] = $filters['limit'];
            $params[':offset'] = $offset;
        }

        $stmt = $this->conn->prepare($query);
        
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

    // Contar total de brechós para paginação
    public function countWithFilters($filters) {
        $conditions = ["ativo = 1"];
        $params = [];

        if (!empty($filters['search'])) {
            $conditions[] = "(cidade LIKE :search OR endereco LIKE :search OR nome LIKE :search)";
            $params[':search'] = "%" . $filters['search'] . "%";
        }

        if (!empty($filters['estado'])) {
            $conditions[] = "estado = :estado";
            $params[':estado'] = $filters['estado'];
        }

        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE " . implode(' AND ', $conditions);

        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    // Buscar brechó por ID
    public function findById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id AND ativo = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Obter estados disponíveis
    public function getAvailableStates() {
        $query = "SELECT DISTINCT estado FROM " . $this->table_name . " WHERE ativo = 1 AND estado IS NOT NULL ORDER BY estado";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
?>