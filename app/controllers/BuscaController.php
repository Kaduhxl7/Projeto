<?php
require_once __DIR__ . '/../config/bootstrap.php';
require_once __DIR__ . '/../models/Brecho.php';

class BuscaController {
    private $brechoModel;

    public function __construct() {
        $this->brechoModel = new Brecho();
    }

    // Página principal de busca
    public function index() {
        $data = [
            'brechos' => [],
            'search_term' => '',
            'filters' => [],
            'estados' => $this->brechoModel->getAvailableStates(),
            'total_results' => 0
        ];

        include __DIR__ . '/../views/busca.php';
    }

    // Processar busca
    public function search() {
        $filters = $this->processFilters();
        
        // Paginação
        $page = $_GET['page'] ?? 1;
        $limit = 12;
        $filters['page'] = $page;
        $filters['limit'] = $limit;

        // Buscar brechós
        $brechos = $this->brechoModel->searchWithFilters($filters);
        $total_results = $this->brechoModel->countWithFilters($filters);
        $total_pages = ceil($total_results / $limit);

        $data = [
            'brechos' => $brechos,
            'search_term' => $filters['search'] ?? '',
            'filters' => $filters,
            'estados' => $this->brechoModel->getAvailableStates(),
            'total_results' => $total_results,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $total_pages,
                'total_items' => $total_results,
                'per_page' => $limit
            ]
        ];

        include __DIR__ . '/../views/busca.php';
    }

    // API para busca AJAX
    public function apiSearch() {
        header('Content-Type: application/json');
        
        try {
            $filters = $this->processFilters();
            $brechos = $this->brechoModel->searchWithFilters($filters);
            
            echo json_encode([
                'success' => true,
                'brechos' => $brechos,
                'total' => count($brechos)
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => 'Erro na busca'
            ]);
        }
    }

    // Busca por geolocalização
    public function searchByLocation() {
        header('Content-Type: application/json');
        
        try {
            $latitude = filter_input(INPUT_POST, 'latitude', FILTER_VALIDATE_FLOAT);
            $longitude = filter_input(INPUT_POST, 'longitude', FILTER_VALIDATE_FLOAT);
            $radius = filter_input(INPUT_POST, 'radius', FILTER_VALIDATE_INT) ?: 50;

            if (!$latitude || !$longitude) {
                throw new Exception('Coordenadas inválidas');
            }

            // Limitar raio máximo para segurança
            if ($radius > 100) {
                $radius = 100;
            }

            $brechos = $this->brechoModel->searchByProximity($latitude, $longitude, $radius);
            
            echo json_encode([
                'success' => true,
                'brechos' => $brechos,
                'total' => count($brechos)
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    // Processar filtros da requisição
    private function processFilters() {
        $filters = [];
        
        // Termo de busca
        if (!empty($_GET['search'])) {
            $filters['search'] = trim(filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING));
        }
        
        // Estado
        if (!empty($_GET['estado'])) {
            $filters['estado'] = filter_input(INPUT_GET, 'estado', FILTER_SANITIZE_STRING);
        }
        
        // Coordenadas para cálculo de distância
        if (!empty($_GET['latitude']) && !empty($_GET['longitude'])) {
            $filters['latitude'] = filter_input(INPUT_GET, 'latitude', FILTER_VALIDATE_FLOAT);
            $filters['longitude'] = filter_input(INPUT_GET, 'longitude', FILTER_VALIDATE_FLOAT);
        }
        
        // Distância máxima
        if (!empty($_GET['max_distance'])) {
            $distance = filter_input(INPUT_GET, 'max_distance', FILTER_VALIDATE_INT);
            if ($distance && $distance <= 100) { // Máximo 100km
                $filters['max_distance'] = $distance;
            }
        }
        
        // Ordenação
        if (!empty($_GET['order'])) {
            $allowed_orders = ['nome', 'cidade', 'estado'];
            $order = filter_input(INPUT_GET, 'order', FILTER_SANITIZE_STRING);
            if (in_array($order, $allowed_orders)) {
                $filters['order'] = $order;
            }
        }
        
        return $filters;
    }

    // Detalhes do brechó
    public function show($id) {
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if (!$id) {
            header('HTTP/1.0 404 Not Found');
            include __DIR__ . '/../views/errors/404.php';
            return;
        }

        $brecho = $this->brechoModel->findById($id);
        if (!$brecho) {
            header('HTTP/1.0 404 Not Found');
            include __DIR__ . '/../views/errors/404.php';
            return;
        }

        $data = ['brecho' => $brecho];
        include __DIR__ . '/../views/brecho-detalhes.php';
    }
}
?>