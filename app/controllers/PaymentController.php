<?php
require_once __DIR__ . '/../config/bootstrap.php';
require_once __DIR__ . '/../models/Payment.php';
require_once __DIR__ . '/../models/Product.php';

class PaymentController {
    private $payment;
    private $product;
    private $config;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        $this->payment = new Payment($db);
        $this->product = new Product($db);
        $this->config = require __DIR__ . '/../config/payment.php';
    }

    // Exibir tela de checkout
    public function checkout() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php');
            exit;
        }

        // Verificar se usuário é vendedor/brechó
        if (!$this->isUserSeller($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Apenas vendedores podem publicar anúncios pagos.';
            header('Location: index.php');
            exit;
        }

        $id_produto = $_GET['produto'] ?? null;
        if (!$id_produto) {
            header('Location: index.php');
            exit;
        }

        // Verificar se produto existe
        $produto = $this->product->findById($id_produto);
        if (!$produto) {
            $_SESSION['error'] = 'Produto não encontrado.';
            header('Location: index.php');
            exit;
        }

        // Verificar se já tem pagamento
        if ($this->payment->hasPendingPayment($id_produto, $_SESSION['user_id'])) {
            $_SESSION['info'] = 'Este produto já possui um pagamento em andamento.';
            header('Location: produto.php?id=' . $id_produto);
            exit;
        }

        $taxa = $this->payment->getConfig('taxa_anuncio') ?: $this->config['taxa_anuncio'];
        
        $data = [
            'produto' => $produto,
            'taxa' => $taxa,
            'metodos' => $this->config['metodos_disponiveis']
        ];

        include __DIR__ . '/../views/payment/checkout.php';
    }

    // Processar pagamento
    public function process() {
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: login.php');
            exit;
        }

        // Verificar se usuário é vendedor/brechó
        if (!$this->isUserSeller($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Apenas vendedores podem realizar pagamentos de anúncios.';
            header('Location: index.php');
            exit;
        }

        $id_produto = $_POST['id_produto'] ?? null;
        $metodo = $_POST['metodo_pagamento'] ?? null;

        if (!$id_produto || !$metodo) {
            $_SESSION['error'] = 'Dados inválidos.';
            header('Location: checkout.php?produto=' . $id_produto);
            exit;
        }

        // Verificar produto
        $produto = $this->product->findById($id_produto);
        if (!$produto) {
            $_SESSION['error'] = 'Produto não encontrado.';
            header('Location: index.php');
            exit;
        }

        $taxa = $this->payment->getConfig('taxa_anuncio') ?: $this->config['taxa_anuncio'];
        
        // Criar transação
        $codigo_transacao = 'DC' . time() . rand(1000, 9999);
        
        $payment_data = [
            'id_usuario' => $_SESSION['user_id'],
            'id_produto' => $id_produto,
            'valor' => $taxa,
            'metodo_pagamento' => $metodo,
            'codigo_transacao' => $codigo_transacao,
            'gateway_id' => null
        ];

        $payment_id = $this->payment->create($payment_data);
        
        if (!$payment_id) {
            $_SESSION['error'] = 'Erro ao criar pagamento.';
            header('Location: checkout.php?produto=' . $id_produto);
            exit;
        }

        // Processar conforme método
        switch ($metodo) {
            case 'pix':
                $this->processPix($payment_id, $taxa, $codigo_transacao);
                break;
            case 'cartao':
                $this->processCard($payment_id, $taxa, $codigo_transacao);
                break;
            default:
                $_SESSION['error'] = 'Método de pagamento não suportado.';
                header('Location: checkout.php?produto=' . $id_produto);
                exit;
        }
    }

    // Processar PIX (simulado)
    private function processPix($payment_id, $valor, $codigo_transacao) {
        // Em produção, aqui seria integração com Mercado Pago ou similar
        $pix_code = $this->generatePixCode($valor, $codigo_transacao);
        
        $data = [
            'payment_id' => $payment_id,
            'codigo_transacao' => $codigo_transacao,
            'pix_code' => $pix_code,
            'valor' => $valor,
            'expira_em' => date('Y-m-d H:i:s', strtotime('+30 minutes'))
        ];

        include __DIR__ . '/../views/payment/pix.php';
    }

    // Processar Cartão (simulado)
    private function processCard($payment_id, $valor, $codigo_transacao) {
        // Simulação de processamento
        $success = rand(1, 10) > 2; // 80% de sucesso
        
        if ($success) {
            $this->payment->updateStatus($payment_id, 'pago', 'Pagamento aprovado - Simulação');
            $this->activateProduct($payment_id);
            
            $_SESSION['success'] = 'Pagamento aprovado! Seu anúncio foi publicado.';
            header('Location: pagamento-sucesso.php?id=' . $payment_id);
        } else {
            $this->payment->updateStatus($payment_id, 'falhou', 'Pagamento recusado - Simulação');
            $_SESSION['error'] = 'Pagamento recusado. Tente novamente.';
            header('Location: pagamento-erro.php?id=' . $payment_id);
        }
        exit;
    }

    // Confirmar pagamento PIX (simulado)
    public function confirmPix() {
        $payment_id = $_POST['payment_id'] ?? null;
        
        if (!$payment_id) {
            echo json_encode(['success' => false, 'message' => 'ID inválido']);
            exit;
        }

        $payment = $this->payment->findById($payment_id);
        if (!$payment || $payment['id_usuario'] != $_SESSION['user_id']) {
            echo json_encode(['success' => false, 'message' => 'Pagamento não encontrado']);
            exit;
        }

        // Simulação: 70% de chance de pagamento confirmado
        $confirmed = rand(1, 10) > 3;
        
        if ($confirmed) {
            $this->payment->updateStatus($payment_id, 'pago', 'PIX confirmado - Simulação');
            $this->activateProduct($payment_id);
            echo json_encode(['success' => true, 'message' => 'Pagamento confirmado!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Pagamento ainda não identificado']);
        }
        exit;
    }

    // Ativar produto após pagamento
    private function activateProduct($payment_id) {
        $payment = $this->payment->findById($payment_id);
        if ($payment && $payment['id_produto']) {
            // Atualizar produto para ativo e pago
            $database = new Database();
            $db = $database->getConnection();
            
            $query = "UPDATE produtos 
                      SET status = 'Ativo', status_pagamento = 'pago', id_pagamento = :payment_id 
                      WHERE id = :id_produto";
            
            $stmt = $db->prepare($query);
            $stmt->bindParam(':payment_id', $payment_id);
            $stmt->bindParam(':id_produto', $payment['id_produto']);
            $stmt->execute();

            // Enviar notificação (se sistema existir)
            $this->sendNotification($payment['id_usuario'], $payment['id_produto']);
        }
    }

    // Gerar código PIX simulado
    private function generatePixCode($valor, $codigo_transacao) {
        // Código PIX simulado para demonstração
        return "00020126580014br.gov.bcb.pix0136" . md5($codigo_transacao) . "5204000053039865802BR5913DressCode6009SAO PAULO62070503***6304" . substr(md5($valor), 0, 4);
    }

    // Enviar notificação
    private function sendNotification($user_id, $product_id) {
        // Integração com sistema de notificações se existir
        try {
            if (class_exists('NotificationController')) {
                $notification = new NotificationController();
                $notification->create($user_id, 'Pagamento Confirmado', 'Seu anúncio foi publicado com sucesso!', 'pagamento');
            }
        } catch (Exception $e) {
            // Ignorar erro de notificação
        }
    }

    // Histórico de pagamentos
    public function history() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php');
            exit;
        }

        $pagamentos = $this->payment->getByUser($_SESSION['user_id'], 20);
        
        $data = ['pagamentos' => $pagamentos];
        include __DIR__ . '/../views/payment/history.php';
    }

    // Verificar se usuário é vendedor/brechó
    private function isUserSeller($user_id) {
        try {
            $database = new Database();
            $db = $database->getConnection();
            
            // Verificar se tem campo quero_vender na tabela usuarios
            $stmt = $db->prepare("SELECT quero_vender FROM usuarios WHERE id = ?");
            $stmt->execute([$user_id]);
            $user = $stmt->fetch();
            
            return $user && $user['quero_vender'] == 1;
        } catch (Exception $e) {
            // Se não existir o campo, assumir que todos podem vender
            return true;
        }
    }
}