<?php
require_once __DIR__ . '/../config/bootstrap.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Dashboard.php';

class DashboardController {
    private $dashboardModel;
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->dashboardModel = new Dashboard($this->db);
    }
    
    public function index() {
        // Verificar se usuário está logado
        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php');
            exit;
        }
        
        // Verificar se usuário é vendedor
        $stmt = $this->db->prepare("SELECT quero_vender, nome FROM usuarios WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$usuario || !$usuario['quero_vender']) {
            $page_title = __('dashboard.access_denied');
            $error_message = __('dashboard.seller_only');
            include __DIR__ . '/../views/dashboard_error.php';
            return;
        }
        
        $vendedor_id = $_SESSION['user_id'];
        $periodo = $_GET['periodo'] ?? null;
        
        // Obter dados do dashboard
        $resumo = $this->dashboardModel->getVendasResumo($vendedor_id, $periodo);
        $vendas_por_mes = $this->dashboardModel->getVendasPorMes($vendedor_id);
        $vendas_por_categoria = $this->dashboardModel->getVendasPorCategoria($vendedor_id);
        $vendas_recentes = $this->dashboardModel->getVendasRecentes($vendedor_id);
        $produtos_ativos = $this->dashboardModel->getProdutosAtivos($vendedor_id);
        $crescimento = $this->dashboardModel->getCrescimentoMensal($vendedor_id);
        
        // Dados para a view
        $data = [
            'usuario' => $usuario,
            'resumo' => $resumo,
            'vendas_por_mes' => $vendas_por_mes,
            'vendas_por_categoria' => $vendas_por_categoria,
            'vendas_recentes' => $vendas_recentes,
            'produtos_ativos' => $produtos_ativos,
            'crescimento' => $crescimento,
            'periodo_selecionado' => $periodo
        ];
        
        $page_title = __('dashboard.title');
        $page_description = __('dashboard.description');
        
        include __DIR__ . '/../views/dashboard.php';
    }
    
    public function exportarDados() {
        if (!isset($_SESSION['user_id'])) {
            header('HTTP/1.0 403 Forbidden');
            exit;
        }
        
        $vendedor_id = $_SESSION['user_id'];
        $vendas = $this->dashboardModel->getVendasRecentes($vendedor_id, 1000);
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="vendas_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        // Cabeçalho CSV
        fputcsv($output, [
            __('dashboard.product'),
            __('dashboard.sale_date'),
            __('dashboard.sale_value'),
            __('dashboard.profit'),
            __('dashboard.status')
        ]);
        
        // Dados
        foreach ($vendas as $venda) {
            fputcsv($output, [
                $venda['produto_nome'],
                date('d/m/Y H:i', strtotime($venda['data_venda'])),
                'R$ ' . number_format($venda['valor_venda'], 2, ',', '.'),
                'R$ ' . number_format($venda['lucro_vendedor'], 2, ',', '.'),
                __('dashboard.status_' . strtolower($venda['status']))
            ]);
        }
        
        fclose($output);
    }
}
?>