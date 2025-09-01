<?php
require_once '../app/config/database.php';

class ConfiguracoesController {
    private $conn;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        if (!$this->conn) {
            throw new Exception('Erro na conexão com o banco de dados');
        }
    }
    
    public function getConfiguracoes($usuario_id) {
        $sql = "SELECT * FROM configuracoes_usuario WHERE usuario_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$usuario_id]);
        $config = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Se não existe configuração, criar uma padrão
        if (!$config) {
            $this->criarConfiguracaoPadrao($usuario_id);
            return $this->getConfiguracoes($usuario_id);
        }
        
        return $config;
    }
    
    public function atualizarConfiguracoes($usuario_id, $configuracoes) {
        try {
            $sql = "UPDATE configuracoes_usuario SET 
                    tema = ?, 
                    cor_primaria = ?, 
                    tamanho_fonte = ?, 
                    layout = ?, 
                    produtos_por_pagina = ?, 
                    mostrar_precos = ?, 
                    notificacoes = ?
                    WHERE usuario_id = ?";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                $configuracoes['tema'],
                $configuracoes['cor_primaria'],
                $configuracoes['tamanho_fonte'],
                $configuracoes['layout'],
                $configuracoes['produtos_por_pagina'],
                $configuracoes['mostrar_precos'] ? 1 : 0,
                $configuracoes['notificacoes'] ? 1 : 0,
                $usuario_id
            ]);
            
            return ['status' => 'success', 'message' => 'Configurações salvas com sucesso!'];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Erro ao salvar configurações'];
        }
    }
    
    private function criarConfiguracaoPadrao($usuario_id) {
        $sql = "INSERT INTO configuracoes_usuario (usuario_id) VALUES (?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$usuario_id]);
    }
}
?>