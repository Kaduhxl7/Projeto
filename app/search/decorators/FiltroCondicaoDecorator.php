<?php
require_once __DIR__ . '/../ProdutoSearchInterface.php';

class FiltroCondicaoDecorator implements ProdutoSearchInterface {
    private ProdutoSearchInterface $busca;
    private string $condicao;
    
    public function __construct(ProdutoSearchInterface $busca, string $condicao) {
        $this->busca = $busca;
        $this->condicao = $condicao;
    }
    
    public function getSQL(): string {
        return $this->busca->getSQL() . " AND p.condicao = :condicao";
    }
    
    public function getParams(): array {
        $params = $this->busca->getParams();
        $params[':condicao'] = $this->condicao;
        return $params;
    }
}
?>