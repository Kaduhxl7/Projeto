<?php
require_once __DIR__ . '/../ProdutoSearchInterface.php';

class FiltroPrecoMaxDecorator implements ProdutoSearchInterface {
    private ProdutoSearchInterface $busca;
    private float $precoMax;
    
    public function __construct(ProdutoSearchInterface $busca, float $precoMax) {
        $this->busca = $busca;
        $this->precoMax = $precoMax;
    }
    
    public function getSQL(): string {
        return $this->busca->getSQL() . " AND p.preco <= :preco_max";
    }
    
    public function getParams(): array {
        $params = $this->busca->getParams();
        $params[':preco_max'] = $this->precoMax;
        return $params;
    }
}
?>