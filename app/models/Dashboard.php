<?php

class Dashboard {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    public function getVendasResumo($vendedor_id, $periodo = null) {
        $where_periodo = "";
        $params = [$vendedor_id];
        
        if ($periodo) {
            switch ($periodo) {
                case '7dias':
                    $where_periodo = " AND data_venda >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
                    break;
                case '30dias':
                    $where_periodo = " AND data_venda >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
                    break;
                case '90dias':
                    $where_periodo = " AND data_venda >= DATE_SUB(NOW(), INTERVAL 90 DAY)";
                    break;
            }
        }
        
        $sql = "SELECT 
                    COUNT(*) as total_vendas,
                    SUM(valor) as total_faturamento,
                    SUM(valor) as total_lucro,
                    AVG(valor) as ticket_medio
                FROM vendas 
                WHERE id_vendedor = ? AND status = 'confirmada'" . $where_periodo;
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getVendasPorMes($vendedor_id) {
        $sql = "SELECT 
                    DATE_FORMAT(data_venda, '%Y-%m') as mes,
                    COUNT(*) as quantidade,
                    SUM(valor) as valor_total
                FROM vendas 
                WHERE id_vendedor = ? AND status = 'confirmada'
                    AND data_venda >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
                GROUP BY DATE_FORMAT(data_venda, '%Y-%m')
                ORDER BY mes";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$vendedor_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getVendasPorCategoria($vendedor_id) {
        $sql = "SELECT 
                    c.nome as categoria,
                    COUNT(*) as quantidade,
                    SUM(v.valor) as valor_total
                FROM vendas v
                JOIN produtos p ON v.id_produto = p.id
                JOIN categorias c ON p.categoria_id = c.id
                WHERE v.id_vendedor = ? AND v.status = 'confirmada'
                GROUP BY c.id, c.nome
                ORDER BY quantidade DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$vendedor_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getVendasRecentes($vendedor_id, $limit = 10) {
        $limit = (int) $limit; // Garantir que é inteiro
        
        $sql = "SELECT 
                    v.*,
                    p.nome as produto_nome,
                    u.nome as comprador_nome
                FROM vendas v
                JOIN produtos p ON v.id_produto = p.id
                LEFT JOIN usuarios u ON v.id_comprador = u.id
                WHERE v.id_vendedor = ?
                ORDER BY v.data_venda DESC
                LIMIT $limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$vendedor_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getProdutosAtivos($vendedor_id) {
        $sql = "SELECT COUNT(*) as total
                FROM produtos 
                WHERE brecho_id = ? AND status = 'Ativo'";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$vendedor_id]);
        return $stmt->fetchColumn();
    }
    
    public function getCrescimentoMensal($vendedor_id) {
        $sql = "SELECT 
                    SUM(CASE WHEN MONTH(data_venda) = MONTH(NOW()) AND YEAR(data_venda) = YEAR(NOW()) THEN valor ELSE 0 END) as mes_atual,
                    SUM(CASE WHEN MONTH(data_venda) = MONTH(NOW()) - 1 AND YEAR(data_venda) = YEAR(NOW()) THEN valor ELSE 0 END) as mes_anterior
                FROM vendas 
                WHERE id_vendedor = ? AND status = 'confirmada'";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$vendedor_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result['mes_anterior'] > 0) {
            $crescimento = (($result['mes_atual'] - $result['mes_anterior']) / $result['mes_anterior']) * 100;
            return round($crescimento, 1);
        }
        
        return 0;
    }
}
?>