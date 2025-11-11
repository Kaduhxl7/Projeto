<?php
require_once __DIR__ . '/../ProdutoSearchInterface.php';

class FiltroPrecoMinDecorator implements ProdutoSearchInterface {
    private ProdutoSearchInterface $busca;
    private float $precoMin;
    
    public function __construct(ProdutoSearchInterface $busca, float $precoMin) {
        $this->busca = $busca;
        $this->precoMin = $precoMin;
    }
    
    public function getSQL(): string {
        return $this->busca->getSQL() . " AND p.preco >= :preco_min";
    }
    
    public function getParams(): array {
        $params = $this->busca->getParams();
        $params[':preco_min'] = $this->precoMin;
        return $params;
    }
}
?>