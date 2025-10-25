<?php
require_once __DIR__ . '/../ProdutoSearchInterface.php';

class FiltroCorDecorator implements ProdutoSearchInterface {
    private ProdutoSearchInterface $busca;
    private string $cor;
    
    public function __construct(ProdutoSearchInterface $busca, string $cor) {
        $this->busca = $busca;
        $this->cor = $cor;
    }
    
    public function getSQL(): string {
        return $this->busca->getSQL() . " AND p.cor LIKE :cor";
    }
    
    public function getParams(): array {
        $params = $this->busca->getParams();
        $params[':cor'] = '%' . $this->cor . '%';
        return $params;
    }
}
?>