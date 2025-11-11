<?php
require_once __DIR__ . '/../repositories/TermoRepository.php';
require_once __DIR__ . '/../models/TermoAceite.php';

/**
 * Controller TermoController
 * Gerencia as ações relacionadas ao aceite de termos
 */
class TermoController {
    private $repository;

    public function __construct($pdo) {
        $this->repository = new TermoRepository($pdo);
    }

    /**
     * Verifica se o usuário aceitou os termos
     */
    public function verificarAceite($id_usuario) {
        return $this->repository->verificarAceite($id_usuario);
    }

    /**
     * Registra o aceite dos termos via AJAX
     */
    public function registrarAceite() {
        header('Content-Type: application/json');
        
        try {
            // Validar sessão
            if (!isset($_SESSION['user_id'])) {
                throw new Exception('Usuário não autenticado');
            }

            // Receber dados JSON
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Validar dados
            if (!isset($data['aceito']) || !$data['aceito']) {
                throw new Exception('É necessário aceitar os termos');
            }

            if (empty($data['assinatura'])) {
                throw new Exception('Assinatura é obrigatória');
            }

            // Criar objeto TermoAceite
            $termo = new TermoAceite();
            $termo->setIdUsuario($_SESSION['user_id']);
            $termo->setTermosAceitos(true);
            $termo->setAssinatura($data['assinatura']);
            $termo->setIpAddress($_SERVER['REMOTE_ADDR'] ?? 'unknown');
            $termo->setUserAgent($_SERVER['HTTP_USER_AGENT'] ?? 'unknown');

            // Registrar no banco
            $sucesso = $this->repository->registrarAceite($termo);

            if ($sucesso) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Termos aceitos com sucesso!'
                ]);
            } else {
                throw new Exception('Erro ao registrar aceite');
            }

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
