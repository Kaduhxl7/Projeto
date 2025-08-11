<?php
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../config/database.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class ProductController {
    private $db;
    private $product;
    private $category;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->product = new Product($this->db);
        $this->category = new Category($this->db);
    }

    // Listar produtos por categoria
    public function listByCategory($categoria_slug) {
        // Verificar se categoria existe
        $categoria = $this->category->findBySlug($categoria_slug);
        if (!$categoria) {
            header('HTTP/1.0 404 Not Found');
            include __DIR__ . '/../views/errors/404.php';
            return;
        }

        // Processar filtros
        $filters = $this->processFilters();
        $filters['categoria'] = $categoria_slug;

        // Paginação
        $page = $_GET['page'] ?? 1;
        $limit = 12;
        $filters['page'] = $page;
        $filters['limit'] = $limit;

        // Buscar produtos
        $produtos = $this->product->search($filters);
        $total_produtos = $this->product->count($filters);
        $total_pages = ceil($total_produtos / $limit);

        // Filtros disponíveis
        $filtros_disponiveis = $this->product->getAvailableFilters($categoria_slug);

        // Dados para a view
        $data = [
            'categoria' => $categoria,
            'produtos' => $produtos,
            'filtros_disponiveis' => $filtros_disponiveis,
            'filtros_ativos' => $filters,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $total_pages,
                'total_items' => $total_produtos,
                'per_page' => $limit
            ]
        ];

        include __DIR__ . '/../views/products/category.php';
    }

    // Detalhes do produto
    public function show($id) {
        $produto = $this->product->findById($id);
        
        if (!$produto) {
            header('HTTP/1.0 404 Not Found');
            include __DIR__ . '/../views/errors/404.php';
            return;
        }

        // Incrementar visualizações
        $this->product->incrementViews($id);

        // Produtos relacionados
        $produtos_relacionados = $this->product->getRelated($produto['categoria_id'], $id);

        $data = [
            'produto' => $produto,
            'produtos_relacionados' => $produtos_relacionados
        ];

        include __DIR__ . '/../views/products/detail.php';
    }

    // Busca geral
    public function search() {
        $filters = $this->processFilters();
        
        // Paginação
        $page = $_GET['page'] ?? 1;
        $limit = 12;
        $filters['page'] = $page;
        $filters['limit'] = $limit;

        // Buscar produtos
        $produtos = $this->product->search($filters);
        $total_produtos = $this->product->count($filters);
        $total_pages = ceil($total_produtos / $limit);

        // Filtros disponíveis
        $filtros_disponiveis = $this->product->getAvailableFilters();

        $data = [
            'produtos' => $produtos,
            'filtros_disponiveis' => $filtros_disponiveis,
            'filtros_ativos' => $filters,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $total_pages,
                'total_items' => $total_produtos,
                'per_page' => $limit
            ],
            'search_term' => $filters['search'] ?? ''
        ];

        include __DIR__ . '/../views/products/search.php';
    }

    // Processar filtros da URL
    private function processFilters() {
        $filters = [];
        
        if (!empty($_GET['search'])) {
            $filters['search'] = trim($_GET['search']);
        }
        
        if (!empty($_GET['cor'])) {
            $filters['cor'] = $_GET['cor'];
        }
        
        if (!empty($_GET['tamanho'])) {
            $filters['tamanho'] = $_GET['tamanho'];
        }
        
        if (!empty($_GET['marca'])) {
            $filters['marca'] = $_GET['marca'];
        }
        
        if (!empty($_GET['condicao'])) {
            $filters['condicao'] = $_GET['condicao'];
        }
        
        if (!empty($_GET['preco_min'])) {
            $filters['preco_min'] = floatval($_GET['preco_min']);
        }
        
        if (!empty($_GET['preco_max'])) {
            $filters['preco_max'] = floatval($_GET['preco_max']);
        }
        
        if (!empty($_GET['order'])) {
            $filters['order'] = $_GET['order'];
        }
        
        if (!empty($_GET['dir'])) {
            $filters['dir'] = $_GET['dir'];
        }
        
        return $filters;
    }

    // API para busca AJAX
    public function apiSearch() {
        header('Content-Type: application/json');
        
        $filters = $this->processFilters();
        $produtos = $this->product->search($filters);
        
        echo json_encode([
            'success' => true,
            'produtos' => $produtos,
            'total' => count($produtos)
        ]);
    }
}
?>