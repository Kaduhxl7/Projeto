<?php
require_once __DIR__ . '/../models/TermoAceite.php';

/**
 * Repository TermoRepository
 * Gerencia operações de banco de dados para aceite de termos
 */
class TermoRepository {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Verifica se o usuário já aceitou os termos
     */
    public function verificarAceite($id_usuario) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM aceite_termos 
            WHERE id_usuario = ? AND termos_aceitos = 1
        ");
        $stmt->execute([$id_usuario]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result ? new TermoAceite($result) : null;
    }

    /**
     * Registra o aceite dos termos
     */
    public function registrarAceite(TermoAceite $termo) {
        $stmt = $this->pdo->prepare("
            INSERT INTO aceite_termos 
            (id_usuario, termos_aceitos, data_aceite, assinatura, ip_address, user_agent)
            VALUES (?, ?, NOW(), ?, ?, ?)
            ON DUPLICATE KEY UPDATE 
            termos_aceitos = VALUES(termos_aceitos),
            data_aceite = VALUES(data_aceite),
            assinatura = VALUES(assinatura),
            ip_address = VALUES(ip_address),
            user_agent = VALUES(user_agent)
        ");
        
        return $stmt->execute([
            $termo->getIdUsuario(),
            $termo->getTermosAceitos(),
            $termo->getAssinatura(),
            $termo->getIpAddress(),
            $termo->getUserAgent()
        ]);
    }

    /**
     * Busca aceite por ID de usuário
     */
    public function buscarPorUsuario($id_usuario) {
        $stmt = $this->pdo->prepare("SELECT * FROM aceite_termos WHERE id_usuario = ?");
        $stmt->execute([$id_usuario]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result ? new TermoAceite($result) : null;
    }
}
