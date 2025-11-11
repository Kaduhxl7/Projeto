<?php
require_once __DIR__ . '/../config/bootstrap.php';
require_once __DIR__ . '/../models/Report.php';
require_once __DIR__ . '/../config/database.php';

class ReportController {
    private $reportModel;
    
    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        $this->reportModel = new Report($db);
    }
    
    public function create() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['status' => 'error', 'message' => 'Método não permitido']);
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        $productId = $input['product_id'] ?? null;
        $reason = $input['reason'] ?? null;
        $description = $input['description'] ?? '';
        $userId = $_SESSION['user_id'] ?? null;
        
        if (!$productId || !$reason) {
            echo json_encode(['status' => 'error', 'message' => __('report.required_fields')]);
            return;
        }
        
        // Verificar se usuário já reportou este produto
        if ($this->reportModel->existsForUser($productId, $userId)) {
            echo json_encode(['status' => 'error', 'message' => __('report.already_reported')]);
            return;
        }
        
        if ($this->reportModel->create($productId, $userId, $reason, $description)) {
            echo json_encode(['status' => 'success', 'message' => __('report.success_message')]);
        } else {
            echo json_encode(['status' => 'error', 'message' => __('report.error_message')]);
        }
    }
}