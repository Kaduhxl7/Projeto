<?php
require_once '../app/config/database.php';

class SecurityController {
    private $conn;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    // Log de ações de segurança
    public function logAction($usuario_id, $acao, $dados_acessados = null) {
        $sql = "INSERT INTO logs_seguranca (usuario_id, acao, ip_address, user_agent, dados_acessados) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            $usuario_id,
            $acao,
            $this->getClientIP(),
            $_SERVER['HTTP_USER_AGENT'] ?? '',
            $dados_acessados
        ]);
    }
    
    // Log de tentativas de login
    public function logLoginAttempt($email, $sucesso) {
        $sql = "INSERT INTO tentativas_login (email, ip_address, sucesso) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$email, $this->getClientIP(), $sucesso ? 1 : 0]);
        
        // Verificar tentativas suspeitas
        $this->checkSuspiciousActivity($email);
    }
    
    // Verificar atividade suspeita
    private function checkSuspiciousActivity($email) {
        $sql = "SELECT COUNT(*) FROM tentativas_login WHERE email = ? AND sucesso = 0 AND created_at > DATE_SUB(NOW(), INTERVAL 15 MINUTE)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$email]);
        $tentativas = $stmt->fetchColumn();
        
        if ($tentativas >= 5) {
            $this->logAction(null, 'TENTATIVAS_SUSPEITAS', "Email: $email, IP: " . $this->getClientIP());
        }
    }
    
    // Gerenciar consentimentos LGPD
    public function salvarConsentimento($usuario_id, $tipo, $consentimento) {
        $sql = "INSERT INTO consentimentos_lgpd (usuario_id, tipo_consentimento, consentimento_dado, ip_address) 
                VALUES (?, ?, ?, ?) 
                ON DUPLICATE KEY UPDATE consentimento_dado = ?, ip_address = ?, updated_at = NOW()";
        $stmt = $this->conn->prepare($sql);
        $ip = $this->getClientIP();
        $stmt->execute([$usuario_id, $tipo, $consentimento, $ip, $consentimento, $ip]);
        
        $this->logAction($usuario_id, 'CONSENTIMENTO_' . strtoupper($tipo), $consentimento ? 'DADO' : 'REVOGADO');
    }
    
    // Solicitar dados pessoais
    public function solicitarDados($usuario_id, $tipo_solicitacao) {
        $sql = "INSERT INTO solicitacoes_dados (usuario_id, tipo_solicitacao) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$usuario_id, $tipo_solicitacao]);
        
        $this->logAction($usuario_id, 'SOLICITACAO_' . strtoupper($tipo_solicitacao));
        
        return ['status' => 'success', 'message' => 'Solicitação registrada. Processaremos em até 15 dias úteis.'];
    }
    
    // Exportar dados do usuário
    public function exportarDados($usuario_id) {
        $dados = [];
        
        // Dados do usuário
        $sql = "SELECT id, email, nome, telefone, data_nascimento, genero, created_at FROM usuarios WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$usuario_id]);
        $dados['usuario'] = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Favoritos
        $sql = "SELECT p.nome, f.created_at FROM favoritos f JOIN produtos p ON f.produto_id = p.id WHERE f.usuario_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$usuario_id]);
        $dados['favoritos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Avaliações
        $sql = "SELECT p.nome, a.nota, a.comentario, a.created_at FROM avaliacoes a JOIN produtos p ON a.produto_id = p.id WHERE a.usuario_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$usuario_id]);
        $dados['avaliacoes'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Configurações
        $sql = "SELECT * FROM configuracoes_usuario WHERE usuario_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$usuario_id]);
        $dados['configuracoes'] = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $this->logAction($usuario_id, 'EXPORTACAO_DADOS');
        
        return $dados;
    }
    
    // Excluir dados do usuário
    public function excluirDados($usuario_id) {
        try {
            $this->conn->beginTransaction();
            
            // Excluir dados relacionados (CASCADE já cuida de alguns)
            $tabelas = ['favoritos', 'avaliacoes', 'configuracoes_usuario', 'consentimentos_lgpd', 'logs_seguranca', 'solicitacoes_dados'];
            
            foreach ($tabelas as $tabela) {
                $sql = "DELETE FROM $tabela WHERE usuario_id = ?";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([$usuario_id]);
            }
            
            // Excluir usuário
            $sql = "DELETE FROM usuarios WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$usuario_id]);
            
            $this->conn->commit();
            
            $this->logAction($usuario_id, 'EXCLUSAO_DADOS_COMPLETA');
            
            return ['status' => 'success', 'message' => 'Dados excluídos permanentemente.'];
            
        } catch (Exception $e) {
            $this->conn->rollback();
            return ['status' => 'error', 'message' => 'Erro ao excluir dados.'];
        }
    }
    
    // Obter IP do cliente
    private function getClientIP() {
        $ipKeys = ['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'];
        
        foreach ($ipKeys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }
    
    // Criptografar dados sensíveis
    public function encryptData($data) {
        $key = 'dresscode_security_key_2024'; // Em produção, usar variável de ambiente
        return openssl_encrypt($data, 'AES-256-CBC', $key, 0, substr(hash('sha256', $key), 0, 16));
    }
    
    // Descriptografar dados
    public function decryptData($encryptedData) {
        $key = 'dresscode_security_key_2024';
        return openssl_decrypt($encryptedData, 'AES-256-CBC', $key, 0, substr(hash('sha256', $key), 0, 16));
    }
}
?>