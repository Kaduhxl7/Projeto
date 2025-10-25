<?php
require_once __DIR__ . '/../config/bootstrap.php';
require_once __DIR__ . '/../repositories/ProductRepository.php';
require_once __DIR__ . '/../repositories/CategoryRepository.php';
require_once __DIR__ . '/../config/DatabaseConnection.php';
require_once __DIR__ . '/../factories/ProductFactory.php';
require_once __DIR__ . '/../search/BuscarProdutosStrategy.php';

class ProductController {
    private $productRepository;
    private $categoryRepository;

    public function __construct() {
        // Usar padrão Singleton para conexão
        $db = DatabaseConnection::getInstance();
        $this->productRepository = new ProductRepository($db);
        $this->categoryRepository = new CategoryRepository($db);
    }

    // Listar produtos por categoria
    public function listByCategory($categoria_slug) {
        // Verificar se categoria existe
        $categoria = $this->categoryRepository->findBySlugAsArray($categoria_slug);
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

        // Buscar produtos usando nova arquitetura Strategy + Decorator
        $searchStrategy = new BuscarProdutosStrategy($this->productRepository, $filters);
        $produtos = $searchStrategy->search();
        $total_produtos = $this->productRepository->count($filters);
        $total_pages = ceil($total_produtos / $limit);

        // Filtros disponíveis
        $filtros_disponiveis = $this->productRepository->getAvailableFilters($categoria_slug);

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
        $produto = $this->productRepository->findByIdAsArray($id);
        
        if (!$produto) {
            header('HTTP/1.0 404 Not Found');
            include __DIR__ . '/../views/errors/404.php';
            return;
        }

        // Incrementar visualizações
        $this->productRepository->incrementViews($id);

        // Produtos relacionados
        $produtos_relacionados = $this->productRepository->getRelated($produto['categoria_id'], $id);

        $data = [
            'produto' => $produto,
            'produtos_relacionados' => $produtos_relacionados
        ];

        include __DIR__ . '/../views/products/detail.php';
    }

    // Busca geral (usando padrão Strategy)
    public function search() {
        $filters = $this->processFilters();
        
        // Paginação
        $page = $_GET['page'] ?? 1;
        $limit = 12;
        $filters['page'] = $page;
        $filters['limit'] = $limit;

        // Buscar produtos usando nova arquitetura Strategy + Decorator
        $searchStrategy = new BuscarProdutosStrategy($this->productRepository, $filters);
        $produtos = $searchStrategy->search();
        $total_produtos = $this->productRepository->count($filters);
        $total_pages = ceil($total_produtos / $limit);

        // Filtros disponíveis
        $filtros_disponiveis = $this->productRepository->getAvailableFilters();

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
        $searchStrategy = new BuscarProdutosStrategy($this->productRepository, $filters);
        $produtos = $searchStrategy->search();
        
        echo json_encode([
            'success' => true,
            'produtos' => $produtos,
            'total' => count($produtos)
        ]);
    }

    // Métodos de favoritos movidos da FavoritosController
    public function favoritarAction() {
        session_start();
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Usuário não logado']);
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        $produto_id = $input['produto_id'] ?? null;
        $action = $input['action'] ?? 'toggle';
        
        if (!$produto_id) {
            echo json_encode(['status' => 'error', 'message' => 'ID do produto não informado']);
            return;
        }
        
        $usuario_id = $_SESSION['user_id'];
        
        try {
            if ($action === 'remove') {
                $success = $this->productRepository->desfavoritarProduto($produto_id, $usuario_id);
                $message = $success ? 'Produto removido dos favoritos!' : 'Erro ao remover favorito';
            } else {
                // Toggle: se já é favorito, remove; se não é, adiciona
                if ($this->productRepository->isFavorito($produto_id, $usuario_id)) {
                    $success = $this->productRepository->desfavoritarProduto($produto_id, $usuario_id);
                    $message = $success ? 'Produto removido dos favoritos!' : 'Erro ao remover favorito';
                } else {
                    $success = $this->productRepository->favoritarProduto($produto_id, $usuario_id);
                    $message = $success ? 'Produto adicionado aos favoritos!' : 'Produto já está nos favoritos!';
                }
            }
            
            echo json_encode([
                'status' => $success ? 'success' : 'error',
                'message' => $message
            ]);
            
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Erro interno']);
        }
    }

    public function listarFavoritos() {
        session_start();
        
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login.php');
            return;
        }
        
        $favoritos = $this->productRepository->listarFavoritos($_SESSION['user_id']);
        
        $data = ['favoritos' => $favoritos];
        include __DIR__ . '/../views/products/favoritos.php';
    }
}
?>